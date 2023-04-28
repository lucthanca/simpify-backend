import { useEffect } from 'react';
import { useAppContext } from '@simpify/context/app';

export const useReloadAppWarning = props => {
  const { message = 'Are you sure want to closed or reload page? You must re-access from Admin Page.' } = props || {};
  const [{ isLoginFromShopify }] = useAppContext();

  useEffect(() => {
    console.log({isLoginFromShopify});
    window.onbeforeunload = () => !isLoginFromShopify && message;

    return () => {
      window.onbeforeunload = null;
    };
  }, []);

  return [];
};
