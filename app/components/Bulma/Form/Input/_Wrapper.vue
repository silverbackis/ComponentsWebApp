<template>
  <div :class="['field', errors ? 'invalid' : '']">
    <label class="label" v-html="label" v-if="label && label !== ''" :for="inputId"></label>
    <div :class="controlClass">
      <slot></slot>
      <span class="icon is-right is-small" v-if="useIcons && !validating">
        <i :class="iconClass"></i>
      </span>
    </div>
    <div v-if="displayErrors && errors.length"
       class="help is-danger">
      <ul>
        <li v-for="(error, errorIndex) in errors" :key="errorIndex" v-html="error"></li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      inputId: {
        type: String,
        required: true
      },
      label: {
        type: String,
        required: true
      },
      validating: {
        type: Boolean,
        required: true
      },
      valid: {
        type: Boolean,
        required: true
      },
      errors: {
        type: Array,
        required: true
      },
      useIcons: {
        type: Boolean,
        required: false,
        default: false
      },
      displayErrors: {
        type: Boolean,
        required: true
      }
    },
    computed: {
      controlClass () {
        return [
          'control',
          this.useIcons ? 'has-icons-right' : '',
          this.validating ? 'is-loading' : ''
        ]
      },
      iconClass () {
        return {
          fa: true,
          'fa-warning has-text-danger': this.displayErrors && !!this.errors.length,
          'fa-check has-text-success': this.valid
        }
      }
    }
  }
</script>
