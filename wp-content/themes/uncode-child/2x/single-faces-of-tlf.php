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

/**
 * specific css/js
 */

wp_enqueue_script('isotope', get_template_directory_uri() . '/library/js/isotopeLayout.min.js', array('jquery'), true);
wp_enqueue_script('isotope-init', get_stylesheet_directory_uri() . '/2x/assets/js/filters.js', array('jquery', 'isotope'), true);

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels tlf-faces-single">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <img src="<?= wp_get_attachment_image_url($background_image['id'], '_2x_small-banner') ?>"
                    class="small-height" alt="<?= $background_image['title'] ?>" loading="lazy">

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

    <section class="face-overview my-lg-5 my-4">
        <div class="row-container">
            <div class="single-h-padding limit-width">
                <figure>
                    <?php $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), '_2x_face-image-single'); ?>
                    <?php $image_url = $image_data[0]; ?>
                    <img src="<?= $image_url; ?>" class="face-img" alt="<?= $face_image['title'] ?>" loading="lazy">
                </figure>
                <div class="face-content">
                    <?= $face_content ?>
                </div>
                <div class="cta-button mt-5">
                    <a class="btn btn-primary" href="<?= $back_button_link ?>">
                        <?= $back_button_text ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="jobs-section" class="jobs-section py-lg-5 py-4">
        <div class="row-container">
            <div class="single-h-padding limit-width">
                <div class="heading text-center">
                    <h3 class="job-heading">
                        <?= $job_section_heading ?>
                    </h3>
                    <p class="job-subheading">
                        <?= $job_section_subheading ?>
                    </p>
                </div>
                <?php include 'jobs-filter.php'; ?>
            </div>
        </div>
    </section>
</div>

<?= get_footer() ?>