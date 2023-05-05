import React, { useCallback } from 'react';
import PropTypes from 'prop-types';
import { Layout, Text, Box, Grid, Page, Icon, Modal, Button, TextField } from '@shopify/polaris';
import {DuplicateMinor, EditMinor, DeleteMinor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';
import {useAppContext} from "@simpify/context/app";
import TitlePage from '@simpify/components/titlePage';
import CreateApp from '@simpify/components/ManageApp/popUpCreate';
import ApplyTheme from '@simpify/components/ManageApp/popUpChooseApp';



const ManageApp = props => {
  const [{ xSimiAccessKey }] = useAppContext();
  const [i18n] = useI18n();
  return (
    <Layout>
      <Layout.Section>
        <TitlePage title={i18n.translate('SimiCart.Page.ManageApp.Title')} />
        <Page fullWidth>
          <ListApp/>
        </Page>
      </Layout.Section>
    </Layout>
  );
};
const ListApp = () => {
  const [i18n] = useI18n();
  
  const data = [
    {id: '1', title: 'BAppss Commerce ', last_update: '21-04-1023',version_android:'v1.1.0',version_ios:'v1.1.0', ios_date: '21-04-1023', android_date: '21-04-1023', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/articles/img-9.png?v=1676456619&width=450'},
    {id: '2', title: 'Bss Shopify Mobile App', last_update: '21-04-1023', version_android:'v1.1.0',version_ios:'v1.1.0', ios_date: '21-04-1023', android_date: '21-04-1023', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/collections/img-11.png?v=1678789120&width=450'}
  ]
  return (
    <Box>
      <div className='grid grid-cols-1 gap-2 sm:grid-cols-3 md:grid-cols-4 sm:gap-6 p-5 sm:p-0 bg-white sm:bg-transparent mt-2'>
        {data.map((item) =>
          <div key={item.id} className='rounded-lg bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px]'>
            <Box padding="6">
              <div className='relative w-full h-0 pb-[60%] group rounded overflow-hidden'>
                <img src={item.src} className='absolute top-0 left-0 w-full h-full object-cover z-10' />
                <div className='absolute top-0 left-0 w-full h-full flex items-center justify-center duration-200 bg-black bg-opacity-50 opacity-0 z-0 group-hover:opacity-100 group-hover:z-20'>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded cursor-pointer'>
                    <Icon
                      source={DuplicateMinor}
                      color="subdued"
                    />
                  </div>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded cursor-pointer'>
                    <Icon
                      source={EditMinor}
                      color="subdued"
                    />
                  </div>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded cursor-pointer'>
                    <Icon
                      source={DeleteMinor}
                      color="subdued"
                    />
                  </div>
                </div>
                <div className='absolute top-2 -right-8 w-[100px] rotate-45 py-1 bg-[#f69435] z-50'>
                  <p className='text-center text-[10px] leading-tight tracking-widest font-semibold text-white uppercase'>Live</p>
                </div>
              </div>
              
              <Box paddingBlockStart='4' paddingBlockEnd='4'>
                <Text variant="headingSm" as="p" fontWeight='semibold'>
                  {item.title}
                </Text>
              </Box>
                <Text variant="headingXs" as="p" fontWeight='regular'>
                  {i18n.translate('SimiCart.Page.ManageApp.last_update', {date: item.last_update})}
                </Text>
                <Box paddingBlockStart='1' paddingBlockEnd='1'>
                  <Text variant="headingXs" as="p" fontWeight='regular'>
                    {i18n.translate('SimiCart.Page.ManageApp.ios_update', {version:item.version_ios, date:item.ios_date} )} 
                  </Text>
                </Box>
                <Text variant="headingXs" as="p" fontWeight='regular'> 
                  {i18n.translate('SimiCart.Page.ManageApp.android_update', {version:item.version_android, date:item.android_date})}
                </Text>
            </Box>
          </div>
        )}
        <div className='rounded-lg relative bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px]'>
          <div className='h-0 pb-[35%] sm:pb-[65%]'>
            <div className='absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2'>
              <CreateApp/>
              <ApplyTheme/>
              <div>
                <Text variant="headingXs" as="p"> 
                  {i18n.translate('SimiCart.Page.ManageApp.addApp')}
                </Text>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Box>
  )
}

ManageApp.propTypes = {};

export default ManageApp;