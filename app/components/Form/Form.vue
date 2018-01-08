<template>
  <form :id="formId"
        :action="form.vars.action"
        :required="form.vars.required"
        :method="form.vars.method"
        :enctype="form.vars.multipart"
        @submit.prevent="submit"
  >
    <slot></slot>
  </form>
</template>

<script>
  import { mapActions } from 'vuex'
  import { getFormId } from './_FormId'

  export default {
    props: {
      form: {
        type: Object,
        required: true
      }
    },
    computed: {
      formId () {
        return getFormId(this.form.vars)
      }
    },
    methods: {
      ...mapActions({
        init: 'forms/init'
      }),
      submit () {
        console.log('Submit the form')
      }
    },
    mounted () {
      this.init(this.form)
    }
  }
</script>
