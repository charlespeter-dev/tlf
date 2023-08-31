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

    /**
     * enqueue jailed boostrap css
     */

    wp_enqueue_style('_2x-css-bootstrap', sprintf('%s/2x/assets/css/bootstrap-container.css', get_stylesheet_directory_uri()), ['uncode-icons', 'rs-plugin-settings'], time());

    /**
     * enqueue bootstrap js
     */

    wp_enqueue_script('_2x-js-bootstrap', sprintf('%s/2x/assets/js/bootstrap.min.js', get_stylesheet_directory_uri()), [], false, true);
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

add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
    add_image_size('_2x-carousel-hero', 1920, 550, true);
    add_image_size('_2x-carousel-testimonials', 1920, 520, true);
    add_image_size('_2x_footer-callout-banner', 1920, 350, true);
    add_image_size('_2x-carousel-news', 350, 350, true);
    add_image_size('_2x-carousel-resources-callout', 540, 340, true);
    add_image_size('_2x-card-customers', 439, 295, true);
    add_image_size('_2x-card-faces-of-tlf-left-top', 440, 339, ['left', 'top']);
});

/**
 * register new theme options using ACF
 */

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(
        array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        )
    );

    acf_add_options_sub_page(
        array(
            'page_title' => 'Theme Header Settings',
            'menu_title' => 'Header',
            'parent_slug' => 'theme-general-settings',
        )
    );

    acf_add_options_sub_page(
        array(
            'page_title' => 'Theme Footer Settings',
            'menu_title' => 'Footer',
            'parent_slug' => 'theme-general-settings',
        )
    );
}