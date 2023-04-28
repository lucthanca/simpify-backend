import React, { useCallback } from 'react';
import { Link, Outlet, NavLink } from 'react-router-dom';
import { ProgressBar, Text  } from '@shopify/polaris';
import EditAppContextProvider, { useEditAppContext } from '@simpify/context/editAppContext';

const AppSettings = React.lazy(() => import('@simpify/components/ManageApp/AppDesign/appSettings'));
const ThemeSettings = React.lazy(() => import('@simpify/components/ManageApp/AppDesign/themeSettings'));
const Pages = React.lazy(() => import('@simpify/components/ManageApp/AppDesign/pages'));
const MenuItem = React.lazy(() => import('@simpify/components/ManageApp/AppDesign/menuItem'));


// import {TickMinor, ChevronRightMinor} from '@shopify/polaris-icons';
// import { useI18n } from '@shopify/react-i18n';

const AppSettingsWithContext = props => {
  return (
    <EditAppContextProvider>
      <Content {...props} />
    </EditAppContextProvider>
  )
}

const Content = () => {
  const [{activeTab},{ setActiveTab }] = useEditAppContext();
  React.useEffect(() => {
    setActiveTab('app_settings');
  },[]);
  const ActiveContent = React.useMemo(() => {
    switch(activeTab) {
      case 'app_settings':
        return AppSettings;
      case 'theme_settings':
        return ThemeSettings;
      case 'pages':
          return Pages;
      case 'menu_item':
        return MenuItem;
      default :
        return AppSettings 
    }
  }, [activeTab]);

  return (
    <div className='flex'>
      <div className='w-1/5'>
        <MenuBar/>
      </div>
      <div className='w-4/5 pl-12 pr-10'>
        <React.Suspense fallback={<div>Loading..........</div>}>
          <ActiveContent />
        </React.Suspense>
      </div>
    </div>
  )
}
const MenuBar = () => {
  const [{ activeTab } ,{ setActiveTab }] = useEditAppContext();

  const handleClickTab = useCallback((tabId) => {
    setActiveTab(tabId);
  }, []);
  return (
  <>
    <div className='flex flex-col pt-5 bg-[var(--p-color-bg-app)] rounded-md'>
      <div className={ (activeTab === 'app_settings') ? "active-menu menu-appdesign":'menu-appdesign' }>
        <p className='py-3 px-5 border-b border-solid border-[var(--p-color-border-disabled)] text-sm font-medium cursor-pointer child' onClick={() => handleClickTab('app_settings')}>App Settings</p>
      </div>
      <div className={ (activeTab === 'theme_settings') ? "active-menu menu-appdesign":'menu-appdesign' }>
        <p className='py-3 px-5 border-b border-solid border-[var(--p-color-border-disabled)] text-sm font-medium cursor-pointer child' onClick={() => handleClickTab('theme_settings')}>Theme Settings</p>
      </div>
      <div className={ (activeTab === 'pages') ?"active-menu menu-appdesign":'menu-appdesign' }>
        <p className='py-3 px-5 border-b border-solid border-[var(--p-color-border-disabled)] text-sm font-medium cursor-pointer child' onClick={() => handleClickTab('pages')}>Pages</p>
      </div>
      <div className={ (activeTab === 'menu_item') ? "active-menu menu-appdesign":'menu-appdesign' }>
        <p className='py-3 px-5 text-sm font-medium cursor-pointer child' onClick={() => handleClickTab('menu_item')}>Menu Items</p>
      </div>
    </div>
  </>
  );
}


export default React.memo(AppSettingsWithContext);