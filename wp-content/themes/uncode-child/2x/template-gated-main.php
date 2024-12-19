<?php

/**
 * Template Name: v2 / Gated / Main
 * Template Post Type: resources, post, page
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

if ($fields)
    extract($fields);

/**
 * css specifics
 */

wp_enqueue_style('_2x-css-template-gated-main', sprintf('%s/2x/assets/css/template-gated-main.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time(), 'screen');

get_header() ?>

<div class="bootstrap-container gated-main">

    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">

            <section class="hero-carousels">

                <img src="<?= wp_get_attachment_image_url($background_image, '_2x_small-banner') ?>" class="full-width"
                    alt="" loading="lazy">

                <div class="_2x-hero-content">

                    <div class="row">
                        <div class="col-lg-8">

                            <?php if (isset($headline) && $headline): ?>
                                <div class="mb-4">
                                    <div class="headline">
                                        <?= $headline ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php if (isset($main_heading) && $main_heading): ?>
                                <div class="mb-4">
                                    <h1 class="mb-0">
                                        <?= $main_heading ?>
                                    </h1>
                                </div>
                            <?php endif ?>

                            <?php if (isset($sub_heading) && $sub_heading): ?>
                                <div class="mb-4">
                                    <div class="sub-heading">
                                        <?= $sub_heading ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>

                </div>

            </section>

        </div>
    </div>

    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">

            <section class="main-contents my-5">

                <div class="row">
                    <div class="col-lg-7">
                        <div class="content">
                            <?= $main_content ?>
                        </div>

                        <?php if (isset($speaker_groups) && $speaker_groups): ?>
                            <div class="speakers">
                                <div class="speakers-heading mt-3 mb-4">
                                    <h3>
                                        <?= __('Speakers:') ?>
                                    </h3>
                                </div>

                                <?php foreach ($speaker_groups as $speaker_group): ?>

                                    <div class="row speakers-content mb-4">

                                        <div class="col-lg-4">
                                            <div class="speaker-image">
                                                <img src="<?= $speaker_group['speaker']['headshot']['url'] ?>" alt=""
                                                    loading="lazy">
                                            </div>
                                        </div>

                                        <div class="col-lg-8">
                                            <div class="speaker-info my-4 my-lg-0">
                                                <div class="speaker-name">
                                                    <?= $speaker_group['speaker']['name'] ?>
                                                </div>
                                                <div class="speaker-title">
                                                    <?= $speaker_group['speaker']['title'] ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-lg-5">
                        <div class="form">
                            <div class="form-heading mb-4">
                                <h3>
                                    <?= $form_heading ?>
                                </h3>
                            </div>
                            <?= do_shortcode($contact_form_shortcode) ?>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </div>


</div>

<?php if (isset($thank_you_page) && $thank_you_page): ?>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('wpcf7mailsent', function (event) {
                setTimeout(function () {
                    window.location.href = '<?= get_the_permalink($thank_you_page) ?>';
                }, 2000);
            }, false);
        });
    </script>

<?php endif ?>

<?php get_footer() ?>