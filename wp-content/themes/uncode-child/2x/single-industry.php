<?php

/**
 * Template Name: v2 / Industry / Single
 * Template Post Type: industry
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * collect featured testimonials
 */

$ft_collections = [];

if (isset($featured_testimonials['testimonials']) && $featured_testimonials['testimonials']) {

    foreach ($featured_testimonials['testimonials'] as $ft) {

        $post_id = $ft['customer'];

        $testimonials = get_field('testimonials', $post_id);

        if ($testimonials) {
            $ft_collections = array_merge($ft_collections, $testimonials);
        }
    }
}

/**
 * related resources
 */

$related_resources = _2x_related_resources($post->ID);

/**
 * specific css
 */

wp_enqueue_style('_2x-css-single-industry', sprintf('%s/2x/assets/css/single-industry.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());
wp_enqueue_style('_2x-css-swiper-bundle', sprintf('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css'), ['_2x-css-single-industry'], time());
wp_enqueue_script('_2x-js-swiper-bundle', sprintf('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js'), [], false, true);

get_header() ?>

<div class="bootstrap-container industry single">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($banner['background_image'], '_2x-carousel-hero') ?>"
                    class="full-width" alt="">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-7">
                            <?php if (isset($banner['main_heading']) && $banner['main_heading']): ?>
                                <div class="mb-3">
                                    <h2 class="mb-0">
                                        <?= $banner['main_heading'] ?>
                                    </h2>
                                </div>
                            <?php endif ?>

                            <?php if (isset($banner['sub_heading']) && $banner['sub_heading']): ?>
                                <div>
                                    <div class="sub-heading mb-4">
                                        <?= $banner['sub_heading'] ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($banner['cta']) && $banner['cta']): ?>
                                <div>
                                    <a class="btn btn-primary" href="<?= $banner['cta']['url'] ?>">
                                        <?= $banner['cta']['title'] ?>
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <?php if (isset($featured_testimonials['testimonials']) && $featured_testimonials['testimonials']): ?>
        <section class="featured-testimonials my-5">

            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <?php if (isset($featured_testimonials['main_heading']) && $featured_testimonials['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="blue text-center">
                                    <?= $featured_testimonials['main_heading'] ?>
                                </h2>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($ft_collections) && $ft_collections): ?>

                        <div class="swiper _2x-swiper-testimonials">
                            <div class="swiper-wrapper">

                                <?php foreach ($ft_collections as $ft): ?>

                                    <div class="swiper-slide my-5">

                                        <div class="quote">
                                            <?php if (isset($ft['quote']) && $ft['quote']): ?>
                                                <div class="quote-quote">
                                                    <?= $ft['quote'] ?>
                                                </div>
                                            <?php endif ?>

                                            <?php if (isset($ft['quote_by']) && $ft['quote_by']): ?>
                                                <div class="quote-by">
                                                    —
                                                    <?= $ft['quote_by'] ?>
                                                </div>
                                            <?php endif ?>

                                            <?php if (isset($ft['quote_by_title']) && $ft['quote_by_title']): ?>
                                                <div class="quote-by-title">
                                                    <p>
                                                        <?= $ft['quote_by_title'] ?>
                                                    </p>
                                                </div>
                                            <?php endif ?>

                                            <?php if (isset($ft['cta']['url']) && $ft['cta']['url']): ?>
                                                <div class="quote-cta">
                                                    <p>
                                                        <a href="<?= $ft['cta']['url'] ?>">
                                                            <?= $ft['cta']['title'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                                        </a>
                                                    </p>
                                                </div>
                                            <?php endif ?>
                                        </div>

                                    </div>

                                <?php endforeach ?>

                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                        <script>
                            window.addEventListener('DOMContentLoaded', function () {

                                const swiperTestimonials = new Swiper("._2x-swiper-testimonials", {
                                    loop: true,
                                    slidesPerView: 1,
                                    spaceBetween: 30,
                                    centeredSlides: true,
                                    grid: {
                                        rows: 1
                                    },
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },
                                    navigation: {
                                        nextEl: ".swiper-button-next",
                                        prevEl: ".swiper-button-prev",
                                    },
                                });
                            });
                        </script>

                    <?php endif ?>

                </div>
            </div>

        </section>
    <?php endif ?>

</div>

<?php get_footer() ?>