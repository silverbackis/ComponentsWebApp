import ComponentMixin from '~/components/componentMixin'
export default {
  mixins: [ComponentMixin],
  props: {
    depth: {
      type: Number,
      required: true
    }
  },
  computed: {
    childKey () {
      return this.$route.params['page' + (this.depth + 2)]
    }
  }
}
