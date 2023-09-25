<?php

/**
 * Template Name: v2 / News
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * post - news
 */

$news_query = new WP_Query([
    'category_name' => 'news',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'fields' => 'ids',
    'post_status' => 'publish'
]);

wp_reset_query();

$news_ids = $news_query->posts;

$cards_ids = $news_ids;

/**
 * specific css
 */

wp_enqueue_style('_2x-css-template-news', sprintf('%s/2x/assets/css/template-news.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>');
        }
    }
</style>

<div class="bootstrap-container">

    <section class="hero-carousels single news">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6">

                            <?php if (isset($main_heading) && $main_heading): ?>
                                <div class="mb-3">
                                    <h2 class="mb-0">
                                        <?= $main_heading ?>
                                    </h2>
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
                                    <a class="btn btn-primary" href="<?= $cta['url'] ?>">
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

    <?php if (isset($cards_ids)): ?>
        <section class="cards-overview news">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row row-cols-1 row-cols-md-2 g-5">

                        <?php foreach ($cards_ids as $card_id): ?>

                            <div class="col">

                                <div class="card-news">
                                    <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($card_id), '_2x-card-news') ?>"
                                        class="img-fluid" alt="" loading="lazy">
                                    <div class="card-news-body position-relative wpk-box-brand">
                                        <div>
                                            <div class="card-news-body-date">
                                                <?= get_the_date('F d, Y', $card_id) ?>
                                            </div>
                                            <div class="card-news-body-title">
                                                <?= get_the_title($card_id) ?>
                                            </div>
                                        </div>
                                        <div class="card-news-body-cta">
                                            <a href="<?= get_the_permalink($card_id) ?>">Read More</a>
                                        </div>
                                    </div>

                                </div>
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