import React from 'react';
import ReactDOM from 'react-dom/client';
// import App from './App';
import './index.css';

import Adapder from '@simpify/components/Adapter';

const searchQuery = new URLSearchParams(window.location.search);

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <Adapder shopDomain={searchQuery.get('shop')} origin={import.meta.env.VITE_SIMPIFY_BACKEND_URL} />
  </React.StrictMode>,
);
