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

		<h5 class="post-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h5>
		<div class="post-meta-info">
			<?php
				printf(
					'<span class="post-author"><span>%1$s</span> <a href="%2$s">%3$s</a>, </span>',
					esc_html__( 'By ', 'lsx-blog-customizer' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_html( get_the_author() )
				);
			?>

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
		</div>

		<?php
			$post_categories = wp_get_post_categories( get_the_ID() );
			$cats = array();

			foreach ( $post_categories as $c ) {
				$cat = get_category( $c );
				/* Translators: %s: category name */
				$cats[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" title="' . sprintf( esc_html__( 'View all posts in %s' , 'lsx-blog-customizer' ), $cat->name ) . '">' . $cat->name . '</a>';
			}

			if ( ! empty( $cats ) ) : ?>
				<div class="post-categories"><span><?php esc_html_e( 'Posted in: ', 'lsx-blog-customizer' ); ?></span><?php echo wp_kses_post( implode( ', ', $cats ) ); ?></div>
			<?php endif;
		?>
	</div>

	<div class="post-content">
		<?php
		if ( 'full' === $post_display ) {
			$stripped_content = wp_strip_all_tags( get_the_content() );
			echo esc_html( $stripped_content );
		} elseif ( 'excerpt' === $post_display ) {
			$stripped_excerpt = wp_strip_all_tags( get_the_excerpt() );
			$excerpt_more     = '<p><a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'lsx' ) . '</a></p>';
			if ( ! has_excerpt() ) {
				$content = wp_trim_words( get_the_content(), 20 );
				$content = '<p>' . $content . '</p>' . $excerpt_more;
				echo wp_kses_post( $content );
			} else {
				$content = '<p>' . $stripped_excerpt . '</p>' . $excerpt_more;
				echo wp_kses_post( $content );
			}
		}
	?>
	</div>

	<div class="post-meta">

		<?php if ( has_tag() ) : ?>
			<div class="post-tags">Tags: <?php echo wp_kses_post( get_the_tag_list( '' ) ); ?></div>
		<?php endif; ?>
	</div>
</div>
