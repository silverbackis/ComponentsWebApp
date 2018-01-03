export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    depth: {
      type: Number,
      required: true
    }
  },
  computed: {
    childComponentGroups () {
      return this.data.childGroups || []
    },
    childKey () {
      return this.$route.params['page' + (this.depth + 2)]
    }
  }
}
