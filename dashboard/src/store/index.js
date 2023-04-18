import { configureStore } from '@reduxjs/toolkit';
import exampleReducer from './example';
import thunk from '@simpify/store/middleware/thunk';

export const store = configureStore({
  reducer: {
    app: exampleReducer,
  },
  middleware: getDefaultMiddleware => getDefaultMiddleware().concat(thunk),
});
