import Vue from 'vue'
import { fetchAll } from '../api/index'

const mapLocationsToComponents = function (locations) {
  let components = {}
  locations.forEach(({ component }) => {
    components[component['@id']] = { componentGroups: component.componentGroups }
    component.componentGroups.forEach(({ componentLocations }) => {
      if (componentLocations) {
        components = Object.assign(components, mapLocationsToComponents(componentLocations))
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
    let data = await fetchAll({ paths: Object.keys(components), $axios: this.$axios })
    data.forEach((component) => {
      commit('setComponent', Object.assign(component, { componentGroups: components[component['@id']].componentGroups }))
    })
  }
}
