<?php

if (is_admin())
    return;

if ($args)
    extract($args);

$handle = str_replace('.php', '', basename(__FILE__));

wp_enqueue_style(sprintf('_2x-css-global-flexible-content-%s', $handle), sprintf('%s/2x/assets/css/%s.css', get_stylesheet_directory_uri(), $handle), [], time(), 'screen');

?>

<section class="<?= $handle ?> py-5">
    <div class="row-container">
        <div class="single-h-padding limit-width position-relative">
            <div class="row">
                <div class="col">
                    <?= $main_content ?>
                </div>
            </div>
        </div>
    </div>
</section>