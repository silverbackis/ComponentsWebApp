import { mutations } from './form/mutations'
import { getters } from './form/getters'
import { actions } from './form/actions'

export const state = () => ({
  forms: {}
})

export { getters, mutations, actions }
