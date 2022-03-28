<?php
    // if uninstall.php is not called by WordPress, die
    if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
        die;
    }
    global $wbdb;

    $meta_keys = [
        'grlp_person_contact_www',
        'grlp_person_contact_email',
        'grlp_person_contact_twitter',
        'grlp_person_contact_facebook',
        'grlp_person_contact_instagram',
        'grlp_person_contact_address',
        'grlp_person_contact_phone',
        'grlp_person_contact_mobile',
        'grlp_person_detail_job',
        'grlp_person_detail_list_pos',
        'grlp_person_detail_custom_order_team',
        'grlp_person_detail_custom_order_detail',
        'grlp_person_detail_constituency',
        'grlp_person_detail_shortinfo',
        'grlp_person_detail_has_link',
    ];
    foreach ( $meta_keys as $meta_key ) {
        delete_metadata('post', null, $meta_key, null, true);
    }
    $args = array(
        'post_type' => 'grlp_person',
        'nopaging' => true
    );
    $query = new WP_Query($args);
    while ( $query->have_posts() ) {
        $query->the_post();
        $id = get_the_ID();
        wp_delete_post( $id, true );
    }
    wp_reset_postdata();

    // // Delete taxonomy
    // foreach ( array( 'abteilung' ) as $taxonomy ) {
    //     // Prepare & excecute SQL, Delete Terms
    //     $wpdb->get_results( $wpdb->prepare( "DELETE t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s')", $taxonomy ) );
        
    //     // Delete Taxonomy
    //     $wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
    // }

    /** Delete All the Taxonomies */
    foreach ( array( 'abteilung' ) as $taxonomy ) {
	
        // Prepare & excecute SQL
	$terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy ) );
  
        // Delete Terms
	if ( $terms ) {
		foreach ( $terms as $term ) {
            $wpdb->delete( $wpdb->term_relationships, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
			$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
			$wpdb->delete( $wpdb->terms, array( 'term_id' => $term->term_id ) );
			delete_option( $taxonomy.'_children' );
		}
	}
	
	// Delete Taxonomy
	$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
}

?>