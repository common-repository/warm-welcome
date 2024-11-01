<?php

function warmwelcome_get_bubble_select_field_options() {
	$widgets = get_option( 'ww_widgets');

	if ( empty($widgets) ) {
		return array();
	}

	$bubble_options = [ 'none' => ''];

	foreach ($widgets as $index => $widget) {
		if ($widget->widgetable->__typename !== 'BubbleWidget') {
			continue;
		}

		$bubble_options[ $widget->uniqueId ] = $widget->name . ' - ' . $widget->widgetable->__typename;
	}

	return $bubble_options;
}


function warmwelcome_is_edit_page($new_edit = null){
	global $pagenow;

	//make sure we are on the backend
	if (!is_admin()) {
		return false;
	}

	if($new_edit == "edit") {
		return in_array( $pagenow, array( 'post.php',  ) );
	} elseif($new_edit == "new") {
		return in_array( $pagenow, array( 'post-new.php' ) );
	} else {
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}
}


function warmwelcome_sanitize_text_or_array_field($data) {
	foreach ($data as &$value) {
		if (is_scalar($value)) {
			$value = sanitize_text_field($value);
			continue;
		}

		$value = warmwelcome_sanitize_text_or_array_field($value);
	}

	return $data;
}