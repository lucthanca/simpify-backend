import React, { useState, useRef, useCallback, useEffect } from "react";
import { Text, Divider, TextField, Popover, Icon, ColorPicker, hsbToHex, Modal} from '@shopify/polaris';
import { UploadMajor} from '@shopify/polaris-icons';
// import Cropper from "react-cropper";
// import "cropperjs/dist/cropper.css";

import { FixedCropper, ImageRestriction, CropperPreview  } from 'react-advanced-cropper';
import 'react-advanced-cropper/dist/style.css'

const AppSettings = props => {

  return (
    <>
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
      <ScreenColor/>
      <LoadingColor/>
    </>
  );
}

const ScreenColor = () => {
  const [color, setColor] = useState({
    hue: 120,
    brightness: 1,
    saturation: 1,
  });
  const [hexColor, setHexColor] = useState('');
  useEffect(() => {
    setHexColor(hsbToHex(color));
  }, [color]);
  const [popoverActive, setPopoverActive] = useState(false);
  const togglePopoverActive = useCallback(
    () => setPopoverActive((popoverActive) => !popoverActive),
    [],
  );
  const handleChange = useCallback(
    (newValue) => 
    setHexColor(newValue),
    [],
  );
  const activator = (
    <button className="absolute right-[1px] top-0 z-50 w-9 h-full p-2 border-x-0 rounded-sm border border-[var(--p-color-border-input)] hover:border-[var(--p-color-border-input)]" onClick={togglePopoverActive}>
        <div className="h-full w-full"
          style={{
            background: hexColor
          }}
        />
    </button>
  )
  
  return (
    <div className="flex justify-end items-center my-10">
      <p className="w-[26%] text-end">Splash Screen Color</p>
      <div className="w-[74%] pl-4 relative">
        <TextField
          autoComplete="off"
          value={hexColor}
          onChange={handleChange}
        />
        <Popover
          active={popoverActive}
          activator={activator}
          onClose={togglePopoverActive}
        >
          <Popover.Section>
            <ColorPicker
              onChange={setColor}
              color={color}
            />
          </Popover.Section>
        </Popover>
      </div>
    </div>
  )
}
const LoadingColor = () => {
  const [color, setColor] = useState({
    hue: 120,
    brightness: 1,
    saturation: 1,
  });
  const [hexColor, setHexColor] = useState('');
  useEffect(() => {
    setHexColor(hsbToHex(color));
  }, [color]);
  const [popoverActive, setPopoverActive] = useState(false);
  const togglePopoverActive = useCallback(
    () => setPopoverActive((popoverActive) => !popoverActive),
    [],
  );
  const handleChange = useCallback(
    (newValue) => 
    setHexColor(newValue),
    [],
  );
  const activator = (
    <button className="absolute right-[1px] top-0 z-50 w-9 h-full p-2 border-x-0 rounded-sm border border-[var(--p-color-border-input)] hover:border-[var(--p-color-border-input)]" onClick={togglePopoverActive}>
        <div className="h-full w-full"
          style={{
            background: hexColor
          }}
        />
    </button>
  )
  
  return (
    <div className="flex justify-end items-center my-10">
      <p className="w-[26%] text-end">Loading Splash Screen Color</p>
      <div className="w-[74%] pl-4 relative">
        <TextField
          autoComplete="off"
          value={hexColor}
          onChange={handleChange}
        />
        <Popover
          active={popoverActive}
          activator={activator}
          onClose={togglePopoverActive}
        >
          <Popover.Section>
            <ColorPicker
              onChange={setColor}
              color={color}
            />
          </Popover.Section>
        </Popover>
      </div>
    </div>
  )
}

const AppLogo = () => {
  const [selectedImage, setSelectedImage] = useState(null);
  const [cropImage, setCropImage] = useState(null);
  const imageCrop = (value) => setCropImage((value));
  const stencilSize = {width: 480, height: 80};
  
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">App logo</p>
      <div className="h-0 pb-[65%] relative rounded-md border border-dashed border-[var(--p-color-border-primary)]">
        {selectedImage && (
          <>
            <div className="w-[90%] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
              <img
                className="aspect-[6] object-cover"
                alt="logo app"
                width={"250px"}
                src={cropImage ? cropImage : URL.createObjectURL(selectedImage)}
              />
            </div>
            <CropperImage selectedImage={URL.createObjectURL(selectedImage)} imageCrop={imageCrop} stencilSize={stencilSize}/>
          </>
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
  const [cropImage, setCropImage] = useState(null);
  const imageCrop = (value) => setCropImage((value));
  const stencilSize = {width: 480, height: 480};
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
            <CropperImage selectedImage={URL.createObjectURL(selectedImage)} imageCrop={imageCrop} stencilSize={stencilSize}/>
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
            480 x 480 px
          </Text>
        </div>
      </div>
    </div>
  )
}
const LoadingScreen = () => {
  const [selectedImage, setSelectedImage] = useState(null);
  const [cropImage, setCropImage] = useState(null);
  const imageCrop = (value) => setCropImage((value));
  const stencilSize = {width: 480, height: 480};
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
            <CropperImage selectedImage={URL.createObjectURL(selectedImage)} imageCrop={imageCrop} stencilSize={stencilSize}/>
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
            480 x 480 px
          </Text>
        </div>
      </div>
    </div>
  )
}
const CropperImage = props => {
  const [active, setActive] = useState(true);
  const toggleActive = useCallback(() => setActive((active) => !active), []);
  const cropperRef = useRef(null);
  const previewRef = useRef(null);
  const onUpdate = () => {
    previewRef.current?.refresh()
  }
  const Cropp = () => {
    if (cropperRef.current) {
      props.imageCrop(cropperRef.current.getCanvas()?.toDataURL());
    }
    setActive(false);
  }
  return (
    <Modal
      large
      open={active}
      onClose={toggleActive}
      primaryAction={{
        content: 'Cropp',
        onAction: Cropp,
      }}
    >
      <Modal.Section>
        <div className='grid grid-cols-1 sm:grid-cols-2 p-1 gap-5 mt-5 max-h-[65vh] sm:max-h-[70vh] overflow-y-scroll scroll'>
          <FixedCropper
            src={props.selectedImage}
            onChange={onUpdate}
            className={'cropper'}
            ref={cropperRef}
            stencilSize={props.stencilSize}
            stencilProps={{
              previewClassName: 'image-editor__preview'
            }}
            imageRestriction={ImageRestriction.stencil}
          />
          <CropperPreview
            className={"image-editor__preview"}
            ref={previewRef}
            cropper={cropperRef}
          />
        </div>
      </Modal.Section>
    </Modal>
      
  )
};
export default React.memo(AppSettings);