/**
 * External dependencies.
 */
import { gql } from '@apollo/client/core';

/**
 * Internal dependencies.
 */
import client from '../client';

const Login = (email, password) => {
	return client.mutate({
		mutation: gql`
			mutation Login($input: LoginInput!) {
				login(input: $input)
			},
		`,
		variables: {
			input: {
				email,
				password
			},
		},
	});
}

const UpdateWidgetURL = async (widgetUniqueId, url, useAsDefault = false) => {
	await client.mutate({
		mutation: gql`
			mutation updateBubbleWidget($input: UpdateBubbleWidgetInput!) {
				widget: updateBubbleWidget(input: $input) {
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
			},
		`,
		variables: {
			input: {
				id: widgetUniqueId,
				availabilityUrls: `${url}`,
				useAsDefaultForAllPages: useAsDefault,
				forceAvailabilityUrlsRewrite: true,
			},
		},
	});
}

export default {
	Login,
	UpdateWidgetURL
};
