import React, { useState, useMemo, useCallback, useImperativeHandle, forwardRef } from 'react';
import PropTypes from 'prop-types';
import { Select as PolarisSelect } from '@shopify/polaris';

function useSelect(props, ref) {
  const { value: providedValue, onChange: providedOnChange, validate, buildMutationInput } = props;
  const [talonSelectedValue, setSelectedValue] = useState();
  const [error, setError] = useState();

  const selectedValue = useMemo(() => {
    return providedValue || talonSelectedValue;
  }, [providedValue, talonSelectedValue]);

  const handleSelectChange = useCallback(
    value => {
      if (providedOnChange) {
        providedOnChange(value);
        return;
      }
      setSelectedValue(value);
    },
    [providedOnChange],
  );
  const onFocus = useCallback(() => {
    setError(undefined);
  }, []);

  useImperativeHandle(
    ref,
    () => {
      return {
        validate: () => {
          setError(undefined);
          if (typeof validate === 'function') {
            const validateResult = validate(selectedValue);
            if (validateResult === true) {
              return true;
            }
            if (typeof validateResult !== 'string') {
              setError('Please validate data.');
              return false;
            }
            setError(validateResult);
            return false;
          }
          return true;
        },
        // getValue: () => selectedValue,
        // setValue: value => handleSelectChange(value),
        buildMutationInput: () => {
          if (buildMutationInput) {
            return buildMutationInput(selectedValue);
          }
          return selectedValue;
        },
      };
    },
    [selectedValue],
  );

  return {
    selectedValue,
    handleSelectChange,
    error,
    onFocus,
  };
}

const Select = (props, ref) => {
  const talonProps = useSelect(props, ref);
  const { selectedValue, handleSelectChange, error, onFocus } = talonProps;

  return <PolarisSelect {...props} onChange={handleSelectChange} value={selectedValue} error={error} onFocus={onFocus} />;
};

// Select.propTypes = {
//   value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
//   onChange: PropTypes.func,
// };

export default React.memo(forwardRef(Select));
