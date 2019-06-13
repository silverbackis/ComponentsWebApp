import React, { Component } from 'react';
import { ArrayInput, ArrayField, SimpleFormIterator, TextInput } from 'react-admin';
import parseHydraDocumentation from '@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation';
import { HydraAdmin, hydraClient, fetchHydra as baseFetchHydra  } from '@api-platform/admin';
import authProvider from './authProvider';
import { Redirect } from 'react-router-dom';
import { createMuiTheme } from '@material-ui/core/styles';
// import Layout from './Component/Layout';
// import { UserShow } from './Components/User/Show';
// import { UserEdit } from './Components/User/Edit';
// import { UserCreate } from './Components/User/Create';
// import { UserList } from './Components/User/List';

const theme = createMuiTheme({
  palette: {
    type: 'light'
  },
});

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;
const fetchHeaders = {'Authorization': `Bearer ${window.localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
  ...options,
  headers: new Headers(fetchHeaders),
});
const dataProvider = api => hydraClient(api, fetchHydra);
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
  .then(
    ({ api }) => {
      const users = api.resources.find(({ name }) => 'users' === name);
      const roles = users.fields.find(f => 'roles' === f.name);

      roles.input = props => (
        <ArrayInput {...props} source="roles">
          <SimpleFormIterator>
            <TextInput defaultValue="ROLE_USER" />
          </SimpleFormIterator>
        </ArrayInput>
      );

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

export default class extends Component {
  state = { api: null }

  componentDidMount() {
    parseHydraDocumentation(entrypoint).then(({api}) => {
        console.log('resources', api.resources)
        this.setState({ api });
      }
    )
  }

  render() {
    if (null === this.state.api) return <div>Loading...</div>;

    return <HydraAdmin
      apiDocumentationParser={apiDocumentationParser}
      authProvider={authProvider}
      entrypoint={entrypoint}
      dataProvider={dataProvider}
    />
  }
}
