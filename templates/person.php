<?php
/**
 * The template for displaying grlp_person pages
 *
 * This is based on the 'page.php' template file from sunflower theme.
 */
$grlp_meta = get_post_meta( $post->ID );
$img_author = isset($grlp_meta['grlp_person_meta_img_author']) ? $grlp_meta['grlp_person_meta_img_author'][0] : '';
$img_author_url = isset($grlp_meta['grlp_person_meta_img_author_url']) ? $grlp_meta['grlp_person_meta_img_author_url'][0] : '';
$img_platform_name = isset($grlp_meta['grlp_person_meta_img_platform_name']) ? $grlp_meta['grlp_person_meta_img_platform_name'][0] : '';
$img_platform_url = isset($grlp_meta['grlp_person_meta_img_platform_url']) ? $grlp_meta['grlp_person_meta_img_platform_url'][0] : '';
$copyright_row = '';
if (! empty($img_author) ) {
    $copyright_row = '<p class="img_copyright">Foto von ';
    if ( !empty($img_author_url) ) {
        $copyright_row .= '<a href="' . $img_author_url . '">' . $img_author . '</a>';
    } else {
        $copyright_row .= $img_author;
    }
    if ( !empty ($img_platform_name) ) {
        $copyright_row .= ' auf ';
        if ( !empty ($img_platform_url) ) {
            $copyright_row .= '<a href="' . $img_platform_url . '">' . $img_platform_name . '</a>';
        } else {
            $copyright_row .= $img_platform_name;
        }
    }
}
$copyright_row .= '</p>';
get_header();
?>
    <div id="content" class="container container-narrow">
        <div class="row">
            <div class="col-12">
                <main id="primary" class="site-main">
                    <?php
                        grlp_get_template(
                            'person_content.php',
                            array(
                                'grlp_meta' => $grlp_meta,
                                'copyright_row' => $copyright_row,
                            )
                        );
                    ?>
                </main><!-- #main -->
            </div>
        </div>
    </div>
<?php
get_sidebar();
get_footer();
