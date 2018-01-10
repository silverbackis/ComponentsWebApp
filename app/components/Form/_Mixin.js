import { mapGetters } from 'vuex'
import { getFormId } from './_FormId'

export default {
  computed: {
    ...mapGetters({
      getForm: 'forms/getForm'
    }),
    formId () {
      return getFormId(this.form.vars)
    },
    storeForm () {
      return this.getForm(this.formId)
    }
  }
}
