 /**
 * External dependencies.
 */
import { ApolloClient, InMemoryCache, createHttpLink} from '@apollo/client/core';
import { gql } from '@apollo/client/core';
import { onError } from "@apollo/client/link/error";
import { setContext } from '@apollo/client/link/context';
import { BatchHttpLink } from 'apollo-link-batch-http';
import { ApolloLink } from 'apollo-link';

 /**
 * Internal dependencies.
 */
import auth from './auth'
import messageBox from '../components/messageBox'

// export default () => {
const httpLink = new createHttpLink({
  uri: `${ww_data.ww_api_url}/graphql`,
})

const headersLink = setContext((_, { headers }) => {
  if (!auth.hasToken()) {
      return headers;
  }

  return {
    headers: {
        ...headers,
        Authorization: `Bearer ${auth.getToken()}`,
    },
  };
});

const errorLink = onError(({graphQLErrors}) => {
	if (!graphQLErrors.length) {
		return;
	}

	messageBox.add(graphQLErrors[0].message);
});

const client = new ApolloClient({
  link: ApolloLink.from([
    errorLink,
    headersLink,
    httpLink,
  ]),
  cache: new InMemoryCache(),
});

export default client;
