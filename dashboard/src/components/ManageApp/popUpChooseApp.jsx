import { useState } from 'react';
import React, { useCallback } from 'react';
import { Text, Box, Modal, Button, Icon } from '@shopify/polaris';
import { ViewMinor, AddMajor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';
import PreviewTheme from '@simpify/components/ManageApp/popupPreview';

const ApplyTheme = () => {
    const [i18n] = useI18n();
    const [active, setActive] = useState(true);
    const toggleActive = useCallback(() => setActive((active) => !active), []);
    const data = [
      {id: '1', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/articles/img-9.png?v=1676456619&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'},
      {id: '2', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/collections/img-11.png?v=1678789120&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'},
      {id: '3', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/articles/img-9.png?v=1676456619&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'},
      {id: '4', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/collections/img-11.png?v=1678789120&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'},
      {id: '5', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/articles/img-9.png?v=1676456619&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'},
      {id: '6', title: 'Theme name', src:'https://cdn.shopify.com/s/files/1/0719/4732/1638/collections/img-11.png?v=1678789120&width=450', preview_img:'https://cdn.shopify.com/s/files/1/0748/3336/3250/files/01-Mobile-Dashboard.jpg?v=1682415597'}
    ]
    return (
      <>
        <Modal
          large
          open={active}
          onClose={toggleActive}
          titleHidden
        >
          <Modal.Section>
            <Box paddingBlockStart='4'>
              <Text variant="headingLg" as="h2" fontWeight='semibold' alignment="center"> 
                {i18n.translate('SimiCart.Page.ManageApp.choose_theme.title')}
              </Text>
              <div className='grid grid-cols-1 sm:grid-cols-3 p-1 gap-2 mt-5 max-h-[65vh] sm:max-h-[40vh] overflow-y-scroll scroll'>
                {data.map((item) =>
                  <PreviewTheme key={item.id} item={item}/>
                )}
                <div className='rounded-lg relative bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px]'>
                  <Box padding="6">
                    <div className='w-full h-0 pb-[35%] sm:pb-[60%] rounded overflow-hidden'>
                      <div className='absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2'>
                        <div className='mb-5 scale-150 cursor-pointer'>
                          <Icon
                            source={AddMajor}
                            color="subdued"
                          />
                        </div>
                        <Text variant="headingXs" as="p" fontWeight='regular'> 
                          {i18n.translate('SimiCart.Page.ManageApp.choose_theme.add_theme')}
                        </Text>
                      </div>
                    </div>
                  </Box>
                </div>
              </div>
              <div className='my-5 flex justify-center'>
                <Button primary>{i18n.translate('SimiCart.Page.ManageApp.choose_theme.button')}</Button>
              </div>
            </Box>
          </Modal.Section>
        </Modal>
      </>
    );
  };
export default ApplyTheme