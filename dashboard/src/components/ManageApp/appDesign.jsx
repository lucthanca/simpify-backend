import React, { useCallback } from 'react';
import { Link, Outlet, NavLink } from 'react-router-dom';
import { ProgressBar, Text  } from '@shopify/polaris';
import EditAppContextProvider, { useEditAppContext } from '@simpify/context/editAppContext';

const AppSetting = React.lazy(() => import('@simpify/components/ManageApp/appDesign'));
const ThemeSettings = React.lazy(() => import('@simpify/components/ManageApp/languages'));
const Pages = React.lazy(() => import('@simpify/components/ManageApp/features'));
const Preview = React.lazy(() => import('@simpify/components/ManageApp/preview'));


// import {TickMinor, ChevronRightMinor} from '@shopify/polaris-icons';
// import { useI18n } from '@shopify/react-i18n';

const AppSettingsWithContext = props => {
  return (
    <EditAppContextProvider>
      <AppSettings {...props} />
    </EditAppContextProvider>
  )
}

const AppSettings = () => {
  const [{activeTab}] = useEditAppContext();
  const ActiveContent = React.useMemo(() => {
    switch(activeTab) {
      case 'app_settings':
        return AppSetting;
      case 'theme_settings':
        return ThemeSettings;
      case 'pages':
          return Pages;
      case 'menu_item':
        return Preview;
      default :
        return AppDesign 
    }
  }, [activeTab]);

  return (
    <>
      <MenuBar/>
      <div className='main-content '>
        <React.Suspense fallback={<div>Loading..........</div>}>
          <ActiveContent />
        </React.Suspense>
      </div>
    </>
  )
}
const MenuBar = () => {
  const [{ activeTab } ,{ setActiveTab }] = useEditAppContext();

  const handleClickTab = useCallback((tabId) => {
    setActiveTab(tabId);
  }, []);
  return (
  <>
      <div>
        <span className={ (activeTab === 'app_settings') ? "bg-red-300":'' } onClick={() => handleClickTab('app_settings')}>App Settings</span>
        <span className={ (activeTab === 'theme_settings') ? "bg-red-300":'' } onClick={() => handleClickTab('theme_settings')}>Theme Settings</span>
        <span className={ (activeTab === 'pages')  ?"bg-red-300":'' } onClick={() => handleClickTab('pages')}>Pages</span>
        <span className={ (activeTab === 'menu_item') ? "bg-red-300":'' } onClick={() => handleClickTab('menu_item')}>Menu Items</span>
      </div>
    
  </>
  );
}


export default React.memo(AppSettingsWithContext);