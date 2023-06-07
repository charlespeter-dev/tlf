<?php
/**
 * The Events Calendar support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if The Events Calendar is active
if ( ! class_exists( 'Tribe__Events__Main' ) ) {
	return;
}

if ( ! class_exists( 'Uncode_Events_Calendar' ) ) :

/**
 * Uncode_Events_Calendar Class
 */
class Uncode_Events_Calendar {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_filter( 'uncode_apply_the_content', '__return_true' );
	}
}

endif;

return new Uncode_Events_Calendar();
