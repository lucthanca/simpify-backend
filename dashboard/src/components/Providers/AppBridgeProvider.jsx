import { useMemo, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Provider } from '@shopify/app-bridge-react';
import { Banner, Layout, Page } from '@shopify/polaris';
import { useAppContext } from '@simpify/context/app.jsx';

/**
 * A component to configure App Bridge.
 * @desc A thin wrapper around AppBridgeProvider that provides the following capabilities:
 *
 * 1. Ensures that navigating inside the app updates the host URL.
 * 2. Configures the App Bridge Provider, which unlocks functionality provided by the host.
 *
 * See: https://shopify.dev/apps/tools/app-bridge/react-components
 */
export function AppBridgeProvider({ children }) {
  const location = useLocation();
  const navigate = useNavigate();
  const [{ apiKey }] = useAppContext();
  const history = useMemo(
    () => ({
      replace: path => {
        navigate(path, { replace: true });
      },
    }),
    [navigate],
  );

  const routerConfig = useMemo(() => ({ history, location }), [history, location]);

  const fiApiK = useMemo(() => {
    // if (!apiKey) {
    //   return import.meta.env.VITE_SHOPIFY_API_KEY;
    // }
    return apiKey;
  }, [apiKey]);

  // The host may be present initially, but later removed by navigation.
  // By caching this in state, we ensure that the host is never lost.
  // During the lifecycle of an app, these values should never be updated anyway.
  // Using state in this way is preferable to useMemo.
  // See: https://stackoverflow.com/questions/60482318/version-of-usememo-for-caching-a-value-that-will-never-change

  const [host] = useState(() => {
    const host = new URLSearchParams(location.search).get('host') || window.__SHOPIFY_DEV_HOST;
    window.__SHOPIFY_DEV_HOST = host;
    return host;
  });

  const appBridgeConfig = useMemo(() => {
    return {
      host,
      apiKey: fiApiK,
      forceRedirect: true,
    };
  }, [fiApiK]);

  if (!fiApiK) {
    return null;
  }

  if (!fiApiK || !appBridgeConfig.host) {
    const bannerProps = !fiApiK
      ? {
          title: 'Missing Shopify API Key',
          children: (
            <>
              Your app is running without the SHOPIFY_API_KEY environment variable. Please ensure that it is set when running or building your React
              app.
            </>
          ),
        }
      : {
          title: 'Missing host query argument',
          children: (
            <>
              Your app can only load if the URL has a <b>host</b> argument. Please ensure that it is set, or access your app using the Partners
              Dashboard <b>Test your app</b> feature
            </>
          ),
        };

    return (
      <Page narrowWidth>
        <Layout>
          <Layout.Section>
            <div style={{ marginTop: '100px' }}>
              <Banner {...bannerProps} status='critical' />
            </div>
          </Layout.Section>
        </Layout>
      </Page>
    );
  }

  return (
    <Provider config={appBridgeConfig} router={routerConfig}>
      {children}
    </Provider>
  );
}
