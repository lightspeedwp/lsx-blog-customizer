<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Frontend' ) ) {

	/**
	 * LSX Blog Customizer Frontend Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer_Frontend extends LSX_Blog_Customizer {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 999 );
			add_action( 'wp',                 array( $this, 'layout' ), 999 );

			add_filter( 'body_class',         array( $this, 'body_class' ), 10 );
			add_filter( 'post_class',         array( $this, 'post_class' ), 10 );

			add_action( 'lsx_banner_inner_bottom', array( $this, 'archive_layout_switcher' ), 90 );
			add_action( 'lsx_global_header_inner_bottom', array( $this, 'archive_layout_switcher' ), 90 );

			add_action( 'lsx_content_top',    array( $this, 'main_blog_page_description' ), 120 );
			add_action( 'lsx_content_top',    array( $this, 'main_blog_page_carousel' ),    130 );

			add_action( 'lsx_content_after',   array( $this, 'single_blog_page_related_posts' ), 20 );

			if ( is_admin() ) {
				add_filter( 'lsx_customizer_colour_selectors_body', array( $this, 'customizer_body_colours_handler' ), 15, 2 );
				add_filter( 'lsx_customizer_colour_selectors_banner', array( $this, 'customizer_banner_colours_handler' ), 15, 2 );
			}
		}

		/**
		 * Enques the assets.
		 *
		 * @since 1.0.0
		 */
		public function assets() {
			$has_owl = wp_script_is( 'owl-slider', 'queue' );

			if ( ! $has_owl ) {
				wp_enqueue_script( 'owl-slider', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/vendor/owl.carousel.min.js', array( 'jquery' ) , LSX_BLOG_CUSTOMIZER_VER, true );
				wp_enqueue_style( 'owl-slider', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/vendor/owl.carousel.min.css', array(), LSX_BLOG_CUSTOMIZER_VER );
			}

			$has_slick = wp_script_is( 'slick', 'queue' );

			if ( ! $has_slick ) {
				wp_enqueue_style( 'slick', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/vendor/slick.css', array(), LSX_BLOG_CUSTOMIZER_VER, null );
				wp_enqueue_script( 'slick', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), null, LSX_BLOG_CUSTOMIZER_VER, true );
			}

			//wp_register_script( 'jquery-touchswipe', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/vendor/jquery.touchSwipe.min.js', array( 'jquery' ) , LSX_BLOG_CUSTOMIZER_VER, true );
			//wp_enqueue_script( 'jquery-touchswipe' );

			wp_enqueue_script( 'lsx_blog_customizer', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/lsx-blog-customizer.min.js', array( 'jquery', 'owl-slider', 'slick', 'jquery-ui-tooltip' ), LSX_BLOG_CUSTOMIZER_VER, true );

			$params = apply_filters( 'lsx_blog_customizer_js_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));

			wp_localize_script( 'lsx_blog_customizer', 'lsx_blog_customizer_params', $params );

			wp_enqueue_style( 'lsx-blog-customizer', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/lsx-blog-customizer.css', array(), LSX_BLOG_CUSTOMIZER_VER );
			wp_style_add_data( 'lsx-blog-customizer', 'rtl', 'replace' );
		}

		/**
		 * Layout.
		 *
		 * @since 1.0.0
		 */
		public function layout() {
			$body_classes               = get_body_class();

			$is_archive                 = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_single_post             = is_singular( 'post' );
			$is_archive_or_single_post  = $is_archive || $is_single_post;

			$general_date               = get_theme_mod( 'lsx_blog_customizer_general_date', true );
			$general_author             = get_theme_mod( 'lsx_blog_customizer_general_author', true );
			$general_category           = get_theme_mod( 'lsx_blog_customizer_general_category', true );
			$general_tags               = get_theme_mod( 'lsx_blog_customizer_general_tags', true );

			$archive_text               = get_theme_mod( 'lsx_blog_customizer_archive_text', true );
			$archive_full_content       = get_theme_mod( 'lsx_blog_customizer_archive_full_content', false );
			$archive_layout             = $this->get_layout_value_from_cookie();

			// $single_thumbnail           = get_theme_mod( 'lsx_blog_customizer_single_thumbnail', true );
			$single_related_posts       = get_theme_mod( 'lsx_blog_customizer_single_related_posts', true );

			if ( $is_archive_or_single_post && false == $general_date ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_date', 10 );
			}

			if ( $is_archive_or_single_post && false == $general_author ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_author', 20 );
			}

			if ( $is_archive_or_single_post && false == $general_category ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_category', 30 );
			}

			if ( $is_archive_or_single_post && false == $general_tags ) {
				remove_action( 'lsx_content_post_tags', 'lsx_post_tags', 10 );
			}

			if ( $is_archive && false == $archive_text ) {
				add_filter( 'lsx_blog_display_text_on_list', '__return_false' );
			}

			if ( $is_archive && true == $archive_full_content ) {
				add_filter( 'lsx_blog_force_content_on_list', '__return_true' );
			}

			if ( $is_archive ) {
				if ( 'grid' === $archive_layout ) {
					add_filter( 'lsx_blog_layout',
						function( $layout ) {
							return 'grid';
						}
					);
				} elseif ( 'list' === $archive_layout ) {
					add_filter( 'lsx_blog_layout',
						function( $layout ) {
							return 'list';
						}
					);
				} else {
					add_filter( 'lsx_blog_layout',
						function( $layout ) {
							return 'default';
						}
					);
				}
			}

			/*if ( $is_single_post && false == $single_thumbnail ) {
				add_filter( 'lsx_allowed_post_type_banners', function( $post_types ) {
					if ( ( $key = array_search( 'post', $post_types ) ) !== false ) {
						unset( $post_types[$key] );
					}

					return $post_types;
				} );
			}*/
		}

		/**
		 * Body class.
		 *
		 * @param array $classes the classes applied to the body tag.
		 * @return array $classes the classes applied to the body tag.
		 * @since 1.0.0
		 */
		public function body_class( $body_classes ) {
			$is_archive                 = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_single_post             = is_singular( 'post' );
			$is_archive_or_single_post  = $is_archive || $is_single_post;

			$general_date               = get_theme_mod( 'lsx_blog_customizer_general_date', true );
			$general_author             = get_theme_mod( 'lsx_blog_customizer_general_author', true );
			$general_category           = get_theme_mod( 'lsx_blog_customizer_general_category', true );

			$archive_full_width         = get_theme_mod( 'lsx_blog_customizer_archive_full_width', false );
			$archive_layout             = $this->get_layout_value_from_cookie();

			$single_full_width          = get_theme_mod( 'lsx_blog_customizer_single_full_width', false );
			$single_posts_navigation    = get_theme_mod( 'lsx_blog_customizer_single_posts_navigation', true );

			if ( $is_archive_or_single_post && false == $general_date ) {
				$body_classes[] = 'lsx-hide-post-date';
			}

			if ( $is_archive_or_single_post && false == $general_author ) {
				$body_classes[] = 'lsx-hide-post-author';
			}

			if ( $is_archive_or_single_post && false == $general_category ) {
				$body_classes[] = 'lsx-hide-post-category';
			}

			if ( $is_archive && true == $archive_full_width ) {
				$body_classes[] = 'lsx-body-full-width';
			}

			if ( $is_archive ) {
				if ( 'grid' === $archive_layout ) {
					$body_classes[] = 'lsx-body-grid-layout';
				} elseif ( 'list' === $archive_layout ) {
					$body_classes[] = 'lsx-body-list-layout';
				}
			}

			if ( $is_single_post && true == $single_full_width ) {
				$body_classes[] = 'lsx-body-full-width';
			}

			if ( $is_single_post && false == $single_posts_navigation ) {
				$body_classes[] = 'lsx-hide-posts-navigation';
			}

			return $body_classes;
		}

		/**
		 * Post class.
		 *
		 * @param  array $classes The classes.
		 * @return array $classes The classes.
		 * @since 1.0.0
		 */
		public function post_class( $classes ) {
			$body_classes               = get_body_class();

			$is_archive                 = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_single_post             = is_singular( 'post' );
			$is_archive_or_single_post  = $is_archive || $is_single_post;

			$general_tags               = get_theme_mod( 'lsx_blog_customizer_general_tags', true );

			if ( $is_single_post && false == $general_tags ) {
				if ( ! class_exists( 'LSX_Sharing' ) && ! function_exists( 'sharing_display' ) && ! class_exists( 'Jetpack_Likes' ) ) {
					$classes[] = 'lsx-hide-post-tags';
				}
			} elseif ( $is_archive && false == $general_tags ) {
				$comments_number = get_comments_number();

				if ( ! comments_open() || ! empty( $comments_number ) ) {
					$classes[] = 'lsx-hide-post-tags';
				}
			}

			return $classes;
		}

		/**
		 * Display (or not) the categories carousel on main blog page
		 *
		 * @since 1.0.0
		 */
		public function main_blog_page_carousel() {
			$body_classes = get_body_class();

			if ( in_array( 'blog', $body_classes ) ) {
				$categories          = get_categories();
				$categories_selected = array();

				foreach ( $categories as $counter => $category ) {
					$customizer_value = get_theme_mod( 'lsx_blog_customizer_main_blog_page_carousel_' . $category->term_id, true );

					if ( 1 === $customizer_value ) {
						$categories_selected[] = $category;
					}
				}

				if ( count( $categories_selected ) > 0 ) {
					if ( empty( locate_template( array( 'lsx-blog-customizer/partials/modules/main-blog-categories-slider.php' ) ) ) ) {
						include LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/main-blog-categories-slider.php';
					} else {
						include locate_template( array( 'lsx-blog-customizer/partials/modules/main-blog-categories-slider.php' ) );
					}
				}
			}
		}

		/**
		 * Display (or not) the categories carousel on main blog page
		 *
		 * @since 1.0.0
		 */
		public function archive_layout_switcher() {
			$body_classes               = get_body_class();
			$is_archive                 = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date();
			$archive_layout_switcher    = get_theme_mod( 'lsx_blog_customizer_archive_layout_switcher', false );

			if ( $is_archive && true == $archive_layout_switcher ) {
				$archive_layout = $this->get_layout_value_from_cookie();

				if ( empty( locate_template( array( 'lsx-blog-customizer/partials/modules/archive-layout-switcher.php' ) ) ) ) {
					include LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/archive-layout-switcher.php';
				} else {
					include locate_template( array( 'lsx-blog-customizer/partials/modules/archive-layout-switcher.php' ) );
				}
			}
		}

		/**
		 * Get layout value from cookie
		 *
		 * @since 1.0.0
		 */
		public function get_layout_value_from_cookie() {
			$archive_layout = get_theme_mod( 'lsx_blog_customizer_archive_layout', 'default' );

			if ( isset( $_COOKIE['lsx-blog-layout'] ) ) {
				$archive_layout_from_cookie = sanitize_key( $_COOKIE['lsx-blog-layout'] );
				$archive_layout_from_cookie = $this->sanitize_select_layout_switcher( $archive_layout_from_cookie );

				if ( ! empty( $archive_layout_from_cookie ) ) {
					$archive_layout = $archive_layout_from_cookie;
				}
			}

			return $archive_layout;
		}

		/**
		 * Display (or not) the related posts on single blog page
		 *
		 * @since 1.0.1
		 */
		public function single_blog_page_related_posts() {
			$is_single_post       = is_singular( 'post' );
			$single_related_posts = get_theme_mod( 'lsx_blog_customizer_single_related_posts', true );

			if ( $is_single_post && true == $single_related_posts ) {
				$post_id        = get_the_ID();
				$post_ids       = array();
				$posts_per_page = 3;
				$post__not_in   = array( $post_id );
				$related_query  = get_transient( 'lsx_related_query_WP_Query_' . $post_id );

				if ( ! is_object( $related_query ) || ! is_a( $related_query, 'WP_Query' ) ) {
					$tags = wp_get_object_terms( $post_id, 'post_tag' );

					if ( ! empty( $tags ) ) {
						$tag_ids = wp_list_pluck( $tags, 'term_id' );

						$args = array(
							'fields'                 => 'ids',
							'post__not_in'           => $post__not_in,
							'tag__in'                => $tag_ids,
							'posts_per_page'         => $posts_per_page,
							'orderby'                => 'rand',
							'order'                  => 'DESC',
							'no_found_rows'          => true,
							'ignore_sticky_posts'    => 1,
							'update_post_meta_cache' => false,
							'update_post_meta_cache' => false,
						);

						$related_query_1 = new \WP_Query( $args );

						if ( isset( $related_query_1->posts ) ) {
							$post_ids = array_merge( $post_ids, $related_query_1->posts );
						}
					}

					if ( empty( $tags ) || empty( $related_query_1 ) || $related_query_1->post_count < 3 ) {
						$categories = wp_get_object_terms( $post_id, 'category' );

						if ( ! empty( $related_query_1 ) ) {
							$posts_per_page -= $related_query_1->post_count;
							$post__not_in = array_merge( $post__not_in, $related_query_1->posts );
						}

						if ( ! empty( $categories ) ) {
							$category_ids = wp_list_pluck( $categories, 'term_id' );

							$args = array(
								'fields'                 => 'ids',
								'post__not_in'           => $post__not_in,
								'posts_per_page'         => $posts_per_page,
								'category__in'           => $category_ids,
								'orderby'                => 'rand',
								'order'                  => 'DESC',
								'no_found_rows'          => true,
								'ignore_sticky_posts'    => 1,
								'update_post_meta_cache' => false,
								'update_post_meta_cache' => false,
							);

							$related_query_2 = new \WP_Query( $args );

							if ( isset( $related_query_2->posts ) ) {
								$post_ids = array_merge( $post_ids, $related_query_2->posts );
							}
						}
					}

					if ( count( $post_ids ) > 0 ) {
						$args = array(
							'post__in'               => $post_ids,
							'orderby'                => 'post__in',
							'order'                  => 'ASC',
							'no_found_rows'          => true,
							'ignore_sticky_posts'    => 1,
							'update_post_meta_cache' => false,
						);

						$related_query = new \WP_Query( $args );
						set_transient( 'lsx_related_query_WP_Query_' . $post_id, $related_query, MINUTE_IN_SECONDS );
					}
				}

				if ( is_object( $related_query ) && is_a( $related_query, 'WP_Query' ) ) {
					if ( $related_query->have_posts() ) {
						remove_action( 'lsx_entry_bottom', array( $this, 'single_blog_page_related_posts' ), 20 );
						add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ), 999 );

						global $wp_query;

						$wp_query->is_single   = false;
						$wp_query->is_singular = false;

						if ( empty( locate_template( array( 'lsx-blog-customizer/partials/modules/single-related-posts.php' ) ) ) ) {
							include LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/single-related-posts.php';
						} else {
							include locate_template( array( 'lsx-blog-customizer/partials/modules/single-related-posts.php' ) );
						}

						$wp_query->is_single   = true;
						$wp_query->is_singular = true;

						remove_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ), 999 );
					}
				}
			}
		}

		/**
		 * Change excerpt length
		 *
		 * @since 1.0.1
		 */
		public function change_excerpt_length( $length ) {
			return 10;
		}

		/**
		 * Display (or not) the description text on main blog page
		 *
		 * @since 1.0.1
		 */
		public function main_blog_page_description() {
			$body_classes = get_body_class();

			if ( in_array( 'blog', $body_classes ) ) {
				$description = get_theme_mod( 'lsx_blog_customizer_main_blog_page_description', true );

				if ( ! empty( $description ) ) {
					if ( empty( locate_template( array( 'lsx-blog-customizer/partials/modules/main-blog-description.php' ) ) ) ) {
						include LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/main-blog-description.php';
					} else {
						include locate_template( array( 'lsx-blog-customizer/partials/modules/main-blog-description.php' ) );
					}
				}
			}
		}

		/**
		 * Handle body colours that might be change by LSX Customiser
		 */
		public function customizer_body_colours_handler( $css, $colors ) {
			$css .= '
				@import "' . LSX_BLOG_CUSTOMIZER_PATH . '/assets/css/scss/customizer-blog-body-colours";

				/**
				 * LSX Customizer - Body (LSX Blog Customizer)
				 */
				@include customizer-blog-body-colours (
					$breaker:   ' . $colors['body_line_color'] . ',
					$color:    	' . $colors['body_text_color'] . ',
					$link:    	' . $colors['body_link_color'] . ',
					$hover:    	' . $colors['body_link_hover_color'] . ',
					$small:    	' . $colors['body_text_small_color'] . '
				);
			';

			return $css;
		}

		/**
		 * Handle body colours that might be change by LSX Customiser
		 */
		public function customizer_banner_colours_handler( $css, $colors ) {
			$css .= '
				@import "' . LSX_BLOG_CUSTOMIZER_PATH . '/assets/css/scss/customizer-blog-banner-colours";

				/**
				 * LSX Customizer - Banner (LSX Blog Customizer)
				 */
				@include customizer-blog-banner-colours (
					$color-image:    	' . $colors['banner_text_image_color'] . '
				);
			';

			return $css;
		}
	}

	new LSX_Blog_Customizer_Frontend;
}
