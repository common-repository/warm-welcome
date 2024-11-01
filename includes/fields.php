<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

function warmwelcome_get_authenticate_fields() {
	$token = get_option( 'ww_token');
	$user = get_option( 'ww_user');

	if ($token) {
		return array(
			Field::make( 'html', 'ww_authenticate_log_out', __( 'Sign Out', ' crb' ) )->set_html( '<p>You are logged in as <b>' . $user . '</b><a href="#" class="ww-log-out">Sign Out</a></p>' )
		);
	}

	return array(
		Field::make( 'separator', 'ww_api_url_sep', __( 'Login', 'crb' ) ),
		Field::make( 'text', 'ww_email', __( 'Email', 'crb' ) )
			->set_attribute( 'type', 'email' ),
		Field::make( 'text', 'ww_password', __( 'Password', 'crb' ) )
			->set_attribute( 'type', 'password' ),
		Field::make( 'html', 'ww_authenticate', __( 'Authenticate', ' crb' ) )
			->set_html( '<a href="#" class="button button-primary button-large ww-log-in">Authenticate</a>')
	);
}

function warmwelcome_get_widgets_block() {
	$widgets = get_option( 'ww_widgets');

	if ( empty( $widgets ) ) {
		return;
	}

	$options = array();

	foreach ($widgets as $widget) {
		if ($widget->widgetable->__typename === 'BubbleWidget') {
			continue;
		}

		$options[ $widget->id ] = $widget->name . ' - ' . $widget->widgetable->__typename;
	}

	return Block::make( 'Warm Welcome Widgets' )
		->add_fields( [
			Field::make( 'select', 'widget', __( 'Widget', 'crb' ) )
				->add_options($options)
				->set_required(),
			] )
			->set_render_callback( function( $args ) use ($widgets) {
				$widget_id = $args['widget'];

				$filtered_widget = array_filter($widgets, function($widget) use ($widget_id) {
					return $widget->id === $widget_id;
				});

				if ( empty( $filtered_widget ) ) {
					return;
				}

				$widget = array_shift($filtered_widget);

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
					return;
				}

				warmwelcome_load_fragment( $type, array( 'widget' => $widget ) );
			});
}

function warmwelcome_get_bubble_widget_option() {
	$widgets = get_option( 'ww_widgets');

	if ( empty( $widgets ) ) {
		return array();
	}

	$bubble_options = [ 'none' => ''];

	foreach ($widgets as $widget) {
		if ($widget->widgetable->__typename !== 'BubbleWidget') {
			continue;
		}

		$bubble_options[ $widget->uniqueId ] = $widget->name . ' - ' . $widget->widgetable->__typename;
	}

	return array(
		Field::make( 'select', 'crb_default_bubble_widget', __( 'Default Bubble Widget', 'crb' ) )
			->add_options($bubble_options)
			->set_help_text('The widget will be available on every page'),
		Field::make( 'html', 'ww_save_options', __( 'Save Options', ' crb' ) )
			->set_html('<a href="#" class="button button-primary button-large ww-save-options">Save Options</a>'),
	);
}

function warmwelcome_get_widgets_list() {
	$widgets = get_option( 'ww_widgets');

	if ( empty( $widgets ) ) {
		return array();
	}

	ob_start();
	?>
	<h3>Your widgets</h3>

	</hr>
	<div class="widgets-settings">
		<ul>
			<?php foreach ($widgets as $widget) : ?>
				<li><strong>ID: <?php echo $widget->id; ?></strong> - <?php echo $widget->name ?></li>
			<?php endforeach; ?>
		</ul>

		<p>
			To place a Signature or a Business widget on your page with the Gutenberg editor you can use the Warm Welcome Widgets block:
		</p>

		<img src="<?php echo plugins_url( 'warm-welcome/resources/assets/block-screenshot.png' ); ?>" alt="" />

		<p>To place a Signature or a Business widget on your page with the Classic editor you can use the <strong>[ww-widget]</strong> shortcode with widget ID attribute. <pre>Example: [ww-widget id=12]</pre></p>
	</div>
	<?php
	$html = ob_get_clean();

	return array(
		Field::make( 'html', 'ww_widgets_list', __( 'Widgets List', ' crb' ) )->set_html( $html )
	);
}