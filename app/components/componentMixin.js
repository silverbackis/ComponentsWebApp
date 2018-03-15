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
    containerClass () {
      return !this.nested ? ['container'] : []
    },
    childComponents () {
      let children = []
      this.component.componentGroups.forEach(({ componentLocations }) => {
        let components = []
        componentLocations.forEach(({ component }) => {
          components.push(component)
        })
        children.push(components)
      })
      return children
    }
  }
}
