<?php
/**
 * Template part for displaying person content in person.php
 *
 */
?>
<article id="post-<?php the_ID(); ?>" class="display-single">
    <header class="entry-header text-center <?php echo (has_post_thumbnail()) ? 'has-post-thumbnail' : 'has-no-post-thumbnail'; ?>">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->
	<?php sunflower_post_thumbnail(false, true); ?>

    <h3><?php echo $grlp_meta['grlp_person_detail_mandate'][0]; ?></h3>
    <div class="wp-block-group d-flex p-0 my-3">
        <div class="wp-block-group__inner-container">
            <?php if (! empty($grlp_meta['grlp_person_contact_email'])) : ?>
            <div class="wp-block-sunflower-meta-data">
                <a href="#" data-unscramble="<?php echo strrev($grlp_meta['grlp_person_contact_email'][0]); ?>" target="_blank" rel="noopener">
                    <i class="fas fa-envelope fa-2x"></i>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if (! empty($grlp_meta['grlp_person_contact_www'])) : ?>
            <div class="wp-block-sunflower-meta-data">
                <a href="<?php echo $grlp_meta['grlp_person_contact_www'][0]; ?>" target="_blank" rel="noopener">
                    <i class="fas fa-globe fa-2x"></i>
                </a>
            </div>
            <?php endif; ?>

            <?php if (! empty($grlp_meta['grlp_person_contact_instagram'] )) : ?>
            <div class="wp-block-sunflower-meta-data">
                <a href="<?php echo $grlp_meta['grlp_person_contact_instagram'][0]; ?>" target="_blank" rel="noopener">
                    <i class="fab fa-instagram fa-2x"></i>
                </a>
            </div>
            <?php endif; ?>

            <?php if (! empty($grlp_meta['grlp_person_contact_twitter'] )) : ?>
            <div class="wp-block-sunflower-meta-data">
                <a href="<?php echo $grlp_meta['grlp_person_contact_twitter'][0]; ?>" target="_blank" rel="noopener">
                    <i class="fab fa-twitter fa-2x"></i>
                </a>
            </div>
            <?php endif; ?>

            <?php if (! empty($grlp_meta['grlp_person_contact_facebook'] )) : ?>
            <div class="wp-block-sunflower-meta-data">
                <a href="<?php echo $grlp_meta['grlp_person_contact_facebook'][0]; ?>" target="_blank" rel="noopener">
                    <i class="fab fa-facebook fa-2x"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
	<div class="entry-content">
		<?php
            the_content();
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
