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
    childComponentGroups () {
      return [] // this.data.childGroups ||
    },
    childKey () {
      return this.$route.params['page' + (this.depth + 2)]
    }
  }
}
