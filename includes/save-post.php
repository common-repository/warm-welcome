<?php

add_action('save_post', 'warmwelcome_save_post', 2, 10);
function warmwelcome_save_post( $post_id, $post ) {
	$bubble_widget_post_meta = carbon_get_post_meta( $post_id, 'ww_bubble_widget');
	$bubble_widget_post_meta_new = sanitize_text_field($_REQUEST['carbon_fields_compact_input']['_ww_bubble_widget']);

	if ( $bubble_widget_post_meta === $bubble_widget_post_meta_new ) {
		return;
	}

	$token = get_option( 'ww_token');

$query = <<<'GRAPHQL'
mutation updateBubbleWidget($input: UpdateBubbleWidgetInput!) {
	updateBubbleWidget(input: $input) {
		id
		uniqueId
		name
		brandingOptions
		publicUrl
		widgetable {
			... on BubbleWidget {
				id
				title
				showBackdrop
				font
				buttonColor
				backgroundColor
				borderColor
				fitVideo
				size
				previewTextFontSize
				availabilityUrls
				useAsDefaultForAllPages
			}
		}
	}
}
GRAPHQL;

	warmwelcome_graphql_query(WARMWELCOME_API_URL . '/graphql', $query, array(
		'input' => array(
			'id' => $bubble_widget_post_meta_new,
			'availabilityUrls' => get_page_link($post->post_parent),
			'forceAvailabilityUrlsRewrite' => true,
		)
	), $token );
}

function warmwelcome_graphql_query( $endpoint, $query, $variables, $token ) {
	$headers = array( 'Content-Type' => 'application/json' );

    if (null !== $token) {
        $headers["Authorization"] = "Bearer $token";
	}

    $data = wp_remote_post($endpoint, array(
		'method' => 'POST',
		'headers' => $headers,
		'body' => json_encode(array(
			'query' => $query,
			'variables' => $variables
		)),
	));

	if ( is_wp_error( $data ) ) {
		return json_decode( $data->get_error_message(), true );
	}

    return json_decode( $data['body'], true );
}
