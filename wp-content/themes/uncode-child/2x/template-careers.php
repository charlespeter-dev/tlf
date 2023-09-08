<?php

/**
 * Template Name: v2 / Careers
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

get_header() ?>

<div class="bootstrap-container careers">

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

    <?php if ($top_content): ?>
        <section class="top-content py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col-lg-6">
                            <video src="<?= $top_content['video_url'] ?>" poster="<?= $top_content['video_image'] ?>"
                                class="img-fluid" controls></video>
                        </div>
                        <div class="col-lg-6">
                            <?= $top_content['content'] ?>
                        </div>
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
                                <h2 class="callout-with-cards-main-heading d-block text-center mb-4">
                                    <?= $stacked_cards['main_heading'] ?>
                                </h2>
                            <?php endif ?>

                            <?php if (isset($stacked_cards['sub_heading']) && $stacked_cards['sub_heading']): ?>
                                <p class="callout-with-cards-sub-heading d-block text-center mb-5">
                                    <?= $stacked_cards['sub_heading'] ?>
                                </p>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="row row-cols-1 row row-cols-md-3">
                        <?php foreach ($stacked_cards['cards'] as $k => $item): ?>
                            <div class="col">
                                <div class="d-flex flex-column">
                                    <img class="callout-with-cards-icon" src="<?= $item['icon'] ?>" alt="">
                                    <div>
                                        <p class="callout-with-cards-headline mb-3">
                                            <strong>
                                                <?= $item['headline'] ?>
                                            </strong>
                                        </p>

                                        <p class="callout-with-cards-excerpt">
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

    <?php if ($faces_of_tlf): ?>

        <section class="faces-of-tlf py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <?php if (isset($faces_of_tlf['main_heading']) && $faces_of_tlf['main_heading']): ?>
                                <h2 class="faces-of-tlf-main-heading d-block text-center mb-4">
                                    <?= $faces_of_tlf['main_heading'] ?>
                                </h2>
                            <?php endif ?>

                            <?php if (isset($faces_of_tlf['sub_heading']) && $faces_of_tlf['sub_heading']): ?>
                                <p class="faces-of-tlf-sub-heading d-block text-center mb-5">
                                    <?= $faces_of_tlf['sub_heading'] ?>
                                </p>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 gy-4">

                        <?php foreach ($faces_of_tlf['faces'] as $k => $item): ?>

                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($item['face']), '_2x-carousel-news') ?>"
                                        class="img-top" alt="">
                                    <div class="card-body position-relative wpk-box-brand">
                                        <p class="sub-title">
                                            <?= __('Faces of TLF') ?>
                                        </p>
                                        <p class="name">
                                            <?= _2x_format_title($item['face']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach ?>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mt-5 mb-4 text-center">
                                <a class="btn btn-primary" href="<?= get_the_permalink(get_page_by_path('faces-of-tlf', 'OBJECT', 'v2')->ID) ?>">
                                    <?= __('Show More') ?>
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

<?= get_footer() ?>