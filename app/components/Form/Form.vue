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

  const DUPLICATE_CANCEL_MESSAGE = 'duplicate'

  export default {
    mixins: [FormMixin],
    props: {
      form: {
        type: Object,
        required: true
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
          let { data } = await this.$axios.request(
            {
              url: this.form.vars.action,
              data: this.submitData,
              method: 'POST',
              cancelToken: this.cancelToken.token,
              validateStatus (status) {
                return [ 400, 200, 201 ].indexOf(status) !== -1
              }
            }
          )
          console.log(data)
        } catch (error) {
          console.log(error)
        }

        this.setFormSubmitting({
          formId: this.formId,
          submitting: false
        })
      }
    },
    created () {
      this.init(this.form)
    }
  }
</script>
