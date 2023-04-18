import React from 'react';
import { useQuery, gql } from '@apollo/client';

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

  return (
    <div className='w-full overflow-hidden'>
      <h1>Hello world!!!</h1>
    </div>
  );
};

export default App;
