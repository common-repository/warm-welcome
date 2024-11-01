<?php

add_action( 'wp_ajax_ww_save_token', 'warmwelcome_save_token' );
add_action( 'wp_ajax_nopriv_ww_save_token', 'warmwelcome_save_token' );
function warmwelcome_save_token(){
	if ( ! wp_verify_nonce( $_POST['wp_nonce'], 'ww-ajax-nonce' ) ) {
		wp_send_json_error();
	}

	update_option('ww_token', sanitize_text_field($_POST['token']));
	update_option('ww_user', sanitize_email($_POST['user']));
	update_option('ww_widgets', array());

	wp_send_json( array(
		'success' => true,
		'token' => get_option('ww_token'),
	) );
}

add_action( 'wp_ajax_ww_save_widgets', 'warmwelcome_save_widgets' );
add_action( 'wp_ajax_nopriv_ww_save_widgets', 'warmwelcome_save_widgets' );
function warmwelcome_save_widgets(){
	if ( ! wp_verify_nonce( $_POST['wp_nonce'], 'ww-ajax-nonce' ) ) {
		wp_send_json_error();
	}

	$widgets = json_decode(stripslashes($_POST['widgets']));

	update_option('ww_widgets', warmwelcome_sanitize_text_or_array_field($widgets));
	update_option('ww_global_widget_id', sanitize_text_field($_POST['globalWidgetId']));

	wp_send_json( array(
		'success' => true,
		'widgets' => get_option('ww_widgets'),
		'globalWidgetId' => get_option('ww_global_widget_id'),
	) );
}

add_action( 'wp_ajax_ww_save_bubble_widget', 'warmwelcome_save_bubble_widget' );
add_action( 'wp_ajax_nopriv_ww_save_bubble_widget', 'warmwelcome_save_bubble_widget' );
function warmwelcome_save_bubble_widget(){
	if ( ! wp_verify_nonce( $_POST['wp_nonce'], 'ww-ajax-nonce' ) ) {
		wp_send_json_error();
	}

	update_option('_crb_default_bubble_widget', sanitize_text_field($_POST['id']));

	wp_send_json( array(
		'success' => true,
		'id' => get_option('_crb_default_bubble_widget'),
	) );
}
