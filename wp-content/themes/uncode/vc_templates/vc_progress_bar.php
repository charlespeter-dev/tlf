<?php
$output = $title = $values = $units = $options = $style = $el_id = $el_class = $css_animation = $animation_delay = $animation_speed = '';
extract( shortcode_atts( array(
	'title' => '',
	'values' => '%5B%7B%22label%22%3A%22Development%22%2C%22value%22%3A%2290%22%7D%2C%7B%22label%22%3A%22Design%22%2C%22value%22%3A%2280%22%7D%2C%7B%22label%22%3A%22Marketing%22%2C%22value%22%3A%2270%22%7D%5D',
	'units' => '',
	'empty_space' => '',
	'options' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'style' => '',
	'el_id' => '',
	'el_class' => ''
), $atts ) );

if ( $el_id !== '' ) {
	$el_id = ' id="' . esc_attr( trim( $el_id ) ) . '"';
} else {
	$el_id = '';
}

$container_class = array('vc_progress_label');
$wrap_class = array('vc_progress_bar wpb_content_element');
$div_data = array();

$wrap_class[] = $this->getExtraClass( $el_class );

if ($css_animation !== '' && uncode_animations_enabled()) {
	$container_class[] = 'animate_when_almost_visible ' . $css_animation;
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

if ( $style !== '' ) {
	$wrap_class[] = 'bar-' . $style;
}

$bar_options = '';
$options = explode( ",", $options );
if ( in_array( "animated", $options ) ) {
	$bar_options .= " animated";
}
if ( in_array( "striped", $options ) ) {
	$bar_options .= " striped";
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, esc_attr(trim(implode(' ', $wrap_class))) , $this->settings['base'], $atts );
$output = '<div class="' . esc_attr($css_class) . '" '  . $el_id . '>';
$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_progress_bar_heading' ) );

$values = (array) vc_param_group_parse_atts( $values );
$max_value = 0.0;
$graph_lines_data = array();
foreach ( $values as $data ) {
	$new_line = $data;
	$new_line['value'] = isset( $data['value'] ) ? $data['value'] : 0;
	$new_line['label'] = isset( $data['label'] ) ? $data['label'] : '';

	if ( $max_value < (float) $new_line['value'] ) {
		$max_value = $new_line['value'];
	}
	$graph_lines_data[] = $new_line;
}

$empty_space = $empty_space === 'yes' ? ' ' : '';

foreach ( $graph_lines_data as $line ) {
	$bar_style = '';
	if ( isset( $line['bar_color_type'] ) && $line['bar_color_type'] === 'uncode-solid' ) {
		$bar_color = '';
		$bar_color_value = isset( $line['bar_color_solid'] ) ? $line['bar_color_solid'] : false;

		if ( $bar_color_value ) {
			$bar_style = ' style="background-color:' . $bar_color_value . ';"';
		}
	} else if ( isset( $line['bar_color_type'] ) && $line['bar_color_type'] === 'uncode-gradient' ) {
		$bar_color = '';
		$bar_color_value = isset( $line['bar_color_gradient'] ) ? $line['bar_color_gradient'] : false;

		if ( strpos( $bar_color_value, 'background' ) !== false ) {
			$value_gradient = json_decode( $bar_color_value );

			if ( isset( $value_gradient->css ) ) {
				$css_value = $value_gradient->css;

				if ( $css_value ) {
					$bar_style = ' style="' . $css_value . '"';
				}
			}
		}
	} else {
		$bar_color = isset($line['bar_color']) ? ' style-' . esc_attr( $line['bar_color'] ) . '-bg' : ' style-accent-bg';
	}

	$back_style = '';
	if ( isset( $line['back_color_type'] ) && $line['back_color_type'] === 'uncode-solid' ) {
		$back_color = '';
		$back_color_value = isset( $line['back_color_solid'] ) ? $line['back_color_solid'] : false;

		if ( $back_color_value ) {
			$back_style = ' style="background-color:' . $back_color_value . ';"';
		}
	} else if ( isset( $line['back_color_type'] ) && $line['back_color_type'] === 'uncode-gradient' ) {
		$back_color = '';
		$back_color_value = isset( $line['back_color_gradient'] ) ? $line['back_color_gradient'] : false;

		if ( strpos( $back_color_value, 'background' ) !== false ) {
			$value_gradient = json_decode( $back_color_value );

			if ( isset( $value_gradient->css ) ) {
				$css_value = $value_gradient->css;

				if ( $css_value ) {
					$back_style = ' style="' . $css_value . '"';
				}
			}
		}
	} else {
		$back_color = isset($line['back_color']) ? ' style-' . esc_attr( $line['back_color'] ) . '-bg style-override' : '';
	}

	$back_color = isset($line['back_color']) ? ' style-' . esc_attr( $line['back_color'] ) . '-bg style-override' : '';
	$unit = ( $units != '' ) ? ' <span class="vc_label_units"><span class="vc_progress_value">' . esc_attr( $line['value'] ) . '</span><span class="vc_progress_unit">' . esc_attr( $empty_space ) . esc_attr( $units ) . '</span></span>' : '';

	$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

	$output .= '<div class="'.esc_attr(trim(implode(' ', $container_class))).'" '.implode(' ', $div_data_attributes).'>' . $line['label'] . '<small class="vc_label">' . $unit . '</small></div>';
	$output .= '<div class="vc_single_bar'.$back_color.'" ' . $back_style . '>';
	if ( $line['value'] !== false ) {
		if ( $max_value > 100.00 ) {
			$percentage_value = (float)$line['value'] > 0 && $max_value > 100.00 ? round( (float)$line['value'] / $max_value * 100, 4 ) : 0;
		} else {
			$percentage_value = $line['value'];
		}
		$output .= '<span class="vc_bar' . $bar_color . $bar_options . '" data-percentage-value="' . esc_attr( $percentage_value ) . '" data-value="' . esc_attr($line['value']) . '" ' . $bar_style . '></span>';
		$output .= '</div>';
	}
}

$output .= '</div>';

echo uncode_remove_p_tag($output);
