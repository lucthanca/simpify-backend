import React, { useMemo, createContext, useContext, useState } from 'react';
import { useQuery, gql } from '@apollo/client';
import { connect } from 'react-redux';
import { increment, decrement, incrementByAmount } from '@simpify/store/example';
import bindActionCreators from '@simpify/utils/bindActionCreators';
import { useLocation } from 'react-router-dom';
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
  const { actions, appState: reduxAppState, children } = props;
  const location = useLocation();
  const search = new URLSearchParams(location.search);
  const accessKey = search.get('x-simi-access');
  const apiKey = search.get('app-api-key'); // using url param for flexible change app
  const requestHost = search.get('host');
  storage.setItem('x-simpify-token', accessKey);

  const [xSimiAccessKey, setSimiKey] = useState(accessKey);
  const [host] = useState(requestHost);

  const isLoginFromShopify = useMemo(() => {
    return !!host;
  }, [host]);

  const { data, loading, error } = useQuery(GET_SHOP_DETAILS, { fetchPolicy: 'cache-and-network', nextFetchPolicy: 'cache-first' });

  const shopInfo = useMemo(() => {
    return data?.simpifyShop || null;
  }, [data]);

  const appError = useMemo(() => {
    if (error) {
      return error.message;
    }
    if (data?.errors?.length > 0) {
      const errs = data.errors.map(error => error.message);

      return errs.join('<br />');
    }
  }, [error]);

  const appApi = useMemo(
    () => ({
      ...actions,
      setSimiKey,
    }),
    [actions, setSimiKey],
  );
  const appState = useMemo(() => {
    return {
      ...reduxAppState,
      xSimiAccessKey,
      apiKey,
      isLoginFromShopify,
      shopInfo,
      isLoadingWithData: !!data && loading,
      isLoadingWithoutData: !data && loading,
      appError,
    };
  }, [reduxAppState, loading, appError, shopInfo, isLoginFromShopify, apiKey, xSimiAccessKey]);
  const contextValue = useMemo(() => [appState, appApi], [appApi, appState]);

  return <AppContext.Provider value={contextValue}>{children}</AppContext.Provider>;
};

const mapStateToProps = ({ app }) => ({ appState: app });

const mapDispatchToProps = dispatch => ({
  actions: bindActionCreators({ increment, decrement, incrementByAmount }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(AppContextProvider);

export const useAppContext = () => useContext(AppContext);
