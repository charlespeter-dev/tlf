<?php

/**
 * Template Name: v2 / Industry / Single / Multimodal Logistics
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

wp_enqueue_style('_2x-single-industry-multimodal-logistics', sprintf('%s/2x/assets/css/single-industry-multimodal-logistics.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<div class="bootstrap-container single single-industry-multimodal-logistics">

    <section class="hero-carousels">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($header['background_image'], '_2x-carousel-hero') ?>"
                    class="full-width" alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-6 col-xl-7">

                            <?php if (isset($parent_title) && $parent_title): ?>
                                <div class="mb-4">
                                    <div class="parent-breadcrumb mb-0">
                                        <a href="<?= $parent_link ?>">
                                            <?= $parent_title ?>
                                        </a>
                                        &gt;
                                        <?= trim(str_replace('Private:', '', $child_title)) ?>
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
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_2['cards']) && $general_info_2['cards']): ?>
        <section class="general_info_2" style="--tlf-background-color: <?= $general_info_2['background_color'] ?>;">

            <?php if (isset($general_info_2['main_heading']) && $general_info_2['main_heading']): ?>
                <h2 class="main-heading mb-0">
                    <?= $general_info_2['main_heading'] ?>
                </h2>
            <?php endif ?>

            <div class="row cards">
                <?php foreach (range(0, 8) as $i): ?>
                    <?php if (in_array($i, [0, 2, 3, 5, 7])):

                        if ($i == 0) {
                            $title = $general_info_2['cards'][0]['title'];
                            $pro = $general_info_2['cards'][0]['pro'];
                            $con = $general_info_2['cards'][0]['con'];
                        }

                        if ($i == 2) {
                            $title = $general_info_2['cards'][1]['title'];
                            $pro = $general_info_2['cards'][1]['pro'];
                            $con = $general_info_2['cards'][1]['con'];
                        }

                        if ($i == 3) {
                            $title = $general_info_2['cards'][2]['title'];
                            $pro = $general_info_2['cards'][2]['pro'];
                            $con = $general_info_2['cards'][2]['con'];
                        }

                        if ($i == 5) {
                            $title = $general_info_2['cards'][3]['title'];
                            $pro = $general_info_2['cards'][3]['pro'];
                            $con = $general_info_2['cards'][3]['con'];
                        }

                        if ($i == 7) {
                            $title = $general_info_2['cards'][4]['title'];
                            $pro = $general_info_2['cards'][4]['pro'];
                            $con = $general_info_2['cards'][4]['con'];
                        }

                        ?>
                        <div class="col-md-4 __col-<?= $i ?>">
                            <div class="card">
                                <div class="card-header">
                                    <h3><?= $title ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="__con">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                            </svg>
                                        </div>
                                        <span>
                                            <?= $con ?>
                                        </span>
                                    </div>
                                    <div class="__pro">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                        </div>
                                        <span><?= $pro ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($i == 4): ?>
                        <div class="col-md-4 __col-<?= $i ?>">
                            <div class="__pie-chart">
                                <?= file_get_contents($general_info_2['pie_chart']['url'], false, stream_context_create([
                                    'http' => [
                                        'timeout' => 10,
                                        'user_agent' => 'Mozilla/5.0 (compatible; PHP)'
                                    ],
                                    'ssl' => [
                                        'verify_peer' => false,
                                        'verify_peer_name' => false,
                                        'allow_self_signed' => true
                                    ]
                                ])) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-4 __col-<?= $i ?>">
                            <div class="card __card-none">&nbsp;</div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>

        </section>
    <?php endif ?>

    <?php if (isset($general_info_3) && $general_info_3): ?>
        <section class="general_info_3">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">


                    <?php if (isset($general_info_3['main_heading']) && $general_info_3['main_heading']): ?>
                        <h2 class="main-heading mb-0">
                            <?= $general_info_3['main_heading'] ?>
                        </h2>
                    <?php endif ?>

                    <?php if (isset($general_info_3['card_desktop']['url']) && $general_info_3['card_desktop']['url']): ?>
                        <div class="card-desktop">
                            <?= file_get_contents($general_info_3['card_desktop']['url'], false, stream_context_create([
                                'http' => [
                                    'timeout' => 10,
                                    'user_agent' => 'Mozilla/5.0 (compatible; PHP)'
                                ],
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ])) ?>
                        </div>
                    <?php endif ?>

                    <?php if (isset($general_info_3['card_mobile']['url']) && $general_info_3['card_mobile']['url']): ?>
                        <div class="card-mobile">
                            <?= file_get_contents($general_info_3['card_mobile']['url'], false, stream_context_create([
                                'http' => [
                                    'timeout' => 10,
                                    'user_agent' => 'Mozilla/5.0 (compatible; PHP)'
                                ],
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ])) ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_4) && $general_info_4): ?>
        <section class="general_info_4" style="--tlf-background-color: <?= $general_info_4['background_color'] ?>;">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">

                    <div class="row">
                        <div class="col-lg-6">

                            <h2 class="main-heading mb-3">
                                <?= $general_info_4['main_heading'] ?>
                            </h2>

                            <ul class="__lists">
                                <?php foreach ($general_info_4['lists'] as $list): ?>
                                    <li>
                                        <div class="__title">
                                            <?= $list['title'] ?>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <div class="col-lg-6 __right-image-container">
                            <div class="__right-image">
                                <img class="img-fluid" src="<?= $general_info_4['right_image']['url'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

    <?php if (isset($general_info_5) && $general_info_5): ?>
        <section class="general_info_5">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="row">
                        <div class="col">
                            <h2 class="main-heading mb-2 mb-lg-5">
                                <?= $general_info_5['main_heading'] ?>
                            </h2>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-lg-3 __cards">
                        <?php foreach ($general_info_5['cards'] as $i => $card): ?>
                            <div class="col">
                                <div class="card __card-<?= $i ?>">
                                    <div class="__icon-container">
                                        <img class="__icon" src="<?= $card['icon']['url'] ?>" alt="">
                                    </div>
                                    <div class="__title-container">
                                        <div class="__title"><?= $card['title'] ?></div>
                                    </div>
                                    <div class="__description-container">
                                        <div class="__description"><?= $card['description'] ?></div>
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
                    <a class="btn btn-primary cursor-init" href="https://thelogicfactory.com/industry/logistics/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>
                        <span>Back to Logistics</span>
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

<?php get_footer() ?>