import { useQuery, gql } from '@apollo/client';
import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { useLocation } from 'react-router-dom';
const AuthContext = createContext(undefined);
import BrowserPersistence from '@simpify/utils/simplePersistence';
const storage = new BrowserPersistence();

const AUTH_QUERY = gql`
  query authenticateShop($query: String!) {
    simpifyAuthenticateShop(query: $query) {
      success
      type
      redirect_url
      message
      access_token
      app_api_key
    }
  }
`;

const AuthContextProvider = props => {
  // eslint-disable-next-line react/prop-types
  const { children } = props;
  const [initialized, setInitialized] = useState(false);
  const [cachedData, setCachedData] = useState();
  const [isLoading, setIsLoading] = useState(true);
  // const [accessKey, setAccessKey] = useState(null);
  // const [apiKey, setApiKey] = useState(null);
  // console.log({ initialized });
  const location = useLocation();
  const { data, loading, error } = useQuery(AUTH_QUERY, {
    variables: { query: location.search },
    fetchPolicy: 'network-only',
    skip: initialized,
  });

  useEffect(() => {
    if (initialized || error || loading) {
      return;
    }
    if (!loading && data) {
      setInitialized(true);
    }
    console.log({ data, loading, error });
    if (data?.simpifyAuthenticateShop?.type === 'installation' && data?.simpifyAuthenticateShop?.redirect_url) {
      window.location.href = data.simpifyAuthenticateShop.redirect_url;
      return;
    }

    setCachedData(data?.simpifyAuthenticateShop || {});
    setIsLoading(false);
    // if (data?.simpifyAuthenticateShop?.app_api_key) {
    //   setApiKey(data?.simpifyAuthenticateShop?.app_api_key);
    // }
    // if (!data?.simpifyAuthenticateShop?.access_token) {
    //   setAccessKey(null);
    //   storage.setItem('x-simpify-token', null);
    //   return;
    // }
    // if (data?.simpifyAuthenticateShop?.access_token && data?.simpifyAuthenticateShop?.access_token !== accessKey) {
    //   setAccessKey(data.simpifyAuthenticateShop.access_token);
    //   storage.setItem('x-simpify-token', data.simpifyAuthenticateShop.access_token);
    // }
  }, [data]);

  const accessKey = useMemo(() => {
    if (cachedData?.access_token) {
      storage.setItem('x-simpify-token', cachedData?.access_token);
    }
    return cachedData?.access_token;
  }, [cachedData]);
  const apiKey = useMemo(() => {
    return cachedData?.app_api_key;
  }, [cachedData]);

  const authenticationError = useMemo(() => {
    return error && error.message;
  }, [error]);
  const state = useMemo(() => {
    return { xSimiAccessKey: accessKey, isLoading, authenticationError, apiKey };
  }, [accessKey, isLoading, authenticationError, apiKey]);
  const api = useMemo(() => {}, []);

  const contextValue = useMemo(() => {
    return [state, api];
  }, [state, api]);
  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>;
};

export default AuthContextProvider;

export const useAuthContext = () => useContext(AuthContext);
