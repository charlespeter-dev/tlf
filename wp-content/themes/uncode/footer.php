<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package uncode
 */

do_action( 'uncode_before_footer_output' );

if ( ! function_exists( 'uncode_get_current_post_type' ) || uncode_get_current_post_type() !== 'uncodeblock' ) {

	global $metabox_data, $is_redirect, $menutype, $is_footer;

	$limit_width = $limit_content_width = $footer_content = $footer_text_content = $footer_icons = $footer_full_width = '';
	$alignArray = array('left','right');
	$is_footer = true;

	$general_style = ot_get_option('_uncode_general_style');
	$boxed = ot_get_option( '_uncode_boxed');
	$vmenu_position = ot_get_option('_uncode_vmenu_position');

	$footer_last_style = ot_get_option( '_uncode_footer_last_style');
	$footer_last_bg = ot_get_option('_uncode_footer_bg_color');
	$footer_last_bg = ($footer_last_bg == '') ? ' style-'.$footer_last_style.'-bg' : ' style-'.$footer_last_bg.'-bg';

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

	/** Get page width info **/
	if (isset($metabox_data['_uncode_specific_footer_width'][0]) && $metabox_data['_uncode_specific_footer_width'][0] !== '') {
		if ($metabox_data['_uncode_specific_footer_width'][0] === 'full') {
			$footer_full_width = true;
		} else {
			$footer_full_width = false;
		}
	} else {
		$footer_generic_width = ot_get_option( '_uncode_'.$post_type.'_footer_width');
		if ($footer_generic_width !== '') {
			if ($footer_generic_width === 'full') {
				$footer_full_width = true;
			} else {
				$footer_full_width = false;
			}
		} else {
			$footer_full = ot_get_option( '_uncode_footer_full');
			$footer_full_width = ($footer_full !== 'on') ? false : true;
		}
	}
	if (!$footer_full_width) {
		$limit_content_width = ' limit-width';
	}

	if (isset($metabox_data['_uncode_specific_footer_block'][0]) && $metabox_data['_uncode_specific_footer_block'][0] !== '') {
		$footer_block = $metabox_data['_uncode_specific_footer_block'][0];
	} else {
		$footer_block = ot_get_option('_uncode_' . $post_type . '_footer_block');
		if ($footer_block === '' && $footer_block !== 'none') {
			$footer_block = ot_get_option('_uncode_footer_block');
		}
	}

	if (isset($footer_block) && !empty($footer_block) && $footer_block !== 'none') {
		$footer_block = apply_filters( 'wpml_object_id', $footer_block, 'post', true );
		$footer_block_post_content = get_post_field('post_content', $footer_block);

		// Check if we have a content block created with VC
		$has_vc_row = strpos( $footer_block_post_content, '[vc_row' ) !== false ? true : false;

		$footer_block_content = '';

		if ( ! $has_vc_row ) {
			$content_style        = uncode_gutenberg_content_block_skin_classes();
			$footer_block_content .= '<div class="footer-content-block row-container ' . esc_attr( $content_style ) . '">';

			// Add inner row
			$footer_block_content .= $footer_full_width ? '<div class="footer-content-block-inner full-width row-parent">' : '<div class="footer-content-block-inner limit-width row-parent">';
		}

		$footer_block_content .= $footer_block_post_content;

		if ($footer_full_width) {
			$footer_block_content = preg_replace('#\s(unlock_row)="([^"]+)"#', ' unlock_row="yes"', $footer_block_content);
			$footer_block_content = preg_replace('#\s(unlock_row_content)="([^"]+)"#', ' unlock_row_content="yes"', $footer_block_content);
			$footer_block_counter = substr_count($footer_block_content, 'unlock_row_content');
			if ($footer_block_counter === 0) {
				$footer_block_content = str_replace('[vc_row ', '[vc_row unlock_row="yes" unlock_row_content="yes" ', $footer_block_content);
			}
		} else {
			$footer_block_content = preg_replace('#\s(unlock_row)="([^"]+)"#', ' unlock_row="yes"', $footer_block_content);
			$footer_block_content = preg_replace('#\s(unlock_row_content)="([^"]+)"#', ' unlock_row_content="no"', $footer_block_content);
			$footer_block_counter = substr_count($footer_block_content, 'unlock_row_content');
			if ($footer_block_counter === 0) {
				$footer_block_content = str_replace('[vc_row ', '[vc_row unlock_row="yes" unlock_row_content="no" ', $footer_block_content);
			}
		}

		if ( ! $has_vc_row ) {
			// Close parent and inner row
			$footer_block_content .= '</div><!-- /.footer-content-block --></div><!-- /.footer-content-block-inner -->';
		}

		$footer_content .= uncode_remove_p_tag($footer_block_content);
	}

	$footer_position = ot_get_option('_uncode_footer_position');
	if ($footer_position === '') {
		$footer_position = 'left';
	}

	$footer_copyright = ot_get_option('_uncode_footer_copyright');
	if ($footer_copyright !== 'off') {
		$footer_text_content = '&copy; '.date("Y").' '.get_bloginfo('name') . '. ' . esc_html__('All rights reserved','uncode');
	}

	$footer_text = ot_get_option('_uncode_footer_text');
	if ($footer_text !== '' && $footer_copyright === 'off') {
		$footer_text_content = uncode_the_content($footer_text);
	}

	if ($footer_text_content !== '') {
		$footer_text_content = '<div class="site-info uncell col-lg-6 pos-middle text-'.$footer_position.'">'.$footer_text_content.'</div><!-- site info -->';
	}

	$footer_social = ot_get_option('_uncode_footer_social');
	if ($footer_social !== 'off') {
		$socials = ot_get_option( '_uncode_social_list','',false,true);
		if (isset($socials) && !empty($socials) && count($socials) > 0) {
			foreach ($socials as $social) {
				if ($social['_uncode_social'] === '') {
					continue;
				}
				$social_rel = apply_filters( 'uncode_social_link_rel', '' );
				$social_rel_html = $social_rel !== '' ? ' rel="' . esc_attr( $social_rel ) . '"' : '';
				$social_aria = isset($social['_uncode_aria']) && $social['_uncode_aria'] !== '' ? ' aria-label="' . wp_kses_post( $social['_uncode_aria'] ) . '"' : '';
				$footer_icons .= '<div class="social-icon icon-box icon-box-top icon-inline"><a href="'.esc_url($social['_uncode_link']).'" target="_blank"' . $social_rel_html . $social_aria . '><i class="'.esc_attr($social['_uncode_social']).'"></i></a></div>';
			}
		}
	}

	if ($footer_icons !== '') {
		$footer_icons = '<div class="uncell col-lg-6 pos-middle text-'.($footer_position === 'center' ? $footer_position : $alignArray[!array_search($footer_position, $alignArray)]).'">' . $footer_icons . '</div>';
	}

	$class_footer = 'site-footer';

	if (($footer_text_content !== '' || $footer_icons !== '')) {
		switch ($footer_position) {
			case 'left':
				$footer_text_content = $footer_text_content . $footer_icons;
				break;
			case 'center':
				$footer_last_bg .= ' footer-center';
				$footer_text_content = $footer_icons . $footer_text_content;
				break;
			case 'right':
				$footer_text_content = $footer_icons . $footer_text_content;
				break;
		}
		$footer_last_bg .= ' footer-last';
		if (strpos($menutype ,'vmenu') !== false) {
			$footer_last_bg .= ' desktop-hidden';
		}

		if ( ( isset($metabox_data['_uncode_specific_copy_hide'][0]) && $metabox_data['_uncode_specific_copy_hide'][0] !== '' && $metabox_data['_uncode_specific_copy_hide'][0] !== 'off' ) ) {
			$show_footer = ( $metabox_data['_uncode_specific_copy_hide'][0] !== 'on' );
		} else {
			$show_footer = ( ot_get_option('_uncode_copy_hide') !== 'on' );
		}
		if ( $show_footer ) {
			$footer_content .= uncode_get_row_template($footer_text_content, $limit_width, $limit_content_width, $footer_last_style, $footer_last_bg, false, false, false);
		}

		if ( $footer_block !== '' && $footer_block !== 'none' && function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
			$cb_edit_link = vc_frontend_editor()->getInlineUrl( '', $footer_block );
			$footer_content .= '<div class="vc_controls-element vc_controls vc_controls-content_block"><div
				class="vc_controls-cc"><a
					class="vc_control-btn vc_element-name vc_control-btn-edit" data-control="edit" href="' . esc_url( $cb_edit_link ) . '" target="_blank" title="' . esc_html__( 'Edit Content Block', 'uncode' ) . '"><span class="vc_btn-content">' . esc_html__( 'Footer Content Block', 'uncode' ) . '<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span></span></a></div></div>';
			$class_footer .= ' has_content_block';

		}

	} ?>
								</div><!-- sections container -->
							</div><!-- page wrapper -->
						<?php
						$footer_content = apply_filters( 'uncode_filter_for_translation', $footer_content );
						if ( $is_redirect !== true && $footer_content!=='' )  : ?>
						<footer id="colophon" class="<?php echo esc_attr( $class_footer ); ?>" role="contentinfo">
							<?php
								echo uncode_switch_stock_string( $footer_content );

								$is_footer = false;
							?>
						</footer>
						<?php endif; ?>
						<?php do_action( 'uncode_after_page_footer' ); ?>
					</div><!-- main container -->
				</div><!-- main wrapper -->
				<?php
				$remove_menu = (isset($metabox_data['_uncode_specific_menu_remove'][0]) && $metabox_data['_uncode_specific_menu_remove'][0] === 'on') ? true : false;
				if ( ! $remove_menu ) {
					if ($is_redirect !== true && $menutype === 'vmenu' && ( ( $vmenu_position === 'right' && !is_rtl() ) || ( $vmenu_position !== 'right' && is_rtl() ) ) ) {
						$mainmenu = new unmenu($menutype, $menutype);
						echo uncode_remove_p_tag( $mainmenu->html );
					}
				}
				?>
			</div><!-- box container -->
			<?php if (strpos($menutype ,'vmenu') !== false) { ?>
				<script type="text/javascript" id="verticalRightMenu">UNCODE.verticalRightMenu();</script>
			<?php } ?>
		</div><!-- box wrapper -->
		<?php
		$footer_uparrow = ot_get_option('_uncode_footer_uparrow');
		if (isset($metabox_data['_uncode_specific_footer_uparrow_hide'][0]) && $metabox_data['_uncode_specific_footer_uparrow_hide'][0] === 'on') {
			$footer_uparrow = 'off';
		}
		if (wp_is_mobile()) {
			$footer_uparrow_mobile = ot_get_option('_uncode_footer_uparrow_mobile');
			if ($footer_uparrow_mobile === 'off') {
				$footer_uparrow = 'off';
			}
		}
		if ($footer_uparrow !== 'off' && ( ! function_exists('vc_is_page_editable') || ! vc_is_page_editable() ) ) {
			$scroll_higher = '';
			if (strpos($menutype ,'vmenu') === false) {
				if ($limit_content_width === '') {
					$scroll_higher = ' footer-scroll-higher';
				}
			}
			$footer_uparrow_class = ot_get_option('_uncode_footer_uparrow_style') === 'circle' ? ' footer-scroll-circle' : '';
			echo '<div class="style-light footer-scroll-top'.$scroll_higher.$footer_uparrow_class.'"><a href="#" class="scroll-top" aria-label="' . esc_html__('Scroll to top','uncode') . '"><i class="fa fa-angle-up fa-stack btn-default btn-hover-nobg"></i></a></div>';
		}
		//$vertical = (strpos($menutype, 'vmenu') !== false || $menutype === 'menu-overlay') ? true : false;
		//if (!$vertical) {

			$search_animation = ot_get_option('_uncode_menu_search_animation');
			if ($search_animation === '' || $search_animation === '3d') {
				$search_animation = 'contentscale';
			}
			$search_dropdown = ot_get_option('_uncode_drop_down_search');

		?>

		<?php if ( apply_filters( 'uncode_search_active', ot_get_option( '_uncode_menu_search') ) === 'on' ) : ?>
			<div class="overlay overlay-<?php echo esc_attr( $search_animation ); ?> overlay-full style-dark style-dark-bg overlay-search" data-area="search" data-container="box-container">
				<div class="mmb-container"><div class="menu-close-search mobile-menu-button menu-button-offcanvas mobile-menu-button-dark lines-button overlay-close close" data-area="search" data-container="box-container"><span class="lines"></span></div></div>
				<div class="search-container"><?php
					global $overlay_search;
					$overlay_search = 'yes';

					if ( ot_get_option('_uncode_menu_search_type' ) === 'products' ) {
						add_filter( 'uncode_product_search_type', '__return_true' );
					}

					get_search_form( true );
					$overlay_search = '';
				?></div>
			</div>
		<?php endif; ?>

	<?php //}

}

wp_footer(); ?>
</body>
</html>
