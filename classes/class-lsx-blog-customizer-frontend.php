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
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 6 );
			add_action( 'wp', array( $this, 'layout' ), 999 );
			add_action( 'wp_head', array( $this, 'wp_head' ), 10 );

			if ( is_admin() ) {
				add_filter( 'lsx_customizer_colour_selectors_body', array( $this, 'customizer_body_colours_handler' ), 15, 2 );
				add_filter( 'lsx_customizer_colour_selectors_banner', array( $this, 'customizer_banner_colours_handler' ), 15, 2 );
			}

			add_action( 'lsx_content_wrap_before', array( $this, 'terms_single_banner' ) );

			remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
			add_filter( 'get_the_excerpt', array( $this, 'custom_wp_trim_excerpt' ) );

			add_filter( 'has_post_thumbnail', array( $this, 'has_post_thumbnail_placeholder' ), 20, 3 );
			add_filter( 'lsx_get_thumbnail_post_placeholder_id', array( $this, 'replace_post_thumbnail_id' ), 10, 2 );
		}

		/**
		 * Enqueues the assets.
		 *
		 * @since 1.0.0
		 */
		public function assets() {
			$has_slick = wp_script_is( 'slick', 'queue' );

			if ( ! $has_slick ) {
				wp_enqueue_style( 'slick', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/vendor/slick.css', array(), LSX_BLOG_CUSTOMIZER_VER, null );
				wp_enqueue_script( 'slick', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), null, LSX_BLOG_CUSTOMIZER_VER, true );
			}

			if ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
				$prefix = 'src/';
				$suffix = '';
			} else {
				$prefix = '';
				$suffix = '.min';
			}
			wp_enqueue_script( 'lsx_blog_customizer', LSX_BLOG_CUSTOMIZER_URL . 'assets/js/' . $prefix . 'lsx-blog-customizer' . $suffix . '.js', array( 'jquery', 'slick' ), LSX_BLOG_CUSTOMIZER_VER, true );

			$params = apply_filters( 'lsx_blog_customizer_js_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));

			wp_localize_script( 'lsx_blog_customizer', 'lsx_blog_customizer_params', $params );

			wp_enqueue_style( 'lsx-blog-customizer', LSX_BLOG_CUSTOMIZER_URL . 'assets/css/lsx-blog-customizer.css', array(), LSX_BLOG_CUSTOMIZER_VER );
			wp_style_add_data( 'lsx-blog-customizer', 'rtl', 'replace' );
		}

		/**
		 * The function which registers all of the layout actions.
		 *
		 * @return void
		 */
		public function wp_head() {
			add_filter( 'body_class', array( $this, 'body_class' ), 10 );
			add_filter( 'post_class', array( $this, 'post_class' ), 10 );

			add_action( 'lsx_banner_inner_bottom', array( $this, 'archive_layout_switcher' ), 90 );
			add_action( 'lsx_blog_customizer_banner_top', array( $this, 'archive_layout_switcher' ), 90 );
			add_action( 'lsx_global_header_inner_bottom', array( $this, 'archive_layout_switcher' ), 90 );

			$top_of_blog_action = apply_filters( 'lsx_blog_customizer_top_of_blog_action', 'lsx_content_top' );
			if ( false !== $top_of_blog_action && '' !== $top_of_blog_action ) {
				add_action( $top_of_blog_action, array( $this, 'main_blog_page_description' ), 120 );
				add_action( $top_of_blog_action, array( $this, 'main_blog_page_carousel' ), 130 );
				add_action( $top_of_blog_action, array( $this, 'category_blog_page_title' ), 130 );
			}
 
			$archive_custom_image    = get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_image', true );
			$archive_custom_title    = get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_title', true );
			$archive_custom_subtitle = get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_tagline', true );
			$archive_custom_icon     = get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_icon_image', true );

			if ( ( false !== $archive_custom_image && '' !== $archive_custom_image ) || ( false !== $archive_custom_title && '' !== $archive_custom_title ) || ( false !== $archive_custom_subtitle && '' !== $archive_custom_subtitle ) || ( false !== $archive_custom_icon && '' !== $archive_custom_icon )) {
				remove_action( 'lsx_content_wrap_before', 'lsx_global_header' );
			}

			add_action( 'lsx_content_after', array( $this, 'single_blog_page_related_posts' ), 20 );
		}

		/**
		 * Layout.
		 *
		 * @since 1.0.0
		 */
		public function layout() {
			$body_classes = get_body_class();

			$is_archive                = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_single_post            = is_singular( 'post' );
			$is_archive_or_single_post = $is_archive || $is_single_post;

			$general_date     = get_theme_mod( 'lsx_blog_customizer_general_date', true );
			$general_author   = get_theme_mod( 'lsx_blog_customizer_general_author', true );
			$general_category = get_theme_mod( 'lsx_blog_customizer_general_category', true );
			$general_tags     = get_theme_mod( 'lsx_blog_customizer_general_tags', true );

			$archive_text         = get_theme_mod( 'lsx_blog_customizer_archive_text', true );
			$archive_full_content = get_theme_mod( 'lsx_blog_customizer_archive_full_content', false );
			$archive_layout       = $this->get_layout_value_from_cookie();

			$single_related_posts = get_theme_mod( 'lsx_blog_customizer_single_related_posts', true );

			if ( $is_archive_or_single_post && false === $general_date ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_date', 10 );
				remove_action( 'lsx_post_meta_top', 'lsx_post_meta_date', 10 );
			}

			if ( $is_archive_or_single_post && false === $general_author ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_author', 20 );
				remove_action( 'lsx_post_meta_top', 'lsx_post_meta_author', 10 );
				remove_action( 'lsx_post_meta_top', 'lsx_post_meta_avatar', 10 );
			}

			if ( $is_archive_or_single_post && false === $general_category ) {
				remove_action( 'lsx_content_post_meta', 'lsx_post_meta_category', 30 );
			}

			if ( $is_archive_or_single_post && false === $general_tags ) {
				remove_action( 'lsx_content_post_tags', 'lsx_post_tags', 10 );
			}

			if ( $is_archive && false === $archive_text ) {
				add_filter( 'lsx_blog_display_text_on_list', '__return_false' );
			}

			if ( $is_archive && true === $archive_full_content ) {
				add_filter( 'lsx_blog_force_content_on_list', '__return_true' );
			}

			if ( $is_archive ) {
				if ( 'grid' === $archive_layout ) {
					add_filter( 'lsx_blog_layout',
						function( $layout ) {
							return 'grid';
						}
					);
				} elseif ( 'half-grid' === $archive_layout ) {
					add_filter( 'lsx_blog_layout',
						function( $layout ) {
							return 'half-grid';
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
		}

		/**
		 * Body class.
		 *
		 * @param array $classes the classes applied to the body tag.
		 * @return array $classes the classes applied to the body tag.
		 * @since 1.0.0
		 */
		public function body_class( $body_classes ) {
			$is_archive                = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_author                 = in_array( 'author', $body_classes );
			$is_single_post            = is_singular( 'post' );
			$is_archive_or_single_post = $is_archive || $is_single_post;
			$general_date              = (bool) get_theme_mod( 'lsx_blog_customizer_general_date', true );
			$general_author            = (bool) get_theme_mod( 'lsx_blog_customizer_general_author', true );
			$general_category          = (bool) get_theme_mod( 'lsx_blog_customizer_general_category', true );
			$general_tags              = (bool) get_theme_mod( 'lsx_blog_customizer_general_tags', true );
			$archive_full_width        = (bool) get_theme_mod( 'lsx_blog_customizer_archive_full_width', false );
			$archive_layout            = $this->get_layout_value_from_cookie();
			$single_full_width         = (bool) get_theme_mod( 'lsx_blog_customizer_single_full_width', false );
			$single_posts_navigation   = (bool) get_theme_mod( 'lsx_blog_customizer_single_posts_navigation', true );

			if ( $is_archive_or_single_post && false === $general_date ) {
				$body_classes[] = 'lsx-hide-post-date';
			}

			if ( $is_archive_or_single_post && false === $general_author ) {
				$body_classes[] = 'lsx-hide-post-author';
			}

			if ( $is_archive_or_single_post && false === $general_category ) {
				$body_classes[] = 'lsx-hide-post-category';
			}

			if ( $is_archive_or_single_post && false === $general_tags ) {
				$body_classes[] = 'lsx-hide-post-tags';
			}

			if ( $is_archive && true === $archive_full_width ) {
				$body_classes[] = 'lsx-body-full-width';
			}

			if ( $is_archive || $is_author ) {
				if ( 'grid' === $archive_layout ) {
					$body_classes[] = 'lsx-body-grid-layout';
				} elseif ( 'half-grid' === $archive_layout ) {
					$body_classes[] = 'lsx-body-half-grid-layout';
				} elseif ( 'list' === $archive_layout ) {
					$body_classes[] = 'lsx-body-list-layout';
				} else {
					$body_classes[] = 'lsx-body-default-layout';
				}
			}

			if ( $is_single_post && true === $single_full_width ) {
				$body_classes[] = 'lsx-body-full-width';
			}

			if ( $is_single_post && false === $single_posts_navigation ) {
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
			$body_classes = get_body_class();

			$is_archive                = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date() || is_search();
			$is_single_post            = is_singular( 'post' );
			$is_archive_or_single_post = $is_archive || $is_single_post;

			$general_tags = (bool) get_theme_mod( 'lsx_blog_customizer_general_tags', true );

			if ( $is_single_post && false === $general_tags ) {
				if ( ! class_exists( 'LSX_Sharing' ) && ! function_exists( 'sharing_display' ) && ! class_exists( 'Jetpack_Likes' ) ) {
					$classes[] = 'lsx-hide-post-tags';
				}
			} elseif ( $is_archive && false === $general_tags ) {
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

			if ( in_array( 'blog', $body_classes ) && ( ! in_array( 'search', $body_classes ) ) ) {
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
			$body_classes            = get_body_class();
			$is_archive              = in_array( 'blog', $body_classes ) || is_post_type_archive( 'post' ) || is_category() || is_tag() || is_date();
			$archive_layout_switcher = (bool) get_theme_mod( 'lsx_blog_customizer_archive_layout_switcher', false );

			$show_switcher           = false;
			if ( $is_archive && true === $archive_layout_switcher ) {
				$show_switcher = true;
			}
			$show_switcher = apply_filters( 'lsx_blog_customizer_show_switcher', $show_switcher );

			if ( $is_archive && true === $show_switcher ) {
				$page_key       = apply_filters( 'lsx_layout_switcher_page_key', 'blog' );
				$archive_layout = $this->get_layout_value_from_cookie( $page_key );

				if ( empty( locate_template( array( 'lsx-blog-customizer/partials/modules/archive-layout-switcher.php' ) ) ) ) {
					include LSX_BLOG_CUSTOMIZER_PATH . 'partials/modules/archive-layout-switcher.php';
				} else {
					include locate_template( array( 'lsx-blog-customizer/partials/modules/archive-layout-switcher.php' ) );
				}
			}
		}

		/**
		 * Outputs the single listing banner
		 *
		 * @return void
		 */
		public function terms_single_banner() {
			if ( is_archive() && ( is_category() || is_tag() ) ) {
				$args = array(
					'image'    => get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_image', true ),
					'title'    => get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_title', true ),
					'subtitle' => get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_banner_tagline', true ),
					'icon'     => get_term_meta( get_queried_object_id(), 'lsx_customizer_post_term_icon_image', true ),
				);
				if ( $args['image'] || $args['title'] || $args['subtitle'] || $args['icon'] ) {
					$this->blog_customizer_do_banner( $args );
				}
			}
		}

		/**
		 * Outputs the banners based on the arguments.
		 *
		 * @param array $args The parameters for the banner
		 * @return void
		 */
		public function blog_customizer_do_banner( $args = array() ) {

			$defaults = array(
				'image'    => '',
				'title'    => '',
				'subtitle' => '',
				'icon'     => '',
			);
			$args     = wp_parse_args( $args, $defaults );

			$background_image = '';
			$icon_image       = '';

			// Generate the background atts.
			$background_image_attr = '';
			$icon_image_attr       = '';
			$background_class      = '';
			$background_image_attr = $args['image'];
			$icon_image_attr       = $args['icon'];
			$title_attr            = apply_filters( 'lsx_global_header_title', get_the_archive_title() );
			$subtitle_attr         = apply_filters( 'lsx_global_header_description', get_the_archive_description() );

			if ( '' !== $background_image_attr && false !== $background_image_attr ) {
				$background_image = 'background-image:url(' . $background_image_attr . ')';
				$background_class = 'banner-has-custom-bg';
			}
			if ( '' !== $icon_image_attr && false !== $icon_image_attr ) {
				$icon_image = '<img class="banner-icon" src="' . $icon_image_attr . '" alt="icon"/>';
			}
			if ( $args['title'] ) {
				$title_attr = $args['title'];
			}
			if ( $args['subtitle'] ) {
				$subtitle_attr = '<p>' . $args['subtitle'] . '</p>';
			}
			if ( $args['image'] || $args['title'] || $args['subtitle'] || $args['icon'] ) {
				?>
				<div class="archive-header-wrapper custom-banner-archive banner-archive col-md-12">
					<div class="archive-header <?php echo esc_attr( $background_class ); ?>" style="<?php echo esc_attr( $background_image ); ?>">
					<?php do_action( 'lsx_blog_customizer_banner_top' ); ?>
						<?php
						if ( '' !== $icon_image && false !== $icon_image ) { 
							echo wp_kses_post( $icon_image );
						}
						?>
						<?php if ( '' !== $title_attr && false !== $title_attr ) { ?>
							<h1 class="archive-title"><?php echo wp_kses_post( $title_attr ); ?></h1>
						<?php } ?>
						<?php if ( '' !== $subtitle_attr && false !== $subtitle_attr ) { ?>
							<?php echo wp_kses_post( $subtitle_attr ); ?>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Display (or not) the categories carousel on main blog page
		 *
		 * @since 1.0.0
		 */
		public function category_blog_page_title() {
			$body_classes = get_body_class();

			if ( in_array( 'category', $body_classes ) || in_array( 'tag', $body_classes ) ) {
				?>
				<div class="archive-category-title">
					<a class="back-to-blog" href="<?php echo ( esc_url( get_post_type_archive_link( 'post' ) ) ); ?>"><?php echo esc_html__( 'Back To Blog', 'lsx' ); ?></a>
				</div>
				<?php
			}
		}

		/**
		 * Get layout value from cookie
		 *
		 * @since 1.0.0
		 */
		public function get_layout_value_from_cookie( $page_key = 'blog' ) {
			$archive_layout = get_theme_mod( 'lsx_blog_customizer_archive_layout', 'default' );
			$archive_layout = apply_filters( 'lsx_layout_switcher_options_default', $archive_layout );

			if ( isset( $_COOKIE[ 'lsx-' . $page_key . '-layout' ] ) ) {
				$archive_layout_from_cookie = sanitize_key( $_COOKIE[ 'lsx-' . $page_key . '-layout' ] );
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
			$single_related_posts = (bool) get_theme_mod( 'lsx_blog_customizer_single_related_posts', true );
			$post_relation        = get_theme_mod( 'lsx_blog_customizer_single_posts_relation', 'both' );

			if ( $is_single_post && true === $single_related_posts ) {
				$post_id        = get_the_ID();
				$post_ids       = array();
				$posts_per_page = 3;
				$post__not_in   = array( $post_id );
				//$related_query  = get_transient( 'lsx_related_query_WP_Query_' . $post_id );
				$tags           = array();
				$categories     = array();

				if ( ! is_object( $related_query ) || ! is_a( $related_query, 'WP_Query' ) ) {

					$related_taxonomies = apply_filters( 'lsx_blog_customizer_related_posts_taxonomies', array( 'post_tag', 'category' ) );

					foreach ( $related_taxonomies as $related_taxonomy ) {
						if ( in_array( $post_relation, array( $related_taxonomy, 'both' ) ) ) {
							$tags = wp_get_object_terms( $post_id, $related_taxonomy );
							if ( ! empty( $tags ) ) {
								$tag_ids = wp_list_pluck( $tags, 'term_id' );

								if ( 'category' === $related_taxonomy ) {
									$primary_id = get_post_meta( $post_id, '_yoast_wpseo_primary_category', true );
									if ( false !== $primary_id && '' !== $primary_id ) {
										$tag_ids = array( $primary_id );
									}
								}

								$args = array(
									'fields'                 => 'ids',
									'post__not_in'           => $post__not_in,
									'posts_per_page'         => $posts_per_page,
									'orderby'                => 'rand',
									'order'                  => 'DESC',
									'no_found_rows'          => true,
									'ignore_sticky_posts'    => 1,
									'update_post_meta_cache' => false,
									'update_post_meta_cache' => false,
								);

								if ( 'post_tag' ===$related_taxonomy ) {
									$args['tag__in'] = $tag_ids;
								} else if ( 'category' ===$related_taxonomy ) {
									$args['category__in'] = $tag_ids;
								}

								$related_query_1 = new \WP_Query( $args );

								if ( isset( $related_query_1->posts ) ) {
									$post_ids = array_merge( $post_ids, $related_query_1->posts );
								}
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

			if ( in_array( 'blog', $body_classes ) && ! is_search() ) {
				$description = get_theme_mod( 'lsx_blog_customizer_main_blog_page_description', false );
				$description = apply_filters( 'lsx_blog_customizer_main_blog_page_description', $description );
				if ( false !== $description && '' !== $description && '0' !== $description ) {
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

		/**
		 * Allow HTML tags in excerpt.
		 *
		 * @package    lsx
		 * @subpackage extras
		 */
		public function custom_wp_trim_excerpt( $wpse_excerpt ) {
			global $post;
			$raw_excerpt = $wpse_excerpt;

			if ( empty( $wpse_excerpt ) ) {
				$wpse_excerpt = get_the_content( '' );

				$post_formats = array(
					'aside'   => 'aside',
					'gallery' => 'gallery',
					'link'    => 'link',
					'image'   => 'image',
					'quote'   => 'quote',
					'status'  => 'status',
					'video'   => 'video',
					'audio'   => 'audio',
				);

				$show_full_content = has_post_format( apply_filters( 'lsx_excerpt_read_more_post_formats', $post_formats ) );

				if ( ! $show_full_content ) {
					$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
					$wpse_excerpt = apply_filters( 'the_content', $wpse_excerpt );
					$wpse_excerpt = str_replace( ']]>', ']]>', $wpse_excerpt );
					$wpse_excerpt = strip_tags( $wpse_excerpt, apply_filters( 'excerpt_strip_tags', '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<a>,<button>,<blockquote>,<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>,<div>' ) );

					$excerpt_word_count = 50;
					$excerpt_word_count = apply_filters( 'excerpt_length', $excerpt_word_count );

					$tokens         = array();
					$excerpt_output = '';
					$has_more       = false;
					$count          = 0;

					preg_match_all( '/^(<[^>]+>|[^<>\s]+)\s*$/u', $wpse_excerpt, $tokens );

					foreach ( $tokens[0] as $token ) {
						if ( $count >= $excerpt_word_count ) {
							$excerpt_output .= trim( $token );
							$has_more        = true;
							break;
						}

						++$count;
						$excerpt_output .= $token;
					}

					$wpse_excerpt = trim( force_balance_tags( $excerpt_output ) );

					if ( $has_more ) {
						$excerpt_end = '<a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'More', 'lsx' ) . '</a>';
						$excerpt_end = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

						$pos = strrpos( $wpse_excerpt, '</' );

						if ( false !== $pos ) {
							// Inside last HTML tag.
							$wpse_excerpt = substr_replace( $wpse_excerpt, $excerpt_end, $pos, 0 ); /* Add read more next to last word */
						} else {
							// After the content.
							$wpse_excerpt .= $excerpt_end; /*Add read more in new paragraph */
						}
					}
				} else {
					$wpse_excerpt = apply_filters( 'the_content', $wpse_excerpt );
					$wpse_excerpt = str_replace( ']]>', ']]>', $wpse_excerpt );
					$wpse_excerpt = trim( force_balance_tags( $wpse_excerpt ) );
				}

				return $wpse_excerpt;
			}

			return apply_filters( 'lsx_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt );
		}

		/**
		 * Makes sure the blog placeholder is know about.
		 *
		 * @param     boolean $has_thumbnail
		 * @param     object $post
		 * @param     int $thumbnail_id
		 * @return     boolean
		 */
		public function has_post_thumbnail_placeholder( $has_thumbnail, $post, $thumbnail_id ) {
			if ( in_array( get_post_type( $post ), array( 'post', 'page' ) ) && $this->get_placeholder() ) {
				$has_thumbnail = true;
			}
			return $has_thumbnail;
		}
		/**
		 * Makes sure the blog placeholder is know about.
		 *
		 * @param     boolean $thumbnail_id
		 * @param     object $post_id
		 * @return    boolean
		 */
		public function replace_post_thumbnail_id( $thumbnail_id, $post_id ) {
			if ( in_array( get_post_type( $post_id ), array( 'post', 'page' ) ) && $this->get_placeholder_id() ) {
				$thumbnail_id = $this->get_placeholder_id();
			}
			return $thumbnail_id;
		}
	}
	new LSX_Blog_Customizer_Frontend();
}
