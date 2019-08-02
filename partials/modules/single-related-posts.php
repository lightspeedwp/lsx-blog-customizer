<?php
/**
 * Related posts
 */

$related_query = get_transient( 'lsx_related_query_WP_Query_' . get_the_ID() );
?>

<div class="row lsx-related-posts lsx-related-posts-title">
	<div class="col-xs-12">
		<h2 class="lsx-title lsx-related-posts-headline"><?php esc_html_e( 'Related Posts', 'lsx-blog-customizer' ); ?></h2>
	</div>
</div>

<div class="row lsx-related-posts lsx-related-posts-content">
	<div class="col-xs-12">
		<div class="lsx-related-posts-wrapper">
			<?php
				while ( $related_query->have_posts() ) {
					$related_query->the_post();
					get_template_part( 'partials/content-related', get_post_format() );
				}
				wp_reset_postdata();
			?>
		</div>
	</div>
</div>
