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
