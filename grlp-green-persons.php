<?php
/**
 * Plugin Name: Grüne Personen 
 * Description: Ein Plugin zur Verwaltung von Personen auf GRÜNEN Webseiten. Es ermöglicht Personen anzulegen und sie in Abteilungen zu gruppieren. Sie können dann in verschiedenen Kontexten (Team, Landesliste...) dargestellt werden. Das Plugin arbeitet sehr direkt mit dem <a href="http://sunflower-theme.de">Sunflower-Theme</a> zusammen und basiert auf der Idee der Personen Verwaltung im <a href="https://github.com/kre8tiv/Joseph-knows-best">JKB-Theme</a>.
 * Version: 1.0
 * Author: Marc Dietz 
 * Author URI: mailto:technik@gruene-rlp.de
 * Text Domain: green-persons
 */

defined('ABSPATH') || exit;

// Init textdomain
add_action('plugins_loaded', 'grlp_gp_load_textdomain');
function grlp_gp_load_textdomain()
{
  load_plugin_textdomain(
    'green-persons',
    false,
    dirname(plugin_basename(__FILE__)) . '/languages'
  );
}


class GRLP_GruenePersonen
{
  public function __construct()
  {
    add_action('init', array($this, 'create_custom_post_type'));
    add_action('init', array($this, 'create_taxonomy_person'));
    add_filter('single_template', 'GRLP_GruenePersonen::load_person_template');
    flush_rewrite_rules();
  }

  public function create_custom_post_type()
  {
    $labels = array(
      'menu_position' => 5,
      'name'                => _x('Persons', 'post type general name', 'green-persons'),
      'singular_name'       => _x('Person', 'post type singular name', 'green-persons'),
      'add_new'             => _x('Add new', 'person', 'green-persons'),
      'add_new_item'        => __('Add new person', 'green-persons'),
      'edit_item'           => __('Edit person', 'green-persons'),
      'new_item'            => __('New person', 'green-persons'),
      'view_item'           => __('View person', 'green-persons'),
      'search_items'        => __('Search person', 'green-persons'),
      'not_found'           => __('No person found', 'green-persons'),
      'not_found_in_trash'  => __('No person found in trash', 'green-persons'),
      'all_items'           => __('All persons', 'green-persons'),
      'parent_item_colon'   => '',
    );
    $supports = array('title', 'editor', 'revisions', 'thumbnail');

    register_post_type(
      'grlp_person',
      array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-id-alt',
        'supports'     => $supports,
        'rewrite'      => array('slug' => 'person'),
        'show_in_rest' => true,
      )
    );
  }

  public function create_taxonomy_person()
  {
    $labels = array(
      'name'              => _x('Divisions', 'taxonomy general name', 'green-persons'),
      'singular_name'     => _x('Division', 'taxonomy singular name', 'green-persons'),
      'search_items'      => __('Search division', 'green-persons'),
      'all_items'         => __('All divisions', 'green-persons'),
      'parent_item'       => __('Parent division', 'green-persons'),
      'parent_item_colon' => __('Parent division:', 'green-persons'),
      'edit_item'         => __('Edit division', 'green-persons'),
      'update_item'       => __('Update division', 'green-persons'),
      'add_new_item'      => __('Add new division', 'green-persons'),
      'new_item_name'     => __('Name of division', 'green-persons'),
      'menu_name'         => _x('Divisions', 'menu name', 'green-persons'),
    );

    $args = array(
      'labels'       => $labels,
      'description'  => __('You can build groups of people inside divisions.', 'green-persons'),
      'hierarchical' => true,
      'show_ui'      => true,
      'show_in_rest' => true,
      // 'show_admin_column' => true,
      // 'query_var'         => true,
    );
    register_taxonomy('abteilung', array('grlp_person'), $args);
  }

  public static function load_person_template($template)
  {
    global $post;

    if ('grlp_person' === $post->post_type && locate_template(array('grlp_person')) !== $template) {
      return plugin_dir_path(__FILE__) . '/templates/single-grlp_person.php';
    }
    return $template;
  }
}

new GRLP_GruenePersonen;



/* TODO: Be consistent: either OOP-Style or procedural. <25-09-21, Marc> */
/* --------------------------------------------------------------------- */
function grlp_person_anzeigen($atts, $content, $shortcode_tag)
{
  $o = '';

  // $o = '<pre style="background-color:#fff;">';
  // $o.= 'Test, 1.2';
  // $o.= '</pre>';

  // $posts_personen = get_posts(
  //   array(
  //   'post_type'=>'grlp_person',
  //   'posts_per_page'=>-1,
  //   'post_id' => $ID,
  //   )
  // );

  // Person template file 
  ob_start();
  require_once('templates/partials/partial_person.php');
  $o .= ob_get_clean();

  // Handle enclosing shortcode tags
  if (!is_null($content)) {
    $o .= apply_filters('the_content', $content);
    $o .= do_shortcode($content);
  }
  return $o;
}

function grlp_shortcodes_init()
{
  add_shortcode('person-anzeigen', 'grlp_person_anzeigen');
}
add_action('init', 'grlp_shortcodes_init');


function grlp_uninstall_plugin()
{
  $args = array(
    'post_type' => 'grlp_person',
    'nopaging' => true
  );
  $query = new WP_Query($args);
  while ($query->have_posts()) {
    $query->the_post();
    $id = get_the_ID();
    wp_delete_post($id, true);
  }
  wp_reset_postdata();
}
register_uninstall_hook(__FILE__, 'grlp_uninstall_plugin');


// Übernommen von JKB ----------------------------------------------------------
add_action('add_meta_boxes', 'grlp_add');
function grlp_add()
{
  global $post;
  $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);
  add_meta_box('grlp_person_contact', 'Kontaktdaten', 'grlp_person_contact_view', 'grlp_person', 'normal', 'high');
  add_meta_box('grlp_person_position', 'Infos & Ämter', 'grlp_person_position_view', 'grlp_person', 'normal', 'high');
}


function grlp_person_contact_view($post)
{
  // $post is already set, and contains an object: the WordPress post
  global $post;
  $values = get_post_custom($post->ID);
  $www        = isset($values['grlp_person_contact_www']) ? esc_attr($values['grlp_person_contact_www'][0]) : '';
  $email      = isset($values['grlp_person_contact_email']) ? esc_attr($values['grlp_person_contact_email'][0]) : '';
  $facebook   = isset($values['grlp_person_contact_facebook']) ? esc_attr($values['grlp_person_contact_facebook'][0]) : '';
  $twitter    = isset($values['grlp_person_contact_twitter']) ? esc_attr($values['grlp_person_contact_twitter'][0]) : '';
  $instagram  = isset($values['grlp_person_contact_instagram']) ? esc_attr($values['grlp_person_contact_instagram'][0]) : '';
  $anschrift  = isset($values['grlp_person_contact_anschrift']) ? esc_html($values['grlp_person_contact_anschrift'][0]) : '';
  $telefon    = isset($values['grlp_person_contact_telefon']) ? esc_html($values['grlp_person_contact_telefon'][0]) : '';
  $selected   = isset($values['my_meta_box_select']) ? esc_attr($values['my_meta_box_select'][0]) : '';
  $check      = isset($values['my_meta_box_check']) ? esc_attr($values['my_meta_box_check'][0]) : '';

  // We'll use this nonce field later on when saving.
  wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');
?>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label for="grlp_person_contact_www">Website</label></th>
        <td><input type="text" name="grlp_person_contact_www" id="grlp_person_contact_www" value="<?php echo $www; ?>" /><br><span class="description">Inklusive http:// Beispiel: http://domain.de.</span></td>

        <th scope="row"><label for="grlp_person_contact_email">E-Mail</label></th>
        <td><input type="text" name="grlp_person_contact_email" id="grlp_person_contact_email" value="<?php echo $email; ?>" /><br><span class="description">vorname.nachname@domain.de</span></td>
      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_contact_facebook">Facebook</label></th>
        <td><input type="text" name="grlp_person_contact_facebook" id="grlp_person_contact_facebook" value="<?php echo $facebook; ?>" /><br><span class="description">Vollständiger Link zum Facebook-Profil, inkl. http://</span></td>

        <th scope="row"><label for="grlp_person_contact_twitter">Twitter</label></th>
        <td><input type="text" name="grlp_person_contact_twitter" id="grlp_person_contact_twitter" value="<?php echo $twitter; ?>" /><br><span class="description">Nur der Twitter-Nutzername ohne @, z.b. gruenenrw.</span></td>
      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_contact_instagram">Instagram</label></th>
        <td><input type="text" name="grlp_person_contact_instagram" id="grlp_person_contact_instagram" value="<?php echo $instagram; ?>" /><br><span class="description">Vollständiger Link zum Instagram-Profil, inkl. http://</span></td>

        <th scope="row"></th>
        <td></td>
      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_contact_anschrift">Anschrift</label></th>
        <td><textarea name="grlp_person_contact_anschrift" id="grlp_person_contact_anschrift"><?php echo $anschrift; ?></textarea><br><span class="description">Platz für Anschrift, Telefon, Fax, etc.</span></td>

        <th scope="row"><label for="grlp_person_contact_telefon">Telefon</label></th>
        <td><input type="text" name="grlp_person_contact_telefon" id="grlp_person_contact_telefon" value="<?php echo $telefon; ?>" /><br><span class="description">Telefonnummer, Form: +49 (211) 222 333 -11</span></td>
      </tr>
    </tbody>
  </table>
<?php
}


add_action('save_post', 'grlp_person_contact_save');
function grlp_person_contact_save($post_id)
{
  // Bail if we're doing an auto save
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // if our nonce isn't there, or we can't verify it, bail
  if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce')) return;

  // if our current user can't edit this post, bail
  if (!current_user_can('edit_post', $post_id)) return;

  // now we can actually save the data
  // allow a tags and only their href attribute

  $allowed = array(
    'a' => array(
      'href' => array()
    )
  );

  // Make sure your data is set before trying to save it
  if (isset($_POST['grlp_person_contact_www'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_www',
      wp_kses($_POST['grlp_person_contact_www'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_contact_email'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_email',
      wp_kses($_POST['grlp_person_contact_email'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_contact_facebook'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_facebook',
      wp_kses($_POST['grlp_person_contact_facebook'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_contact_twitter'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_twitter',
      wp_kses(
        $_POST['grlp_person_contact_twitter'],
        $allowed
      )
    );
  }
  if (isset($_POST['grlp_person_contact_instagram'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_instagram',
      wp_kses(
        $_POST['grlp_person_contact_instagram'],
        $allowed
      )
    );
  }
  if (isset($_POST['grlp_person_contact_telefon'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_telefon',
      wp_kses(
        $_POST['grlp_person_contact_telefon'],
        $allowed
      )
    );
  }
  if (isset($_POST['grlp_person_contact_anschrift'])) {
    update_post_meta(
      $post_id,
      'grlp_person_contact_anschrift',
      esc_html($_POST['grlp_person_contact_anschrift'])
    );
  }
}

/** PERSONEN: Positionen **/

function grlp_person_position_view($post)
{
  // $post is already set, and contains an object: the WordPress post
  global $post;
  $values = get_post_custom($post->ID);
  $excerpt = isset($values['grlp_person_excerpt']) ? esc_html($values['grlp_person_excerpt'][0]) : '';
  $motivation = isset($values['grlp_person_motivation']) ? esc_html($values['grlp_person_motivation'][0]) : '';
  $amt = isset($values['grlp_person_pos_amt']) ? esc_attr($values['grlp_person_pos_amt'][0]) : '';
  $listenplatz = isset($values['grlp_person_pos_listenplatz']) ? esc_attr($values['grlp_person_pos_listenplatz'][0]) : '';
  $sortierung = isset($values['grlp_person_pos_sortierung']) ? esc_attr($values['grlp_person_pos_sortierung'][0]) : '';
  $wahlkreis = isset($values['grlp_person_pos_wahlkreis']) ? esc_attr($values['grlp_person_pos_wahlkreis'][0]) : '';
  $grlp_person_pos_details = isset ($values["grlp_person_pos_details"]) ? esc_attr($values['grlp_person_pos_details'][0]) : 'no';


  // We'll use this nonce field later on when saving.
  wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');
?>
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label for="grlp_person_excerpt">Kurzbeschreibung</label></th>
        <td><textarea name="grlp_person_excerpt" id="grlp_person_excerpt"><?php echo $excerpt; ?></textarea><br><span class="description">Kurzbeschreibung, im Idealfall in 140 Zeichen.</span></td>

        <th scope="row"><label for="grlp_person_motivation">Motivation</label></th>
        <td><textarea name="grlp_person_motivation" id="grlp_person_motivation"><?php echo $motivation; ?></textarea><br><span class="description">Motivation (für Kandidat*innen).</span></td>

      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_pos_amt">Amt/Mandat</label></th>
        <td><input type="text" name="grlp_person_pos_amt" id="grlp_person_pos_amt" value="<?php echo $amt; ?>" /><br><span class="description">Sprecherfunktion, o.Ä.</span></td>

        <th scope="row"><label for="grlp_person_pos_listenplatz">Listenplatz</label></th>
        <td><input type="text" name="grlp_person_pos_listenplatz" id="grlp_person_pos_listenplatz" value="<?php echo $listenplatz; ?>" /><br><span class="description">Der Listenplatz, z.B. "02", "10", "43". Mandate werden danach sortiert.</span></td>
      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_pos_wahlkreis">Wahlkreis</label></th>
        <td><input type="text" name="grlp_person_pos_wahlkreis" id="grlp_person_pos_wahlkreis" value="<?php echo $wahlkreis; ?>" /><br><span class="description">z.B. "Aachen II".</span></td>

        <th scope="row"><label for="grlp_person_pos_details">Link zur Detailseite</label></th>
        <td><input type="checkbox" name="grlp_person_pos_details" id="grlp_person_pos_details" value="yes" <?php checked($grlp_person_pos_details, 'yes'); ?> /><br><span class="description">Link zur Detailseite in der Übersicht anzeigen.</span></td>
      </tr>
      <tr>
        <th scope="row"><label for="grlp_person_pos_sortierung">Sortierung</label></th>
        <td><input type="text" name="grlp_person_pos_sortierung" id="grlp_person_pos_sortierung" value="<?php echo $sortierung; ?>" /><br><span class="description">Sortierung für MA und Vorstand, z.B. "02", "10", "43".</span></td>
      </tr>

    </tbody>
  </table>
<?php
}


add_action('save_post', 'grlp_person_position_save');
function grlp_person_position_save($post_id)
{
  // Bail if we're doing an auto save
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // if our nonce isn't there, or we can't verify it, bail
  if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce')) return;

  // if our current user can't edit this post, bail
  if (!current_user_can('edit_post', $post_id)) return;

  // now we can actually save the data
  $allowed = array(
    'a' => array( // on allow a tags
      'href' => array() // and those anchors can only have href attribute
    )
  );

  // Make sure your data is set before trying to save it
  if (isset($_POST['grlp_person_excerpt'])) {
    update_post_meta(
      $post_id,
      'grlp_person_excerpt',
      esc_html($_POST['grlp_person_excerpt'])
    );
  }
  if (isset($_POST['grlp_person_motivation'])) {
    update_post_meta(
      $post_id,
      'grlp_person_motivation',
      esc_html($_POST['grlp_person_motivation'])
    );
  }
  if (isset($_POST['grlp_person_pos_amt'])) {
    update_post_meta(
      $post_id,
      'grlp_person_pos_amt',
      wp_kses($_POST['grlp_person_pos_amt'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_pos_listenplatz'])) {
    update_post_meta(
      $post_id,
      'grlp_person_pos_listenplatz',
      wp_kses($_POST['grlp_person_pos_listenplatz'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_pos_wahlkreis'])) {
    update_post_meta(
      $post_id,
      'grlp_person_pos_wahlkreis',
      wp_kses($_POST['grlp_person_pos_wahlkreis'], $allowed)
    );
  }
  if (isset($_POST['grlp_person_pos_details'])) {
    update_post_meta($post_id, 'grlp_person_pos_details', 'yes');
  } else {
    update_post_meta($post_id, 'grlp_person_pos_details', 'no');
  }
  if (isset($_POST['grlp_person_pos_sortierung'])) {
    update_post_meta(
      $post_id,
      'grlp_person_pos_sortierung',
      wp_kses($_POST['grlp_person_pos_sortierung'], $allowed)
    );
  }
}
