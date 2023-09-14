<?php

/**
 * Template Name: v2 / Resources / Single Webinar
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

<div class="bootstrap-container resources single-webinar">

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

    <section class="py-5">
        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="main-content">
                    <?= $main_content ?>
                </div>

            </div>
        </div>
    </section>

</div>


<?php get_footer() ?>