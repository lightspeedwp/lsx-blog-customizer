<?php
if ( ! class_exists( 'LSX_Blog_Customizer_Blog' ) ) {

	/**
	 * LSX Blog Customizer Blog Class
	 *
	 * @package   LSX Blog Customizer
	 * @author    LightSpeed
	 * @license   GPL3
	 * @link
	 * @copyright 2018 LightSpeed
	 */
	class LSX_Blog_Customizer_Blog extends LSX_Blog_Customizer {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 20 );
		}

		/**
		 * Customizer Controls and Settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since 1.0.0
		 */
		public function customize_register( $wp_customize ) {
			/**
			 * Add the blog panel
			 */
			$wp_customize->add_panel( 'lsx_blog_customizer_blog_panel', array(
				'priority'       => 61,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => esc_html__( 'Blog', 'lsx-blog-customizer' ),
				'description'    => esc_html__( 'Customize the appearance of your blog posts and archives.', 'lsx-blog-customizer' ),
			) );

			/**
			 * General section
			 */
			$wp_customize->add_section( 'lsx_blog_customizer_general' , array(
				'title'       => esc_html__( 'General', 'lsx-blog-customizer' ),
				'priority'    => 10,
				'description' => esc_html__( 'Customize the look & feel of the blog archives and blog post pages:', 'lsx-blog-customizer' ),
				'panel'       => 'lsx_blog_customizer_blog_panel',
			) );

			/**
			 * Main blog page section
			 */
			$wp_customize->add_section( 'lsx_blog_customizer_main_blog_page' , array(
				'title'       => esc_html__( 'Main blog page', 'lsx-blog-customizer' ),
				'priority'    => 30,
				'description' => esc_html__( 'Customize the look & feel of the main blog page:', 'lsx-blog-customizer' ),
				'panel'       => 'lsx_blog_customizer_blog_panel',
			) );

			/**
			 * Blog archives section
			 */
			$wp_customize->add_section( 'lsx_blog_customizer_archive' , array(
				'title'       => esc_html__( 'Archives', 'lsx-blog-customizer' ),
				'priority'    => 40,
				'description' => esc_html__( 'Customize the look & feel of the blog archives:', 'lsx-blog-customizer' ),
				'panel'       => 'lsx_blog_customizer_blog_panel',
			) );

			/**
			 * Single blog post section
			 */
			$wp_customize->add_section( 'lsx_blog_customizer_single' , array(
				'title'       => esc_html__( 'Single posts', 'lsx-blog-customizer' ),
				'priority'    => 50,
				'description' => esc_html__( 'Customize the look & feel of the blog post pages:', 'lsx-blog-customizer' ),
				'panel'       => 'lsx_blog_customizer_blog_panel',
			) );

			/**
			 * General section: display date
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_general_date', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_general_date', array(
				'label'       => esc_html__( 'Display date', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display post date in blog archives and blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_general',
				'settings'    => 'lsx_blog_customizer_general_date',
				'type'        => 'checkbox',
				'priority'    => 10,
			) ) );

			/**
			 * General section: display author
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_general_author', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_general_author', array(
				'label'       => esc_html__( 'Display author', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display post author in blog archives and blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_general',
				'settings'    => 'lsx_blog_customizer_general_author',
				'type'        => 'checkbox',
				'priority'    => 20,
			) ) );

			/**
			 * General section: display categories
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_general_category', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_general_category', array(
				'label'       => esc_html__( 'Display categories', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display post categories in blog archives and blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_general',
				'settings'    => 'lsx_blog_customizer_general_category',
				'type'        => 'checkbox',
				'priority'    => 30,
			) ) );

			/**
			 * General section: display tags
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_general_tags', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_general_tags', array(
				'label'       => esc_html__( 'Display tags', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display post tags in blog archives and blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_general',
				'settings'    => 'lsx_blog_customizer_general_tags',
				'type'        => 'checkbox',
				'priority'    => 30,
			) ) );

			$wp_customize->add_setting(
				'lsx_blog_customizer_general_placeholder',
				array(
					'default'           => '',
					'sanitize_callback' => 'wp_kses_post',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'blog_customizer_posts_placeholder',
					array(
						'label'       => __( 'Fallback Image', 'lsx-blog-customizer' ),
						'section'     => 'lsx_blog_customizer_general',
						'settings'    => 'lsx_blog_customizer_general_placeholder',
						'priority'    => 40,
						'description' => esc_html__( 'The selected image will be used when a post / page is missing a featured image. A default fallback image included in the theme will be used if no image is set.', 'lsx-blog-customizer' ),
					)
				)
			);

			/**
			 *  Main blog page section: description
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_main_blog_page_description', array(
				'default'           => '',
				'sanitize_callback' => array( $this, 'sanitize_textarea' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_main_blog_page_description', array(
				'label'       => esc_html__( 'Description', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Description visible before posts list.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_main_blog_page',
				'settings'    => 'lsx_blog_customizer_main_blog_page_description',
				'type'        => 'textarea',
				'priority'    => 10,
			) ) );

			/**
			 * Main blog page section (carousel): full width
			 */
			$categories = get_categories();

			foreach ( $categories as $counter => $category ) {
				$wp_customize->add_setting( 'lsx_blog_customizer_main_blog_page_carousel_' . $category->term_id, array(
					'default'           => false,
					'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_main_blog_page_carousel_' . $category->term_id, array(
					'label'    => esc_html( $category->name ),
					'section'  => 'lsx_blog_customizer_main_blog_page',
					'settings' => 'lsx_blog_customizer_main_blog_page_carousel_' . $category->term_id,
					'type'     => 'checkbox',
					'priority' => ( ( $counter + 1 ) * 10 ),
				) ) );
			}

			/**
			 * Blog archives section: full width
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_archive_full_width', array(
				'default'           => false,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_archive_full_width', array(
				'label'       => esc_html__( 'Full width', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display blog archives in a full width layout.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_archive',
				'settings'    => 'lsx_blog_customizer_archive_full_width',
				'type'        => 'checkbox',
				'priority'    => 10,
			) ) );

			/**
			 * Blog archives section: text
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_archive_text', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_archive_text', array(
				'label'       => esc_html__( 'Excerpt/content', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display excerpt/content on blog archives.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_archive',
				'settings'    => 'lsx_blog_customizer_archive_text',
				'type'        => 'checkbox',
				'priority'    => 20,
			) ) );

			/**
			 * Blog archives section: full content
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_archive_full_content', array(
				'default'           => false,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_archive_full_content', array(
				'label'       => esc_html__( 'Full content', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display full content on blog archives.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_archive',
				'settings'    => 'lsx_blog_customizer_archive_full_content',
				'type'        => 'checkbox',
				'priority'    => 30,
			) ) );

			/**
			 * Blog archives section: front-end layout switcher
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_archive_layout_switcher', array(
				'default'           => false,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_archive_layout_switcher', array(
				'label'       => esc_html__( 'Front-end layout switcher', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display layout switcher (list X grid) on front-end.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_archive',
				'settings'    => 'lsx_blog_customizer_archive_layout_switcher',
				'type'        => 'checkbox',
				'priority'    => 40,
			) ) );

			/**
			 * Blog archives section: layout
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_archive_layout', array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_select_layout_switcher' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_archive_layout', array(
				'label'       => esc_html__( 'Layout', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Default layout on blog archives.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_archive',
				'settings'    => 'lsx_blog_customizer_archive_layout',
				'type'        => 'select',
				'priority'    => 50,
				'choices'     => array(
					'default'   => esc_html__( 'Default', 'lsx-blog-customizer' ),
					'list'      => esc_html__( 'List', 'lsx-blog-customizer' ),
					'grid'      => esc_html__( '3 Columns Grid', 'lsx-blog-customizer' ),
					'half-grid' => esc_html__( '2 Columns Grid', 'lsx-blog-customizer' ),
				),
			) ) );

			/**
			 * Single blog post section: full width
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_single_full_width', array(
				'default'           => false,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_single_full_width', array(
				'label'       => esc_html__( 'Full width', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Give the single blog post pages a full width layout.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_single',
				'settings'    => 'lsx_blog_customizer_single_full_width',
				'type'        => 'checkbox',
				'priority'    => 10,
			) ) );

			/**
			 * Single blog post section: display posts navigation
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_single_posts_navigation', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_single_posts_navigation', array(
				'label'       => esc_html__( 'Display posts navigation', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display posts navigation in blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_single',
				'settings'    => 'lsx_blog_customizer_single_posts_navigation',
				'type'        => 'checkbox',
				'priority'    => 30,
			) ) );

			/**
			 * Single blog post section: display related posts
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_single_related_posts', array(
				'default'           => true,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_single_related_posts', array(
				'label'       => esc_html__( 'Display related posts', 'lsx-blog-customizer' ),
				'description' => esc_html__( 'Display related posts in blog post pages.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_single',
				'settings'    => 'lsx_blog_customizer_single_related_posts',
				'type'        => 'checkbox',
				'priority'    => 40,
			) ) );

			/**
			 * Single Blog Post: Posts Relation
			 */
			$wp_customize->add_setting( 'lsx_blog_customizer_single_posts_relation', array(
				'default'           => 'both',
				'sanitize_callback' => array( $this, 'sanitize_select_posts_relation' ),
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'lsx_blog_customizer_single_posts_relation', array(
				'description' => esc_html__( 'Select the taxonomy to use for the related posts.', 'lsx-blog-customizer' ),
				'section'     => 'lsx_blog_customizer_single',
				'settings'    => 'lsx_blog_customizer_single_posts_relation',
				'type'        => 'select',
				'priority'    => 50,
				'choices'     => array(
					'both'   => esc_html__( 'Both', 'lsx-blog-customizer' ),
					'post_tag'  => esc_html__( 'Tag', 'lsx-blog-customizer' ),
					'category'  => esc_html__( 'Category', 'lsx-blog-customizer' ),
				),
			) ) );

		}

	}
	new LSX_Blog_Customizer_Blog;
}
