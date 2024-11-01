<?php

/**
 * Widget Shortcode
 */
add_shortcode( 'ww-widget', 'ww_widget_shortcode' );
function ww_widget_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'id' => 0,
		),
		$atts, 'ww-widget'
	);

	$widgets = get_option( 'ww_widgets');

	if ( empty( $widgets ) ) {
		return '';
	}

	$widget = array_filter( $widgets, function ($widget) use ($atts) {
		return $widget->id === $atts['id'];
	} );

	if ( empty( $widget ) ) {
		return '';
	}

	$widget = array_shift($widget);

	switch ($widget->widgetable->__typename) {
		case 'BusinessCardWidget':
			$type = 'business-card-widget';
			break;

		case 'SignatureWidget':
			$type = 'signature-widget';
			break;

		case 'PageWidget':
			$type = 'page-widget';
			break;

		default:
			$type = '';
			break;
	}

	if ( ! $type ) {
		return '';
	}

	ob_start();

	warmwelcome_load_fragment( $type, array( 'widget' => $widget ) );

	return ob_get_clean();
}