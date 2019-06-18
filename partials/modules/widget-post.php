<?php
/**
 * LSX Blog Customizer - Widget posts - Entry
 */

global $post, $post_display, $post_image;
?>

<div class="post-slot">
	<?php if ( ! empty( $post_image ) ) : ?>
		<figure class="post-image"><?php echo wp_kses_post( $post_image ); ?></figure>
	<?php endif; ?>

	<div class="post-info">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="post-avatar"><?php echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 96 ) ); ?></a>

		<?php
			printf(
				'<span class="post-date"><a href="%1$s" rel="bookmark">%2$s</a></span>',
				esc_url( get_permalink() ),
				sprintf(
					'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
					esc_attr( get_the_date( 'c' ) ),
					get_the_date()
				)
			);
		?>

		<?php
			printf(
				'<span class="post-author"><span>%1$s</span> <a href="%2$s">%3$s</a></span>',
				esc_html__( 'by', 'lsx-blog-customizer' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			);
		?>
	</div>

	<h5 class="post-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h5>

	<div class="post-content"><?php
		if ( 'full' === $post_display ) {
			$stripped_content = wp_strip_all_tags( get_the_content() );
			echo esc_html( $stripped_content );
		} elseif ( 'excerpt' === $post_display ) {
			$stripped_excerpt = wp_strip_all_tags( get_the_excerpt() );
			echo esc_html( $stripped_excerpt );
		}
	?></div>

	<div class="post-meta">
		<?php
			$post_categories = wp_get_post_categories( get_the_ID() );
			$cats = array();

			foreach ( $post_categories as $c ) {
				$cat = get_category( $c );
				/* Translators: %s: category name */
				$cats[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" title="' . sprintf( esc_html__( 'View all posts in %s' , 'lsx-blog-customizer' ), $cat->name ) . '">' . $cat->name . '</a>';
			}

			if ( ! empty( $cats ) ) : ?>
				<div class="post-categories"><span><?php esc_html_e( 'Posted in', 'lsx-blog-customizer' ); ?></span><?php echo wp_kses_post( implode( ', ', $cats ) ); ?></div>
			<?php endif;
		?>

		<?php if ( has_tag() ) : ?>
			<div class="post-tags"><?php echo wp_kses_post( get_the_tag_list( '' ) ); ?></div>
		<?php endif; ?>
	</div>
</div>
