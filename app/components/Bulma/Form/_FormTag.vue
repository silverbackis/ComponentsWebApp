<template>
  <form :id="formId" :action="action" :required="form.vars.required" :method="form.vars.method" :enctype="form.vars.multipart" @submit.prevent="submit">
    <slot></slot>
  </form>
</template>

<script>
  import { mapMutations, mapGetters } from 'vuex'
  import _FormId from './_FormId'

  export default {
    mixins: [_FormId],
    props: {
      form: Object,
      action: {
        type: String,
        required: true
      },
      successFn: {
        type: Function // ,
        // required: true
      },
      failFn: {
        type: Function,
        required: false,
        default: null
      },
      apiUrl: {
        type: Boolean,
        required: false,
        default: true
      }
    },
    methods: {
      ...mapMutations({
        initForm: 'forms/initForm',
        destroyForm: 'forms/destroyForm',
        cancelForm: 'forms/cancelForm',
        setFormSubmitting: 'forms/setFormSubmitting'
      }),
      submit () {
        /* this.setFormSubmitting({ formId: this.formId, submitting: true })
        let submitObj = this.getFormSubmitData(this.formId)
        this.$axios.post(this.getFormAction(this.formId), submitObj, {
          cancelToken: this.getFormAxiosCancelToken(this.formId),
          baseURL: null
        })
          .then((res) => {
            this.setFormSubmitting({ formId: this.formId, submitting: false })
            return this.successFn(res)
          })
          .catch((error) => {
            this.setFormSubmitting({ formId: this.formId, submitting: false })
            if (!axios.isCancel(error)) {
              if (this.failFn) {
                this.failFn(error)
              } else {
                console.warn('post form exception: ', error)
              }
            }
          }) */
        console.log('SUBMIT from _FormTag.vue')
      }
    },
    computed: {
      ...mapGetters({
        getFormValid: 'forms/getFormValid',
        getFormAction: 'forms/getFormAction',
        getFormSubmitData: 'forms/getFormSubmitData',
        getFormAxiosCancelToken: 'forms/getFormAxiosCancelToken'
      })
    },
    mounted () {
      this.initForm({
        formId: this.formId,
        models: this.form.children,
        action: this.action
      })
    },
    beforeDestroy () {
      this.cancelForm(this.formId)
      if (this.getFormValid(this.formId)) {
        // valid form can be destroyed - was submitted and accepted so no need for it anymore
        // on next load it'll be a fresh form
        this.destroyForm(this.formId)
      }
    }
  }
</script>
