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
    created () {
      this.init(this.form)
    }
  }
</script>
