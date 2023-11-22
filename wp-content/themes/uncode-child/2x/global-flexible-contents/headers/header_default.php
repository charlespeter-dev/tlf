<?php

if (is_admin())
    return;

if ($args)
    extract($args);

$handle = str_replace('.php', '', basename(__FILE__));

wp_enqueue_style(sprintf('_2x-css-global-flexible-content-%s', $handle), sprintf('%s/2x/assets/css/%s.css', get_stylesheet_directory_uri(), $handle), [], time(), 'screen');

?>

<section class="hero-carousels single <?= $handle ?>">
    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">

            <img src="<?= wp_get_attachment_image_url($background_image, '_2x-carousel-hero') ?>" class="full-width"
                alt="" loading="lazy">

            <div class="_2x-hero-content">

                <div class="row">
                    <div class="col-lg-8">

                        <?php if (isset($main_heading) && $main_heading): ?>
                            <div class="mb-3">
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

                        <?php if (isset($cta) && $cta): ?>
                            <div>
                                <a class="btn btn-primary" href="<?= $cta['url'] ?>" target="_blank">
                                    <?= $cta['title'] ?>
                                </a>
                            </div>
                        <?php endif ?>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>