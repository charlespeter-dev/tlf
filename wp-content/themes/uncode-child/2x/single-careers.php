<?php

/**
 * Template Name: v2 / Careers / Careers details - Single
 * Template Post Type: vacatures
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

            <?php $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), '_2x_xs-banner' ); ?>
                <?php $image_url = $image_data[0]; ?>

                <img src="<?php echo $image_url = $image_url; ?>" class="xs-height" alt="">
                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-0">
                                <h2 class="mb-0">
                                    <?= get_the_title($post->ID) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="job-details">
        <div class="row-container">
            <div class="single-h-padding limit-width">
                <div class="mission-profile">
                    <?= $job_mission_profile ?>
                </div>
                <div class="row mt-5 mx-0">
                        <div class="col-12 col-lg-6 m-0 p-0 job-benefits">
                            <?= $job_benefits ?>
                        </div>
                        <div class="form-wrapper col-12 col-lg-6">
                            <div id="contact-form" class="form-area">
                                <h3 class="form-title">
                                    <?= $form_title ?>
                                </h3>
                                <p class="form-desc">
                                    <?= $form_description ?>
                                </p>
                                <div class="form-embed">
                                    <?= $application_form_embed ?>
                                </div>
                            </div>
                        </div>
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
</div>

<?= get_footer() ?>

// job section id
