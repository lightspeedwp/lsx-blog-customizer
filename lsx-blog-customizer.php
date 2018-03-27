<?php
/*
 * Plugin Name: LSX Blog Customizer
 * Plugin URI:  https://www.lsdev.biz/product/lsx-blog-customizer/
 * Description:	This extension gives you complete control over the appearance of your LSX-powered WordPress blog
 * Version:     1.1.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-blog-customizer
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_BLOG_CUSTOMIZER_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_BLOG_CUSTOMIZER_CORE', __FILE__ );
define( 'LSX_BLOG_CUSTOMIZER_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_BLOG_CUSTOMIZER_VER', '1.1.1' );

/* ======================= The API Classes ========================= */

if ( ! class_exists( 'LSX_API_Manager' ) ) {
	require_once( 'classes/class-lsx-api-manager.php' );
}

/**
 * Runs once when the plugin is activated.
 */
function lsx_blog_customizer_activate_plugin() {
	$lsx_to_password = get_option( 'lsx_api_instance', false );

	if ( false === $lsx_to_password ) {
		update_option( 'lsx_api_instance', LSX_API_Manager::generatePassword() );
	}
}
register_activation_hook( __FILE__, 'lsx_blog_customizer_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Blog Customizer Settings.
 */
function lsx_blog_customizer_options_pages_filter( $pages ) {
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter( 'lsx_api_manager_options_pages', 'lsx_blog_customizer_options_pages_filter', 10, 1 );

function lsx_blog_customizer_api_admin_init() {
	global $lsx_blog_customizer_api_manager;

	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	$data = array(
		'api_key' => '',
		'email'   => '',
	);

	if ( false !== $options && isset( $options['api'] ) ) {
		if ( isset( $options['api']['lsx-blog-customizer_api_key'] ) && '' !== $options['api']['lsx-blog-customizer_api_key'] ) {
			$data['api_key'] = $options['api']['lsx-blog-customizer_api_key'];
		}

		if ( isset( $options['api']['lsx-blog-customizer_email'] ) && '' !== $options['api']['lsx-blog-customizer_email'] ) {
			$data['email'] = $options['api']['lsx-blog-customizer_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );

	if ( false === $instance ) {
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id' => 'LSX Blog Customizer',
		'version'    => '1.1.1',
		'instance'   => $instance,
		'email'      => $data['email'],
		'api_key'    => $data['api_key'],
		'file'       => 'lsx-blog-customizer.php',
	);

	$lsx_blog_customizer_api_manager = new LSX_API_Manager( $api_array );
}
add_action( 'admin_init', 'lsx_blog_customizer_api_admin_init' );

/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer.php' );
require_once( LSX_BLOG_CUSTOMIZER_PATH . 'includes/functions.php' );
