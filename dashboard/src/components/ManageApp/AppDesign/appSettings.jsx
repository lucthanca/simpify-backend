import React, { useState, useRef, useCallback, useEffect } from "react";
import { Text, Divider, TextField, Popover, Icon, ColorPicker, hsbToHex, Modal, ButtonGroup, Button} from '@shopify/polaris';
import { UploadMajor} from '@shopify/polaris-icons';
import { useI18n } from '@shopify/react-i18n';
import { FixedCropper, ImageRestriction, CropperPreview  } from 'react-advanced-cropper';
import 'react-advanced-cropper/dist/style.css'

const AppSettings = props => {
  const [i18n] = useI18n();
  return (
    <>
      <div className="pt-6 pb-4">
        <Text variant="headingLg" as="h2" fontWeight='semibold'>
          {i18n.translate('SimiCart.Page.ManageApp.edit_app.app_settings.title')}
        </Text>
      </div>
      <Divider />
      <div className="flex justify-end items-center my-10">
        <p className="w-[26%] text-end">{i18n.translate('SimiCart.Page.ManageApp.edit_app.app_settings.app_name')}</p>
        <div className="w-[74%] pl-4">
          <AppName/>
        </div>
      </div>
      <div className="grid grid-cols-3 gap-2 mt-10">
        <AppLogo/>
        <AppIcon/>
        <LoadingScreen/>
      </div>
      <ScreenColor/>
      <LoadingColor/>
      <Divider />
      <div className="flex justify-center py-5">
        <ButtonGroup>
          <Button primary>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_save')}</Button>
          <Button>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_reset')}</Button>
        </ButtonGroup>
      </div>
    </>
  );
}
const AppName = () => {
  const [value, setValue] = useState('');
  const handleChange = useCallback((newValue) => setValue(newValue),[]);
  return (
    <TextField
      value={value}
      onChange={handleChange}
      autoComplete="off"
    />
  )
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
  const [i18n] = useI18n();
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">{i18n.translate('SimiCart.Page.ManageApp.edit_app.app_settings.app_logo')}</p>
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
            <CropperImage selectedImage={selectedImage} imageCrop={imageCrop} stencilSize={stencilSize}/>
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
  const [i18n] = useI18n();
  const imageCrop = (value) => setCropImage((value));
  const stencilSize = {width: 480, height: 480};
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">{i18n.translate('SimiCart.Page.ManageApp.edit_app.app_settings.app_icon')}</p>
      <div className="h-0 pb-[65%] relative rounded-md border border-dashed border-[var(--p-color-border-primary)]">
        {selectedImage && (
          <div className="w-[40%] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <img
              className="aspect-square object-cover"
              alt="logo app"
              width={"250px"}
              src={URL.createObjectURL(selectedImage)}
            />
            <CropperImage selectedImage={selectedImage} imageCrop={imageCrop} stencilSize={stencilSize}/>
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
  const [i18n] = useI18n();
  const imageCrop = (value) => setCropImage((value));
  const stencilSize = {width: 480, height: 480};
  return (
    <div>
      <p className="text-sm font-semibold text-center pb-6">{i18n.translate('SimiCart.Page.ManageApp.edit_app.app_settings.loading_screen')}</p>
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
  
  const Cropp = useCallback(() => {
    // if (cropperRef.current) {
    //   props.imageCrop(cropperRef.current.getCanvas()?.toDataURL());
    // }
    // setActive(false);
    const hasExtension = (fileName) => {
      const dotIndex = fileName.lastIndexOf('.');
      return dotIndex > 0 && dotIndex < fileName.length - 1;
    }
    const canvas = cropperRef.current?.getCanvas();
    if (canvas) {
      const form = new FormData();
      canvas.toBlob((blob) => {
        if (blob) {
          let blobFileName = props.selectedImage.name;
          if (!hasExtension(blobFileName)) {
            blobFileName += '.' + mimeToExt(props.selectedImage.type);
          }
          form.append('param_name', 'image');
          form.append('image', blob, blobFileName);
          
          // const token = storage.getItem('x-simify-token');
          const headers = new Headers({
            "Accept": "application/json, text/javascript, */*; q=0.01",
            'X-Simify-Token': "54vuy8j7socogm6dqz73gfuse8me5pdr"
          });
          const fetchOptions = {
            method: 'POST',
            body: form,
            headers,
          }
          fetch('https://simpify.lucthanca.tk/simpify/app_image/upload', fetchOptions).then((response) => {
            console.log('hello',response);
            if (response.status !== 200) {
              throw new Error('Server error. Please try again!');
            }
            const contentType = response.headers.get("content_type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
              return response.json();
            }
            throw new Error('Invalid response return. Please try again!');
          }).then(data => {
            console.log('data',data);
            if (data.error) {
              throw new Error(data.error);
            }
          }).catch(e => {
            // console.error(e.message);
            console.log('error');
          });
        }
      }, props.selectedImage.type);
    }
  }, [props.selectedImage])
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
        <div className='grid grid-cols-1 sm:grid-cols-2 p-1 gap-5 mt-5 max-h-[65vh] sm:max-h-[70vh] overflow-y-auto scroll'>
          <FixedCropper
            src={URL.createObjectURL(props.selectedImage)}
            onChange={onUpdate}
            className={'cropper'}
            ref={cropperRef}
            stencilSize={props.stencilSize}
            stencilProps={{
              previewClassName: 'image-editor__preview'
            }}
            imageRestriction={ImageRestriction.none}
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