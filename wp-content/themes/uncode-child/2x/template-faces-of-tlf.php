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

/**
 * specific css
 */

wp_enqueue_style('_2x-css-template-faces-of-tlf', sprintf('%s/2x/assets/css/template-faces-of-tlf.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>');
        }
    }
</style>

<div class="bootstrap-container">

    <section class="hero-carousels single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <h1 class="mb-0">
                                    <?= $main_heading ?>
                                </h1>
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
        <section class="faces-of-tlf my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-4">

                        <?php foreach ($cards_ids as $card_id): ?>
                            <div class="col">
                                <a href="<?= get_the_permalink($card_id) ?>">
                                    <div class="card h-100">
                                        <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($card_id), '_2x-carousel-news') ?>"
                                            class="img-top" alt="" loading="lazy">
                                        <div class="card-body position-relative wpk-box-brand">
                                            <p class="sub-title">
                                                <?= __('Faces of TLF') ?>
                                            </p>
                                            <p class="name">
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
</div>

<?= get_footer() ?>