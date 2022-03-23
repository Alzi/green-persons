<?php
/**
 * The template for displaying grlp_person pages
 *
 * This is based on the 'page.php' template file from sunflower theme.
 */
$grlp_meta = get_post_meta( $post->ID );
get_header();
?>
    <div id="content" class="container container-narrow">
        <div class="row">
            <div class="col-12">
                <main id="primary" class="site-main">
                    <?php
                        grlp_get_template('person_content.php', array('grlp_meta'=>$grlp_meta));
                    ?>
                </main><!-- #main -->
            </div>
        </div>
    </div>
<?php
get_sidebar();
get_footer();
