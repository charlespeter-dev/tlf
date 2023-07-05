<?php
add_action('after_setup_theme', 'uncode_language_setup');
function uncode_language_setup()
{
	load_child_theme_textdomain('uncode', get_stylesheet_directory() . '/languages');
}

function theme_enqueue_styles()
{
	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on') ? null : rand();
	$parent_style = 'uncode-style';
	$child_style = array('uncode-custom-style');
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/library/css/style.css', array(), $resources_version);
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', $child_style, $resources_version);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Vacatures', 'Post Type General Name', 'uncode' ),
        'singular_name'       => _x( 'Vacature', 'Post Type Singular Name', 'uncode' ),
        'menu_name'           => __( 'Vacatures', 'uncode' ),
      //'parent_item_colon'   => __( 'Parent Movie', 'uncode' ),
        'all_items'           => __( 'Alle vacatures', 'uncode' ),
        'view_item'           => __( 'Bekijk vacature', 'uncode' ),
        'add_new_item'        => __( 'Voeg nieuwe vacature toe', 'uncode' ),
        'add_new'             => __( 'Voeg toe', 'uncode' ),
        'edit_item'           => __( 'Bewerk Vacature', 'uncode' ),
        'update_item'         => __( 'Update Vacature', 'uncode' ),
        'search_items'        => __( 'zoek Vacature', 'uncode' ),
        'not_found'           => __( 'Not Found', 'uncode' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'uncode' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'vacature', 'uncode' ),
        'description'         => __( 'samenvatting vacatures', 'uncode' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array('expertises', 'category' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'vacatures', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );

add_filter('ai1wm_exclude_content_from_export', function ($exclude_filters) {
    $exclude_filters[] = 'updraft';
    $exclude_filters[] = 'plugins/updraftplus';
    return $exclude_filters;
});

require '2x/functions.php';
