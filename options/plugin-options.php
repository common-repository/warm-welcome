<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

$token = get_option( 'ww_token');

$fields = array_merge(
	array(
		Field::make( 'html', 'ww_logo', __( 'Logo', ' crb' ) )
			->set_html( '<img class="ww-logo" src="'. plugin_dir_url('') . '/warm-welcome/resources/assets/logo.svg" />' )
	),
	warmwelcome_get_authenticate_fields()
);

if($token) {
	warmwelcome_get_widgets_block();

	$fields = array_merge(
		$fields,
		warmwelcome_get_bubble_widget_option(),
		warmwelcome_get_widgets_list()
	);
}

Container::make( 'theme_options', __( 'Warm Welcome', 'crb' ) )
	->set_icon( 'dashicons-cake' )
	->add_fields( $fields );
