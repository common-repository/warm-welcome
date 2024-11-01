<?php

function warmwelcome_load_fragment( $template_name, $data = array() ) {
	extract( $data );
	$template = $template_name . '.php';
	$file = WARM_WELCOME_PLUGIN_DIR . '/templates/' . $template;

	require $file;
}
