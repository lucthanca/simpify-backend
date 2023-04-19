import {Frame, Navigation, Page, Layout, LegacyCard} from '@shopify/polaris';
import {HomeMinor, OrdersMinor, ProductsMinor} from '@shopify/polaris-icons';
import React from 'react';
import MainPage from './mainPage';

function MenuNavigation() {
  return (
    <Frame
      navigation={navigationMarkup}
    >
      {actualPageMarkup}
    </Frame>
  );
}
const navigationMarkup = (
  <Navigation location="/">
    <Navigation.Section
      items={[
        {
          url: '#',
          label: 'Dashboard',
          icon: HomeMinor,
        },
        {
          url: '#',
          excludePaths: ['#'],
          label: 'Manage App',
          icon: OrdersMinor,
          badge: '15',
        },
        {
          url: '#',
          excludePaths: ['#'],
          label: 'Integration',
          icon: ProductsMinor,
        },
        {
          url: '#',
          excludePaths: ['#'],
          label: 'Plan',
          icon: ProductsMinor,
        },
        {
          url: '#',
          excludePaths: ['#'],
          label: 'Contact Us',
          icon: ProductsMinor,
        },
      ]}
    />
  </Navigation>
)
const actualPageMarkup = (
  <Page>
    <MainPage/>
  </Page>
);
export default MenuNavigation