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
extract($fields);

$offices = get_field('offices_list');

get_header() ?>

<div class="bootstrap-container">

    <section class="hero-carousels contact">
        <div class="row-container">
            <div class="single-h-padding limit-width position-relative">

                <?php $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), '_2x_medium-banner' ); ?>
                <?php $image_url = $image_data[0]; ?>

                <img src="<?php echo $image_url = $image_url; ?>" class="medium-height" alt="">

                <div class="_2x-hero-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php if (isset($main_heading)): ?>
                                <div class="mb-3">
                                    <h2 class="mb-0">
                                        <?= $main_heading ?>
                                    </h2>
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
                                    <a class="btn btn-primary" href="<?= $cta_button_link ?>">
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

    

    <?php if (isset($offices_list)): ?>
        <section class="offices-wrapper">
            <div class="row-container">
                <div class="single-h-padding limit-width">
                    <div class="col-12 main-content">
                        <?= $main_content ?>
                    </div>
                    <div class="row m-0 gy-5">
                        <div class="row col-12 col-lg-7 row-cols-1 row-cols-sm-2 m-0 p-0 g-5 offices-list">
                            <?php foreach ($offices as $office): 
                                $office_img = $office['office_image'];  $office_name = $office['office_name'];  $office_loc = $office['office_address']; $office_phone = $office['office_phone']; $office_email = $office['office_email'];?>
                                <div class="col">
                                    <div class="office-card">
                                        <figure class="office-img">
                                            <img src="<?= wp_get_attachment_image_url($office_img['id'], '_2x-office-image'); ?>" class="img-fluid" alt="<?= $office_img['title'] ?>">
                                        </figure>

                                        <div class="office-details">
                                            <p class="office-name">
                                                <?= $office_name ?>
                                            </p>
                                            <p class="office-loc">
                                                <?= $office_loc ?>
                                            </p>
                                            <p class="office-email">
                                                <a href="mailto:<?= $office_email ?>" class="office-email">
                                                    <?= $office_email ?>
                                                </a>
                                            </p>
                                            <p class="office-phone">
                                                <a href="tel:<?= $office_phone ?>" class="office-phone">
                                                    <?= $office_phone ?>
                                                </a>
                                            </p>
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