<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make( 'post_meta', __( 'Warm Welcome', 'crb' ) )
	->where( 'post_type', '=', 'page' )
	->set_context('side')
	->add_fields( array(
		Field::make( 'select', 'ww_bubble_widget', __( 'Add Bubble Widget', 'crb' )
			)->add_options(warmwelcome_get_bubble_select_field_options())
	) );