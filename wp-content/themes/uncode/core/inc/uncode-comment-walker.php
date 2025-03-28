<?php

	/** COMMENTS WALKER */
if ( ! class_exists( 'uncode_walker_comment' ) ) :
	class uncode_walker_comment extends Walker_Comment {
		var $tree_type = 'comment';
		var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

		// constructor – wrapper for the comments list
		function __construct() { ?>

			<section class="comments-list">

		<?php }

		// start_lvl – wrapper for child comments list
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 2; ?>

			<section class="child-comments comments-list">

		<?php }

		// end_lvl – closing wrapper for child comments list
		function end_lvl( &$output, $depth = 0, $args = array() ) {
			$GLOBALS['comment_depth'] = $depth + 2; ?>

			</section>

		<?php }

		// start_el – HTML for comment template
		function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
			$depth++;
			$GLOBALS['comment_depth'] = $depth;
			$GLOBALS['comment'] = $comment;
			$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );

			if ( 'article' == $args['style'] ) {
				$tag = 'article';
				$add_below = 'comment';
			} else {
				$tag = 'article';
				$add_below = 'comment';
			} ?>

			<article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>">
				<div class="comment-content post-content">
					<figure class="gravatar"><?php echo get_avatar( $comment, 256, '', '', array( 'loading' => 'lazy' ) ); ?></figure>
					<div class="comment-meta post-meta" role="complementary">
						<div class="comment-author headings-style">
							<?php
							if ( get_comment_author_url() != '' ) {
								echo '<a class="comment-author-link" href="' . get_comment_author_url() . '" rel="nofollow">';
							} else {
								echo '<span  class="comment-author-link">';
							}

							comment_author();

							if ( get_comment_author_url() != '' ) {
								echo '</a>';
							} else {
								echo '</span>';
							}
							?>
						</div>
						<time class="comment-meta-item" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>"><span><?php comment_date() ?><?php if ( apply_filters( 'uncode_display_comment_time', false ) ) { ?>, <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a><?php } ?></span></time>
						<?php edit_comment_link('<p class="comment-meta-item">' . esc_html__('Edit this comment','uncode') . '</p>','',''); ?>
						<?php if ($comment->comment_approved == '0') : ?>
						<p class="comment-meta-item"><?php esc_html_e('Your comment is awaiting moderation', 'uncode'); ?>.</p>
						<?php endif; ?>
						<?php comment_text() ?>
						<?php comment_reply_link(array_merge( $args, array('reply_text' => '<span>'.esc_html__('Reply', 'uncode').'</span>', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					</div>
				</div>

		<?php }

		// end_el – closing HTML for comment template
		function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

			</article>

		<?php }

		// destructor – closing wrapper for the comments list
		function __destruct() { ?>

			</section>

		<?php }

	}
endif;
?>
