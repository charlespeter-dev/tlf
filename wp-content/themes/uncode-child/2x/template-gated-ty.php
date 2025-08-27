<?php

/**
 * Template Name: v2 / Gated / Thank You
 * Template Post Type: ty
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

if ($fields)
    extract($fields);

// -------------------------------
// css specifics
// -------------------------------

wp_enqueue_style('_2x-css-template-gated-ty', sprintf('%s/2x/assets/css/template-gated-ty.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time(), 'screen');

// -------------------------------
// get 3 latest resources
// -------------------------------

$query_exclude = new WP_Query([
    'post_type' => ['post', 'page', 'resources'],
    'meta_key' => 'thank_you_page',
    'post_status' => 'publish',
    'meta_value' => $post->ID,
    'fields' => 'ids',
    'tax_query' => [
        'relation' => 'AND',
        [
            'taxonomy' => 'language',
            'field' => 'slug',
            'terms' => ['en', 'es', 'fr'],
            'operator' => 'IN',
            'include_children' => true
        ]
    ]
]);

wp_reset_postdata();

$query = new WP_Query([
    'post_type' => ['resources'],
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'order' => 'DESC',
    'orderby' => 'date',
    'post__not_in' => $query_exclude->posts,
    'fields' => 'ids',
    'tax_query' => [
        'relation' => 'AND',
        [
            'taxonomy' => 'language',
            'field' => 'slug',
            'terms' => ['en', 'es', 'fr'],
            'operator' => 'IN',
            'include_children' => true
        ]
    ]
]);

$post_ids = $query->posts;

wp_reset_postdata();

get_header() ?>

<div class="bootstrap-container gated-main">

    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">

            <section class="hero-carousels">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-7">

                            <?php if (isset($headline) && $headline): ?>
                                <div class="mb-4">
                                    <div class="headline">
                                        <?= $headline ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($main_heading) && $main_heading): ?>
                                <div class="mb-4">
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

            </section>

        </div>
    </div>

    <?php if (isset($post_ids) && $post_ids): ?>

        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <section class="latest-resources news">

                    <div class="row mb-5">
                        <div class="col-lg-8">
                            <h2>
                                <?= __('You may also be interested in…') ?>
                            </h2>
                        </div>
                        <div class="col-lg-4 show-more-top">
                            <div>
                                <a href="https://thelogicfactory.com/resources/" class="red cursor-init">
                                    Show More <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-lg-3 g-5">

                        <?php foreach ($post_ids as $post_id):

                            $terms = get_the_terms($post_id, 'resource_category');

                            ?>

                            <div class="col">

                                <a href="<?= get_the_permalink($post_id) ?>">
                                    <div class="card h-100">
                                        <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($post_id), '_2x-carousel-news') ?>"
                                            class="img-top" alt="" loading="lazy">
                                        <div class="card-body position-relative wpk-box-brand">
                                            <p class="date">
                                                <?= $terms[0]->name ?>
                                            </p>
                                            <p class="post-title">
                                                <?= get_the_title($post_id) ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>

                            </div>

                        <?php endforeach ?>

                    </div>

                </section>

            </div>
        </div>

    <?php endif ?>

</div>

<script>
    (function () {
        try {
            var script = document.createElement('script');
            if ('async') {
                script.async = true;
            }
            script.src = 'https://localhost:5555/browser-sync/browser-sync-client.js?v=3.0.2';
            if (document.body) {
                document.body.appendChild(script);
            } else if (document.head) {
                document.head.appendChild(script);
            }
        } catch (e) {
            console.error("Browsersync: could not append script tag", e);
        }
    })();
</script>

<?php get_footer() ?>