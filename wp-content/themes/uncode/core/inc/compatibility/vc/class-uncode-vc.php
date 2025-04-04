<?php
/**
 * VC related functions support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_VC_Functions' ) ) :

/**
 * Uncode_VC_Functions Class
 */
class Uncode_VC_Functions {

	/**
	 * Construct.
	 */
	public function __construct() {
		// Disable Gutenberg when VC is active
		add_action( 'uncode_upgraded', array( $this, 'disable_gutenberg' ) );
		// Don't load Gutenberg CSS if disabled
		add_filter( 'uncode_load_gutenberg_css', array( $this, 'disable_gutenberg_css' ) );
		// Run actions after theme setup
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
	}

	/**
	 * Disable Gutenberg when VC is active
	 * Just check the "Disable Gutenberg" option
	 */
	public function disable_gutenberg() {
		global $wp_version;

		if ( ! get_option( 'wpb_js_gutenberg_disable' ) && ! get_option( 'uncode_check_for_vc_gutenberg_disable_option' ) ) {
			update_option( 'wpb_js_gutenberg_disable', true, false );
		}

		update_option( 'uncode_check_for_vc_gutenberg_disable_option', true, false );
	}

	/**
	 * Don't load Gutenberg CSS when disabled
	 */
	public function disable_gutenberg_css() {
		if ( get_option( 'wpb_js_gutenberg_disable' ) && class_exists( 'Vc_Manager' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Run actions after theme setup
	 */
	public function after_setup_theme() {
		// Remove prompt
		remove_action( 'upgrader_process_complete', 'vc_set_promo_editor_popup', 10, 2 );
	}
}

endif;

return new Uncode_VC_Functions();
