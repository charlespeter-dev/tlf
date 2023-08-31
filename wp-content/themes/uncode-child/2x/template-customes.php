<?php

/**
 * Template Name: 2x - Customers
 */

if (is_admin())
    return;

global $post;

$fields = get_fields($post->ID);

extract($fields);

/**
 * customers
 */

$customers_query = new WP_Query([
    'post_type' => 'portfolio',
    'posts_per_page' => -1,
    'orderby' => 'rand',
    'fields' => 'ids',
    'post_status' => 'publish'
]);

$customers_ids = $customers_query->posts;

get_header() ?>

<!-- begin: custom 2x css + override uncode specific styles -->
<style>
    @media (max-width: 959px) {
        .row div[class*=col-lg-] {
            padding: unset;
        }
    }

    @media (min-width: 960px) {

        .chrome .col-lg-2,
        .chrome .col-lg-3,
        .chrome .col-lg-4,
        .chrome .col-lg-5,
        .chrome .col-lg-6,
        .chrome .col-lg-7,
        .chrome .col-lg-8,
        .chrome .col-lg-9 {
            height: unset;
        }
    }
</style>
<!-- end: override uncode specific styles -->

<section class="bootstrap-container">

    <div class="hero-carousels hero default">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= $background_image ?>" class="full-width" alt="">

                <div class="_2x-hero-content">
                    <div class="mb-3">
                        <h2 class="mb-0">
                            <?= $main_heading ?>
                        </h2>
                    </div>
                    <div class="mb-4">
                        <div class="sub-heading">
                            <?= $sub_heading ?>
                        </div>
                    </div>
                    <div>
                        <a class="btn btn-primary" href="<?= $cta['url'] ?>"><?= $cta['title'] ?></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="customers my-5">
        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">

                    <?php foreach ($customers_ids as $customer_id): ?>
                        <div class="col">
                            <a href="<?= get_the_permalink($customer_id) ?>">
                                <div class="card h-100">
                                    <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($customer_id), '_2x-card-customers') ?>"
                                        class="img-fluid" alt="">

                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= strtolower(get_the_title($customer_id)) ?>
                                        </h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up
                                            the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>

                </div>

            </div>
        </div>
    </div>


</section>

<?= get_footer() ?>