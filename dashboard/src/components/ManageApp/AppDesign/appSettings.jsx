import React, { useState } from "react";
import { Text, Divider, TextField, DropZone, Icon} from '@shopify/polaris';
import { UploadMajor} from '@shopify/polaris-icons';


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
      <div className="grid grid-cols-3 gap-2 mt-10">
        <AppLogo/>
        <AppIcon/>
        <LoadingScreen/>
      </div>
      <div className="flex justify-end items-center my-10">
        <p className="w-[26%] text-end">Splash Screen Color</p>
        <div className="w-[74%] pl-4">
          <TextField
          autoComplete="off"
          />
        </div>
      </div>
      <div className="flex justify-end items-center my-10">
        <p className="w-[26%] text-end">Loading Splash Screen Color</p>
        <div className="w-[74%] pl-4">
          <TextField
          autoComplete="off"
          />
        </div>
      </div>
    </div>
  );
}


const AppLogo = () => {
  const [selectedImage, setSelectedImage] = useState(null);
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">App logo</p>
      <div className="h-0 pb-[65%] relative rounded-md border border-dashed border-[var(--p-color-border-primary)]">
        {selectedImage && (
          <div className="w-[90%] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <img
              className="aspect-[6] object-cover"
              alt="logo app"
              width={"250px"}
              src={URL.createObjectURL(selectedImage)}
            />
          </div>
        )}
        <div className="absolute bottom-3 right-3">
          <label className="cursor-pointer" htmlFor="logo-app">
            <Icon
              source={UploadMajor}
              color="primary"
            />
          </label>
          <input
            id="logo-app" 
            className="absolute w-1 h-1 opacity-0"
            type="file"
            name="img"
            onChange={(event) => {
              setSelectedImage(event.target.files[0]);
            }}
          />
        </div>
      </div>
      <div>
        <p className="py-3 text-center text-sm font-medium">preview</p>
        <div className="text-center font-medium text-xs">
          <Text variant="bodySm" as="p">
            PNG file
          </Text>
          <Text variant="bodySm" as="p" color="subdued">
            480 x 80 px
          </Text>
        </div>
      </div>
    </div>
  )
}
const AppIcon = () => {
  const [selectedImage, setSelectedImage] = useState(null);
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">App logo</p>
      <div className="h-0 pb-[65%] relative rounded-md border border-dashed border-[var(--p-color-border-primary)]">
        {selectedImage && (
          <div className="w-[40%] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <img
              className="aspect-square object-cover"
              alt="logo app"
              width={"250px"}
              src={URL.createObjectURL(selectedImage)}
            />
          </div>
        )}
        <div className="absolute bottom-3 right-3">
          <label className="cursor-pointer" htmlFor="logo-icon">
            <Icon
              source={UploadMajor}
              color="primary"
            />
          </label>
          <input
            id="logo-icon" 
            className="absolute w-1 h-1 opacity-0"
            type="file"
            name="img"
            onChange={(event) => {
              setSelectedImage(event.target.files[0]);
            }}
          />
        </div>
      </div>
      <div>
        <p className="py-3 text-center text-sm font-medium">preview</p>
        <div className="text-center font-medium text-xs">
          <Text variant="bodySm" as="p">
            PNG file
          </Text>
          <Text variant="bodySm" as="p" color="subdued">
            480 x 80 px
          </Text>
        </div>
      </div>
    </div>
  )
}
const LoadingScreen = () => {
  const [selectedImage, setSelectedImage] = useState(null);
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">App logo</p>
      <div className="h-0 pb-[65%] relative rounded-md border border-dashed border-[var(--p-color-border-primary)]">
        {selectedImage && (
          <div className="w-[40%] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <img
              className="aspect-square object-cover"
              alt="loading screen"
              width={"250px"}
              src={URL.createObjectURL(selectedImage)}
            />
          </div>
        )}
        <div className="absolute bottom-3 right-3">
          <label className="cursor-pointer" htmlFor="loading-screen">
            <Icon
              source={UploadMajor}
              color="primary"
            />
          </label>
          <input
            id="loading-screen" 
            className="absolute w-1 h-1 opacity-0"
            type="file"
            name="img"
            onChange={(event) => {
              setSelectedImage(event.target.files[0]);
            }}
          />
        </div>
      </div>
      <div>
        <p className="py-3 text-center text-sm font-medium">preview</p>
        <div className="text-center font-medium text-xs">
          <Text variant="bodySm" as="p">
            PNG file
          </Text>
          <Text variant="bodySm" as="p" color="subdued">
            480 x 80 px
          </Text>
        </div>
      </div>
    </div>
  )
}
export default React.memo(AppSettings);