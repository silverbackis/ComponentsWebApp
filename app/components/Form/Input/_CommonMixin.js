import { mapGetters, mapMutations } from 'vuex'

export default {
  props: {
    formId: {
      type: String,
      required: true
    },
    inputName: {
      type: String,
      required: true
    },
    inputType: {
      type: String,
      require: true
    }
  },
  methods: {
    ...mapMutations({
      setInputDisplayErrors: 'forms/setInputDisplayErrors'
    }),
    extendInputId (data) {
      if (!data) {
        data = {}
      }
      return Object.assign(
        {
          formId: this.formId,
          inputName: this.inputName
        },
        data
      )
    }
  },
  computed: {
    ...mapGetters({
      getInput: 'forms/getInput'
    }),
    input () {
      return this.getInput(this.formId, this.inputName)
    },
    inputId () {
      return this.input.vars.id
    },
    errors () {
      return this.input.vars.errors
    },
    valid () {
      return this.input.vars.valid
    },
    validating () {
      return this.input.validating
    },
    label () {
      return this.input.vars.label
    },
    displayErrors: {
      get () {
        return !!(this.input.displayErrors && this.errors.length)
      },
      set (displayErrors) {
        this.setInputDisplayErrors(
          this.extendInputId({ displayErrors })
        )
      }
    }
  }
}
