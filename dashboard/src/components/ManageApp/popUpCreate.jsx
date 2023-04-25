import { useState } from 'react';
import React, { useCallback } from 'react';
import { Text, Box, Icon, Modal, Button, TextField } from '@shopify/polaris';
import { AddMajor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';

const CreateApp = () => {
    const [i18n] = useI18n();
    const [active, setActive] = useState(false);
    const [titlename, setTitle] = useState('');
    const toggleActive = useCallback(() => setActive((active) => !active), []);
    const handleChange = useCallback(value =>{
      setTitle(value);
      console.log(titlename);
      
      titlename == ''? setDisabled(true): setDisabled(false);
      console.log(disabled);
    }, []);
    const activator = 
      <div onClick={toggleActive} className='mb-5 scale-150 cursor-pointer'>
        <Icon
          source={AddMajor}
          color="subdued"
        />
      </div>;
    return (
      <>
        {activator}
        <Modal
          small
          open={active}
          onClose={toggleActive}
          titleHidden
        >
          <Modal.Section>
            <Box paddingBlockStart='4' paddingInlineStart='5' paddingInlineEnd='5'>
              <Box paddingBlockEnd='2'>
                <Text variant="headingMd" as="p" fontWeight='semibold'> 
                  {i18n.translate('SimiCart.Page.ManageApp.create.title')}
                </Text>
              </Box>
              <TextField
                value={titlename}
                onChange={handleChange}
                autoComplete="off"
              />
              <div className='my-4 flex justify-center'>
                <Button primary>{i18n.translate('SimiCart.Page.ManageApp.create.button')}</Button>
              </div>
            </Box>
          </Modal.Section>
        </Modal>
      </>
    );
  };
export default CreateApp