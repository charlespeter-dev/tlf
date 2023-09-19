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
 * css/js specifics
 * - includes swiper js v8
 */

wp_enqueue_style('_2x-css-about-us', sprintf('%s/2x/assets/css/template-about-us.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());
wp_enqueue_style('_2x-css-swiper-bundle', sprintf('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css'), ['_2x-css-about-us'], time());
wp_enqueue_script('_2x-js-swiper-bundle', sprintf('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js'), [], false, true);

/**
 * debug
 */

$main_content = '';
$boards = [];
$partner_logo = [];

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

    <?php if (isset($main_content) && $main_content): ?>
        <section class="main-content my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">
                    <div>
                        <?= $main_content ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($our_history['histories']) && $our_history['histories']): ?>
        <section class="our-history py-5 mb-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                <div class="row">
                    <div class="col">
                        <h2 class="blue text-center mb-5">
                            <?= $our_history['main_heading'] ?>
                        </h2>
                    </div>
                </div>

                    <div class="swiper _2x-swiper-history">
                        <div class="swiper-wrapper">
                            <?php foreach ($our_history['histories'] as $history): ?>
                                <div class="swiper-slide">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="year mb-3">
                                            <?= $history['year'] ?>
                                        </div>
                                        <div>
                                            <div class="circle">
                                                <div class="circle-inner"></div>
                                            </div>
                                        </div>
                                        <div class="excerpt mt-3">
                                            <?= $history['excerpt'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#003375"
                                class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                            </svg>
                        </div>
                        <div class="swiper-button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#003375"
                                class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                            </svg>
                        </div>
                        <div class="time-line"></div>
                    </div>



                </div>
            </div>
        </section>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                const swiperHistory = new Swiper("._2x-swiper-history", {
                    loop: true,
                    slidesPerView: 5,
                    spaceBetween: 30,
                    centeredSlides: true,
                    grid: {
                        rows: 1
                    },
                    pagination: {                       //pagination(dots)
                        el: '.swiper-pagination',
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
            });
        </script>
    <?php endif ?>


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

    <?php if (isset($partner_logo['logo_detail']) && $partner_logo['logo_detail']): ?>
        <section class="partner-logo mt-5 pb-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <div class="py-4 text-center">
                        <h2 class="blue mb-0">
                            <?= $partner_logo['logo_heading'] ?>
                        </h2>
                    </div>

                    <div class="swiper _2x-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($partner_logo['logo_detail'] as $k => $item): ?>
                                <div class="swiper-slide">
                                    <img class="img-fluid p-0 m-0" src="<?= $item['logo_image'] ?>" alt="">
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="cta-container my-3">
                                <a href="<?= $partner_logo['cta']['url'] ?>">
                                    <?= $partner_logo['cta']['title'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <style>
            ._2x-swiper .swiper-wrapper {
                align-items: center;
            }
        </style>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                const swiperPartnerLogo = new Swiper("._2x-swiper", {
                    autoplay: {
                        delay: 5000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false
                    },
                    loop: true,
                    slidesPerView: 5,
                    spaceBetween: 30,
                    grid: {
                        rows: 1
                    },
                });
            });
        </script>
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