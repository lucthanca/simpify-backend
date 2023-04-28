import React from 'react';
import Routes from '@simpify/components/Routes';
import { AppBridgeProvider } from '@simpify/components/Providers';
import ChannelMenu from '@simpify/components/ChannelMenu';
import { Banner, Layout, Page } from '@shopify/polaris';
import AppContextProvider, { useAppContext } from '@simpify/context/app';
import { PolarisProvider } from '@simpify/components/Providers';
import { I18nManager, I18nContext, useI18n } from '@shopify/react-i18n';
import FullPageLoading from '@simpify/components/FullPageLoading';
import { AnimatePresence, motion } from 'framer-motion';

import GetStartedForm from '@simpify/components/GetStartedPopup';
import AuthContextProvider from '@simpify/context/auth';
import { useReloadAppWarning } from '@simpify/hooks/useReloadAppWarning';

// eslint-disable-next-line no-unused-vars
const App = props => {
  const i18nManager = new I18nManager({
    locale: 'en',
    onError() {},
  });

  return (
    <I18nContext.Provider value={i18nManager}>
      <AuthContextProvider>
        <AppContextProvider>
          <PolarisProvider>
            <FullPageLoadingWrapper>
              <ChosenProvider />
            </FullPageLoadingWrapper>
          </PolarisProvider>
        </AppContextProvider>
      </AuthContextProvider>
    </I18nContext.Provider>
  );
};

function FullPageLoadingWrapper(props) {
  const { children } = props;
  const [{ isLoadingWithoutData, isAuthenticationLoading }] = useAppContext();

  const mainContentVariants = {
    hidden: {
      x: '0',
      opacity: 0,
    },
    visible: {
      x: '0',
      opacity: 1,
      transition: { delay: 0.5, duration: 0.5, type: 'spring', stiffness: 80, mass: 0.5 },
    },
    exit: {
      x: '-100vw',
      opacity: 1,
      transition: { duration: 1.25, type: 'spring', stiffness: 80, mass: 0.5 },
    },
  };

  return (
    <>
      <AnimatePresence mode={'wait'}>{(isLoadingWithoutData || isAuthenticationLoading) && <FullPageLoading />}</AnimatePresence>
      <AnimatePresence mode={'wait'}>
        {(!isLoadingWithoutData && !isAuthenticationLoading) && (
          <motion.div variants={mainContentVariants} initial='hidden' animate='visible' exit='exit'>
            {children}
          </motion.div>
        )}
      </AnimatePresence>
    </>
  )
}

// eslint-disable-next-line no-unused-vars
const VerifyRequest = props => {
  const [{ xSimiAccessKey, shopInfo, appError, isLoadingWithoutData, isAuthenticationLoading }] = useAppContext();
  const errorComponent = React.useMemo(() => {
    if (isAuthenticationLoading || isLoadingWithoutData) return null;

    if (!xSimiAccessKey) {
      return <Unauthorized title={'Unauthorized!!!'} message={<>Hey, you! Stop right there. Authorization required.</>} />;
    }

    if (appError) {
      return <Unauthorized title={'Whoops!!!'} message={appError} />;
    }

    return null;
  }, [xSimiAccessKey, appError, isAuthenticationLoading, isLoadingWithoutData]);

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
      {errorComponent}
      <AnimatePresence mode={'wait'}>
        {!isShopFilledAllRequiredFields && !errorComponent && !isLoadingWithoutData && !isAuthenticationLoading && <GetStartedForm />}
      </AnimatePresence>
      {!errorComponent && !isLoadingWithoutData && !isAuthenticationLoading && isShopFilledAllRequiredFields && <Routes pages={pages} />}
    </>
  );
};

// eslint-disable-next-line no-unused-vars
const ChosenProvider = props => {
  const [i18n] = useI18n();
  const [{ isLoginFromShopify }] = useAppContext();
  useReloadAppWarning();
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
  // eslint-disable-next-line react/prop-types
  const { title, message } = props;
  return (
    <Page narrowWidth>
      <Layout>
        <Layout.Section>
          {/* eslint-disable-next-line react/no-children-prop */}
          <Banner title={title} children={message} status='critical' />
        </Layout.Section>
      </Layout>
    </Page>
  );
};

export default App;
