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

            <?php if (isset($main_heading) && $main_heading): ?>
                <div class="row">
                    <div class="col">
                        <h2 class="mb-4 blue text-center">
                            <?= $main_heading ?>
                        </h2>
                    </div>
                </div>
            <?php endif ?>

            <!-- begin: tabbed desktop-only -->
            <div class="desktop-only mt-5">
                <nav>
                    <div class="nav nav-tabs nav-justified">

                        <?php foreach ($tabs as $key => $tab): ?>
                            <div class="nav-link <?= !$key ? 'active' : '' ?>" data-bs-toggle="tab"
                                data-bs-target="#tab-content-<?= $key ?>">
                                <span>
                                    <?= $tab['label'] ?>
                                </span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </nav>

                <div class="tab-content py-3">
                    <?php foreach ($tabs as $key => $tab): ?>

                        <div class="tab-pane fade <?= !$key ? 'show active' : '' ?>" id="tab-content-<?= $key ?>">
                            <div class="row align-items-center">
                                <div class="col-lg-4">
                                    <img class="img-fluid" src="<?= $tab['left_image'] ?>" alt="" loading="lazy">
                                </div>
                                <div class="col-lg-8">
                                    <?= $tab['content'] ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach ?>
                </div>

            </div>
            <!-- end: tabbed desktop-only -->

            <!-- begin: tabbed mobile-only -->
            <div class="mobile-only mt-5">
                <div class="accordion" id="acc-mobile">

                    <?php foreach ($tabs as $key => $tab): ?>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <div class="accordion-button <?= $key ? 'collapsed' : '' ?>" data-bs-toggle="collapse"
                                    data-bs-target="#acc-content-<?= $key ?>">
                                    <div>
                                        <span>
                                            <?= $tab['label'] ?>
                                        </span>
                                        <span class="plus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                                            </svg>
                                        </span>
                                        <span class="dash">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-collapse collapse <?= !$key ? 'show' : '' ?>"
                                id="acc-content-<?= $key ?>">
                                <div class="accordion-body">
                                    <div class="row flex-column">

                                        <div class="col-lg-6">
                                            <img class="img-fluid" src="<?= $tab['left_image'] ?>" alt="" loading="lazy">
                                        </div>

                                        <div class="col-lg-6 mt-4">
                                            <?= $tab['content'] ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                </div>
            </div>
            <!-- end: tabbed mobile-only -->

        </div>
    </div>
</section>