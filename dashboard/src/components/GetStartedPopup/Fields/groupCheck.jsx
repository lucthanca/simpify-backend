import React, { useCallback, useMemo, useImperativeHandle, useState } from 'react';
import PropTypes from 'prop-types';
import { RadioButton, Checkbox } from '@shopify/polaris';

function useGroupCheck(props, ref) {
  const { validate, allowMultiple, buildMutationInput } = props;
  const [selectedValue, setSelectedValue] = useState([]);
  const [error, setError] = useState();
  const handleSelectValue = useCallback((isChecked, value) => {
    setSelectedValue(prev => {
      if (allowMultiple) {
        if (isChecked) {
          return [...prev, value];
        }
        return prev.filter(i => i !== value);
      }
      return [value];
    });
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
        // setValue: val => setSelectedValue(),
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
    handleSelectValue,
    error,
  };
}

const GroupCheck = (props, ref) => {
  const { title, choices, allowMultiple = false, ...restProps } = props;
  const talonProps = useGroupCheck(props, ref);
  const { selectedValue, handleSelectValue, error } = talonProps;
  // return <ChoiceList {...props} selected={selectedValue} onChange={handleSelectValue} error={error} />;

  const CheckField = useMemo(() => {
    if (allowMultiple) {
      return Checkbox;
    }
    return RadioButton;
  }, [allowMultiple]);

  return (
    <div>
      <div className='Polaris-Labelled__LabelWrapper'>
        <div className='Polaris-Label'>
          <label className='font-semibold'>{title}</label>
        </div>
      </div>
      <div className='flex gap-4'>
        {choices?.length > 0 &&
          choices.map(it => (
            <CheckField
              {...restProps}
              key={it.value}
              label={it.label}
              checked={selectedValue.includes(it.value)}
              onChange={checked => handleSelectValue(checked, it.value)}
            />
          ))}
      </div>
      {error && (
        <div className='Polaris-Labelled__Error'>
          <div id='PolarisTextField4Error' className='Polaris-InlineError'>
            <div className='Polaris-InlineError__Icon'>
              <span className='Polaris-Icon'>
                <span className='Polaris-Text--root Polaris-Text--visuallyHidden'></span>
                <svg viewBox='0 0 20 20' className='Polaris-Icon__Svg' focusable='false' aria-hidden='true'>
                  <path
                    fillRule='evenodd'
                    d='M11.42 2.588a2.007 2.007 0 0 0-2.84 0l-5.992 5.993a2.007 2.007 0 0 0 0 2.838l5.993 5.993a2.007 2.007 0 0 0 2.838 0l5.993-5.993a2.007 2.007 0 0 0 0-2.838l-5.993-5.993Zm-2.223 4.2a.803.803 0 1 1 1.606 0v3.212a.803.803 0 1 1-1.606 0v-3.211Zm1.606 6.423a.803.803 0 1 1-1.606 0 .803.803 0 0 1 1.606 0Z'></path>
                </svg>
              </span>
            </div>
            {error}
          </div>
        </div>
      )}
    </div>
  );
};

// GroupCheck.propTypes = {
//   title: PropTypes.oneOfType([PropTypes.string, PropTypes.node]),
//   choices: PropTypes.arrayOf(PropTypes.object),
//   allowMultiple: PropTypes.bool,
// };

export default React.memo(React.forwardRef(GroupCheck));
