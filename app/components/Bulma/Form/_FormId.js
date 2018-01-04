export default {
  computed: {
    formId () {
      return this.form.vars.id || this.form.vars.attr.id
    }
  }
}
