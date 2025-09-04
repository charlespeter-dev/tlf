<?php

/**
 * Template Name: v2 / Resources / Single Webinar
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
    if (!in_array($cats->slug, ['visible', 'hidden', 'gated'])) {

        // ---------------------------------------------------
        // if slug is 'webinar', check its language
        // if language is 'fr', append ' -fr'
        // ---------------------------------------------------

        if ($cats->slug === 'webinar') {

            $languages = get_the_terms($post->ID, 'language');

            foreach ($languages as $lang) {

                if ($lang->slug === 'en') {
                    $resource_categories[$post->ID] = $cats->name;
                    break;
                }

                if ($lang->slug === 'fr') {
                    $resource_categories[$post->ID] = $cats->name . ' - FR';
                    break;
                }

                if ($lang->slug === 'es') {
                    $resource_categories[$post->ID] = $cats->name . ' - ES';
                    break;
                }
            }

        } else {
            $resource_categories[$post->ID] = $cats->name;
        }
    }
}

/**
 * related resources
 */

$related_resources = _2x_related_resources($post->ID);

get_header() ?>

<div class="bootstrap-container resources single-webinar">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner-hero') ?>"
                    class="full-width" alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-7">

                            <?php if (isset($resource_categories) && $resource_categories): ?>
                                <div class="mb-3">
                                    <div class="sub-heading">
                                        <?= implode(' / ', $resource_categories) ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="mb-3">
                                <h1 class="mb-0">
                                    <?= get_the_title($post->ID) ?>
                                </h1>
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

                <!-- speakers -->

                <?php if (isset($speaker_groups) && $speaker_groups): ?>
                    <div class="speakers">
                        <div class="speakers-heading mt-3 mb-4">
                            <h3>
                                <?= __('Speakers:') ?>
                            </h3>
                        </div>

                        <div class="row speakers-content mb-4">

                            <?php foreach ($speaker_groups as $speaker_group): ?>

                                <div class="col-lg-6">
                                    <div class="speaker-image">
                                        <img class="headshot" src="<?= $speaker_group['speaker']['headshot']['url'] ?>" alt=""
                                            loading="lazy">
                                    </div>

                                    <div class="speaker-info my-4 my-lg-0">
                                        <div class="speaker-name">
                                            <?= $speaker_group['speaker']['name'] ?>
                                        </div>
                                        <div class="speaker-title">
                                            <?= $speaker_group['speaker']['title'] ?>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach ?>

                        </div>
                    </div>
                <?php endif ?>

                <!-- related webinars internal -->

                <?php if (isset($related_webinars[0]['webinar_post']) && $related_webinars[0]['webinar_post']): ?>

                    <div class="speakers-heading mt-3 mb-4">
                        <h3>
                            <?= __('Start Watching:') ?>
                        </h3>
                    </div>

                    <div class="row related-webinars">
                        <?php foreach ($related_webinars as $post): ?>
                            <a class="col-lg-6" href="<?= get_the_permalink($post['webinar_post']) ?>" target="_blank"
                                rel="noopener">
                                <div class="grey-bg">
                                    <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($post['webinar_post']), '_2x-card-news') ?>"
                                        alt="" loading="lazy">
                                    <div class="card-body">
                                        <div class="title">
                                            <?= get_the_title($post['webinar_post']) ?>
                                        </div>
                                        <div class="link">
                                            <?= $post['cta_label'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach ?>
                    </div>

                <?php endif ?>

                <!-- related webinars extenal -->

                <?php if ($related_webinars_external[0] ?? false): ?>

                    <div class="speakers-heading mt-3 mb-4">
                        <h3>
                            <?= __('Start Watching:') ?>
                        </h3>
                    </div>

                    <div class="row related-webinars">
                        <?php foreach ($related_webinars_external as $link): ?>
                            <a class="col-lg-6" href="<?= $link['webinar_link']['url'] ?>" target="_blank" rel="noopener">
                                <div class="grey-bg">
                                    <img src="<?= $link['webinar_thumbnail']['url'] ?>" alt="" loading="lazy">
                                    <div class="card-body">
                                        <div class="title">
                                            <?= $link['webinar_link']['title'] ?>
                                        </div>
                                        <div class="link">
                                            <?= __('Watch Now') ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach ?>
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