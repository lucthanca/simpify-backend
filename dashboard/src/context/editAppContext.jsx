import { gql, useQuery } from '@apollo/client';
import { createContext, useContext, useMemo, useState } from "react";
import { useParams } from 'react-router';

const EditAppContext = createContext(undefined);

const EditAppContextProvider = props => {

    const {  children} = props;
    const param = useParams();


    const [activeTab, setActiveTab] = useState('app_design');

    const state = useMemo(() => {
        return {
            activeTab,
        };
    }, [activeTab]);

    const api = useMemo(() => {
        return {
            setActiveTab,
        }
    }, [setActiveTab]);

    const contextValue = useMemo(() => ([state, api]), [state, api]);

    return <EditAppContext.Provider value={contextValue}>{children}</EditAppContext.Provider>
}
export default EditAppContextProvider;

export const useEditAppContext = () => useContext(EditAppContext);