import React from 'react';
import PropTypes from 'prop-types';
import { Layout, Text, Box, Grid, Page, Icon } from '@shopify/polaris';
import {DuplicateMinor, EditMajor, DeleteMajor, AddMajor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';
import {useAppContext} from "@simpify/context/app";
import TitlePage from '@simpify/components/titlePage';


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
  const data = [
    {id: '1', title: 'Bss Commerce App', last_update: '21-04-1023', ios_date: '21-04-1023', android_date: '21-04-1023', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/articles/img-9.png?v=1676456619&width=450'},
    {id: '2', title: 'Bss Shopify Mobile App', last_update: '21-04-1023', ios_date: '21-04-1023', android_date: '21-04-1023', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/collections/img-11.png?v=1678789120&width=450'}
  ]
  return (
    <Box>
      <div className='grid grid-cols-4 gap-6'>
        {data.map((item) =>
          <div key={item.id} className='border border-dashed border-[var(--p-border-disabled)] rounded-lg bg-white'>
            <Box padding="6">
              <div className='relative w-full h-0 pb-[60%] group'>
                <img src={item.src} className='absolute top-0 left-0 w-full h-full object-cover z-10' />
                <div className='absolute top-0 left-0 w-full h-full flex items-center justify-center duration-200 bg-black bg-opacity-50 opacity-0 z-0 group-hover:opacity-100 group-hover:z-20'>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded'>
                    <Icon
                      source={DuplicateMinor}
                      color="subdued"
                    />
                  </div>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded-sm'>
                    <Icon
                      source={EditMajor}
                      color="subdued"
                    />
                  </div>
                  <div className='mx-2 w-10 h-10 flex items-center justify-center bg-white rounded-sm'>
                    <Icon
                      source={DeleteMajor}
                      color="subdued"
                    />
                  </div>
                </div>
              </div>
              
              <Box paddingBlockStart='4' paddingBlockEnd='3'>
                <Text variant="headingSm" as="p" fontWeight='semibold'>
                  {item.title}
                </Text>
              </Box>
              <Box paddingBlockEnd='6'>
                <Text variant="headingXs" as="p" color="subdued" fontWeight='regular'>
                  {item.last_update}
                </Text>
                <Text variant="headingXs" as="p" color="subdued" fontWeight='regular'>
                  {item.ios_date}
                </Text>
                <Text variant="headingXs" as="p" color="subdued" fontWeight='regular'> 
                  {item.android_date}
                </Text>
              </Box>
            </Box>
          </div>
        )}
        <div className='border border-dashed border-[var(--p-border-disabled)] rounded-lg flex items-center justify-center'>
          <div>
            <Icon
              source={AddMajor}
              color="subdued"
            />
            <Text variant="headingXs" as="p" color="subdued" fontWeight='regular'> 
              addd
            </Text>
          </div>
        </div>
      </div>
    </Box>
  )
}
ManageApp.propTypes = {};

export default ManageApp;