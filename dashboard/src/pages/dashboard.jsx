import React from 'react';
import PropTypes from 'prop-types';
import { TitleBar } from '@shopify/app-bridge-react';
import { Card, Heading, Layout, Link, Page, Stack, TextContainer } from '@shopify/polaris';
import { useAppContext } from '@simpify/context/app.jsx';
import { useI18n } from '@shopify/react-i18n';
import { LegacyCard, ResourceList, Avatar, Text } from '@shopify/polaris';
import Popup from '@simpify/components/GetStartedPopup';

const Dashboard = props => {
  const [{ xSimiAccessKey }] = useAppContext();
  const [i18n] = useI18n();

  return (
    <Page narrowWidth>
      {!xSimiAccessKey && <TitleBar title={i18n.translate('SimiCart.Page.Dashboard.Title')} primaryAction={null} />}
      <Layout>
        <Layout.Section>
          <Popup />
        </Layout.Section>
      </Layout>
    </Page>
  );
};

Dashboard.propTypes = {};

export default Dashboard;
