import React from 'react';
import '@shopify/polaris/build/esm/styles.css';
import { Text, Box, ProgressBar, Page, Thumbnail, Icon, HorizontalStack, Button} from '@shopify/polaris';
import {TickMinor, ChevronRightMinor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';
import { ChartSale } from './chartSale';

const MainPage = props => {
  const [i18n] = useI18n();
  const steps = [
    {label: i18n.translate('SimiCart.Page.Dashboard.Welcome.steps_1'), step: '1', active: true},
    {label: i18n.translate('SimiCart.Page.Dashboard.Welcome.steps_2'), step: '2', active: true},
    {label: i18n.translate('SimiCart.Page.Dashboard.Welcome.steps_3'), step: '3', active: true},
    {label: i18n.translate('SimiCart.Page.Dashboard.Welcome.steps_4'), step: '4', active: false},
    {label: i18n.translate('SimiCart.Page.Dashboard.Welcome.steps_5'), step: '5', active: false},
  ];
  const stepGuide = (
    <div className='flex mt-6 sm:grid sm:grid-cols-5 gap-[6px] pt-3 pb-9 sm:pb-4 sm:pt-0 overflow-x-scroll sm:overflow-hidden scroll'>
      {steps.map((item) =>
        <div key={item.label} className='bg-[var(--p-background-selected)] rounded-lg relative p-7 pb-10 min-w-[130px] sm:min-w-[auto]'>
          <div className='flex justify-center pb-2'>
            {item.active &&
              <div className='w-9 h-9 rounded-full bg-[var(--p-action-primary)] flex items-center justify-center fill-[var(--p-icon-on-primary)]'>
                <Icon source={TickMinor} />
              </div>
            }
            {!item.active &&
              <div className='w-9 h-9 rounded-full bg-white flex items-center justify-center'>
                <Text variant="headingSm" as="p" alignment="center">
                  {item.step}
                </Text>
              </div>
            }
          </div>
          <Text variant="headingXs" as="p" alignment="center">
            {item.label}
          </Text>
          {item.step !== '5' &&
            <div className='absolute -bottom-[18px] -right-[18px] z-10 flex justify-center items-center w-9 h-9 bg-[var(--p-background-selected)] rounded-full border-2 border-white'>
              <Icon source={ChevronRightMinor} color="subdued" />
            </div>
          }
        </div>
      )}
    </div>
  );
  const wellcome = (
    <div className='mt-4 mb-6'>
      <Box background="bg" padding={{xs: '6', sm: '6', md: '6', lg: '8', xl: '8'}} paddingBlockEnd={{xs: '6', sm: '6', md: '6', lg: '12', xl: '12'}} shadow='md' borderRadius='2'>
        <div className='grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-14 xl:gap-[75px]'>
          <div className='md:col-span-7'>
            <Text variant="headingLg" as="h2" fontWeight='semibold'>
              {i18n.translate('SimiCart.Page.Dashboard.Welcome.title')}
            </Text>
            <Box paddingBlockStart='2' paddingBlockEnd='8'>
              <Text variant="headingSm" as="p" color="subdued" fontWeight='regular'>
                {i18n.translate('SimiCart.Page.Dashboard.Welcome.subtitle')}
              </Text>
            </Box>
            <div className='gap-[5px] grid grid-cols-2 md:grid-cols-3'>
              <div className='p-6 lg:p-10 lg:pb-5 border border-dashed rounded-lg boder-[var(--p-border-disabled)]'>
                <Text variant="headingSm" as="p" fontWeight='regular'>
                  {i18n.translate('SimiCart.Page.Dashboard.Welcome.current_plan')}
                </Text>
                <Box paddingBlockStart='2'>
                  <Text variant="heading2xl" as="h3" color="success" fontWeight='bold'>
                    {i18n.translate('SimiCart.Page.Dashboard.Welcome.price')}
                  </Text>
                </Box>
              </div>
              <div className='p-6 lg:p-10 lg:pb-5 border border-dashed rounded-lg boder-[var(--p-border-disabled)]'>
                <Text variant="headingSm" as="p" fontWeight='regular'>
                  {i18n.translate('SimiCart.Page.Dashboard.Welcome.current_edit')}
                </Text>
                <Box paddingBlockStart='2'>
                  <Text variant="heading2xl" as="h3" color="success" fontWeight='bold'>
                    Name
                  </Text>
                </Box>
              </div>
              <div className='p-6 lg:p-10 lg:pb-5 border border-dashed rounded-lg boder-[var(--p-border-disabled)] col-span-2 md:col-span-1'>
                <Text variant="headingSm" as="p" fontWeight='regular'>
                  {i18n.translate('SimiCart.Page.Dashboard.Welcome.current_progress')}
                </Text>
                <div className='sm:block flex justify-between'>
                  <Box paddingBlockStart='2'>
                    <Text variant="heading2xl" as="h3" color="success" fontWeight='bold'>
                      70%
                    </Text>
                  </Box>
                  <div className='w-full pl-10 sm:pl-0 pt-5 sm:pt-0 sm:mt-4 sm:ml-0 mt-0 ml-2'>
                    <ProgressBar progress={70} color="primary" size="small" />
                  </div>
                </div>
              </div>
            </div>
            {stepGuide}
          </div>
          <div className='md:col-span-5 flex flex-col justify-center'>
            <div className='relative h-0 pb-[60%] md:pb-0 md:h-full'>
              <iframe
                className=' absolute top-0 left-0 w-full h-full'
                width='560'
                height='315'
                src='https://www.youtube.com/embed/U-3WzWCK9lI'
                title='YouTube video player'
                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
                allowFullScreen></iframe>
            </div>
          </div>
        </div>
      </Box>
    </div>
  );
  const options = [
    {label: i18n.translate('SimiCart.Page.Dashboard.Live_app.installed'), value: '90'},
    {label: i18n.translate('SimiCart.Page.Dashboard.Live_app.active'), value: '80'},
    {label: i18n.translate('SimiCart.Page.Dashboard.Live_app.uninstalled'), value: '10'},
  ];
  const liveApp = (
    <Box background="bg" padding={{xs: '6', sm: '6', md: '6', lg: '8', xl: '8'}} shadow='md' borderRadius='2'>
      <Box paddingBlockEnd='6'>
        <HorizontalStack>
          <Text variant="headingLg" as="h2" fontWeight='semibold'>
            {i18n.translate('SimiCart.Page.Dashboard.Live_app.title')}
          </Text>
          <div className='ml-9 text-[var(--p-color-bg-inverse-active)]'>
            <Text variant="headingLg" as="h2" fontWeight='semibold'>
              Name
            </Text>
          </div>
        </HorizontalStack>
      </Box>
      <div className='grid gap-[6px] gri sm:grid-cols-3'>
        {options.map((item) =>
          <Box key={item.label} shadow='md' borderRadius='2' background='bg-primary'>
            <div className='sm:py-[60px] py-6 px-5'>
              <Text variant="headingLg" as="h3" alignment="center" color="text-inverse" fontWeight='semibold'>
                {item.label}
              </Text>
              <Text variant="heading4xl" as="h3" alignment="center" color="text-inverse" fontWeight='bold'>
                {item.value}
              </Text>
            </div>
          </Box>
        )}
      </div>
    </Box>
  );

  const listApp = [
    {id: '1', label: 'TRACK Order, PayPal Tracking', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '2', label: 'SEO:Image Optimizer Page', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '3', label: 'DECO: Product Labels & Badges', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '4', label: 'TRACK Order, PayPal Tracking', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '5', label: 'SEO:Image Optimizer Page', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '6', label: 'TRACK Order, PayPal Tracking', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '7', label: 'DECO: Product Labels & Badges', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '8', label: 'SEO:Image Optimizer Page', description: 'Track your orders across 1700+ global carriers, Embed branded tracking page, Auto email, Buyer Protection.', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
  ];
  const appPartners = (
    <Box background="bg" padding={{xs: '6', sm: '6', md: '6', lg: '8', xl: '8'}} shadow='md' borderRadius='2'>
      <Box paddingBlockEnd='6'>
        <Text variant="headingXl" as="h1">
          {i18n.translate('SimiCart.Page.Dashboard.List_apps.Title')}
        </Text>
      </Box>
      <div className='grid gap-[6px] sm:grid-cols-4 grid-cols-1'>
        {listApp.map((item) =>
          <Box key={item.id} padding="6" borderRadius='2' borderWidth="1" borderStyle='dashed' borderColor='border-disabled'>
            <Thumbnail
              source={item.src}
            />
            <Box paddingBlockStart='4' paddingBlockEnd='3'>
              <Text variant="headingSm" as="p" fontWeight='semibold'>
                {item.label}
              </Text>
            </Box>
            <Box paddingBlockEnd='6'>
              <Text variant="headingXs" as="p" color="subdued" fontWeight='regular'>
                {item.description}
              </Text>
            </Box>
            <Button outline>{i18n.translate('SimiCart.Page.Dashboard.List_apps.Button')}</Button>
          </Box>
        )}
      </div>
    </Box>
  );
  return (
    <>
      <Page fullWidth>
        {wellcome}
        {liveApp}
        <ChartSale/>
        {appPartners}
      </Page>
    </>
  )
}


export default MainPage;
