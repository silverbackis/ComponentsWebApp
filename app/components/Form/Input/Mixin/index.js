import computed from './computed'
import methods from './methods'
import lifecycle from './lifecycle'

export default {
  mixins: [ computed, methods, lifecycle ],
  data () {
    return {
      validationCalls: 0
    }
  },
  props: {
    inputClass: {
      type: String,
      required: false,
      default: 'input'
    }
  }
}
