<?php
$url = $link = $target = $button_color = $size = $width = $text_skin = $hover_fx = $outline = $wide = $icon = $icon_position = $icon_animation = $border_animation = $radius = $shadow = $shadow_weight = $italic = $display = $inline_mobile = $scale_mobile = $top_margin = $onclick = $rel = $media_lightbox = $lbox_skin = $lbox_transparency = $lbox_dir = $lbox_title = $lbox_caption = $lbox_social = $lbox_deep = $lbox_deep_id = $lbox_no_tmb = $lbox_no_arrows = $lbox_gallery_arrows = $lbox_gallery_arrows_bg = $lbox_zoom_origin = $lbox_connected = $lbox_actual_size = $lbox_full = $lbox_download = $lbox_counter = $lbox_transition = $lb_video_advanced = $lb_autoplay = $lb_muted = $css_animation = $animation_delay = $animation_speed = $el_id = $el_class = $lightbox_data = $custom_typo = $font_family = $font_weight = $text_transform = $letter_spacing = $border_width = $btn_link_size = $dynamic = $quantity = '';
extract(shortcode_atts(array(
	'uncode_shortcode_id' => '',
	'url' => '',
	'link' => '',
	'target' => 'self',
	'button_color' => 'default',
	'button_color_type' => '',
	'button_color_solid' => '',
	'button_color_gradient' => '',
	'size' => '',
	'width' => '',
	'text_skin' => '',
	'outline' => '',
	'hover_fx' => '',
	'wide' => 'no',
	'icon' => '',
	'icon_position' => 'left',
	'icon_animation' => '',
	'border_animation' => '',
	'radius' => '',
	'shadow' => '',
	'shadow_weight' => '',
	'custom_typo' => '',
	'font_family' => '',
	'font_weight' => '',
	'text_transform' => 'initial',
	'letter_spacing' => '',
	'border_width' => '',
	'btn_link_size' => '',
	'btn_link_hover' => '',
	'btn_link_underline' => '',
	'italic' => '',
	'display' => '',
	'inline_mobile' => '',
	'scale_mobile' => '',
	'top_margin' => '',
	'onclick' => '',
	'rel' => '',
	'media_lightbox' => '',
	'lbox_skin' => '',
	'lbox_transparency' => '',
	'lbox_dir' => '',
	'lbox_title' => '',
	'lbox_caption' => '',
	'lbox_social' => '',
	'lbox_deep' => '',
	'lbox_no_tmb' => '',
	'lbox_no_arrows' => '',
	'lbox_gallery_arrows' => '',
	'lbox_gallery_arrows_bg' => '',
	'lbox_connected' => '',
	'lbox_actual_size' => '',
	'lbox_full' => '',
	'lbox_download' => '',
	'lbox_counter' => '',
	'lbox_transition' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'parallax_intensity' => '',
	'parallax_centered' => '',
	'el_id' => '',
	'el_class' => '',
	'dynamic' => '',
	'target' => '',
	'quantity' => '',
	'lb_video_advanced' => '',
	'lb_autoplay' => '',
	'lb_muted' => '',
) , $atts));

if ( $el_id !== '' ) {
	$el_id = ' id="' . esc_attr( trim( $el_id ) ) . '"';
} else {
	$el_id = '';
}

$inline_style_css = uncode_get_dynamic_colors_css_from_shortcode( array(
	'type'       => 'vc_button',
	'id'         => $uncode_shortcode_id,
	'attributes' => array(
		'button_color'          => $button_color,
		'button_color_type'     => $button_color_type,
		'button_color_solid'    => $button_color_solid,
		'button_color_gradient' => $button_color_gradient,
	)
) );

$button_color = uncode_get_shortcode_color_attribute_value( 'button_color', $uncode_shortcode_id, $button_color_type, $button_color, $button_color_solid, $button_color_gradient );

global $lightbox_id, $adaptive_images;

//parse link
$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );
$a_href = $link['url'];
$a_title = $link['title'];
$a_target = $link['target'];
$a_rel = $link['rel'];
$lightbox_data = '';
$lbox_enhance = get_option( 'uncode_core_settings_opt_lightbox_enhance' ) === 'on';

if ($media_lightbox !== '') {
	$lightbox_classes = array();
	if ( get_post_mime_type($media_lightbox) == 'oembed/gallery' && wp_get_post_parent_id($media_lightbox) ) {
		$parent_id = wp_get_post_parent_id($media_lightbox);
		if ( class_exists( 'SitePress' ) ) {
			$parent_id = apply_filters( 'wpml_object_id', $parent_id );
		}
		$media_album_ids = get_post_meta($parent_id, '_uncode_featured_media', true);//string of images in the album
		$media_album_ids_arr = explode(',', $media_album_ids);//array of images in the album
		$a_href = '#';
		$media_album = '';
		$media_dimensions = '';//it will stay empty
		$album_item_dimensions = '';
		$inline_hidden = '';

		foreach ($media_album_ids_arr as $_key => $_value) {
			$album_item_attributes = uncode_get_album_item($_value);
			$album_th_id = $album_item_attributes['poster'];
			if ( $album_th_id == '' ) {
				continue;
			}
			$thumb_attributes = uncode_get_media_info($album_th_id);
			$album_th_metavalues = unserialize($thumb_attributes->metadata);

			if ( !isset($album_th_metavalues['width']) || !isset($album_th_metavalues['height']) ) {
				continue;
			}

			$album_th_w = $album_th_metavalues['width'];
			$album_th_h = $album_th_metavalues['height'];
			if ($album_item_attributes) {
				$album_item_title = (isset($lightbox_classes['data-title']) && $lightbox_classes['data-title'] === true) ? $album_item_attributes['title'] : '';
				$album_item_caption = (isset($lightbox_classes['data-caption']) && $lightbox_classes['data-caption'] === true) ? $album_item_attributes['caption'] : '';
				if (isset($album_item_attributes['width']) && isset($album_item_attributes['height'])) {
					$album_item_dimensions .= '{';
					if ( ! $lbox_enhance ) {
						$album_item_dimensions .= '"title":"' . esc_attr($album_item_title) . '",';
						$album_item_dimensions .= '"caption":"' . esc_html($album_item_caption) . '",';
					} else {
						$subHtml = '';
						if ( $album_item_title !== '' ) {
							$subHtml .= '<h6>' . esc_attr($album_item_title) . '</h6>';
						}
						if ( $album_item_caption !== '' ) {
							$subHtml .= '<p>' . esc_html($album_item_caption) . '</p>';
						}
						if ( $subHtml !== '' ) {
							$album_item_dimensions .= '"subHtml":"' . $subHtml . '",';
						}
					}
					//$album_item_dimensions .= '"post_mime_type":"' . esc_attr($album_item_attributes['mime_type']) . '",';

					if (
						$album_item_attributes['mime_type'] === 'oembed/iframe'
						||
						$album_item_attributes['mime_type'] === 'oembed/vimeo' && uncode_privacy_allow_content( 'vimeo' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/youtube' && uncode_privacy_allow_content( 'youtube' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/spotify' && uncode_privacy_allow_content( 'spotify' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/soundcloud' && uncode_privacy_allow_content( 'soundcloud' ) === false
					) {
						$poster_th_id = $album_th_id;
						$poster_attributes = uncode_get_media_info($poster_th_id);
						$poster_metavalues = unserialize($poster_attributes->metadata);
						$album_item_dimensions .= '"width":"' . esc_attr($poster_metavalues['width']) . '",';
						$album_item_dimensions .= '"height":"' . esc_attr($poster_metavalues['height']) . '",';
						$resize_album_item = wp_get_attachment_image_src($poster_th_id, 'medium');
						$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
						$album_item_dimensions .= '"url":"' . esc_attr('#inline-' . $el_id . '-' . $album_th_id) . '","type":"inline"';
						$inline_hidden .= '<div id="inline-' . esc_attr( $el_id . '-' . $album_th_id ) . '" class="ilightbox-html" style="display: none;">' . $album_item_attributes['url'] . '</div>';
						apply_filters( 'uncode_before_checking_consent', true, $album_item_attributes['mime_type'] );
					} else {
						if (
							$album_item_attributes['mime_type'] === 'oembed/vimeo'
							||
							$album_item_attributes['mime_type'] === 'oembed/youtube'
							||
							$album_item_attributes['mime_type'] === 'oembed/spotify'
							||
							$album_item_attributes['mime_type'] === 'oembed/soundcloud'
							||
							strpos( $album_item_attributes['mime_type'], 'video/' ) !== false
						) {
							$poster_th_id = $album_th_id;
							$poster_attributes = uncode_get_media_info($poster_th_id);
							$poster_metavalues = unserialize($poster_attributes->metadata);
							$resize_album_item = wp_get_attachment_image_src($poster_th_id, 'medium');
							if ( $lbox_enhance ) {
								$album_item_dimensions .= '"thumb":"' . esc_url($resize_album_item[0]) . '",';
							} else {
								$album_item_dimensions .= '"width":"' . esc_attr($poster_metavalues['width']) . '",';
								$album_item_dimensions .= '"height":"' . esc_attr($poster_metavalues['height']) . '",';
								$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
							}
						} else {
							$resize_album_item = wp_get_attachment_image_src($thumb_attributes->id, 'medium');
							if ( $lbox_enhance ) {
								$album_item_dimensions .= '"thumb":"' . esc_url($resize_album_item[0]) . '",';
							} else {
								$album_item_dimensions .= '"width":"' . esc_attr($album_item_attributes['width']) . '",';
								$album_item_dimensions .= '"height":"' . esc_attr($album_item_attributes['height']) . '",';
								$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
							}
						}

						if ( $lbox_enhance ) {
							if ( $album_item_attributes['mime_type'] === 'video/mp4' ) {
								$album_item_dimensions .= '"video":{"source":[{"src":"' . esc_url($album_item_attributes['url']) . '","type":"video/mp4"}],"attributes":{"preload":"false","controls":"true"}}';
							} else {
								$album_item_dimensions .= '"src":"' . esc_url($album_item_attributes['url']) . '"';
							}
						} else {
							$album_item_dimensions .= '"url":"' . esc_url($album_item_attributes['url']) . '"';
						}
					}
					$album_item_dimensions .= '}';
				}
			}

			if ( $_key+1 < count($media_album_ids_arr) ) {
				$album_item_dimensions .= ',';
			}
		}

		$lightbox_data .= ' data-album=\'[' . $album_item_dimensions . ']\'';

		if ($lbox_skin !== '') {
			$lightbox_classes['data-skin'] = $lbox_skin;
		}
		if ($lbox_transparency !== '') {
			$lightbox_classes['data-transparency'] = $lbox_transparency;
		}
		if ($lbox_dir !== '') {
			$lightbox_classes['data-dir'] = $lbox_dir;
		}
		if ($lbox_social !== '') {
			$lightbox_classes['data-social'] = true;
		}
		if ($lbox_deep !== '') {
			$lightbox_classes['data-deep'] = 'gallery_' . $media_lightbox;
		}
		if ($lbox_no_tmb !== '') {
			$lightbox_classes['data-notmb'] = true;
		}
		if ($lbox_no_arrows !== '') {
			$lightbox_classes['data-noarr'] = true;
		}
		if ($lbox_gallery_arrows !== '') {
			$lightbox_classes['data-arrows'] = $lbox_gallery_arrows;
		}
		if ($lbox_gallery_arrows_bg !== '') {
			$lightbox_classes['data-arrows-bg'] = $lbox_gallery_arrows_bg;
		}
		if ($lbox_actual_size !== '') {
			$lightbox_classes['data-actual-size'] = true;
		}
		if ($lbox_full !== '') {
			$lightbox_classes['data-full'] = true;
		}
		if ($lbox_download !== '') {
			$lightbox_classes['data-download'] = true;
		}
		if ($lbox_counter !== '') {
			$lightbox_classes['data-counter'] = true;
		}
		if ( $lbox_transition !== '' ) {
			$lightbox_classes['data-transition'] = esc_attr($lbox_transition);
		}
		if ( $lbox_connected !== '' ) {
			$lightbox_classes['data-connect'] = true;
		}
		if (count($lightbox_classes) === 0) {
			$lightbox_classes['data-active'] = true;
		}
		if ($lbox_connected === 'yes' && isset($lightbox_id) && $lightbox_id !== '' ) {
			$lbox_id = $lightbox_id;
		} else {
			$lbox_id = uncode_big_rand();
		}

		$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $lightbox_classes, array_keys($lightbox_classes));

	} else {
		if ( class_exists( 'SitePress' ) ) {
			$media_lightbox = apply_filters( 'wpml_object_id', $media_lightbox );
		}
		$media_attributes = uncode_get_media_info($media_lightbox);
		if (isset($media_attributes)) {
			$media_metavalues = unserialize($media_attributes->metadata);
			$media_mime = $media_attributes->post_mime_type;
			$media_dimensions = '';
			$video_src = $video_enhanced = '';
			if (isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
				$media_dimensions = 'width:' . $media_metavalues['width'] . ',';
				$media_dimensions .= 'height:' . $media_metavalues['height'] . ',';
			}
			if (strpos($media_mime, 'image/') !== false && $media_mime !== 'image/url' && isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
				$image_orig_w = $media_metavalues['width'];
				$image_orig_h = $media_metavalues['height'];
				if ($adaptive_images === 'on') {
					$adaptive_images = 'off';
					$big_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
					$adaptive_images = 'on';
				} else {
					$big_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
				}

				$a_href = $big_image['url'];
			} elseif ($media_mime === 'oembed/iframe') {
				$lightbox_classes['data-type'] = 'inline';
				$a_href = '#inline-' . $media_lightbox;
				echo '<div id="inline-' . esc_attr( $media_lightbox ) . '" class="ilightbox-html" style="display: none;">' . $media_attributes->post_content . '</div>';
			} else {
				if ($media_mime === 'image/url') {
					$a_href = $media_attributes->guid;
				} else {
					$media_oembed = uncode_get_oembed($media_lightbox, $media_attributes->guid, $media_attributes->post_mime_type, false, $media_attributes->post_excerpt, $media_attributes->post_content, true);
					$consent_id = str_replace( 'oembed/', '', $media_mime );
					if ( uncode_privacy_allow_content( $consent_id ) === false ) {
	    				$a_href = '#inline-' . esc_attr( $media_lightbox ) . '" data-type="inline" target="#inline' . esc_attr( $media_lightbox );
	    				$inline_hidden = '<div id="inline-' . esc_attr( $media_lightbox ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
						$poster_th_id = get_post_meta($media_lightbox, "_uncode_poster_image", true);
						$poster_attributes = uncode_get_media_info($poster_th_id);
						if ( is_object($poster_attributes) ) {
							$poster_metavalues = unserialize($poster_attributes->metadata);
							$media_dimensions = 'width:' . esc_attr($poster_metavalues['width']) . ',';
							$media_dimensions .= 'height:' . esc_attr($poster_metavalues['height']) . ',';
						}
	    			} elseif ($media_mime === 'oembed/html' || $media_mime === 'oembed/iframe') {
						$frame_id = 'frame-' . uncode_big_rand();
						$a_href = '#' . $frame_id;
						echo '<div id="' . esc_attr( $frame_id ) . '" style="display: none;">' . $media_attributes->post_content . '</div>';
					} else {
						$a_href = $media_oembed['code'];
					}
				}
			}

			if (isset($media_attributes->post_mime_type) && strpos($media_attributes->post_mime_type, 'video/') !== false) {
				$video_src .= 'html5video:{preload:\'true\',';
				$video_autoplay = get_post_meta($media_lightbox, "_uncode_video_autoplay", true);
				if ($video_autoplay) {
					$video_src .= 'autoplay:\'true\',';
					$video_enhanced .= ' data-autoplay="true"';
				}
				$video_loop = get_post_meta($media_lightbox, "_uncode_video_loop", true);
				if ($video_loop) {
					$video_src .= 'loop:\'true\',';
					$video_enhanced .= ' data-loop="true"';
				}
				$alt_videos = get_post_meta($media_lightbox, "_uncode_video_alternative", true);
				if (!empty($alt_videos)) {
					foreach ($alt_videos as $key => $value) {
						$exloded_url = explode(".", strtolower($value));
						$ext = end($exloded_url);
						if ($ext !== '') {
							$video_src .= $ext . ":'" . $value."',";
						}
					}
				}
				$video_src .= '},';
			}

			if ($lbox_skin !== '') {
				$lightbox_classes['data-skin'] = $lbox_skin;
			}
			if ($lbox_caption !== '') {
				$lightbox_classes['data-caption'] = get_the_excerpt($media_lightbox);
			}
			if ($lbox_transparency !== '') {
				$lightbox_classes['data-transparency'] = $lbox_transparency;
			}
			if ($lbox_transparency !== '') {
				$lightbox_classes['data-transparency'] = $lbox_transparency;
			}
			if ($lbox_dir !== '') {
				$lightbox_classes['data-dir'] = $lbox_dir;
			}
			if ($lbox_social !== '') {
				$lightbox_classes['data-social'] = true;
			}
			if ($lbox_deep !== '') {
				$lightbox_classes['data-deep'] = $media_lightbox;
			}
			if ($lbox_no_tmb !== '') {
				$lightbox_classes['data-notmb'] = true;
			}
			if ($lbox_no_arrows !== '') {
				$lightbox_classes['data-noarr'] = true;
			}
			if ($lbox_gallery_arrows !== '') {
				$lightbox_classes['data-arrows'] = $lbox_gallery_arrows;
			}
			if ($lbox_gallery_arrows_bg !== '') {
				$lightbox_classes['data-arrows-bg'] = $lbox_gallery_arrows_bg;
			}
			if ($lbox_zoom_origin !== '') {
				$lightbox_classes['data-zoom-origin'] = true;
			}
			if ($lbox_actual_size !== '') {
				$lightbox_classes['data-actual-size'] = true;
			}
			if ($lbox_full !== '') {
				$lightbox_classes['data-full'] = true;
			}
			if ($lbox_download !== '') {
				$lightbox_classes['data-download'] = true;
			}
			if ($lbox_counter !== '') {
				$lightbox_classes['data-counter'] = true;
			}
			if ( $lbox_transition !== '' ) {
				$lightbox_classes['data-transition'] = esc_attr($lbox_transition);
			}
			if (count($lightbox_classes) === 0) {
				$lightbox_classes['data-active'] = true;
			}
			if ($lbox_connected === 'yes') {
				if (!isset($lightbox_id) || $lightbox_id === '') {
					$lightbox_id = uncode_big_rand();
				}
				$lbox_id = $lightbox_id;
			} else {
				$lbox_id = $media_lightbox;
			}

			$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $lightbox_classes, array_keys($lightbox_classes));

		}

		$th_size = $lbox_enhance ? 'thumbnail' : 'medium';
		$poster_th_id = get_post_meta($media_lightbox, "_uncode_poster_image", true);
		$poster_attributes = uncode_get_media_info($poster_th_id);
		if ( isset($poster_attributes->id) ) {
			$data_options_th = wp_get_attachment_image_src($poster_attributes->id, $th_size);
		} else {
			if ( isset($media_attributes->id) ) {
				$data_options_th = wp_get_attachment_image_src($media_attributes->id, $th_size);
			}
		}

		if ( $lbox_enhance ) {
			if ( $media_attributes->post_mime_type !== 'oembed/flickr' && isset( $data_options_th[0] ) ) {
				$lightbox_data .= ' data-external-thumb-image="'. $data_options_th[0] .'"';
			}
			if ( $media_attributes->post_mime_type === 'video/mp4' ) {
				$lightbox_data .= 'data-video=\'{"source": [{"src":"' . $a_href . '", "type":"video/mp4"}]}\' data-icon="video"';
			} elseif ( $media_attributes->post_mime_type === 'oembed/youtube' || $media_attributes->post_mime_type === 'oembed/vimeo' ) {
				$lightbox_data .= 'data-icon="video"';
			} elseif ( $media_attributes->post_mime_type === 'oembed/spotify' || $media_attributes->post_mime_type === 'oembed/soundcloud' ) {
				$lightbox_data .= 'data-src="' . $a_href . '" data-iframe="true"';
			}
			$lightbox_data .= $video_enhanced;
			if ( isset($media_metavalues['width']) && isset($media_metavalues['height']) ) {
				$lightbox_data .= ' data-lg-size="' . $media_metavalues['width'] . '-' . $media_metavalues['height'] . '"';
			}
		} else {
			$lightbox_data .= ' data-options="' . esc_attr( $media_dimensions . $video_src ) . '"';
		}

	}

	if ( isset( $div_data_attributes ) && is_array( $div_data_attributes ) ) {
		$lightbox_data .= ' ' . implode(' ', $div_data_attributes);
	}

	$lightbox_data .= ' data-lbox="ilightbox_single-' . esc_attr( $lbox_id ) . '"';

}

// Prepare button classes
$wrapper_class = array('btn-container');
$classes = array('btn');
$div_data = array();

// Size class
$bigtext = false;
if ($size) {
	if ($size === 'link') {
		unset($classes[0]);
		if ( $btn_link_size !== '' ) {
			$classes[] = $btn_link_size;
		}
		if ( $btn_link_size === 'bigtext' ) {
			$bigtext = true;
		}

		if ( $btn_link_hover !== '' ) {
			$classes[] = $btn_link_hover;
		}

		if ( $btn_link_underline !== '' ) {
			$classes[] = $btn_link_underline;
		}
	} else {
		$classes[] = $size;
	}
}

if ($custom_typo==='yes') {
	$classes[] = 'btn-custom-typo';
	$classes[] = $font_family;
	if ( $font_weight !== '' ) {
		$classes[] = 'font-weight-' . $font_weight;
	}
	$classes[] = 'text-' . $text_transform;
	$classes[] = $letter_spacing == '' ? 'no-letterspace' : $letter_spacing;
}

if ($border_width!=='') {
	$classes[] = 'border-width-' . intval($border_width);
} else {
	$classes[] = 'border-width-0';
}

// Additional classes
if ($el_class) {
	$classes[] = $el_class;
}

// Color class
if ($button_color === '') {
	$button_color = 'default';
}
if ($button_color !== 'default') {
	if ($text_skin === 'yes') {
		$classes[] = 'btn-text-skin';
	}
}
if ($size !== 'btn-link' && $size !== 'link') {
	$classes[] = 'btn-' . $button_color;
} else {
	$classes[] = 'text-' . $button_color . '-color';
}

// Radius class
if ($radius) {
	$classes[] = $radius;
}

// Hover effect
$hover_fx = $hover_fx=='' ? ot_get_option('_uncode_button_hover') : $hover_fx;

// Outlined and flat classes
if ( $hover_fx == '' || $hover_fx == 'outlined' ) {
	if ($outline === 'yes' ) {
		$classes[] = 'btn-outline';
	}
} else {
	$classes[] = 'btn-flat';
}

// Shadow class
if ($shadow === 'yes') {
	$classes[] = 'btn-shadow';
	if ( $shadow_weight !== '' ) {
		$classes[] = 'btn-shadow-' . $shadow_weight;
	}
}

// Italic class
if ($italic === 'yes') {
	$classes[] = 'btn-italic';
}

// Wide class
if ($wide === 'yes') {
	$wrapper_class[] = 'btn-block';
	$classes[] = 'btn-block';
}

if ($display === 'inline') {
	// Add margin class
	if ($top_margin === 'yes') {
		$classes[] = 'btn-top-margin';
	}
	$wrapper_class[] = 'btn-inline';
	if ( $inline_mobile === 'yes' ) {
		$wrapper_class[] = 'btn-inline-mobile';
	}
}

if ( $scale_mobile !== '' ) {
	$classes[] = 'btn-no-scale';
}

// Prepare icon
if ($icon !== '') {
	$icon = '<i class="' . esc_attr($icon) . '"></i>';
	if ($icon_animation === 'yes') {
		$classes[] = 'btn-icon-fx';
	}
} else {
	$icon = '';
}

$content = trim($content);

if ($icon_position === 'right') {
	$content = $content . $icon;
	$classes[] = 'btn-icon-right';
} else {
	$content = $icon . $content;
	$classes[] = 'btn-icon-left';
}

if ($border_animation !== '') {
	$classes[] = $border_animation;
	$classes[] = 'btn-border-animated';
}

if ( $dynamic === 'gallery' ) {
	$wrapper_class[] = 'btn-gallery-trigger';

	if ( $target !== '' ) {
		$target = preg_replace('/\#/i', '', $target);
		$div_data['data-target'] = esc_attr( $target );
	}
}

if ( $dynamic === 'ajax_filters' ) {
	$wrapper_class[] = 'btn-ajax-filters-trigger';
}

$el_class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . implode(' ', $classes), $this->settings['base'], $atts );

// Prepare onclick action
$onclick = ($onclick !== '') ? ' onClick="' . esc_attr( $onclick ) . '"' : '';

// Prepare rel attribute
$rel = ($rel) ? ' rel="' . esc_attr($rel) . '"' : '';
if ( $rel == '' && $a_rel ) {
	$rel = ' rel="' . esc_attr($a_rel) . '"';
}

if ($css_animation !== '' && uncode_animations_enabled()) {
	if ( $css_animation === 'parallax' ) {
		$wrapper_class[] = 'parallax-el';
		$div_data = array_merge( $div_data, uncode_get_parallax_div_data( $parallax_intensity, $parallax_centered ) );
	} else {
		$wrapper_class[] = 'animate_when_almost_visible ' . $css_animation;
		if ($animation_delay !== '') {
			$div_data['data-delay'] = $animation_delay;
		}
		if ($animation_speed !== '') {
			$div_data['data-speed'] = $animation_speed;
		}
	}
}

if ($width !== '') {
	$width = ' style="min-width:' . esc_attr( $width ) . 'px"';
}

$title = ($a_title !== '') ? ' title="' . esc_attr( $a_title ) . '"' : '';
$_target = (trim($a_target) !== '') ? ' target="' . esc_attr( trim($a_target) ) . '"' : '';

if ( $lb_video_advanced === 'yes' ) {
	if ( $lb_autoplay !== '' ) {
		$div_data['data-lb-autoplay'] = $lb_autoplay;
	}
	if ( $lb_muted !== '' ) {
		$div_data['data-lb-muted'] = $lb_muted;
	}
}
$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$bigtext_start = $bigtext_end = '';
if ( $bigtext ) {
	$bigtext_start = '<span>';
	$bigtext_end = '</span>';
}

if ( isset( $inline_hidden ) && $inline_hidden !== '' ) {
	echo uncode_switch_stock_string( $inline_hidden );
}

global $product, $post, $is_header_cb;
$or_post = $post;
$or_product = $product;

if ( ( $dynamic === 'add-to-cart' || $dynamic === 'permalink' ) && ! $product ) {
	$product_object = uncode_populate_post_object();
} else {
	$product_object = $product;
}

if ( class_exists( 'WooCommerce' ) && function_exists('is_product') && is_product() && $dynamic == 'add-to-cart' ) {

	if ( apply_filters( 'uncode_hide_vc_button', false ) ) {
		return;
	}

	do_action( 'uncode_woocommerce_single_product_summary_21' );
}

$post_type = uncode_get_current_post_type();
if ( class_exists( 'WooCommerce' ) && is_a( $product_object, 'WC_Product' ) ) {
	if ( ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) || $post_type == 'uncodeblock' ) {
		$post = get_post($product_object->get_id());
		$product = wc_get_product($product_object->get_id());
	}

	if ( $dynamic === 'permalink' && $product_object ) {
		$a_href = get_permalink( $product_object->get_id() );
	}
}

if ( class_exists( 'WooCommerce' ) && $dynamic === 'add-to-cart' && is_a( $product_object, 'WC_Product' ) ) {
	$product_type = $product_object->get_type();
	$product_allowed = true;
	$product_args = array();
	$button_args = array();

	if ( $quantity !== '' ) {
		$quantity_controller_func = '__return_' . ( $quantity === 'variation' ? 'true' : 'false' );
		add_filter( 'uncode_input_quantity_controller', $quantity_controller_func );
	}

	$quantity_wide_func = $wide !== 'yes' || $product_type === 'grouped' ? '__return_false' : '__return_true';
	add_filter( 'uncode_input_quantity_wide', $quantity_wide_func );

	$allowed_product_types = array(
		'grouped',
		'variable',
		'external',
		'simple'
	);

	if ( ! in_array( $product_type, apply_filters( 'uncode_allowed_product_types', $allowed_product_types ) ) ) {
		do_action('uncode_woocommerce_add_to_cart');
	} else {
		switch ( $product_type ) {
			case 'grouped':
				$products = array_filter( array_map( 'wc_get_product', $product_object->get_children() ), 'wc_products_array_filter_visible_grouped' );
				if ( $products ) {
					$product_args = array(
						'grouped_product'    => $product_object,
						'grouped_products'   => $products,
						'quantites_required' => false,
						'button_class' => esc_attr(trim($css_class)),
						'vc_shortcode' => true,
					);
				} else {
					$product_allowed = false;
				}
				break;
			case 'variable':

				wp_enqueue_script( 'wc-add-to-cart-variation' );

				$get_variations = count( $product_object->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product_object );

				$product_args = array(
					'available_variations' => $get_variations ? $product_object->get_available_variations() : false,
					'attributes'           => $product_object->get_variation_attributes(),
					'selected_attributes'  => $product_object->get_default_attributes(),
					'vc_shortcode' => true,
				);

				global $button_args;
				$button_args['button_class'] = esc_attr(trim($css_class));
				add_filter( 'uncode_woocommerce_single_variation_add_to_cart_button_args', function(){ global $button_args; return $button_args; } );
				break;
			default:
				$_product_type = $product_type;
				if ( ! $product_object->add_to_cart_url() ) {
					$product_allowed = false;
				} else {
					$product_args = array(
						'product_url' => $product_object->add_to_cart_url(),
						'button_text' => $product_object->single_add_to_cart_text(),
						'button_class' => esc_attr(trim($css_class)),
						'vc_shortcode' => true,
					);
				}
				break;
		}

		echo uncode_print_dynamic_inline_style( $inline_style_css );

		if ( $product_allowed && ( ! is_admin() || uncode_is_quick_view() || ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) ) ) {

			wc_get_template( 'single-product/add-to-cart/' . $product_type . '.php', $product_args );
			if ( function_exists('vc_is_page_editable') && vc_is_page_editable() && !$is_header_cb ) {
				echo '</div>';
			}
		}
	}

} else {
	$span_before = '';
	$span_after  = '';

	if ( $button_color_type === 'uncode-gradient' && ( $size === 'btn-link' || $size === 'link' ) ) {
		$span_before = '<span class="btn-gradient-el">';
		$span_after  = '</span>';
		$css_class .= ' btn-gradient-underline';
	} else if ( $btn_link_underline ) {
		$span_before = '<span>';
		$span_after  = '</span>';
	}

	$href_att = ' href="'. $a_href . '"';
	if ( $lbox_enhance ) {
		if ( $media_lightbox && isset($media_attributes) ) {
			if ( $media_attributes->post_mime_type === 'video/mp4' ) {
				$href_att = '';
			}
		}
	}

	$tag = function_exists('vc_is_page_editable') && vc_is_page_editable() ? 'div' : 'span';
	echo '<' . $tag . ' class="' . esc_attr(trim(implode(' ', $wrapper_class))) . '" '.implode(' ', $div_data_attributes).'><a role="button" ' . $href_att . ' class="custom-link ' . esc_attr(trim($css_class)) . '"' . $title . $_target . $onclick . $rel . $lightbox_data . $width . $el_id . '>' . $span_before . $bigtext_start . do_shortcode( apply_filters( 'uncode_get_vc_button_content', $content ) ) . $bigtext_end . $span_after . '</a>' . uncode_print_dynamic_inline_style($inline_style_css) . '</' . $tag . '>';
}

if ( class_exists( 'WooCommerce' ) && function_exists('is_product') && is_product() && $dynamic == 'add-to-cart' ) {
	do_action( 'uncode_woocommerce_single_product_summary_30' );
}

$post = $or_post;
$product = $or_product;
