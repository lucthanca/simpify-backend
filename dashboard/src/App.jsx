import React from 'react';
import Routes from '@simpify/components/Routes';
import { AppBridgeProvider } from '@simpify/components/Providers';
import ChannelMenu from '@simpify/components/ChannelMenu';
import { useLocation } from 'react-router-dom';
import { Banner, Layout, Page, LegacyCard, Select, TextField, Checkbox } from '@shopify/polaris';
import AppContextProvider, { useAppContext } from '@simpify/context/app';
import { PolarisProvider } from '@simpify/components/Providers';
import { I18nManager, I18nContext, useI18n } from '@shopify/react-i18n';
import FullPageLoading from '@simpify/components/FullPageLoading';
import { AnimatePresence } from 'framer-motion';

import GetStartedForm from '@simpify/components/GetStartedPopup';

const App = props => {
  const i18nManager = new I18nManager({
    locale: 'en',
    onError() {
      // console.log(error);
    },
  });

  return (
    <I18nContext.Provider value={i18nManager}>
      <AppContextProvider>
        <PolarisProvider>
          <ChosenProvider />
        </PolarisProvider>
      </AppContextProvider>
    </I18nContext.Provider>
  );
};

const VerifyRequest = props => {
  const [{ xSimiAccessKey, shopInfo, isLoginFromShopify, appError, isLoadingWithoutData }] = useAppContext();
  const errorComponent = React.useMemo(() => {
    if (!xSimiAccessKey && !isLoginFromShopify) {
      return <Unauthorized title={'Unauthorized!!!'} message={<>Hey, you! Stop right there. Authorization required.</>} />;
    }

    if (appError) {
      return <Unauthorized title={'Whoops!!!'} message={appError} />;
    }

    return null;
  }, [xSimiAccessKey, isLoginFromShopify, appError]);

  // Any .tsx or .jsx files in /pages will become a route
  // See documentation for <Routes /> for more info
  const pages = import.meta.globEager('./pages/**/!(*.test.[jt]sx)*.([jt]sx)');

  const isShopFilledAllRequiredFields = React.useMemo(() => {
    return Boolean(
      shopInfo?.more_info && shopInfo?.more_info?.shop_owner_email && shopInfo?.more_info?.shop_owner_name && shopInfo?.more_info?.industry,
    );
  }, [shopInfo]);

  return (
    <>
      <AnimatePresence mode={'wait'}>{isLoadingWithoutData && xSimiAccessKey && !shopInfo && <FullPageLoading />}</AnimatePresence>
      {errorComponent}
      <AnimatePresence mode={'wait'}>
        {!isShopFilledAllRequiredFields && !errorComponent && !isLoadingWithoutData && <GetStartedForm />}
      </AnimatePresence>
      {!errorComponent && shopInfo && !isLoadingWithoutData && isShopFilledAllRequiredFields && <Routes pages={pages} />}
    </>
  );
};

const ChosenProvider = props => {
  const [i18n] = useI18n();
  const [{ isLoginFromShopify }] = useAppContext();
  if (!isLoginFromShopify) {
    return <VerifyRequest />;
  }
  return (
    <AppBridgeProvider>
      <VerifyRequest />
      <ChannelMenu
        navigationLinks={[
          {
            label: i18n.translate('SimiCart.Navigations.Dashboard'),
            destination: '/dashboard',
          },
          {
            label: i18n.translate('SimiCart.Navigations.ManageApp'),
            destination: '/manageapp',
          },
          {
            label: i18n.translate('SimiCart.Navigations.Integrations'),
            destination: '/integrations',
          },
          {
            label: i18n.translate('SimiCart.Navigations.Plan'),
            destination: '/plan',
          },
          {
            label: i18n.translate('SimiCart.Navigations.ContactUs'),
            destination: '/contact',
          },
        ]}
        matcher={(link, location) => link.destination === location.pathname}
      />
    </AppBridgeProvider>
  );
};

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
};

export default App;
