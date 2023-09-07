<?php

/**
 * Template Name: v2 / Faces of TLF
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

$cards_ids = [];

if (isset($faces) && $faces) {
    foreach ($faces as $face) {
        $cards_ids[] = $face['face'];
    }
}

function _2x_format_title($post_id)
{
    $post = get_post($post_id);
    $title = explode('-', $post->post_title)[1];
    return ucwords($title);
}

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="">

                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <h2 class="mb-0">
                                    <?= $main_heading ?>
                                </h2>
                            </div>
                            <div class="mb-4">
                                <div class="sub-heading">
                                    <?= $sub_heading ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                                <?= __('Faces of TLF') ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= _2x_format_title($card_id) ?>
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