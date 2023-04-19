import React, { useMemo, createContext, useContext, useState } from 'react';
import { connect } from 'react-redux';
import { increment, decrement, incrementByAmount } from '@simpify/store/example';
import bindActionCreators from '@simpify/utils/bindActionCreators';
import { useLocation } from "react-router-dom";

const AppContext = createContext(undefined);

const AppContextProvider = props => {
  const { actions, appState: reduxAppState, children } = props;
  const location = useLocation();
  const search = new URLSearchParams(location.search);
  const accessKey = search.get('x-simi-access');
  const apiKey = search.get('app-api-key'); // using url param for flexible change app

  const [xSimiAccessKey, setSimiKey] = useState(accessKey);

  const appApi = useMemo(() => ({ ...actions, setSimiKey }), [actions, setSimiKey]);
  const appState = useMemo(() => {
    return {
      ...reduxAppState,
      xSimiAccessKey,
      apiKey,
    };
  }, [reduxAppState]);
  const contextValue = useMemo(() => [appState, appApi], [appApi, appState]);

  return <AppContext.Provider value={contextValue}>{children}</AppContext.Provider>;
};

const mapStateToProps = ({ app }) => ({ appState: app });

const mapDispatchToProps = dispatch => ({
  actions: bindActionCreators({ increment, decrement, incrementByAmount }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(AppContextProvider);

export const useAppContext = () => useContext(AppContext);
