import { useCallback, useMemo } from 'react';
import { AppProvider } from '@shopify/polaris';
import { useNavigate } from '@shopify/app-bridge-react';
import en from '@shopify/polaris/locales/en.json';
import localEn from '@simpify/locales/en.json';
import '@shopify/polaris/build/esm/styles.css';
import { useAppContext } from '@simpify/context/app.jsx';
import { useI18n } from '@shopify/react-i18n';

function AppBridgeLink({ url, children, external, ...rest }) {
  const navigate = useNavigate();
  const handleClick = useCallback(() => {
    navigate(url);
  }, [url]);

  const IS_EXTERNAL_LINK_REGEX = /^(?:[a-z][a-z\d+.-]*:|\/\/)/;

  if (external || IS_EXTERNAL_LINK_REGEX.test(url)) {
    return (
      <a {...rest} href={url} target='_blank' rel='noopener noreferrer'>
        {children}
      </a>
    );
  }

  return (
    <a {...rest} onClick={handleClick}>
      {children}
    </a>
  );
}

/**
 * Sets up the AppProvider from Polaris.
 * @desc PolarisProvider passes a custom link component to Polaris.
 * The Link component handles navigation within an embedded app.
 * Prefer using this vs any other method such as an anchor.
 * Use it by importing Link from Polaris, e.g:
 *
 * ```
 * import {Link} from '@shopify/polaris'
 *
 * function MyComponent() {
 *  return (
 *    <div><Link url="/tab2">Tab 2</Link></div>
 *  )
 * }
 * ```
 *
 * PolarisProvider also passes translations to Polaris.
 *
 */
export function PolarisProvider({ children }) {
  const [{ xSimiAccessKey }] = useAppContext();
  const linkComponent = useMemo(() => {
    if (xSimiAccessKey) {
      return undefined;
    }
    return AppBridgeLink;
  }, [xSimiAccessKey, AppBridgeLink]);
  const getTranslateDic = useCallback(locale => {
    if (locale === 'en') {
      return { ...en, ...localEn };
    }

    // const dic = await import(`../../../node_modules/@shopify/polaris/locales/${locale}.json`);
    // return dic && dic.default;
  }, []);

  const [i18n, ShareTranslations] = useI18n({
    id: 'Polaris',
    fallback: en,
    translations: getTranslateDic,
  });

  return (
    <AppProvider i18n={i18n.translations} linkComponent={linkComponent}>
      <ShareTranslations>{children}</ShareTranslations>
    </AppProvider>
  );
}