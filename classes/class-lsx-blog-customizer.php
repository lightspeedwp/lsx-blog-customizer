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
			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-admin.php';
			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-frontend.php';
			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-blog.php';

			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-posts.php';
			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-widget-posts.php';

			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-terms.php';
			require_once LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer-widget-terms.php';
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
		 * Sanitize select (layout switcher).
		 *
		 * @since 1.0.0
		 */
		public function sanitize_select_posts_relation( $input ) {
			$valid = array(
				'both'   => esc_html__( 'Both', 'lsx-blog-customizer' ),
				'post_tag'      => esc_html__( 'Tag', 'lsx-blog-customizer' ),
				'category'      => esc_html__( 'Category', 'lsx-blog-customizer' ),
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

		/**
		 * Get the options array from the customizer options.
		 *
		 * @return array
		 */
		public function get_options() {
			$options = array();
			if ( function_exists( 'tour_operator' ) ) {
				$options = get_option( '_lsx-to_settings', false );
			} else {
				$options = get_option( '_lsx_settings', false );
				if ( false === $options ) {
					$options = get_option( '_lsx_lsx-settings', false );
				}
			}
			return $options;
		}
		/**
		 * Get the placeholder for the blog posts.
		 *
		 * @return string
		 */
		public function get_placeholder() {
			$options     = $this->get_options();
			$placeholder = '';
			if ( isset( $options['display'] ) && ! empty( $options['display']['blog_customizer_posts_placeholder'] ) ) {
				$placeholder = $options['display']['blog_customizer_posts_placeholder'];
			}
			if ( get_theme_mod( 'lsx_blog_customizer_general_placeholder' ) ) {
				$placeholder = get_theme_mod( 'lsx_blog_customizer_general_placeholder' );
			}
			return $placeholder;
		}

		/**
		 * Get the placeholder for the blog posts.
		 *
		 * @return string
		 */
		public function get_placeholder_id() {
			$options     = $this->get_options();
			$placeholder = '';
			if ( isset( $options['display'] ) && ! empty( $options['display']['blog_customizer_posts_placeholder'] ) ) {
				$placeholder = $options['display']['blog_customizer_posts_placeholder'];
			}
			if ( get_theme_mod( 'lsx_blog_customizer_general_placeholder' ) ) {
				$placeholder_id = attachment_url_to_postid( get_theme_mod( 'lsx_blog_customizer_general_placeholder' ) );
				if ( false !== $placeholder_id ) {
					$placeholder = $placeholder_id;
				}
			}
			return $placeholder;
		}
	}

	new LSX_Blog_Customizer();

}
