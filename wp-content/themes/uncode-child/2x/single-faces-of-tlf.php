<?php

/**
 * Template Name: v2 / About Us / Faces of TLF - Single
 * Template Post Type: post
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

extract($fields);

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels tlf-faces-single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image['id'], '_2x_small-banner') ?>" class="small-height" alt="<?= $background_image['title'] ?>">

                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <div class="mb-0">
                                <h2 class="mb-0">
                                    <?= $main_heading ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <section class="face-overview">
        <div class="row-container">
            <div class="single-h-padding limit-width">
                <figure>
                    <?php $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), '_2x_face-image-single' ); ?>
                    <?php $image_url = $image_data[0]; ?>
                    <img src="<?php echo $image_url = $image_url; ?>" class="face-img" alt="<?= $face_image['title'] ?>">
                </figure>
                <div class="face-content">
                    <?= $face_content ?>
                </div>
                <div class="cta-button mt-5">
                    <a class="btn btn-primary" href="<?= $back_button_link ?>"><?= $back_button_text ?></a>
                </div>
            </div>
        </div>
    </section>

    <section id="jobs-section" class="jobs-section">
        <div class="row-container">
            <div class="single-h-padding limit-width">
                <div class="heading text-center">
                    <h3><?= $job_section_heading ?></h3>
                    <p><?= $job_section_subheading ?></p>
                </div>
                <?php include 'jobs-filter.php'; ?>
            </div>
        </div>
    </section>

    <?php if (isset($options['footer_callout_banner']) && $options['footer_callout_banner']): ?>
        <section class="footer-callout-banner">
            <div class="row-container">
                <div class="single-h-padding limit-width position-relative">
                    <img src="<?= wp_get_attachment_image_url($options['footer_callout_banner']['background_image'], '_2x_footer-callout-banner') ?>"
                        class="full-width" alt="">

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