import Vue from 'vue'
import { fetchAll } from '../api/index'

const mapLocationsToComponents = function (locations) {
  let components = []
  locations.forEach(({ component }) => {
    components.push(component['@id'])
    component.componentGroups.forEach(({ componentLocations }) => {
      if (componentLocations) {
        console.log(component['@id'], componentLocations)
        components.push(...mapLocationsToComponents(componentLocations))
      }
    })
  })
  return components
}

export const state = () => ({
  data: {}
})

export const mutations = {
  setComponent (state, data) {
    Vue.set(state.data, data['@id'], data)
  }
}

export const actions = {
  async init ({ commit, rootGetters }, locations) {
    let components = mapLocationsToComponents(locations)
    let data = await fetchAll({ paths: components, $axios: this.$axios })
    data.forEach((component) => {
      commit('setComponent', component)
    })
  }
}
