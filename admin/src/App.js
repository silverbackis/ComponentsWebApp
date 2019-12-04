import React from 'react';
import { HydraAdmin, ResourceGuesser } from '@api-platform/admin';
import Cookies from 'js-cookie';
import parseHydraDocumentation from '@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation';
import { dataProvider as baseDataProvider, fetchHydra as baseFetchHydra  } from '@api-platform/admin';
import authProviderFn from './authProvider';
import { Redirect } from 'react-router-dom';
import { UserEdit, UserList, UserShow, UserCreate } from './resources/Users';

const entrypoint
  = process.env.REACT_APP_API_ENTRYPOINT;
const cookie_domain = process.env.REACT_APP_COOKIE_DOMAIN

const authProvider = authProviderFn(cookie_domain, entrypoint)

const fetchHeaders = {'Authorization': `Bearer ${Cookies.get('TKN')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
  ...options,
  headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = (entrypoint) => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
  .then(
    ({ api }) => {
      api.resources = api.resources.filter(({ name }) => {
        return 'users' === name
      });
      return { api };
    },
    (result) => {
      switch (result.status) {
        case 401:
          return Promise.resolve({
            api: result.api,
            customRoutes: [{
              props: {
                path: '/',
                render: () => <Redirect to={`/login`}/>,
              },
            }],
          });

        default:
          return Promise.reject(result);
      }
    },
  );

const dataProvider = baseDataProvider(entrypoint, fetchHydra, apiDocumentationParser);

export default props => (
  <HydraAdmin
    apiDocumentationParser={ apiDocumentationParser }
    dataProvider={ dataProvider }
    authProvider={ authProvider }
    entrypoint={ entrypoint }
  >
    <ResourceGuesser name="users" edit={UserEdit} list={UserList} show={UserShow} create={UserCreate} />
  </HydraAdmin>
);
