<?php

/**
 * Template Name: v2 / Industry / Single / Fast Track IFP
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

wp_enqueue_style('_2x-css-single-industry-fast-track-ifp', sprintf('%s/2x/assets/css/single-industry-fast-track-ifp.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

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

<div class="bootstrap-container single industry fast-track-ifp">

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

    <?php if (isset($general_info_1['content']) && $general_info_1['content']): ?>
        <section class="general_info_1 content">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="row">
                        <div class="col">
                            <?= $general_info_1['content'] ?>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-lg-3 g-5 __cards">
                        <?php foreach ($general_info_1['cards'] as $card): ?>
                            <div class="col">
                                <div class="__card">
                                    <div class="__icon">
                                        <img src="<?= $card['icon']['url'] ?>" alt="">
                                    </div>
                                    <div class="__title-container">
                                        <div class="__title"><?= $card['title'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_2['lists']) && $general_info_2['lists']): ?>
        <section class="general_info_2" style="--tlf-background-color: <?= $general_info_2['background_color'] ?>;">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="main-heading mb-5">
                                <?= $general_info_2['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="__lists">
                                <?php foreach ($general_info_2['lists'] as $list): ?>
                                    <li>
                                        <div class="__title">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20">
                                                    <g id="Group_48375" data-name="Group 48375"
                                                        transform="translate(-916 -1038)">
                                                        <circle id="Ellipse_497" data-name="Ellipse 497" cx="10" cy="10" r="10"
                                                            transform="translate(916 1038)" fill="#003375" />
                                                        <path id="Path_1889" data-name="Path 1889"
                                                            d="M8675.771,3189l3.321,3.321,6.389-6.792"
                                                            transform="translate(-7754.625 -2141)" fill="none" stroke="#fff"
                                                            stroke-width="1.5" />
                                                    </g>
                                                    <script xmlns="" />
                                                </svg>
                                            </div>
                                            <span><?= $list['title'] ?></span>
                                        </div>
                                        <div class="__description">
                                            <?= $list['description'] ?>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <img class="img-fluid" src="<?= $general_info_2['right_image']['url'] ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_3['cards']) && $general_info_3['cards']): ?>
        <section class="general_info_3">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="main-heading mb-4">
                                <?= $general_info_3['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <p class="subheading mb-4">
                                <?= $general_info_3['subheading'] ?>
                            </p>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-lg-2 g-5 __cards">
                        <?php foreach ($general_info_3['cards'] as $card): ?>
                            <div class="col">
                                <div class="__card">
                                    <div class="__icon">
                                        <img src="<?= $card['icon']['url'] ?>" alt="">
                                    </div>
                                    <div class="__title-container">
                                        <div class="__title"><?= $card['title'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_4['lists']) && $general_info_4['lists']): ?>
        <section class="general_info_4" style="--tlf-background-color: <?= $general_info_4['background_color'] ?>;">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="main-heading mb-5">
                                <?= $general_info_4['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="__lists">
                                <?php foreach ($general_info_4['lists'] as $list): ?>
                                    <li>
                                        <div class="__title">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20">
                                                    <g id="Group_48375" data-name="Group 48375"
                                                        transform="translate(-916 -1038)">
                                                        <circle id="Ellipse_497" data-name="Ellipse 497" cx="10" cy="10" r="10"
                                                            transform="translate(916 1038)" fill="#003375" />
                                                        <path id="Path_1889" data-name="Path 1889"
                                                            d="M8675.771,3189l3.321,3.321,6.389-6.792"
                                                            transform="translate(-7754.625 -2141)" fill="none" stroke="#fff"
                                                            stroke-width="1.5" />
                                                    </g>
                                                    <script xmlns="" />
                                                </svg>
                                            </div>
                                            <span><?= $list['title'] ?></span>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <div class="table-heading">
                                                <div>
                                                    <img class="img-fluid"
                                                        src="<?= $general_info_4['result_table']['icon']['url'] ?>" alt="">
                                                </div>
                                                <div>
                                                    <?= $general_info_4['result_table']['table_heading'] ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php foreach ($general_info_4['result_table']['results'] as $row): ?>
                                        <tr>
                                            <td class="w-50">
                                                <?= $row['left_item'] ?>
                                            </td>
                                            <td class="w-50">
                                                <?= $row['right_item'] ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_5['faqs']) && $general_info_5['faqs']): ?>
        <section class="general_info_5">
            <div class="row-container">
                <div class="single-h-padding limit-width">

                    <div class="row">
                        <div class="col">
                            <h2 class="main-heading mb-5">
                                <?= $general_info_5['main_heading'] ?>
                            </h2>
                        </div>
                    </div>
                    <div class="accordion" id="acc-parent">
                        <?php foreach ($general_info_5['faqs'] as $i => $faq): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#acc-<?= $i ?>">
                                        <?= $faq['question'] ?>
                                    </button>
                                </h2>
                                <div id="acc-<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#acc-parent">
                                    <div class="accordion-body">
                                        <?= $faq['answer'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

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