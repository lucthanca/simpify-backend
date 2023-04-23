import React, { useMemo, useState, useCallback, useImperativeHandle } from 'react';
import PropTypes from 'prop-types';
import { TextField as PolarisTextField } from '@shopify/polaris';

function useTextField(props, ref) {
  const { providedOnChange, providedValue, providedOnClearButtonClick, validate, buildMutationInput } = props;
  const [talonValue, setValue] = useState();
  const [error, setError] = useState();

  const onChange = useCallback(value => {
    if (typeof providedOnChange === 'function') {
      providedOnChange(value);
      return;
    }
    setValue(value);
  }, []);

  const value = useMemo(() => {
    return providedValue || talonValue;
  }, [providedValue, talonValue]);

  const handleClear = useCallback(() => {
    if (typeof providedOnClearButtonClick === 'function') {
      providedOnClearButtonClick();
      return;
    }
    setValue(undefined);
  }, []);

  useImperativeHandle(
    ref,
    () => {
      return {
        validate: () => {
          setError(undefined);
          if (typeof validate === 'function') {
            const validateResult = validate(value);
            if (validateResult === true) {
              return true;
            }
            if (typeof validateResult !== 'string') {
              setError('Please validate data.');
              return true;
            }
            setError(validateResult);
            return false;
          }
          return true;
        },
        // getValue: () => value,
        // setValue: val => setValue(val),
        buildMutationInput: () => {
          if (buildMutationInput) {
            return buildMutationInput(value);
          }
          return value;
        },
      };
    },
    [value],
  );
  const onFocus = useCallback(() => {
    setError(undefined);
  }, []);

  return {
    value,
    onChange,
    handleClear,
    error,
    onFocus,
  };
}

const TextField = (props, ref) => {
  const { onChange: providedOnChange, value: providedValue, validate, buildMutationInput, ...restProps } = props;
  const talonProps = useTextField({ providedOnChange, providedValue, validate, buildMutationInput }, ref);
  const { onChange: talonOnChange, value, handleClear, error, onFocus } = talonProps;

  return <PolarisTextField {...restProps} onChange={talonOnChange} value={value} onClearButtonClick={handleClear} error={error} onFocus={onFocus} />;
};

// TextField.propTypes = {
//   onChange: PropTypes.func,
//   value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
//   validate: PropTypes.func,
// };

export default React.memo(React.forwardRef(TextField));
