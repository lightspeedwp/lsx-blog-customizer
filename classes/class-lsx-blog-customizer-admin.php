<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Admin' ) ) {

	/**
	 * LSX Blog Customizer Admin Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer_Admin extends LSX_Blog_Customizer {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'customize_preview_init', array( $this, 'assets' ), 9999 );
			add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
			add_action( 'cmb2_admin_init', array( $this, 'register_blog_taxonomy_fields' ), 5  );
			$this->load_vendors();
		}

		/**
		 * Loads the plugin functions.
		 */
		private function load_vendors() {
			// Configure custom fields.
			if ( ! class_exists( 'CMB2' ) ) {
				require_once LSX_BLOG_CUSTOMIZER_PATH . 'vendor/CMB2/init.php';
			}
		}

		/**
		 * Returns the post types currently active
		 *
		 * @return void
		 */
		public function get_post_types() {
			$post_types = apply_filters( 'lsx_blog_customizer_post_types', isset( $this->post_types ) );
			foreach ( $post_types as $index => $post_type ) {
				$is_disabled = \cmb2_get_option( 'lsx_blog_customizer_options', $post_type . '_disabled', false );
				if ( true === $is_disabled || 1 === $is_disabled || 'on' === $is_disabled ) {
					unset( $post_types[ $index ] );
				}
			}
			return $post_types;
		}

		/**
		 * Enqueues the assets.
		 *
		 * @since 1.0.0
		 */
		public function assets() {
			wp_enqueue_script( 'lsx_blog_customizer_admin', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/lsx-blog-customizer-admin.min.js', array( 'jquery' ), LSX_BLOG_CUSTOMIZER_VER, true );

			$params = apply_filters( 'lsx_blog_customizer_admin_js_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));

			wp_localize_script( 'lsx_blog_customizer_admin', 'lsx_blog_customizer_params', $params );
			wp_enqueue_style( 'lsx_blog_customizer_admin', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/lsx-blog-customizer-admin.css', array(), LSX_BLOG_CUSTOMIZER_VER );
		}

		/**
		 * Change the "Insert into Post" button text when media modal is used for feature images.
		 *
		 * @since 1.1.0
		 */
		public function change_attachment_field_button( $html ) {
			if ( isset( $_GET['feature_image_text_button'] ) ) {
				$html = str_replace( 'value="Insert into Post"', sprintf( 'value="%s"', esc_html__( 'Select featured image', 'lsx-blog-customizer' ) ), $html );
			}

			return $html;
		}

		/**
		 * Hook in and add a metabox to add fields to the Categories.
		 */
		function register_blog_taxonomy_fields() {
			$prefix = 'lsx_customizer_post_term_';
		 
			/** 
			 * Metabox to add fields to categories and tags 
			 */ 
			$cmb_term = new_cmb2_box( array( 
				'id'           => $prefix . 'edit',
				'title'        => esc_html__( 'Additional Fields', 'lsx-blog-customizer' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( 'category', 'post_tag' ),
			) );

			$cmb_term->add_field( array( 
				'name'        => esc_html__( 'Banner Title', 'lsx-blog-customizer' ), 
				'id'          => $prefix . 'banner_title',
				'description' =>  esc_html__( 'Add an optional title for the banner', 'lsx-blog-customizer' ),
				'type'        => 'text',
			) );

			$cmb_term->add_field( array( 
				'name'        => esc_html__( 'Banner Tagline', 'lsx-blog-customizer' ),
				'id'          => $prefix . 'banner_tagline',
				'description' =>  esc_html__( 'Add an optional tagline for the banner', 'lsx-blog-customizer' ), 
				'type'        => 'textarea_small',
			) );
		 
			$cmb_term->add_field( array( 
				'name'         => esc_html__( 'Featured Image', 'lsx-blog-customizer' ),
				'id'           => $prefix . 'featured_image',
				'description'  =>  esc_html__( 'Add an optional featured image for the blog archive carousel.', 'lsx-blog-customizer' ),
				'type'         => 'file',
				'preview_size' => array( 50, 50 ),
			) );

			$cmb_term->add_field( array( 
				'name'         => esc_html__( 'Icon Image', 'lsx-blog-customizer' ), 
				'id'           => $prefix . 'icon_image',
				'description'  =>  esc_html__( 'Add an optional icon for the banner', 'lsx-blog-customizer' ), 
				'type'         => 'file',
				'preview_size' => array( 25, 25 ),
			) );

			$cmb_term->add_field( array( 
				'name'         => esc_html__( 'Banner Image', 'lsx-blog-customizer' ),
				'id'           => $prefix . 'banner_image',
				'description'  =>  esc_html__( 'Add an optional background image for the banner', 'lsx-blog-customizer' ),
				'type'         => 'file',
				'preview_size' => array( 100, 50 ),
			) ); 
		}

	}

	new LSX_Blog_Customizer_Admin();

}
