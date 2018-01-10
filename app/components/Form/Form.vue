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
  import { getFormId } from './_FormId'

  export default {
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
      formId () {
        return getFormId(this.form.vars)
      },
      submitData () {
        return this.getFormSubmitData(this.formId)
      }
    },
    methods: {
      ...mapActions({
        init: 'forms/init'
      }),
      ...mapMutations({
        setFormSubmitting: 'forms/setFormSubmitting'
      }),
      submit () {
        this.setFormSubmitting({
          formId: this.formId,
          submitting: true
        })
        console.log(this.submitData)
        setTimeout(() => {
          this.setFormSubmitting({
            formId: this.formId,
            submitting: false
          })
        }, 1000)
      }
    },
    created () {
      this.init(this.form)
    }
  }
</script>
