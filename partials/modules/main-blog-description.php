<?php
/**
 * Layout switcher
 */

?>
<div class="blog-description">
	<?php echo wp_kses_post( apply_filters( 'the_content', $description ) ); ?>
</div>
