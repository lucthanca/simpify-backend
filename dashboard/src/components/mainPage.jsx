import React from 'react';
import '@shopify/polaris/build/esm/styles.css';
import { Text, Box, Grid, ProgressBar, Page, Thumbnail, Icon, Frame, Loading} from '@shopify/polaris';
import {TickMinor} from '@shopify/polaris-icons';
import {Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Tooltip} from 'chart.js';
import { Line } from 'react-chartjs-2';
const MainPage = props => {
  const title = (
    <div className='border-b-2 border-[var(--p-action-primary)]'>
      <Box background="bg" padding="6" shadow='md'>
        <Text variant="headingXl" as="h1">
          Dashboard
        </Text>
      </Box>
    </div>
  );
  const steps = [
    {label: 'Design your app', step: '1', active: true},
    {label: 'Set languge for your app', step: '2', active: true},
    {label: 'Configure your app features', step: '3', active: true},
    {label: 'Preview your app', step: '4', active: false},
    {label: 'Publish', step: '5', active: false},
  ];
  const stepGuide = (
    <div className='grid grid-cols-2 md:grid-cols-5 mt-4 gap-[5px]'>
      {steps.map((item) =>
        <div key={item.label} className='bg-[var(--p-background-selected)] rounded-lg'>
          <Box padding="4">
            <div className='flex justify-center pb-2'>
              {item.active &&
                <div className='w-9 h-9 rounded-full bg-[var(--p-action-primary)] flex items-center justify-center fill-[var(--p-icon-on-primary)]'>
                  <Icon
                    source={TickMinor}
                  />
                </div>
              }
              {!item.active &&
                <div className='w-9 h-9 rounded-full bg-white flex items-center justify-center'>
                  <Text variant="headingMd" as="p" alignment="center">
                    {item.step}
                  </Text>
                </div>
              }
            </div>
            <Text variant="headingXs" as="p" alignment="center">
              {item.label}
            </Text>
          </Box>
        </div>
      )}
    </div>
  );
  const wellcome = (
    <div className='my-10'>
      <Box background="bg" padding="6" shadow='md' borderRadius='2'>
        <Grid>
          <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 8, xl: 8}}>
            <Text variant="headingXl" as="h1">
              Welcome, Name
            </Text>
            <Box paddingBlockStart='4' paddingBlockEnd='4'>
              <Text variant="headingXs" as="h6">
                Let's get ready to rock your business with Mobile App!
              </Text>
            </Box>
            <Grid gap={{xs: '5px', sm: '5px', md: '5px', lg: '5px', xl: '5px'}}>
              <Grid.Cell columnSpan={{xs: 6, lg: 4}}>
                <Box padding='8' borderWidth="1" borderStyle='dashed' borderRadius='2' borderColor='border-disabled'>
                  <Text variant="headingSm" as="h5">
                    Current plan
                  </Text>
                  <Text variant="headingXl" as="h5" color="success">
                    Free
                  </Text>
                </Box>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 4}}>
                <Box padding='8' borderWidth="1" borderStyle='dashed' borderRadius='2' borderColor='border-disabled'>
                  <Text variant="headingSm" as="h5">
                    Current editing app
                  </Text>
                  <Text variant="headingXl" as="h5" color="success">
                    Name
                  </Text>
                </Box>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 4}}>
                <Box padding='8' borderWidth="1" borderStyle='dashed' borderRadius='2' borderColor='border-disabled'>
                  <Text variant="headingSm" as="h5">
                    Current progress
                  </Text>
                  <div className='flex justify-between'>
                    <Text variant="headingXl" as="h5" color="success">
                      70%
                    </Text>
                    <div className='flex items-center w-24 ml-2'>
                      <ProgressBar progress={40} color="primary" size="small" />
                    </div>
                  </div>
                </Box>
              </Grid.Cell>
            </Grid>
            {stepGuide}
          </Grid.Cell>
          <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 4, xl: 4}}>
            <div className='relative h-full w-full'>
              <iframe className=' absolute top-0 left-0 w-full h-full' width="560" height="315" src="https://www.youtube.com/embed/U-3WzWCK9lI" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowFullScreen >
              </iframe>
            </div>
          </Grid.Cell>
        </Grid>
      </Box>
    </div>
  );
  const options = [
    {label: 'Installed', value: '90'},
    {label: 'Active', value: '80'},
    {label: 'Uninstalled', value: '10'},
  ];
  const liveApp = (
    <Box background="bg" padding="6" shadow='md' borderRadius='2'>
      <Box paddingBlockEnd='6'>
        <Text variant="headingXl" as="h1">
          Live app
        </Text>
      </Box>
      <Grid gap={{xs: '5px', sm: '5px', md: '5px', lg: '5px', xl: '5px'}}>
        {options.map((item) =>
          <Grid.Cell key={item.label} columnSpan={{xs: 4, lg: 4}}>
            <Box padding="6" shadow='md' borderRadius='2' background='bg-primary'>
              <Text variant="headingXl" as="h1" alignment="center" color="text-inverse">
                {item.label}
              </Text>
              <Text variant="heading2xl" as="h1" alignment="center" color="text-inverse">
                {item.value}
              </Text>
            </Box>
          </Grid.Cell>
        )}
      </Grid>
    </Box>
  );

  const listApp = [
    {id: '1', label: 'Installed', value: '90', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '2', label: 'Active', value: '80', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '3', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '4', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '5', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '6', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '7', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
    {id: '8', label: 'Uninstalled', value: '10', src:'https://burst.shopifycdn.com/photos/black-leather-choker-necklace_373x@2x.jpg'},
  ];
  const appPartners = (
    <Box paddingBlockStart='8' paddingBlockEnd='8'>
      <Box background="bg" padding="6" shadow='md' borderRadius='2'>
        <Box paddingBlockEnd='6'>
          <Text variant="headingXl" as="h1">
            App Partners
          </Text>
        </Box>
        <Grid gap={{xs: '5px', sm: '5px', md: '5px', lg: '5px', xl: '5px'}}>
          {listApp.map((item) =>
            <Grid.Cell key={item.id} columnSpan={{xs: 6, lg: 3}}>
              <Box padding="6" borderRadius='2' borderWidth="1" borderStyle='dashed' borderColor='border-disabled'>
                <Thumbnail
                  source={item.src}
                />
                <Text variant="headingXl" as="p">
                  {item.label}
                </Text>
                <Text variant="headingSm" as="p">
                  {item.value}
                </Text>
              </Box>
            </Grid.Cell>
          )}
        </Grid>
      </Box>
    </Box>
  );
  return (
    <>
      {title}
      <Page fullWidth>
        {wellcome}
        {liveApp}
        <Chart/>
        {appPartners}
      </Page>
    </>
  )
}
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip
);
export const options = {
  responsive: true
};

const labels = ['January', 'February', 'March', 'April', 'May','January', 'February', 'March', 'April', 'May', 'June', 'July'];
const dataChart = [203,156,99,251,247,251,247];

export const data = {
  labels,
  datasets: [
    {
      data: labels.map((item, i) => dataChart[i]),
      borderColor: 'rgb(255, 99, 132)',
      backgroundColor: 'rgba(255, 99, 132, 0.5)',
    },
  ],
};

export function Chart() {
  return (
    <Box paddingBlockStart='8' paddingBlockEnd='8'>
      <Box background="bg" padding="6" shadow='md' borderRadius='2'>
        <Box paddingBlockEnd='6'>
          <Text variant="headingXl" as="h1">
            Sales Report
          </Text>
        </Box>
        <Line options={options} data={data} />
      </Box>
    </Box>
  )
}

export default MainPage

