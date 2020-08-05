<?php
/**
 * Layout switcher
 */

$layout_options = array(
	'default' => array(
		'label' => __( 'Default', 'lsx-blog-customizer' ),
		'icon'  => 'fa-arrows-alt',
	),
	'grid' => array(
		'label' => __( 'Grid 3 Columns', 'lsx-blog-customizer' ),
		'icon'  => 'fa-th',
	),
	'half-grid' => array(
		'label' => __( 'Grid 2 Columns', 'lsx-blog-customizer' ),
		'icon'  => 'fa-th-large',
	),
	'list' => array(
		'label' => __( 'List', 'lsx-blog-customizer' ),
		'icon'  => 'fa-bars',
	),
);
$layout_options = apply_filters( 'lsx_layout_switcher_options', $layout_options );
if ( ! empty( $layout_options ) ) {
	?>
	<div class="lsx-layout-switcher">
		<span class="lsx-layout-switcher-label"><?php esc_html_e( 'Select view:', 'lsx-blog-customizer' ); ?></span>

		<div class="lsx-layout-switcher-options" data-page="<?php echo esc_attr( $page_key ); ?>">
			<?php
			foreach ( $layout_options as $key => $params ) {
				?>
					<a href="#" class="lsx-layout-switcher-option <?php echo $key === $archive_layout ? 'active' : ''; ?>" data-layout="<?php echo esc_attr( $key ); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr( $params['label'] ); ?>" aria-label="<?php echo esc_attr( $params['label'] ); ?><?php esc_html_e( ' view', 'lsx-blog-customizer' ); ?>">
					<i class="fa <?php echo esc_attr( $params['icon'] ); ?>" aria-hidden="true"></i>
					</a>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
?>
