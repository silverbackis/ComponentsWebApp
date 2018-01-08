import data from './data'
import props from './props'
import computed from './computed'
import methods from './methods'
import watchers from './watchers'
import lifecycle from './lifecycle'

export default {
  mixins: [ data, props, computed, methods, watchers, lifecycle ]
}
