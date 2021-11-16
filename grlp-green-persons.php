<?php
/**
 * Plugin Name: Grüne Personen 
 * Description: Ein Plugin zur Verwaltung von Personen auf GRÜNEN Webseiten. Es ermöglicht Personen anzulegen und sie in Abteilungen zu gruppieren. Sie können dann in verschiedenen Kontexten (Team, Landesliste...) dargestellt werden. Das Plugin arbeitet sehr direkt mit dem <a href="http://sunflower-theme.de">Sunflower-Theme</a> zusammen und basiert auf der Idee der Personen Verwaltung im <a href="https://github.com/kre8tiv/Joseph-knows-best">JKB-Theme</a>.
 * Version: 1.0
 * Author: Marc Dietz 
 * Author URI: mailto:technik@gruene-rlp.de
 * Text Domain: green-persons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create GRLP settings menu
 * 
 * Register the settings menu only for admins
 * 
 * @return None
 * 
 */
add_action( 'admin_menu', 'grlp_add_menu_item' );
function grlp_add_menu_item()
{
    add_menu_page(
        'GRLP Settings Page',
        'GRLP Settings',
        'manage_options',
        'grlp-options',
        'grlp_settings_page',
        'dashicons-smiley',
        99
    );
}

/**
 * HTML page of GRLP settings menu
 * 
 * @todo This is just for learning - maybe we won't even need 
 *       a settings page, or at least not in this plugin :)
 * 
 * @return None
 * 
 */
function grlp_settings_page()
{
    $args = array(
        'numberposts'   => -1,
        'post_type'     => 'grlp_person',
        'post_status'   => 'publish',
        // 'abteilung'     => 'lgs',
        'tax_query'     => array(
            array(
                'taxonomy'      => 'abteilung',
                'field'         => 'slug',
                'terms'         => 'lgs',
            )
        ),
    );

    $my_posts = get_posts($args);

    echo "<pre>";
    print_r($my_posts);
    echo "<h2>Team-LGS:</h2>";
    foreach ($my_posts as $post) {
        echo $post->post_title . " " . $post->ID . "\n";
    }

    $marcs_meta = get_post_meta( 5513 );
    $marcs_www = get_post_meta( 5513, 'grlp_person_contact_www', true );

    echo get_the_post_thumbnail(5513);

    print_r($marcs_www);
    print_r($marcs_meta);

    echo "</pre>";

    echo "<h1>Grüne Personen Einstellungen</h1>";
    echo '<p>Hier kann ich ganz cool irgendwelche Testvariablen oder ähnliches ausgeben, was sehr praktisch ist. :)</p>';
    echo '<p>In Zukunft kann das dann eine Seite werden, auf der z.B. auch Tutorials zur Verwendung von unserem Wordpress verlinkt sein könnten.</p>';
    echo '<h2>Überschrift zweiten Grades.</h2>';
}

/**
 * Load textdomain
 * 
 * We write the source in english and provide a german
 * translation file.
 * 
 * @return None
 *
 */
add_action( 'plugins_loaded', 'grlp_gp_load_textdomain' );
function grlp_gp_load_textdomain()
{
    load_plugin_textdomain(
        'green_persons',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}


/**
 * Create 'grlp_person' custom post type
 * 
 * @return None
 *
 */
add_action( 'init', 'grlp_create_person_post_type' );
function grlp_create_person_post_type()
{
    $labels = array(
        'menu_position' => 5,
        'name'                => _x('Persons', 'post type general name', 'green_persons'),
        'singular_name'       => _x('Person', 'post type singular name', 'green_persons'),
        'add_new'             => _x('Add new', 'person', 'green_persons'),
        'add_new_item'        => __('Add new person', 'green_persons'),
        'edit_item'           => __('Edit person', 'green_persons'),
        'new_item'            => __('New person', 'green_persons'),
        'view_item'           => __('View person', 'green_persons'),
        'search_items'        => __('Search person', 'green_persons'),
        'not_found'           => __('No person found', 'green_persons'),
        'not_found_in_trash'  => __('No person found in trash', 'green_persons'),
        'all_items'           => __('All persons', 'green_persons'),
        'parent_item_colon'   => '',
    );
    $supports = array( 'title', 'editor', 'revisions', 'thumbnail' );

    register_post_type(
        'grlp_person',
        array(
            'labels'       => $labels,
            'public'       => true,
            'menu_icon'    => 'dashicons-id-alt',
            'supports'     => $supports,
            'rewrite'      => array('slug' => 'gruene-personen'),
            'show_in_rest' => true,
        )
    );
}


/**
 * Create taxonomy for 'grlp_persons'
 *
 * @return None
 *
 */
add_action( 'init', 'grlp_create_person_taxonomy' );
function grlp_create_person_taxonomy()
{
    $labels = array(
        'name'              => _x('Divisions', 'taxonomy general name', 'green_persons'),
        'singular_name'     => _x('Division', 'taxonomy singular name', 'green_persons'),
        'search_items'      => __('Search division', 'green_persons'),
        'all_items'         => __('All divisions', 'green_persons'),
        'parent_item'       => __('Parent division', 'green_persons'),
        'parent_item_colon' => __('Parent division:', 'green_persons'),
        'edit_item'         => __('Edit division', 'green_persons'),
        'update_item'       => __('Update division', 'green_persons'),
        'add_new_item'      => __('Add new division', 'green_persons'),
        'new_item_name'     => __('Name of division', 'green_persons'),
        'menu_name'         => _x('Divisions', 'menu name', 'green_persons'),
    );

    $args = array(
        'labels'       => $labels,
        'description'  => __('You can build groups of people inside divisions.', 'green_persons'),
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        // 'show_admin_column' => true,
        // 'query_var'         => true,
    );
    register_taxonomy( 'abteilung', array('grlp_person'), $args );
}

/**
 * Change columns on admin-page of grlp_person
 *
 * Add column 'Abteilung' to show all custom taxonomies the person
 * is part of. Rename 'title' column to 'Name of person'
 *
 * @return None
 *
 */
add_filter( 'manage_grlp_person_posts_columns', function ( $columns ) { 
    $columns['title'] = 'Name der Person';
    return array_merge(
        $columns, [
            'abteilung' => __('Abteilung', 'green_persons'),
        ]
    );
});


/**
 * Print the terms as links. They GET all persons that belong to that
 * particular taxonomy term. 
 *
 * @return None
 *
 */
add_action( 'manage_grlp_person_posts_custom_column', function( $column_key, $post_id ) {
	if ( $column_key == 'abteilung' ) {
        $term_obj_list = get_the_terms( $post_id, 'abteilung' );
        $num_items = ! empty($term_obj_list) ? count($term_obj_list) : 0;
        if ( $num_items > 0 ){
            for( $i=0; $i < $num_items; $i++ ){
                echo '<a href="/wp-admin/edit.php?abteilung='.$term_obj_list[$i]->slug.'&post_type=grlp_person">'.$term_obj_list[$i]->name.'</a>';
                if ( $i < $num_items -1 ){
                    echo', ';
                }
            }
        }
        else {
            echo 'keine Abteilung';
        }
	}
}, 10, 2 );


/**
 * Load a custom template for displaying the detailed person site
 *
 * @return None
 *
 */
add_filter( 'single_template', 'grlp_load_single_person_template' );
function grlp_load_single_person_template( $template )
{
    global $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'grlp_person' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-grlp_person.php' )) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-grlp_person.php';
        }
    }
    return $template;
}

// TODO: learn about this function and how to really use it
// flush_rewrite_rules();


/**
 * Add shortcodes
 *
 * @return None
 *
 */
add_action( 'init', 'grlp_shortcodes_init' );
function grlp_shortcodes_init()
{
    add_shortcode( 'team', 'grlp_team_anzeigen' );
}

function grlp_team_anzeigen( $atts, $content, $shortcode_tag )
{
    $posts = array();
    $o = '';
    if ( ! empty( $atts )) {
        if ( isset( $atts['abteilung'] )) {
            $posts = get_posts(
                array(
                    'post_type'     => 'grlp_person',
                    'numberposts'   => -1,
                    'abteilung'     => $atts['abteilung'],
                    // 'post_status'   => 'publish',
                )
            );
        }
    }

    $count = 0;
    $num_of_posts = sizeof( $posts );
    $num_of_columns = @absint( $atts['cols'] ) > 0 ? absint( $atts['cols'] ) : 3;

    $o .= '<div class="wp-block-columns mb-4">' . "\n";
    foreach ( $posts as $post ) {
        $o .= '<div class="wp-block-column">' . "\n";
        $o .= '<div class="wp-block-media-text alignwide is-stacked-on-mobile person has-shadow">' . "\n";
        $o .= '<figure class="wp-block-media-text__media">' . "\n";
        $o .= get_the_post_thumbnail( $post->ID );
        $o .= '</figure>' . "\n";
        $o .= '<div class="wp-block-media-text__content">' . "\n";
        $o .= '<p class="person-name">' . $post->post_title . '</p>' . "\n";
        // FIXME: just for testing we show the url here, but we want to show the persons function.
        $o .= '<p class="person-description">' . get_post_meta( $post->ID, 'grlp_person_contact_www', true ) . '</p>' . "\n";
        $o .= '<div class="wp-block-group d-flex p-0"><div class="wp-block-group__inner-container">' . "\n";
        // $o .= '<a href="">E-Mail</a>' . "\n";
        $o .= '</div></div>' . "\n";
        $o .= '</div>';
        $o .= '</div>';
        $o .= '</div>' . "\n";

        $count++;
        if ( $count % $num_of_columns == 0 ) {
            $o .= '</div>' . "\n";
            if ( $count < $num_of_posts ) {
                $o .= '<div class="wp-block-columns mb-4">' . "\n";
            }
        }
    }
    if ( $count % $num_of_columns != 0 ) {
        for ( $i = $count % $num_of_columns; $i < $num_of_columns; $i++ ) {
            $o .= '<div class="wp-block-column"></div>' . "\n";
        }
        $o .= '</div>' . "\n";
    }
    return $o;
}


//TODO: move to uninstall.php
function grlp_uninstall_plugin()
{
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
}
register_uninstall_hook( __FILE__, 'grlp_uninstall_plugin' );


/**
 * Add meta boxes
 *
 * Have 2 MetaBoxes for a person. One with contact information and 
 * another with additional and political infos.
 *
 * @return None
 * 
 **/
add_action( 'add_meta_boxes_grlp_person', 'grlp_register_meta_boxes' );
function grlp_register_meta_boxes( $post )
{
    global $post;
    // The following is only relevant if we want to add different meta
    // boxes with different page-templates. 
    // $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);

    add_meta_box( 'grlp_person_contact', __( 'Kontaktdaten' ), 'grlp_person_contact_view', 'grlp_person', 'normal', 'high' );
    add_meta_box( 'grlp_person_detail', __( 'Infos & Ämter' ), 'grlp_person_detail_view', 'grlp_person', 'normal', 'high' );

    // FIXME: this is a description of all allowed arguments for 'register_post_meta'
    //        We want to get rid of it at times.
    // $args = [
    //     "type" 				=> "string, boolean, integer, number",
    //     "description" 		=> "human readable string that may be used",
    //     "single" 			=> 'true / false; whether single value of data or array',
    //     "sanitize_callback" => 'cb function for sanitizing metadata',
    //     "auth_callback" 	=> 'cb function to run meta capability checks to determine whether the user can add, edit or delete',
    //     "show_in_rest" 		=> 'Whether the metadata is considered public and can be shown via the REST API'
    // ];

    register_post_meta( 'grlp_person', 'grlp_person_contact_www', [
        'type'              => 'string',
        'description'       => __( 'URL der Website der Person (inklusive http...)', 'green_person' ),
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return esc_url_raw( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_email', [
        'description'       => __( 'E-Mail Adresse der Person', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return sanitize_email( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_twitter', [
        'description'       => __( 'Twitter Name der Person (ohne @!)', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return esc_url_raw( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_facebook', [
        'description'       => __( 'Vollstängiger Link zum Facebook Profil', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return esc_url_raw( $value );
        }
    ]);
    register_post_meta( 'grlp_person', 'grlp_person_contact_instagram', [
        'description'       => __( 'Vollstängiger Link zum Instagram Profil', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return esc_url_raw( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_address', [
        'description'       => __( 'Platz für Anschrift, Fax oder zusätzl. Tel', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return wp_strip_all_tags( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_phone', [
        'description'       => __( 'Telefonnummer Form: (06131) 89 243 00', 'green_person' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return wp_strip_all_tags( $value );
        }
    ]);

    register_post_meta( 'grlp_person', 'grlp_person_contact_mobile', [
        'description'       => __( 'Mobilfunk Nummer Form: (0176) 123 456 789' ),
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => function ( $value ) {
            return wp_strip_all_tags( $value );
        }
    ]);

    // ------------- Person Detail ----------------------------------------
    
    // register_post_meta( 'grlp_person', 'grlp_person_detail_', [
    //     'description'       => __( '' ),
    //     'type'              => 'string',
    //     'single'            => true,
    //     'show_in_rest'      => true,
    //     'sanitize_callback' => function ( $value ) {
    //         return wp_strip_all_tags( $value );
    //     }
    // ]);

    // $job = isset($values['grlp_person_detail_job']) ? esc_attr($values['grlp_person_detail_job'][0]) : '';
    // $list_pos = isset($values['grlp_person_detail_list_pos']) ? esc_attr($values['grlp_person_detail_list_pos'][0]) : '';
    // $custom_order = isset($values['grlp_person_detail_custom_order']) ? esc_attr($values['grlp_person_detail_custom_order'][0]) : '';
    // $constituency = isset($values['grlp_person_detail_constituency']) ? esc_attr($values['grlp_person_detail_constituency'][0]) : '';
    // $constit_num = isset($values['grlp_person_detail_constit_num']) ? esc_attr($values['grlp_person_detail_constit_num'][0]) : '';
    // $grlp_mandate = isset($values['grlp_person_detail_mandate']) ? esc_html($values['grlp_person_detail_mandate'][0]) : '';
    // $has_link_to_site = isset($
}

/**
 * View fields for 'grlp_person_contact' MetaBox
 *
 * @param $post Post data
 * @return None
 * 
 **/
function grlp_person_contact_view( $post )
{
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'grlp_person_contact_view', 'grlp_nonce_contact' );
    $values = get_post_custom( $post->ID );

    $www        = isset( $values['grlp_person_contact_www'] ) ? esc_attr( $values['grlp_person_contact_www'][0] ) : '';
    $email      = isset( $values['grlp_person_contact_email'] ) ? esc_attr( $values['grlp_person_contact_email'][0] ) : '';
    $facebook   = isset( $values['grlp_person_contact_facebook'] ) ? esc_attr( $values['grlp_person_contact_facebook'][0] ) : '';
    $twitter    = isset( $values['grlp_person_contact_twitter'] ) ? esc_attr( $values['grlp_person_contact_twitter'][0] ) : '';
    $instagram  = isset( $values['grlp_person_contact_instagram'] ) ? esc_attr( $values['grlp_person_contact_instagram'][0] ) : '';
    $mobile     = isset( $values['grlp_person_contact_mobile'] ) ? esc_attr( $values['grlp_person_contact_mobile'][0] ) : '';
    $phone      = isset( $values['grlp_person_contact_phone'] ) ? esc_attr( $values['grlp_person_contact_phone'][0] ) : '';
    $address    = isset( $values['grlp_person_contact_address'] ) ? $values['grlp_person_contact_address'][0] : '';
?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="grlp_person_contact_www">Website</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_www" id="grlp_person_contact_www" value="<?php echo $www; ?>" />
                    <br /><span class="description">Inklusive http:// Beispiel: http://domain.de.</span>
                </td>
                <th scope="row"><label for="grlp_person_contact_email">E-Mail</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_email" id="grlp_person_contact_email" value="<?php echo $email; ?>" />
                    <br /><span class="description">vorname.nachname@domain.de</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="grlp_person_contact_facebook">Facebook</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_facebook" id="grlp_person_contact_facebook" value="<?php echo $facebook; ?>" />
                    <br /><span class="description">Vollständiger Link zum Facebook-Profil, inkl. http://</span>
                </td>
                <th scope="row"><label for="grlp_person_contact_twitter">Twitter</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_twitter" id="grlp_person_contact_twitter" value="<?php echo $twitter; ?>" />
                    <br /><span class="description">Nur der Twitter-Nutzername ohne @, z.b. gruenenrw.</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="grlp_person_contact_instagram">Instagram</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_instagram" id="grlp_person_contact_instagram" value="<?php echo $instagram; ?>" />
                    <br /><span class="description">Vollständiger Link zum Instagram-Profil, inkl. http://</span>
                </td>

                <th scope="row"><label for="grlp_person_contact_address">Anschrift</label></th>
                <td>
                    <textarea name="grlp_person_contact_address" id="grlp_person_contact_address"><?php echo $address; ?></textarea>
                    <br /><span class="description">Platz für Anschrift, Telefon, Fax, etc.<br />(&lt;br /&gt; -tags für Zeilenumbruch verwenden)</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="grlp_person_contact_phone">Telefon</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_phone" id="grlp_person_contact_phone" value="<?php echo $phone; ?>" />
                    <br /><span class="description">Telefonnummer, Form: (01234) 89 243 -99</span>
                </td>

                <th scope="row"><label for="grlp_person_contact_mobile">Mobiltelefon</label></th>
                <td>
                    <input type="text" name="grlp_person_contact_mobile" id="grlp_person_contact_mobile" value="<?php echo $mobile; ?>" />
                    <br /><span class="description">Mobilfunknummer, Form: (0179) 12 345 678</span>
                </td>
            </tr>
        </tbody>
    </table>
<?php
}


add_action( 'save_post', 'grlp_person_contact_save' );
function grlp_person_contact_save( $post_id )
{
    // Bail if we're doing an auto save
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    {
        return;
    } 

    // if our nonce isn't there, or we can't verify it, bail
    if ( ! isset( $_POST['grlp_nonce_contact'] ) || ! wp_verify_nonce( $_POST['grlp_nonce_contact'], 'grlp_person_contact_view' ))
    {
        return;
    }

    // if our current user can't edit this post, bail
    if ( ! current_user_can( 'edit_post', $post_id )) 
    {
        return;
    }

    // Make sure your data is set before trying to save it
    if ( isset( $_POST['grlp_person_contact_www'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_www',
            esc_url_raw( $_POST['grlp_person_contact_www'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_email'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_email',
            sanitize_email( $_POST['grlp_person_contact_email'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_facebook'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_facebook',
            esc_url_raw( $_POST['grlp_person_contact_facebook'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_twitter'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_twitter',
            esc_url_raw ( $_POST['grlp_person_contact_twitter'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_instagram'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_instagram',
            esc_url_raw( $_POST['grlp_person_contact_instagram'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_phone'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_phone',
            wp_strip_all_tags( $_POST['grlp_person_contact_phone'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_mobile'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_mobile',
            wp_strip_all_tags( $_POST['grlp_person_contact_mobile'] )
        );
    }
    if ( isset( $_POST['grlp_person_contact_address'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_contact_address',
            wp_kses(
                $_POST['grlp_person_contact_address'],
                array ('a' => array('href'), 'br' => array())
            )
        );
    }
}

/**
 * View fields for 'grlp_person_details' MetaBox
 * 
 * @param $post Post data
 * @return None
 * 
 */
function grlp_person_detail_view( $post )
{
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'grlp_person_detail_view', 'grlp_nonce_view' );
    $values = get_post_custom( $post->ID );
    $job = isset( $values['grlp_person_detail_job'] ) ? esc_attr( $values['grlp_person_detail_job'][0] ) : '';
    $list_pos = isset( $values['grlp_person_detail_list_pos'] ) ? esc_attr( $values['grlp_person_detail_list_pos'][0] ) : '';
    $custom_order = isset( $values['grlp_person_detail_custom_order'] ) ? esc_attr( $values['grlp_person_detail_custom_order'][0] ) : '';
    $constituency = isset( $values['grlp_person_detail_constituency'] ) ? esc_attr( $values['grlp_person_detail_constituency'][0] ) : '';
    $constit_num = isset( $values['grlp_person_detail_constit_num'] ) ? esc_attr( $values['grlp_person_detail_constit_num'][0] ) : '';
    $grlp_mandate = isset( $values['grlp_person_detail_mandate'] ) ? esc_html( $values['grlp_person_detail_mandate'][0] ) : '';
    $has_link_to_site = isset( $values['grlp_person_detail_has_link'] ) ? esc_attr( $values['grlp_person_detail_has_link'][0] ) : 'no';
?>

    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="grlp_person_detail_job">Tätigkeit</label></th>
                <td>
                    <input type="text" name="grlp_person_detail_job" id="grlp_person_detail_job" value="<?php echo $job; ?>" />
                    <br /><span class="description">z.B. Pressesprecher*in, Landesvorsitzende [team]</span>
                </td>
                <th scope="row"><label for="grlp_person_detail_mandate">Mandat</label></th>
                <td>
                    <textarea type="text" name="grlp_person_detail_mandate" id="grlp_person_detail_mandate"><?php echo $grlp_mandate; ?></textarea>
                    <br /><span class="description">Mandat und Beschreibung, z.B. MdL, Sprecher für... [mandate]</span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="grlp_person_detail_list_pos">Listenplatz</label></th>
                <td>
                    <input type="text" name="grlp_person_detail_list_pos" id="grlp_person_detail_list_pos" value="<?php echo $list_pos; ?>" />
                    <br /><span class="description">Der Listenplatz, z.B. '2', '43' [gruene-liste]</span>
                </td>
                <th scope="row"><label for="grlp_person_detail_constituency">Wahlkreis</label></th>
                <td>
                    <p>
                        <input type="text" name="grlp_person_detail_constituency" id="grlp_person_detail_constituency" value="<?php echo $constituency; ?>" />
                        <br /><span class="description">Name (z.B. Koblenz)</span>
                    </p>
                    <p>
                        <input type="text" name="grlp_person_detail_constit_num" id="grlp_person_detail_constit_num" value="<?php echo $constit_num; ?>" />
                        <br /><span class="description">Wahlkreisnummer (z.B. 199)</span>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="grlp_person_detail_custom_order">Sortierung</label></th>
                <td>
                    <input type="text" name="grlp_person_detail_custom_order" id="grlp_person_detail_custom_order" value="<?php echo $custom_order; ?>" />
                    <br /><span class="description">Sortierreihenfolge [team],[mandate]</span>
                </td>
                <th scope="row"><label for="grlp_person_detail_has_link">Link zur Detailseite</label></th>
                <td>
                    <input type="checkbox" name="grlp_person_detail_has_link" id="grlp_person_detail_has_link" value="yes" <?php checked($has_link_to_site, 'yes'); ?> />
                    <br /><span class="description">Link zur Detailseite in der Übersicht anzeigen.</span>
                </td>
            </tr>
        </tbody>
    </table>

<?php
}


add_action( 'save_post', 'grlp_person_detail_save' );
function grlp_person_detail_save( $post_id )
{
    // Bail if we're doing an auto save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    } 

    // if our nonce isn't there, or we can't verify it, bail
    if ( ! isset( $_POST['grlp_nonce_view'] ) || ! wp_verify_nonce( $_POST['grlp_nonce_view'], 'grlp_person_detail_view' )) {
        return;  
    }

    // if our current user can't edit this post, bail
    if ( ! current_user_can( 'edit_post', $post_id )) {
        return;
    } 

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        ),
        'br' => array()
    );

    // Make sure your data is set before trying to save it
    if ( isset( $_POST['grlp_person_detail_job'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_job',
            esc_html( $_POST['grlp_person_detail_job'] )
        );
    }
    if ( isset( $_POST['grlp_person_detail_list_pos'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_list_pos',
            intval( $_POST['grlp_person_detail_list_pos'] )
        );
    }
    if ( isset( $_POST['grlp_person_detail_custom_order'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_custom_order',
            intval( $_POST['grlp_person_detail_custom_order'] )
        );
    }
    if ( isset( $_POST['grlp_person_detail_constituency'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_constituency',
            esc_html( $_POST['grlp_person_detail_constituency'] )
        );
    }
    if ( isset( $_POST['grlp_person_detail_constit_num'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_constit_num',
            intval( $_POST['grlp_person_detail_constit_num'] )
        );
    }
    if ( isset( $_POST['grlp_person_detail_mandate'] )) {
        update_post_meta(
            $post_id,
            'grlp_person_detail_mandate',
            wp_kses_post(
                $_POST['grlp_person_detail_mandate'],
                $allowed
            )
        );
    }
    if ( isset( $_POST['grlp_person_detail_has_link'] )) {
        update_post_meta( $post_id, 'grlp_person_detail_has_link', 'yes' );
    } else {
        update_post_meta( $post_id, 'grlp_person_detail_has_link', 'no' );
    }
}

function grlp_put_person()
{
    echo "<h1>Hi I'm a Person!";
}
