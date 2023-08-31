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

wp_reset_query();

$customers_ids = $customers_query->posts;

$cards_ids = $customers_ids;

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                    alt="">

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
                        <a class="btn btn-primary" href="<?= $cta['url'] ?>">
                            <?= $cta['title'] ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php if (isset($cards_ids)): ?>
        <section class="cards-overview my-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">

                        <?php foreach ($cards_ids as $card_id): ?>
                            <div class="col">
                                <a href="<?= get_the_permalink($card_id) ?>">
                                    <div class="card h-100">
                                        <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($card_id), '_2x-card-customers') ?>"
                                            class="img-fluid" alt="">

                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= strtolower(get_the_title($card_id)) ?>
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
        </section>
    <?php endif ?>

</div>

<?= get_footer() ?>