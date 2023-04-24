import { useQuery, gql } from '@apollo/client';
import { createContext, useContext, useEffect, useMemo, useState } from "react";
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
    }
  }
`;

const AuthContextProvider = props => {
  // eslint-disable-next-line react/prop-types
  const { children } = props;
  const [initialized, setInitialized] = useState(false);
  const [accessKey, setAccessKey] = useState(null);
  console.log({ initialized });
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
      // console.log(data.simpifyAuthenticateShop.redirect_url);
      window.location.href = data.simpifyAuthenticateShop.redirect_url;
      return;
    }
    if (!data?.simpifyAuthenticateShop?.access_token) {
      setAccessKey(null);
      storage.setItem('x-simpify-token', null);
      return;
    }
    console.log({accessKey});
    if (data?.simpifyAuthenticateShop?.access_token && data?.simpifyAuthenticateShop?.access_token !== accessKey) {
      setAccessKey(data.simpifyAuthenticateShop.access_token);
      storage.setItem('x-simpify-token', data.simpifyAuthenticateShop.access_token);
    }
  }, [data]);

  // const xSimiAccessKey = useMemo(() => {
  //   return storage.getItem('x-simpify-token');
  // }, [data, loading, initialized]);
  const authenticationError = useMemo(() => {
    return error && error.message;
  }, [error]);
  const state = useMemo(() => {
    return { xSimiAccessKey: accessKey, isLoading: loading, authenticationError };
  }, [accessKey, loading, authenticationError]);
  const api = useMemo(() => {}, []);

  const contextValue = useMemo(() => {
    return [state, api];
  }, [state, api]);
  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>;
};

export default AuthContextProvider;

export const useAuthContext = () => useContext(AuthContext);
