<template>
  <div class="field">
    <label class="label" v-html="label" v-if="label && label !== ''" :for="inputId"></label>
    <div :class="controlClass">
      <div :class="wrapperClass">
        <slot></slot>
      </div>
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
      },
      select: {
        type: [Boolean, String],
        default: false,
        validator (value) {
          return value === false || ['single', 'multiple'].indexOf(value) !== -1
        }
      }
    },
    computed: {
      hasErrors () {
        return !this.valid && this.displayErrors && !!this.errors.length && !this.validating
      },
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
          'fa-warning has-text-danger': this.hasErrors,
          'fa-check has-text-success': this.valid && !this.validating
        }
      },
      wrapperClass () {
        return this.select ? this.selectClass : this.fieldClass
      },
      fieldClass () {
        return Object.assign(this.validClass, {
          field: true
        })
      },
      selectClass () {
        return Object.assign(this.validClass, {
          select: true,
          'is-multiple': this.select === 'multiple'
        })
      },
      validClass () {
        return {
          'is-success': this.valid && !this.validating,
          'is-danger': this.hasErrors
        }
      }
    }
  }
</script>
