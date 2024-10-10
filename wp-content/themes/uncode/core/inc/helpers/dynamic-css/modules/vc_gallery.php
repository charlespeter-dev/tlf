<?php
/**
 * VC Media Gallery CSS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generate the CSS for the module
 */
function uncode_get_dynamic_colors_css_for_shortcode_vc_gallery( $shortcode, $custom_color_keys ) {
	$accepted_keys = array(
		'gallery_back_color'    => array( 'bg' ),
		'filter_back_color'     => array( 'bg' ),
		'infinite_button_color' => array( 'button' ),
		'footer_back_color'     => array( 'bg' ),
	);

	$css = '';

	foreach ( $custom_color_keys as $custom_color_key ) {
		if ( ! array_key_exists( $custom_color_key, $accepted_keys ) ) {
			continue;
		}

		$css_value = uncode_get_dynamic_color_attr_data( $shortcode, $custom_color_key, $accepted_keys[$custom_color_key] );

		if ( $css_value ) {
			$css .= $css_value;
		}
	}

	return $css;
}


/**
 * Generate the CSS for the css grids module (both for post index and media gallery as well)
 */
function uncode_get_linear_grids_css_for_shortcode_vc_gallery( $shortcode_data ) {
	$css = '';
	$css_vals = '';
	$id = $shortcode_data['id'];
	$by = isset( $shortcode_data['by'] ) ? $shortcode_data['by'] : '';
	$w = isset( $shortcode_data['w'] ) ? $shortcode_data['w'] : '';
	$h = isset( $shortcode_data['h'] ) ? $shortcode_data['h'] : '';
	$css_class = $by === 'height' ? '.linear-by-h' : '.linear-by-w';

	if ( $by !== 'height' && $w !== '' ) {
		$w .= is_numeric($w) ? 'px' : '';
		$css_vals .= ' width: ' . sanitize_text_field( wp_filter_nohtml_kses( $w ) ) . ';';
	}

	if (  $by === 'height' && $h !== '' ) {
		$h .= is_numeric($h) ? 'px' : '';
		$css_vals .= ' height: ' . sanitize_text_field( wp_filter_nohtml_kses( $h ) ) . ';';
	}

	if ( $css_vals !== '' ) {
		$css .= '.lineargrid-' . $id . $css_class . '.linear-system .linear-wrapper .linear-container .tmb img {' . $css_vals . '}';
		$css .= '.lineargrid-' . $id . $css_class . '.linear-system .linear-wrapper .linear-container .tmb .fluid-object {' . $css_vals . '}';
	}

	return $css;
}
