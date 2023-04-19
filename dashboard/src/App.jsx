import React from 'react';
import { useQuery, gql } from '@apollo/client';
import { NavigationMenu } from '@shopify/app-bridge-react';
import Routes from '@simpify/components/Routes/index.jsx';

const storeConfig = gql`
  query getStoreConfig {
    storeConfig {
      id
      code
      website_id
      locale
      base_currency_code
      default_display_currency_code
      timezone
      weight_unit
      base_url
      base_link_url
      base_static_url
      base_media_url
      secure_base_url
      secure_base_link_url
      secure_base_static_url
      secure_base_media_url
      store_name
    }
  }
`;

const App = () => {
  const { data, loading, error } = useQuery(storeConfig);

  console.log(data, error, loading);

  // Any .tsx or .jsx files in /pages will become a route
  // See documentation for <Routes /> for more info
  const pages = import.meta.globEager('./pages/**/!(*.test.[jt]sx)*.([jt]sx)');

  return (
    <>
      <NavigationMenu
        navigationLinks={[
          {
            label: 'Dashboard',
            destination: '/dashboard',
          },
        ]}
      />
      <Routes pages={pages} />
      <div>Hello World heh!!!</div>
    </>
  );
};

export default App;
