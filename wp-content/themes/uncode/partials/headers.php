<?php

/*****************
 *
 *   HEADER BUILDER
 *
 ******************/

if (!class_exists('unheader')) {
	class unheader
	{

		public $html, $poster_id, $show_title;

		function __construct($args, $page_title = '', $subheading = '')
		{

			global $onepage, $metabox_data;

			$poster_id = false;

			$limit_width = $limit_content_width = $content_html = $content_width = $header_title = $header_title_custom = $header_title_font = $header_title_size = $header_title_height = $header_title_spacing = $header_title_weight = $header_title_italic = $header_title_transform = $header_background = $header_container_background = $header_title = $header_parallax = $header_kburns = $header_parallax_class = $height_style = $data_height = $header_background_video = $back_mime = $back_mime_css = $header_background_selfvideo = $header_overlay_color = $header_overlay_color_alpha = $header_overlay_pattern = $header_container_overlay_color = $header_container_overlay_color_alpha = $header_container_overlay_pattern = $header_align = $header_scroll_opacity = $header_scrolldown = $header_no_padding = $header_no_padding_mobile = '';

			$get_post_type = get_post_type();

			if (isset($args['_uncode_header_full_width'][0]) && $args['_uncode_header_full_width'][0] !== '') {
				if ($args['_uncode_header_full_width'][0] === 'limit') {
					$limit_width = ' limit-width';
				}
			} else {
				$header_width = ot_get_option( '_uncode_header_full');
				if ($header_width === 'off') {
					$limit_width = ' limit-width';
				}
			}
			if (isset($args['_uncode_header_content_width'][0]) && $args['_uncode_header_content_width'][0] !== 'on') {
				$limit_content_width = ' limit-width';
			}

			if (isset($args['_uncode_header_custom_width'][0])) {
				if ($args['_uncode_header_custom_width'][0] != '100') {
					$content_width = ' style="max-width: ' . $args['_uncode_header_custom_width'][0] . '%;"';
				}
			}

			if ($onepage) {
				if (isset($args['_uncode_scroll_header_name'][0]) && $args['_uncode_scroll_header_name'][0] !== '') {
					$onepage_header_name = esc_attr($args['_uncode_scroll_header_name'][0]);
					$onepage_header_name = ' data-label="'. $onepage_header_name .'" data-name="'.  sanitize_title($onepage_header_name).'"';
				}
				else {
					$onepage_header_name = '';
				}
			} else {
				$onepage_header_name = '';
			}

			/** style **/
			if (isset($args['_uncode_header_height'][0]) && $args['_uncode_header_height'][0] !== '') {
				$height = $args['_uncode_header_height'][0];
				if ($height[1] == '%') {
					$data_height = ' data-height="' . $height[0] . '"';
				}
				if ($height[1] == 'px') {
					$data_height = ' data-height="fixed"';
					$height_style .= 'height: ' . $height[0] . $height[1] . ';';
				}
			}

			if (isset($args['_uncode_header_min_height'][0]) && $args['_uncode_header_min_height'][0] !== '') {
				$min_height = intval(preg_replace('/[^0-9]+/', '', $args['_uncode_header_min_height'][0]), 10);
				$height_style .= 'min-height: ' . $min_height . 'px;';
			}

			if ($height_style !== '') {
				$height_style = ' style="'.$height_style.'"';
			}

			$header_style = (isset($args['_uncode_header_style'])) ? $args['_uncode_header_style'][0] : 'light';
			$header_align = (isset($args['_uncode_header_align'])) ? $args['_uncode_header_align'][0] : 'center';
			if (isset($args['_uncode_header_title'])) {
				$header_title = $args['_uncode_header_title'][0];
			}
			if (isset($args['_uncode_header_title_custom'])) {
				$header_title_custom = $args['_uncode_header_title_custom'][0];
			}
			if (isset($args['_uncode_header_title_font'])) {
				$header_title_font = $args['_uncode_header_title_font'][0];
			}
			if (isset($args['_uncode_header_title_size'])) {
				$header_title_size = $args['_uncode_header_title_size'][0];
			}
			if (isset($args['_uncode_header_title_height'])) {
				$header_title_height = $args['_uncode_header_title_height'][0];
			}
			if (isset($args['_uncode_header_title_spacing'])) {
				$header_title_spacing = $args['_uncode_header_title_spacing'][0];
			}
			if (isset($args['_uncode_header_title_weight'])) {
				$header_title_weight = $args['_uncode_header_title_weight'][0];
			}
			if (isset($args['_uncode_header_title_italic'])) {
				$header_title_italic = $args['_uncode_header_title_italic'][0];
			}
			if (isset($args['_uncode_header_title_transform'])) {
				$header_title_transform = $args['_uncode_header_title_transform'][0];
			}
			if (isset($args['_uncode_header_overlay_pattern'])) {
				$header_overlay_pattern = $args['_uncode_header_overlay_pattern'][0];
			}
			if (isset($args['_uncode_header_background'])) {
				$header_background = $args['_uncode_header_background'][0];
			}
			if (isset($args['_uncode_header_overlay_color'])) {
				$header_overlay_color = $args['_uncode_header_overlay_color'][0];
			}
			if (isset($args['_uncode_header_overlay_color_alpha'])) {
				$header_overlay_color_alpha = $args['_uncode_header_overlay_color_alpha'][0];
			}
			if (isset($args['_uncode_header_scroll_opacity'])) {
				$header_scroll_opacity = $args['_uncode_header_scroll_opacity'][0];
			}
			if (isset($args['_uncode_header_scrolldown'])) {
				$header_scrolldown = $args['_uncode_header_scrolldown'][0];
			}
			if (isset($args['_uncode_menu_no_padding']) && $args['_uncode_menu_no_padding'][0] !== '' ) {
				$header_no_padding = $args['_uncode_menu_no_padding'][0];
			} else {
				$header_no_padding = ot_get_option('_uncode_'.$get_post_type.'_menu_no_padding');
			}
			if (isset($args['_uncode_menu_no_padding_mobile']) && $args['_uncode_menu_no_padding_mobile'][0] !== '' ) {
				$header_no_padding_mobile = $args['_uncode_menu_no_padding_mobile'][0];
			} else {
				$header_no_padding_mobile = ot_get_option('_uncode_'.$get_post_type.'_menu_no_padding_mobile');
			}

			$item_style = ' style-' . $header_style;

			if ($header_scrolldown === 'on') {
				$header_scroll_html = '<div class="header-scrolldown'.$item_style.'"><i class="fa fa-angle-down"></i></div>';
			} else {
				$header_scroll_html = '';
			}

			if ($header_no_padding === 'on') {
				$header_no_padding = 'remove-menu-padding ';
			} else {
				$header_no_padding = '';
			}

			if ($header_no_padding_mobile === 'on') {
				$header_no_padding .= 'remove-menu-padding-mobile ';
			}

			$this->html = '';

			$header_type = (isset($args['_uncode_header_type'][0])) ? $args['_uncode_header_type'][0] : 'none';

			switch ($header_type) {

				case 'header_basic':

					if ( get_option( 'uncode_core_settings_opt_disable_basic_header' ) === 'on' ) {
						return;
					}

					$div_data = array();
					$title_classes = array();
					$data_size = '';

					$header_parallax = (isset($args['_uncode_header_parallax'][0]) && $args['_uncode_header_parallax'][0] == 'on') ? ' header-parallax' : '';
					$header_kburns = '';
					if ( isset($args['_uncode_header_kburns'][0]) ) {
						if ( $args['_uncode_header_kburns'][0] == 'on' ) {
							$header_kburns = ' with-kburns';
						} elseif ( $args['_uncode_header_kburns'][0] == 'zoom' ) {
							$header_kburns = ' with-zoomout';
						} elseif ( $args['_uncode_header_kburns'][0] == 'magnetic' ) {
							$header_kburns = ' magnetic';
						}
					}
					$header_position = (isset($args['_uncode_header_position'][0])) ? ' ' . $args['_uncode_header_position'][0] : '';

					// If a custom header is set on the customizer, use that one
					if ( function_exists( 'get_header_image' ) && get_header_image() ) {
						$customizer_header = get_custom_header();

						if ( ! empty( $customizer_header->attachment_id ) ) {
							$header_background[ 'background-image' ] = $customizer_header->attachment_id;
						}
					}

					$header_background_array = uncode_get_back_html($header_background, $header_overlay_color, $header_overlay_color_alpha, $header_overlay_pattern, 'header');

					$this->poster_id = $header_background_array['poster_id'];
					$text_animation = (isset($args['_uncode_header_text_animation'][0]) && $args['_uncode_header_text_animation'][0] != '') ? ' blocks-animation ' . $args['_uncode_header_text_animation'][0] : '';
					if (isset($args['_uncode_header_animation_speed'][0]) && $args['_uncode_header_animation_speed'][0] != '') {
						$div_data['data-speed'] = $args['_uncode_header_animation_speed'][0];
					}
					if (isset($args['_uncode_header_animation_delay'][0]) && $args['_uncode_header_animation_delay'][0] != '') {
						$div_data['data-delay'] = $args['_uncode_header_animation_delay'][0];
					}

					if ($header_title_font !== '') {
						$title_classes[] = $header_title_font;
					}
					if ($header_title_size !== '') {
						$title_classes[] = $header_title_size;
					}
					if ($header_title_height !== '') {
						$title_classes[] = $header_title_height;
					}
					if ($header_title_spacing !== '') {
						$title_classes[] = $header_title_spacing;
					}
					if ($header_title_weight !== '') {
						$title_classes[] = 'font-weight-' . $header_title_weight;
					}
					if ($header_title_transform !== '') {
						$title_classes[] = 'text-' . $header_title_transform;
					}

					$page_title = ($header_title_italic === 'on') ? $page_title = '<i>' . $page_title . '</i>' : $page_title;

					if ($header_background_array['content_html'] === '') {
						if ($header_title !== 'off') {

							$content_html .= apply_filters( 'uncode_before_header_title', '' );

							if ($header_title_custom !== 'on') {
								$content_html .= '<h1 class="header-title '.implode(' ', $title_classes).'"'.$data_size.'><span>' . $page_title . '</span></h1>';
							} else {
								if (isset($args['_uncode_header_text'][0])) {
									$content = '<h1 class="header-title '.implode(' ', $title_classes).'"'.$data_size.'><span>';
									$title = trim($args['_uncode_header_text'][0]);
									$title_lines = explode("\n", $title);
									$lines_counter = count($title_lines);
									if ($lines_counter > 1) {
										foreach ($title_lines as $key => $value) {
											$value = trim($value);
											$content .= $value;
											if ($value !== '' && ($lines_counter - 1 !== $key)) {
												$content .= '</span><span>';
											}
										}
									} else {
										$content .= $title;
									}
									$content .= '</span></h1>';
									$content_html .= do_shortcode($content);
								}
							}

							if ($get_post_type === 'post' && is_single()) {
								$content_html .= uncode_post_info();
							}
							if ($get_post_type === 'portfolio' && is_single()) {
								$content_html .= uncode_portfolio_info();
							}

							$content_html .= apply_filters( 'uncode_after_header_title', '' );

						}

					} else {
						$this->show_title = 'yes';
						$content_html .= $header_background_array['content_html'];
					}

					if ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
						if (isset($metabox_data['_uncode_header_type'][0]) && $metabox_data['_uncode_header_type'][0] !== '') {
							$edit_header_options_str = esc_html__( 'Edit Page Options', 'uncode' );
							$edit_header_options_url =  esc_url( get_edit_post_link() );
						} else {
							$edit_header_options_str = esc_html__( 'Edit Theme Options', 'uncode' );
							$edit_header_options_url =  esc_url(admin_url( 'admin.php?page=uncode-options' ));
						}
						$content_html .= '<div class="vc_controls-element vc_controls vc_controls-content_block"><div
					class="vc_controls-cc"><a
						class="vc_control-btn vc_element-name vc_control-btn-edit" data-control="edit" href="' . $edit_header_options_url . '" target="_blank" title="' . $edit_header_options_str . '"><span class="vc_btn-content">' . $edit_header_options_str . '<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span></span></a></div></div>';
					}

					$this->html.= '<div class="'.$header_no_padding . 'header-basic' . $limit_width . $item_style . '">
													<div class="background-element header-wrapper'.($header_scroll_opacity === 'on' ? ' header-scroll-opacity' : '') . (($onepage) ? ' onepage-section' : '') . $header_parallax . $header_kburns . $header_background_array['back_color'] . (($header_background_array['content_html'] === '' || $header_background_array['content_only_text']) ? ' header-only-text' : '') .'"' . $onepage_header_name . $data_height . $height_style. '>
													' . (isset($header_background_array['back_html']) ? $header_background_array['back_html'] : '');

					$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

					if ($content_html !== '') {
						$this->html .=	'<div class="header-main-container'.$limit_content_width . ($header_background_array['is_carousel'] ? ' header-carousel' :'' ). '">
															<div class="header-content' . $header_position . ' header-align-' . $header_align . '">
																<div class="header-content-inner' . $text_animation . '"' . $content_width . ' '.implode(' ', $div_data_attributes).'>
																	'.$content_html.'
																</div>
															</div>
														</div>';
					}
					$this->html.= $header_scroll_html .
													'</div>
												</div>';

				break;

			case 'header_uncodeblock':
			case 'first_row':

				if ( $header_type === 'first_row' && function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
					break;
				}

				if ( $header_type === 'header_uncodeblock' ) {
					global $is_cb, $is_header_cb;
					$old_cb = $is_cb;
					$is_cb = $is_header_cb = true;
				}

				$this->html .= '<div class="'.$header_no_padding . 'header-wrapper header-uncode-block'. ($header_scroll_opacity === 'on' ? ' header-scroll-opacity' : '') . '">
									' . (isset($header_background_array['back_html']) ? $header_background_array['back_html'] : '');

				if ( $header_type === 'header_uncodeblock' ) {

					$uncodeblock_id = (isset($args['_uncode_blocks_list']) && $args['_uncode_blocks_list'][0] !== '') ? $args['_uncode_blocks_list'][0] : '';
					if ($uncodeblock_id !== '') {
						$uncodeblock_id = apply_filters( 'wpml_object_id', $uncodeblock_id, 'post' );
					}

					$uncode_block_content = ($uncodeblock_id !== '') ? get_post_field('post_content', $uncodeblock_id) : '';
				} else {
					$uncode_block_content = uncode_get_header_from_content();
				}

				$uncode_block = '';

				// Check if we have a content block created with VC
				$has_vc_row = strpos( $uncode_block_content, '[vc_row' ) !== false ? true : false;

				if ( ! $has_vc_row ) {
					$content_style = uncode_gutenberg_content_block_skin_classes();

					$uncode_block .= '<div class="header-content-block row-container ' . esc_attr( $content_style ) . '"><div class="header-content-block-inner limit-width row row-parent">';
				}

				$uncode_block .= $uncode_block_content;

				$uncode_block = str_replace('[vc_row ', '[vc_row is_header="yes" ', $uncode_block);
				$uncode_block = str_replace('[uncode_slider', '[uncode_slider is_header="yes"', $uncode_block);
				if ($subheading !== '' ) {
					$_subheading = ' subheading="'.$subheading.'"';
				} else {
					$_subheading = '';
				}

				$regex = '/\[vc_custom_heading(.*?)\](.*?)\[\/vc_custom_heading\]/';
				preg_match_all($regex, $uncode_block, $matches, PREG_PATTERN_ORDER);

				foreach ($matches as $key => $headings) {
					$regex_attr = '/ auto_text=\"yes\"/';
					$regex_attr_2 = '/ auto_text=\"excerpt\"/';
					foreach ($headings as $key => $heading) {
						preg_match_all($regex, $heading, $h_matches, PREG_SET_ORDER);
						foreach ($h_matches as $key2 => $value) {
							if ($subheading !== '' ) {
								$_subheading = ' subheading="'.$subheading.'"';
								if ( strpos( $value[0], ' subheading=' ) === false ) {
									$_subheading = '';
								}
							}
							preg_match($regex_attr, $value[1], $matches_attr);
							if (isset($matches_attr[0]) && $matches_attr[0]!=='') {
								$value[1] = preg_replace('/ subheading=\"(.*?)\"/', $_subheading, $value[1]);
								$replacement = '[vc_custom_heading is_header="yes" ' . $value[1] . ']' . $page_title . '[/vc_custom_heading]';
								$uncode_block = str_replace($value[0], $replacement, $uncode_block);
							}
							preg_match($regex_attr_2, $value[1], $matches_attr);
							if (isset($matches_attr[0]) && $matches_attr[0]!=='') {
								$replacement = '[vc_custom_heading is_header="yes" ' . $value[1] . ']' . $subheading . '[/vc_custom_heading]';
								$uncode_block = str_replace($value[0], $replacement, $uncode_block);
							}
						}
					}
				}

				$featured_id = isset($header_background['background-image']) ? $header_background['background-image'] : '';
				if ( $featured_id !== '' && isset($is_cb) && $is_cb ) {
					// Featured image in row
					$regex = '/\[vc_row(.*?)\]/';
					$regex_attr = '/(.*?)=\"(.*?)\"/';
					preg_match_all($regex, $uncode_block, $matches, PREG_SET_ORDER);
					foreach ($matches as $key => $value) {
						$media_found = $secondary_media = false;
						if (isset($value[1])) {
							preg_match_all($regex_attr, trim($value[1]), $matches_attr, PREG_SET_ORDER);
							foreach ($matches_attr as $key_attr => $value_attr) {
								if (trim($value_attr[1]) === 'back_image_auto') {
									if ($value_attr[2] === 'yes') {
										$media_found = true;
									}
								}
								if (trim($value_attr[1]) === 'back_image_option') {
									if ($value_attr[2] === 'secondary') {
										$secondary_media = true;
									}
								}
							}
						}
						if ($media_found) {
							$vc_row_filtered = preg_replace('/ back_image=\"(.*?)\"/', '', $value[1]);
							if (  is_singular() ) {
								$featured_id = apply_filters( 'uncode_featured_image_id', $featured_id, get_the_id() );
								if ( $secondary_media ) {
									$featured_id = uncode_get_secondary_featured_thumbnail_id(get_the_id());
								}
							} elseif ( is_tax() ) {
								$term_id = get_queried_object_id();
								$featured_id = apply_filters( 'uncode_featured_image_id', $featured_id, $term_id );
								if ( $secondary_media ) {
									$featured_id = uncode_get_term_featured_thumbnail_id($term_id, true);
								}
							}
							if ( $featured_id ) {
								$replacement = '[vc_row' . $vc_row_filtered . ' back_image="'.$featured_id.'" featured_image="yes"]';
								$uncode_block = str_replace($value[0], $replacement, $uncode_block);
							}
						}
					}

					// Featured image in column
					$regex = '/\[vc_column(.*?)\]/';
					$regex_attr = '/(.*?)=\"(.*?)\"/';
					preg_match_all($regex, $uncode_block, $matches, PREG_SET_ORDER);
					foreach ($matches as $key => $value) {
						$media_found = false;
						if (isset($value[1])) {
							preg_match_all($regex_attr, trim($value[1]), $matches_attr, PREG_SET_ORDER);
							foreach ($matches_attr as $key_attr => $value_attr) {
								if (trim($value_attr[1]) === 'back_image_auto') {
									if ($value_attr[2] === 'yes') {
										$media_found = true;
									}
								}
								if (trim($value_attr[1]) === 'back_image_option') {
									if ($value_attr[2] === 'secondary') {
										$secondary_media = true;
									}
								}
							}
						}
						if ($media_found) {
							if (  is_singular() ) {
								$featured_id = apply_filters( 'uncode_featured_image_id', $featured_id, get_the_id() );
								if ( $secondary_media ) {
									$featured_id = uncode_get_secondary_featured_thumbnail_id(get_the_id());
								}
							} elseif ( is_tax() ) {
								$term_id = get_queried_object_id();
								$featured_id = apply_filters( 'uncode_featured_image_id', $featured_id, $term_id );
								if ( $secondary_media ) {
									$featured_id = uncode_get_term_featured_thumbnail_id($term_id, true);
								}
							}
							if ( $featured_id ) {
								$replacement = '[vc_column' . $value[1] . ' back_image="'.$featured_id.'" featured_image="yes"]';
								$uncode_block = str_replace($value[0], $replacement, $uncode_block);
							}
						}
					}
				}

				if ( isset($uncodeblock_id) && $uncodeblock_id !== '' && $uncodeblock_id !== 'none' && function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
					$cb_edit_link = vc_frontend_editor()->getInlineUrl( '', $uncodeblock_id );
					$uncode_block .= '<div class="vc_controls-element vc_controls vc_controls-content_block"><div
			class="vc_controls-cc"><a
				class="vc_control-btn vc_element-name vc_control-btn-edit" data-control="edit" href="' . esc_url( $cb_edit_link ) . '" target="_blank" title="' . esc_html__( 'Edit Content Block', 'uncode' ) . '"><span class="vc_btn-content">' . esc_html__( 'Header Content Block', 'uncode' ) . '<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span></span></a></div></div>';
				}

				if ( ! $has_vc_row ) {
					// Close parent and inner row
					$uncode_block .= '</div><!-- /.header-content-block --></div><!-- /.header-content-block-inner -->';
				}

				$this->html.= $uncode_block;
				$this->html.= $header_scroll_html;
				$this->html.= '</div>';
				if ( isset($old_cb) ) {
					$is_cb = $old_cb;
				}

			break;

			case 'header_revslider':

				$this->html.= '<div class="' . $header_no_padding . 'header-wrapper header-revslider">
									' . (isset($header_background_array['back_html']) ? $header_background_array['back_html'] : '') . '
									<div class="header-main-container">';

				$revslider_id = (isset($args['_uncode_revslider_list']) && $args['_uncode_revslider_list'][0] != '') ? $args['_uncode_revslider_list'][0] : '';
				if ( $revslider_id !== '' && $revslider_id !== 'none' && function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
					$rs_edit_link = admin_url( 'admin.php?page=revslider&view=slider&id=' . $revslider_id );
					$this->html.= '<div class="vc_controls-element vc_controls vc_controls-content_block"><div
			class="vc_controls-cc"><a
				class="vc_control-btn vc_element-name vc_control-btn-edit" data-control="edit" href="' . esc_url( $rs_edit_link ) . '" target="_blank" title="' . esc_html__( 'Edit Slider', 'uncode' ) . '"><span class="vc_btn-content">' . esc_html__( 'Header Slider', 'uncode' ) . '<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span></span></a></div></div>';
				}

				$this->html.= do_shortcode('[rev_slider ' . $revslider_id . ']');
				$this->html = apply_filters( 'uncode_filter_for_translation', $this->html );

				$this->html.= 		'</div>';
				$this->html.= '</div>';

			break;

			case 'header_layerslider':

				$this->html.= '<div class="' . $header_no_padding . 'header-wrapper header-layerslider">
									' . (isset($header_background_array['back_html']) ? $header_background_array['back_html'] : '') . '
									<div class="header-main-container">';

				$layerslider_id = (isset($args['_uncode_layerslider_list']) && $args['_uncode_layerslider_list'][0] != '') ? $args['_uncode_layerslider_list'][0] : '';
				if ( $layerslider_id !== '' && $layerslider_id !== 'none' && function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
					$ls_edit_link = admin_url( 'admin.php?page=layerslider&action=edit&id=' . $layerslider_id );
					$this->html.= '<div class="vc_controls-element vc_controls vc_controls-content_block"><div
			class="vc_controls-cc"><a
				class="vc_control-btn vc_element-name vc_control-btn-edit" data-control="edit" href="' . esc_url( $ls_edit_link ) . '" target="_blank" title="' . esc_html__( 'Edit Slider', 'uncode' ) . '"><span class="vc_btn-content">' . esc_html__( 'Header Slider', 'uncode' ) . '<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span></span></a></div></div>';
				}
				$this->html.= do_shortcode('[layerslider id="' . $layerslider_id . '"]');
				$this->html = apply_filters( 'uncode_filter_for_translation', $this->html );

				$this->html.= 		'</div>';
				$this->html.= '</div>';

				break;
			}
		}
	}
}
?>
