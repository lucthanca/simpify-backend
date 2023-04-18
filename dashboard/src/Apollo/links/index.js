import { createHttpLink } from '@apollo/client';

import createAuthLink from '@simpify/Apollo/links/authLink';
import createGqlCacheLink from '@simpify/Apollo/links/gqlCacheLink';
import createMutationQueueLink from '@simpify/Apollo/links/mutationQueueLink';
import createRetryLink from '@simpify/Apollo/links/retryLink';
import createErrorLink from './errorLink';
import shrinkQuery from '@simpify/utils/shrinkQuery';

/**
 * Intercept and shrink URLs from GET queries.
 *
 * Using GET makes it possible to use edge caching in Magento Cloud, but risks
 * exceeding URL limits with default usage of Apollo's http link.
 *
 * `shrinkQuery` encodes the URL in a more efficient way.
 *
 * @param {*} uri
 * @param {*} options
 */
export const customFetchToShrinkQuery = (uri, options) => {
  // TODO: add `ismorphic-fetch` or equivalent to avoid this error
  if (typeof globalThis.fetch !== 'function') {
    console.error('This environment does not define `fetch`.');
    return () => {};
  }

  const resource = options.method === 'GET' ? shrinkQuery(uri) : uri;

  return globalThis.fetch(resource, options);
};

const getLinks = apiBase => {
  const authLink = createAuthLink();
  const retryLink = createRetryLink();
  const gqlCacheLink = createGqlCacheLink();
  const mutationQueueLink = createMutationQueueLink();
  const errorLink = createErrorLink();

  // Warning: `useGETForQueries` risks exceeding URL length limits.
  // These limits in practice are typically set at or behind where TLS
  // terminates. For Magento Cloud & Fastly, 8kb is the maximum by default.
  // https://docs.fastly.com/en/guides/resource-limits#request-and-response-limits
  const httpLink = createHttpLink({
    fetch: customFetchToShrinkQuery,
    useGETForQueries: true,
    uri: apiBase,
  });

  const links = new Map()
    .set('MUTATION_QUEUE', mutationQueueLink)
    .set('RETRY', retryLink)
    .set('AUTH', authLink)
    .set('GQL_CACHE', gqlCacheLink)
    .set('ERROR', errorLink)
    .set('HTTP', httpLink);

  return links;
};
export default getLinks;
