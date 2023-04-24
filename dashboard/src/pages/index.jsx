import React from 'react';
import PropTypes from 'prop-types';
import { TitleBar } from '@shopify/app-bridge-react';
import { Layout } from '@shopify/polaris';
import Popup from '@simpify/components/GetStartedPopup';
import MainPage from '@simpify/components/mainPage';
import { useI18n } from '@shopify/react-i18n';
import { useAppContext } from '@simpify/context/app';
import TitlePage from '@simpify/components/titlePage';

const Dashboard = props => {
  const [{ xSimiAccessKey }] = useAppContext();
  const [i18n] = useI18n();

  return (
    <>
      <Layout>
        <Layout.Section>
          {/*<Popup />*/}
          <TitlePage title={i18n.translate('SimiCart.Page.Dashboard.Title')} />
          <MainPage />
        </Layout.Section>
      </Layout>
    </>
  );
};

Dashboard.propTypes = {};

export default Dashboard;
