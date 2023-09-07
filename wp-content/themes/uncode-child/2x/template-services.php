<?php

/**
 * Template Name: v2 / Services
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

get_header() ?>

<div class="bootstrap-container services">

    <section class="hero-carousels single customers">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($hero_carousel['background_image'], '_2x-carousel-hero') ?>"
                    class="full-width" alt="">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6">

                            <?php if (isset($hero_carousel['main_heading']) && $hero_carousel['main_heading']): ?>
                                <div class="mb-3">
                                    <h2 class="mb-0">
                                        <?= $hero_carousel['main_heading'] ?>
                                    </h2>
                                </div>
                            <?php endif ?>

                            <?php if (isset($hero_carousel['sub_heading']) && $hero_carousel['sub_heading']): ?>
                                <div class="mb-4">
                                    <div class="sub-heading">
                                        <?= $hero_carousel['sub_heading'] ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($hero_carousel['cta']) && $hero_carousel['cta']): ?>
                                <div>
                                    <a class="btn btn-primary" href="<?= $hero_carousel['cta']['url'] ?>">
                                        <?= $hero_carousel['cta']['title'] ?>
                                    </a>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <?php if ($content_top): ?>
        <section class="content-top py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <?= $content_top ?>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if ($callout_cards): ?>

        <section class="callout-with-cards py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="d-block text-center mb-5">
                                <?= $callout_cards['main_heading'] ?>
                            </h2>
                        </div>
                    </div>
                    <div class="row gy-4">
                        <?php foreach ($callout_cards['cards'] as $k => $item): ?>
                            <div class="col">
                                <div class="card h-100 wpk-box-brand">

                                    <div class="card-body">
                                        <img src="<?= $item['icon'] ?>" alt="">
                                        <hr>
                                        <p class="headline mb-3"><strong>
                                                <?= $item['headline'] ?>
                                            </strong></p>
                                        <p class="excerpt">
                                            <?= $item['excerpt'] ?>
                                        </p>
                                    </div>
                                    <div class="card-footer mb-4">
                                        <a href="<?= $item['cta']['url'] ?>">
                                            <?= $item['cta']['title'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>

    <?php endif ?>

    <?php if ($content_bottom): ?>

        <section class="content-bottom py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="d-block text-center mb-5">
                                <?= $content_bottom['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($content_bottom['contents'] as $k => $item): ?>
                            <div class="col-lg-12">

                                <div class="row contents mb-4">

                                    <div class="col-lg-2 icon">
                                        <img class="img-fluid" src="<?= $item['icon'] ?>" alt="">
                                    </div>

                                    <div class="col-lg-10">
                                        <p class="headline">
                                            <strong>
                                                <?= $item['headline'] ?>
                                            </strong>
                                        </p>

                                        <p class="excerpt">
                                            <?= $item['excerpt'] ?>
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

    <?php if ($quote): ?>

        <section class="quote-container py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="d-block text-center mb-5">
                                <?= $quote['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div>
                        <?= $quote['quote'] ?>
                    </div>

                    <?php if ($quote['cta']): ?>
                        <div class="quote-cta">
                            <a href="<?= $quote['cta']['url'] ?>">
                                <?= $quote['cta']['title'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                            </a>
                        </div>
                    <?php endif ?>

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