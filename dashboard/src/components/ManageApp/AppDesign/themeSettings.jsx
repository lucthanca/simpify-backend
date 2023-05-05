import React, { useCallback, useState, useEffect }  from "react";
import { useI18n } from '@shopify/react-i18n';
import { Text, Divider, ButtonGroup, Button, TextField, ColorPicker, Popover, hsbToHex} from '@shopify/polaris';
import EditAppContextProvider, { useEditAppContext } from '@simpify/context/editAppContext';


const ThemeSettings = props => {
  return (
    <EditAppContextProvider>
      <Content {...props} />
    </EditAppContextProvider>
  )
}
const Content = props => {
  const [i18n] = useI18n();
  const [{activeTab},{ setActiveTab }] = useEditAppContext();
  React.useEffect(() => {
    setActiveTab('app_color');
  },[]);
  const ActiveContent = React.useMemo(() => {
    switch(activeTab) {
      case 'app_color':
        return AppColor;
      case 'app_font':
        return AppFont;
      default :
        return AppColor 
    }
  }, [activeTab]);
  return (
    <>
      <div className="pt-6 flex">
        <Text variant="headingLg" as="h2" fontWeight='semibold'>
          {i18n.translate('SimiCart.Page.ManageApp.edit_app.theme_settings.title')}
        </Text>
        <MenuBar/>
      </div>
      <Divider />
      <React.Suspense fallback={<div>Loading..........</div>}>
        <ActiveContent />
      </React.Suspense>
    </>
  );
}
const MenuBar = () => {
  const [{ activeTab } ,{ setActiveTab }] = useEditAppContext();

  const handleClickTab = useCallback((tabId) => {
    setActiveTab(tabId);
  }, []);
  return (
  <>
    <div className='flex gap-2 mx-auto'>
      <div className={ (activeTab === 'app_color') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('app_color')}>
          App Color
        </p>
      </div>
      <div className={ (activeTab === 'app_font') ? "active menu-editapp":'menu-editapp' }>
        <p className='py-2 px-3 text-sm font-medium cursor-pointer' onClick={() => handleClickTab('app_font')}>
          App Font
        </p>
      </div>
    </div>
  </>
  );
}
const dataColor = [
  {id: 'cl1', title: 'Key color', color_default: '#000'},
  {id: 'cl2', title: 'Top menu icon color', color_default: '#ffff'},
  {id: 'cl3', title: 'Button background', color_default: '#000'},
  {id: 'cl4', title: 'Button text color', color_default: '#000'},
  {id: 'cl5', title: 'Menu background', color_default: '#000'},
  {id: 'cl6', title: 'Menu text color', color_default: '#000'},
  {id: 'cl7', title: 'Menu line color', color_default: '#000'},
  {id: 'cl8', title: 'Menu icon color', color_default: '#000'},
]
const AppColor = () => {
  const [i18n] = useI18n();
  return (
    <>
      <div className="py-5">
        <Text variant="headingSm" as="p"> 
          1. Select basic color
        </Text>
      </div>
      {dataColor.map((item) =>
        <div key={item.id} className="flex justify-end items-center my-4">
          <p className="w-[26%] text-end capitalize">{item.title}</p>
          <div className="w-[74%] pl-4 relative">
            <TextFieldColor color_default={item.color_default}/>
          </div>
        </div>
      )}
      <Divider />
      <div className="flex justify-center py-5">
        <ButtonGroup>
          <Button primary>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_save')}</Button>
          <Button>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_reset')}</Button>
        </ButtonGroup>
      </div>
    </>
  )
}
const AppFont = () => {
  const [i18n] = useI18n();
  return (
    <div>
      font
      <Divider />
      <div className="flex justify-center py-5">
        <ButtonGroup>
          <Button primary>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_save')}</Button>
          <Button>{i18n.translate('SimiCart.Page.ManageApp.edit_app.button_reset')}</Button>
        </ButtonGroup>
      </div>
    </div>
  )
}
const TextFieldColor = (props) => {
  console.log(props.color_default);
  const [color, setColor] = useState({
    hue: 0,
    brightness: 0,
    saturation: 0,
  });
  const [hexColor, setHexColor] = useState('');
  useEffect(() => {
    setHexColor(hsbToHex(color));
  }, [color]);
  useEffect(() => {
    setHexColor(props.color_default);
  }, []);
  
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
    <>
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
    </>
  )
}
export default React.memo(ThemeSettings);