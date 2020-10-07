<?php
/**
 * Categories slider
 */

?>
<h2 class="text-center categories-slider-title"><?php echo esc_html__( 'Browse By Category', 'lsx-blog-customizer' ); ?></h2>
<div id="categories-slider">
	<?php
	foreach ( $categories_selected as $category ) :
		$image = '/wp-content/plugins/lsx-blog-customizer/assets/images/placeholder-350x230.jpg';
		
		$image_id = get_term_meta( $category->term_id, 'lsx_customizer_post_term_featured_image', true );

		if ( ! empty( $image_id ) ) {
			$image = $image_id;
		}

		$category->image = $image;
	?>

	<div class="item" style="background-image: url(<?php echo esc_url( $category->image ); ?>)">
		<a href="<?php echo esc_attr( get_category_link( $category->term_id ) ); ?>">
			<span><?php echo esc_html( $category->name ); ?></span>
		</a>
	</div>
	<?php endforeach; ?>
</div>
