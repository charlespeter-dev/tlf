<?php

/**
 * Template Name: v2 / Resources
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * resources
 */

$resources_query = new WP_Query([
    'post_type' => 'resources',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'fields' => 'ids',
    'post_status' => 'publish'
    // 'tax_query' => [
    //     'relation' => 'AND',
    //     [
    //         'taxonomy' => 'resource_category',
    //         'field' => 'slug',
    //         'terms' => array('article'),
    //         'operator' => 'NOT IN'
    //     ]
    // ]
]);

wp_reset_query();

$resources_ids = $resources_query->posts;

$resource_categories = [];

foreach ($resources_ids as $resource_id) {
    $categories = get_the_terms($resource_id, 'resource_category');
    foreach ($categories as $cats) {
        if ($cats->slug != 'visible' || $cats->slug != 'hidden') {
            $resource_categories[$resource_id] = $cats->name;
        }
    }
}

$cards_ids = $resources_ids;

get_header() ?>

<div class="bootstrap-container">

    <?php if ($hero_carousel): ?>
        <section class="bootstrap-container">
            <div class="hero-carousels resources">
                <div id="_2x-carousel-hero" class="carousel carousel-fade" data-bs-pause="false" data-bs-interval="10000"
                    data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($hero_carousel as $k => $item): ?>
                            <div class="carousel-item <?= ($k == 0) ? 'active' : '' ?>">

                                <div class="row-container">

                                    <div class="single-h-padding limit-width position-relative">

                                        <img src="<?= wp_get_attachment_image_url($item['background_image'], '_2x-carousel-hero') ?>"
                                            class="full-width" alt="">

                                        <div class="_2x-hero-content">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <img src="<?= wp_get_attachment_image_url($item['resource_carousel_thumbnail'], 'full') ?>"
                                                        class="img-fluid" alt="">
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <h2 class="mb-0">
                                                            <?= $item['main_heading'] ?>
                                                        </h2>
                                                    </div>
                                                    <div class="mb-4">
                                                        <div class="sub-heading">
                                                            <?= $item['sub_heading'] ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <a class="btn btn-primary" href="<?= $item['cta']['url'] ?>">
                                                            <?= $item['cta']['title'] ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <?php if (count($hero_carousel) > 1): ?>
                        <div class="carousel-indicators">
                            <?php foreach ($hero_carousel as $k => $item): ?>
                                <button type="button" data-bs-target="#_2x-carousel-hero" data-bs-slide-to="<?= $k ?>"
                                    class="<?= ($k == 0) ? 'active' : '' ?>"></button>
                            <?php endforeach ?>

                        </div>
                    <?php endif ?>

                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($cards_ids)): ?>
        <section class="cards-overview faces-of-tlf my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">

                        <?php foreach ($cards_ids as $card_id): ?>
                            <div class="col">
                                <a href="<?= get_the_permalink($card_id) ?>">
                                    <div class="card h-100">
                                        <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($card_id), '_2x-card-faces-of-tlf-left-top') ?>"
                                            class="img-fluid" alt="">

                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= $resource_categories[$card_id] ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= ucwords(get_the_title($card_id)) ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
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