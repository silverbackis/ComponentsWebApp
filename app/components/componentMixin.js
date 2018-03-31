import { mapGetters } from 'vuex'
import ComponentWrapper from './Bulma/ComponentWrapper'

export default {
  props: {
    component: {
      type: Object,
      required: true
    },
    nested: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  components: {
    ComponentWrapper
  },
  computed: {
    ...mapGetters({
      getComponent: 'component/getComponent'
    }),
    containerClass () {
      return !this.nested ? ['container'] : []
    },
    childComponents () {
      return this.component.componentGroups.map(({ componentLocations }) => {
        return componentLocations.map(loc => loc.component)
      })
    }
  }
}
