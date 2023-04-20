import React from 'react';
import { Button, Modal, VerticalStack } from '@shopify/polaris';
import { useState, useCallback, useRef } from 'react';
import PropTypes from 'prop-types';

const Popup = props => {
  const [active, setActive] = useState(true);

  const buttonRef = useRef(null);

  const handleOpen = useCallback(() => setActive(true), []);

  const handleClose = useCallback(() => {
    setActive(false);
  }, []);

  const activator = (
    <div ref={buttonRef}>
      <Button onClick={handleOpen}>Open</Button>
    </div>
  );

  return (
    <div style={{ height: '500px' }}>
      {activator}
      <Modal
        activator={buttonRef}
        open={active}
        onClose={handleClose}
        title='Reach more shoppers with Instagram product tags'
        primaryAction={{
          content: 'Add Instagram',
          onAction: handleClose,
        }}
        secondaryActions={[
          {
            content: 'Learn more',
            onAction: handleClose,
          },
        ]}>
        <Modal.Section>
          <VerticalStack>
            <p>Use Instagram posts to share your products with millions of people. Let shoppers buy from your store without leaving Instagram.</p>
          </VerticalStack>
        </Modal.Section>
      </Modal>
    </div>
  );
};

Popup.propTypes = {};

export default Popup;
