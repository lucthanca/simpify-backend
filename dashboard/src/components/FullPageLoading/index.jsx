import React from 'react';
import PropTypes from 'prop-types';
import '@simpify/components/FullPageLoading/styles.css';
import { motion } from 'framer-motion';

const Index = props => {
  const loadingVariants = {
    hidden: {
      x: '100vw',
      opacity: 1,
    },
    visible: {
      x: '0',
      opacity: 1,
      transition: { duration: 0.8, type: 'spring', stiffness: 80, mass: 0.5 },
    },
    exit: {
      x: '-100vw',
      opacity: 1,
      transition: { delay: 0.8, duration: 0.5, type: 'spring', stiffness: 80, mass: 0.5 },
    },
  };

  return (
    <motion.div className='loading-bg' variants={loadingVariants} initial='hidden' animate='visible' exit='exit'>
      <div id='loader'>
        <div id='loader_shadow'></div>
        <div id='loader_box'></div>
        <div className='loader_text'>
          <h3 className='text-2xl text-white'>Loading...</h3>
        </div>
      </div>
    </motion.div>
  );
};

Index.propTypes = {};

export default Index;
