<?php

/**
 * Template Name: v2 / Resources
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

/**
 * search filter ID
 */

$search_filter_id = 0;
if (isset($hero_carousel[0]['search_filter_id']) && $hero_carousel[0]['search_filter_id']) {
    $search_filter_id = $hero_carousel[0]['search_filter_id'];
}

/**
 * resources
 */

$args = [
    'post_type' => 'resources',
    'order' => 'DESC',
    'orderby' => 'date',
    'post_status' => 'publish',
    'fields' => 'ids',
    'posts_per_page' => -1,
];

$_sf_s = '';

if (isset($_GET['_sf_s']) && $_GET['_sf_s']) {

    $_sf_s = $_GET['_sf_s'];

    $args['meta_query'] = [
        'relation' => 'OR',
        [
            'key' => 'main_content',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'top_content',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'bottom_content',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'bottom_content_right_content',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'infographic_summary_right_content',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'headline',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'main_heading',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
        [
            'key' => 'sub_heading',
            'value' => $_sf_s,
            'compare' => 'LIKE',
        ],
    ];

    if (isset($_GET['_sft_resource_category']) && $_GET['_sft_resource_category']) {

        $_sft_resource_category = $_GET['_sft_resource_category'];

        if (strpos($_sft_resource_category, ',') !== false) {
            $_sft_resource_category = explode(',', $_sft_resource_category);
        }

        $args['tax_query'] = [
            'relation' => 'AND',
            [
                'taxonomy' => 'resource_category',
                'field' => 'slug',
                'terms' => $_sft_resource_category,
            ]
        ];
    }
}

$resources_query = new WP_Query($args);

$resources_ids = $resources_query->posts;

wp_reset_query();

// ---------------------------------------
// search by title using 'LIKE' operator
// ---------------------------------------

if ($_sf_s) {
    function title_filter($where, &$wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('search_title')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_term)) . '%\'';
        }
        return $where;
    }

    add_filter('posts_where', 'title_filter', 10, 2);

    $args = [
        'post_type' => 'resources',
        'order' => 'DESC',
        'orderby' => 'date',
        'post_status' => 'publish',
        'fields' => 'ids',
        'posts_per_page' => -1,
        'search_title' => $_sf_s,
        'post__not_in' => $resources_ids,
    ];

    if (isset($_GET['_sft_resource_category']) && $_GET['_sft_resource_category']) {

        $_sft_resource_category = $_GET['_sft_resource_category'];

        if (strpos($_sft_resource_category, ',') !== false) {
            $_sft_resource_category = explode(',', $_sft_resource_category);
        }

        $args['tax_query'] = [
            'relation' => 'AND',
            [
                'taxonomy' => 'resource_category',
                'field' => 'slug',
                'terms' => $_sft_resource_category,
            ]
        ];
    }

    $by_title_query = new WP_Query($args);

    remove_filter('posts_where', 'title_filter', 10);

    $resources_ids = array_merge($resources_ids, $by_title_query->posts);

    wp_reset_query();
}

// ---------------------------------------
// search by taxonomies only
// ---------------------------------------

if (isset($_GET['_sft_resource_category']) && $_GET['_sft_resource_category'] && !$_sf_s) {

    $_sft_resource_category = $_GET['_sft_resource_category'];

    if (strpos($_sft_resource_category, ',') !== false) {
        $_sft_resource_category = explode(',', $_sft_resource_category);
    }

    $args['tax_query'] = [
        'relation' => 'AND',
        [
            'taxonomy' => 'resource_category',
            'field' => 'slug',
            'terms' => $_sft_resource_category,
        ]
    ];

    $by_tax_query = new WP_Query($args);

    $resources_ids = $by_tax_query->posts;
}

$resource_categories = [];

foreach ($resources_ids as $resource_id) {
    $categories = get_the_terms($resource_id, 'resource_category');
    foreach ($categories as $cats) {
        if (!in_array($cats->slug, ['visible', 'hidden', 'gated'])) {
            $resource_categories[$resource_id] = $cats->name;
        }
    }
}

$cards_ids = $resources_ids;

/**
 * specific css
 */

wp_enqueue_style('_2x-css-template-resources', sprintf('%s/2x/assets/css/template-resources.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<div class="bootstrap-container">

    <?php if ($hero_carousel): ?>
        <section class="hero-carousels resources">
            <div id="_2x-carousel-hero" class="carousel carousel-fade" data-bs-pause="false" data-bs-interval="10000"
                data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($hero_carousel as $k => $item): ?>
                        <div class="carousel-item <?= ($k == 0) ? 'active' : '' ?>"
                            style="background-image: url('<?= wp_get_attachment_image_url($item['background_image'], '_2x-carousel-hero') ?>');">

                            <div class="row-container">

                                <div class="single-h-padding limit-width position-relative">

                                    <img src="<?= wp_get_attachment_image_url($item['background_image'], '_2x-carousel-hero') ?>"
                                        class="full-width" alt="" loading="lazy">

                                    <div class="_2x-hero-content">
                                        <div class="row">

                                            <div class="col-lg-7">
                                                <div class="mb-3">

                                                    <?php if ($k == 0): ?>
                                                        <h1 class="mb-0">
                                                            <?= $item['main_heading'] ?>
                                                        </h1>
                                                    <?php else: ?>
                                                        <h2 class="mb-0">
                                                            <?= $item['main_heading'] ?>
                                                        </h2>
                                                    <?php endif ?>

                                                </div>
                                                <div class="mb-4">
                                                    <div class="sub-heading">
                                                        <?= $item['sub_heading'] ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a class="btn btn-primary" href="<?= $item['cta']['url'] ?>"
                                                        target="_blank">
                                                        <?= $item['cta']['title'] ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-lg-5">
                                                <img src="<?= wp_get_attachment_image_url($item['resource_carousel_thumbnail'], 'full') ?>"
                                                    class="img-fluid" alt="" loading="lazy">
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <?php if (count($hero_carousel) > 1): ?>
                    <div class="carousel-indicators">
                        <?php foreach ($hero_carousel as $k => $item): ?>
                            <button type="button" data-bs-target="#_2x-carousel-hero" data-bs-slide-to="<?= $k ?>"
                                class="<?= ($k == 0) ? 'active' : '' ?>"></button>
                        <?php endforeach ?>

                    </div>
                <?php endif ?>

            </div>
        </section>
    <?php endif ?>

    <section class="search">

        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="row mt-5 mb-3">
                    <div class="col">

                        <a data-bs-toggle="collapse" href="#filter-by">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-filter" viewBox="0 0 16 16">
                                <path
                                    d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                            </svg>
                            <span>Search &amp; Filter</span>
                        </a>
                    </div>
                </div>


                <div class="collapse <?= (isset($_GET['_sft_resource_category']) || isset($_GET['_sf_s'])) ? 'show' : '' ?>"
                    id="filter-by">
                    <div class="row">
                        <div class="col">
                            <?= do_shortcode('[searchandfilter id="' . $search_filter_id . '"]') ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>



    </section>

    <?php if (isset($cards_ids) && $cards_ids): ?>
        <section class="cards-overview mt-4 mb-5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <?php if (!$cards_ids): ?>
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-warning p-0" role="alert">
                                    No resources found.
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-warning p-0" role="alert">
                                    <?= count($cards_ids) ?> resources found.
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">

                        <?php foreach ($cards_ids as $card_id):

                            // ---------------------------------------------
                            // handle UTM parameters in the URL
                            // ---------------------------------------------
                    
                            if ($card_id == 33217) {
                                $utm_params = [
                                    'utm_source' => 'linkedin',
                                    'utm_medium' => 'paid',
                                ];

                                $card_url = get_the_permalink($card_id) . '?' . http_build_query($utm_params);
                            } else {
                                $card_url = get_the_permalink($card_id);
                            }

                            ?>
                            <div class="col">
                                <a href="<?= $card_url ?>">
                                    <div class="card h-100">
                                        <img src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($card_id), '_2x-card-faces-of-tlf-left-top') ?>"
                                            class="img-fluid" alt="" loading="lazy">

                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= $resource_categories[$card_id] ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= ucwords(get_the_title($card_id)) ?>
                                            </p>
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