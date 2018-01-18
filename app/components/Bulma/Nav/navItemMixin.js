export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    activeClass: 'is-active'
  }),
  computed: {
    childItems () {
      return !this.item.child ? null : this.item.child.items
    },
    toRoute () {
      return this.item.route.route + (this.item.fragment ? ('#' + this.item.fragment) : '')
    }
  }
}
