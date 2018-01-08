<template>
  <component :is="inputComponent"
             :formId="formId"
             :inputName="inputName"
             :wrapped="wrapped"
             :inputType="inputType"
  />
</template>

<script>
  export default {
    props: {
      input: {
        type: Object,
        required: true
      },
      wrapped: {
        type: Boolean,
        default: true
      },
      formId: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        availableComponents: [
          'simple',
          'textarea',
          'choice',
          'button',
          'checkbox'
        ],
        inputComponent: null
      }
    },
    computed: {
      inputComponentDir () {
        return this.wrapped ? 'Bulma' : ''
      },
      inputName () {
        return this.input.vars.full_name
      }
    },
    methods: {
      isInputType (InputType) {
        return this.availableComponents.indexOf(InputType) !== -1
      },
      toPascalCase (str) {
        return str.split('_').map(function (item) {
          return item.charAt(0).toUpperCase() + item.substring(1)
        }).join('')
      },
      resolveInputComponent () {
        let inputComponentType = this.availableComponents[0]
        for (let bp of this.input.vars.block_prefixes) {
          if (this.isInputType(bp)) {
            inputComponentType = bp
          }
          if (bp !== this.input.vars.unique_block_prefix) {
            this.inputType = bp
          }
        }
        this.inputComponent = () => ({
          component: import('./../' + this.inputComponentDir + '/Form/Input/' + this.toPascalCase(inputComponentType))
        })
      }
    },
    created () {
      this.resolveInputComponent()
    }
  }
</script>
