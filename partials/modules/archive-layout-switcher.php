<?php
/**
 * Layout switcher
 */

?>

<div class="lsx-layout-switcher">
	<span class="lsx-layout-switcher-label"><?php esc_html_e( 'Select view:', 'lsx-blog-customizer' ); ?></span>

	<div class="lsx-layout-switcher-options">
		<a href="#" class="lsx-layout-switcher-option <?php echo 'default' === $archive_layout ? 'active' : ''; ?>" data-layout="default" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Default', 'lsx-blog-customizer' ); ?>" aria-label="<?php esc_html_e( 'Default view', 'lsx-blog-customizer' ); ?>">
			<i class="fa fa-arrows-alt" aria-hidden="true"></i>
		</a>

		<a href="#" class="lsx-layout-switcher-option <?php echo 'grid' === $archive_layout ? 'active' : ''; ?>" data-layout="grid" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Grid', 'lsx-blog-customizer' ); ?>" aria-label="<?php esc_html_e( 'Grid view', 'lsx-blog-customizer' ); ?>">
			<i class="fa fa-th-large" aria-hidden="true"></i>
		</a>

		<a href="#" class="lsx-layout-switcher-option <?php echo 'list' === $archive_layout ? 'active' : ''; ?>" data-layout="list" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'List', 'lsx-blog-customizer' ); ?>" aria-label="<?php esc_html_e( 'List view', 'lsx-blog-customizer' ); ?>">
			<i class="fa fa-bars" aria-hidden="true"></i>
		</a>
	</div>
</div>
