<?php

if (is_admin())
    return;

if ($args)
    extract($args);

$handle = str_replace('.php', '', basename(__FILE__));

wp_enqueue_style(sprintf('_2x-css-global-flexible-content-%s', $handle), sprintf('%s/2x/assets/css/%s.css', get_stylesheet_directory_uri(), $handle), [], time(), 'screen');

?>

<section class="<?= $handle ?>">
    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">
            <img src="<?= $background_image['sizes']['_2x_footer-callout-banner'] ?>"
                class="full-width" alt="" loading="lazy">

            <div class="footer-callout-banner-content">
                <div class="main-heading mb-4">
                    <?= $main_heading ?>
                </div>
                <div class="cta">
                    <a class="btn btn-primary" href="<?= $cta['url'] ?>" target="_blank">
                        <?= $cta['title'] ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>