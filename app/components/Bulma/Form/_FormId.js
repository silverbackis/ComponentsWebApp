export default {
  computed: {
    formId () {
      return 'form_' + this.form.vars.id || this.form.vars.attr.id
    }
  }
}
