<?php
/**
 * Template part for displaying person-content
 *
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('display-single'); ?>>
    <header class="entry-header full-width <?php echo (has_post_thumbnail()) ? 'has-post-thumbnail' : 'has-no-post-thumbnail'; ?>">
        <div class="container">
            <div class="row position-relative">
                <div class="col-12 offset-md-1 <?php echo ($metadata) ? 'text-left col-md-8' : 'col-md-10'; ?>">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </div>
            </div>
        </div>
    </header><!-- .entry-header -->

    <?php sunflower_post_thumbnail($styled_layout, true); ?>

    <div class="col-12 col-md-10 offset-md-1">
        <div class="entry-meta mb-3">
            <?php printf( '<p><strong>Email:</strong> %s</p>', $grlp_meta['grlp_person_contact_email'][0] ); ?>
        </div><!-- .entry-meta -->

        <div class="entry-content">
            <?php
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sunflower' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer mt-5">
            <?php sunflower_entry_footer(true); ?>
        </footer><!-- .entry-footer -->
    </div>  
</article><!-- #post-<?php the_ID(); ?> -->
