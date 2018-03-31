import componentMixin from '~/components/componentMixin'

export default {
  mixins: [ componentMixin ],
  data: () => ({
    activeClass: 'is-active'
  }),
  computed: {
    toRoute () {
      return this.component.route.route + (this.component.fragment ? ('#' + this.component.fragment) : '')
    }
  }
}
