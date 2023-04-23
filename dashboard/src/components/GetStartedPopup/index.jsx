import { gql, useQuery, useMutation } from '@apollo/client';
import { useI18n } from '@shopify/react-i18n';
import React, { useCallback } from 'react';
import { LegacyCard, Page, Toast, Frame } from '@shopify/polaris';
import { SimiGroupCheck, SimiSelect, SimiTextField } from '@simpify/components/GetStartedPopup/Fields';

const UPDATE_SHOP_INFO_MUTATION = gql`
  mutation updateShopInformation($input: SimpifyShopInput!) {
    updateShopInformation(input: $input) {
      uid
      more_info {
        industry
        shop_owner_name
        shop_owner_email
        how_you_know_us {
          label
          value
        }
      }
    }
  }
`;

const useGetStartedForm = props => {
  const fieldRefs = React.useRef([]);
  const [isLoading, setIsLoading] = React.useState(false);
  const [error, setError] = React.useState();

  const listIndustries = React.useMemo(() => {
    return [
      { label: '--- Please select ---', value: '' },
      { label: 'Arts and crafts', value: 'arts&crafts' },
      { label: 'Business equipment and supplies', value: 'beas' },
      { label: 'Food and drink.', value: 'food' },
      { label: 'ardware and automotive.', value: 'hardware' },
    ];
  }, []);

  const listKnows = React.useMemo(() => {
    return [
      { label: 'Facebook', value: 'fb' },
      { label: 'Google', value: 'gg' },
      { label: 'Shopify Market', value: 'shopify' },
    ];
  }, []);

  const [updateShopInfo, { loading, error: mutationError }] = useMutation(UPDATE_SHOP_INFO_MUTATION);

  const handleSubmit = useCallback(() => {
    let allValid = true;
    let moreInfoPayload = {};
    Object.keys(fieldRefs.current).every(fieldK => {
      if (typeof fieldRefs.current[fieldK]?.validate === 'function') {
        const validateResult = fieldRefs.current[fieldK]?.validate();
        if (!validateResult) {
          allValid = false;
          return true;
        }
      }

      moreInfoPayload[fieldK] = fieldRefs.current[fieldK].buildMutationInput();

      return true;
    });

    if (allValid) {
      // setIsLoading(true);
      updateShopInfo({ variables: { input: { more_info: moreInfoPayload } } });
      // setIsLoading(false);
    }
  }, []);

  const dismissToast = useCallback(() => {
    setError(undefined);
  }, []);

  const buildMutationInputForHowKnow = useCallback(
    fieldValue => {
      return listKnows.filter(i => fieldValue.includes(i.value));
    },
    [listKnows],
  );

  React.useEffect(() => {
    mutationError && setError(mutationError?.message);
  }, [mutationError]);

  return {
    listKnows,
    listIndustries,
    fieldRefs,
    handleSubmit,
    isLoading: loading,
    dismissToast,
    error,
    buildMutationInputForHowKnow,
  };
};

const GetStartedForm = props => {
  const [i18n] = useI18n();
  const talonProps = useGetStartedForm();
  const { listKnows, listIndustries, fieldRefs, handleSubmit, isLoading, error, dismissToast, buildMutationInputForHowKnow } = talonProps;

  const toastMarkup = error ? <Toast content={error} error onDismiss={dismissToast} /> : null;

  const validateRequired = React.useCallback(val => {
    if (!val) {
      return i18n.translate('SimiCart.GetStarted.Form.RequiredFieldValidateMessage');
    }
    return true;
  }, []);

  return (
    <Page fullWidth>
      <div className='md:max-w-[30vw] max-w-[600px] mx-auto p-4 min-[30.625em]:p-0'>
        <LegacyCard
          title={<span className={'font-semibold'}>{i18n.translate('SimiCart.GetStarted.Title')}</span>}
          sectioned
          primaryFooterAction={{
            content: i18n.translate('SimiCart.GetStarted.SubmitButtonTitle'),
            onAction: handleSubmit,
            loading: isLoading,
          }}>
          <div className='flex flex-col gap-4'>
            <SimiSelect
              ref={ref => (fieldRefs.current['industry'] = ref)}
              label={
                <span className={'font-semibold'}>
                  {i18n.translate('SimiCart.GetStarted.Form.Industry')} <strong className='text-[var(--p-color-text-critical)]'>*</strong>
                </span>
              }
              options={listIndustries}
              name={'industry'}
              validate={validateRequired}
            />
            <SimiTextField
              ref={ref => (fieldRefs.current['shop_owner_name'] = ref)}
              label={
                <span className='font-semibold'>
                  {i18n.translate('SimiCart.GetStarted.Form.YourName')} <strong className='text-[var(--p-color-text-critical)]'>*</strong>
                </span>
              }
              name='shop_owner_name'
              clearButton
              autoComplete='off'
              placeholder={i18n.translate('SimiCart.GetStarted.Form.YourNamePlaceholder')}
              validate={validateRequired}
            />
            <SimiTextField
              ref={ref => (fieldRefs.current['shop_owner_email'] = ref)}
              label={
                <span className='font-semibold'>
                  {i18n.translate('SimiCart.GetStarted.Form.YourEmail')} <strong className='text-[var(--p-color-text-critical)]'>*</strong>
                </span>
              }
              type='email'
              name='shop_owner_email'
              clearButton
              autoComplete='email'
              placeholder={i18n.translate('SimiCart.GetStarted.Form.YourEmailPlaceholder')}
              validate={validateRequired}
            />

            <SimiGroupCheck
              ref={ref => (fieldRefs.current['how_you_know_us'] = ref)}
              allowMultiple
              choices={listKnows}
              title={i18n.translate('SimiCart.GetStarted.Form.howYouKnowLabel')}
              name='how_you_know_us'
              buildMutationInput={buildMutationInputForHowKnow}
            />
          </div>
        </LegacyCard>
      </div>
      <Frame>{toastMarkup}</Frame>
    </Page>
  );
};

export default GetStartedForm;
