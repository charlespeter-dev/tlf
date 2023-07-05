<?php

/**
 * disable admin bar
 */

show_admin_bar(false);

/**
 * deregister style/script
 * enqueue style/scripts
 */

add_action('wp_enqueue_scripts', function () {

    if (is_front_page()) {
        /**
         * enqueue jailed boostrap css
         */

        wp_enqueue_style('_2x-css-bootstrap', sprintf('%s/2x/assets/css/bootstrap-container.css', get_stylesheet_directory_uri()), ['uncode-icons', 'rs-plugin-settings'], time());

        /**
         * enqueue bootstrap js
         */

        wp_enqueue_script('_2x-js-bootstrap', sprintf('%s/2x/assets/js/bootstrap.min.js', get_stylesheet_directory_uri()), [], false, true);
    }
});

/**
 * save acf as json
 */

add_filter('acf/settings/save_json', function ($path) {
    $path = get_stylesheet_directory() . '/2x/acf-json';
    return $path;
});

/**
 * register new image size for hero carousel
 */

add_action('after_setup_theme',  function () {
    add_theme_support('post-thumbnails');
    add_image_size('_2x-carousel-hero', 1920, 450, true);
    add_image_size('_2x-carousel-testimonials', 1920, 520, true);
});
