import { AlphaCard, Text, Box, Grid, ProgressBar} from '@shopify/polaris';

const MainPage = () => {
  const title = (
    <AlphaCard>
      <Text variant="heading2xl" as="h1">
        Dashboard
      </Text>
    </AlphaCard>
  );
  const userGuide = (
    <Box background="bg-app-selected">
      <Grid>
        <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 6, xl: 8}}>
          <Text variant="heading2xl" as="h1">
            Welcome, Name
          </Text>
          <Text variant="headingXs" as="h6">
            Let's get ready to rock your business with Mobile App! 
          </Text>
          <Text variant="headingSm" as="h5">
            Current plan
          </Text>
          <Text variant="headingSm" as="h5">
            Current editing app
          </Text>
          {/* <Inline></Inline> */}
            <Text variant="headingSm" as="h5">
              Current progress
            </Text>
            <div style={{width: 225}}>
              <ProgressBar progress={40} size="small" />
            </div>
          
        </Grid.Cell>
        <Grid.Cell columnSpan={{xs: 6, sm: 3, md: 3, lg: 6, xl: 4}}>
          <Text variant="heading2xl" as="h1">
            Dashboard
          </Text>
        </Grid.Cell>
      </Grid>
    </Box>
  );
  return (
    <>
    {title}
    {userGuide}
    </>
  );
};


export default MainPage

