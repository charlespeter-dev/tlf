<?php

/**
 * Template Name: v2 / Resources / Single Brochure
 * Template Post Type: resources
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * get category
 */

$categories = get_the_terms($post->ID, 'resource_category');
foreach ($categories as $cats) {
    if ($cats->slug != 'visible' || $cats->slug != 'hidden') {
        $resource_categories[$post->ID] = $cats->name;
    }
}

get_header() ?>

<div class="bootstrap-container resources single-brochure">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner-hero') ?>"
                    class="full-width" alt="">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-9">

                            <?php if (isset($resource_categories) && $resource_categories): ?>
                                <div class="mb-3">
                                    <div class="sub-heading">
                                        <?= implode(' / ', $resource_categories) ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="mb-3">
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

    <?php if ($pdf): ?>
        <section class="py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="top-content">
                        <?= $top_content ?>
                    </div>

                    <div class="text-center my-5">
                        <a href="<?= $pdf['url'] ?>" target="_blank" class="btn btn-primary">
                            <?= $pdf['title'] ?>
                        </a>
                    </div>

                    <iframe src="<?= $pdf['url'] ?>" frameborder="0"></iframe>

                    <div class="row mt-5 align-items-center">
                        <div class="col-lg-6">
                            <img src="<?= $bottom_content['left_thumbnail_image'] ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-lg-6">
                            <?= $bottom_content['right_content'] ?>
                        </div>
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

<?php get_footer() ?>