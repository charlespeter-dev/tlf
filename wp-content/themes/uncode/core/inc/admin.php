<?php

/**
 * Load functions for the Uncode panel menu.
 */
if ( is_admin() ) {
	require_once 'admin-pages/uncode-panel-functions.php';

	if ( ! defined('ENVATO_HOSTED_SITE') ) {
		require_once 'admin-pages/support.php';
	}
}

function uncode_welcome_page(){
	require_once 'admin-pages/welcome.php';
}

function uncode_admin_menu(){
	if ( current_user_can( 'edit_theme_options' ) ) {
		$uncode_system_status_menu_after_text = apply_filters( 'uncode_system_status_menu_after_text', '' );
		add_menu_page( 'UNCODE', UNCODE_NAME . $uncode_system_status_menu_after_text, 'edit_theme_options', 'uncode-system-status', 'uncode_welcome_page', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDY3LjQzIDY3LjQzOyIgdmlld0JveD0iMCAwIDY3LjQzIDY3LjQzIj48cGF0aCBkPSJNNjMuNDMgMEg0QzEuOCAwIDAgMS44IDAgNHY1OS40M2MwIDIuMiAxLjggNCA0IDRoNTkuNDNjMi4yIDAgNC0xLjggNC00VjRjMC0yLjItMS44LTQtNC00ek00OC41MSAzNi45NmMwIDMuMTItLjczIDUuODEtMi4xOSA4LjA4LTEuNDYgMi4yNi0zLjMgMy45Mi01LjU0IDQuOTYtMi4yMyAxLjA0LTQuNjYgMS41Ni03LjI3IDEuNTYtNC4wOSAwLTcuNTUtMS4yNy0xMC4zNy0zLjgyLTIuODItMi41NS00LjIzLTYuMTQtNC4yMy0xMC43N3YtMjEuMWg4LjYxdjIxLjA5YzAgMi4xMS41MiAzLjc0IDEuNTYgNC44OCAxLjA0IDEuMTQgMi41NyAxLjcxIDQuNTggMS43MSAyLjAxIDAgMy41Ni0uNTcgNC42My0xLjcxIDEuMDctMS4xNCAxLjYxLTIuNzcgMS42MS00Ljg4VjE1Ljg3aDguNjF2MjEuMDl6IiBmaWxsPSIjYTBhNWFhIiBjbGFzcz0idW5jb2RlLWljb24tcGF0aCIgLz48L3N2Zz4K', 4 );
		add_submenu_page( 'uncode-system-status', 'UNCODE', esc_html__('System Status','uncode'), 'edit_theme_options', 'uncode-system-status', 'uncode_welcome_page' );
	}
}
add_action( 'admin_menu', 'uncode_admin_menu' );

function uncode_admin_inline_styles() {
	echo '<style>#adminmenu .toplevel_page_uncode-system-status div.wp-menu-image.svg {background-size: 16px auto;} .wpb-notice {display: none !important;}</style>';
}
add_action( 'admin_head', 'uncode_admin_inline_styles' );

/**
 * Remove top margin for admin bar
 */
function uncode_remove_adminbar_margin()
{
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'uncode_remove_adminbar_margin');

if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	header( 'Location: '.admin_url().'admin.php?page=uncode-system-status&first=true');
}

function uncode_ot_admin_script()
{
	wp_enqueue_script( 'ot-admin-fontpicker', get_template_directory_uri() . '/core/assets/js/min/jquery.fonticonpicker.min.js', array('ot-admin-js'), UNCODE_VERSION , false);
}
add_action('ot_admin_scripts_after', 'uncode_ot_admin_script');

function uncode_load_admin_script($hook) {
	if ( 'widgets.php' === $hook ) {
        return;
    }

    global $pagenow, $uncode_colors_flat_array;
	$screen = get_current_screen();

	wp_enqueue_script('admin_uncode_js', get_template_directory_uri() . '/core/assets/js/min/admin_uncode.min.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-dialog' ), UNCODE_VERSION , true);
	wp_enqueue_style ('wp-jquery-ui-dialog');

	// Get media categories (used for the Media Upload dropdown filter)
	$terms = get_terms( 'media-category', array(
		'orderby'       => 'name',
		'order'         => 'ASC',
		'hide_empty'    => true,
	) );

	$site_parameters = array(
		'ICONS_PATH'        => UNCODE_ICONS_PATH,
		'theme_directory'   => get_template_directory_uri(),
		'admin_ajax'        => admin_url( 'admin-ajax.php' ),
		'ajax_save_message' => array(
			'success' => esc_html__( 'Theme Options saved!', 'uncode' ),
			'error'   => esc_html__( 'Theme Options not saved.', 'uncode' ),
		),
		'http_errors'       => array(
			'400'     => esc_html__( ' Error: 400 - Request content was invalid.', 'uncode' ),
			'401'     => esc_html__( ' Error: 401 - Unauthorized access.', 'uncode' ),
			'403'     => esc_html__( ' Error: 403 - Forbidden resource can\'t be accessed.', 'uncode' ),
			'404'     => esc_html__( ' Error: 404 - Requested page not found.', 'uncode' ),
			'408'     => esc_html__( ' Error: 408 - Request time out.', 'uncode' ),
			'500'     => esc_html__( ' Error: 500 - Internal server error.', 'uncode' ),
			'503'     => esc_html__( ' Error: 503 - Service unavailable.', 'uncode' ),
			'login'   => esc_html__( ' Your session has expired.', 'uncode' ),
			'unknown' => esc_html__( ' Unknown error.', 'uncode' )
		),
		'media_cats'        => array(
			'all_label' => esc_html__( 'All Media Categories', 'uncode' ),
			'terms'     => $terms,
		),
		'is_frontend_editor' 	=> function_exists('vc_is_page_editable') ? vc_is_page_editable() : false,
		'loc_strings'        => array(
			'read_more' => esc_html__( 'Read more', 'uncode' ),
		),
		'enable_debug'                => apply_filters( 'uncode_enable_debug_on_js_scripts', false ),
		'theme_registration'          => array(
			'nonce'                       => wp_create_nonce( 'uncode-theme-registration-form-nonce' ),
			'locale'                      => array(
				'empty_purchase_code' => esc_html__( 'Please enter a valid Envato Purchase Code', 'uncode' ),
				'empty_terms'         => esc_html__( 'You must accept the Terms of Service in order to perform this action', 'uncode' ),
			),
		),
		'delete_ajax_filters_transients' => array(
			'nonce'                   => wp_create_nonce( 'uncode-delete-ajax-filters-transients-nonce' ),
		),
		'theme_options_input_vars' => array(
			'enable_max_input_vars_popup' => apply_filters( 'uncode_enable_max_input_vars_popup', true ),
			'max_input_vars'              => uncode_get_minimum_max_input_vars(),
			'recommended_max_input_vars'  => 3000,
			'max_vars_nonce'              => wp_create_nonce( 'uncode-theme-options-test-input-vars-nonce' ),
			'number_of_inputs_nonce'      => wp_create_nonce( 'uncode-theme-options-number-of-inputs-nonce' ),
			'locale'                      => array(
				'button_confirm'  => esc_html__( 'Save Anyway', 'uncode' ),
				'button_cancel'   => esc_html__( "Don't show this message again", "uncode" ),
				'title'           => esc_html__( 'Confirmation Required', 'uncode' ),
				'content'         =>
					'<div class="uncode-modal-max-vars-content"><p>' . __( '<strong>Important warning!</strong>', 'uncode' ) . '</p>'
					. '<p>' . sprintf( __( 'Before saving Theme Options you need to increase the <em><strong>max_input_vars</strong></em> value of your PHP configuration. Your current allowed value is too low and you risk to loose your settings if you choose to continue, please set it to at least <strong class="vars-placeholder">dddd</strong>: <a href="%s" target="_blank">more info</a>.', 'uncode' ), 'https://support.undsgn.com/hc/en-us/articles/213459869' ) . '</p>'
					. '<p>' . sprintf( __( 'If you decide to continue, we strongly suggest you to perform a backup first, <a href="%s" target="_blank">more info</a>.', 'uncode' ), 'https://undsgn.zendesk.com/hc/en-us/articles/360001216518' ) . '</p></div>',
			),
		),
		'process_variations'=> array(
			'nonce'          => wp_create_nonce( 'uncode-process-variations-nonce' ),
			'process_status' => get_option( 'uncode_variations_status' ),
			'locale'         => array(
				'reprocess_text' => esc_html__( 'Re-process All Variations', 'uncode' ),
			)
		),
		'uncode_colors_flat_array' => $uncode_colors_flat_array,
		'has_valid_purchase_code'  => uncode_check_valid_purchase_code() && uncode_9iol_er() ? true : false,
		'lbox_enhanced' => apply_filters( 'uncode_lightgallery', get_option( 'uncode_core_settings_opt_lightbox_enhance' ) === 'on' ),
		'disable_oembed_preview' => apply_filters( 'uncode_disable_oembed_preview', false)
	);
	wp_localize_script( 'admin_uncode_js', 'SiteParameters', $site_parameters );

	// Script for theme/plugin installation/updates
	if ( 'themes.php' == $pagenow || 'update-core.php' == $pagenow || 'plugins.php' == $pagenow || ( $screen && isset( $screen->id ) && ( 'uncode_page_uncode-plugins' === $screen->id || 'uncode_page_uncode-import-demo' === $screen->id ) ) ) {
		wp_enqueue_script('uncode_update', get_template_directory_uri() . '/core/assets/js/min/uncode-update.min.js', array( 'jquery' ), UNCODE_VERSION , true);

		$uncode_update_parameters = array(
			'update_instructions_text' => esc_html__( 'Please read this article before updating the theme', 'uncode' ),
			'update_instructions_url'  => 'https://support.undsgn.com/hc/en-us/articles/214001205',
			'changelog_text'           => esc_html__( 'Read the Change Log', 'uncode' ),
			'changelog_url'            => 'https://support.undsgn.com/hc/en-us/articles/213454129-Change-Log',
			'premium_plugins'          => uncode_get_premium_plugins(),
			'system_status_url'        => esc_url( admin_url( 'admin.php?page=uncode-system-status' ) ),
			'is_uncode_active'         => uncode_get_purchase_code() ? true : false,
			'modal_texts'              => array(
				'modal_title'                  => esc_html__( 'Uncode Registration Required', 'uncode' ),
				'modal_button'                 => esc_html__( 'Register Uncode', 'uncode' ),
				'block_theme_update'           => esc_html__( 'Please register your copy of Uncode Theme to update the theme.', 'uncode' ),
				'block_import_title'           => esc_html__( 'Import Demo', 'uncode' ),
				'block_import'                 => esc_html__( 'Please register your copy of Uncode Theme to import premium contents.', 'uncode' ),
				'block_single_plugin_update'   => esc_html__( 'Please register your copy of Uncode Theme to %s the following plugin:', 'uncode' ),
				'block_multiple_plugin_update' => esc_html__( 'Please register your copy of Uncode Theme to %s the following plugins:', 'uncode' ),
			),
		);

		wp_localize_script( 'uncode_update', 'UncodeUpdateParameters', $uncode_update_parameters );
	}

}
add_action('admin_enqueue_scripts', 'uncode_load_admin_script');

function uncode_init_admin_css()
{
	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on' || ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) ) ? null : rand();
	if ( apply_filters( 'uncode_load_ot_admin_style', true ) ) {
		wp_enqueue_style('ot-admin', get_template_directory_uri() . '/core/assets/css/ot-admin.css', array('wp-jquery-ui-dialog'), $resources_version);
	}
	wp_enqueue_style('admin-uncode-icons', get_template_directory_uri() . '/library/css/uncode-icons.css', array('ot-admin'), $resources_version);

	$back_css = get_template_directory() . '/core/assets/css/';
	$ot_id = is_multisite() ? get_current_blog_id() : '';
	if ( apply_filters( 'uncode_force_dynamic_style_load', false ) || file_exists($back_css . 'admin-custom'.$ot_id.'.css') && wp_is_writable($back_css . 'admin-custom'.$ot_id.'.css') && ! uncode_append_custom_styles_to_head() ) {
		wp_enqueue_style('uncode-custom-style', get_template_directory_uri() . '/core/assets/css/admin-custom'.$ot_id.'.css', array('ot-admin'), $resources_version);
	} else {
		$styles = uncode_create_dynamic_css();
		wp_add_inline_style( 'ot-admin', uncode_compress_css_inline($styles['admin']));
	}
}

add_action('admin_init', 'uncode_init_admin_css');

add_action( 'admin_footer', 'uncode_select_wrapper_category', 1000 );
if ( ! function_exists( 'uncode_select_wrapper_category' ) ) :
function uncode_select_wrapper_category(){
	global $pagenow;
	if ( $pagenow === 'term.php' || $pagenow === 'edit-tags.php' ) {
?>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('.colors-dropdown').each(function() {
			var selectValue, selectName;
			if (!$(this).parent().hasClass('select-wrapper')) {
				$(this).wrap('<div class="select-wrapper" />');
			}
			if ($('.term_color', this).length) {
				$(this).closest('.select-wrapper').addClass('select-uncode-colors');
				$(this).closest('.form-field').addClass('format-setting-inner');
				if (window.navigator.userAgent.indexOf("Windows NT 10.0") == -1) {
					$(this).easyDropDown({
						cutOff: 10
					});
				}
			}
		});
	});
})(jQuery);
</script>
<?php
	}
}
endif;

function uncode_register_admin_js() {
	$i18nLocale = array(
		'add_media' => esc_html__( 'Add Media', 'uncode' ),
		'add_medias' => esc_html__( 'Add Media', 'uncode' ),
		'select_media' => esc_html__( 'Media selection', 'uncode' ),
		'select_medias' => esc_html__( 'Media selection', 'uncode' ),
		'all_medias' => esc_html__( 'All media', 'uncode' ),
	);
	wp_localize_script( 'vc-backend-actions-js', 'i18nLocaleUncode', $i18nLocale );
	wp_localize_script( 'uncode-admin-fix-inputs', 'i18nLocaleUncode', $i18nLocale );
}

add_action('vc_base_register_admin_js', 'uncode_register_admin_js');

//////////////////
// MIME helper //
//////////////////

function uncode_modify_post_mime_types( $post_mime_types ) {
    $post_mime_types['oembed/vimeo'] = array( esc_html__( 'Vimeo','uncode' ), esc_html__( 'Manage Vimeos','uncode' ), _n_noop( 'Vimeo <span class="count">(%s)</span>', 'Vimeos <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/youtube'] = array( esc_html__( 'YouTube','uncode' ), esc_html__( 'Manage YouTubes' ,'uncode'), _n_noop( 'YouTube <span class="count">(%s)</span>', 'YouTubes <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/soundcloud'] = array( esc_html__( 'SoundCloud','uncode' ), esc_html__( 'Manage SoundClouds','uncode' ), _n_noop( 'SoundCloud <span class="count">(%s)</span>', 'SoundClouds <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/spotify'] = array( esc_html__( 'Spotify','uncode' ), esc_html__( 'Manage Spotifys','uncode' ), _n_noop( 'Spotify <span class="count">(%s)</span>', 'Spotifys <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/twitter'] = array( esc_html__( 'Twitter','uncode' ), esc_html__( 'Manage Tweets','uncode' ), _n_noop( 'Twitter <span class="count">(%s)</span>', 'Tweets <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/flickr'] = array( esc_html__( 'Flickr','uncode' ), esc_html__( 'Manage Flickrs','uncode' ), _n_noop( 'Flickr <span class="count">(%s)</span>', 'Flickrs <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/svg'] = array( esc_html__( 'SVG','uncode' ), esc_html__( 'Manage SVGs','uncode' ), _n_noop( 'SVG <span class="count">(%s)</span>', 'SVGs <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/html'] = array( esc_html__( 'HTML','uncode' ), esc_html__( 'Manage HTMLs','uncode' ), _n_noop( 'HTML <span class="count">(%s)</span>', 'HTMLs <span class="count">(%s)</span>', 'uncode' ) );
    $post_mime_types['oembed/iframe'] = array( esc_html__( 'iFrame','uncode' ), esc_html__( 'Manage iFrames','uncode' ), _n_noop( 'iFrame <span class="count">(%s)</span>', 'iFrames <span class="count">(%s)</span>', 'uncode' ) );
    return $post_mime_types;
}

add_filter( 'post_mime_types', 'uncode_modify_post_mime_types' );

////////////////////////////////////
// Media library additional fields //
////////////////////////////////////

function uncode_add_additional_fields($form_fields, $post)
{
	// Don't show fields on gallery attachments
	if ($post->post_mime_type == 'oembed/gallery') {
		return $form_fields;
	}

	$team = (bool) get_post_meta($post->ID, "_uncode_team_member", true);
	$social_original = (bool) get_post_meta($post->ID, "_uncode_social_original", true);
	$animated_svg = (bool) get_post_meta($post->ID, "_uncode_animated_svg", true);
	$animated_svg_time = get_post_meta($post->ID, "_uncode_animated_svg_time", true);
	$hex_val = get_post_meta($post->ID, "_uncode_hex_val", true);
	$start_rating_val = get_post_meta($post->ID, "_uncode_start_rating_val", true);
	$team_social = get_post_meta($post->ID, "_uncode_team_member_social", true);
	$poster = get_post_meta($post->ID, "_uncode_poster_image", true);
	$poster_video = get_post_meta($post->ID, "_uncode_poster_video", true);
	$video_loop = (bool) get_post_meta($post->ID, "_uncode_video_loop", true);
	$video_auto = (bool) get_post_meta($post->ID, "_uncode_video_autoplay", true);
	$video_mobile = (bool) get_post_meta($post->ID, "_uncode_video_mobile_bg", true);
	$videos = get_post_meta($post->ID, "_uncode_video_alternative", true);
	$video1 = isset($videos[0]) ? $videos[0] : '';
	$video2 = isset($videos[1]) ? $videos[1] : '';

	$checked_team = ($team) ? 'checked="checked"' : '';
	$checked_social = ($social_original) ? 'checked="checked"' : '';
	$checked_animated = ($animated_svg) ? 'checked="checked"' : '';
	$checked_loop = ($video_loop) ? 'checked="checked"' : '';
	$checked_auto = ($video_auto) ? 'checked="checked"' : '';
	$checked_mobile = ($video_mobile) ? 'checked="checked"' : '';

	if ($post->post_mime_type === 'oembed/svg') {
		$alt = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
		if ( empty($alt) ) {
			$alt = '';
		}
		$form_fields['svg_alt'] = array(
			'value' => $alt,
			'label' => esc_html__('Alt Text', 'uncode') ,
		);
	}

	if (strpos($post->post_mime_type, 'image') === false || $post->post_mime_type === 'image/svg+xml')
	{
		$dimensions = get_post_meta($post->ID, "_wp_attachment_metadata", true);
		if (!empty($dimensions)) {
			$width = isset($dimensions['width']) ? $dimensions['width'] : 1;
			$height = isset($dimensions['height']) ? $dimensions['height'] : 1;
		} else {
			$width = 1;
			$height = 1;
		}

		$form_fields["media_width"] = array(
			"label" => esc_html__("Width", 'uncode') ,
			"value" => $width
		);
		$form_fields["media_height"] = array(
			"label" => esc_html__("Height", 'uncode') ,
			"value" => $height
		);
		$form_fields["poster_image"] = array(
			"label" => esc_html__("Media Poster (Image ID)", 'uncode') ,
			"value" => $poster,
		);
	}

	if (strpos($post->post_mime_type, 'oembed/youtube') !== false || strpos($post->post_mime_type, 'oembed/vimeo') !== false || strpos($post->post_mime_type, 'video/') !== false) {
		$form_fields["poster_video"] = array(
			"label" => esc_html__("Loop Poster (Video ID)", 'uncode') ,
			"value" => $poster_video,
		);
	}

	if (strpos($post->post_mime_type, 'video/') !== false) {
		$form_fields["video_loop"] = array(
			"label" => esc_html__("Loop?", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='checkbox' {$checked_loop}  name='attachments[{$post->ID}][video_loop]' id='attachments[{$post->ID}][video_loop]' /> <span>Yes</span>",
			"value" => $video_loop
		);
		$form_fields["video_auto"] = array(
			"label" => esc_html__("Autoplay?", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='checkbox' {$checked_auto}  name='attachments[{$post->ID}][video_autoplay]' id='attachments[{$post->ID}][video_autoplay]' /> <span>Yes</span>",
			"value" => $video_auto
		);
		$form_fields["video_mobile"] = array(
			"label" => esc_html__("Mobile video background?", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='checkbox' {$checked_mobile}  name='attachments[{$post->ID}][video_mobile_bg]' id='attachments[{$post->ID}][video_mobile_bg]' /> <span>Yes</span>",
			"value" => $video_mobile
		);
		$form_fields["video_alt_1"] = array(
			"label" => esc_html__("Alternative video source 1", 'uncode') ,
			"value" => $video1,
		);
		$form_fields["video_alt_2"] = array(
			"label" => esc_html__("Alternative video source 2", 'uncode') ,
			"value" => $video2,
		);
	}

	if (strpos($post->post_mime_type, 'oembed/svg') !== false || $post->post_mime_type === 'image/svg+xml') {
		$form_fields["animated_svg"] = array(
			"label" => esc_html__("Animated?", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='checkbox' {$checked_animated}  name='attachments[{$post->ID}][animated_svg]' id='attachments[{$post->ID}][animated_svg]' /> <span>Yes</span>",
			"value" => $animated_svg
		);
	}

	if ($animated_svg) {
		$form_fields["animated_svg_time"] = array(
			"label" => esc_html__("Animation time (default 100)", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='text' value='" . $animated_svg_time . "' name='attachments[{$post->ID}][animated_svg_time]' id='attachments[{$post->ID}][animated_svg_time]' /><br />"
		);
	}

	if (strpos($post->post_mime_type, 'image') !== false) {
		$form_fields["media_id"] = array(
			"label" => esc_html__("ID", 'uncode') ,
			"input" => 'html',
			"html" => '<input type="text" value="' . $post->ID . '" readonly=""><br />'
		);
	}

	if (strpos($post->post_mime_type, 'image') !== false 
		|| strpos($post->post_mime_type, 'video') !== false
		|| strpos($post->post_mime_type, 'youtube') !== false
		|| strpos($post->post_mime_type, 'vimeo') !== false
	) {
		$form_fields["hex_val"] = array(
			"label" => esc_html__("HEX Color", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='text' value='". $hex_val . "' name='attachments[{$post->ID}][hex_val]' id='attachments[{$post->ID}][hex_val]' /><br />"
		);
	}

	if ($post->post_mime_type === 'oembed/twitter') {
		$form_fields["social_original"] = array(
			"label" => esc_html__("Twitter original", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='checkbox' {$checked_social} name='attachments[{$post->ID}][social_original]' id='attachments[{$post->ID}][social_original]' /> <span>Yes</span>",
			"value" => $social_original
		);
	}

	$form_fields["team_member"] = array(
		"label" => esc_html__("Team Member", 'uncode') ,
		"input" => 'html',
		"html" => "<input type='checkbox' {$checked_team} name='attachments[{$post->ID}][team_member]' id='attachments[{$post->ID}][team_member]' /> <span>Yes</span>",
		"value" => $team
	);

	if ( $post->post_mime_type === 'oembed/html') {
		$form_fields["star_rating"] = array(
			"label" => esc_html__("Star Rating", 'uncode') ,
			"input" => 'html',
			"html" => "<input type='text' value='" . $start_rating_val . "' name='attachments[{$post->ID}][start_rating_val]' id='attachments[{$post->ID}][start_rating_val]' /><br />"
		);
	}

	if ($team) {
		$form_fields["team_member_social"] = array(
			"label" => esc_html__("Socials", 'uncode') ,
			"input" => 'textarea',
			"value" => $team_social
		);
	}

	$taxonomies = apply_filters( 'media-taxonomies', get_object_taxonomies( 'attachment', 'objects' ) );

	if ( !$taxonomies ) {
		return $form_fields;
	}

	foreach ( $taxonomies as $taxonomyname => $taxonomy ) :
		$form_fields[$taxonomyname] = array(
			'label' => $taxonomy->labels->singular_name,
			'input' => 'html',
			'html' => uncode_terms_checkboxes( $taxonomy, $post->ID ),
			'show_in_edit' => true,
		);
	endforeach;

	return $form_fields;
}

function uncode_save_additional_fields($attachment_id) {

	if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'save-attachment-compat' || ($_REQUEST['action'] == 'editpost') && $_REQUEST['post_type'] == 'attachment')) {

		if (isset($_REQUEST['attachments'][$attachment_id]['svg_alt'])) {
			$alt_text = $_REQUEST['attachments'][$attachment_id]['svg_alt'];
			update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['team_member'])) {
			$team = ($_REQUEST['attachments'][$attachment_id]['team_member'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_team_member', $team);
		} else {
			delete_post_meta($attachment_id, '_uncode_team_member', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['social_original'])) {
			$social_original = ($_REQUEST['attachments'][$attachment_id]['social_original'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_social_original', $social_original);
		} else {
			delete_post_meta($attachment_id, '_uncode_social_original', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['animated_svg'])) {
			$animated = ($_REQUEST['attachments'][$attachment_id]['animated_svg'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_animated_svg', $animated);
		} else {
			delete_post_meta($attachment_id, '_uncode_animated_svg', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['animated_svg_time'])) {
			$animated_svg_time = $_REQUEST['attachments'][$attachment_id]['animated_svg_time'];
			update_post_meta($attachment_id, '_uncode_animated_svg_time', $animated_svg_time);
		} else {
			delete_post_meta($attachment_id, '_uncode_animated_svg_time');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['hex_val'])) {
			$hex_val = $_REQUEST['attachments'][$attachment_id]['hex_val'];
			update_post_meta($attachment_id, '_uncode_hex_val', $hex_val);
		} else {
			delete_post_meta($attachment_id, '_uncode_hex_val');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['start_rating_val'])) {
			$start_rating_val = $_REQUEST['attachments'][$attachment_id]['start_rating_val'];
			update_post_meta($attachment_id, '_uncode_start_rating_val', $start_rating_val);
		} else {
			delete_post_meta($attachment_id, '_uncode_start_rating_val');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['video_loop'])) {
			$video_loop = ($_REQUEST['attachments'][$attachment_id]['video_loop'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_video_loop', $video_loop);
		} else {
			delete_post_meta($attachment_id, '_uncode_video_loop', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['video_autoplay'])) {
			$video_auto = ($_REQUEST['attachments'][$attachment_id]['video_autoplay'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_video_autoplay', $video_auto);
		} else {
			delete_post_meta($attachment_id, '_uncode_video_autoplay', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['video_mobile_bg'])) {
			$video_mobile = ($_REQUEST['attachments'][$attachment_id]['video_mobile_bg'] == 'on') ? '1' : '0';
			update_post_meta($attachment_id, '_uncode_video_mobile_bg', $video_mobile);
		} else {
			delete_post_meta($attachment_id, '_uncode_video_mobile_bg', '1');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['team_member_social']) && isset($_REQUEST['attachments'][$attachment_id]['team_member_social']) !== '') {
			$team_social = $_REQUEST['attachments'][$attachment_id]['team_member_social'];
			update_post_meta($attachment_id, '_uncode_team_member_social', $team_social);
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['media_width']) && isset($_REQUEST['attachments'][$attachment_id]['media_width']) !== '' && isset($_REQUEST['attachments'][$attachment_id]['media_height']) && isset($_REQUEST['attachments'][$attachment_id]['media_height']) !== '') {
			$dimensions = array( 'width' => $_REQUEST['attachments'][$attachment_id]['media_width'], 'height' => $_REQUEST['attachments'][$attachment_id]['media_height'] );
			update_post_meta($attachment_id, '_wp_attachment_metadata', $dimensions);
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['poster_image']) && $_REQUEST['attachments'][$attachment_id]['poster_image'] !== '') {
			$poster = $_REQUEST['attachments'][$attachment_id]['poster_image'];
			update_post_meta($attachment_id, '_uncode_poster_image', $poster);
		} else {
			delete_post_meta($attachment_id, '_uncode_poster_image');
		}

		if (isset($_REQUEST['attachments'][$attachment_id]['poster_video']) && $_REQUEST['attachments'][$attachment_id]['poster_video'] !== '') {
			$poster_video = $_REQUEST['attachments'][$attachment_id]['poster_video'];
			update_post_meta($attachment_id, '_uncode_poster_video', $poster_video);
		} else {
			delete_post_meta($attachment_id, '_uncode_poster_video');
		}

		if (!isset($_REQUEST['attachments'][$attachment_id]['video_alt_1']) && !isset($_REQUEST['attachments'][$attachment_id]['video_alt_2'])) {
			delete_post_meta($attachment_id, '_uncode_video_alternative');
		} else {
			$alt_array = array();
			$alt_array[] = $_REQUEST['attachments'][$attachment_id]['video_alt_1'];
			$alt_array[] = $_REQUEST['attachments'][$attachment_id]['video_alt_2'];
			update_post_meta($attachment_id, '_uncode_video_alternative', $alt_array);
		}

		if ( isset($_REQUEST['changes']) ) {
			$changes = $_REQUEST['changes'];

			if ( isset( $changes['url'] ) && isset($_REQUEST['id']) && $_REQUEST['id'] !== '' ) {
				global $wpdb;
				$id = esc_sql($_REQUEST['id']);
				$code = esc_sql($changes['url']);
				$update = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET guid = %s WHERE ID = %d", $code, $id ) );
			}
		}

	}

}
add_action('edit_attachment', 'uncode_save_additional_fields');

/**
 * Ensure that we have correct width/height values
 * on SVGs when retreiving their data in the Media page.
 */
function uncode_get_media_additional_fields( $data, $attachment_id ) {
	if ( ! is_admin() ) {
        return $data;
    }

    global $pagenow, $uncode_get_svgs_metadata;

    if ( 'upload.php' != $pagenow && 'media-upload.php' != $pagenow ) {
        return $data;
    }

    $uncode_get_svgs_metadata = is_null( $uncode_get_svgs_metadata ) ? array() : $uncode_get_svgs_metadata;

    if ( isset( $uncode_get_svgs_metadata[$attachment_id] ) ) {
    	$data['width']  = $uncode_get_svgs_metadata[$attachment_id]['width'];
		$data['height'] = $uncode_get_svgs_metadata[$attachment_id]['height'];

        return $data;
    }

	$attachment = get_post( $attachment_id );

	if ( $attachment && $attachment->post_mime_type === 'image/svg+xml' ) {
		$width  = isset( $data['width'] ) ? absint( $data['width'] ) : 0;
		$height = isset( $data['height'] ) ? absint( $data['height'] ) : 0;

		$uncode_get_svgs_metadata[$attachment_id] = array(
			'width'  => $width,
			'height' => $height,
		);

		$data['width']  = $width;
		$data['height'] = $height;
	}

	return $data;
}
add_filter( 'wp_get_attachment_metadata', 'uncode_get_media_additional_fields', 10, 2 );

function uncode_terms_checkboxes( $taxonomy, $post_id ) {
	if ( !is_object( $taxonomy ) ) :
		$taxonomy = get_taxonomy( $taxonomy );
	endif;
	$terms = get_terms( $taxonomy->name, array(
		'hide_empty' => FALSE,
	));
	$attachment_terms = wp_get_object_terms( $post_id, $taxonomy->name, array(
		'fields' => 'ids'
	));
	ob_start();
	?>
	<div class="media-term-section">

		<div class="media-terms" data-id="<?php echo esc_attr( $post_id ) ?>" data-taxonomy="<?php echo esc_attr( $taxonomy->name ) ?>">

			<ul>
				<?php
				wp_terms_checklist( 0, array(
					'selected_cats'         => $attachment_terms,
					'taxonomy'              => $taxonomy->name,
					'checked_ontop'         => FALSE
				));
				?>
			</ul>

		</div><!-- .media-terms -->

		<a href="#" class="toggle-add-media-term taxonomy-add-new"><?php echo esc_attr( $taxonomy->labels->add_new_item ); ?></a>

		<div class="add-new-term">

			<input type="text" value="">

			<?php
			if ( 1 == $taxonomy->hierarchical ) :
				wp_dropdown_categories( array(
					'taxonomy' => $taxonomy->name,
					'class' => 'parent-' . $taxonomy->name,
					'id' => 'parent-' . $taxonomy->name,
					'name' => 'parent-' . $taxonomy->name,
					'show_option_none' => '- ' . $taxonomy->labels->parent_item . ' -',
					'hide_empty' => FALSE,
				) );
			endif;
			?>

			<a class="button save-media-category" data-taxonomy="<?php echo esc_attr( $taxonomy->name ); ?>" data-id="<?php echo esc_attr( $post_id ); ?>"><?php echo esc_attr( $taxonomy->labels->add_new_item ); ?></a>

		</div><!-- .add-new-term -->

	</div><!-- .media-term-section -->

	<?php
	$output = ob_get_contents();
	ob_end_clean();
	return apply_filters( 'media-checkboxes', $output, $taxonomy, $terms );
}

add_filter("attachment_fields_to_edit", "uncode_add_additional_fields", 10, 2);

function uncode_save_media_terms() {
	$post_id = intval( $_REQUEST['attachment_id'] );
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		die();
	}
	if ( isset($_REQUEST['term_ids']) && is_array($_REQUEST['term_ids']) ) {
		$term_ids = array_map( 'intval', $_REQUEST['term_ids'] );
	} else {
		$term_ids = array('');
	}
	$response = wp_set_post_terms( $post_id, $term_ids, sanitize_text_field( $_REQUEST['taxonomy'] ) );
	wp_update_term_count_now( $term_ids, sanitize_text_field( $_REQUEST['taxonomy'] ) );
}

function uncode_add_media_term() {
	$response = array();
	$attachment_id = intval( $_REQUEST['attachment_id'] );
	$taxonomy = get_taxonomy( sanitize_text_field( $_REQUEST['taxonomy'] ) );
	$parent = ( intval( $_REQUEST['parent'] ) > 0 ) ? intval( $_REQUEST['parent'] ) : 0;
	// Check if term already exists
	$term = get_term_by( 'name', sanitize_text_field( $_REQUEST['term'] ), $taxonomy->name );
	// No, so lets add it
	if ( !$term ) :
		$term = wp_insert_term( sanitize_text_field( $_REQUEST['term'] ), $taxonomy->name, array( 'parent' => $parent ) );
		$term = get_term_by( 'id', $term['term_id'], $taxonomy->name );
	endif;
	// Connect attachment with term
	wp_set_object_terms( $attachment_id, $term->term_id, $taxonomy->name, TRUE );
	$attachment_terms = wp_get_object_terms( $attachment_id, $taxonomy->name, array(
		'fields' => 'ids'
	));
	ob_start();
	wp_terms_checklist( 0, array(
		'selected_cats'         => $attachment_terms,
		'taxonomy'              => $taxonomy->name,
		'checked_ontop'         => FALSE
	));
	$checklist = ob_get_contents();
	ob_end_clean();
	$response['checkboxes'] = $checklist;
	$response['selectbox'] = wp_dropdown_categories( array(
		'taxonomy' => $taxonomy->name,
		'class' => 'parent-' . $taxonomy->name,
		'id' => 'parent-' . $taxonomy->name,
		'name' => 'parent-' . $taxonomy->name,
		'show_option_none' => '- ' . $taxonomy->labels->parent_item . ' -',
		'hide_empty' => FALSE,
		'echo' => FALSE,
	) );
	die( json_encode( $response ) );
}

add_action( 'wp_ajax_save-media-terms', 'uncode_save_media_terms', 0, 1 );
add_action( 'wp_ajax_add-media-term', 'uncode_add_media_term', 0, 1 );

function uncode_taxonomy_add_meta_field( $taxonomy ) {
	$is_new_tax = in_array( $taxonomy, uncode_get_legacy_taxonomies() ) ? false : true;
	$is_product_attribute = in_array( $taxonomy, uncode_get_all_product_attributes_with_archive() );

	if ( ! $is_new_tax ) {
		/* create localized JS array */
		$localized_array = array(
			'upload_text'       => apply_filters( 'ot_upload_text', esc_html__( 'Send to OptionTree', 'uncode' ) ),
			'remove_media_text' => esc_html__( 'Remove Media', 'uncode' ),
		);
		/* localized script attached to 'option_tree' */
		wp_localize_script( 'admin_uncode_js', 'option_tree', $localized_array );
	}

	wp_enqueue_media();

	global $uncode_colors;
	$uncode_colors[0][1] = esc_html__( 'Select…', 'uncode' );
	?>
	<?php if ( ! $is_new_tax ) : ?>
		<div class="form-field">
			<label for="term_meta[term_media]"><?php esc_html_e( 'Featured Image', 'uncode' ); ?></label>
			<div class="format-setting-inner">
				<div class="option-tree-ui-upload-parent">
					<input type="text" name="term_meta[term_media]" id="term_media" value="" class="widefat option-tree-ui-upload-input " readonly="">
					<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" title="Add Media"><span class="icon fa fa-plus2"></span><?php esc_html_e( 'Add media','uncode' ); ?></a>
				</div>
			</div>
			<p class="description" style="padding-top: 22px;"><?php esc_html_e( 'Select a media assigned to the category.','uncode' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( ! $is_product_attribute ) : ?>
	<div class="form-field">
		<label for="term_meta[term_color]"><?php esc_html_e( 'Color', 'uncode' ); ?></label>
		<select name="term_meta[term_color]" id="term_meta[term_color]" class="term_color">
		<?php
			foreach ($uncode_colors as $key => $value) {
				?><option class="<?php echo esc_attr($value[0]); ?>" value="<?php echo esc_attr($value[0]); ?>"><?php echo esc_html($value[1]); ?></option><?php
			}
		?>
		</select>
		<p class="description" style="padding-top: 22px;"><?php esc_html_e( 'Select a color assigned to the category.','uncode' ); ?></p>
	</div>
	<?php endif; ?>
	<script type="text/javascript">
		jQuery( document ).ready(function( $ ) {
			<?php if ( ! $is_product_attribute ) : ?>
			$('select.term_color').each(function(index) {
				var $select = $(this);
				if ($(this).is('[class*="_color"]') && window.navigator.userAgent.indexOf("Windows NT 10.0") == -1) {
					$(this).easyDropDown({
						cutOff: 10,
					});
				}
			});
			<?php endif; ?>
			<?php if ( ! $is_new_tax ) : ?>
			$.fn.uncode_init_upload();
			<?php endif; ?>
		});
	</script>
<?php
}

// Edit term page
function uncode_taxonomy_edit_meta_field($term, $taxonomy) {
	$is_new_tax           = in_array( $taxonomy, uncode_get_legacy_taxonomies() ) ? false : true;
	$is_product_attribute = in_array( $taxonomy, uncode_get_all_product_attributes_with_archive() );

	if ( ! $is_new_tax ) {
		/* create localized JS array */
		$localized_array = array(
			'upload_text'       => apply_filters( 'ot_upload_text', esc_html__( 'Send to OptionTree', 'uncode' ) ),
			'remove_media_text' => esc_html__( 'Remove Media', 'uncode' ),
		);
		/* localized script attached to 'option_tree' */
		wp_localize_script( 'admin_uncode_js', 'option_tree', $localized_array );
	}

	wp_enqueue_media();

	global $uncode_colors;
	$uncode_colors[0][1] = esc_html__( 'Select…', 'uncode' );
	// put the term ID into a variable
	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "_uncode_taxonomy_$t_id" );
	?>
	<?php if ( ! $is_new_tax ) : ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[term_media]"><?php esc_html_e( 'Featured Image', 'uncode' ); ?></label></th>
		<td>
			<div class="format-setting-inner">
				<div class="option-tree-ui-upload-parent">
					<input type="text" name="term_meta[term_media]" id="term_media" value="<?php echo isset( $term_meta['term_media'] ) ? esc_attr($term_meta['term_media']) : ''; ?>" class="widefat option-tree-ui-upload-input " readonly="">
					<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" title="Add Media"><span class="icon fa fa-plus2"></span><?php esc_html_e( 'Add media','uncode' ); ?></a>
				</div>
			</div>
			<?php
			if ( isset( $term_meta['term_media'] ) && $term_meta['term_media'] && wp_attachment_is_image( $term_meta['term_media'] ) ) {
				$attachment_data = wp_get_attachment_image_src( $term_meta['term_media'], 'original' );
				/* check for attachment data */
				if ( $attachment_data ) {
					$field_src = $attachment_data[0];
				}
				echo '<div class="option-tree-ui-media-wrap" id="term_media_media">';
				/* replace image src */
				if ( isset( $field_src ) ) {
					$term_meta['term_media'] = $field_src;
				}

				$post = get_post($term_meta['term_media']);
				if (isset($post->post_mime_type) && $post->post_mime_type === 'oembed/svg') {
					echo '<div class="option-tree-ui-image-wrap">' . $post->post_content . '</div>';
				} else if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $term_meta['term_media'] ) ) {
					echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( $term_meta['term_media'] ) . '" /></div>';
				} else {
					echo '<div class="option-tree-ui-image-wrap"><div class="option-tree-ui-image-wrap"><div class="oembed" onload="alert(\'load\');"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' . esc_url( $term_meta['term_media'] ) . '</div></div></div>';
				}
				echo '<a href="#" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'uncode' ) . '"><span class="icon fa fa-minus2"></span>' . esc_html__( 'Remove Media', 'uncode' ) . '</a>';
				echo '</div>';
			}
			?>
			<p class="description" style="padding-top: 22px;"><?php esc_html_e( 'Select a media assigned to the category.','uncode' ); ?></p>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( ! $is_product_attribute ) : ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[term_color]"><?php esc_html_e( 'Color', 'uncode' ); ?></label></th>
		<td>
			<select name="term_meta[term_color]" id="term_meta[term_color]" class="term_color">
			<?php
				foreach ($uncode_colors as $key => $value) {
					$selected = (isset($term_meta['term_color']) && $term_meta['term_color'] === $value[0]) ? ' selected="selected"' : '';
					?><option class="<?php echo  esc_attr($value[0]); ?>" value="<?php echo esc_attr($value[0]); ?>"<?php echo wp_kses_post($selected); ?>><?php echo esc_html($value[1]); ?></option><?php
				}
			?>
			</select>
			<p class="description" style="padding-top: 22px;"><?php esc_html_e( 'Select a color assigned to the category.','uncode' ); ?></p>
		</td>
	</tr>
	<?php endif; ?>
	<script type="text/javascript">
		jQuery( document ).ready(function( $ ) {
			<?php if ( ! $is_product_attribute ) : ?>
			$('select.term_color').each(function(index) {
				var $select = $(this);
				if ($(this).is('[class*="_color"]') && window.navigator.userAgent.indexOf("Windows NT 10.0") == -1) {
					$(this).easyDropDown({
						cutOff: 10,
					});
				}
			});
			<?php endif; ?>
			<?php if ( ! $is_new_tax ) : ?>
			$.fn.uncode_init_upload();
			<?php endif; ?>
		});
	</script>
<?php
}

/**
* Add legacy form fields to product attributes.
*/
function uncode_add_taxonomy_fields_to_product_attributes() {
	$attributes_with_archive = uncode_get_all_product_attributes_with_archive();
	foreach ( $attributes_with_archive as $attribute_with_archive ) {
		add_action( $attribute_with_archive . '_add_form_fields', 'uncode_taxonomy_add_meta_field', 10, 2 );
		add_action( $attribute_with_archive . '_edit_form_fields', 'uncode_taxonomy_edit_meta_field', 10, 2 );
		add_action( 'edited_' . $attribute_with_archive, 'uncode_save_taxonomy_custom_meta', 10, 2 );
		add_action( 'create_' . $attribute_with_archive, 'uncode_save_taxonomy_custom_meta', 10, 2 );
	}
}
add_action( 'wp_loaded', 'uncode_add_taxonomy_fields_to_product_attributes' );

// Save extra taxonomy fields callback function.
function uncode_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "_uncode_taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "_uncode_taxonomy_$t_id", $term_meta, false );
	}
}

function uncode_edit_tax_meta() {
	$tax_list = uncode_get_legacy_taxonomies();
	foreach ($tax_list as $tax) {
		add_action( $tax . '_add_form_fields', 'uncode_taxonomy_add_meta_field', 10, 2 );
		add_action( $tax . '_edit_form_fields', 'uncode_taxonomy_edit_meta_field', 10, 2 );
		add_action( 'edited_' . $tax, 'uncode_save_taxonomy_custom_meta', 10, 2 );
		add_action( 'create_' . $tax, 'uncode_save_taxonomy_custom_meta', 10, 2 );
	}
}
add_action('admin_init', 'uncode_edit_tax_meta');


///////////////
// Menu edit //
///////////////

// add custom menu fields to menu
function uncode_add_custom_nav_fields($menu_item)
{
	$menu_item->icon = get_post_meta($menu_item->ID, '_menu_item_icon', true);
	$menu_item->megamenu = get_post_meta($menu_item->ID, '_menu_item_megamenu', true);
	$menu_item->button = get_post_meta($menu_item->ID, '_menu_item_button', true);
	if ( apply_filters( 'uncode_activate_menu_badges', false ) ) {
		$menu_item->badge_text = get_post_meta($menu_item->ID, '_menu_item_badge_text', true);
		$menu_item->badge_color = get_post_meta($menu_item->ID, '_menu_item_badge_color', true);
	}
	return $menu_item;
}

function uncode_update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {
	// Check if element is properly sent
	if (isset($_REQUEST['menu-item-icon']) && is_array($_REQUEST['menu-item-icon'])) {
		$icon_value = (array_key_exists($menu_item_db_id, $_REQUEST['menu-item-icon'])) ? $_REQUEST['menu-item-icon'][$menu_item_db_id] : '';
		update_post_meta($menu_item_db_id, '_menu_item_icon', $icon_value);
	} else {
		update_post_meta($menu_item_db_id, '_menu_item_icon', '');
	}
	if (isset($_REQUEST['menu-item-megamenu']) && is_array($_REQUEST['menu-item-megamenu'])) {
		$megamenu_value = (array_key_exists($menu_item_db_id, $_REQUEST['menu-item-megamenu'])) ? $_REQUEST['menu-item-megamenu'][$menu_item_db_id] : '';
		update_post_meta($menu_item_db_id, '_menu_item_megamenu', $megamenu_value);
	} else {
		update_post_meta($menu_item_db_id, '_menu_item_megamenu', '');
	}
	if (isset($_REQUEST['menu-item-button']) && is_array($_REQUEST['menu-item-button'])) {
		$button_value = (array_key_exists($menu_item_db_id, $_REQUEST['menu-item-button'])) ? $_REQUEST['menu-item-button'][$menu_item_db_id] : '';
		update_post_meta($menu_item_db_id, '_menu_item_button', $button_value);
	} else {
		update_post_meta($menu_item_db_id, '_menu_item_button', '');
	}
	if ( apply_filters( 'uncode_activate_menu_badges', false ) ) {
		if (isset($_REQUEST['menu-item-badge-text']) && is_array($_REQUEST['menu-item-badge-text'])) {
			$badge_text_value = (array_key_exists($menu_item_db_id, $_REQUEST['menu-item-badge-text'])) ? $_REQUEST['menu-item-badge-text'][$menu_item_db_id] : '';
			update_post_meta($menu_item_db_id, '_menu_item_badge_text', $badge_text_value);
		} else {
			delete_post_meta($menu_item_db_id, '_menu_item_badge_text');
		}
		if (isset($_REQUEST['menu-item-badge-color']) && is_array($_REQUEST['menu-item-badge-color'])) {
			$badge_color_value = (array_key_exists($menu_item_db_id, $_REQUEST['menu-item-badge-color'])) ? $_REQUEST['menu-item-badge-color'][$menu_item_db_id] : '';
			update_post_meta($menu_item_db_id, '_menu_item_badge_color', $badge_color_value);
		} else {
			delete_post_meta($menu_item_db_id, '_menu_item_badge_color');
		}
	}
}

function uncode_edit_walker()
{
	return 'Walker_Nav_Menu_Edit_Custom';
}

function uncode_edit_walker_scripts( $hook ) {
	if ( 'nav-menus.php' === $hook ) {
		wp_enqueue_script( 'menu-iconpicker', get_template_directory_uri() . '/core/assets/js/menu-iconpicker.js', false, UNCODE_VERSION , false);
		wp_enqueue_script( 'menu-fontpicker', get_template_directory_uri() . '/core/assets/js/min/jquery.fonticonpicker.min.js', array('menu-iconpicker'), UNCODE_VERSION , false);
	}
}

add_filter('wp_setup_nav_menu_item', 'uncode_add_custom_nav_fields');
add_action('wp_update_nav_menu_item', 'uncode_update_custom_nav_fields', 10, 3);
add_filter('wp_edit_nav_menu_walker', 'uncode_edit_walker', 10);
add_action( 'admin_enqueue_scripts', 'uncode_edit_walker_scripts' );

require_once ('edit_custom_walker.php');

/////////////////////////
// oEmbed Admin helper //
/////////////////////////

function uncode_admin_get_oembed()
{
	$code = $mime = '';
	$width = 1;
	$height = 1;
	$urlEnterd = isset($_REQUEST['urlOembed']) ? urldecode($_REQUEST['urlOembed']) : die();
	$onlycode = isset($_REQUEST['onlycode']) ? $_REQUEST['onlycode'] : false;

	$WP_oembed = new WP_oEmbed();
	$raw_provider = parse_url($WP_oembed->get_provider($urlEnterd));

	if (isset($raw_provider['host']))
	{
		$host = $raw_provider['host'];
		$strip = array(
			"www.",
			"api.",
			"embed.",
			"publish.",
		);
		$bare_host = str_replace($strip, "", $host);
		$bare_host = explode('.', $bare_host);

		$mime = 'oembed/' . $bare_host[0];

		$code = wp_oembed_get($urlEnterd);
		preg_match_all('/(width|height)=("[^"]*")/i', $code, $img_attr);
		if (isset($img_attr[2][0])) {
			$width = preg_replace('/\D/', '', $img_attr[2][0]);
		}
		if (isset($img_attr[2][1])) {
			$height = preg_replace('/\D/', '', $img_attr[2][1]);
		}

		if ($bare_host[0] === 'youtube') {
			$parts = parse_url($urlEnterd);
			if (isset($parts['query'])) {
				parse_str($parts['query'], $query);
				if (isset($query['v'])) {
					$idvideo = $query['v'];
				} else {
					$idvideo = $parts['path'];
					$idvideo = str_replace('/', '', $idvideo);
				}
			} else {
				$idvideo = $parts['path'];
				$idvideo = str_replace('/', '', $idvideo);
			}
			$code = '<img src="https://img.youtube.com/vi/' . $idvideo . '/hqdefault.jpg" />';
		} else if ($bare_host[0] === 'vimeo') {
			$urlEnterd = preg_replace('/#.*/', '', $urlEnterd);
			$vimeo = unserialize(wp_remote_fopen("http://vimeo.com/api/v2/video/".basename(strtok($urlEnterd, '?')).".php"));
			if ( isset( $vimeo[0] ) && isset( $vimeo[0]['thumbnail_large'] ) ) {
				$code = '<img src="' . $vimeo[0]['thumbnail_large'] . '" />';
			} else {
				$code = '';
			}
		} else if ($bare_host[0] === 'flickr') {
			$code = preg_replace('/<\/?a[^>]*>/','',$code);
		}
	}
	else
	{
		if (preg_match('/(\.jpg|\.jpeg|\.png|\.bmp|\.webp)$/i', $urlEnterd) || preg_match('/(\.jpg?|\.jpeg?|\.png?|\.bmp?)/i', $urlEnterd) || strpos($urlEnterd, 'imgix') !== false)
		{
			$code = '<img src="' . esc_url( $urlEnterd ) . '" />';
			$mime = 'image/url';
			if ($onlycode == 'false')
			{
				if ($getsize = @getimagesize(sanitize_url($urlEnterd)))
				{
					if (isset($getsize[0])) {
						$width = $getsize[0];
					}
					if (isset($getsize[1])) {
						$height = $getsize[1];
					}
				}
				else
				{
					$width = 'indefinit';
					$height = 'indefinit';
				}
			}
		} else {
			if( strpos( strtolower($urlEnterd), '<iframe' ) !== false ) {
				$mime = 'oembed/iframe';
				preg_match_all('/(width|height)=("[^"]*")/i',$urlEnterd, $iframe_size);
				if (isset($iframe_size[2][0])) {
					preg_match("|\d+|", $iframe_size[2][0], $width);
					$width = $width[0];
				}
				if (isset($iframe_size[2][1])) {
					preg_match("|\d+|", $iframe_size[2][1], $height);
					$height = $height[0];
				}
			} else if( strpos( strtolower($urlEnterd), '<svg' ) !== false ) {
				$mime = 'oembed/svg';
				preg_match_all('/(width|height)=("[^"]*")/i',$urlEnterd, $svg_size);
				if (isset($svg_size[2][0])) {
					preg_match("|\d+|", $svg_size[2][0], $width);
					$width = $width[0];
				}
				if (isset($svg_size[2][1])) {
					preg_match("|\d+|", $svg_size[2][1], $height);
					$height = $height[0];
				}
			} else $mime = 'oembed/html';
			$code = esc_html($urlEnterd);
		}
	}

	if ($code == '' && $urlEnterd != '') {
		$code = 'null';
	}

	echo json_encode(array(
		'code' => $code,
		'mime' => $mime,
		'width' => $width,
		'height' => $height
	));

	die();
}

function uncode_action_add_attachment( $metadata, $attachment_id ) {
	$width = $height = '';
	$attachment = get_post($attachment_id);
	if ($attachment && $attachment->post_mime_type === 'image/svg+xml') {
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
		  require_once (ABSPATH . '/wp-admin/includes/file.php');
		  WP_Filesystem();
		}
		$xmlget = $wp_filesystem->get_contents($attachment->guid);
		preg_match_all('/(width|height)=("[^"]*")/i', $xmlget, $img_attr);
		if (isset($img_attr[2][0])) {
			$width = preg_replace('/\D/', '', $img_attr[2][0]);
		}
		if (isset($img_attr[2][1])) {
			$height = preg_replace('/\D/', '', $img_attr[2][1]);
		}
		if ($width !== '' && $height !== '') {
			$metadata['width'] = (int)$width;
			$metadata['height'] = (int)$height;
		}
	}
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'uncode_action_add_attachment', 10, 2 );

//For logged in users
add_action('wp_ajax_get_oembed','uncode_admin_get_oembed');
//For logged out users
add_action('wp_ajax_nopriv_get_oembed','uncode_admin_get_oembed');


///////////////////////////
// Adaptive Images Utils //
///////////////////////////

/**
 * AJAX utility function for get all the images.
 */
function uncode_list_images() {

	if(!function_exists('disk_free_space')) {
		die();
	}

	$erase = (isset($_POST['erase']) && $_POST['erase'] === 'true') ? true : false ;
	$query_images_args = array(
		'post_type'      => 'attachment',
		'post_mime_type' =>'image',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
	);

	$query_images = new WP_Query( $query_images_args );
	$images_block = array();
	$files = array();
	foreach ( $query_images->posts as $image) {
		$image_id = $image->ID;
		$filename = get_attached_file( $image_id );
		if ( apply_filters( 'uncode_force_delete_uia_meta_data', false ) ) {
			uncode_delete_uia_meta_data( $image_id );
		}
		if ($filename != '') {
			$extension_pos = strrpos($filename, '.');
			$filename_wildcard = substr($filename, 0, $extension_pos) . '*' . substr($filename, $extension_pos);
			$image_block = glob($filename_wildcard);
			if (is_array($image_block)) {
				foreach ($image_block as $key => $image) {
					if (strpos($image_block[$key],'-uai-') !== false) {
						if ($erase) {
							unlink($image_block[$key]);
							if ( ! apply_filters( 'uncode_force_delete_uia_meta_data', false ) ) {
								uncode_delete_uia_meta_data( $image_id );
							}
							do_action( 'uncode_delete_crop_image', $image_id, $image_block[$key] );
						} else {
							$files[] = $image_block[$key];
						}
					}
				}
			}
			$images_block[] = $image_block;
		}
	}

	$files_size = 0;

	foreach ( $files as $image) {
		$files_size += filesize($image);
	}

	function formatBytes($size, $precision = 2) {
		$base = log($size, 1024);
		$suffixes = array('', 'k', 'M', 'G', 'T');

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	$bytes = ($files_size !== 0) ? formatBytes($files_size, 2) : 0;

	wp_send_json_success(
		array(
			'message' => sprintf( esc_html__( 'The Adaptive Images / Dynamic Srcset system is using %1$s of the %2$s space left.', 'uncode' ), $bytes, formatBytes(disk_free_space("."), 2) )
		)
	);
}

/* AJAX call to load all images */
add_action( 'wp_ajax_list_images', 'uncode_list_images' );

/**
 * delete all the AI images version when an attachment is erased
 */
function uncode_delete_uia_files($postId) {
	global $wpdb;
	$filename = get_attached_file( $postId);
	if ($filename != '') {
		$extension_pos = strrpos($filename, '.');
		$filename_wildcard = substr($filename, 0, $extension_pos) . '*' . substr($filename, $extension_pos);
		$image_block = glob($filename_wildcard);
		foreach ($image_block as $key => $image) {
			if (strpos($image_block[$key],'-uai-') !== false) {
				unlink($image_block[$key]);
			}
		}
	}
}

add_action( 'delete_attachment', 'uncode_delete_uia_files' );

/**
 * Delete AI entry from attachment meta
 */
function uncode_delete_uia_meta_data( $image_id ) {
	$media_data           = wp_get_attachment_metadata( $image_id );
	$media_data_sizes     = isset( $media_data[ 'sizes' ] ) ? $media_data[ 'sizes' ] : array();
	$new_media_data_sizes = array();

	// Remove uai images
	foreach ( $media_data_sizes as $size => $size_data ) {
		if ( strpos( $size, 'uai') === false ) {
			$new_media_data_sizes[ $size ] = $size_data;
		}
	}

	// Set new sizes
	$media_data[ 'sizes' ] = $new_media_data_sizes;

	wp_update_attachment_metadata( $image_id, $media_data );
}

/**
 * Override export menu
 */
function uncode_override_export_menu() {
	add_submenu_page( 'tools.php', 'Export', 'Export', 'manage_options', 'uncode-export', 'export_submenu_page_callback' );
	global $submenu;
	unset($submenu['tools.php'][15]);
}

add_action('admin_menu', 'uncode_override_export_menu');

function uncode_header_export_xml(){
    global $pagenow;

    if( 'tools.php' == $pagenow && isset($_GET['page']) && 'uncode-export' == $_GET['page'] && isset($_GET['download']) && 'true' == $_GET['download'] ){

    	$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty($sitename) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.xml';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

			if ( defined( 'UNCODE_EXPORT_TEMPLATE' ) ) {
				require_once( UNCODE_EXPORT_TEMPLATE );
			} else {
				require_once( 'export/uncode_export_template.php' );
			}

			if ( isset( $_GET['download'] ) ) {
				$args = array();

				if ( ! isset( $_GET['content'] ) || 'all' == $_GET['content'] ) {
					$args['content'] = 'all';
				} else if ( 'posts' == $_GET['content'] ) {
					$args['content'] = 'post';

					if ( $_GET['cat'] ) {
						$args['category'] = (int) $_GET['cat'];
					}

					if ( $_GET['post_author'] ) {
						$args['author'] = (int) $_GET['post_author'];
					}

					if ( $_GET['post_start_date'] || $_GET['post_end_date'] ) {
						$args['start_date'] = $_GET['post_start_date'];
						$args['end_date'] = $_GET['post_end_date'];
					}

					if ( $_GET['post_status'] ) {
						$args['status'] = $_GET['post_status'];
					}
				} else if ( 'pages' == $_GET['content'] ) {
					$args['content'] = 'page';

					if ( $_GET['page_author'] ) {
						$args['author'] = (int) $_GET['page_author'];
					}

					if ( $_GET['page_start_date'] || $_GET['page_end_date'] ) {
						$args['start_date'] = $_GET['page_start_date'];
						$args['end_date'] = $_GET['page_end_date'];
					}

					if ( $_GET['page_status'] ) {
						$args['status'] = $_GET['page_status'];
					}
				} else {
					$args['content'] = $_GET['content'];
				}

				uncode_export_wp( $args );

				die();
			}
    }
}

add_action( 'admin_init', 'uncode_header_export_xml' );

/**
 * TinyMce add MARK buttn
 */
add_action( 'init', 'uncode_mark_button' );
function uncode_mark_button() {
    add_filter( "mce_external_plugins", "uncode_mark_add_button" );
    add_filter( 'mce_buttons', 'uncode_mark_register_button' );
}
function uncode_mark_add_button( $plugin_array ) {
    $plugin_array['uncodemarkbutton'] = $dir = get_template_directory_uri() . '/core/assets/js/tinymce.js';
    return $plugin_array;
}
function uncode_mark_register_button( $buttons ) {
    array_push( $buttons, 'markbutton' );
    return $buttons;
}

if ( ! function_exists( 'uncode_get_current_post_type' ) ) :
/**
 * Get post type in any case.
 * @since Uncode 1.6.0
 */
function uncode_get_current_post_type() {
  global $post, $typenow, $current_screen, $pagenow;
  if($post && $post->post_type) {
  	if ( isset( $_GET['uncodeblock'] ) ) {
		return 'uncodeblock';
	}
    return $post->post_type;
  } elseif($typenow) {
    return $typenow;
  } elseif($current_screen && $current_screen->post_type) {
    return $current_screen->post_type;
  } elseif(isset($_REQUEST['post_type'])) {
    return sanitize_key( $_REQUEST['post_type'] );
  } elseif(isset($_GET['post']) && $_GET['post'] != -1) {
    $thispost = get_post($_GET['post']);
    if ( $thispost ) {
	    return $thispost->post_type;
    } else {
		return null;
    }
  } else {
	if ( $pagenow === 'post-new.php' ) {
		return 'post';
	} else {
		return null;
	}
  }
}
endif;

/**
 * Detect js_composer plugin. For use in Admin area only.
 */
if ( uncode_check_for_dependency( 'js_composer/js_composer.php' ) ) {
	function uncode_js_composer_nag() {
    ?>
    <div class="notice error is-dismissible">
      <p><?php esc_attr_e( 'In order to run Uncode you need first to deactivate WPBakery Page Builder and install the Uncode WPBakery Page Builder.', 'uncode' ); ?></p>
    	<p><a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" aria-label="<?php esc_attr_e('Deactivate WPBakery Page Builder','uncode'); ?>"><?php esc_attr_e('Deactivate WPBakery Page Builder','uncode'); ?></a></p>

    </div>
    <?php
	}
	add_action( 'admin_notices', 'uncode_js_composer_nag' );
}

$max_input_vars = ini_get('max_input_vars');
if ( $max_input_vars < uncode_get_recommended_max_input_vars() ) {
	global $pagenow;
	if (is_admin() && $pagenow === 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'uncode-options') {
		function uncode_php_max_vars_nag() {
	    ?>
	    <div class="notice error is-dismissible">
	      <p>
	      	<strong><?php esc_html_e('Warning: PHP max_input_vars.','uncode'); ?></strong>
	      </p>
	      <p>
	      	<?php echo sprintf( wp_kses(__( 'Before saving the theme options you need to address an issue marked on the <strong><a href="%s">System Status</a></strong>.', 'uncode' ), array( 'strong' => array(), 'a' => array( 'href' => array(),'target' => array() ) ) ), admin_url('admin.php?page=uncode-system-status') ); ?>
	      </p>
	    </div>
	    <?php
		}
		add_action( 'admin_notices', 'uncode_php_max_vars_nag' );
	}
}

function uncode_transparent_header_nag() {
	if (!is_admin()) {
		return false;
	}
	if (!isset($_GET['post'])) {
		return false;
	}
	$uncode_post_types = uncode_get_post_types(true);
	$uncode_current_post_type = uncode_get_current_post_type();
	if (in_array($uncode_current_post_type, $uncode_post_types)) {
		$general_style = ot_get_option( '_uncode_general_style');
		$stylemain = ot_get_option( '_uncode_primary_menu_style');
		if ($stylemain === '') {
			$stylemain = $general_style;
		}
		$transpmainheader = ot_get_option('_uncode_menu_bg_alpha_' . $stylemain);
		if ($transpmainheader !== '100') {
			$post_id = $_GET['post'];
			$metabox_data = get_post_custom($post_id);
			$show_nag = false;
			$get_post_type = get_post_type($post_id);
			$get_generic_header = ot_get_option('_uncode_'.$get_post_type.'_header');
			if (isset($metabox_data['_uncode_specific_menu_opaque'][0]) && $metabox_data['_uncode_specific_menu_opaque'][0] !== 'on') {
				if ($get_generic_header === 'none' || $get_generic_header === '') {
					$show_nag = true;
					if ( !isset($metabox_data['_uncode_header_type']) || (isset($metabox_data['_uncode_header_type'][0]) && $metabox_data['_uncode_header_type'][0] === 'none')) {
						$show_nag = true;
					} else {
						$show_nag = false;
					}
				}
				if ($show_nag) {
				?>
					<div class="notice notice-success notice-warning is-dismissible">
						<p><?php echo sprintf( wp_kses(__( 'The menu transparency will not be visible without a declared header <a class="page-options-header-section" href="%s">here</a>.', 'uncode' ), array( 'a' => array( 'href' => array(),'class' => array(),'target' => array() ) ) ), '#_uncode_page_options' ); ?></p>
					</div>
				<?php
				}
			}
		}
	}
}

add_action( 'admin_notices', 'uncode_transparent_header_nag' );

if (!function_exists('uncode_get_post_types')) {
	function uncode_get_post_types($built_in = false) {
		$args = array(
	    'public'                => true,
	    '_builtin'              => false
		);
		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'
		$get_post_types = get_post_types($args,$output,$operator);
		$uncode_post_types = array();
		if (($key = array_search('uncodeblock', $get_post_types)) !== false) {
	    	unset($get_post_types[$key]);
		}
		if (($key = array_search('uncode_gallery', $get_post_types)) !== false) {
	    	unset($get_post_types[$key]);
		}
		if (($key = array_search('popup', $get_post_types)) !== false) {
	    	unset($get_post_types[$key]);
		}
		if ($built_in) {
			$uncode_post_types[] = 'post';
		}
		if ($built_in) {
			$uncode_post_types[] = 'page';
		}
		foreach ($get_post_types as $key => $value) {
			$uncode_post_types[] = $key;
		}

		$uncode_post_types[] = 'author';

		return $uncode_post_types;
	}
}


/**
 * Convert HEX color to RGB
 */

function uncode_hex2rgb($hex)
{
	if ( strpos( $hex, 'rgba') !== false ) {
		return $hex;
	}

	$hex = str_replace("#", "", $hex);

	if (strlen($hex) == 3)
	{
		$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
		$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
		$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
	}
	else
	{
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
	}
	$rgb = array(
		$r,
		$g,
		$b
	);

	return $rgb;
	// returns an array with the rgb values

}

add_filter( 'user_contactmethods', 'uncode_additional_contactmethods', 10, 1 );
if ( ! function_exists( 'uncode_additional_contactmethods' ) ) :
/**
 * User profile socials.
 * @since Uncode 1.5.0
 */
function uncode_additional_contactmethods( $contactmethods ) {
	// Add Facebook.
	$contactmethods['facebook'] = esc_html__( 'Facebook profile URL', 'uncode' );
	// Add Twitter.
	$contactmethods['twitter'] = esc_html__( 'Twitter username or profile URL', 'uncode' );
	// Add Google+.
	$contactmethods['googleplus'] = esc_html__( 'Google+', 'uncode' );
	// Add Dribbble.
	$contactmethods['dribbble'] = esc_html__( 'Dribbble profile URL', 'uncode' );
	// Add Instagram.
	$contactmethods['instagram'] = esc_html__( 'Instagram profile URL', 'uncode' );
	// Add Pinterest.
	$contactmethods['pinterest'] = esc_html__( 'Pinterest page URL', 'uncode' );
	// Add Xing.
	$contactmethods['xing'] = esc_html__( 'Xing profile URL', 'uncode' );
	// Add YouTube.
	$contactmethods['youtube'] = esc_html__( 'YouTube page URL', 'uncode' );
	// Add Vimeo.
	$contactmethods['vimeo'] = esc_html__( 'Vimeo page URL', 'uncode' );
	// Add Tumblr.
	$contactmethods['linkedin'] = esc_html__( 'LinkedIn page URL', 'uncode' );
	// Add Tumblr.
	$contactmethods['tumblr'] = esc_html__( 'Tumblr page URL', 'uncode' );

	return $contactmethods;
}
endif; //uncode_additional_contactmethods

add_action( 'show_user_profile', 'uncode_add_user_qualification' );
add_action( 'edit_user_profile', 'uncode_add_user_qualification' );
if ( ! function_exists( 'uncode_add_user_qualification' ) ) :
/**
 * Enter user qualification.
 * @since Uncode 1.9.2
 */
function uncode_add_user_qualification( $user ) {
	?>
    <table class="form-table">
		<tr id="user-qualification" class="user-qualification-wrap">
            <th>
            	<label for="user_qualification"><?php esc_html_e( 'Qualification', 'uncode' ); ?></label>
            </th>
            <td>
                <input type="text" class="regular-text" name="user_qualification" value="<?php echo esc_html( get_the_author_meta( 'user_qualification', $user->ID ) ); ?>" id="user_qualification"><br />
				<p class="description"><?php esc_html_e( 'Enter a descriptive sentence.','uncode' ); ?></p>
            </td>
        </tr>
    </table>
<?php
}
endif; //uncode_add_user_qualification

add_action('user_register', 'uncode_save_user_qualification');
add_action('profile_update', 'uncode_save_user_qualification');
if ( ! function_exists( 'uncode_save_user_qualification' ) ) :
/**
 * Enter user qualification.
 * @since Uncode 1.9.2
 */
function uncode_save_user_qualification($user_id){
    # again do this only if you can
    if(!current_user_can('manage_options')) {
        return false;
    }

    if ( isset( $_POST[ 'user_qualification' ] ) ) {
	    # save my custom field
	    update_user_meta( absint( $user_id ), 'user_qualification', sanitize_text_field( $_POST[ 'user_qualification' ] ) );
	}
}
endif; //uncode_save_user_qualification

add_action( 'show_user_profile', 'uncode_user_add_meta_featured_image' );
add_action( 'edit_user_profile', 'uncode_user_add_meta_featured_image' );
if ( ! function_exists( 'uncode_user_add_meta_featured_image' ) ) :
/**
 * Edit user featured media.
 * @since Uncode 1.5.0
 */
function uncode_user_add_meta_featured_image( $user ) {
	if ( !is_admin() ) {
		return;
	}
	/* create localized JS array */
	$localized_array = array(
		'upload_text'           => apply_filters( 'ot_upload_text', esc_html__( 'Send to OptionTree', 'uncode' ) ),
		'remove_media_text'     => esc_html__( 'Remove Media', 'uncode' ),
	);
	/* localized script attached to 'option_tree' */
	wp_localize_script( 'admin_uncode_js', 'option_tree', $localized_array );
	wp_enqueue_media();
    $user_uncode_meta = get_the_author_meta( 'user_uncode_meta', $user->ID );
	?>
    <table class="form-table">
		<tr id="user-featured-image" class="user-featured-image-wrap">
			<th>
				<label for="user_uncode_meta[term_media]"><?php esc_html_e( 'Featured Image', 'uncode' ); ?></label>
			</th>
			<td>
				<div class="format-setting-inner">
					<div class="option-tree-ui-upload-parent">
						<input type="text" name="user_uncode_meta[term_media]" id="term_media" value="<?php echo esc_attr( isset($user_uncode_meta['term_media']) ? $user_uncode_meta['term_media'] : '' ); ?>" class="widefat option-tree-ui-upload-input " readonly="">
						<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" title="Add Media"><span class="icon fa fa-plus2"></span><?php esc_html_e( 'Add media','uncode' ); ?></a>
					</div>
					<?php
						if ( isset($user_uncode_meta['term_media']) && wp_attachment_is_image( $user_uncode_meta['term_media'] ) ) {
							$attachment_data = wp_get_attachment_image_src( $user_uncode_meta['term_media'], 'original' );
							/* check for attachment data */
							if ( $attachment_data ) {
								$field_src = $attachment_data[0];
							}
							echo '<div class="option-tree-ui-media-wrap" id="term_media_media">';
							/* replace image src */
							if ( isset( $field_src ) ) {
								$user_uncode_meta['term_media'] = $field_src;
							}

							$post = get_post($user_uncode_meta['term_media']);
							if (isset($post->post_mime_type) && $post->post_mime_type === 'oembed/svg') {
								echo '<div class="option-tree-ui-image-wrap">' . $post->post_content . '</div>';
							} else if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $user_uncode_meta['term_media'] ) ) {
								echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( $user_uncode_meta['term_media'] ) . '" /></div>';
							} else {
								echo '<div class="option-tree-ui-image-wrap"><div class="option-tree-ui-image-wrap"><div class="oembed" onload="alert(\'load\');"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' . esc_url( $user_uncode_meta['term_media'] ) . '</div></div></div>';
							}
							echo '<a href="#" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'uncode' ) . '"><span class="icon fa fa-minus2"></span>' . esc_html__( 'Remove Media', 'uncode' ) . '</a>';
							echo '</div>';
						}
					?>
					<p class="description"><?php esc_html_e( 'Select a media assigned to the author page.','uncode' ); ?></p>
				</div>
			</td>
		</tr>
    </table>
	<script type="text/javascript">
		jQuery( document ).ready(function( $ ) {
			$.fn.uncode_init_upload();
		});
	</script>
<?php
}
endif; //uncode_user_add_meta_featured_image

add_action( 'personal_options_update', 'uncode_user_save_meta_featured_image' );
add_action( 'edit_user_profile_update', 'uncode_user_save_meta_featured_image' );
if ( ! function_exists( 'uncode_user_save_meta_featured_image' ) ) :
/**
 * Save user featured media.
 * @since Uncode 1.5.0
 */
function uncode_user_save_meta_featured_image( $user_id ) {

    if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
    }

    if ( empty( $_POST['user_uncode_meta'] ) ) {
		return false;
    }

    update_user_meta( $user_id, 'user_uncode_meta', $_POST['user_uncode_meta'] );
}
endif; //uncode_user_save_meta_featured_image

/**
 * Check if a new version of Uncode has been installed or updated.
 * @since Uncode 1.6.1
 */
function uncode_check_if_theme_was_updated() {
	$current_action = current_action();
	if ( is_admin() && $current_action === 'wp' ) {
		return;
	}
	$latest_version = get_option( 'uncode_latest_version' );
	if ( ! $latest_version || version_compare( $latest_version, UNCODE_PARENT_VERSION, '<' ) ) {
		update_option( 'uncode_latest_version', UNCODE_PARENT_VERSION, false );
		do_action( 'uncode_upgraded' );
	}
}
add_action( 'admin_init', 'uncode_check_if_theme_was_updated' );
add_action( 'wp', 'uncode_check_if_theme_was_updated' );

/**
 * Create dynamic CSS when upgrading or installing Uncode Core
 */
add_action( 'uncode_upgraded', 'uncode_create_dynamic_css' );

if ( !function_exists('uncode_get_WC_version') ):
/**
 * Get WooCoomerce current version if exists.
 * @since Uncode 1.5.0
 */
function uncode_get_WC_version() {
	return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
}
endif;//uncode_get_WC_version

add_action( 'custom_menu_order', 'uncode_change_menu_cap', 50 );
if ( !function_exists('uncode_change_menu_cap') ):
/**
 * @since Uncode 1.5.0
 */
function uncode_change_menu_cap( $menu_ord ) {
    global $submenu;

    if ( !isset( $submenu['uncode-system-status'] ) ) {
	    return $menu_ord;
    }

    foreach ($submenu['uncode-system-status'] as $position => $menu) {
        if ( isset($menu[2]) && $menu[2]=='uncode-system-status' ) {
            $status = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-import-demo' ) {
            $demo = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-plugins' ) {
            $plugins = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-options' ) {
            $options = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-core-settings' ) {
            $core_settings = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-settings' ) {
            $settings = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-font-stacks' ) {
            $fonts = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
        if ( isset($menu[2]) && $menu[2]=='uncode-support' ) {
            $support = $menu;
            unset($submenu['uncode-system-status'][$position]);
        }
    }

    if ( isset($options) ) {
	    array_unshift( $submenu['uncode-system-status'], $options);
    }
    if ( ot_get_option('_uncode_admin_help') !== 'off' && isset($support) ) {
		array_unshift( $submenu['uncode-system-status'], $support);
    }
    if ( isset($core_settings) ) {
	    array_unshift( $submenu['uncode-system-status'], $core_settings);
    }
    if ( isset($settings) ) {
	    array_unshift( $submenu['uncode-system-status'], $settings);
    }
    if ( isset($fonts) ) {
	    array_unshift( $submenu['uncode-system-status'], $fonts);
    }
    if ( isset($demo) ) {
	    array_unshift( $submenu['uncode-system-status'], $demo);
    }
    if ( isset($plugins) ) {
	    array_unshift( $submenu['uncode-system-status'], $plugins);
    }
    if ( isset($status) ) {
	    array_unshift( $submenu['uncode-system-status'], $status);
    }

    return $menu_ord;
}
endif;//uncode_change_menu_cap

if ( !function_exists('uncode_VC_deregister_pages') ):
	/**
	 * @since Uncode 1.5.0
	 */
	function uncode_VC_deregister_pages() {
		if ( function_exists('unregister_post_type')) {
			//WP 4.5+
			unregister_post_type( 'vc_grid_item' );
		}

		if ( is_admin() ) {
			// VC menu for non admin users
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				remove_action( 'admin_menu', 'vc_menu_page_build' );
				remove_action( 'network_admin_menu', 'vc_network_menu_page_build' );
			}

			// Grid builder page
			remove_action( 'vc_menu_page_build', 'vc_gitem_add_submenu_page' );
		}
	}
endif;//uncode_VC_deregister_pages
add_action( 'init', 'uncode_VC_deregister_pages', 100 );

if ( ! function_exists( 'uncode_VC_remove_submenu_page' ) ):
	function uncode_VC_remove_submenu_page() {
		remove_submenu_page( 'vc-general', 'edit.php?post_type=vc_grid_item' );
	}
endif;
add_action( 'admin_init', 'uncode_VC_remove_submenu_page', 100 );

add_action( 'wp_ajax_uncode_vc_admin_notice_dismiss', 'uncode_vc_admin_notice_dismiss' );
if ( !function_exists( 'uncode_vc_admin_notice_dismiss' ) ) :
/**
 * @since Uncode 1.5.0
 */
function uncode_vc_admin_notice_dismiss() {

    update_option( 'uncode_vc_admin_notice', true );

    die();
}
endif; //mood_dismiss_notice_updates

add_action( 'admin_notices', 'uncode_vc_admin_notice' );
if ( ! function_exists( 'uncode_vc_admin_notice' ) ) :
/**
 * @since Uncode 1.5.0
 */
function uncode_vc_admin_notice() {

	if (!function_exists('vc_editor_post_types')) {
		return;
	}

	$post_type = uncode_get_current_post_type();
	$vc_post_type = vc_editor_post_types();

	if (in_array($post_type, $vc_post_type)) {
		return;
	}

    if ( !get_option('uncode_vc_admin_notice') && $post_type == 'uncodeblock' ) {
?>
    <div class="notice error is-dismissible" id="uncode_vc_admin_notice">
        <p><?php printf( wp_kses_post( __( 'Please activate Content Block in WPBakery Page Builder > <a href="%1s">Role Manager</a>. More info on the documentation, <a href="%2s" target="_blank">click here</a>', 'uncode' ) ), esc_url( admin_url( 'admin.php?page=vc-roles' ) ), esc_url( 'https://support.undsgn.com/hc/en-us/articles/214006125' ) ); ?></p>
    </div>
<?php
    }

}
endif; //uncode_admin_notices

add_action('wp_ajax_uncode_test_vars', 'uncode_test_vars');
if ( ! function_exists( 'uncode_test_vars' ) ) :
/**
 * @since Uncode 1.6.4
 */
function uncode_test_vars() {
	if ( ( isset( $_POST[ 'test_input_vars_from_theme_options_nonce' ] ) && wp_verify_nonce( $_POST[ 'test_input_vars_from_theme_options_nonce' ], 'uncode-theme-options-test-input-vars-nonce' ) ) || ( isset( $_POST[ 'test_input_vars_from_system_status_nonce' ] ) && wp_verify_nonce( $_POST[ 'test_input_vars_from_system_status_nonce' ], 'uncode-system-status-test-input-vars-nonce' ) ) ) {
		$count = count( $_POST[ 'content' ] ) + 1;

		wp_send_json_success(
			array(
				'count' => $count
			)
		);
	}

	// Invalid nonce or data
	wp_send_json_error();
}
endif; //uncode_test_vars

add_action( 'wp_ajax_uncode_update_max_input_vars', 'uncode_update_max_input_vars' );
if ( !function_exists( 'uncode_update_max_input_vars' ) ) :
/**
 * @since Uncode 1.7.0
 */
function uncode_update_max_input_vars() {
	if ( ( isset( $_POST[ 'update_input_vars_from_theme_options_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_input_vars_from_theme_options_nonce' ], 'uncode-theme-options-test-input-vars-nonce' ) ) || ( isset( $_POST[ 'update_input_vars_from_system_status_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_input_vars_from_system_status_nonce' ], 'uncode-system-status-test-input-vars-nonce' ) ) ) {

		// Save also the number of inputs in theme options
		if ( isset( $_POST[ 'theme_options_number_of_inputs' ] ) ) {
			update_option( 'uncode_theme_options_number_of_inputs', intval( $_POST[ 'theme_options_number_of_inputs' ] ), false );
		}

		update_option( 'uncode_test_max_input_vars', intval( $_POST[ 'calculated_vars' ] ), false );
		wp_send_json_success();
	}

	// Invalid nonce or data
	wp_send_json_error();
}
endif; //uncode_update_max_input_vars

if ( ! function_exists( 'uncode_update_theme_options_number_of_inputs' ) ) :
	function uncode_update_theme_options_number_of_inputs() {
		if ( isset( $_POST[ 'update_theme_options_number_of_inputs_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_theme_options_number_of_inputs_nonce' ], 'uncode-theme-options-number-of-inputs-nonce' ) && ( isset( $_POST[ 'theme_options_number_of_inputs' ] ) ) ) {

			update_option( 'uncode_theme_options_number_of_inputs', intval( $_POST[ 'theme_options_number_of_inputs' ] ), false );

			wp_send_json_success();
		}

		// Invalid nonce or data
		wp_send_json_error();
	}
endif;
add_action( 'wp_ajax_uncode_update_theme_options_number_of_inputs', 'uncode_update_theme_options_number_of_inputs' );

if ( ! function_exists( 'uncode_envato_toolkit_deprecated_message' ) ) :

	/**
	 * If Enavto Toolkit is active, add a warning.
	 */
	function uncode_envato_toolkit_deprecated_message() {
		if ( is_admin() && class_exists( 'Envato_WP_Toolkit' ) ) {
			echo '<div class="error"><p>' . sprintf( wp_kses( __( 'Envato WordPress Toolkit has been deprecated. Please deactivate it and <a href="%1$s">register your theme here</a>.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'admin.php?page=uncode-system-status' ) ) ) . '</p></div>';
		}
	}

endif;
add_action( 'admin_notices', 'uncode_envato_toolkit_deprecated_message' );

if ( ! function_exists( 'uncode_add_editor_styles' ) ) :
	/**
	 * Custom stylesheet file for the TinyMCE editor
	 */
	function uncode_add_editor_styles() {
		add_editor_style( get_template_directory_uri() . '/core/assets/css/editor-style.css' );
	}
endif;
add_action( 'admin_init', 'uncode_add_editor_styles' );

if ( ! function_exists( 'uncode_message_when_action_is_required' ) ) :
	/**
	 * Add admin notice when Uncode Core is not active or not updated
	 */
	function uncode_message_when_action_is_required() {
		if ( ! is_admin() ) {
			return;
		}

		$is_active        = class_exists( 'UncodeCore_Plugin' ) ? true : false;
		$is_installed     = file_exists( WP_PLUGIN_DIR . '/uncode-core/uncode-core.php' ) ? true : false;
		$update_available = false;

		if ( $is_installed ) {
			$uncode_core_data = get_plugin_data( WP_PLUGIN_DIR . '/uncode-core/uncode-core.php' );

			if ( isset( $GLOBALS[ 'tgmpa' ]->plugins[ 'uncode-core' ][ 'version' ] ) && isset( $uncode_core_data[ 'Version' ] ) ) {
				if ( version_compare( $GLOBALS[ 'tgmpa' ]->plugins[ 'uncode-core' ][ 'version' ], $uncode_core_data[ 'Version' ], '>' ) ) {
					$update_available = true;
				}
			}
		}

		if ( $update_available ) {
			// Installed and update available. Not necessarily active.
			echo '<div class="notice notice-error error is-dismissible">
					<p><strong>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">' . wp_kses( __( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: <em>Uncode Core</em>.', 'uncode' ), array( 'em' => array() ) ) . '</span>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="' . admin_url( 'admin.php?page=uncode-plugins' ) . '&amp;plugin_status=update">Begin updating plugin</a></span>
					</strong></p>
				</div>';
		} else if ( $is_installed && ! $is_active ) {
			// Installed but not active
			echo '<div class="notice notice-error error is-dismissible">
					<p><strong>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">' . wp_kses( __( 'This theme requires the following plugin: <em>Uncode Core</em>.', 'uncode' ), array( 'em' => array() ) ) . '</span>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="' . admin_url( 'admin.php?page=uncode-plugins' ) . '&amp;plugin_status=activate">Begin activating plugin</a></span>
					</strong></p>
				</div>';
		} else if ( ! $is_installed ) {
			// Not installed
			echo '<div class="notice notice-error error is-dismissible">
					<p><strong>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">' . wp_kses( __( 'This theme requires the following plugin: <em>Uncode Core</em>.', 'uncode' ), array( 'em' => array() ) ) . '</span>
						<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="' . admin_url( 'admin.php?page=uncode-plugins' ) . '&amp;plugin_status=install">Begin installing plugin</a></span>
					</strong></p>
				</div>';
		}
	}
endif;
add_action( 'admin_notices', 'uncode_message_when_action_is_required' );

/**
 * If someone clicks on a menu page that it is now included in Uncode Core
 * and Uncode Core is not active, redirect him to the install plugins page.
 */
function uncode_redirect_to_install_plugins_if_needed() {
	global $plugin_page;

	// Check if the user has the new Uncode Core installed
	if ( class_exists( 'UncodeCore_Plugin' ) && defined( 'UNCODE_CORE_ADVANCED' ) ) {
		return;
	}

	if ( isset( $plugin_page ) ) {
		$uncode_core_pages = array(
			'uncode-import-demo',
			'uncode-font-stacks',
			'uncode-settings',
			'uncode-options',
		);

		if ( in_array( $plugin_page, $uncode_core_pages) ) {
			wp_redirect( admin_url( 'admin.php?page=uncode-plugins' )  );
			die();
		}
	}
}
add_action( 'admin_page_access_denied', 'uncode_redirect_to_install_plugins_if_needed' );
