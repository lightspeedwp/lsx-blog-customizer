<?php
if ( ! class_exists( 'LSX_Blog_Customizer' ) ) {

	/**
	 * LSX Blog Customizer Main Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer {

		/**
		 * Plugin slug.
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $plugin_slug = 'lsx-blog-customizer';

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-admin.php' );
			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-frontend.php' );
			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-blog.php' );

			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-posts.php' );
			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-widget-posts.php' );

			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-terms.php' );
			require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-widget-terms.php' );
		}

		/**
		 * Sanitize checkbox.
		 *
		 * @since 1.0.0
		 */
		public function sanitize_checkbox( $input ) {
			return ( 1 === absint( $input ) ) ? 1 : 0;
		}

		/**
		 * Sanitize select (layout switcher).
		 *
		 * @since 1.0.0
		 */
		public function sanitize_select_layout_switcher( $input ) {
			$valid = array(
				'default'   => esc_html__( 'Default', 'lsx-blog-customizer' ),
				'list'      => esc_html__( 'List', 'lsx-blog-customizer' ),
				'grid'      => esc_html__( '3 Columns Grid', 'lsx-blog-customizer' ),
				'half-grid' => esc_html__( '2 Columns Grid', 'lsx-blog-customizer' ),
			);

			if ( array_key_exists( $input, $valid ) ) {
				return $input;
			} else {
				return '';
			}
		}

		/**
		 * Sanitize textarea.
		 *
		 * @since 1.0.1
		 */
		public function sanitize_textarea( $input ) {
			return wp_kses_post( $input );
		}

	}

	new LSX_Blog_Customizer();

}
