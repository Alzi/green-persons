<?php 
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

?>