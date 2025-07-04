<?php

function uncode_center_nav_menu_items($items, $args) {
	if ( $args->theme_location == 'primary') {
		if (is_array($items) || is_object($items)) {
			global $logo_html;
			$menu_items = array();
			foreach ($items as $key => $item) {
				if (!$item->menu_item_parent) {
					$menu_items[] = $key;
				}
			}
			$new_item_array = array();
			$new_item = new stdClass;
			$new_item->ID = 0;
			$new_item->db_id = 0;
			$new_item->menu_item_parent = 0;
			$new_item->url = '';
			$new_item->title = $logo_html;
			$new_item->menu_order = 0;
			$new_item->object_id = 0;
			$new_item->description = '';
			$new_item->attr_title = '';
			$new_item->button = '';
			$new_item->megamenu = '';
			$new_item->logo = true;
			$new_item->classes = array('mobile-hidden','tablet-hidden');
			$new_item_array[] = $new_item;
			$get_position = apply_filters( 'uncode_split_menu_logo_position', floor(count($menu_items) / 2) - 1, $menu_items);
			array_splice($items, $menu_items[$get_position], 0, $new_item_array);
		}
	}

	return $items;
}

/*****************
*
*   MENU BUILDER
*
******************/

if (!class_exists('unmenu')) {
	class unmenu {

		public $html;

		function __construct($type, $param)
		{
			global $LOGO, $metabox_data, $post, $menutype, $adaptive_images, $adaptive_images_async, $ai_width;

			$general_style = ot_get_option( '_uncode_general_style' );
			$stylemain = ot_get_option( '_uncode_primary_menu_style' );
			$menu_sticky_mobile = ot_get_option( '_uncode_menu_sticky_mobile' );
			$menu_mobile_overlay = ot_get_option('_uncode_menu_mobile_centered');
			$menu_mobile_off_cavas = $menu_sticky_mobile === 'on' && $menu_mobile_overlay === 'off-canvas' ? ' menu-parent-off-canvas' : '';

			if ($stylemain === '') {
				$stylemain = $general_style;
			}

			$social_to_append = false;

			$type = ($type == '') ? 'hmenu-right' : $type;
			$vertical = (strpos($type, 'vmenu') !== false || $type === 'menu-overlay' || $type === 'menu-overlay-center') ? true : false;

			$social_html = $social_html_inner = $secondary_menu_html = $search = $main_absolute = $stylemainback = $stylesecback = $main_width = $menu_bloginfo = $vmenu_position = $row_inner_class = '';

			$menu_custom_padding = ot_get_option('_uncode_menu_custom_padding');
			$data_padding_shrink = '';
			if ($menu_custom_padding === 'on') {
				$custom_menu_padding_desktop = ot_get_option('_uncode_menu_custom_padding_desktop');
				$custom_menu_padding_desktop_shrinked = $custom_menu_padding_desktop > 9 ? $custom_menu_padding_desktop - 9 : 0;
				$data_padding_shrink = ' data-padding-shrink ="' . esc_attr( $custom_menu_padding_desktop_shrinked ) . '"';
			}

			$logoDiv = '<a href="' . esc_url( apply_filters( 'uncode_logo_url', home_url( '/' ) ) ) . '" class="navbar-brand"' . $data_padding_shrink . ' data-minheight="'.(($LOGO->logo_min == "") ? "20" : esc_attr($LOGO->logo_min)).'" aria-label="' . apply_filters( 'uncode_logo_aria_label', esc_html(get_bloginfo( 'name','display' )) ) . '">';
			$logoDivInner = '';
			$logoMobileInner = '';

			$logo_height = isset( $LOGO->logo_height ) && $LOGO->logo_height ? $LOGO->logo_height: '20';
			$logo_height = preg_replace('/[^0-9.]+/', '', $logo_height);
			$logo_height_mobile = ot_get_option('_uncode_logo_height_mobile');
			if ($logo_height_mobile !== '') {
				$logo_height_mobile = preg_replace('/[^0-9.]+/', '', $logo_height_mobile);
			} else {
				$logo_height_mobile = $logo_height;
			}
			$logo_hide = $logo_mobile_hide = '';
			$secondary_enhanced = get_option( 'uncode_core_settings_opt_enhanced_top_bar' );

			if ( isset($LOGO->logo_mobile_id) && $LOGO->logo_mobile_id !== '' ) {
				if (!is_array($LOGO->logo_mobile_id)) {
					$LOGO->logo_mobile_id = array($LOGO->logo_mobile_id);
				}
				foreach ($LOGO->logo_mobile_id as $key => $value) {
					$logo_alt = get_post_meta($value, '_wp_attachment_image_alt', true);
					if ( empty($logo_alt) ) {
						$logo_alt = esc_html__('logo','uncode');
					}
					$logo_info = uncode_get_media_info($value);
					$media_metavalues = (isset($logo_info->metadata)) ? unserialize($logo_info->metadata) : array();
					$logoSkinClass = 'mobile-logo ';
					if (!empty($logo_info)) {
						if (count($LOGO->logo_mobile_id) === 2) {
							if ($key === 0 && $stylemain === 'light') {
								$logo_hide = '';
							} elseif ($key === 1 && $stylemain === 'dark') {
								$logo_hide = '';
							} else {
								$logo_hide = 'display:none;';
							}
							$logoSkinClass .= $key === 0 ? 'logo-light' : 'logo-dark';
						} else {
							$logoSkinClass .= 'logo-skinnable';
						}
						if ($logo_info->post_mime_type === 'oembed/svg') {
							$media_code = $logo_info->post_content;
							$media_width = isset($media_metavalues['width']) ? absint( $media_metavalues['width'] ) : 1;
							$media_height = isset($media_metavalues['height']) ? absint( $media_metavalues['height'] ) : 1;
							$logo_ratio = $media_width / $media_height;
							$rand_id = rand();
							$media_code = preg_replace('#\s(id)="([^"]+)"#', ' $1="$2-' .$rand_id .'"', $media_code);
							$media_code = preg_replace('#\s(fill)="url\(\'\#([^"]+)\'\)"#', ' $1="url(\'#$2-' .$rand_id .'\')"', $media_code);
							$media_code = preg_replace('#\s(xmlns)="([^"]+)"#', '', $media_code);
							$media_code = preg_replace('#\s(xmlns:svg)="([^"]+)"#', '', $media_code);
							$media_code = preg_replace('#\s(xmlns:xlink)="([^"]+)"#', '', $media_code);
							if ($logo_info->animated_svg) {
								$logoSkinClass = 'main-logo ';
								preg_match('/(id)=("[^"]*")/i', $media_code, $id_attr);
								if (isset($id_attr[2])) {
									$id_icon = str_replace('"', '', $id_attr[2]);
								} else {
									$id_icon = 'icon-' . uncode_big_rand();
									$media_code = preg_replace('/<svg/', '<svg id="' . $id_icon . '"', $media_code);
								}
								$icon_time = (isset($logo_info->animated_svg_time) && $logo_info->animated_svg_time !== '') ? $logo_info->animated_svg_time : 100;
								$media_code .= "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function(event) { UNCODE.vivus('".$id_icon."', '".$icon_time."', false, false); });</script>";
							}
							if ($menutype === 'menu-overlay' || $menutype === 'menu-overlay-center' || $type === 'offcanvas_head') {
								$vmenu_position = ot_get_option('_uncode_vmenu_position');
								if ($vmenu_position === 'left') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMaxYMin" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							} elseif ($vertical) {
								$vmenu_position = ot_get_option('_uncode_vmenu_position');
								if ($vmenu_position === 'right') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMaxYMin" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							} else {
								if ($menutype === 'hmenu-center-split') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMidYMid" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							}
							$logoMobileInner .= '<div class="html-code '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="height: '.$logo_height.'px;'.$logo_hide.'">';
							$logoMobileInner .= '<canvas class="logo-canvas" height="'.round(absint($logo_height)).'" width="'.round($logo_ratio * $logo_height) .'"></canvas>';
							$logoMobileInner .= $media_code . '</div>' ;
						} elseif ($logo_info->post_mime_type === 'oembed/html') {
							$logoMobileInner .= '<h2 class="text-logo h3 '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="font-size:'.$logo_height.'px;'.$logo_hide.'">' . esc_html($logo_info->post_content) . '</h2>' ;
						} else {
							$logo_metavalues = (isset($logo_info->metadata)) ? unserialize($logo_info->metadata) : array();
							$logo_metavalues['width'] = isset($logo_metavalues['width']) ? absint( $logo_metavalues['width']) : 1;
							$logo_metavalues['height'] = isset($logo_metavalues['height']) ? absint( $logo_metavalues['height']) : 1;

							$logoMobileInner .= '<div class="logo-image '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="height: '.$logo_height.'px;'.$logo_hide.'">';
							if ($logo_info->post_mime_type === 'image/svg+xml') {
								if ($logo_info->animated_svg) {
									if (isset($logo_metavalues['width']) && $logo_metavalues['width'] !== 1) {
										$icon_width = ' style="width:'.$logo_metavalues['width'].'px"';
									} else {
										$icon_width = '';
									}
									$id_icon = 'icon-' . uncode_big_rand();
									$icon_time = (isset($logo_info->animated_svg_time) && $logo_info->animated_svg_time !== '') ? $logo_info->animated_svg_time : 100;
									$logoMobileInner .= '<div id="'.$id_icon.'"'.$icon_width.' class="icon-media"></div>';
									$logoMobileInner .= "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function(event) { UNCODE.vivus('".$id_icon."', '".$icon_time."', false, '".$logo_info->guid."'); });</script>";
								} else {
									$logoMobileInner .= '<img src="'.$logo_info->guid.'" alt="logo" width="'.round(absint($logo_metavalues['width'])).'" height="'.round(absint($logo_metavalues['height'])).'" class="img-responsive" />';
								}
							} else {
								$logo_class = ' class="img-responsive"';
								$logoMobileInner .= '<img src="'.$logo_info->guid.'" alt="'.$logo_alt.'" width="'.round(absint($logo_metavalues['width'])).'" height="'.round(absint($logo_metavalues['height'])).'"'.$logo_class.' />';
							}
							$logoMobileInner .= '</div>';
						}
					}
				}
			}
			if (isset($LOGO->logo_id)) {
				if (!is_array($LOGO->logo_id)) {
					$LOGO->logo_id = array($LOGO->logo_id);
				}
				foreach ($LOGO->logo_id as $key => $value) {
					$logo_alt = get_post_meta($value, '_wp_attachment_image_alt', true);
					if ( empty($logo_alt) ) {
						$logo_alt = esc_html__('logo','uncode');
					}
					$logo_info = uncode_get_media_info($value);
					$media_metavalues = (isset($logo_info->metadata)) ? unserialize($logo_info->metadata) : array();
					$media_metavalues['width'] = isset($media_metavalues['width']) ? absint( $media_metavalues['width']) : 1;
					$media_metavalues['height'] = isset($media_metavalues['height']) ? absint( $media_metavalues['height']) : 1;
					$logoSkinClass = 'main-logo ';
					if (!empty($logo_info)) {
						if (count($LOGO->logo_id) === 2) {
							if ($key === 0 && $stylemain === 'light') {
								$logo_hide = '';
							} elseif ($key === 1 && $stylemain === 'dark') {
								$logo_hide = '';
							} else {
								$logo_hide = 'display:none;';
							}
							$logoSkinClass .= $key === 0 ? ' logo-light' : ' logo-dark';
						} else {
							$logoSkinClass .= 'logo-skinnable';
						}
						if ($logo_info->post_mime_type === 'oembed/svg') {
							$media_code = $logo_info->post_content;
							$logo_ratio = (isset($media_metavalues['width']) && $media_metavalues['width'] && isset($media_metavalues['height']) && $media_metavalues['height']) ? $media_metavalues['width'] / $media_metavalues['height'] : 1;
							$rand_id = rand();
							$media_code = preg_replace('#\s(id)="([^"]+)"#', ' $1="$2-' .$rand_id .'"', $media_code);
							$media_code = preg_replace('#\s(fill)="url\(\'\#([^"]+)\'\)"#', ' $1="url(\'#$2-' .$rand_id .'\')"', $media_code);
							$media_code = preg_replace('#\s(xmlns)="([^"]+)"#', '', $media_code);
							$media_code = preg_replace('#\s(xmlns:svg)="([^"]+)"#', '', $media_code);
							$media_code = preg_replace('#\s(xmlns:xlink)="([^"]+)"#', '', $media_code);
							if ($logo_info->animated_svg) {
								$logoSkinClass = 'main-logo ';
								preg_match('/(id)=("[^"]*")/i', $media_code, $id_attr);
								if (isset($id_attr[2])) {
									$id_icon = str_replace('"', '', $id_attr[2]);
								} else {
									$id_icon = 'icon-' . uncode_big_rand();
									$media_code = preg_replace('/<svg/', '<svg id="' . $id_icon . '"', $media_code);
								}
								$icon_time = (isset($logo_info->animated_svg_time) && $logo_info->animated_svg_time !== '') ? $logo_info->animated_svg_time : 100;
								$media_code .= "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function(event) { UNCODE.vivus('".$id_icon."', '".$icon_time."', false, false); });</script>";
							}
							if ($menutype === 'menu-overlay' || $menutype === 'menu-overlay-center' || $type === 'offcanvas_head') {
								$vmenu_position = ot_get_option('_uncode_vmenu_position');
								if ($vmenu_position === 'left') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMaxYMin" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							} elseif ($vertical) {
								$vmenu_position = ot_get_option('_uncode_vmenu_position');
								if ($vmenu_position === 'right') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMaxYMin" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							} else {
								if ($menutype === 'hmenu-center-split') {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMidYMid" ', $media_code);
								} else {
									$media_code = str_replace('<svg ', '<svg preserveAspectRatio="xMinYMin" ', $media_code);
								}
							}
							$logoDivInner .= '<div class="html-code '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="height: '.$logo_height.'px;'.$logo_hide.'">';
							$logoDivInner .= '<canvas class="logo-canvas" height="'.round(absint($logo_height)).'" width="'.round($logo_ratio * $logo_height) .'"></canvas>';
							$logoDivInner .= $media_code . '</div>' ;
						} elseif ($logo_info->post_mime_type === 'oembed/html') {
							$logoDivInner .= '<h2 class="text-logo h3 '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="font-size:'.$logo_height.'px;'.$logo_hide.'">' . esc_html($logo_info->post_content) . '</h2>' ;
						} else {
							$logo_metavalues = (isset($logo_info->metadata)) ? unserialize($logo_info->metadata) : array();
							$logo_metavalues['width'] = isset($logo_metavalues['width']) ? absint( $logo_metavalues['width']) : 1;
							$logo_metavalues['height'] = isset($logo_metavalues['height']) ? absint( $logo_metavalues['height']) : 1;

							$logoDivInner .= '<div class="logo-image '.$logoSkinClass.'" data-maxheight="'.$logo_height.'" style="height: '.$logo_height.'px;'.$logo_hide.'">';
							if ($logo_info->post_mime_type === 'image/svg+xml') {
								if ($logo_info->animated_svg) {
									if (isset($logo_metavalues['width']) && $logo_metavalues['width'] !== 1) {
										$icon_width = ' style="width:'.$logo_metavalues['width'].'px"';
									} else {
										$icon_width = '';
									}
									$id_icon = 'icon-' . uncode_big_rand();
									$icon_time = (isset($logo_info->animated_svg_time) && $logo_info->animated_svg_time !== '') ? $logo_info->animated_svg_time : 100;
									$logoDivInner .= '<div id="'.$id_icon.'"'.$icon_width.' class="icon-media"></div>';
									$logoDivInner .= "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function(event) { UNCODE.vivus('".$id_icon."', '".$icon_time."', false, '".$logo_info->guid."'); });</script>";
								} else {
									$logoDivInner .= '<img src="'.$logo_info->guid.'" alt="'.$logo_alt.'" width="'.round(absint($logo_metavalues['width'])).'" height="'.round(absint($logo_metavalues['height'])).'" class="img-responsive" />';
								}
							} else {
								$logo_class = ' class="img-responsive"';
								$logoDivInner .= '<img src="'.$logo_info->guid.'" alt="'.$logo_alt.'" width="'.round(absint($logo_metavalues['width'])).'" height="'.round(absint($logo_metavalues['height'])).'"'.$logo_class.' />';
							}
							$logoDivInner .= '</div>';
						}
					}
				}
			}
			if ($logoDivInner === '') {
				$logoDivInner .= '<h2 class="text-logo h3 logo-skinnable main-logo" data-maxheight="'.$logo_height.'" style="font-size:'.$logo_height.'px;">' . esc_html(get_bloginfo( 'name','display' )) . '</h2>';
			}
			$logoDiv .= $logoDivInner;
			$logoDiv .= $logoMobileInner;
			$logoDiv .= '</a>';

			// If a logo is set on the customizer, use that one
			if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
				$customizer_logo_id   = get_theme_mod( 'custom_logo' );
				$customizer_logo_data = wp_get_attachment_image_src( $customizer_logo_id , 'full' );

				$logoDiv = '<a href="'.esc_url( apply_filters( 'uncode_logo_url', home_url( get_current_blog_id(), '/' ) ) ).'" class="navbar-brand" data-minheight="20" aria-label="' . apply_filters( 'uncode_logo_aria_label', esc_html(get_bloginfo( 'name','display' )) ) . '"><div class="logo-customizer"><img src="' . esc_url( $customizer_logo_data[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="' . esc_attr( $customizer_logo_data[1] ) . '" height="' . esc_attr( $customizer_logo_data[2] ) . '" /></div></a>';
			}

			$socials = ot_get_option( '_uncode_social_list');
			$boxed = ot_get_option( '_uncode_boxed');
			$menu_bloginfo = ot_get_option( '_uncode_menu_bloginfo');
			$menu_bloginfo_show = '';
			$menu_secondary_show = '';
			$menu_socials_top_bar = '';
			if ( $secondary_enhanced === 'on' ) {
				$menu_secondary_show = ot_get_option( '_uncode_menu_secondary_show' );
			}
			if ( $secondary_enhanced === 'on' && strpos($type, 'hmenu') !== false ) {
				$menu_bloginfo_show = ot_get_option( '_uncode_menu_bloginfo_show' );
				$menu_socials_top_bar = ot_get_option( '_uncode_menu_socials_top_bar' );

				$num_cols = array( $menu_bloginfo_show, $menu_secondary_show, $menu_socials_top_bar );
				$unique_cols = array_unique( $num_cols );
				$unique_cols = array_filter( $unique_cols );
				$row_inner_class = ' top-menu-cols-' . count($unique_cols);
				$row_inner_class .= ' top-enhanced-split-' . ot_get_option( '_uncode_top_bar_responsive' );

				if ( $menu_bloginfo_show === '' && $menu_secondary_show === '' && $menu_socials_top_bar === '' ) {
					$menu_bloginfo = '';
				} else {
					if ( count($unique_cols) === 1 ) {
						foreach ($unique_cols as $key_c => $value_c) {
							$row_inner_class .= ' top-enhanced-' .  $value_c;
							break;
						}
					} else {
						$row_inner_class .= ' top-enhanced-between';
					}
				}
			}

			$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
			$post_type = $post_type === 'product_variation' ? 'product' : $post_type;

			if (is_author()) {
				$post_type = 'author';
			}
			if (is_archive() || is_home()) {
				$post_type .= '_index';
			}
			if (is_404()) {
				$post_type = '404';
			}
			if (is_search()) {
				$post_type = 'search_index';
			}

			$theme_locations = get_nav_menu_locations();

			if (isset($metabox_data['_uncode_specific_menu'][0]) && $metabox_data['_uncode_specific_menu'][0] !== '') {
				$primary_menu = $metabox_data['_uncode_specific_menu'][0];
			} else {
				$menu_generic = ot_get_option( '_uncode_'.$post_type.'_menu');
				if ($menu_generic !== '') {
					$primary_menu = $menu_generic;
				} else {
					$primary_menu = '';
					if (isset($theme_locations['primary'])) {
						$menu_obj = get_term( $theme_locations['primary'], 'nav_menu' );
						if (isset($menu_obj->name)) {
							$primary_menu = $menu_obj->name;
						}
					}
				}
			}

			if (isset($metabox_data['_uncode_specific_menu_width'][0]) && $metabox_data['_uncode_specific_menu_width'][0] !== '') {
				if ($metabox_data['_uncode_specific_menu_width'][0] === 'full') {
					$menu_full_width = true;
				}
			} else {
				$menu_generic_width = ot_get_option( '_uncode_'.$post_type.'_menu_width');
				if ($menu_generic_width === 'full') {
					$menu_full_width = true;
				} else {
					if ( $menu_generic_width === '' ) {
						$menu_full = ot_get_option( '_uncode_menu_full');
						$menu_full_width = ($menu_full !== 'on') ? false : true;
					}
				}
			}
			if (!isset($menu_full_width)) {
				$menu_full_width = false;
			}

			$get_menu_hide = ot_get_option( (wp_is_mobile() ? '_uncode_menu_hide_mobile' : '_uncode_menu_hide') );
			if ( wp_is_mobile() ) {
				if ( ot_get_option('_uncode_menu_sticky_mobile') !== 'on' ) {
					$get_menu_hide = 'off';
				}
			} else {
				if ( ot_get_option('_uncode_menu_hide') !== 'on' ) {
					$get_menu_hide = 'off';
				}
			}
			$menu_hide = ($get_menu_hide === 'on')  ? ' menu-hide' : '';
			$menu_sticky = (ot_get_option( (wp_is_mobile() ? '_uncode_menu_sticky_mobile' : '_uncode_menu_sticky')) === 'on') || ( uncode_is_full_page(true) ) ? ' menu-sticky' : (($get_menu_hide === 'on') ? ' menu-hide-only' : '');
			$menu_no_arrow = (ot_get_option( '_uncode_menu_no_arrows') === 'on')  ? ' menu-no-arrows' : '';
			if ($type === 'vmenu' && $menu_hide != '') {
				if ($menu_sticky !== '' && !uncode_is_full_page(true) ) {
					$menu_hide .= '-vertical';
				}
				if ($menu_sticky !== '') {
					$menu_sticky .= '-vertical';
				}
			}

			if ( $menu_sticky_mobile === 'on' ) {
				$menu_sticky .= ' menu-sticky-mobile';
			}

			$effects = '';
			$menu_shrink = (ot_get_option( '_uncode_menu_shrink' ) === 'on' && $type !== 'hmenu-center') ? ' menu-shrink' : '';
			$effects .= ($menu_hide !== '') ? $menu_hide : '';

			if ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
				$menu_hide = $menu_sticky = $menu_shrink = '';
			}

			$sub_shadows = ot_get_option( '_uncode_submenu_shadows' );
			$sub_shadows_darker = ot_get_option( '_uncode_submenu_darker_shadows') === 'on' ? 'darker-' : '';
			$sub_extra_classes = $sub_shadows !== '' ? ' menu-dd-shadow-' . $sub_shadows_darker . $sub_shadows : '';

			$sub_enhanced = ot_get_option( '_uncode_submenu_style' );
			//if ( $sub_extra_classes != '' ) {
				$sub_extra_classes .= ' ' . esc_attr( $sub_enhanced );
			//}

			if ($boxed === 'on') {
				$effects .= ' limit-width';
			} else {
				if (!$menu_full_width) {
					$main_width = ' limit-width';
				}
			}

			$has_shadows = ot_get_option( '_uncode_menu_shadows') == 'on' ? true : false;
			$has_borders = ot_get_option( '_uncode_menu_borders') == 'on' ? true : false;
			$remove_shadow = (isset($metabox_data['_uncode_specific_menu_no_shadow'][0]) && $metabox_data['_uncode_specific_menu_no_shadow'][0] === 'on') ? true : false;
			$menushadows = $has_shadows ?  ' menu-shadows' : '';
			$menushadows .= $remove_shadow ?  ' force-no-shadows' : '';
			$menuborders = $has_borders ? (($vertical) ? ' vmenu-borders' : ' menu-borders') : ' menu-no-borders';
			$menuborders .= $menuborders === ' menu-borders' && ot_get_option( '_uncode_no_menu_v_borders' ) === 'on' ? ' menu-h-borders' : '';

			$shadow_type = ot_get_option( '_uncode_shadow_type' );
			$needs_after = $shadow_type === 'diffuse' ? ' needs-after' : '';

			$stylemainsubmenu = ot_get_option( '_uncode_primary_submenu_style');
			if ($stylemainsubmenu === '') {
				$stylemainsubmenu = $stylemain;
			}

			$stylesecmenu = ot_get_option( '_uncode_secondary_menu_style');
			if ($stylesecmenu === '') {
				$stylesecmenu = $general_style;
			}

			$transpmainheader = ot_get_option('_uncode_menu_bg_alpha_' . $stylemain);

			$stylemainback = ot_get_option('_uncode_menu_bg_color_' . $stylemain);
			$stylemainback = ($stylemainback === '') ? ' style-' . $stylemain . '-bg' : ' style-' . $stylemainback . '-bg';

			if ($type === 'menu-overlay' || $type === 'menu-overlay-center') {
				$styleoverlay = ot_get_option( '_uncode_overlay_menu_style');
				$bgoverlay = ot_get_option( '_uncode_overlay_menu_bg' );
				$bg_primary_submenu = ot_get_option( '_uncode_submenu_bg_color_' . $styleoverlay );
				$stylemainmenu = ' menu-' . $styleoverlay . ' submenu-' . $styleoverlay;
				$buttonstyle_primary = 'mobile-menu-button-' . $styleoverlay;
				$bgoverlay = 'style-' . ( $bgoverlay == '' ? $styleoverlay : $bg_primary_submenu ) . '-bg';
			} else {
				$stylemainmenu = ' menu-' . $stylemain . ' submenu-' . ($vertical ? $stylemain : $stylemainsubmenu);
				$buttonstyle_primary = 'mobile-menu-button-' . $stylemain;
			}

			$stylemainbackfull = $stylemainback . $menuborders . $menushadows;
			$stylemainback = '';
			$menushadows = '';

			$stylemaincombo = ' menu-primary' . $stylemainmenu;

			$stylesecback = ot_get_option('_uncode_secmenu_bg_color_' . $stylesecmenu);
			$stylesecback = ($stylesecback === '') ? ' style-' . $stylesecmenu . '-bg' : ' style-' . $stylesecback . '-bg';
			$stylesubstylemenu = ' menu-' . $stylesecmenu . ' submenu-' . $stylesecmenu;
			$stylesecbackfull = '';
			$stylesecback = $stylesecback;

			$stylesubcombo = ' menu-secondary' . $stylesubstylemenu;

			if ($transpmainheader !== '100') {
				$remove_transparency = false;
				if (isset($metabox_data['_uncode_specific_menu_opaque'][0]) && $metabox_data['_uncode_specific_menu_opaque'][0] === 'on') {
					$remove_transparency = true;
				} else {
					$get_remove_transparency = ot_get_option( '_uncode_'.$post_type.'_menu_opaque');
					if ($get_remove_transparency === 'on') {
						$remove_transparency = true;
					}
				}

				if (!$remove_transparency) {
					$stylemaincombo .= ' menu-transparent';
					if (!($vertical && $type !== 'offcanvas_head') || $type === 'menu-overlay-center') {
						$stylemaincombo .= ' menu-add-padding';
					}

					$menu_desktop_transparency = ot_get_option('_uncode_menu_desktop_transparency');
					$menu_mobile_transparency = ot_get_option('_uncode_menu_mobile_transparency_scroll');
					$menu_change_skin = ot_get_option('_uncode_menu_change_skin');
					if ($menu_desktop_transparency === 'on' && $type !== 'hmenu-center' && $type !== 'vmenu') {
						$stylemaincombo .= ' menu-desktop-transparent';
					}
					if ($menu_mobile_transparency === 'on') {
						$stylemaincombo .= ' menu-mobile-transparent';
					}
					if ($menu_change_skin === 'on') {
						$stylemaincombo .= ' menu-change-skin';
					}

					$main_absolute = ' menu-absolute';
				}
			}

			$stylemaincombo .= ' style-' . $stylemain . '-original';
			$lateral_padding_style = '';

			if ( $menu_full_width === true || $boxed === 'on' ) {
				$lateral_padding_option = ot_get_option( '_uncode_menu_custom_lateral_padding' );
				switch ($lateral_padding_option) {
					case 72:
						$lateral_padding = ' double';
						break;
					case 108:
						$lateral_padding = ' triple';
						break;
					case 144:
						$lateral_padding = ' quad';
						break;
					case 180:
						$lateral_padding = ' penta';
						break;
					case 216:
						$lateral_padding = ' exa';
						break;
					case 36:
					default:
						$lateral_padding = ' single';
						break;
				}
				$lateral_padding_style .= $lateral_padding !== '' ? esc_attr( $lateral_padding ) . '-h-padding' : ' single-h-padding';
				$stylemaincombo .= $lateral_padding_style;
			}

			$stylemaincombo_overlay = $stylemaincombo . ' style-' . $stylemain . '-override';

			$icons_count = 0;

			$woo_icon = $woo_icon_mobile = $woo_cart_class = '';
			if ( class_exists( 'WooCommerce' ) ) {
				$woo_cart = apply_filters( 'uncode_woo_cart', ot_get_option('_uncode_woocommerce_cart') );
				$woo_icon = apply_filters( 'uncode_woo_icon', ot_get_option('_uncode_woocommerce_cart_icon') );
				if ($woo_cart === 'on' && $woo_icon !== '') {
					$woo_cart_mobile = apply_filters( 'uncode_woo_cart_mobile', ot_get_option('_uncode_woocommerce_cart_mobile') );
					if ($type === 'menu-overlay' || $type === 'menu-overlay-center' || $type === 'offcanvas_head' || $type === 'vmenu-offcanvas') {
						$woo_cart_desktop =  apply_filters( 'uncode_woo_cart_desktop', ot_get_option('_uncode_woocommerce_cart_desktop' ) );
					} else {
						$woo_cart_desktop = '';
					}
					if ($woo_cart_mobile === 'on' || $woo_cart_desktop === 'on') {
						if ($woo_cart_mobile === 'on' && $woo_cart_desktop !== 'on') {
							$woo_cart_class_mobile = 'desktop-hidden ';
							$woo_cart_class = 'mobile-hidden tablet-hidden ';
						} elseif ($woo_cart_mobile !== 'on' && $woo_cart_desktop === 'on') {
							$woo_cart_class = 'desktop-hidden ';
							$woo_cart_class_mobile = 'mobile-hidden tablet-hidden ';
						} else {
							$woo_cart_class = 'hidden ';
							$woo_cart_class_mobile = '';
						}
						global $woocommerce;
                        $checkout_url = wc_get_cart_url();
                        $trigger_side_cart = apply_filters( 'woocommerce_widget_cart_is_hidden', uncode_is_sidecart_hidden() ) ? '' : ' id="trigger_side_cart"';
						$tot_articles = $woocommerce->cart->cart_contents_count;
						$qty_fx = apply_filters( 'uncode_woocommerce_popup_cart_quantity', ot_get_option( '_uncode_woocommerce_popup_cart_quantity'  ) === 'on' );
						if ( $qty_fx !== true ) {
							$icon_badge = (( $tot_articles !== 0 ) ? '<span class="badge">'.$tot_articles.'</span>' : '<span class="badge" style="display: none;"></span>');
						} else {
							$icon_badge = (( $tot_articles !== 0 ) ? '<span class="badge">'.$tot_articles.'</span>' : '<span class="badge"></span>');
						}
						$woo_icon_mobile = '<a class="'.$woo_cart_class_mobile.'mobile-shopping-cart mobile-additional-icon"' . $trigger_side_cart . ' href="'.$checkout_url.'" aria-label="' . apply_filters( 'uncode_woo_cart_aria_label', esc_html__('Shopping cart','uncode') ) . '"><span class="cart-icon-container additional-icon-container"><i class="'.$woo_icon.'"></i>'.$icon_badge.'</span></a>';
						$woo_icon_mobile = apply_filters( 'uncode_woo_cart_icon_mobile', $woo_icon_mobile );
					}
					if ($woo_cart_class === 'hidden') {
						$woo_icon = '';
					} else {
						if ($type == 'offcanvas_head' && $woo_cart_class === '') {
							$woo_cart_class = 'hidden ';
						} else {
							if ( $woo_cart_desktop !== 'off' && $woo_cart_desktop !== '' ) {
								$icons_count++;
							}
						}
						$woo_icon = uncode_add_cart_in_menu($woo_icon, $woo_cart_class);
					}
				} else {
					$woo_icon = '';
				}
				$woo_icon = apply_filters( 'uncode_woo_cart_icon', $woo_icon );
			}

			$login_account_icon = $login_account_icon_mobile = $login_account_class = '';
			$login_account = apply_filters( 'uncode_login_account', ot_get_option('_uncode_login_account') );
			$login_account_icon = apply_filters( 'uncode_login_account_icon', ot_get_option('_uncode_login_account_icon') );
			if ($login_account === 'on' && $login_account_icon !== '') {
				$login_account_mobile = apply_filters( 'uncode_login_account_mobile', ot_get_option('_uncode_woocommerce_cart_mobile') );
				if ($type === 'menu-overlay' || $type === 'menu-overlay-center' || $type === 'offcanvas_head' || $type === 'vmenu-offcanvas') {
					$login_account_desktop =  apply_filters( 'uncode_login_account_desktop', ot_get_option('_uncode_login_account_desktop' ) );
				} else {
					$login_account_desktop = '';
				}
				if ($login_account_mobile === 'on' || $login_account_desktop === 'on') {
					if ($login_account_mobile === 'on' && $login_account_desktop !== 'on') {
						$login_account_class_mobile = 'desktop-hidden ';
						$login_account_class = 'mobile-hidden tablet-hidden ';
					} elseif ($login_account_mobile !== 'on' && $login_account_desktop === 'on') {
						$login_account_class = 'desktop-hidden ';
						$login_account_class_mobile = 'mobile-hidden tablet-hidden ';
					} else {
						$login_account_class = 'hidden ';
						$login_account_class_mobile = '';
					}
					$account_url = uncode_get_login_url();
					$login_account_icon_mobile = '<a class="'.$login_account_class_mobile.'mobile-account-icon mobile-additional-icon" href="'.esc_url($account_url).'" aria-label="' . apply_filters( 'uncode_login_aria_label', esc_html__('Login','uncode') ) . '"><span class="account-icon-container additional-icon-container"><i class="'.$login_account_icon.'"></i></span></a>';
					$login_account_icon_mobile = apply_filters( 'uncode_login_account_icon_mobile', $login_account_icon_mobile );
				}
				if ($login_account_class === 'hidden') {
					$login_account_icon = '';
				} else {
					if ($type == 'offcanvas_head' && $login_account_class === '') {
						$login_account_class = 'hidden ';
					}
					if ( $login_account_desktop !== 'off' && $login_account_desktop !== '' ) {
						$icons_count++;
					}
					$login_account_icon = uncode_add_account_in_menu($login_account_icon, $login_account_class);
				}
			} else {
				$login_account_icon = '';
			}
			$login_account_icon = apply_filters( 'uncode_login_icon', $login_account_icon );

			$woo_wishlist_icon = $woo_wishlist_icon_mobile = $woo_wishlist_class = '';
			if ( class_exists( 'YITH_WCWL' ) ) {
				$woo_wishlist = apply_filters( 'uncode_woo_wishlist', ot_get_option('_uncode_woocommerce_wishlist') );
				$woo_wishlist_icon = apply_filters( 'uncode_woo_wishlist_icon', ot_get_option('_uncode_woocommerce_wishlist_icon') );
				if ($woo_wishlist === 'on' && $woo_wishlist_icon !== '') {
					$woo_wishlist_mobile = apply_filters( 'uncode_woo_wishlist_mobile', ot_get_option('_uncode_woocommerce_cart_mobile') );
					if ($type === 'menu-overlay' || $type === 'menu-overlay-center' || $type === 'offcanvas_head' || $type === 'vmenu-offcanvas') {
						$woo_wishlist_desktop =  apply_filters( 'uncode_woo_wishlist_desktop', ot_get_option('_uncode_woocommerce_wishlist_desktop' ) );
					} else {
						$woo_wishlist_desktop = '';
					}
					if ($woo_wishlist_mobile === 'on' || $woo_wishlist_desktop === 'on') {
						if ($woo_wishlist_mobile === 'on' && $woo_wishlist_desktop !== 'on') {
							$woo_wishlist_class_mobile = 'desktop-hidden ';
							$woo_wishlist_class = 'mobile-hidden tablet-hidden ';
						} elseif ($woo_wishlist_mobile !== 'on' && $woo_wishlist_desktop === 'on') {
							$woo_wishlist_class = 'desktop-hidden ';
							$woo_wishlist_class_mobile = 'mobile-hidden tablet-hidden ';
						} else {
							$woo_wishlist_class = 'hidden ';
							$woo_wishlist_class_mobile = '';
						}
                        $wishlist_url  = get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) );
                        $tot_wishlist_articles = absint( yith_wcwl_count_all_products() );
						$wishlist_icon_badge = (( $tot_wishlist_articles !== 0 ) ? '<span class="badge">'.$tot_wishlist_articles.'</span>' : '<span class="badge" style="display: none;"></span>');
						$woo_wishlist_icon_mobile = '<a class="'.$woo_wishlist_class_mobile.'mobile-wishlist-icon mobile-additional-icon" href="'.esc_url($wishlist_url).'" aria-label="' . apply_filters( 'uncode_woo_wishlist_aria_label', esc_html__('Wishlist','uncode') ) . '"><span class="wishlist-icon-container additional-icon-container"><i class="'.$woo_wishlist_icon.'"></i>' . $wishlist_icon_badge . '</span></a>';
						$woo_wishlist_icon_mobile = apply_filters( 'uncode_woo_wishlist_icon_mobile', $woo_wishlist_icon_mobile );
					}
					if ($woo_wishlist_class === 'hidden') {
						$woo_wishlist_icon = '';
					} else {
						if ($type == 'offcanvas_head' && $woo_wishlist_class === '') {
							$woo_wishlist_class = 'hidden ';
						}
						$woo_wishlist_icon = uncode_add_wishlist_in_menu($woo_wishlist_icon, $woo_wishlist_class);
						if ( $woo_wishlist_desktop !== 'off' && $woo_wishlist_desktop !== '' ) {
							$icons_count++;
						}
					}
				} else {
					$woo_wishlist_icon = '';
				}
				$woo_wishlist_icon = apply_filters( 'uncode_woo_wishlist_icon', $woo_wishlist_icon );
			}

			$search_active = apply_filters( 'uncode_search_active', ot_get_option( '_uncode_menu_search') );

			$search_icon_mobile = $search_desktop = '';
			if ( $search_active === 'on' ) {
				$search_type = ot_get_option('_uncode_menu_search_type' ) === 'products' ? 'products' : 'default';
				$search_type = apply_filters('uncode_search_type', $search_type );

				$search_mobile = apply_filters( 'uncode_search_mobile', ot_get_option('_uncode_woocommerce_cart_mobile') );

				if ($type === 'menu-overlay' || $type === 'menu-overlay-center' || $type === 'offcanvas_head' || $type === 'vmenu-offcanvas') {
					$search_desktop =  apply_filters( 'uncode_search_desktop', ot_get_option('_uncode_menu_search_desktop' ) );
				} else {
					$search_desktop = '';
				}

				if ($search_mobile === 'on' || $search_desktop === 'on') {

					if ($search_mobile === 'on' && $search_desktop !== 'on') {
						$search_class_mobile = 'desktop-hidden ';
					} elseif ($search_mobile !== 'on' && $search_desktop === 'on') {
						$search_class_mobile = 'mobile-hidden tablet-hidden ';
					} else {
						$search_class_mobile = '';
					}

					if ( $menutype === 'menu-overlay-center' ) {
						$search_class_mobile = 'desktop-hidden ';
					}

					$search_icon_mobile  = '<a class="' . $search_class_mobile . ' mobile-search-icon trigger-overlay mobile-additional-icon" data-area="search" data-container="box-container" href="#" aria-label="' . apply_filters( 'uncode_search_aria_label', esc_html__('Search','uncode') ) . '"><span class="search-icon-container additional-icon-container"><i class="fa fa-search3"></i></span></a>';
					$search_icon_mobile  = apply_filters( 'uncode_search_icon_mobile', $search_icon_mobile );

					if ( $menutype !== 'menu-overlay-center' ) {
						if ( $search_desktop !== 'off' && $search_desktop !== '' ) {
							$icons_count++;
						}
					}
				}
			}

			$socials_active = apply_filters( 'uncode_socials_active', ot_get_option( '_uncode_menu_socials') );

			if (!empty($socials)) {
				foreach ($socials as $social) {
					if (isset($social['_uncode_menu_hidden']) && $social['_uncode_menu_hidden'] === 'on' || $social['_uncode_social'] === '') {
						continue;
					}
					$social_rel = apply_filters( 'uncode_social_link_rel', '' );
					$social_rel_html = $social_rel !== '' ? ' rel="' . esc_attr( $social_rel ) . '"' : '';
					$social_responsive = $menu_sticky_mobile !== 'on' ? 'tablet-hidden mobile-hidden ' : '';
					$social_aria = isset($social['_uncode_aria']) && $social['_uncode_aria'] !== '' ? ' aria-label="' . wp_kses_post( $social['_uncode_aria'] ) . '"' : '';
					$social_html_inner .= '<li role="menuitem" class="menu-item-link social-icon ' . $social_responsive . $social['_uncode_social_unique_id'].'"><a href="'.$social['_uncode_link'].'" class="social-menu-link" role="button" target="_blank"' . $social_rel_html . $social_aria . '><i class="'.$social['_uncode_social'].'" role="presentation"></i></a></li>';

					$social_to_append = ' navbar-social';

				}
			}

			$social_html_inner_top = '';

			if ( $menu_socials_top_bar !== '' ) {
				$social_html_inner_top_class = 'menu-smart'.(is_rtl() ? ' sm-rtl' : '').' menu-mini sm';
				$social_html_inner_top_class .= ot_get_option( '_uncode_menu_socials_top_bar_tablet_hide' ) === 'on' ? ' tablet-hidden' : '';
				$social_html_inner_top_class .= ot_get_option( '_uncode_menu_socials_top_bar_mobile_hide' ) === 'on' ? ' mobile-hidden' : '';
				$social_html_inner_top_class .= ' top-enhanced-inner top-enhanced-' .  $menu_socials_top_bar;
				$social_html_inner_top = '<ul class="' . $social_html_inner_top_class . '" role="menu">' . $social_html_inner . '</ul>';
				$social_html_inner = '';
			}

			if ($socials_active === 'on' || $search_active === 'on' || $woo_icon !== '' || $woo_icon_mobile !== '' || $login_account_icon !== '' || $login_account_icon_mobile !== '' || $woo_wishlist_icon !== '' || $woo_wishlist_icon_mobile !== '' || $social_html_inner !== '') {

				$search_inner = '';

				if ($socials_active === 'on' && strpos($type, 'vmenu') === false && $type !== 'hmenu-center-split') {
					if ($param === 'menu-overlay-center' && $vmenu_position === 'right') {
						$search_inner .= '';
					} else {
						$search_inner .= $social_html_inner;
					}
				}

				$show_search_menu_item = true;
				$show_search_dropdown  = true;

				// Horizontal menus
				if ( strpos( $menutype, 'hmenu' ) !== false && $search_active === 'on' && $search_mobile === 'on' ) {
					$show_search_dropdown = false;
				}

				// Off canvas menu
				if ( $menutype === 'vmenu-offcanvas' && $search_active === 'on' && $search_mobile === 'on' && $search_desktop === 'on' ) {
					$show_search_menu_item = false;
				}

				// Overlay menu
				if ( $menutype === 'menu-overlay' && $search_active === 'on' && $search_mobile === 'on' && $search_desktop === 'on' ) {
					$show_search_menu_item = false;
				}

				if ($search_active === 'on' && $show_search_menu_item) {
					$search_icon_class = '';

					if ( $search_mobile === 'on' ) {
						$search_icon_class = 'mobile-hidden tablet-hidden';
					}

					if ( $menutype === 'menu-overlay' && $search_desktop === 'on' ) {
						$search_icon_class = 'desktop-hidden';

						if ( $search_mobile === 'on' ) {
							$search_icon_class = 'hidden';
						}
					}

					if ( $menutype === 'vmenu-offcanvas' && $search_mobile !== 'on' && $search_desktop === 'on' ) {
						$search_icon_class = 'desktop-hidden';
					}

					if ( !( $type == 'offcanvas_head' && $param === 'menu-overlay-center' && $search_desktop !== 'on' ) ) {

						$search_inner .= '<li role="menuitem" class="menu-item-link search-icon style-'.$stylemain.' dropdown ' . $search_icon_class . '">';
						$search_inner .= 	'<a href="#"'.(!$vertical ? ' class="trigger-overlay search-icon" role="button" data-area="search" data-container="box-container"' : '').' aria-label="' . apply_filters( 'uncode_search_aria_label', esc_html__('Search','uncode') ) . '">
													<i class="fa fa-search3"></i>';
						if (!$vertical) {
							$search_inner .= 		'<span class="desktop-hidden">';
						}
							$search_inner .= 		'<span>' .esc_html__('Search','uncode') . '</span>';
						if (!$vertical) {
							$search_inner .=		'</span>';
						}
						$search_inner .=			'<i class="fa fa-angle-down fa-dropdown'.(!$vertical ? ' desktop-hidden' : '').'"></i>
													</a>';

						if ( $show_search_dropdown ) {
							$search_placeholder = $search_type === 'products' ? esc_html__('Search products…','uncode') : esc_html__('Search…','uncode');
							$search_inner .=        '<ul role="menu" class="drop-menu'.(!$vertical ? ' desktop-hidden' : '').'">
														<li role="menuitem">
															<form class="search" method="get" action="'. get_home_url(get_current_blog_id(),'/') .'">
																<input type="search" class="search-field no-livesearch" placeholder="'.$search_placeholder.'" value="" name="s" title="' . $search_placeholder . '" />';

							$search_inner .= $search_type === 'products' ? '<input type="hidden" name="post_type" value="product" />' : '';

							$search_inner .=        		'</form>
														</li>
													</ul>';
						}

						$search_inner .= 	'</li>';

					}
				}

				if ($param === 'menu-overlay-center' && $vmenu_position === 'right' ) {
					if ($socials_active === 'on' && strpos($type, 'vmenu') === false && $type !== 'hmenu-center-split' && !( $param === 'menu-overlay-center' && $type !== 'offcanvas_head' )) {
						$search_inner .= $social_html_inner;
					}
				}

				if ($type === 'menu-overlay-center' && $param === 'menu-overlay-center' && $search_desktop === 'on' ) {
					$search_inner = '';
				}

				if ( $type == 'offcanvas_head' && $param === 'menu-overlay-center' ) {
					$search_inner .= $search_icon_mobile;
				}
				if ( ! ($type == 'offcanvas_head' && $param == 'menu-overlay-center') ) {
					$search_inner .= $login_account_icon;
					$search_inner .= $woo_wishlist_icon;
					$search_inner .= $woo_icon;
				}

				if ($search_inner !== '') {

						if ($vertical) {
							$search .= '<div class="menu-accordion menu-accordion-extra-icons">';
						}
						$search .= '<ul class="menu-smart'.(is_rtl() ? ' sm-rtl' : '').' sm'.($vertical ? ' sm-vertical' : ' menu-icons').($social_html_inner !== '' ? ' menu-smart-social' : '').'" role="menu">';
						$search .= $search_inner;
						$search .= '</ul>';
						if ($vertical) {
							$search .= '</div>';
						}

				}

			}


			if (!empty($socials) && strpos($type, 'vmenu') !== false && $socials_active === 'on') {
				$social_html_responsive = $menu_sticky_mobile !== 'on' ? ' mobile-hidden tablet-hidden' : '';
				$social_html .= '<div class="nav navbar-nav navbar-social"><ul class="menu-smart'.(is_rtl() ? ' sm-rtl' : '').' sm menu-social' . $social_html_responsive . '" role="menu">';
				$social_html .= $social_html_inner;
				$social_html .= '</ul></div>';
			}

			$no_secondary = ot_get_option('_uncode_menu_no_secondary');

			$stylepadding = ot_get_option('_uncode_secondary_padding') === 'on' ? ' top-menu-padding' : '';

			$menu_sub_animation = ot_get_option( '_uncode_menu_li_animation' ) === 'on' ? ' menu-animated' : '';

			$secondary_menu = '';

			if ($no_secondary !== 'on') {

				$bloginfo_breakpoint = $secondary_menu_html = $secondary_menu_html_nav = '';
				if ( $secondary_enhanced === 'on' ) {
					$hide_info_tablet = ot_get_option('_uncode_menu_bloginfo_tablet');
					$hide_info_mobile = ot_get_option('_uncode_menu_bloginfo_mobile');
					if ( $hide_info_tablet === 'on' ) {
						$bloginfo_breakpoint .= ' tablet-hidden';
					}
					if ( $hide_info_mobile === 'on' ) {
						$bloginfo_breakpoint .= ' mobile-hidden';
					}
					$stylepadding .= ' top-menu-enhanced' . $menu_sub_animation;
					$top_sub_enhanced = ' top-menu-enhanced-horizontal ' . esc_attr( $sub_enhanced );
					$top_smart_menu_class = ' top-menu-enhanced-child';
					$top_shadow = $sub_shadows !== '' ? ' menu-dd-shadow-' . $sub_shadows_darker . $sub_shadows : '';
					if ( ot_get_option( '_uncode_topbar_border' ) === 'on' ) {
						$stylepadding .= ' top-menu-border';
					}
				} else {
					$stylepadding .= ' mobile-hidden tablet-hidden';
					$top_shadow = $top_sub_enhanced = $top_smart_menu_class = '';
				}

				$stylepadding .= $lateral_padding_style;

				if ( isset($theme_locations['secondary']) ) {
					$menu_obj = get_term( $theme_locations['secondary'], 'nav_menu' );
					if (isset($menu_obj->name)) {
						$secondary_menu = $menu_obj->name;
					}
					$secondary_menu_html = $secondary_menu_html_nav = wp_nav_menu(
															array(
																	"menu"              => $secondary_menu,
																	"theme_location"    => "secondary",
																	"container"         => "false",
																	"walker"            => new wp_bootstrap_navwalker(),
																	'fallback_cb'    => false,
																	'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																	//"container_class"   => "navbar-topmenu navbar-nav-last",
																	"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '') . " menu-mini sm" . $top_smart_menu_class,
																	"echo"            => 0
																)
															);
				}

				if ($menu_bloginfo !== '' || ($secondary_menu_html_nav !== '' && !empty($secondary_menu_html_nav))) {
					$secondary_menu_html = '<div class="top-menu navbar'.$stylesubcombo.$stylesecbackfull.$stylesecback.$stylepadding.'">
																		<div class="row-menu'.$main_width.'">
																			<div class="row-menu-inner' . $row_inner_class . '">';

																			if ( $secondary_enhanced === 'on' ) {
																				$menu_bloginfo_html = $menu_secondary_html = $menu_socials_html = '';

																				if ( $menu_bloginfo_show !== '' ) {
																					$menu_bloginfo_class = ot_get_option( '_uncode_menu_bloginfo_tablet' ) === 'on' ? ' tablet-hidden' : '';
																					$menu_bloginfo_class .= ot_get_option( '_uncode_menu_bloginfo_mobile' ) === 'on' ? ' mobile-hidden' : '';
																					$menu_bloginfo_html = '<div class="menu-bloginfo top-enhanced-inner top-enhanced-' .  $menu_bloginfo_show . $menu_bloginfo_class . '">
																							<div class="menu-bloginfo-inner style-' . $stylesecmenu . $bloginfo_breakpoint .'">
																								' . strip_tags( $menu_bloginfo,  apply_filters( 'uncode_bloginfo_tags', array ("img", "a", "span", "strong", "em", "i", "b") ) ) . '
																							</div>
																					</div>';
																				}
																				if ( $menu_secondary_show !== '' ) {
																					$menu_secondary_class = ot_get_option( '_uncode_menu_secondary_tablet' ) === 'on' ? ' tablet-hidden' : '';
																					$menu_secondary_class .= ot_get_option( '_uncode_menu_secondary_mobile' ) === 'on' ? ' mobile-hidden' : '';

																					$menu_secondary_html = '<div class="menu-horizontal' . $top_shadow . $top_sub_enhanced . ' top-enhanced-inner top-enhanced-' .  $menu_secondary_show . $menu_secondary_class . '">
																						<div class="navbar-topmenu">'.$secondary_menu_html_nav.'</div>
																					</div>';
																				}
																				if ( $menu_socials_top_bar !== '' ) {
																					$menu_socials_html = $social_html_inner_top;
																				}

																				if ( isset($unique_cols) ) {
																					foreach ($unique_cols as $key => $value_col) {
																						$secondary_menu_html .= '<div class="topbar-col topbar-col-' . esc_attr( $value_col ) . '">';
																							if ( $value_col === $menu_bloginfo_show ) {
																								$secondary_menu_html .= $menu_bloginfo_html;
																							}
																							if ( $value_col === $menu_secondary_show ) {
																								$secondary_menu_html .= $menu_secondary_html;
																							}
																							if ( $value_col === $menu_socials_top_bar ) {
																								$secondary_menu_html .= $menu_socials_html;
																							}
																						$secondary_menu_html .= '</div>';
																					}
																				}
																			} else {
																				$secondary_menu_html .= '<div class="col-lg-0 middle">
																					<div class="menu-bloginfo">
																						<div class="menu-bloginfo-inner style-' . $stylesecmenu . $bloginfo_breakpoint .'">
																							'.$menu_bloginfo.'
																						</div>
																					</div>
																				</div>
																				<div class="col-lg-12 menu-horizontal' . $top_shadow . $top_sub_enhanced . '">
																					<div class="navbar-topmenu navbar-nav-last">'.$secondary_menu_html_nav.'</div>
																				</div>';
																			}
																			$secondary_menu_html .= '</div>
																		</div>
																	</div>';
				}
			}

			$cta_menu = false;
			$no_cta = apply_filters( 'uncode_cta_menu_hide', ot_get_option('_uncode_menu_no_cta') );
			if ($no_cta === 'off' && isset($theme_locations['cta'])) {
				$cta_obj = get_term( $theme_locations['cta'], 'nav_menu' );
				$cta_menu = apply_filters( 'uncode_cta_menu', isset($cta_obj->name) ? $cta_obj->name : false );
			}

			$burger_label_span = $burger_label_close_span = $burger_has_close = '';
			$burger_label = apply_filters( 'uncode_burger_open_label', '' );
			$burger_label_close = apply_filters( 'uncode_burger_close_label', '' );
			if ( $burger_label_close !== '' ) {
				$burger_label_close_span = '<span class="burger-label-close">' . esc_html( $burger_label_close ) . '</span>';
				$burger_has_close = esc_html( ' burger-has-close' );
			}
			if ( $burger_label !== '' ) {
				$burger_label_span = '<span class="burger-label">' . $burger_label_close_span . '<span class="burger-label-open' . esc_html( $burger_has_close ) . '">' . esc_html( $burger_label ) . '</span></span>';
			}

			$search_dd_option = ot_get_option('_uncode_drop_down_search');
			$search_dropdown = '';

			if ( apply_filters( 'uncode_search_active', ot_get_option( '_uncode_menu_search') ) === 'on' && apply_filters( 'uncode_search_dropdown', $search_dd_option ) === 'on' && strpos($menutype, 'hmenu') !== false ) {
				global $overlay_search;
				$overlay_search = 'yes';
				if ( ot_get_option('_uncode_menu_search_type' ) === 'products' ) {
					add_filter( 'uncode_product_search_type', '__return_true' );
				}
				$search_animation = ot_get_option('_uncode_menu_search_animation');
				if ($search_animation !== 'sequential') {
					$search_animation = ' overlay-search-trid';
				} else {
					$search_animation = '';
				}
				$search_dropdown .= '<div class="overlay overlay-search style-' . $stylemainsubmenu . ' ' . $sub_extra_classes . $search_animation . '" data-area="search" data-container="box-container">
					<div class="overlay-search-wrapper">
						<div class="search-container' . $main_width . '">
							<div class="mmb-container"><div class="menu-close-search menu-close-dd mobile-menu-button menu-button-offcanvas mobile-menu-button-dark lines-button overlay-close close" data-area="search" data-container="box-container"><span class="lines lines-dropdown"></span></div></div>' .
							get_search_form( false )
						. '</div>
					</div>
				</div>';
			}

			switch ($type) {

				/**
				 * Horizontal menus
				 * */
				case 'hmenu-right':
				case 'hmenu-left':
				case 'hmenu-justify':
					$this->html = '<div class="menu-wrapper'.$menu_shrink.$menu_sticky.$menu_no_arrow.'">
													'.($no_secondary !== 'on' ? $secondary_menu_html : '').'
													<header id="masthead" class="navbar'.$stylemaincombo.$main_absolute.$menu_sub_animation.' menu-with-logo' . $menu_mobile_off_cavas . '">
														<div class="menu-container'.$effects.$stylemainbackfull.$needs_after.'" role="navigation">
															<div class="row-menu'.$main_width.'">
																<div class="row-menu-inner'.$stylemainback.'">
																	<div id="logo-container-mobile" class="col-lg-0 logo-container middle">
																		<div id="main-logo" class="navbar-header style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																		<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
																	</div>
																	<div class="col-lg-12 main-menu-container middle">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">
																				'.wp_nav_menu( array(
																					"menu"              => $primary_menu,
																					"theme_location"    => "primary",
																					"container"         => "div",
																					"container_class"   => "nav navbar-nav navbar-main " . (($search !== '' || $type === 'hmenu-justify' || $cta_menu) ? 'navbar-nav-first' : 'navbar-nav-last') ,
																					'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																					"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																					"fallback_cb"       => false,
																					"walker"            => new wp_bootstrap_navwalker(),
																					"echo"            => 0)
																				);

																				if ( $cta_menu ) {
																					$this->html .= wp_nav_menu( array(
																						"menu"              => $cta_menu,
																						"theme_location"    => "cta",
																						"container"         => "div",
																						"container_class"   => "nav navbar-nav navbar-cta" . ( ( $search === '' ) ? ' navbar-nav-last' : '' ),
																						"menu_class"        => "menu-cta-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																						"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																						"walker"            => new wp_bootstrap_navwalker(),
																						"echo"            => 0)
																					);
																				}
																				$this->html .= apply_filters( 'uncode_menu_before_socials', false );
																				$this->html .= ( ($search !== '' || ( $type === 'hmenu-justify' && !$cta_menu) ) ? '<div class="nav navbar-nav navbar-nav-last navbar-extra-icons">'.$search.'</div>' : '');
						if ($no_secondary !== 'on' && $secondary_enhanced !== 'on') {
							$this->html .=										'<div class="desktop-hidden menu-accordion-secondary">
														 							'.wp_nav_menu( array(
															 							"menu"              => $secondary_menu,
															 							"theme_location"    => "secondary",
															 							"container"         => "div",
																						"container_class"   => "menu-accordion menu-accordion-2",
															 							"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical mobile-secondary-menu",
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
															 							'fallback_cb'    => false,
															 							"walker"            => new wp_bootstrap_navwalker(),
															 							"echo"            => 0)
															 						) .
															 					'</div>';
						}
						$this->html .=										'</div>
																		</div>
																	</div>
																</div>
															</div>'
															. $search_dropdown .
														'</div>
													</header>
												</div>';
					break;

				/**
				 * Center menu
				 * */
				case 'hmenu-center':
					$this->html = '<div class="menu-wrapper'.$menu_sticky.$menu_no_arrow.$menu_sub_animation.' style-'.$stylemain.'-original">'.
													($no_secondary !== 'on' ? $secondary_menu_html : '').
													'<div class="navbar menu-secondary'.str_replace(' menu-transparent', '', $stylemaincombo).$main_absolute.'">
														<div class="menu-container-mobile '.str_replace(' menu-shadows', '', $stylemainbackfull).$effects.'">
															<div class="row-menu style-'.$stylemain.'-bg">
																<div class="row-menu-inner">
																	<div id="logo-container-mobile" class="col-lg-0 logo-container">
																		<div id="main-logo" class="navbar-header style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																	</div>
																</div>
																<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
															</div>
														</div>
													</div>
													<header id="masthead" class="navbar'.str_replace(' menu-transparent', '', $stylemaincombo).'">
														<div class="menu-container'.$effects.$stylemainbackfull.$needs_after.'" role="navigation">
															<div class="row-menu'.$main_width.'">
																<div class="row-menu-inner'.$stylemainback.'">
																	<div class="col-lg-12 main-menu-container middle">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">
																				'.wp_nav_menu( array(
																					"menu"              => $primary_menu,
																					"theme_location"    => "primary",
																					"container"         => "div",
																					"container_class"   => "nav navbar-nav navbar-main " . (($search !== '' || $cta_menu) ? 'navbar-nav-first' : 'navbar-nav-last') ,
																					'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																					"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																					"fallback_cb"       => false,
																					"walker"            => new wp_bootstrap_navwalker(),
																					"echo"            => 0)
																				);

																				if ( $cta_menu ) {
																					$this->html .= wp_nav_menu( array(
																						"menu"              => $cta_menu,
																						"theme_location"    => "cta",
																						"container"         => "div",
																						"container_class"   => "nav navbar-nav navbar-cta" . ( ( $search === '' ) ? ' navbar-nav-last' : '' ),
																						"menu_class"        => "menu-cta-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																						"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																						"walker"            => new wp_bootstrap_navwalker(),
																						"echo"            => 0)
																					);
																				}
																				$this->html .= apply_filters( 'uncode_menu_before_socials', false );
																				$this->html .= ($search !== '' ? '<div class="nav navbar-nav navbar-nav-last navbar-extra-icons">'.$search.'</div>' : '');
					if ($no_secondary !== 'on' && $secondary_enhanced !== 'on') {
							$this->html .=						'<div class="desktop-hidden menu-accordion-secondary">
														 							'.wp_nav_menu( array(
															 							"menu"              => $secondary_menu,
															 							"theme_location"    => "secondary",
															 							"container"         => "div",
																						"container_class"   => "menu-accordion menu-accordion-3",
															 							"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical mobile-secondary-menu",
																						 'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
															 							'fallback_cb'    => false,
															 							"walker"            => new wp_bootstrap_navwalker(),
															 							"echo"            => 0)
															 						) .
															 					'</div>';
					}
						$this->html .=						'</div>
																		</div>
																	</div>
																</div>
															</div>'
															. $search_dropdown .
														'</div>
													</header>
												</div>';
					break;

				case 'hmenu-center-split':
					global $logo_html;
					$logo_html = '<div class="logo-container megamenu-diff middle">
													<div id="main-logo" class="navbar-header style-'.$stylemain.'">
														'.$logoDiv.'
													</div>
													<div class="mmb-container"><div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
												</div>';
												add_filter('wp_nav_menu_objects', 'uncode_center_nav_menu_items', apply_filters( 'uncode_center_nav_menu_items_priority', 10 ), 2);
												$this->html = '<div class="menu-wrapper'.$menu_shrink.$menu_sticky.$menu_no_arrow.$menu_sub_animation.'">
													'.($no_secondary !== 'on' ? $secondary_menu_html : '').'
													<header id="masthead" class="navbar'.$stylemaincombo.$main_absolute.' menu-with-logo' . $menu_mobile_off_cavas . '">
														<div class="menu-container'.$effects.$stylemainbackfull.$needs_after.'" role="navigation">
															<div class="row-menu'.$main_width.'">
																<div class="row-menu-inner'.$stylemainback.'">
																	<div id="logo-container-mobile" class="col-lg-0 logo-container megamenu-diff desktop-hidden">
																		<div class="navbar-header style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																		<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
																	</div>
																	<div class="col-lg-12 main-menu-container middle">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">';
						if ($social_html_inner !== '' && $socials_active === 'on') {
							$this->html .=						'<div class="nav navbar-nav navbar-social navbar-nav-first">
																	<ul class="menu-smart'.(is_rtl() ? ' sm-rtl' : '').' sm menu-icons" role="menu">
																		'.$social_html_inner.'
																	</ul>
																</div>';
						}

																if ( $cta_menu ) {
																	$this->html .= wp_nav_menu( array(
																		"menu"              => $cta_menu,
																		"theme_location"    => "cta",
																		"container"         => "div",
																		"container_class"   => "nav navbar-nav navbar-cta hmenu-center-split-child" . ( ( $social_html_inner === '' ) ? ' navbar-nav-first' : '' ),
																		'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																		"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																		"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																		"walker"            => new wp_bootstrap_navwalker(),
																		"echo"            => 0)
																	);
																}

								$ceter_split_nav_menu =			wp_nav_menu( array(
																	"menu"              => $primary_menu,
																	"theme_location"    => "primary",
																	"container"         => "div",
																	"container_class"   => "nav navbar-nav navbar-main",
																	'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																	"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																	"fallback_cb"       => false,
																	"walker"            => new wp_bootstrap_navwalker(),
																	"echo"            => 0)
																);
							if ( $ceter_split_nav_menu != '' ) {
								$this->html .= $ceter_split_nav_menu;
							} else {
								$this->html .= '<div class="nav navbar-nav navbar-main"><ul id="menu-main-menu" class="menu-primary-inner menu-smart'.(is_rtl() ? ' sm-rtl' : '').' sm" role="menu"><li role="menuitem" id="menu-item-0">' . $logo_html . '</li></ul></div>';
							}

							$this->html .= apply_filters( 'uncode_menu_before_socials', false );
						if ($search !== '') {
							$this->html .=						'<div class="nav navbar-nav navbar-nav-last navbar-extra-icons">'.$search.'</div>';
						}

						if ($no_secondary !== 'on' && $secondary_enhanced !== 'on') {
							$this->html .=						'<div class="desktop-hidden menu-accordion-secondary">
														 							'.wp_nav_menu( array(
															 							"menu"              => $secondary_menu,
															 							"theme_location"    => "secondary",
															 							"container"         => "div",
																						"container_class"   => "menu-accordion menu-accordion-4",
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																						"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical mobile-secondary-menu",
															 							'fallback_cb'    => false,
															 							"walker"            => new wp_bootstrap_navwalker(),
															 							"echo"            => 0)
															 						) .
															 					'</div>';
						}
						$this->html .=						'</div>
																		</div>
																	</div>
																</div>
															</div>'
															. $search_dropdown .
														'</div>
													</header>
												</div>';
					break;

				case 'offcanvas_head':
					$primary_menu_out = wp_nav_menu( array(
						"menu"              => $primary_menu,
						"theme_location"    => "primary",
						"container"         => "div",
						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
						"container_class"   => "menu-accordion menu-accordion-9",
						"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical",
						"fallback_cb"       => false,
						"walker"            => new wp_bootstrap_navwalker(),
						"echo"            => 0)
					);
					$secondary_menu_out = wp_nav_menu( array(
						"menu"              => $secondary_menu,
						"theme_location"    => "secondary",
						"container"         => "div",
						"items_wrap"      => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
						"container_class"   => "menu-accordion menu-accordion-5",
						"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical",
						'fallback_cb'    => false,
						"walker"            => new wp_bootstrap_navwalker(),
						"echo"            => 0)
					);

					$this->html = '<div class="menu-wrapper'.$menu_shrink.$menu_sticky.'">
													<span class="menu-container-ghost-bg'.$stylemainbackfull.'"></span>
													<div id="masthead" class="navbar'.$stylemaincombo_overlay.$main_absolute.' menu-with-logo' . $menu_mobile_off_cavas . '">
														<div class="menu-container'.$effects.$stylemainbackfull.'" role="navigation">
															<div class="row-menu row-offcanvas'.$main_width.'">
																<div class="row-menu-inner row-brand menu-horizontal-inner'.$stylemainback.'">';

					if ($param == 'menu-overlay-center' && $search !== '') {
						$this->html .= 				'<div class="nav navbar-nav navbar-nav-first' . $social_to_append . '">
																		'.$search.'
																	</div>';
					}
					$this->html .= 					'<div id="logo-container-mobile" class="col-lg-0 logo-container middle">
																		<div id="main-logo" class="navbar-header style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																	</div>
																	<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="'.(($param == 'menu-overlay' || $param == 'menu-overlay-center') ? 'mobile-menu-button menu-button-overlay no-toggle' : 'mobile-menu-button menu-button-offcanvas').' '.$buttonstyle_primary.' lines-button trigger-overlay" '.(($param == 'menu-overlay' || $param == 'menu-overlay-center') ? 'data-area="menu" data-container="main-container"' : '').'>' . $burger_label_span . '<span class="lines"><span></span></span></div></div>';


																if ( $cta_menu ) {
																	$this->html .= '<div class="col-lg-12 main-menu-container cta-container middle' . $menu_sub_animation . $menu_no_arrow  . ' cta-with-icons-' . $icons_count . '">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">
																				'.wp_nav_menu( array(
																					"menu"              => $cta_menu,
																					"theme_location"    => "cta",
																					"container"         => "div",
																					"container_class"   => "nav navbar-nav navbar-cta",
																					'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																					"menu_class"        => "menu-cta-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																					"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																					"walker"            => new wp_bootstrap_navwalker(),
																					"echo"            => 0)
																				).
																			'</div>
																		</div>
																	</div><!-- .main-menu-container -->';
																}

														if ( $menutype !== 'menu-overlay' && $menutype !== 'menu-overlay-center' && $menutype !== 'vmenu-offcanvas' ) {
															$this->html .= apply_filters( 'uncode_menu_before_socials', false );
														}
														$this->html .= '</div>
															</div>'
															. $search_dropdown .
														'</div>
													</div>
												</div>';
					break;

				/**
				 * Overlay menu
				 * */
				case 'menu-overlay':
				case 'menu-overlay-center':

					$overlay_animation = ot_get_option( '_uncode_menu_overlay_animation');
					if ($overlay_animation === '' || $overlay_animation === '3d') {
						$overlay_animation = 'contentscale';
					}
					$primary_menu_out = wp_nav_menu( array(
						"menu"              => $primary_menu,
						"theme_location"    => "primary",
						"container"         => "div",
						"container_class"   => "menu-accordion menu-accordion-primary",
						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
						"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical",
						"fallback_cb"       => false,
						"walker"            => new wp_bootstrap_navwalker(),
						"echo"              => 0)
					);
					$secondary_menu_out = wp_nav_menu( array(
						"menu"              => $secondary_menu,
						"theme_location"    => "secondary",
						"container"         => "div",
						"items_wrap"        => '<ul id="%1$s" class="%2$s mobile-secondary-menu" role="menu">%3$s</ul>',
						"container_class"   => "menu-accordion menu-accordion-secondary",
						"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical",
						'fallback_cb'       => false,
						"walker"            => new wp_bootstrap_navwalker(),
						"echo"              => 0)
					);

					$limit_width = ot_get_option('_uncode_vmenu_align') === 'right' || ot_get_option('_uncode_vmenu_align') === 'left' ? ' limit-width' : '';

					if ( $primary_menu_out != '' || $search !== '' || ( $no_secondary !== 'on' && $secondary_menu_out != '' ) ) {
						$this->html =	'<div class="overlay overlay-'.$overlay_animation.' overlay-menu" data-area="menu" data-container="main-container">
														<div class="overlay-bg ' . $bgoverlay . '"></div>
														<div class="main-header">
															<div class="vmenu-container menu-container style-'.$styleoverlay.$menu_no_arrow.$stylemaincombo.'" data-lenis-prevent role="navigation">
																<div class="row row-parent">
																	<div class="row-inner">
																		<div class="menu-sidebar main-menu-container">
																			<div class="navbar-main">
																				<div class="menu-sidebar-inner' . $limit_width . '">
																					'.$primary_menu_out;

						$this->html .= apply_filters( 'uncode_menu_before_socials', false );

						if ($search !== '') {
							$this->html .= $search;
						}

						if ($no_secondary !== 'on' && $secondary_enhanced !== 'on') {
								$this->html .= $secondary_menu_out;
						}
							$this->html .= 						'</div>
																			</div>
													 					</div>
																	</div>
																</div>
															</div>
														</div>
													</div>';
						break;
					} else {
						break;
					}

				/**
				 * Overlay menu
				 * */
				case 'hmenu-center-double':
					$this->html = '<div class="menu-wrapper'.$menu_shrink.$menu_sticky.$menu_no_arrow.'">
													'.($no_secondary !== 'on' ? $secondary_menu_html : '').'
													<header id="masthead" class="navbar'.$stylemaincombo.$main_absolute.$menu_sub_animation.' menu-with-logo' . $menu_mobile_off_cavas . '">
														<div class="menu-container'.$effects.$stylemainbackfull.$needs_after.'" role="navigation">
															<div class="row-menu'.$main_width.'">
																<div class="row-menu-inner'.$stylemainback.'">
																	<div class="col-lg-5 main-menu-container middle">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">
																				'.wp_nav_menu( array(
																					"menu"              => $primary_menu,
																					"theme_location"    => "primary",
																					"container"         => "div",
																					'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																					"container_class"   => "nav navbar-nav navbar-main " . (($search !== '' || $type === 'hmenu-justify' || $cta_menu) ? 'navbar-nav-first' : 'navbar-nav-last') ,
																					"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																					"fallback_cb"       => false,
																					"walker"            => new wp_bootstrap_navwalker(),
																					"echo"            => 0)
																				);

						$this->html .=										'</div>
																		</div>
																	</div>
																	<div id="logo-container-mobile" class="col-lg-2 logo-container middle">
																		<div id="main-logo" class="navbar-header style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																		<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
																	</div>
																	<div class="col-lg-5 main-menu-container middle">
																		<div class="menu-horizontal' . $sub_extra_classes . '">
																			<div class="menu-horizontal-inner">
																				';

																				if ( $cta_menu ) {
																					$this->html .= wp_nav_menu( array(
																						"menu"              => $cta_menu,
																						"theme_location"    => "cta",
																						"container"         => "div",
																						"container_class"   => "nav navbar-nav navbar-cta" . ( ( $search === '' ) ? ' navbar-nav-last' : '' ),
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																						"menu_class"        => "menu-cta-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																						"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																						"walker"            => new wp_bootstrap_navwalker(),
																						"echo"            => 0)
																					);
																				}

																				$this->html .= apply_filters( 'uncode_menu_before_socials', false );

																				$this->html .= ( ($search !== '' || $type === 'hmenu-center-double' || ( $type === 'hmenu-justify' && !$cta_menu) ) ? '<div class="nav navbar-nav navbar-nav-last  navbar-extra-icons">'.$search.'</div>' : '');
						if ($no_secondary !== 'on' && $secondary_enhanced !== 'on') {
							$this->html .=										'<div class="desktop-hidden menu-accordion-secondary">
														 							'.wp_nav_menu( array(
															 							"menu"              => $secondary_menu,
															 							"theme_location"    => "secondary",
															 							"container"         => "div",
																						"container_class"   => "menu-accordion menu-accordion-7",
																						'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																						"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical mobile-secondary-menu",
															 							'fallback_cb'    => false,
															 							"walker"            => new wp_bootstrap_navwalker(),
															 							"echo"            => 0)
															 						) .
															 					'</div>';
						}
						$this->html .=										'</div>
																		</div>
																	</div>
																</div>
															</div>'
															. $search_dropdown .
														'</div>
													</header>
												</div>';
					break;

				/**
				 * Vertical menus
				 * */
				default:
					$footer_copyright = ot_get_option('_uncode_footer_copyright');
					$footer_text_content = '';
					if ($footer_copyright !== 'off' && ot_get_option('_uncode_copy_hide') !== 'on') {
						$footer_text_content .= '<p>&copy; '.date("Y").' '.get_bloginfo('name') . '. <span style="white-space:nowrap;">' . esc_html__('All rights reserved','uncode') . '</span></p>';
					} else {
						$footer_text = ot_get_option('_uncode_footer_text');
						if ($footer_text !== '') {
							$footer_text_content .= uncode_remove_p_tag($footer_text);
						}
					}
					$this->html = '<div class="main-header">
													<div id="masthead" class="masthead-vertical'.$menu_sticky.'">
														<div class="vmenu-container menu-container '.str_replace(' menu-transparent', '', $stylemaincombo).$stylemainbackfull.$menu_no_arrow.$menu_hide.'" data-lenis-prevent role="navigation">
															<div class="row row-parent'.$stylemainback.'">';
					$offcanvas_overlay = ot_get_option('_uncode_offcanvas_overlay');
					if ($menutype === 'vmenu-offcanvas' && $offcanvas_overlay === 'on' ) {
						$this->html .= '<div class="uncode-close-offcanvas-overlay lines-button close"><span class="lines"></span></div>';
					}
					if ($menutype !== 'vmenu-offcanvas') {
						$this->html .= 			'<div class="row-inner restrict row-brand">
																	<div id="logo-container-mobile" class="col-lg-12 logo-container">
																		<div class="style-'.$stylemain.'">
																			'.$logoDiv.'
																		</div>
																		<div class="mmb-container"><div class="mobile-additional-icons">'.$search_icon_mobile.$login_account_icon_mobile.$woo_wishlist_icon_mobile.$woo_icon_mobile.'</div>' . apply_filters( 'uncode_mobile_extra_menu_elements', false) . '<div class="mobile-menu-button '.$buttonstyle_primary.' lines-button"><span class="lines"><span></span></span></div></div>
																	</div>
																</div>';
					}

					$this->html .= 				'<div class="row-inner expand">
																	<div class="main-menu-container">
																		<div class="vmenu-row-wrapper">
																			<div class="vmenu-wrap-cell">
																				<div class="row-inner expand">
																					<div class="menu-sidebar navbar-main">
																						<div class="menu-sidebar-inner">
																							'.wp_nav_menu( array(
																	 							"menu"              => $primary_menu,
																	 							"theme_location"    => "primary",
																	 							"container"         => "div",
																								"container_class"   => "menu-accordion menu-accordion-primary",
																								'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																	 							"menu_class"        => "menu-primary-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm sm-vertical",
																	 							"fallback_cb"       => false,
																	 							"walker"            => new wp_bootstrap_navwalker(),
																	 							"echo"            => 0)
																	 						);
																							if ( $cta_menu && $type !== 'vmenu-offcanvas' ) {
																								$this->html .= wp_nav_menu( array(
																									"menu"              => $cta_menu,
																									"theme_location"    => "cta",
																									"container"         => "div",
																									"container_class"   => "menu-accordion navbar-accordion-cta",
																									"menu_class"        => "menu-cta-inner menu-smart".(is_rtl() ? ' sm-rtl' : '')." sm",
																									'items_wrap'        => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
																									"fallback_cb"       => "wp_bootstrap_navwalker::fallback",
																									"walker"            => new wp_bootstrap_navwalker(),
																									"echo"            => 0)
																								);
																							}
																 				$this->html .= '</div>
															 						</div>
															 					</div>';
						if ($no_secondary !== 'on' || $social_html !== '' || $search !== '' || $footer_text_content !== '') {

							$secondary_menu_html = $search;

							if ( ( $no_secondary !== 'on' && $secondary_enhanced !== 'on' ) || ( $secondary_enhanced === 'on' && $menu_secondary_show !== '' ) ) {
								$secondary_menu_nav = wp_nav_menu( array(
														 							"menu"              => $secondary_menu,
														 							"theme_location"    => "secondary",
														 							"container"         => "div",
														 							"items_wrap"      	=> '<ul id="%1$s" class="%2$s mobile-secondary-menu" role="menu">%3$s</ul>',
																					"container_class"   => "menu-accordion menu-accordion-secondary",
														 							"menu_class"        => "menu-smart".(is_rtl() ? ' sm-rtl' : '').($social_html !== '' ? '' : '')." sm sm-vertical",
														 							'fallback_cb'    		=> false,
														 							"walker"            => new wp_bootstrap_navwalker(),
														 							"echo"            	=> 0)
														 						);
								if ($secondary_menu_nav !== '') {
									$secondary_menu_html .= $secondary_menu_nav;
								}
							}

							$this->html .= apply_filters( 'uncode_menu_before_socials', false );
							if ($social_html !== '') {
								$secondary_menu_html .= $social_html;
							}

							if ($footer_text_content !== '') {
								$secondary_menu_html .= '<div class="mobile-hidden tablet-hidden vmenu-footer style-'.$stylemain.'">' . $footer_text_content . '</div>';
							}

							if ($secondary_menu_html !== '') {
								$this->html .=				'<div id="secondary-menu-html" class="row-inner restrict">
														 						<div class="menu-sidebar">
														 							<div class="menu-sidebar-inner">
																						'.$secondary_menu_html.'
																					</div>
																				</div>
																			</div>';
							}

					}

					$this->html .= 					'
															 				</div>
														 				</div>
														 			</div>
																</div>
															</div>
														</div>
													</div>
												</div>';
				break;
			}
		}
	}
}

if ( isset( $overlay_search ) ) {
	$overlay_search = '';
}

?>
