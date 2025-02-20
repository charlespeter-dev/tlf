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
     * aos css
     */

    wp_enqueue_style('_2x-css-aos', 'https://unpkg.com/aos@next/dist/aos.css', ['_2x-css-single-industry-poultry'], time(), 'screen');

    /**
     * enqueue bootstrap js
     */

    wp_enqueue_script('_2x-js-bootstrap', sprintf('%s/2x/assets/js/bootstrap.min.js', get_stylesheet_directory_uri()), [], false, true);

    /**
     * aos
     */

    wp_enqueue_script('_2x-js-aos', 'https://unpkg.com/aos@next/dist/aos.js', ['_2x-js-bootstrap'], time(), false);
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
        'orderby' => 'date',
        'order' => 'DESC',
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
 ** related industries
 * - get 3 random industries except $post->ID
 */

function _2x_related_industries($exclude_post_id = 0, $post__in = [])
{
    $post__not_in = [$exclude_post_id];

    if ($post__in) {
        $post__not_in = array_merge($post__not_in, $post__in);
    }

    $industries_query = new WP_Query([
        'post_type' => 'industry',
        'posts_per_page' => -1,
        'orderby' => 'rand',
        'fields' => 'ids',
        'post_status' => 'publish',
        'post__not_in' => $post__not_in
    ]);

    wp_reset_query();

    $return = [];

    if ($industries_query->posts) {

        $industries_query->posts = array_merge($post__in, $industries_query->posts);

        foreach ($industries_query->posts as $post_id) {

            if ($post_id == $exclude_post_id)
                continue;

            if (count($return) < 3) {
                $return[$post_id]['title'] = get_the_title($post_id);
                $return[$post_id]['url'] = get_the_permalink($post_id);
                $return[$post_id]['id'] = $post_id;
            }
        }
    }

    if ($return) {
        array_multisort($return);
    }

    return $return;
}

/**
 * preload font 'gotham_boldregular'
 * https://thelogicfactory.com/wp-content/themes/uncode-child/style.css
 */

add_action('wp_head', function () {
    ?>
    <link rel="preload" href="<?= sprintf("%s/gotham-bold-webfont.woff2", get_stylesheet_directory_uri()) ?>" as="font"
        type="font/woff2" crossorigin>
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

/**
 * zoomInfo
 */

add_action('wp_head', function () {
    ?>

    <script>
        window[(function (_NPg, _Vy) {
            var _3l = ''; for (var _8N = 0; _8N < _NPg.length; _8N++) {
                var
                    _ar = _NPg[_8N].charCodeAt(); _3l == _3l; _ar -= _Vy; _ar += 61; _ar != _8N; _Vy > 9; _ar %= 94; _ar += 33; _3l +=
                        String.fromCharCode(_ar)
            } return _3l
        })(atob('XUxTdXJtaGZ3Tmh8'), 3)] = '6e18db36591683122784'; var zi =
            document.createElement('script'); (zi.type = 'text/javascript'), (zi.async = true), (zi.src = (function (_sBd, _ui) {
                var _KP = ''; for (var _zS = 0; _zS < _sBd.length; _zS++) {
                    var _0n = _sBd[_zS].charCodeAt(); _0n -= _ui; _ui > 8; _0n
                        != _zS; _0n += 61; _0n %= 94; _0n += 33; _KP == _KP; _KP += String.fromCharCode(_0n)
                } return _KP
            })(atob('KTU1MTRZTk4rNE07Kkw0JDMqMTU0TSQwLk47Kkw1IihNKzQ='), 31)), document.readyState === 'complete' ?
                    document.body.appendChild(zi) : window.addEventListener('load', function () { document.body.appendChild(zi) });
    </script>

<?php });

/**
 * resources slug modifier
 * @link https://wordpress.stackexchange.com/questions/94817/add-category-base-to-url-in-custom-post-type-taxonomy
 * 
 * /resources/<taxonomy>/<postname>
 */

// add_filter('post_type_link', function ($post_link, $id = 0) {
//     $post = get_post($id);
//     if (is_object($post)) {
//         $terms = wp_get_object_terms($post->ID, 'resource_category');
//         if ($terms) {
//             return str_replace('%resource_category%', $terms[0]->slug, $post_link);
//         }
//     }
//     return $post_link;
// }, 1, 3);

// add_action('init', function () {
//     add_rewrite_rule(
//         '^resources/(.*)/(.*)/?$',
//         'index.php?post_type=resources&name=$matches[2]',
//         'top'
//     );
// });

// ----------------------------------------
// Gated Resource:
// save all submitted entries
// ----------------------------------------

add_action('wpcf7_submit', function ($form, $result) {

    if ($result['status'] != 'mail_sent')
        return;

    $submission = WPCF7_Submission::get_instance();

    $data = $submission->get_posted_data();

    if (!$data)
        return;

    $data['url'] = $submission->get_meta('url');
    $data['timestamp'] = $submission->get_meta('timestamp');

    foreach ($data as $form_tag => $value) {
        if (!is_scalar($value)) {
            unset($data[$form_tag]);
        }
    }

    $post_content = '';

    foreach ($data as $form_tag => $value) {
        $post_content .= sprintf("%s: %s\n", $form_tag, $value);
    }

    $args = [
        'post_type' => 'gated-submission',
        'post_title' => sprintf("%s %s", $data['first_name'], $data['last_name']),
        'post_content' => $post_content,
        'post_status' => 'publish',
        'meta_input' => $data,
    ];

    $post_id = wp_insert_post($args);

    if (!is_wp_error($post_id) && $post_id !== 0 && is_int($post_id)) {
        update_post_meta($post_id, 'gated_submission_form', serialize($form));
        update_post_meta($post_id, 'gated_submission_result', serialize($result));
    }

}, PHP_INT_MAX, 2);

// --------------------------------
// Gated Submission:
// modify table list
// --------------------------------

add_filter('manage_gated-submission_posts_columns', function ($columns) {
    $columns = [
        'cb' => $columns['cb'],
        'date' => $columns['date'],
        'first_name' => __('First Name'),
        'last_name' => __('Last Name'),
        'email' => __('Email'),
        'company_name' => __('Company Name'),
        'url' => __('Form Source')
    ];

    return $columns;
});

add_action('manage_gated-submission_posts_custom_column', function ($column, $post_id) {

    if ('first_name' === $column) {
        echo get_post_meta($post_id, 'first_name', true);
    }

    if ('last_name' === $column) {
        echo get_post_meta($post_id, 'last_name', true);
    }

    if ('email' === $column) {
        echo get_post_meta($post_id, 'email', true);
    }

    if ('company_name' === $column) {
        echo get_post_meta($post_id, 'company_name', true);
    }

    if ('url' === $column) {
        $url = get_post_meta($post_id, 'url', true);
        echo sprintf('<a href="%s" target="_blank">%s</a>', $url, $url);
    }

}, PHP_INT_MAX, 2);

add_filter('manage_edit-gated-submission_sortable_columns', function ($columns) {
    $columns['first_name'] = 'first_name';
    $columns['last_name'] = 'last_name';
    $columns['email'] = 'email';
    $columns['company_name'] = 'company_name';
    $columns['url'] = 'url';
    return $columns;
});

add_action(
    'pre_get_posts',
    function ($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        if ('last_name' === $query->get('orderby')) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'last_name');
        }

        if ('first_name' === $query->get('orderby')) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'first_name');
        }

        if ('email' === $query->get('orderby')) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'email');
        }

        if ('company_name' === $query->get('orderby')) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'company_name');
        }

        if ('url' === $query->get('orderby')) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'url');
        }
    }
);
