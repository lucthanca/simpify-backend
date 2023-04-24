import { useQuery, gql } from '@apollo/client';
import { createContext, useContext, useEffect, useMemo } from 'react';
import { useLocation } from 'react-router-dom';
const AuthContext = createContext(undefined);

const AUTH_QUERY = gql`
  query authenticateShop($param: AuthShopParam!) {
    simpifyAuthenticateShop(param: $param) {
      success
      type
      redirect_url
      message
    }
  }
`;

const AuthContextProvider = props => {
  const location = useLocation();

  const searchParams = Object.fromEntries(new URLSearchParams(location.search));
  const { data, loading, error } = useQuery(AUTH_QUERY, { variables: { param: searchParams } });

  useEffect(() => {
    console.log({ data, loading, error });
    if (data?.simpifyAuthenticateShop?.type === 'installation' && data?.simpifyAuthenticateShop?.redirect_url) {
      window.location.href = data.simpifyAuthenticateShop.redirect_url;
    }
  }, [data, loading, error]);

  const state = useMemo(() => {
    return { searchParams };
  }, []);

  const api = useMemo(() => {}, []);

  const contextValue = useMemo(() => {
    return [state, api];
  }, [state, api]);
  return <AuthContext.Provider value={contextValue}></AuthContext.Provider>;
};

export default AuthContextProvider;

export const useAuthContext = () => useContext(AuthContext);
