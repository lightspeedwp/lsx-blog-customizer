<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Posts' ) ) {

	/**
	 * LSX Blog Customizer Posts Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2016 LightSpeed
	 */
	class LSX_Blog_Customizer_Posts extends LSX_Blog_Customizer {

		/**
		 * Output columns.
		 *
		 * @var string
		 * @since 1.1.0
		 */
		public $columns;

		/**
		 * Output responsive (images).
		 *
		 * @var string
		 * @since 1.1.0
		 */
		public $responsive;

		/**
		 * Holds the shortcode attributes
		 *
		 * @var array
		 */
		public $atts;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 */
		public function __construct() {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_slick_data_params' ), 10, 2 );
			add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
			add_filter( 'excerpt_strip_tags', array( $this, 'change_excerpt_strip_tags' ) );
		}

		/**
		 * Allow data params for Slick slider addon.
		 *
		 * @since 1.1.0
		 */
		public function allow_slick_data_params( $allowedtags, $context ) {
			$allowedtags['div']['data-slick'] = true;
			return $allowedtags;
		}

		/**
		 * Output.
		 *
		 * @since 1.1.0
		 */
		public function output( $atts ) {
			extract( shortcode_atts( array(
				'taxonomy'   => 'category',
				'columns'    => 3,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'limit'      => '-1',
				'include'    => '',
				'display'    => 'excerpt',
				'size'       => 'lsx-thumbnail-single',
				'responsive' => 'true',
				'show_image' => 'true',
				'carousel'   => 'true',
			), $atts ) );

			if ( 'true' === $responsive || true === $responsive ) {
				$responsive = ' img-responsive';
			} else {
				$responsive = '';
			}
			$this->atts       = $atts;
			$this->columns    = $columns;
			$this->responsive = $responsive;

			if ( ! empty( $include ) ) {
				$include = explode( ',', $include );

				$args = array(
					'taxonomy'       => 'category',
					'post_type'      => 'post',
					'posts_per_page' => $limit,
					'post__in'       => $include,
					'orderby'        => 'post__in',
					'order'          => $order,
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array(
								'post-format-aside',
								'post-format-audio',
								'post-format-chat',
								'post-format-gallery',
								'post-format-image',
								'post-format-link',
								'post-format-quote',
								'post-format-status',
								'post-format-video',
							),
							'operator' => 'NOT IN',
						),
					),
				);
			} else {
				$args = array(
					'taxonomy'       => 'category',
					'post_type'      => 'post',
					'posts_per_page' => $limit,
					'orderby'        => $orderby,
					'order'          => $order,
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array(
								'post-format-aside',
								'post-format-audio',
								'post-format-chat',
								'post-format-gallery',
								'post-format-image',
								'post-format-link',
								'post-format-quote',
								'post-format-status',
								'post-format-video',
							),
							'operator' => 'NOT IN',
						),
					),
				);
			}

			// Use the category attribute properly.
			if ( 'category' !== $taxonomy && ! empty( $taxonomy ) && false !== $taxonomy ) {
				$args['cat'] = $taxonomy;
			}
			$posts = new WP_Query( $args );

			if ( $posts->have_posts() ) {
				global $post, $post_display, $post_image;

				$post_display = $display;

				$count        = 0;
				$count_global = 0;

				if ( ( 'true' === $carousel || true === $carousel ) && 1 < $posts->post_count ) : ?>
					<div class="lsx-blog-customizer-posts-slider lsx-blog-customizer-posts-wrapper" data-slick='{ "slidesToShow": <?php echo esc_attr( $columns ); ?>, "slidesToScroll": <?php echo esc_attr( $columns ); ?> }'>
				<?php else : ?>
					<div class="lsx-blog-customizer-posts-wrapper"><div class="row">
				<?php endif;

				while ( $posts->have_posts() ) {
					$posts->the_post();

					// Count
					$count++;
					$count_global++;

					// Image
					if ( 'true' === $show_image || true === $show_image ) {
						if ( is_numeric( $size ) ) {
							$thumb_size = array( $size, $size );
						} else {
							$thumb_size = $size;
						}

						if ( ! empty( get_the_post_thumbnail( $post->ID ) ) ) {
							$post_thumb_id = get_post_thumbnail_id( $post->ID );
							if ( 'lsx-placeholder' === $post_thumb_id ) {
								$post_image = '<img class="attachment-responsive wp-post-image lsx-responsive" src="/wp-content/plugins/lsx-blog-customizer/assets/images/placeholder-350x230.jpg">';
							} else {
								$post_image = get_the_post_thumbnail( $post->ID, $thumb_size, array(
									'class' => $responsive,
								) );
							}
						} else {
							$post_image = '';
						}

						if ( empty( $post_image ) ) {
							if ( $this->get_placeholder() ) {
								$post_image = '<img class="' . $responsive . '" src="' . $this->get_placeholder() . '" width="' . $size . '" alt="placeholder" />';
							} else {
								$post_image = '';
							}
						}
					} else {
						$post_image = '';
					}

					if ( ( 'true' === $carousel || true === $carousel ) && 1 < $posts->post_count ) {
						$this->content_part( 'widget', 'post' );
					} elseif ( $columns >= 1 && $columns <= 4 ) {
						$md_col_width = 12 / $columns;

						?><div class="col-xs-12 col-md-<?php echo esc_attr( $md_col_width ); ?>"><?php
						$this->content_part( 'widget', 'post' );
						?></div><?php

						if ( $count === $columns && $posts->post_count > $count_global ) {
							?></div><div class="row"><?php
							$count = 0;
						}
					} else { ?>
						<p class="bg-warning" style="padding:20px;">
							<?php esc_html_e( 'Invalid number of columns set. LSX Blog Customizer Posts supports 1 to 4 columns.', 'lsx-blog-customizer' ); ?>
						</p>
					<?php }

					wp_reset_postdata();
				}

				if ( ( 'true' === $carousel || true === $carousel ) && 1 < $posts->post_count ) {
					?>
					</div>
					<?php
				} else {
					?>
					</div>
				</div>
					<?php
				}
			}
		}

		/**
		 * Change the word count when crop the content to excerpt (homepage widget).
		 *
		 * @since 1.1.0
		 */
		public function change_excerpt_length( $excerpt_word_count ) {
			global $post;

			if ( is_front_page() && 'post' === $post->post_type ) {
				$excerpt_word_count = 20;
			}

			return $excerpt_word_count;
		}

		/**
		 * Change the allowed tags crop the content to excerpt (homepage widget).
		 *
		 * @since 1.1.0
		 */
		public function change_excerpt_strip_tags( $allowed_tags ) {
			global $post;

			if ( is_front_page() && 'post' === $post->post_type ) {
				$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
			}

			return $allowed_tags;
		}

		/**
		 * Redirect WordPress to the single template located in the plugin.
		 *
		 * @since 1.1.0
		 */
		public function content_part( $slug, $name = null ) {
			$template = array();

			if ( ! empty( $name ) ) {
				$template = "{$slug}-{$name}.php";
			} else {
				$template = "{$slug}.php";
			}

			$original_name = $template;
			$path          = get_stylesheet_directory() . '/lsx-blog-customizer/';
			$path          = apply_filters( 'lsx_blog_customizer_widget_path', $path, $this->atts );
			$template      = apply_filters( 'lsx_blog_customizer_widget_template', $template, $this->atts );

			if ( file_exists( $path . $template ) ) {
				$template = $path . $template;
			} elseif ( file_exists( LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/' . $template ) ) {
				$template = LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/' . $template;
			} else {
				$template = false;
			}

			if ( ! empty( $template ) ) {
				load_template( $template, false );
			} else {
				echo wp_kses_post( '<p>No ' . $original_name . ' can be found.</p>' );
			}
		}
	}
}
