/**
 * External dependencies.
 */
import { gql } from '@apollo/client/core';

/**
 * Internal dependencies.
 */
import client from '../client';

/**
 * Internal dependencies.
 */
import WidgetFragments from './fragments/widget';

const GetWidgets = () => {
	return client.query({
		query: gql`
			query GetWidgets($input: WidgetsFilterInput, $page: Int) {
				user: me {
					id
					additionalData {
						globalWidgetId
					}
					widgets(input: $input, page: $page) {
						data {
							...FullWidgetDataFragment
						}
						paginatorInfo {
							hasMorePages
							currentPage
						}
					}
				}
			},
			${WidgetFragments.FullWidgetDataFragment}
		`,
	});
}

export default {
    GetWidgets,
};
