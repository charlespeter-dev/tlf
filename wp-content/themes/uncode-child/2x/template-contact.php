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
                                <div class="mb-5">
                                    <div class="sub-heading">
                                        <?= $sub_heading ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if ($ctas[0] ?? false): ?>
                                <div class="cta-container">
                                    <?php foreach ($ctas as $i => $cta): ?>

                                        <a class="btn btn-white" href="<?= $cta['cta']['url'] ?>"
                                            target="<?= $cta['cta']['target'] ?>">
                                            <?php if ($i == 0): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-globe-europe-africa-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0m0 1a6.97 6.97 0 0 0-4.335 1.505l-.285.641a.847.847 0 0 0 1.48.816l.244-.368a.81.81 0 0 1 1.035-.275.81.81 0 0 0 .722 0l.262-.13a1 1 0 0 1 .775-.05l.984.34q.118.04.243.054c.784.093.855.377.694.801a.84.84 0 0 1-1.035.487l-.01-.003C8.273 4.663 7.747 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 3 1.935 1.89 3 3 1.146 1.194-1 4 2 4 1.75 0 3-3.5 3-4.5 0-.704 1.5-1 1-2.5-.097-.291-.396-.568-.642-.756-.173-.133-.206-.396-.051-.55a.334.334 0 0 1 .42-.043l1.085.724a.276.276 0 0 0 .348-.035c.15-.15.414-.083.488.117.16.428.445 1.046.847 1.354A7 7 0 0 0 8 1" />
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-globe-americas-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="m8 0 .412.01A7.97 7.97 0 0 1 13.29 2a8.04 8.04 0 0 1 2.548 4.382 8 8 0 1 1-15.674 0 8 8 0 0 1 1.361-3.078A8 8 0 0 1 2.711 2 7.96 7.96 0 0 1 8 0m0 1a7 7 0 0 0-5.958 3.324C2.497 6.192 6.669 7.827 6.5 8c-.5.5-1.034.884-1 1.5.07 1.248 2.259.774 2.5 2 .202 1.032-1.051 3 0 3 1.5-.5 3.798-3.186 4-5 .138-1.242-2-2-3.5-2.5-.828-.276-1.055.648-1.5.5S4.5 5.5 5.5 5s1 0 1.5.5c1 .5.5-1 1-1.5.838-.838 3.16-1.394 3.605-2.001A6.97 6.97 0 0 0 8 1" />
                                                </svg>
                                            <?php endif ?>
                                            <?= $cta['cta']['title'] ?>
                                        </a>

                                    <?php endforeach; ?>
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