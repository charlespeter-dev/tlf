<?php

/**
 * Template Name: v2 / Our Partners
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);
extract($fields);

$partners = get_field('partners_list');

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels our-partners">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <?php $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), '_2x_xs-banner' ); ?>
                <?php $image_url = $image_data[0]; ?>

                <img src="<?php echo $image_url = $image_url; ?>" class="xs-height" alt="">
                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-0">
                                <h2 class="mb-0">
                                    <?= get_the_title($post->ID) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($partners_list)): ?>
        <section class="partners-wrapper">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="row row-cols-1 row-cols-lg-2 m-0 gy-5">
                        <?php foreach ($partners as $partner): 
                            $partner_img = $partner['partner_image'];  $partner_name = $partner['partner_name'];  $partner_desc = $partner['partner_description'];?>
                            <div class="col">
                                <div class="partner-card h-100">
                                    <figure class="partner-img">
                                        <img src="<?= wp_get_attachment_image_url($partner_img['id'], '_2x-partner-logo'); ?>" class="img-fluid" alt="<?= $partner_img['title'] ?>">
                                    </figure>

                                    <div class="partner-details">
                                        <p class="partner-name">
                                            <?= $partner_name ?>
                                        </p>
                                        <p class="partner-desc">
                                            <?= $partner_desc ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($options['footer_callout_banner']) && $options['footer_callout_banner']): ?>
        <section class="footer-callout-banner">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">
                    <img src="<?= wp_get_attachment_image_url($options['footer_callout_banner']['background_image'], '_2x_footer-callout-banner') ?>"
                        class="full-width" alt="">

                    <div class="footer-callout-banner-content">
                        <div class="main-heading mb-4">
                            <?= $options['footer_callout_banner']['main_heading'] ?>
                        </div>
                        <div class="cta">
                            <a class="btn btn-primary" href="<?= $options['footer_callout_banner']['cta']['url'] ?>">
                                <?= $options['footer_callout_banner']['cta']['title'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

</div>

<?= get_footer() ?>