<?php

/**
 * Template Name: v2 / Services / Continuous Services
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * specific css
 */

wp_enqueue_style('_2x-css-template-services-continuous-services', sprintf('%s/2x/assets/css/template-services-continuous-services.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_url($hero_carousel['background_image'], '_2x-carousel-hero') ?>');
        }
    }
</style>

<div class="bootstrap-container services continuous-services">

    <section class="hero-carousels single customers">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($hero_carousel['background_image'], '_2x-carousel-hero') ?>"
                    class="full-width" alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6">

                            <?php if (isset($hero_carousel['main_heading']) && $hero_carousel['main_heading']): ?>
                                <div class="mb-3">
                                    <h1 class="mb-0">
                                        <?= $hero_carousel['main_heading'] ?>
                                    </h1>
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
                                    <a class="btn btn-primary" href="<?= $hero_carousel['cta']['url'] ?>" target="_blank">
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

    <?php if ($callout_cards): ?>

        <section class="callout-with-cards py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <?php if (isset($callout_cards['main_heading']) && $callout_cards['main_heading']): ?>
                                <h2 class="callout-with-cards-main-heading d-block text-center mb-4">
                                    <?= $callout_cards['main_heading'] ?>
                                </h2>
                            <?php endif ?>

                            <?php if (isset($callout_cards['sub_heading']) && $callout_cards['sub_heading']): ?>
                                <div class="callout-with-cards-sub-heading mb-3">
                                    <?= $callout_cards['sub_heading'] ?>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="row row-cols-1 row row-cols-md-3 row-cols-lg-5 gy-4">
                        <?php foreach ($callout_cards['cards'] as $k => $item): ?>
                            <div class="col">
                                <div class="d-flex flex-column align-items-center">
                                    <img class="callout-with-cards-icon" src="<?= $item['icon'] ?>" alt="" loading="lazy">
                                    <div>
                                        <p class="callout-with-cards-headline mb-3">
                                            <strong>
                                                <?= $item['headline'] ?>
                                            </strong>
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

    <?php if (isset($service_level['cards']) && $service_level['cards']): ?>
        <section class="cards-overview py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <?php if (isset($service_level['main_heading']) && $service_level['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="main-heading mb-5">
                                    <?= $service_level['main_heading'] ?>
                                </h2>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">

                        <?php foreach ($service_level['cards'] as $card): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?= wp_get_attachment_image_url($card['card_image'], '_2x-card-customers') ?>"
                                        class="img-fluid" alt="" loading="lazy">

                                    <div class="card-body">
                                        <?= $card['card_body'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>

                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($packages['cards']) && $packages['cards']): ?>
        <section class="cards-overview packages py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <?php if (isset($packages['sub_heading']) && $packages['sub_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <p class="sub-heading pb-4">
                                    <?= $packages['sub_heading'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row row-cols-1 row-cols-md-4 g-5">

                        <?php foreach ($packages['cards'] as $card): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?= $card['package_image'] ?>" class="img-fluid" alt="" loading="lazy">
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>

                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if ($stacked_cards): ?>

        <section class="callout-with-cards stacked-cards py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <?php if (isset($stacked_cards['main_heading']) && $stacked_cards['main_heading']): ?>
                                <h2 class="callout-with-cards-main-heading d-block text-center mb-5">
                                    <?= $stacked_cards['main_heading'] ?>
                                </h2>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="row row-cols-1 row row-cols-md-3">
                        <?php foreach ($stacked_cards['cards'] as $k => $item): ?>
                            <div class="col">
                                <div class="d-flex flex-column align-items-center">
                                    <img class="callout-with-cards-icon" src="<?= $item['icon'] ?>" alt="" loading="lazy">
                                    <div>
                                        <p class="callout-with-cards-headline mb-3">
                                            <strong>
                                                <?= $item['headline'] ?>
                                            </strong>
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

                    <?php if (isset($quote['main_heading']) && $quote['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="d-block text-center mb-5">
                                    <?= $quote['main_heading'] ?>
                                </h2>
                            </div>
                        </div>
                    <?php endif ?>

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

<?= get_footer() ?>