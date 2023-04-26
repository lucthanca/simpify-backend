import { useEffect } from 'react';
import { useAppContext } from '@simpify/context/app';

export const useReloadAppWarning = props => {
  const [{ isLoginFromShopify }] = useAppContext();

  useEffect(() => {
    window.onbeforeunload = () => !isLoginFromShopify;

    return () => {
      window.onbeforeunload = null;
    };
  }, []);

  return [];
};
