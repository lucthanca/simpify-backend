import React from 'react';
import { Text, Box} from '@shopify/polaris';

const TitlePage = props => {
  return (
    <div className='border-b-2 border-[var(--p-action-primary)]'>
      <Box background="bg" padding="4" paddingInlineStart='8' paddingInlineEnd='8' shadow='md'>
        <Text variant="headingMd" as="h1" fontWeight='semibold'>
          {props.title}
        </Text>
      </Box>
    </div>
  )
}
export default React.memo(TitlePage);