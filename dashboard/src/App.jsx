import React from 'react';
import Routes from '@simpify/components/Routes/index.jsx';
import { AppBridgeProvider } from '@simpify/components/Providers';
import AppContextProvider from '@simpify/context/app.jsx';
import ChannelMenu from '@simpify/components/ChannelMenu';
import { useLocation } from 'react-router-dom';

const App = () => {
  // Any .tsx or .jsx files in /pages will become a route
  // See documentation for <Routes /> for more info
  const pages = import.meta.globEager('./pages/**/!(*.test.[jt]sx)*.([jt]sx)');

  const location = useLocation();

  return (
    <>
      <AppBridgeProvider>
        <ChannelMenu
          navigationLinks={[
            {
              label: 'Dashboard',
              destination: '/dashboard',
            },
          ]}
          matcher={(link, location) => link.destination === location.pathname}
        />
        <AppContextProvider>
          <Routes pages={pages} />
        </AppContextProvider>
      </AppBridgeProvider>
    </>
  );
};

export default App;
