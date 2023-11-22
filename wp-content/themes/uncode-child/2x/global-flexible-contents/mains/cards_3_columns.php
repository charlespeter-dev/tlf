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
        <div class="single-h-padding limit-width">

            <div class="row">
                <div class="col">
                    <h2 class="text-center pb-5">
                        <?= $main_heading ?>
                    </h2>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-lg-3 g-4">

                <?php foreach ($cards as $card): ?>

                    <div class="col">
                        <div class="card h-100">
                            <img src="<?= $card['header_thumbnail']['url'] ?>" class="img-fluid" alt="">
                            <div class="card-body">
                                <?= $card['body_content'] ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach ?>

            </div>
        </div>
    </div>
</section>