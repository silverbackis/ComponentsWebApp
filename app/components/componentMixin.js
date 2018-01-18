import ComponentWrapper from './Bulma/ComponentWrapper'

export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    nested: {
      type: Boolean,
      required: true
    }
  },
  components: {
    ComponentWrapper
  },
  computed: {
    containerClass () {
      return !this.nested ? ['container'] : []
    }
  }
}
