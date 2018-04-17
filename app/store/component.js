import Vue from 'vue'
import { fetch } from '../api/index'

export const flattenComponentData = function (locations) {
  let components = {}
  locations.forEach(({ component }) => {
    components[component['@id']] = component
    component.componentGroups.forEach(({ componentLocations }) => {
      if (componentLocations) {
        components = Object.assign(components, flattenComponentData(componentLocations))
      }
    })
    if (component.childComponentGroup) {
      components = Object.assign(components, flattenComponentData(component.childComponentGroup.componentLocations))
    }
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

export const getters = {
  getComponent: ({ data }) => (id) => {
    if (id instanceof Object) {
      return data[id['@id']]
    }
    return data[id]
  }
}

export const actions = {
  async initPage ({ commit, rootGetters }, page) {
    let { data } = await fetch({ path: page['@id'], $axios: this.$axios })
    let locations = data.componentLocations
    let components = flattenComponentData(locations)
    Object.keys(components).forEach((componentId) => {
      commit('setComponent', components[componentId])
    })
  }
}
