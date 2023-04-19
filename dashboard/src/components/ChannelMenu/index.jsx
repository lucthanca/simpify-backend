import { useMemo, useState, useEffect, useCallback } from 'react';
import PropTypes from 'prop-types';
import { useAppBridge } from '@shopify/app-bridge-react';
import { ChannelMenu as ShopifyChannelMenu, AppLink } from '@shopify/app-bridge/actions';

const ChannelMenu = props => {
  const { navigationLinks, matcher: providedMatcher } = props;
  const app = useAppBridge();
  const [items, setItems] = useState(undefined);

  const matcher = useCallback(
    (link, location) => {
      if (typeof providedMatcher === 'function') {
        return providedMatcher(link, location);
      }

      return link.destination.replace(/\/$/, '') === location.pathname.replace(/\/$/, '');
    },
    [providedMatcher],
  );

  useEffect(() => {
    const items = navigationLinks.map(function (link) {
      return AppLink.create(app, link);
    });
    setItems(items);
  }, [app, JSON.stringify(navigationLinks)]);

  const activeLink = useMemo(() => {
    const activeLinkIndex = (items || []).findIndex(function (link) {
      return matcher(link, location);
    });
    if (activeLinkIndex >= 0) {
      return items === null || items === void 0 ? void 0 : items[activeLinkIndex];
    }
  }, [app, location.pathname, matcher, items, location.pathname]);

  useEffect(() => {
    // Skip creating the navigation menu on initial render
    if (!items) {
      return;
    }
    /**
     * There isn't any advantage to keeping around a consistent instance of
     * the navigation menu as when we create a new one it results in
     * the same UPDATE action to be dispatched
     */
    ShopifyChannelMenu.create(app, { items: items, active: activeLink });
  }, [items, activeLink]);
  return null;
};

ChannelMenu.propTypes = {
  navigationLinks: PropTypes.array,
  matcher: PropTypes.func,
};
export default ChannelMenu;
