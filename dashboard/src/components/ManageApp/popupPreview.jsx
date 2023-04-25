import { useState } from 'react';
import React, { useCallback } from 'react';
import { Text, Box, Modal, Button, Icon } from '@shopify/polaris';
import { ViewMinor, AddMajor} from '@shopify/polaris-icons';
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
            <Modal
              small
              open={active}
              onClose={toggleActive}
              titleHidden
            >
              <Modal.Section>
                <Box paddingBlockStart='4'>
                  <div className='my-5 px-2 sm:px-5 max-h-[70vh] overflow-y-scroll scroll'>
                    <img src={props.item.preview_img}/>
                  </div>
                </Box>
              </Modal.Section>
            </Modal>
          </div>
        </Box>
      </div>
      
    );
  };
export default PreviewTheme