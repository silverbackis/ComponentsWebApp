import Cookies from 'js-cookie'
import { AUTH_LOGIN, AUTH_LOGOUT, AUTH_ERROR, AUTH_CHECK } from 'react-admin';

export default (cookie_domain, entrypoint) => ((type, params) => {
  // Change this to be your own login check route.
  const login_uri = entrypoint + '/login_check';

  switch (type) {
    case AUTH_LOGIN:
      const { username, password } = params;
      const request = new Request(`${login_uri}`, {
        method: 'POST',
        body: JSON.stringify({ username, password }),
        headers: new Headers({ 'Content-Type': 'application/json' }),
      });

      return fetch(request)
        .then(response => {
          if (response.status < 200 || response.status >= 300) throw new Error(response.statusText);

          return response.json();
        })
        .then(({ token }) => {
          Cookies.set('TKN', token, { secure: true, domain: cookie_domain })
          window.location.replace('/');
        });

    case AUTH_LOGOUT:
      Cookies.remove('TKN', { domain: cookie_domain });
      break;

    case AUTH_ERROR:
      if (401 === params.status || 403 === params.status) {
        Cookies.remove('TKN', { domain: cookie_domain });

        return Promise.reject();
      }
      break;

    case AUTH_CHECK:
      return Cookies.get('TKN', { domain: cookie_domain }) ? Promise.resolve() : Promise.reject();

    default:
      return Promise.resolve();
  }
})
