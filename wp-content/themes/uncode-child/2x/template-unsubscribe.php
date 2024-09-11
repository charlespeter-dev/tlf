<?php

/**
 * Template Name: v2 / Unsubscribe
 * Template Post Type: page
 */

if (is_admin())
    return;

global $post;

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


            <section class="main-contents my-5">

                <div class="row">

                    <div class="col-lg-12">
                        <div class="form">
                            <div class="form-heading mb-4">
                                <h3>
                                    <?= $form_heading ?>
                                </h3>
                            </div>
                            <?= do_shortcode($form_shortcode) ?>
                        </div>
                    </div>
                </div>

            </section>

        </div>

    </div>

</div>

<?php get_footer() ?>