<?php
/*
 * Plugin Name: LSX Blog Customizer
 * Plugin URI:  https://www.lsdev.biz/product/lsx-blog-customizer/
 * Description:	This extension gives you complete control over the appearance of your LSX-powered WordPress blog
 * Version:     1.2
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
define( 'LSX_BLOG_CUSTOMIZER_VER', '1.2' );


/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_BLOG_CUSTOMIZER_PATH . 'classes/class-lsx-blog-customizer.php' );
require_once( LSX_BLOG_CUSTOMIZER_PATH . 'includes/functions.php' );
