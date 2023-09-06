<?php

/**
 * Template Name: 2x - Homepage
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

/**
 * hero carousels
 */

$hero_carousels = get_field('hero_carousel', $post->ID);

/**
 * customer logo
 */

$customer_logo = get_field('customer_logo', $post->ID);

$customer_logo_splitted = $customer_logo['logo_detail'] ? array_chunk($customer_logo['logo_detail'], 6) : [];


/**
 * callout with cards
 */

$callout_with_cards = get_field('callout_with_cards', $post->ID);


/**
 * testimonials
 */

$testimonials = get_field('testimonials', $post->ID);

/**
 * featured post
 */

$featured_post = get_field('featured_post', $post->ID);

/**
 * news
 */

$news_main_heading = get_field('news_main_heading', $post->ID);

$news_items = get_field('news_items', $post->ID);

/**
 * resources_callout
 */

$resources_callout = get_field('resources_callout', $post->ID);

/**
 * footer_callout_banner
 */

$footer_callout_banner = get_field('footer_callout_banner', $post->ID);


get_header() ?>

<?php if ($hero_carousels) : ?>
    <section class="bootstrap-container">
        <div class="hero-carousels">
            <div id="_2x-carousel-hero" class="carousel carousel-fade" data-bs-pause="false" data-bs-interval="10000" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($hero_carousels as $k => $item) : ?>
                        <div class="carousel-item <?= ($k == 0) ? 'active' : '' ?>">

                            <div class="row-container">

                                <div class="single-h-padding limit-width position-relative">

                                    <img src="<?= wp_get_attachment_image_url($item['background_image'], '_2x-carousel-hero') ?>" class="full-width" alt="">

                                    <div class="_2x-hero-content">
                                        <div class="mb-3">
                                            <h2 class="mb-0"><?= $item['main_heading'] ?></h2>
                                        </div>
                                        <div class="mb-4">
                                            <div class="sub-heading"><?= $item['sub_heading'] ?></div>
                                        </div>
                                        <div>
                                            <a class="btn btn-primary" href="<?= $item['cta_url'] ?>"><?= $item['cta_text'] ?></a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <?php if (count($hero_carousels) > 1) : ?>
                    <div class="carousel-indicators">
                        <?php foreach ($hero_carousels as $k => $item) : ?>
                            <button type="button" data-bs-target="#_2x-carousel-hero" data-bs-slide-to="<?= $k ?>" class="<?= ($k == 0) ? 'active' : '' ?>"></button>
                        <?php endforeach ?>

                    </div>
                <?php endif ?>

            </div>
        </div>
    </section>
<?php endif ?>

<?php if ($customer_logo || $callout_with_cards) : ?>
    <div class="row-container">
        <div class="single-h-padding limit-width">


            <?php if ($customer_logo) : ?>

                <section class="bootstrap-container">
                    <div class="customer-logo py-5">

                        <div class="py-4 text-center">
                            <h2 class="mb-0"><?= $customer_logo['logo_heading'] ?></h2>
                        </div>

                        <div class="_2x-carousel slide container" data-bs-pause="false" data-bs-interval="3000" data-bs-ride="carousel">
                            <div class="carousel-inner w-100 p-0 m-0">

                                <?php foreach ($customer_logo['logo_detail'] as $k => $item) : ?>

                                    <div class="carousel-item <?= $k == 0 ? 'active' : '' ?>">
                                        <div class="col col-md-3 col-lg-2 p-0 m-0">
                                            <div class="card card-body p-0 m-0">
                                                <img class="img-fluid p-0 m-0" src="<?= $item['logo_image'] ?>" alt="">
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach ?>

                            </div>

                        </div>
                    </div>

                </section>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    $('._2x-carousel .carousel-item').each(function() {
                        var minPerSlide = 6;
                        var next = $(this).next();
                        if (!next.length) {
                            next = $(this).siblings(':first');
                        }
                        next.children(':first-child').clone().appendTo($(this));

                        for (var i = 0; i < minPerSlide; i++) {
                            next = next.next();
                            if (!next.length) {
                                next = $(this).siblings(':first');
                            }

                            next.children(':first-child').clone().appendTo($(this));
                        }
                    });
                </script>

            <?php endif ?>

            <?php if ($callout_with_cards) : ?>

                <section class="bootstrap-container">
                    <div class="callout-with-cards py-5">
                        <div class="row">
                            <div class="col">
                                <h2 class="d-block text-center mb-4"><?= $callout_with_cards['main_heading'] ?></h2>
                                <p class="d-block text-center mb-5"><?= $callout_with_cards['sub_heading'] ?></p>
                            </div>
                        </div>
                        <div class="row gy-4">
                            <?php foreach ($callout_with_cards['cards'] as $k => $item) : ?>
                                <div class="col">
                                    <div class="card h-100 wpk-box-brand">

                                        <div class="card-body">
                                            <img src="<?= $item['icon'] ?>" alt="">
                                            <hr>
                                            <p class="headline mb-3"><strong><?= $item['headline'] ?></strong></p>
                                            <p class="excerpt"><?= $item['excerpt'] ?></p>
                                        </div>
                                        <div class="card-footer mb-4">
                                            <a href="<?= $item['cta_url'] ?>"><?= $item['cta_text'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </section>

            <?php endif ?>

        </div>
    </div>
<?php endif ?>

<?php if ($testimonials) : ?>
    <section class="bootstrap-container">
        <div class="testimonials">
            <div id="_2x-carousel-testimonials" class="carousel slide">
                <div class="carousel-inner">

                    <?php foreach ($testimonials as $k => $item) : ?>

                        <div class="carousel-item <?= ($k == 0) ? 'active' : '' ?>">

                            <div class="row-container">

                                <div class="single-h-padding limit-width position-relative">

                                    <img src="<?= wp_get_attachment_image_url($item['background_image'], '_2x-carousel-testimonials') ?>" class="full-width" alt="">

                                    <div class="_2x-carousel-testimonials-content">
                                        <div class="icon-brand-logo mb-3"><img src="<?= $item['company_icon'] ?>" alt=""></div>
                                        <div class="company-name mb-4"><?= $item['company_name'] ?></div>
                                        <div class="mb-4"><?= $item['quote'] ?></div>
                                        <div class="source"><?= $item['source'] ?></div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    <?php endforeach ?>

                    <?php if (count($testimonials) > 1) : ?>
                        <div class="carousel-indicators">
                            <?php foreach ($testimonials as $k => $item) : ?>
                                <button type="button" data-bs-target="#_2x-carousel-testimonials" data-bs-slide-to="<?= $k ?>" class="<?= ($k == 0) ? 'active' : '' ?>"></button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                </div>



            </div>
        </div>
    </section>
<?php endif ?>


<?php if ($featured_post || $news_items || $resources_callout) : ?>

    <div class="row-container">
        <div class="single-h-padding limit-width">

            <?php if ($featured_post) : ?>
                <section class="bootstrap-container">
                    <div class="featured-post py-5">
                        <div class="row">
                            <div class="col-lg-5">
                                <img src="<?= $featured_post['left_image'] ?>" class="img-fluid" alt="">
                            </div>
                            <div class="col-lg-7">
                                <p class="post-type"><?= $featured_post['post_type'] ?></p>
                                <h2 class="main-heading mb-4"><?= $featured_post['main_heading'] ?></h2>
                                <p class="sub-heading"><?= $featured_post['sub_heading'] ?></p>
                                <div class="cta mt-4">
                                    <a href="<?= get_the_permalink($featured_post['cta_url'][0]->ID) ?>"><?= $featured_post['cta_text'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif ?>

            <?php if ($news_items) : ?>
                <section class="bootstrap-container">
                    <div class="news">
                        <div class="row mb-3">
                            <div class="col">
                                <h2><?= $news_main_heading ?></h2>
                            </div>
                        </div>

                        <div class="row gy-4">

                            <?php foreach ($news_items as $k => $item) : ?>

                                <div class="col">
                                    <a href="<?= get_the_permalink($item['the_post']->ID) ?>">
                                        <div class="card h-100">
                                            <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($item['the_post']->ID), '_2x-carousel-news') ?>" class="img-top" alt="">
                                            <div class="card-body position-relative wpk-box-brand">
                                                <p class="date"><?= get_the_date('F j, Y', $item['the_post']->ID) ?></p>
                                                <p class="post-title"><?= $item['the_post']->post_title ?></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            <?php endforeach ?>
                        </div>
                    </div>
                </section>
            <?php endif ?>


            <?php if ($resources_callout) : ?>
                <section class="bootstrap-container">
                    <div class="resources-callout py-5">
                        <div id="_2x-carousel-resources-callout" class="carousel slide">
                            <div class="carousel-inner">

                                <?php foreach ($resources_callout as $k => $item) : ?>
                                    <div class="carousel-item <?= ($k == 0) ? 'active' : '' ?>">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="text-center">
                                                    <h2 class="main-heading"><?= $item['main_heading'] ?></h2>
                                                    <p class="sub-heading"><?= $item['sub_heading'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 order-1 order-lg-0">

                                                <div class="main-content">
                                                    <?= $item['main_content'] ?>
                                                </div>

                                                <div class="cta mt-4">
                                                    <a href="<?= $item['cta_url'] ?>"> <?= $item['cta_text'] ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i></a>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 order-0 order-lg-1">
                                                <img class="mb-4" src="<?= wp_get_attachment_image_url($item['right_image'], '_2x-carousel-resources-callout') ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>

                            </div>

                            <?php if (count($resources_callout) > 1) : ?>
                                <div class="carousel-indicators">
                                    <?php foreach ($resources_callout as $k => $item) : ?>
                                        <button type="button" data-bs-target="#_2x-carousel-resources-callout" data-bs-slide-to="<?= $k ?>" class="<?= ($k == 0) ? 'active' : '' ?>"></button>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>
                </section>
            <?php endif ?>

        </div>
    </div>

<?php endif ?>


<?php if (isset($options['footer_callout_banner']) && $options['footer_callout_banner']) : ?>
    <section class="bootstrap-container">
        <div class="footer-callout-banner">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">
                    <img src="<?= wp_get_attachment_image_url($options['footer_callout_banner']['background_image'], '_2x_footer-callout-banner') ?>" class="full-width" alt="">

                    <div class="footer-callout-banner-content">
                        <div class="main-heading mb-4"><?= $options['footer_callout_banner']['main_heading'] ?></div>
                        <div class="cta"><a class="btn btn-primary" href="<?= $options['footer_callout_banner']['cta']['url'] ?>"><?= $options['footer_callout_banner']['cta']['title'] ?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>

<?php get_footer() ?>