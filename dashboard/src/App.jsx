import React from 'react';
import Routes from '@simpify/components/Routes';
import { AppBridgeProvider } from '@simpify/components/Providers';
import ChannelMenu from '@simpify/components/ChannelMenu';
import { useLocation } from 'react-router-dom';
import {Banner, Layout, Page} from "@shopify/polaris";
import AppContextProvider, { useAppContext } from "@simpify/context/app";
import { PolarisProvider } from '@simpify/components/Providers';

const App = props => {
  return (
    <AppContextProvider>
      <PolarisProvider>
        <ChosenProvider />
      </PolarisProvider>
    </AppContextProvider>
  );
}

const ChosenProvider = props => {
  const location = useLocation();
  const search = new URLSearchParams(location.search);
  const host = search.get('host');

  const [{ xSimiAccessKey }] = useAppContext();

  if (!xSimiAccessKey && !host) {
    return <Unauthorized title={'Unauthorized!!!'} message={<>Hey, you! Stop right there. Authorization required.</>} />;
  }

  // Any .tsx or .jsx files in /pages will become a route
  // See documentation for <Routes /> for more info
  const pages = import.meta.globEager('./pages/**/!(*.test.[jt]sx)*.([jt]sx)');
  if (xSimiAccessKey) {
    return <Routes pages={pages} />
  }
  return (
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
      <Routes pages={pages} />
    </AppBridgeProvider>
  );
}

const Unauthorized = props => {
  const { title, message } = props;
  return (
    <Page narrowWidth>
      <Layout>
        <Layout.Section>
          <Banner title={title} children={message} status='critical' />
        </Layout.Section>
      </Layout>
    </Page>
  );
}

export default App;
