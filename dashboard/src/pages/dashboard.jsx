import React from 'react';
import PropTypes from 'prop-types';
import { TitleBar } from '@shopify/app-bridge-react';
import { Card, Heading, Layout, Link, Page, Stack, TextContainer } from '@shopify/polaris';
import {useAppContext} from "@simpify/context/app.jsx";
import MainPage from '@simpify/components/mainPage';



const Dashboard = props => {
  const [{ xSimiAccessKey }] = useAppContext();
  
  return (
    <>
      { !xSimiAccessKey && <TitleBar title='App name' primaryAction={null} /> }
      <Layout>
        <Layout.Section>
          <MainPage/>
        </Layout.Section>
      </Layout>
    </>
  );
};

Dashboard.propTypes = {};

export default Dashboard;