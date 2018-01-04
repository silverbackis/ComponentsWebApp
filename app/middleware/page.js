export default async function ({ store, route, redirect }) {
  let data = await store.dispatch('page/FETCH_ROUTE', { route })
  if (data.redirect) {
    await redirect(data.redirect.route)
  } else {
    data = data['hydra:member']
    await store.commit('page/SET_ROUTE_PAGES', { data })
    await store.dispatch('page/FETCH_PAGES')
  }
}
