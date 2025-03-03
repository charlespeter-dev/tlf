<?php

/**
 * Template Name: v2 / Resources / Single Whitepaper
 * Template Post Type: resources, gated-resource
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

/**
 * related resources
 */

$related_resources = _2x_related_resources($post->ID);

get_header() ?>

<div class="bootstrap-container resources single-whitepaper">

    <section class="hero-carousels"
        style="--bg-url:url('<?= wp_get_attachment_image_url($background_image, '_2x_small-banner-hero') ?>');">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner-hero') ?>"
                    class="full-width" alt="" loading="lazy">

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
                                <h1 class="mb-0">
                                    <?php if (isset($main_heading) && $main_heading): ?>
                                        <?= $main_heading ?>
                                    <?php else: ?>
                                        <?= get_the_title($post->ID) ?>
                                    <?php endif ?>
                                </h1>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section>
        <div class="row-container">
            <div class="single-h-padding limit-width">

                <?php if (isset($top_content) && $top_content): ?>
                    <div class="row">
                        <div class="col">
                            <div class="top-content my-5">
                                <?= $top_content ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (isset($infographic_summary) && $infographic_summary): ?>

                    <div class="row align-items-center my-5">
                        <div class="col-lg-6 d-lg-flex justify-content-lg-end">
                            <img src="<?= $infographic_summary['left_thumbnail_image'] ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-lg-6">
                            <?= $infographic_summary['right_content'] ?>

                            <div class="my-5">
                                <a href="<?= $pdf['url'] ?>" target="_blank" class="btn btn-primary">
                                    <?= $pdf['title'] ?>
                                </a>
                            </div>

                        </div>
                    </div>

                <?php endif ?>


                <?php if (isset($bottom_content) && $bottom_content): ?>
                    <div class="row">
                        <div class="col">
                            <div class="bottom-content my-5">
                                <?= $bottom_content ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

            </div>
        </div>
    </section>


    <?php if (!empty($related_resources)): ?>
        <section class="related-resources pb-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <hr class="my-5">

                    <div class="row mb-5">
                        <div class="col-lg-6">
                            <h2 class="blue">
                                <?= $options['related_resources']['main_heading'] ?>
                            </h2>
                        </div>
                        <div class="col-lg-6 show-more-top">
                            <div>
                                <a href="<?= $options['related_resources']['cta']['url'] ?>" class="red">
                                    <?= $options['related_resources']['cta']['title'] ?> <i
                                        class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 gy-4">
                        <?php foreach ($related_resources as $post_id => $item): ?>
                            <div class="col">

                                <a href="<?= $item['url'] ?>">
                                    <div class="card h-100">
                                        <img class="img-fluid"
                                            src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($post_id), '_2x-card-faces-of-tlf-left-top') ?>"
                                            alt="" loading="lazy">
                                        <div class="card-body position-relative wpk-box-brand">
                                            <p class="category mb-3">
                                                <?= implode(' / ', $item['category']) ?>
                                            </p>
                                            <p class="post-title">
                                                <?= $item['title'] ?>
                                            </p>
                                        </div>

                                    </div>
                                </a>

                            </div>
                        <?php endforeach ?>
                    </div>

                    <div class="row mt-5">
                        <div class="col show-more-bottom">
                            <div>
                                <a href="<?= $options['related_resources']['cta']['url'] ?>" class="red">
                                    <?= $options['related_resources']['cta']['title'] ?> <i
                                        class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
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
                        class="full-width" alt="" loading="lazy">

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