import React from 'react';
import Routes from '@simpify/components/Routes';
import { AppBridgeProvider } from '@simpify/components/Providers';
import ChannelMenu from '@simpify/components/ChannelMenu';
import { useLocation } from 'react-router-dom';
import { Banner, Layout, Page, LegacyCard, Select, TextField } from '@shopify/polaris';
import AppContextProvider, { useAppContext } from '@simpify/context/app';
import { PolarisProvider } from '@simpify/components/Providers';
import { I18nManager, I18nContext, useI18n } from '@shopify/react-i18n';
import FullPageLoading from "@simpify/components/FullPageLoading";
import {AnimatePresence } from 'framer-motion';

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

const useGetStartedForm = props => {
  return {};
}

const GetStartedForm = props => {
  const [i18n] = useI18n();
  const [selected, setSelected] = React.useState('today');
  const [email, setEmail] = React.useState('bernadette.lapresse@jadedpixel.com');
  const [name, setName] = React.useState('');

  const handleEmailChange = React.useCallback(
    (newValue) => setEmail(newValue),
    [],
  );

  const handleSelectChange = React.useCallback(
    (value) => setSelected(value),
    [],
  );
  const handleNameChange = React.useCallback((v) => setName(v));
  const handleClearName = React.useCallback(() => setName(''));

  const options = [
    {label: 'Arts and crafts', value: 'arts&crafts'},
    {label: 'Business equipment and supplies', value: 'beas'},
    {label: 'Food and drink.', value: 'food'},
    {label: 'ardware and automotive.', value: 'hardware'},
  ];


  return (
    <Page fullWidth>
      <Layout>
        <Layout.Section>
          <div className='md:max-w-[55vw] max-w-[600px] mx-auto'>
            <LegacyCard title={i18n.translate('SimiCart.GetStarted.Title')}
                        sectioned primaryFooterAction={{content: 'Let\'s get started', onAction: () => { console.log('testttt') }, loading: false}}>
              <div className='flex flex-col gap-4'>
                <Select
                  label={i18n.translate('SimiCart.GetStarted.Form.Industry')}
                  options={options}
                  onChange={handleSelectChange}
                  value={selected}
                />
                <TextField
                  label="Store name"
                  value={name}
                  onChange={handleNameChange}
                  clearButton
                  onClearButtonClick={handleClearName}
                  autoComplete="off"
                />
                <TextField
                  label="Your Email"
                  type="email"
                  value={email}
                  onChange={handleEmailChange}
                  autoComplete="email"
                  clearButton
                />

              </div>
            </LegacyCard>
          </div>
        </Layout.Section>
      </Layout>
    </Page>
  );
}

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
    return false;
  }, []);
  return (
    <>
      <AnimatePresence mode={'wait'}>
        {(isLoadingWithoutData && xSimiAccessKey && !shopInfo) && <FullPageLoading />}
      </AnimatePresence>
        {errorComponent}
      {!isShopFilledAllRequiredFields && <GetStartedForm />}
        {(!errorComponent && shopInfo && !isLoadingWithoutData && isShopFilledAllRequiredFields) && <Routes pages={pages} />}
    </>
  );
}

const ChosenProvider = props => {
  const [i18n] = useI18n();
  const [{ xSimiAccessKey, isLoginFromShopify, appError, isFullLoading }] = useAppContext();
  if (!isLoginFromShopify) {
    return <VerifyRequest />
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
