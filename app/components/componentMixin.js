import componentWrapper from './Bulma/componentWrapper'

export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    wrap: {
      type: Boolean,
      required: true
    },
    depth: {
      type: Number,
      required: true
    }
  },
  components: {
    componentWrapper
  }
}
