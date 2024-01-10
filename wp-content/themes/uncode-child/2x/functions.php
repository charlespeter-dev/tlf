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
     * default child style
     */

    wp_enqueue_style('_2x-css-child', sprintf('%s/style.css', get_stylesheet_directory_uri()), ['uncode-style'], time());


    /**
     * enqueue jailed boostrap css
     */

    wp_enqueue_style('_2x-css-bootstrap', sprintf('%s/2x/assets/css/bootstrap-container.css', get_stylesheet_directory_uri()), ['uncode-style'], time());

    /**
     * enqueue bootstrap js
     */

    wp_enqueue_script('_2x-js-bootstrap', sprintf('%s/2x/assets/js/bootstrap.min.js', get_stylesheet_directory_uri()), [], false, true);
});

/**
 * save acf as json
 */

add_filter('acf/settings/save_json', function ($path) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
});


add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
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
    add_image_size('_2x-carousel-resources-callout', 540, 340, false);
    add_image_size('_2x-card-customers', 439, 295, true);
    add_image_size('_2x-card-faces-of-tlf-left-top', 440, 339, ['left', 'top']);
    add_image_size('_2x_face-image-single', 540, 465, true);
    add_image_size('_2x-medium-banner', 1920, 575, true);
    add_image_size('_2x_small-banner', 1920, 375, true);
    add_image_size('_2x_xs-banner', 1920, 365, true);
    add_image_size('_2x-card-news', 600, 600, true);
    add_image_size('_2x-partner-logo', 674, 130);
    add_image_size('_2x-office-image', 350, 250, true);
    add_image_size('_2x-industry-single-tabbed', 560, 426, true);
});

/**
 * disable image compression
 */

add_filter('jpeg_quality', function () {
    return 100;
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
/**
 * faces of TLF 
 * format title
 */

function _2x_format_title($post_id)
{
    $post = get_post($post_id);
    $title = explode('-', $post->post_title)[1];
    return ucwords($title);
}

/**
 ** related resources
 * - get 3 random resources
 */

function _2x_related_resources($exclude_post_id = 0)
{
    $resources_query = new WP_Query([
        'post_type' => 'resources',
        'posts_per_page' => 3,
        'orderby' => 'rand',
        'fields' => 'ids',
        'post_status' => 'publish',
        'post__not_in' => [$exclude_post_id]
    ]);

    wp_reset_query();

    $return = [];

    if (!empty($resources_query->posts)) {
        foreach ($resources_query->posts as $post_id) {

            /**
             * get categories
             */

            $categories = get_the_terms($post_id, 'resource_category');
            foreach ($categories as $cats) {
                $return[$post_id]['category'][] = $cats->name;
            }

            /**
             * title
             */

            $return[$post_id]['title'] = get_the_title($post_id);

            /**
             * permalink
             */

            $return[$post_id]['url'] = get_the_permalink($post_id);
        }
    }

    return $return;
}

/**
 * preload font 'gotham_boldregular'
 * https://thelogicfactory.com/wp-content/themes/uncode-child/style.css
 */

add_action('wp_head', function () {
    ?>
    <link rel="preload" href="https://thelogicfactory.com/wp-content/themes/uncode-child/gotham-bold-webfont.woff2"
        as="font" type="font/woff2" crossorigin>
    <?php
});

/**
 * Retrieve a page given its slug.
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string       $page_slug  Page slug
 * @param string       $output     Optional. Output type. OBJECT, ARRAY_N, or ARRAY_A.
 *                                 Default OBJECT.
 * @param string|array $post_type  Optional. Post type or array of post types. Default 'page'.
 * @return WP_Post|null WP_Post on success or null on failure
 */
function get_page_by_slug($page_slug, $post_type = 'page', $output = OBJECT)
{
    global $wpdb;

    if (is_array($post_type)) {
        $post_type = esc_sql($post_type);
        $post_type_in_string = "'" . implode("','", $post_type) . "'";
        $sql = $wpdb->prepare("
			SELECT ID
			FROM $wpdb->posts
			WHERE post_name = %s
			AND post_type IN ($post_type_in_string)
		", $page_slug);
    } else {
        $sql = $wpdb->prepare("
			SELECT ID
			FROM $wpdb->posts
			WHERE post_name = %s
			AND post_type = %s
		", $page_slug, $post_type);
    }

    $page = $wpdb->get_var($sql);

    if ($page)
        return get_post($page, $output);

    return null;
}

/**
 * disable archive for uncode 'portfolio'
 */

add_filter('register_post_type_args', function ($args, $post_type) {
    if ('portfolio' === $post_type) {
        $args['has_archive'] = false;
    }
    return $args;
}, 10, 2);