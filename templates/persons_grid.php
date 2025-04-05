<?php
    $all_settings = array (
        'team' => array (
            'show_job'           => true  && (!($atts['jobinfo'] ?? '') == 'nein'),
            'show_shortinfo'     => false || (($atts['kurzinfo'] ?? '') == 'ja'),
            'show_phonenumbers'  => true  && (!($atts['telefon'] ?? '') == 'nein'),
            'show_detail_button' => false || (($atts['button'] ?? '') == 'ja'),
            'show_list_pos'      => false,
            'show_constituency'  => false,
            'show_constit_num'   => false
        ),
        'detail' => array (
            'show_job'           => false || (($atts['jobinfo'] ?? '') == 'ja'),
            'show_shortinfo'     => true  && (!($atts['kurzinfo'] ?? '') == 'nein'),
            'show_phonenumbers'  => false || (($atts['telefon'] ?? '') == 'ja'),
            'show_detail_button' => true  && (!($atts['button'] ?? '') == 'nein'),
            'show_list_pos'      => false,
            'show_constituency'  => false,
            'show_constit_num'   => false
        ),
        'candidate_list' => array (
            'show_job'           => false || (($atts['jobinfo'] ?? '') == 'ja'),
            'show_shortinfo'     => false || (($atts['kurzinfo'] ?? '') == 'ja'),
            'show_phonenumbers'  => false || (($atts['telefon'] ?? '') == 'ja'),
            'show_detail_button' => true  && (!($atts['button'] ?? '') == 'nein'),
            'show_list_pos'      => true  && (!($atts['listenplatz'] ?? '') == 'nein'),
            'show_constituency'  => true  && (!($atts['wahlkreis'] ?? '') == 'nein'),
            'show_constit_num'   => true  && (!($atts['wk-nummer'] ?? '') == 'nein')
        ),
        'direct_candidate_list' => array (
            'show_job'           => false || (($atts['jobinfo'] ?? '') == 'ja'),
            'show_shortinfo'     => false || (($atts['kurzinfo'] ?? '') == 'ja'),
            'show_phonenumbers'  => false || (($atts['telefon'] ?? '') == 'ja'),
            'show_detail_button' => true  && (!($atts['button'] ?? '') == 'nein'),
            'show_list_pos'      => true  && (!($atts['listenplatz'] ?? '') == 'nein'),
            'show_constituency'  => true  && (!($atts['wahlkreis'] ?? '') == 'nein'),
            'show_constit_num'   => true  && (!($atts['wk-nummer'] ?? '') == 'nein')
        )
    );
    $settings = $all_settings[$view];
?>

<div class="grlp-person-container<?php echo isset($atts['cols'])?' gridcols-'.$atts['cols']:'' ?>">
    <?php foreach ($persons as $person) : ?>
        <?php
        $phone = get_post_meta( $person->ID, 'grlp_person_contact_phone', true );
        $mobile = get_post_meta( $person->ID, 'grlp_person_contact_mobile', true );
        $web = get_post_meta( $person->ID, 'grlp_person_contact_www', true );
        $email = get_post_meta( $person->ID, 'grlp_person_contact_email', true );
        $instagram = get_post_meta( $person->ID, 'grlp_person_contact_instagram', true );
        $twitter = get_post_meta( $person->ID, 'grlp_person_contact_twitter', true );
        $facebook = get_post_meta( $person->ID, 'grlp_person_contact_facebook', true );
        $bluesky = get_post_meta( $person->ID, 'grlp_person_contact_bluesky', true );
        $threads = get_post_meta( $person->ID, 'grlp_person_contact_threads', true );
        $linkedin = get_post_meta( $person->ID, 'grlp_person_contact_linkedin', true );
        $tiktok = get_post_meta( $person->ID, 'grlp_person_contact_tiktok', true );
        $newsletter = get_post_meta( $person->ID, 'grlp_person_contact_newsletter', true );
        $list_position = get_post_meta( $person->ID, 'grlp_person_detail_list_pos', true);
        $constituency = get_post_meta( $person->ID, 'grlp_person_detail_constituency', true);
        $constit_num = get_post_meta( $person->ID, 'grlp_person_detail_constit_num', true);
        $img_author = get_post_meta( $person->ID, 'grlp_person_meta_img_author', true);
        $img_author_url = get_post_meta( $person->ID, 'grlp_person_meta_img_author_url', true);
        $img_platform_name = get_post_meta( $person->ID, 'grlp_person_meta_img_platform_name', true);
        $img_platform_url = get_post_meta( $person->ID, 'grlp_person_meta_img_platform_url', true);
        $show_detail_button = $settings['show_detail_button'] && get_post_meta( $person->ID, 'grlp_person_detail_has_link', true);
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
            $copyright_row .= '</p>';
        }
        ?>
        <div class="person has-shadow">
            <figure>
                <?php if ( !empty( get_the_post_thumbnail( $person->ID )) ) :?>
                <?php echo get_the_post_thumbnail( $person->ID ); ?>
                <?php else : ?>
                <img src="https://sunflower-theme.de/demo/wp-content/uploads/sites/6/2021/01/sunflower-flower-summer-blossom-4298808-1024x682.jpg" />
                <?php endif; ?>
                <?php if (!empty($copyright_row)) echo $copyright_row; ?>
            </figure>
            <div class="person-info">
                <p class="person-name"><?php echo $person->post_title; ?></p>
                <?php if( $settings['show_job'] ) : ?>
                    <p class="person-description"><?php echo get_post_meta( $person->ID, 'grlp_person_detail_job', true ); ?></p>
                <?php endif; ?>
                <?php if( $settings['show_shortinfo'] ) : ?>
                    <p class="person-description"><?php echo get_post_meta( $person->ID, 'grlp_person_detail_shortinfo', true ); ?></p>
                <?php endif; ?>
                <!-- Direktliste -->
                <?php if ( $view == 'direct_candidate_list' ) : ?>
                    <div class="person-direct-candidate-container">
                    <?php if( $settings['show_constituency'] ) : ?>
                        <p class="person-constituency"><?php
                            printf( 'Wahlkreis %s (%s)',
                                get_post_meta( $person->ID, 'grlp_person_detail_constit_num', true ),
                                get_post_meta( $person->ID, 'grlp_person_detail_constituency', true )
                            );
                        ?></p>
                    <?php endif; ?>
                    <?php if( $settings['show_list_pos'] && get_post_meta( $person->ID, 'grlp_person_detail_list_pos', true ) != '' ) : ?>
                        <p class="person-list-pos">Listenplatz <?php echo get_post_meta( $person->ID, 'grlp_person_detail_list_pos', true ); ?></p>
                    <?php else : ?>
                        <p>&nbsp;</p>
                    <?php endif; ?>
                    </div>
                <!-- Landesliste -->
                <?php elseif ( $view == 'candidate_list' ) : ?>
                    <div class="person-candidate-container">
                    <?php if( $settings['show_list_pos'] ) : ?>
                        <p class="person-list-pos">Listenplatz <?php echo get_post_meta( $person->ID, 'grlp_person_detail_list_pos', true ); ?></p>
                    <?php endif; ?>
                    <?php if( $settings['show_constituency'] && get_post_meta( $person->ID, 'grlp_person_detail_constituency', true ) != '' ) : ?>
                        <p class="person-constituency"><?php
                            printf( 'Wahlkreis %s (%s)',
                                get_post_meta( $person->ID, 'grlp_person_detail_constit_num', true ),
                                get_post_meta( $person->ID, 'grlp_person_detail_constituency', true )
                            );
                            ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="person-contact-info d-flex">
                    <?php if (! empty($web)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $web; ?>"><i class="fa-solid fa-globe"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($email)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="mailto:<?php echo $email; ?>"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($instagram)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $instagram; ?>"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php  if (! empty ($twitter)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $twitter; ?>"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($facebook)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $facebook; ?>"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($bluesky)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $bluesky; ?>"><i class="fa-brands fa-bluesky"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($threads)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $threads; ?>"><i class="fa-brands fa-threads"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($linkedin)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $linkedin; ?>"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty ($tiktok)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $tiktok; ?>"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                    <?php endif; ?>
					<?php if (! empty ($newsletter)) : ?>
                    <div class="wp-block-sunflower-meta-data">
                        <a href="<?php echo $newsletter; ?>"><i class="fa-solid fa-envelope-open-text"></i></a>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if( $settings['show_phonenumbers'] ) : ?>
                    <?php if ( $phone ) : ?>
                    <p class="person-description"><?php echo $phone; ?></p>
                    <?php endif; ?>
                    <?php if ( $mobile ) : ?>
                    <p class="person-description"><?php echo $mobile; ?></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ( $show_detail_button ) : ?>
                <div class="details-button">
                    <div class="wp-block-button mb-0 mt-2">
                        <a class="wp-block-button__link" href="<?php printf("/person/%s", $person->post_name); ?>">Details</a>
                    </div>
                </div>
                <?php endif; ?>
            </div><!-- person-info -->
        </div><!-- person -->
    <?php endforeach; ?>
</div><!-- grlp-person-container -->
