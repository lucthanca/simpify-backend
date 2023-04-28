import React, { useCallback } from 'react';
import { ProgressBar, Text, Page, Box  } from '@shopify/polaris';
import EditAppContextProvider, { useEditAppContext } from '@simpify/context/editAppContext';

const AppDesign = React.lazy(() => import('@simpify/components/ManageApp/appDesign'));
const Languages = React.lazy(() => import('@simpify/components/ManageApp/languages'));
const Features = React.lazy(() => import('@simpify/components/ManageApp/features'));
const Preview = React.lazy(() => import('@simpify/components/ManageApp/preview'));
const Publish = React.lazy(() => import('@simpify/components/ManageApp/publish'));


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
      case 'features':
          return Features;
      case 'preview':
        return Preview;
      case 'publish':
        return Publish;
      default :
        return AppDesign 
    }
  }, [activeTab]);

  return (
    <>
      <Page fullWidth>
        <div className='flex items-center'>
          <div className='w-[18%]'>
          <Title/>
          </div>
          <div className='w-[82%] flex justify-center'>
            <TabMenu/>
          </div>
        </div>
      </Page>
      <Page fullWidth>
        <Box padding='8' background='bg' borderRadius='2'>
          <div className='flex'>
            <div className='w-[74%]'>
              <React.Suspense fallback={<div>Loading..........</div>}>
                <ActiveContent/>
              </React.Suspense>
            </div>
            <div className='w-[26%] pl-4'>
              <img src='/src/assets/ip2.png'/>
            </div>
          </div>
        </Box>
      </Page>
    </>
  )
}
const Title = () => {
  return (
    <div>
      <Text variant="headingLg" as="p"> 
        Title name
      </Text>
      <div className='flex items-center gap-4 relative'>
        <Text variant="headingLg" as="p"> 
          20%
        </Text>
        <div className='w-full'>
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
    <div className='flex gap-2'>
      <div className={ (activeTab === 'app_design') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('app_design')}>App Design</p>
      </div>
      <div className={ (activeTab === 'language') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('language')}>Language</p>
      </div>
      <div className={ (activeTab === 'features') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('features')}>Features</p>
      </div>
      <div className={ (activeTab === 'preview') ? "active menu-editapp":'menu-editapp' } >
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('preview')}>Preview</p>
      </div>
      <div className={ (activeTab === 'publish') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('publish')}>Publish</p>
      </div>
    </div>
  </>
  );
}

export default EditAppWithContext