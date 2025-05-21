<?php

/**
 * Template Name: v2 / Industry / Single / Poultry
 * Template Post Type: industry
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

if ($fields)
    extract($fields);

// ------------------------------
// get parent
// ------------------------------

$parent_id = wp_get_post_parent_id($post->ID);

if ($parent_id) {
    $parent_title = get_the_title($parent_id);
    $parent_link = get_the_permalink($parent_id);
}

// ------------------------------
// child
// ------------------------------

$child_title = get_the_title($post->ID);
$child_link = get_the_permalink($post->ID);

// ----------------------------------------
// related industries
// always show F&B + Metals in the top 3
// ----------------------------------------

$_2x_related_resources = _2x_related_industries($post->ID, [29812, 29835]);

/**
 * specific css/js
 */

wp_enqueue_style('_2x-css-single-industry-poultry', sprintf('%s/2x/assets/css/single-industry-poultry.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_url($header['background_image'], '_2x-carousel-hero') ?>');
            background-size: cover;
            background-position: 0 0;
            background-repeat: no-repeat;
        }
    }
</style>

<div class="bootstrap-container single industry poultry">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($header['background_image'], '_2x-carousel-hero') ?>"
                    class="full-width" alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6">

                            <?php if (isset($parent_title) && $parent_title): ?>
                                <div class="mb-4">
                                    <div class="parent-breadcrumb mb-0">
                                        <a href="<?= $parent_link ?>">
                                            <?= $parent_title ?>
                                        </a>
                                        &gt;
                                        <?= $child_title ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($header['main_heading']) && $header['main_heading']): ?>
                                <div class="mb-4">
                                    <h1 class="mb-0">
                                        <?= $header['main_heading'] ?>
                                    </h1>
                                </div>
                            <?php endif ?>

                            <?php if (isset($header['sub_heading']) && $header['sub_heading']): ?>
                                <div class="mb-4 mb-lg-5">
                                    <div class="sub-heading">
                                        <?= $header['sub_heading'] ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($header['cta']) && $header['cta']): ?>
                                <div>
                                    <a class="btn btn-primary" href="<?= $header['cta']['url'] ?>" target="_blank">
                                        <?= $header['cta']['title'] ?>
                                    </a>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <?php if (isset($description['top_content']) && $description['top_content']): ?>
        <section class="top-content">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="row">
                        <div class="col">
                            <?= $description['top_content'] ?>
                        </div>
                    </div>

                    <div class="row mt-4 mb-5">
                        <div class="col">
                            <div class="icon-groups">
                                <?php foreach ($description['icon_groups'] as $icon_group): ?>

                                    <div class="icon-group">
                                        <div class="icon">
                                            <img src="<?= $icon_group['icon']['url'] ?>" alt="">
                                        </div>
                                        <div class="icon-title">
                                            <?= $icon_group['icon_title'] ?>
                                        </div>
                                    </div>

                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <?= $description['bottom_content'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <section class="journey"
        style="--tlf-bg-color:<?= isset($journey['background_color']) && $journey['background_color'] ? $journey['background_color'] : '#fff' ?>;">

        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="row">
                    <div class="col">

                        <?php if (isset($journey['main_heading']) && $journey['main_heading']): ?>
                            <div class="mb-4">
                                <h2 class="main-heading mb-4">
                                    <?= $journey['main_heading'] ?>
                                </h2>
                            </div>
                        <?php endif ?>

                        <?php if (isset($journey['sub_heading']) && $journey['sub_heading']): ?>
                            <div class="mb-4">
                                <div class="sub-heading">
                                    <?= $journey['sub_heading'] ?>
                                </div>
                            </div>
                        <?php endif ?>

                    </div>
                </div>

                <div class="row journey-supply-push">

                    <?php if (isset($journey_supply_push['main_heading']) && $journey_supply_push['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <h2 class="main-heading mb-4">
                                        <?= $journey_supply_push['main_heading'] ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row d-block d-lg-none">
                        <div class="col">

                            <?php foreach ($journey_supply_push['icon_groups'] as $k => $icon_group):

                                if ($k != 1)
                                    continue;

                                ?>
                                <div class="mt-4 mb-5">
                                    <img class="img-fluid" src="<?= $icon_group['icon']['url'] ?>" alt="">
                                </div>
                            <?php endforeach ?>

                        </div>
                    </div>

                    <div class="col-lg-6 left">

                        <div class="vertical-line"></div>
                        <div class="down-arrow"></div>

                        <div class="cards">
                            <?php foreach ($journey_supply_push['card_groups'] as $card_group): ?>
                                <div class="card" data-aos="fade-up" data-aos-duration="500" data-aos-easing="ease-in-out">
                                    <div class="card-header card-title">
                                        <?= $card_group['card_title'] ?>
                                    </div>
                                    <div class="card-body card-description">
                                        <?= $card_group['card_description'] ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-lg-6 right d-none d-lg-flex">
                        <div class="icon-groups">
                            <?php foreach ($journey_supply_push['icon_groups'] as $icon_group): ?>
                                <div class="icon-group">
                                    <img class="img-fluid" src="<?= $icon_group['icon']['url'] ?>" alt="">
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="row journey-demand-pull mt-5 mt-lg-0">

                    <?php if (isset($journey_demand_pull['main_heading']) && $journey_demand_pull['main_heading']): ?>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <h2 class="main-heading mb-4">
                                        <?= $journey_demand_pull['main_heading'] ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="row d-block d-lg-none">
                        <div class="col">

                            <?php foreach ($journey_demand_pull['icon_groups'] as $k => $icon_group):

                                if ($k > 0)
                                    continue;

                                ?>
                                <div class="my-4">
                                    <img class="img-fluid" src="<?= $icon_group['icon']['url'] ?>" alt="">
                                </div>
                            <?php endforeach ?>

                        </div>
                    </div>

                    <div class="col-lg-6 left">

                        <div class="vertical-line"></div>
                        <div class="up-arrow"></div>

                        <div class="cards">
                            <?php foreach ($journey_demand_pull['card_groups'] as $card_group): ?>
                                <div class="card" data-aos="fade-up" data-aos-duration="500" data-aos-easing="ease-in-out">
                                    <div class="card-header card-title">
                                        <?= $card_group['card_title'] ?>
                                    </div>
                                    <div class="card-body card-description">
                                        <?= $card_group['card_description'] ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-lg-6 right d-none d-lg-flex">
                        <div class="icon-groups">
                            <?php foreach ($journey_demand_pull['icon_groups'] as $icon_group): ?>
                                <div class="icon-group">
                                    <img class="img-fluid" src="<?= $icon_group['icon']['url'] ?>" alt="">
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <section class="callout">
        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="row">
                    <div class="col">

                        <?php foreach ($callout['content_groups'] as $idx => $content_group): ?>

                            <div class="mb-4">
                                <h2 class="main-heading mb-4">
                                    <?= $content_group['main_heading'] ?>
                                </h2>
                            </div>

                            <div class="mb-4">
                                <div class="content">
                                    <?= $content_group['content'] ?>
                                </div>
                            </div>

                        <?php endforeach ?>

                        <div class="row row-cols-1 row-cols-lg-3 g-5">
                            <?php foreach ($callout['card_groups'] as $idx => $card_group): ?>
                                <div class="col">
                                    <div class="card h-100 <?= $idx == 1 ? 'side-border' : '' ?>">
                                        <div class="card-header icon">
                                            <img src="<?= $card_group['icon']['url'] ?>" alt="">
                                        </div>
                                        <div class="card-body title">
                                            <?= $card_group['title'] ?>
                                        </div>
                                        <div class="card-footer description">
                                            <?= $card_group['description'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="cta-container">
        <div class="row-container">
            <div class="single-h-padding limit-width">

                <div class="cta-inner">
                    <a class="btn btn-primary cursor-init" href="https://thelogicfactory.com/industry/food-beverage/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>
                        <span>Back to Food &amp; Beverage</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <?php if (!empty($_2x_related_resources)): ?>
        <section class="related-resources py-5">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <div class="row mb-5">
                        <div class="col-lg-6">
                            <h2 class="blue">
                                <?= __('Other Industries') ?>
                            </h2>
                        </div>
                        <div class="col-lg-6 show-more-top">
                            <div>
                                <a href="https://thelogicfactory.com/industry/" class="red fw-bold">
                                    <?= __('Show More') ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 gy-4">
                        <?php foreach ($_2x_related_resources as $item): ?>
                            <div class="col">

                                <a href="<?= $item['url'] ?>">
                                    <div class="card h-100">
                                        <img class="img-fluid"
                                            src="<?= wp_get_attachment_image_url(get_post_thumbnail_id($item['id']), '_2x-card-faces-of-tlf-left-top') ?>"
                                            alt="" loading="lazy">
                                        <div class="card-body position-relative wpk-box-brand">
                                            <p class="category mb-3">
                                                <?= $item['title'] ?>
                                            </p>
                                            <p class="post-title">
                                                <?= get_field('overview_text', $item['id']) ?>
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
                                <a href="https://thelogicfactory.com/industry/" class="red fw-bold">
                                    <?= __('Show More') ?> <i class="fa fa-arrow-right2 t-icon-size-lg"></i>
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
<script>
    window.addEventListener('load', function () {
        AOS.init();
    });
</script>

<?= get_footer() ?>