<?php
/**
 * Displays comments and the comment form for singular content.
 *
 * @package Scouting_Nederland
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="scouting-nederland-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="scouting-nederland-comments-title">
			<?php
			$scouting_nederland_comment_count = get_comments_number();
			printf(
				esc_html(
					/* translators: %s: Number of comments. */
					_n( '%s comment', '%s comments', $scouting_nederland_comment_count, 'scouting-nederland-wordpress-theme' )
				),
				esc_html( number_format_i18n( $scouting_nederland_comment_count ) )
			);
			?>
		</h2>

		<ol class="scouting-nederland-comment-list">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 48,
					'short_ping'  => true,
					'style'       => 'ol',
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( 'Older comments', 'scouting-nederland-wordpress-theme' ),
				'next_text' => esc_html__( 'Newer comments', 'scouting-nederland-wordpress-theme' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="scouting-nederland-comments-closed"><?php esc_html_e( 'Comments are closed.', 'scouting-nederland-wordpress-theme' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'   => 'scouting-nederland-comment-form',
			'title_reply'  => __( 'Leave a comment', 'scouting-nederland-wordpress-theme' ),
			'label_submit' => __( 'Post comment', 'scouting-nederland-wordpress-theme' ),
		)
	);
	?>
</section>
