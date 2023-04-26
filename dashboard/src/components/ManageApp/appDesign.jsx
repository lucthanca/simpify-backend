import React from "react";
import { Link, Outlet, NavLink } from 'react-router-dom';

const AppDesign = props => {

  return (
    <div>
      <Navigation/>
    </div>
  );
}
const Navigation = () => {
  return (
    <div className="flex flex-col w-[100px]">
      <NavLink to="./appsettings">App Settings</NavLink>
      <NavLink to="./themesettings">Theme Settings</NavLink>
      <NavLink to="./pages">Pages</NavLink>
      <NavLink to="./menuitem">Menu Items</NavLink>
    </div>
  )
}
export default React.memo(AppDesign);