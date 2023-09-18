<?php

/**
 * Template Name: v2 / About Us
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * css specifics
 */

wp_enqueue_style('_2x-css-about-us', sprintf('%s/2x/assets/css/template-about-us.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<div class="bootstrap-container about-us">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">
                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner') ?>"
                    class="small-height" alt="">
                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-0">
                                <h2 class="mb-0">
                                    <?= get_the_title($post->ID) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
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

    <?php if (isset($boards) && $boards): ?>
        <section class="boards my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <?php foreach ($boards as $board): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="board-name blue text-center py-5 my-5">
                                    <?= $board['board_name'] ?>
                                </h2>
                            </div>
                        </div>

                        <?php foreach ($board['managers'] as $k => $manager): ?>

                            <div class="row <?= ($k) ? 'mt-5' : '' ?>">
                                <div class="col-lg-3">
                                    <img class="img-fluid mb-4"
                                        src="<?= wp_get_attachment_image_url($manager['profile_picture'], '_2x-carousel-news') ?>"
                                        alt="">
                                </div>
                                <div class="col-lg-9">
                                    <div class="manager-name">
                                        <h3 class="blue">
                                            <?= $manager['manager_name'] ?>
                                        </h3>
                                    </div>
                                    <div class="manager-title">
                                        <?= $manager['manager_title'] ?>
                                    </div>
                                    <div class="manager-description">
                                        <?= $manager['manager_description'] ?>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach ?>

                    <?php endforeach ?>

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