<?php 
    $contact_email      = $grlp_meta['grlp_person_contact_email'][0];
    $contact_facebook   = $grlp_meta['grlp_person_contact_facebook'][0];
    $contact_twitter    = $grlp_meta['grlp_person_contact_twitter'][0];
    $contact_instagram  = $grlp_meta['grlp_person_contact_instagram'][0];
    $contact_web        = $grlp_meta['grlp_person_contact_www'][0];
?>
<div class="wp-container wp-block-group d-flex p-0 my-3">
    <div class="wp-block-group__inner-container">
        <?php if (! empty( $contact_email )) : ?>
        <div class="wp-block-sunflower-meta-data">
            <a href="#" data-unscramble="<?php echo strrev($contact_email); ?>" target="_blank" rel="noopener">
                <i class="fas fa-envelope fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
        <?php if (! empty( $contact_web )) : ?>
        <div class="wp-block-sunflower-meta-data">
            <a href="<?php echo $contact_web; ?>" target="_blank" rel="noopener">
                <i class="fas fa-globe fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
        <?php if (! empty( $contact_facebook )) : ?>
        <div class="wp-block-sunflower-meta-data">
            <a href="<?php echo $contact_facebook; ?>" target="_blank" rel="noopener">
                <i class="fab fa-facebook fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
        <?php if (! empty( $contact_twitter )) : ?>
        <div class="wp-block-sunflower-meta-data">
            <a href="<?php echo $contact_twitter; ?>" target="_blank" rel="noopener">
                <i class="fa-brands fa-x-twitter fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
        <?php if (! empty( $contact_instagram )) : ?>
        <div class="wp-block-sunflower-meta-data">
            <a href="<?php echo $contact_instagram; ?>" target="_blank" rel="noopener">
                <i class="fab fa-instagram fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
