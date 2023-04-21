import React from 'react';
import PropTypes from 'prop-types';
import { Layout, Text, Box, Grid, Page } from '@shopify/polaris';
import { useI18n } from '@shopify/react-i18n';
import {useAppContext} from "@simpify/context/app";
import TitlePage from '@simpify/components/titlePage';


const ManageApp = props => {
  const [{ xSimiAccessKey }] = useAppContext();
  const [i18n] = useI18n();

  return (
    <Layout>
      <Layout.Section>
        <TitlePage title={i18n.translate('SimiCart.Page.Dashboard.Title')} />
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
    <Box background="bg" padding="8" shadow='md' borderRadius='2'>
      <Grid gap={{xs: '5px', sm: '5px', md: '6px', lg: '6px', xl: '6px'}}>
        {data.map((item) =>
          <Grid.Cell key={item.id} columnSpan={{xs: 6, lg: 3}}>
            <Box padding="6" borderRadius='2' borderWidth="1" borderStyle='dashed' borderColor='border-disabled'>
              <img src={item.src} alt="" />
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
          </Grid.Cell>
        )}
        <Grid.Cell columnSpan={{xs: 6, lg: 3}}>
            <Box padding="6" borderRadius='2' borderWidth="1" borderStyle='dashed' borderColor='border-disabled'>
              
            </Box>
          </Grid.Cell>
      </Grid>
    </Box>
  )
}
ManageApp.propTypes = {};

export default ManageApp;