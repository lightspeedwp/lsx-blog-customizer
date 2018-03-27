<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Admin' ) ) {

	/**
	 * LSX Blog Customizer Admin Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2016 LightSpeed
	 */
	class LSX_Blog_Customizer_Admin extends LSX_Blog_Customizer {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			//if ( ! class_exists( 'CMB_Meta_Box' ) ) {
			//	require_once( LSX_BLOG_CUSTOMIZER_PATH . '/vendor/Custom-Meta-Boxes/custom-meta-boxes.php' );
			//}

			add_action( 'customize_preview_init', array( $this, 'assets' ), 9999 );

			add_action( 'init', array( $this, 'create_settings_page' ), 100 );
			add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

			add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		}

		/**
		 * Enques the assets.
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
		 * Returns the array of settings to the UIX Class.
		 *
		 * @since 1.1.0
		 */
		public function create_settings_page() {
			if ( is_admin() ) {
				if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
					include_once LSX_BLOG_CUSTOMIZER_PATH . 'vendor/uix/uix.php';
					$pages = $this->settings_page_array();
					$uix = \lsx\ui\uix::get_instance( 'lsx' );
					$uix->register_pages( $pages );
				}

				if ( function_exists( 'tour_operator' ) ) {
					add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
				} else {
					add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
				}
			}
		}

		/**
		 * Returns the array of settings to the UIX Class.
		 *
		 * @since 1.1.0
		 */
		public function settings_page_array() {
			$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

			return array(
				'settings'  => array(
					'page_title'  => esc_html__( 'Theme Options', 'lsx-blog-customizer' ),
					'menu_title'  => esc_html__( 'Theme Options', 'lsx-blog-customizer' ),
					'capability'  => 'manage_options',
					'icon'        => 'dashicons-book-alt',
					'parent'      => 'themes.php',
					'save_button' => esc_html__( 'Save Changes', 'lsx-blog-customizer' ),
					'tabs'        => $tabs,
				),
			);
		}

		/**
		 * Register tabs.
		 *
		 * @since 1.1.0
		 */
		public function register_tabs( $tabs ) {
			$default = true;

			if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
				$default = false;
			}

			if ( ! function_exists( 'tour_operator' ) ) {
				if ( ! array_key_exists( 'display', $tabs ) ) {
					$tabs['display'] = array(
						'page_title'        => '',
						'page_description'  => '',
						'menu_title'        => esc_html__( 'Display', 'lsx-blog-customizer' ),
						'template'          => LSX_BLOG_CUSTOMIZER_PATH . 'includes/settings/display.php',
						'default'           => $default,
					);

					$default = false;
				}

				if ( ! array_key_exists( 'api', $tabs ) ) {
					$tabs['api'] = array(
						'page_title'        => '',
						'page_description'  => '',
						'menu_title'        => esc_html__( 'API', 'lsx-blog-customizer' ),
						'template'          => LSX_BLOG_CUSTOMIZER_PATH . 'includes/settings/api.php',
						'default'           => $default,
					);

					$default = false;
				}
			}

			return $tabs;
		}

		/**
		 * Outputs the display tabs settings.
		 *
		 * @since 1.1.0
		 *
		 * @param $tab string
		 * @return null
		 */
		public function display_settings( $tab = 'general' ) {
			if ( 'blog-customizer' === $tab ) {
				$this->placeholder_field();
			}
		}

		/**
		 * Outputs the flag position field.
		 *
		 * @since 1.1.0
		 */
		public function placeholder_field() {
			?>
			<tr class="form-field">
				<th scope="row">
					<label for="banner"> <?php esc_html_e( 'Placeholder', 'lsx-blog-customizer' ); ?></label>
				</th>
				<td>
					<input class="input_image_id" type="hidden" {{#if blog_customizer_posts_placeholder_id}} value="{{blog_customizer_posts_placeholder_id}}" {{/if}} name="blog_customizer_posts_placeholder_id" />
					<input class="input_image" type="hidden" {{#if blog_customizer_posts_placeholder}} value="{{blog_customizer_posts_placeholder}}" {{/if}} name="blog_customizer_posts_placeholder" />
					<div class="thumbnail-preview">
						{{#if blog_customizer_posts_placeholder}}<img src="{{blog_customizer_posts_placeholder}}" width="150" />{{/if}}
					</div>
					<a {{#if blog_customizer_posts_placeholder}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="blog_customizer_posts_placeholder"><?php esc_html_e( 'Choose Image', 'lsx-blog-customizer' ); ?></a>
					<a {{#unless blog_customizer_posts_placeholder}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="blog_customizer_posts_placeholder"><?php esc_html_e( 'Delete', 'lsx-blog-customizer' ); ?></a>
				</td>
			</tr>
		<?php }

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

	}

	new LSX_Blog_Customizer_Admin();

}
