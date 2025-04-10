<?php
/**
 * Sorting menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Create sorting dropdown
 */
function uncode_woocommerce_print_sorting_dropdown( $sorting_default_text, $echo = true ) {
	$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
	$catalog_orderby_options = apply_filters(
		'woocommerce_catalog_orderby',
		array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sort by popularity', 'woocommerce' ),
			'rating'     => __( 'Sort by average rating', 'woocommerce' ),
			'date'       => __( 'Sort by latest', 'woocommerce' ),
			'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
		)
	);

	if ( isset( $catalog_orderby_options[ 'menu_order' ] ) && $sorting_default_text !== '' ) {
		$catalog_orderby_options[ 'menu_order' ] = $sorting_default_text;
	}

	$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
	$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.

	if ( wc_get_loop_prop( 'is_search' ) ) {
		$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );

		unset( $catalog_orderby_options['menu_order'] );
	}

	if ( ! $show_default_orderby ) {
		unset( $catalog_orderby_options['menu_order'] );
	}

	if ( ! wc_review_ratings_enabled() ) {
		unset( $catalog_orderby_options['rating'] );
	}

	if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
		$orderby = current( array_keys( $catalog_orderby_options ) );
	}

	// Unset selected option and add it to the top
	if ( isset( $catalog_orderby_options[ $orderby ] ) ) {
		$selected_sort = array( $orderby => $catalog_orderby_options[ $orderby ] );
		unset( $catalog_orderby_options[ $orderby ] );
	}

	ob_start();
	?>

	<ul class="menu-smart sm uncode-woocommerce-sorting<?php if ( is_rtl() ) { echo ' sm-rtl'; } ?>">
		<li class="uncode-woocommerce-sorting__item menu-item"><a href="<?php echo esc_url( add_query_arg( 'orderby', key( $selected_sort ) ) ); ?>" data-toggle="dropdown" class="dropdown-toggle mobile-toggle-trigger uncode-woocommerce-sorting__link no-isotope-filter no-grid-filter"><?php echo esc_html( $selected_sort[ key( $selected_sort ) ] ); ?></a>
			<ul class="drop-menu sm-nowrap uncode-woocommerce-sorting-dropdown ul-mobile-dropdown">
				<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
					<li class="uncode-woocommerce-sorting-dropdown__item"><a class="uncode-woocommerce-sorting-dropdown__link no-isotope-filter no-grid-filter" href="<?php echo esc_url( add_query_arg( 'orderby', $id ) ); ?>"><?php echo esc_html( $name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>

	<?php

	$out = ob_get_clean();
	if ( $echo ) {
		echo uncode_switch_stock_string( $out );
	} else {
		return $out;
	}
}

/**
 * Print result count
 */
function uncode_woocommerce_print_result_count( $total, $per_page, $current_page, $echo = true ) {
	$default_orderby = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;

	// If products follow the default order this doesn't need to be informed.
	$orderby = 'menu_order' === $orderby ? '' : $orderby;

	$orderby = is_string( $orderby ) ? $orderby : '';

	$catalog_orderedby_options = apply_filters(
		'woocommerce_catalog_orderedby',
		array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sorted by popularity', 'woocommerce' ),
			'rating'     => __( 'Sorted by average rating', 'woocommerce' ),
			'date'       => __( 'Sorted by latest', 'woocommerce' ),
			'price'      => __( 'Sorted by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sorted by price: high to low', 'woocommerce' ),
		)
	);
	$orderedby                 = isset( $catalog_orderedby_options[ $orderby ] ) ? $catalog_orderedby_options[ $orderby ] : '';
	$orderedby                 = is_string( $orderedby ) ? $orderedby : '';

	$args = array(
		'total'    => absint( $total ),
		'per_page' => $per_page,
		'current'  => $current_page ? $current_page : 1,
		'orderedby' => $orderedby,
	);

	ob_start();
	wc_get_template( 'loop/result-count.php', $args );

	$out = ob_get_clean();
	if ( $echo ) {
		echo uncode_switch_stock_string( $out );
	} else {
		return $out;
	}
}
