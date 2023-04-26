import { useState } from 'react';
import React, { useCallback } from 'react';
import { Text, Box, Modal, Button, Icon } from '@shopify/polaris';
import { ViewMinor, CancelMajor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';

const PreviewTheme = props => {
    const [i18n] = useI18n();
    const [active, setActive] = useState(false);
    const toggleActive = useCallback(() => setActive((active) => !active), []);
    return (
      <div className='rounded-lg bg-white shadow-[rgba(0,0,0,0.1)_0px_0px_5px_0px,rgba(0,0,0,0.1)_0px_0px_1px_0px]'>
        <Box padding="6">
          <div className='relative w-full h-0 pb-[60%] group rounded overflow-hidden'>
            <img src={props.item.src} className='absolute top-0 left-0 w-full h-full object-cover z-10' />
          </div>
          <div className='flex items-center justify-between mt-5'>
            <Text variant="headingSm" as="p" fontWeight='semibold'>
              {props.item.title}
            </Text>
            <div onClick={toggleActive} className='cursor-pointer'>
              <Icon
                source={ViewMinor}
                color="subdued"
              />
            </div>
            {active &&
              <div className='fixed flex justify-center z-[999] items-end sm:items-center top-0 left-0 right-0 bottom-0 bg-black bg-opacity-30'>
                <div onClick={toggleActive} className='absolute top-0 left-0 w-full h-full z-[1000]'></div>
                <div className='w-full sm:max-w-[400px] z-[9999] bg-white sm:rounded-lg relative'>
                  <div onClick={toggleActive} className='absolute right-4 top-4 cursor-pointer'>
                    <Icon
                      source={CancelMajor}
                      color="base"
                    />
                  </div>
                  <div className='py-8 px-5'>
                    <div className='my-5 px-2 sm:px-5 max-h-[75vh] min-h-[50vh] overflow-y-scroll scroll'>
                      <img src={props.item.preview_img}/>
                    </div>
                  </div>
                </div>
              </div>
              
            }
          </div>
        </Box>
      </div>
      
    );
  };
export default PreviewTheme