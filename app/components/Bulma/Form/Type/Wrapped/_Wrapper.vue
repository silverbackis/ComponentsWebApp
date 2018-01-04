<template>
    <div :class="['field', errors ? 'invalid' : '']">
        <label class="label" v-html="label" v-if="label && label !== ''" :for="inputId"></label>
        <div :class="controlClass">
            <slot></slot>
            <span class="icon is-right is-small" v-if="useIcons && !validating">
              <i :class="iconClass"></i>
            </span>
        </div>
        <p v-if="errors"
           class="help is-danger">
        <ul>
            <li v-for="(error, errorIndex) in errors" :key="errorIndex" v-html="error"></li>
        </ul>
        </p>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    props: {
      label: {
        type: String,
        required: false
      },
      inputId: {
        type: String,
        required: false
      },
      inputName: {
        type: String,
        required: false
      },
      useIcons: {
        type: Boolean,
        required: false,
        default: false
      },
      formId: {
        type: String,
        required: true
      }
    },
    computed: {
      validating () {
        return this.getInputValidating(this.formId, this.inputName)
      },
      controlClass () {
        return [
          'control',
          this.useIcons ? 'has-icons-right' : '',
          this.validating ? 'is-loading' : ''
        ]
      },
      iconClass () {
        return [
          'fa',
          this.getInputCurrentErrors(this.formId, this.inputName) ? 'fa-warning' : this.getInputValid(this.formId, this.inputName) ? 'fa-check' : ''
        ]
      },
      errors () {
        return this.getInputCurrentErrors(this.formId, this.inputName)
      },
      ...mapGetters({
        getInputCurrentErrors: 'forms/getInputCurrentErrors',
        getInputValidating: 'forms/getInputValidating',
        getInputValid: 'forms/getInputValid'
      })
    }
  }
</script>
