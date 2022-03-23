<div class="grlp-person-container<?php echo isset($atts['cols'])?' gridcols-'.$atts['cols']:'' ?>">
    <?php foreach ($team_persons as $person) : ?>
    <?php 
    $phone = get_post_meta( $person->ID, 'grlp_person_contact_phone', true );
    $mobile = get_post_meta( $person->ID, 'grlp_person_contact_mobile', true );
    $web = get_post_meta( $person->ID, 'grlp_person_contact_www', true );
    $email = get_post_meta( $person->ID, 'grlp_person_contact_email', true );
    $insta = get_post_meta( $person->ID, 'grlp_person_contact_insta', true );
    $twitter = get_post_meta( $person->ID, 'grlp_person_contact_twitter', true );
    $facebook = get_post_meta( $person->ID, 'grlp_person_contact_facebook', true );
    $has_detail_link = $atts['links'] && get_post_meta( $person->ID, 'grlp_person_detail_has_link', true);
    ?>
    <div class="person has-shadow">
        <figure>
            <?php if ( !empty( get_the_post_thumbnail( $person->ID )) ) :?>
            <?php echo get_the_post_thumbnail( $person->ID ); ?>
            <?php else : ?>
            <img src="https://sunflower-theme.de/demo/wp-content/uploads/sites/6/2021/01/sunflower-flower-summer-blossom-4298808-1024x682.jpg" />
            <?php endif; ?>
        </figure>
        <div class="person-info" style="">
            <p class="person-name"><?php echo $person->post_title; ?></p>
            <p class="person-description"><?php echo get_post_meta( $person->ID, 'grlp_person_detail_job', true ); ?></p>
            <div class="person-contact-info d-flex">
                <?php if (! empty($web)) : ?>
                <div class="wp-block-sunflower-meta-data">
                    <a href="<?php echo $web; ?>"><i class="fas fa-globe"></i></a>
                </div>
                <?php endif; ?>
                <?php if (! empty ($email)) : ?>
                <div class="wp-block-sunflower-meta-data">
                    <a href="mailto:<?php echo $email; ?>"><i class="fas fa-envelope"></i></a>
                </div>
                <?php endif; ?>
                <?php if (! empty ($insta)) : ?>
                <div class="wp-block-sunflower-meta-data">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
                <?php endif; ?>
                <?php if (! empty ($twitter)) : ?>
                <div class="wp-block-sunflower-meta-data">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
                <?php endif; ?>
                <?php if (! empty ($facebook)) : ?>
                <div class="wp-block-sunflower-meta-data">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                </div>
                <?php endif; ?>
            </div>
            <?php if ( $phone ) : ?>
            <p class="person-description">Tel.: <?php echo $phone; ?></p>
            <?php endif; ?>
            <?php if ( $mobile ) : ?>
            <p class="person-description">Mobil.: <?php echo $mobile; ?></p>
            <?php endif; ?>
            <?php if ( $has_detail_link ) : ?>
            <div class="details-button">
                <div class="wp-block-button mb-0 mt-1">
                    <a class="wp-block-button__link" href="/gruene_personen/<?php echo $person->post_name; ?>">Details</a>
                </div>
            </div>
            <?php endif; ?>
        </div><!-- person-info -->
    </div><!-- person -->
<?php endforeach; ?>
</div><!-- grlp-person-container -->
