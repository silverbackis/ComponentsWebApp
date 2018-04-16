<template>
  <form :id="formId"
        :action="form.vars.action"
        :required="form.vars.required"
        :method="form.vars.method"
        :enctype="form.vars.multipart"
        v-bind="form.vars.attr"
        @submit.prevent="submit"
  >
    <slot></slot>
  </form>
</template>

<script>
  import { mapActions, mapMutations, mapGetters } from 'vuex'
  import FormMixin from './_Mixin'
  import axios from 'axios'

  const DUPLICATE_CANCEL_MESSAGE = 'duplicate'

  export default {
    mixins: [FormMixin],
    props: {
      form: {
        type: Object,
        required: true
      },
      successFn: {
        type: Function,
        required: false
      }
    },
    computed: {
      ...mapGetters({
        getFormSubmitData: 'forms/getFormSubmitData'
      }),
      submitData () {
        return this.getFormSubmitData(this.formId)
      },
      cancelToken: {
        get () {
          return this.storeForm.cancelToken
        },
        set (token) {
          this.setFormCancelToken({ formId: this.formId, token })
        }
      }
    },
    methods: {
      ...mapActions({
        init: 'forms/init',
        submitForm: 'forms/submit',
        refreshCancelToken: 'forms/refreshCancelToken'
      }),
      ...mapMutations({
        setFormSubmitting: 'forms/setFormSubmitting',
        setFormValidationResult: 'forms/setFormValidationResult',
        setInputValidationResult: 'forms/setInputValidationResult',
        setInputDisplayErrors: 'forms/setInputDisplayErrors',
        setFormCancelToken: 'forms/setFormCancelToken'
      }),
      async submit () {
        this.setFormSubmitting({
          formId: this.formId,
          submitting: true
        })

        if (this.cancelToken) {
          this.cancelToken.cancel(DUPLICATE_CANCEL_MESSAGE)
        }
        this.refreshCancelToken({ formId: this.formId })
        try {
          let { status, data } = await this.$axios.request(
            {
              url: this.form.vars.action,
              data: this.submitData,
              method: 'POST',
              cancelToken: this.cancelToken.token,
              validateStatus (status) {
                return [ 400, 200, 201, 401 ].indexOf(status) !== -1
              }
            }
          )
          if (this.successFn) {
            this.successFn(data)
          }
          const form = data.form
          const VARS = form ? form.vars : { errors: [ data.message ] }
          this.setFormValidationResult({
            formId: this.formId,
            valid: status === 200,
            errors: VARS.errors
          })
          if (form) {
            let x = form.children.length
            let child
            while (x--) {
              child = form.children[x]
              // E.g. buttons which are not valid/invalid
              if (child.vars.valid === undefined) {
                continue
              }
              this.setInputValidationResult({
                formId: this.formId,
                inputName: child.vars.full_name,
                valid: child.vars.valid,
                errors: child.vars.errors
              })
              this.setInputDisplayErrors({
                formId: this.formId,
                inputName: child.vars.full_name,
                displayErrors: true
              })
            }
          }
        } catch (error) {
          this.submitError(error)
        }

        this.setFormSubmitting({
          formId: this.formId,
          submitting: false
        })
      },
      submitError (error) {
        if (error.message === DUPLICATE_CANCEL_MESSAGE) {
          console.log('previous form submission cancelled')
        } else {
          if (axios.isCancel(error)) {
            console.warn(error)
          } else if (error.response) {
            console.warn('validate request error: ', error.response)
            this.setFormValidationResult({
              formId: this.formId,
              valid: false,
              errors: [
                '<b>' + error.response.status + ' ' + error.response.statusText + ':</b> ' +
                error.response.data['hydra:description']
              ]
            })
          } else {
            console.warn('validate unknown error: ', error)
          }
        }
      }
    },
    created () {
      this.init(this.form)
    }
  }
</script>
