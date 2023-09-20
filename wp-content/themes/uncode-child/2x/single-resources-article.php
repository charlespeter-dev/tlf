<?php

/**
 * Template Name: v2 / Resources / Single Article
 * Template Post Type: resources
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

get_header() ?>

<div class="bootstrap-container resources single-article">

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

    <?php if (isset($about_author['authors']) && $about_author['authors']): ?>
        <section class="about-author my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <div class="row mb-3">
                        <div class="col">
                            <h3 class="red">
                                <?= $about_author['main_heading'] ?>
                            </h3>
                        </div>
                    </div>

                    <?php foreach ($about_author['authors'] as $k => $author): ?>

                        <div class="row <?= ($k) ? 'mt-5' : '' ?>">
                            <div class="col-lg-3">
                                <img class="img-fluid mb-4"
                                    src="<?= wp_get_attachment_image_url($author['profile_picture'], '_2x-carousel-news') ?>"
                                    alt="">
                            </div>
                            <div class="col-lg-9">
                                <div class="about-author-name">
                                    <h3 class="blue">
                                        <?= $author['author_name'] ?>
                                    </h3>
                                </div>
                                <div class="about-author-description">
                                    <p>
                                        <?= $author['author_description'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    <?php endforeach ?>

                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($article_sources) && $article_sources): ?>
        <section class="about-author my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">
                    <div>
                        <?= $article_sources ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

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
                                            src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($post_id), '_2x-carousel-resources-callout') ?>"
                                            alt="">
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