<?php

/*
 * Plugin Name: Warm Welcome
 * Plugin URI: https://www.warmwelcome.com/
 * Description: Add Warm Welcome bubble, signature, business card and page widgets to your pages.
 * Version: 1.0.3
 * Author: Warm Welcome
 * License: GPLv2 or later
 */

define('WARM_WELCOME_PLUGIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WARMWELCOME_API_URL', 'https://app.warmwelcome.com');

add_action( 'after_setup_theme', 'warmwelcome_add_plugin_options', 100 );
function warmwelcome_add_plugin_options() {
	# Autoload dependencies
	$autoload_dir = WARM_WELCOME_PLUGIN_DIR . 'vendor/autoload.php';
	if ( ! is_readable( $autoload_dir ) ) {
		wp_die( __( 'Please, run <code>composer install</code> to download and install the theme dependencies.', 'crb' ) );
	}

	include_once( $autoload_dir );
	\Carbon_Fields\Carbon_Fields::boot();

	# Add Actions
	add_action( 'carbon_fields_register_fields', 'warmwelcome_attach_plugin_options', 100 );

	# Attach custom shortcodes
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/helpers.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/ajax.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/utils.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/bubble-widget.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/fields.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'includes/save-post.php' );
}

add_action( 'admin_enqueue_scripts', 'warmwelcome_admin_enqueue_scripts' );
# Enqueue JS and CSS assets on the admin
function warmwelcome_admin_enqueue_scripts( $hook ) {
	# Enqueue Custom JS files
	wp_enqueue_script( 'ww-js-bundle', plugins_url( 'dist/js/bundle.js', __FILE__ ), array( 'jquery' ), null, true );

	# Enqueue Custom CSS files
	wp_enqueue_style( 'ww-admin-styles', plugins_url( 'admin-style.css', __FILE__ ), array() );

	$localize_data = array(
		'ww_api_url' => WARMWELCOME_API_URL,
		'ww_ajax_nonce' => wp_create_nonce('ww-ajax-nonce'),
	);

	if ( $token = get_option('ww_token') ) {
		$localize_data[ 'ww_token' ] = $token;
	}

	if ( carbon_get_theme_option('crb_default_bubble_widget') ) {
		$localize_data[ 'ww_widgets_data' ] = array(
			'global_widget_url' => get_option('ww_global_widget_id'),
			'widgets' => get_option('ww_widgets'),
			'site_url' => site_url(),
		);
	}

	wp_localize_script( 'ww-js-bundle', 'ww_data', $localize_data );

	if (warmwelcome_is_edit_page()){
		global $post;

		wp_localize_script( 'ww-js-bundle', 'ww_page_data', array(
			'link' => get_permalink($post),
		));
	 }

}

# Enqueue JS and CSS assets on the front-end
add_action( 'wp_enqueue_scripts', 'warmwelcome_wp_enqueue_assets' );
function warmwelcome_wp_enqueue_assets() {
	# Enqueue Custom CSS files
	wp_enqueue_style( 'ww-styles', plugins_url( 'style.css', __FILE__ ), array() );

	# Enqueue Custom JS files
	wp_enqueue_script( 'ww-js-bubble', 'https://d7a97ajcmht8v.cloudfront.net/production/app.js', array( 'jquery' ), null, true );
}

function warmwelcome_attach_plugin_options() {
	# Attach fields
	include_once( WARM_WELCOME_PLUGIN_DIR . 'options/plugin-options.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'options/plugin-meta.php' );
	include_once( WARM_WELCOME_PLUGIN_DIR . 'options/shortcodes.php' );
}

register_deactivation_hook( __FILE__, 'warmwelcome_plugin_deactivate' );
function warmwelcome_plugin_deactivate() {
	delete_option( 'ww_token' );
	delete_option( 'ww_user' );
	delete_option( 'ww_widgets' );
}
