<?php
/**
 * Functions
 *
 * @package   LSX Blog Customizer
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */

/**
 * Add our action to init to set up our vars first.
 */
function lsx_blog_customizer_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-blog-customizer', false, basename( LSX_BLOG_CUSTOMIZER_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_blog_customizer_load_plugin_textdomain' );

/**
 * Wraps the output class in a function to be called in templates.
 */
function lsx_blog_customizer_posts( $args ) {
	$lsx_blog_customizer_posts = new LSX_Blog_Customizer_Posts;
	$lsx_blog_customizer_posts->output( $args );
}

/**
 * Shortcode.
 */
function lsx_blog_customizer_shortcode( $atts ) {
	ob_start();

	$lsx_blog_customizer_posts = new LSX_Blog_Customizer_Posts;
	$lsx_blog_customizer_posts->output( $atts );

	$content = ob_get_clean();
	$content = preg_replace( '/<!--[^>]+-->/', '', $content );

	return $content;
}
add_shortcode( 'lsx_posts', 'lsx_blog_customizer_shortcode' );

/**
 * Wraps the output class in a function to be called in templates.
 */
function lsx_blog_customizer_terms( $args ) {
	$lsx_blog_customizer_terms = new LSX_Blog_Customizer_Terms;
	echo wp_kses_post( $lsx_blog_customizer_terms->output( $args ) );
}
