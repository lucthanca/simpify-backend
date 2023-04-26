import React, { useCallback } from 'react';
import { Link, Outlet, NavLink } from 'react-router-dom';
import { ProgressBar, Text  } from '@shopify/polaris';
import EditAppContextProvider, { useEditAppContext } from '@simpify/context/editAppContext';

const AppDesign = React.lazy(() => import('@simpify/components/ManageApp/appDesign'));
const Languages = React.lazy(() => import('@simpify/components/ManageApp/languages'));

// import {TickMinor, ChevronRightMinor} from '@shopify/polaris-icons';
// import { useI18n } from '@shopify/react-i18n';

const EditAppWithContext = props => {
  return (
    <EditAppContextProvider>
      <EditApp {...props} />
    </EditAppContextProvider>
  )
}

const EditApp = () => {
  const [{activeTab}] = useEditAppContext();
  const ActiveContent = React.useMemo(() => {
    switch(activeTab) {
      case 'app_design':
        return AppDesign;

        case 'language':
          return Languages;

      default :
        return AppDesign 
    }
  }, [activeTab]);

  return (
    <>
    <div className='flex'>
      <Title/>
      <TabMenu/>
    </div>
    <div className='main-content '>
      <React.Suspense fallback={<div>Loading..........</div>}>
        <ActiveContent />
      </React.Suspense>
    </div>
    </>
  )
}
const Title = () => {
  return (
    <div>
      <Text variant="headingLg" as="p"> 
        Title name
      </Text>
      <div className='flex'>
        <Text variant="headingLg" as="p"> 
          20%
        </Text>
        <div className='w-36'>
          <ProgressBar progress={20} color="primary" size="small" />
        </div>
      </div>
    </div>
  )
}
const TabMenu = () => {
  const [{ activeTab } ,{ setActiveTab }] = useEditAppContext();

  const handleClickTab = useCallback((tabId) => {
    setActiveTab(tabId);
  }, []);
  return (
  <>
      <div>
        <span className={ (activeTab === 'app_design') ? "bg-red-300":'' } onClick={() => handleClickTab('app_design')}>App Design</span>
        <span className={ (activeTab === 'language') ? "bg-red-300":'' } onClick={() => handleClickTab('language')}>Language</span>
        <span className={ (activeTab === 'features') ? "bg-red-300":'' } onClick={() => handleClickTab('features')}>Features</span>
        <span className={ (activeTab === 'preview') ? "bg-red-300":'' } onClick={() => handleClickTab('preview')}>Preview</span>
        <span className={ (activeTab === 'publish') ? "bg-red-300":'' } onClick={() => handleClickTab('publish')}>Publish</span>
      </div>
    
  </>
  );
}

export default EditAppWithContext