import React, { useMemo, createContext, useContext, useState } from 'react';
import { useQuery, gql } from '@apollo/client';
import { connect } from 'react-redux';
import { increment, decrement, incrementByAmount } from '@simpify/store/example';
import bindActionCreators from '@simpify/utils/bindActionCreators';
import { useLocation } from 'react-router-dom';
import { useAuthContext } from "@simpify/context/auth";
import BrowserPersistence from '@simpify/utils/simplePersistence';

const storage = new BrowserPersistence();

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
  const [{ xSimiAccessKey, isLoading: isAuthenticationLoading, authenticationError }] = useAuthContext();
  const location = useLocation();
  const search = new URLSearchParams(location.search);
  // console.log(location.search);
  const apiKey = search.get('app-api-key'); // using url param for flexible change app
  const requestHost = search.get('host');
  const shopifySession = search.get('session');
  const forceRedirectToShopifyFlag = search.get('force_to_shopify');
  const [host] = useState(requestHost);
  const [session] = useState(shopifySession);
  const [forceRedirect] = useState(forceRedirectToShopifyFlag);

  const isLoginFromShopify = useMemo(() => {
    return (!!host && !!session) || forceRedirect;
  }, [host, session, forceRedirect]);
  // console.log({ xSimiAccessKey });

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
      isAuthenticating: isAuthenticationLoading,
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
