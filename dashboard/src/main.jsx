import React from 'react';
import ReactDOM from 'react-dom/client';
// import App from './App';
import './index.css';

import Adapder from '@simpify/components/Adapter';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <Adapder shopDomain={'vadu-test-store.myshopify.com'} origin={'https://simpify.lucthanca.tk'} />
  </React.StrictMode>,
);
