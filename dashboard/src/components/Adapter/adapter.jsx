import React from 'react';
import { ApolloProvider } from '@apollo/client';
import { Provider as ReduxProvider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import PlatformProvider from '../../App.jsx';
import { useAdapter } from '@simpify/talons/Adapter/useAdapter';
import { store } from '@simpify/store';
import { string } from 'prop-types';

function Adapter(props) {
  const talonProps = useAdapter(props);
  const { apolloProps, initialized, routerProps } = talonProps;

  if (!initialized) {
    return null;
  }
  // eslint-disable-next-line react/prop-types
  const children = props.children || <PlatformProvider />;

  return (
    <ApolloProvider {...apolloProps}>
      <ReduxProvider store={store}>
        <BrowserRouter {...routerProps}>{children}</BrowserRouter>
      </ReduxProvider>
    </ApolloProvider>
  );
}

export default Adapter;

Adapter.propTypes = {
  shopDomain: string.isRequired,
  origin: string,
  apiUrl: string,
};
