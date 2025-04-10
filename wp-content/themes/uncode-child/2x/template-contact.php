<?php

/**
 * Template Name: v2 / Contact
 * Template Post Type: page, v2
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

if ($fields)
    extract($fields);

$offices = get_field('offices_list');

/**
 * specific css
 */

wp_enqueue_style('_2x-css-template-contact', sprintf('%s/2x/assets/css/template-contact.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time());

get_header() ?>

<style>
    .bootstrap-container {
        .hero-carousels {
            background-image: url('<?= wp_get_attachment_image_src(get_post_thumbnail_id(), '_2x_medium-banner')[0] ?>');
        }
    }
</style>

<div class="bootstrap-container">

    <section class="hero-carousels contact">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <?php $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), '_2x_medium-banner'); ?>
                <?php $image_url = $image_data[0]; ?>

                <img src="<?= $image_url; ?>" class="medium-height" alt="" loading="lazy">

                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php if (isset($main_heading)): ?>
                                <div class="mb-3">
                                    <h1 class="mb-0">
                                        <?= $main_heading ?>
                                    </h1>
                                </div>
                            <?php endif ?>

                            <?php if (isset($sub_heading)): ?>
                                <div class="mb-4">
                                    <div class="sub-heading">
                                        <?= $sub_heading ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($cta_button_text)): ?>
                                <div>
                                    <a class="btn btn-primary" href="<?= $cta_button_link ?>" target="_blank">
                                        <?= $cta_button_text ?>
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php if (isset($offices_list) && $offices_list): ?>
        <section class="offices-wrapper py-lg-5 py-4">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="col-12 main-content">
                        <?= $main_content ?>
                    </div>
                    <div class="row m-0 gy-5">
                        <div class="row col-12 col-lg-7 row-cols-1 row-cols-sm-2 m-0 p-0 g-5 offices-list">
                            <?php foreach ($offices as $office):
                                $office_img = $office['office_image'];
                                $office_name = $office['office_name'];
                                $office_loc = $office['office_address'];
                                $office_phone = $office['office_phone'];
                                $office_email = $office['office_email']; ?>
                                <div class="col">
                                    <div class="office-card card h-100">
                                        <figure class="office-img">
                                            <img src="<?= wp_get_attachment_image_url($office_img['id'], '_2x-office-image'); ?>"
                                                class="img-fluid" alt="<?= $office_img['title'] ?>" loading="lazy">
                                        </figure>

                                        <div class="office-details card-body">

                                            <div>
                                                <p class="office-name">
                                                    <?= $office_name ?>
                                                </p>

                                                <p class="office-loc mb-3">
                                                    <?= $office_loc ?>
                                                </p>
                                            </div>

                                            <div>
                                                <p class="office-email mb-3">
                                                    <?= __('Email: ') ?>
                                                    <a href="mailto:<?= $office_email ?>" class="office-email">
                                                        <?= $office_email ?>
                                                    </a>
                                                </p>
                                                <p class="office-phone">
                                                    <?= __('Office: ') ?>
                                                    <a href="tel:<?= $office_phone ?>" class="office-phone">
                                                        <?= $office_phone ?>
                                                    </a>
                                                </p>

                                                <?php if (isset($office['support_numbers'][0]) && $office['support_numbers'][0]): ?>
                                                    <?php foreach ($office['support_numbers'] as $support_number): ?>
                                                        <p class="office-phone mb-0">
                                                            <?= __('For support: ') ?>
                                                            <a href="tel:<?= $support_number['phone_number'] ?>" class="office-phone">
                                                                <?= $support_number['phone_number'] ?>
                                                            </a>
                                                        </p>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="form-wrapper col-12 col-lg-5">
                            <div id="contact-form" class="form-area">
                                <h3 class="form-title">
                                    <?= $form_title ?>
                                </h3>
                                <p class="form-desc">
                                    <?= $form_description ?>
                                </p>
                                <div class="form-embed">
                                    <?= $form_embed ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>

</div>

<?= get_footer() ?>