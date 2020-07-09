<?php
/**
 * Layout switcher
 */

?>
<div class="<?php echo esc_attr( apply_filters( 'lsx_blog_customizer_blog_description_class', 'blog-description' ) ); ?>">
	<?php echo wp_kses_post( apply_filters( 'the_content', $description ) ); ?>
</div>
