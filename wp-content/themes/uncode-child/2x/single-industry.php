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
 * featured resource
 */

$fresource = [];

if (isset($featured_resource['resource']) && $featured_resource['resource']) {
    $post_id = $featured_resource['resource'];
    $fresource = get_fields($post_id);

    /**
     * category
     */

    $categories = get_the_terms($post_id, 'resource_category');
    foreach ($categories as $cats) {
        $fresource['category'][] = $cats->name;
    }

    /**
     * title
     */

    $fresource['title'] = get_the_title($post_id);

    /**
     * excerpt
     */

    $fresource['excerpt'] = get_the_excerpt($post_id);

    /**
     * permalink
     */

    $fresource['permalink'] = get_the_permalink($post_id);
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
    window.addEventListener('DOMContentLoaded', function () {
        attach(mql);
    });
</script>

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

    <?php if (isset($tabbed['tabs']) && $tabbed['tabs']): ?>
        <section class="tabbed py-5 my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <?php if (isset($tabbed['main_heading']) && $tabbed['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="mb-4 blue text-center">
                                    <?= $tabbed['main_heading'] ?>
                                </h2>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($tabbed['sub_heading']) && $tabbed['sub_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <div class="sub-heading">
                                    <?= $tabbed['sub_heading'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <!-- begin: tabbed desktop-only -->
                    <div class="desktop-only mt-5">
                        <nav>
                            <div class="nav nav-tabs nav-justified">

                                <?php foreach ($tabbed['tabs'] as $key => $tab): ?>
                                    <div class="nav-link <?= !$key ? 'active' : '' ?>" data-bs-toggle="tab"
                                        data-bs-target="#tab-content-<?= $key ?>">
                                        <span>
                                            <?= $tab['label'] ?>
                                        </span>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </nav>

                        <div class="tab-content py-3">
                            <?php foreach ($tabbed['tabs'] as $key => $tab): ?>

                                <div class="tab-pane fade <?= !$key ? 'show active' : '' ?>" id="tab-content-<?= $key ?>">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6">
                                            <?= $tab['content'] ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <img class="img-fluid"
                                                src="<?= wp_get_attachment_image_url($tab['right_image'], '_2x-industry-single-tabbed') ?>"
                                                alt="">
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach ?>
                        </div>

                    </div>
                    <!-- end: tabbed desktop-only -->

                    <!-- begin: tabbed mobile-only -->
                    <div class="mobile-only mt-5">
                        <div class="accordion" id="acc-mobile">

                            <?php foreach ($tabbed['tabs'] as $key => $tab): ?>
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <div class="accordion-button <?= $key ? 'collapsed' : '' ?>" data-bs-toggle="collapse"
                                            data-bs-target="#acc-content-<?= $key ?>">
                                            <div>
                                                <span>
                                                    <?= $tab['label'] ?>
                                                </span>
                                                <span class="plus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                                                    </svg>
                                                </span>
                                                <span class="dash">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-collapse collapse <?= !$key ? 'show' : '' ?>"
                                        id="acc-content-<?= $key ?>">
                                        <div class="accordion-body">
                                            <div class="row flex-column">
                                                <div class="col-lg-6">
                                                    <?= $tab['content'] ?>
                                                </div>
                                                <div class="col-lg-6 mt-4">
                                                    <img class="img-fluid"
                                                        src="<?= wp_get_attachment_image_url($tab['right_image'], '_2x-industry-single-tabbed') ?>"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>

                        </div>
                    </div>
                    <!-- end: tabbed mobile-only -->

                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($callout['callouts']) && $callout['callouts']): ?>
        <section class="callout">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <?php if (isset($callout['main_heading']) && $callout['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <h2 class="mb-4 blue text-center">
                                    <?= $callout['main_heading'] ?>
                                </h2>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($callout['sub_heading']) && $callout['sub_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <div class="sub-heading">
                                    <?= $callout['sub_heading'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row mt-4">
                        <?php foreach ($callout['callouts'] as $item): ?>
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

    <?php if (isset($fresource['bottom_content']['left_thumbnail_image']) && $fresource['bottom_content']['left_thumbnail_image']): ?>
        <section class="featured-resource my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <hr>

                    <div class="row align-items-center mt-5">
                        <div class="col-lg-6">
                            <img src="<?= $fresource['bottom_content']['left_thumbnail_image'] ?>" alt="">
                        </div>
                        <div class="col-lg-6">
                            <div class="resource-type mb-4">
                                <?= implode(' / ', $fresource['category']) ?>
                            </div>
                            <div class="mb-4">
                                <h2 class="blue">
                                    <?= $fresource['title'] ?>
                                </h2>
                            </div>
                            <p>
                                <?= $fresource['excerpt'] ?>
                            </p>
                            <div>
                                <a href="<?= $fresource['permalink'] ?>">
                                    <?= __('Download the Brochure') ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
                        </div>
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