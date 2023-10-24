<?php

/**
 * Template Name: v2 / Post / Landing Page
 * Template Post Type: post
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * related resources
 */

$related_resources = _2x_related_resources($post->ID);

/**
 * css specifics
 */

wp_enqueue_style('_2x-css-single-article', sprintf('%s/2x/assets/css/single-article.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<div class="bootstrap-container post single-article">

    <section class="hero-carousels single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-8">

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

    <section class="main-content my-5">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">
                <div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div>
                                <img class="img-fluid" src="<?= get_the_post_thumbnail_url($post->ID, 'full') ?>" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?= $main_content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php get_footer() ?>