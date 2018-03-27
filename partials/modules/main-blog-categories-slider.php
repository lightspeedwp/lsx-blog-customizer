<?php
/**
 * Categories slider
 */

?>

<div id="categories-slider">
	<?php foreach ( $categories_selected as $category ) :
		$image = 'https://placeholdit.imgix.net/~text?txtsize=23&amp;txt=' . esc_attr( $category->name ) . '&amp;w=300&amp;h=200';
		$image_id = get_term_meta( $category->term_id, 'thumbnail', true );

		if ( ! empty( $image_id ) ) {
			$image_data = wp_get_attachment_image_src( $image_id, 'lsx-thumbnail-wide' );

			if ( is_array( $image_data ) ) {
				$image = $image_data[0];
			}
		}

		$category->image = $image;
	?>

	<div class="item" style="background-image: url(<?php echo esc_url( $category->image ); ?>)">
		<a href="<?php echo get_category_link( $category->term_id ); ?>">
			<span><?php echo esc_html( $category->name ); ?></span>
		</a>
	</div>
	<?php endforeach; ?>
</div>
