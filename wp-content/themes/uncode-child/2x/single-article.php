<?php

/**
 * Template Name: v2 / Post / Single Article
 * Template Post Type: post
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * related resources
 */

$related_resources = _2x_related_resources($post->ID);

/**
 * css specifics
 */

wp_enqueue_style('_2x-css-single-article', sprintf('%s/2x/assets/css/single-article.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<div class="bootstrap-container post single-article">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">
                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner-hero') ?>"
                    class="full-width" alt="">
            </div>
        </div>
    </section>

    <section class="main-content my-5">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">
                <div>
                    <?= $main_content ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($related_resources) && $related_resources): ?>
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