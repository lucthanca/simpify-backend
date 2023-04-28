import React, { useMemo, createContext, useContext, useState } from 'react';
import { useQuery, gql } from '@apollo/client';
import { connect } from 'react-redux';
import { increment, decrement, incrementByAmount } from '@simpify/store/example';
import bindActionCreators from '@simpify/utils/bindActionCreators';
import { useLocation } from 'react-router-dom';
import { useAuthContext } from '@simpify/context/auth';

const AppContext = createContext(undefined);

export const GET_SHOP_DETAILS = gql`
  query getShopDetails {
    simpifyShop {
      uid
      more_info {
        industry
        shop_owner_name
        shop_owner_email
        how_you_know_us {
          label
          value
        }
      }
    }
  }
`;

const AppContextProvider = props => {
  // eslint-disable-next-line react/prop-types
  const { actions, appState: reduxAppState, children } = props;
  const [{ xSimiAccessKey, isLoading: isAuthenticationLoading, authenticationError, apiKey }] = useAuthContext();
  const location = useLocation();
  const search = new URLSearchParams(location.search);
  // console.log(location.search);
  const [host] = useState(() => {
    const host = search.get('host') || window.__SHOPIFY_DEV_HOST;
    window.__SHOPIFY_DEV_HOST = host;
    return host;
  });
  const [session] = useState(() => {
    const session = search.get('session') || window.__SHOPIFY_DEV_SHOP_SESSION;
    window.__SHOPIFY_DEV_SHOP_SESSION = session;
    return session;
  });
  const [forceRedirect] = useState(() => {
    const forceRedirect = search.get('force_to_shopify') || window.__SHOPIFY_DEV_IS_FORCE_REDIRECT_TO_SHOPIFY;
    window.__SHOPIFY_DEV_IS_FORCE_REDIRECT_TO_SHOPIFY = forceRedirect;
    return forceRedirect;
  });

  const [isLoginFromShopify] = useState(() => {
    return (!!host && !!session) || forceRedirect;
    }, [host, session, forceRedirect]);

  const { data, loading, error } = useQuery(GET_SHOP_DETAILS, {
    fetchPolicy: 'cache-and-network',
    nextFetchPolicy: 'cache-first',
    skip: !xSimiAccessKey,
  });

  const shopInfo = useMemo(() => {
    return data?.simpifyShop || null;
  }, [data]);

  const appError = useMemo(() => {
    if (authenticationError) {
      return authenticationError;
    }
    if (error) {
      return error.message;
    }
    if (data?.errors?.length > 0) {
      const errs = data.errors.map(error => error.message);

      return errs.join('<br />');
    }
  }, [error, authenticationError]);

  const appApi = useMemo(
    () => ({
      ...actions,
    }),
    [actions],
  );

  const isLoadingWithData = !!data && loading;
  const isLoadingWithoutData = !data && loading;
  const appState = useMemo(() => {
    return {
      ...reduxAppState,
      xSimiAccessKey,
      apiKey,
      isLoginFromShopify,
      shopInfo,
      isAuthenticationLoading,
      isLoadingWithData,
      isLoadingWithoutData,
      appError,
    };
  }, [reduxAppState, loading, appError, shopInfo, isLoginFromShopify, apiKey, xSimiAccessKey, isAuthenticationLoading]);
  const contextValue = useMemo(() => [appState, appApi], [appApi, appState]);

  return <AppContext.Provider value={contextValue}>{children}</AppContext.Provider>;
};

const mapStateToProps = ({ app }) => ({ appState: app });

const mapDispatchToProps = dispatch => ({
  actions: bindActionCreators({ increment, decrement, incrementByAmount }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(AppContextProvider);

export const useAppContext = () => useContext(AppContext);
