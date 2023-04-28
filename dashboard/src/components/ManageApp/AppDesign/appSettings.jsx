import React from "react";
import { Text, Divider,  TextField} from '@shopify/polaris';

const AppSettings = props => {

  return (
    <div>
      <div className="pt-6 pb-4">
        <Text variant="headingLg" as="h2" fontWeight='semibold'>
          App Settings
        </Text>
      </div>
      <Divider />
      <div className="flex justify-end items-center my-10">
        <p className="w-[26%] text-end">App name</p>
        <div className="w-[74%] pl-4">
          <TextField
          autoComplete="off"
          />
        </div>
      </div>
    </div>
  );
}
export default React.memo(AppSettings);