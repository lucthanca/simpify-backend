import { setContext } from '@apollo/client/link/context';
import BrowserPersistence from '@simpify/utils/simplePersistence';

const storage = new BrowserPersistence();

export default function createAuthLink() {
  return setContext((_, { headers }) => {
    // get the authentication token from local storage if it exists.
    const token = storage.getItem('x-simpify-token');

    // return the headers to the context so httpLink can read them
    return {
      headers: {
        ...headers,
        authorization: token ? `Bearer ${token}` : '',
      },
    };
  });
}
