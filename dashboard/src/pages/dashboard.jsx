import MenuNavigation from '@simpify/components/navigation';
import '@shopify/polaris/build/esm/styles.css';
import { Text, Box, Grid, ProgressBar, Page, VideoThumbnail} from '@shopify/polaris';

const Dashboard = props => {
  const title = (
    <Box background="bg" padding="6" shadow='md'>
      <Text variant="heading2xl" as="h1">
        Dashboard
      </Text>
    </Box>
  );
  const userGuide = (
    <div className='my-10'>
      <Box background="bg" padding="6" shadow='md' borderRadius='6'>
        <Grid>
          <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 8, xl: 8}}>
            <Text variant="heading2xl" as="h1">
              Welcome, Name
            </Text>
            <Box paddingBlockStart='4' paddingBlockEnd='4'>
              <Text variant="headingXs" as="h6">
                Let's get ready to rock your business with Mobile App! 
              </Text>
            </Box>
            <Grid gap={{xs: '5px', lg: '10px'}}>
              <Grid.Cell columnSpan={{xs: 6, lg: 3}}>
                <Text variant="headingSm" as="h5">
                  Current plan
                </Text>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 8}}>
                <Text variant="headingSm" as="h5">
                  Current plan
                </Text>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 3}}>
                <Text variant="headingSm" as="h5">
                  Current editing app
                </Text>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 8}}>
                <Text variant="headingSm" as="h5">
                  Current plan
                </Text>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 3}}>
                <Text variant="headingSm" as="h5">
                  Current progress
                </Text>
              </Grid.Cell>
              <Grid.Cell columnSpan={{xs: 6, lg: 8}}>
                <div className='flex items-center w-24'>
                  <ProgressBar progress={40} size="small" />
                </div>
              </Grid.Cell>
            </Grid>
  
          </Grid.Cell>
          <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 4, xl: 4}}>
            <VideoThumbnail
              videoLength={80}
              thumbnailUrl="https://burst.shopifycdn.com/photos/business-woman-smiling-in-office.jpg?width=1850"
              onClick={() => console.log('clicked')}
            />
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
    <Box background="bg" padding="6" shadow='md' borderRadius='6'>
      <Box paddingBlockEnd='6'>
        <Text variant="heading2xl" as="h1">
          Live app
        </Text>
      </Box>
      <Grid gap={{xs: '10px', lg: '20px'}}>
        {options.map((item) =>
          <Grid.Cell key={item.label} columnSpan={{xs: 4, lg: 4}}>
            <Box padding="6" shadow='md' borderRadius='4'>
              <Text variant="heading2xl" as="h1" alignment="center">
                {item.label}
              </Text>
              <Text variant="heading2xl" as="h1" alignment="center">
                {item.value}
              </Text>
            </Box>
          </Grid.Cell>
        )}
      </Grid>
    </Box>
  );
  return (
    <Page>
      {title}
      {userGuide}
      {liveApp}
    </Page>
  )
}

export default Dashboard
  
  