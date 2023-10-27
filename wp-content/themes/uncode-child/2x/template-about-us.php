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

// $main_content = '';
// $boards = [];
// $partner_logo = [];

get_header() ?>

<script>
    const mql = window.matchMedia("(min-width: 992px)");
    const attach = (e) => {
        if (e.matches) {
            document.body.classList.remove('is-mobile');
            document.body.classList.add('is-desktop');
        } else {
            document.body.classList.remove('is-desktop');
            document.body.classList.add('is-mobile');
        }
    }

    mql.addEventListener('change', attach);
</script>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_url($background_image, '_2x_small-banner') ?>');
        }
    }
</style>

<div class="bootstrap-container about-us">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6">

                            <?php if (isset($main_heading) && $main_heading): ?>
                                <div class="mb-3">
                                    <h1 class="mb-0">
                                        <?= $main_heading ?>
                                    </h1>
                                </div>
                            <?php endif ?>

                            <?php if (isset($sub_heading) && $sub_heading): ?>
                                <div class="mb-4">
                                    <div class="sub-heading">
                                        <?= $sub_heading ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($cta) && $cta): ?>
                                <div>
                                    <a class="btn btn-primary" href="<?= $cta['url'] ?>" target="_blank">
                                        <?= $cta['title'] ?>
                                    </a>
                                </div>
                            <?php endif ?>

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
                            <svg id="Group_48604" data-name="Group 48604" xmlns="http://www.w3.org/2000/svg" width="32"
                                height="32" viewBox="0 0 28 28">
                                <circle id="Ellipse_500" data-name="Ellipse 500" cx="14" cy="14" r="14" fill="#003375" />
                                <path id="Icon_ionic-ios-arrow-down" data-name="Icon ionic-ios-arrow-down"
                                    d="M5.4,1.861,9.483,5.947a.768.768,0,0,0,1.09,0,.778.778,0,0,0,0-1.093L5.947.225A.77.77,0,0,0,4.883.2L.225,4.85a.772.772,0,0,0,1.09,1.093Z"
                                    transform="translate(10.026 19.293) rotate(-90)" fill="#fff" />
                            </svg>
                        </div>
                        <div class="swiper-button-next">
                            <svg id="Group_48605" data-name="Group 48605" xmlns="http://www.w3.org/2000/svg" width="32"
                                height="32" viewBox="0 0 28 28">
                                <circle id="Ellipse_500" data-name="Ellipse 500" cx="14" cy="14" r="14" fill="#003375" />
                                <path id="Icon_ionic-ios-arrow-down" data-name="Icon ionic-ios-arrow-down"
                                    d="M5.4,4.312,9.483.227a.768.768,0,0,1,1.09,0,.778.778,0,0,1,0,1.093L5.947,5.948a.77.77,0,0,1-1.064.022L.225,1.323A.772.772,0,1,1,1.315.23Z"
                                    transform="translate(11.801 19.293) rotate(-90)" fill="#fff" />
                            </svg>
                        </div>
                        <div class="time-line"></div>
                    </div>

                </div>
            </div>
        </section>

        <script>
            window.addEventListener('DOMContentLoaded', function () {

                attach(mql);

                const isDesktop = document.querySelector('body.is-desktop');

                var slidesPerView = 1;
                if (isDesktop) {
                    slidesPerView = 5;
                }

                const swiperHistory = new Swiper("._2x-swiper-history", {
                    loop: true,
                    slidesPerView: slidesPerView,
                    spaceBetween: 30,
                    centeredSlides: true,
                    grid: {
                        rows: 1
                    },
                    pagination: {
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

                    <?php foreach ($boards as $i => $board): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="board-name blue text-center pb-5 mb-5 <?= ($i == 1) ? 'pt-5 mt-5' : '' ?>">
                                    <?= $board['board_name'] ?>
                                </h2>
                            </div>
                        </div>

                        <?php foreach ($board['managers'] as $k => $manager): ?>

                            <div class="row <?= ($k) ? 'mt-5' : '' ?>">
                                <div class="col-lg-3">
                                    <img class="img-fluid mb-4"
                                        src="<?= wp_get_attachment_image_url($manager['profile_picture'], '_2x-carousel-news') ?>"
                                        alt="" loading="lazy">
                                </div>
                                <div class="col-lg-9">
                                    <div class="manager-name">
                                        <h3 class="blue">
                                            <?= $manager['manager_name'] ?>
                                        </h3>
                                    </div>
                                    <div class="manager-title mb-4">
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
                                    <img class="img-fluid p-0 m-0" src="<?= $item['logo_image'] ?>" alt="" loading="lazy">
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

                const isDesktop = document.querySelector('body.is-desktop');

                var slidesPerView = 1;
                if (isDesktop) {
                    slidesPerView = 5;
                }

                const swiperPartnerLogo = new Swiper("._2x-swiper", {
                    autoplay: {
                        delay: 3000,
                        pauseOnMouseEnter: false,
                        disableOnInteraction: false
                    },
                    loop: true,
                    slidesPerView: slidesPerView,
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