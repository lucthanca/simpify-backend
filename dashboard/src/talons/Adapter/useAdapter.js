import { useMemo, useCallback, useState, useEffect } from 'react';
import { ApolloLink } from '@apollo/client';
import { InMemoryCache } from '@apollo/client/cache';
import { ApolloClient } from '@apollo/client/core';
import { CACHE_PERSIST_PREFIX } from '@simpify/Apollo/constants';
import { CachePersistor } from 'apollo-cache-persist';
import getLinks from '@simpify/Apollo/links';
import attachClient from '@simpify/Apollo/attachClientToStore';
import typePolicies from '@simpify/Apollo/policies';

const isServer = !globalThis.document;

export const useAdapter = props => {
  const { shopDomain, apiUrl, origin } = props;
  const [initialized, setInitialized] = useState(false);
  const apiBase = useMemo(() => apiUrl || new URL('/graphql', origin).toString(), [apiUrl, origin]);
  const configureLinks = useCallback(links => {
    return [...links.values()];
  }, []);
  const apolloLink = useMemo(() => {
    let links = getLinks(apiBase);

    if (configureLinks) {
      links = configureLinks(links, apiBase);
    }

    return ApolloLink.from(Array.from(links.values()));
  }, [apiBase, configureLinks]);

  const createApolloClient = useCallback((cache, link) => {
    return new ApolloClient({
      cache,
      link,
      ssrMode: isServer,
    });
  }, []);
  const createCachePersistor = useCallback((domain, cache) => {
    return isServer
      ? null
      : new CachePersistor({
          key: `${CACHE_PERSIST_PREFIX}-${domain}`,
          cache,
          storage: globalThis.localStorage,
          // eslint-disable-next-line no-undef
          debug: process.env.NODE_ENV === 'development',
        });
  }, []);

  // eslint-disable-next-line no-unused-vars
  const clearCacheData = useCallback(async (client, cacheType) => {
    // Cached data
    // client.cache.evict({ id: 'TypeName' });
    // client.cache.evict({ fieldName: 'queryName' });
    client.cache.gc();
    if (client.persistor) {
      await client.persistor.persist();
    }
  }, []);

  const apolloClient = useMemo(() => {
    const client = createApolloClient(preInstantiatedCache, apolloLink);
    const persistor = isServer ? null : createCachePersistor(shopDomain, preInstantiatedCache);

    client.apiBase = apiBase;
    client.persistor = persistor;
    client.clearCacheData = clearCacheData;

    return client;
  }, [shopDomain]);

  const apolloProps = { client: apolloClient };

  useEffect(() => {
    if (initialized) return;
    // immediately invoke this async function
    (async () => {
      // restore persisted data to the Apollo cache
      await apolloClient.persistor.restore();

      // attach the Apollo client to the Redux store
      await attachClient(apolloClient);

      // mark this routine as complete
      setInitialized(true);
    })();
  }, [initialized, apolloClient]);
  return {
    apolloProps,
    routerProps: {},
    initialized,
  };
};

/**
 * To improve initial load time, create an apollo cache object as soon as
 * this module is executed, since it doesn't depend on any component props.
 * The tradeoff is that we may be creating an instance we don't end up needing.
 */
const preInstantiatedCache = new InMemoryCache({
  // SIMPIFY_POSSIBLE_TYPES is injected into the bundle at vite config at build time.
  // eslint-disable-next-line no-undef
  possibleTypes: SIMPIFY_POSSIBLE_TYPES,
  typePolicies,
});
