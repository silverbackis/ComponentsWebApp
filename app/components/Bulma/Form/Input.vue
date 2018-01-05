<template>
    <component :is="inputComponent"
               :input="input"
               :formId="formId"
               :noSelectWrap="noSelectWrap"
               :lastBlockPrefix="lastBlockPrefix"
               :disableValidation="disableValidation"
    />
</template>

<script>
  export default {
    props: {
      input: {
        type: Object,
        required: true
      },
      inputOnly: {
        type: Boolean,
        default: false
      },
      noSelectWrap: {
        type: Boolean,
        default: false
      },
      formId: {
        type: String,
        required: true
      },
      disableValidation: {
        type: Boolean,
        default: false
      }
    },
    /* components: {
      SimpleInput: () => import('./Type/Input/Simple'),
      TextareaInput: () => import('./Type/Input/Textarea'),
      ChoiceInput: () => import('./Type/Input/Choice'),
      ButtonInput: () => import('./Type/Input/Button'),
      CheckboxInput: () => import('./Type/Input/Checkbox'),
      SimpleInputWrapped: () => import('./Type/Wrapped/Simple'),
      TextareaInputWrapped: () => import('./Type/Wrapped/Textarea'),
      ChoiceInputWrapped: () => import('./Type/Wrapped/Choice'),
      ButtonInputWrapped: () => import('./Type/Wrapped/Button'),
      CheckboxInputWrapped: () => import('./Type/Wrapped/Checkbox')
    }, */
    data () {
      return {
        inputComponentType: null,
        lastBlockPrefix: null,
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
        return this.inputOnly ? 'Input' : 'Wrapped'
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
        for (let bp of this.input.vars.block_prefixes) {
          if (this.isInputType(bp)) {
            this.inputComponentType = bp
          }
          if (bp !== this.input.vars.unique_block_prefix) {
            this.lastBlockPrefix = bp
          }
        }
        this.inputComponent = () => ({
          component: import('./Type/' + this.inputComponentDir + '/' + this.toPascalCase(this.inputComponentType))
        })
      }
    },
    created () {
      // Set Default
      this.inputComponentType = this.availableComponents[0]
      this.resolveInputComponent()
    }
  }
</script>

<style lang="sass">
    @import "~assets/css/vars"

    .checkbox,
    .radio
        + .radio,
        + .checkbox
            margin-left: 1rem
        &.custom
            position: relative
            padding-left: 1.25rem
        .custom
            position: absolute
            top: 0
            left: 0
            z-index: -1
            opacity: 0
            ~ .indicator
                position: absolute
                width: 1rem
                height: 1rem
                top: .1rem
                left: 0
                pointer-events: none
                user-select: none
                background: 50% 50% no-repeat $grey-light
                background-size: 50% 50%
                ~ .input-label
                    display: block
                    position: relative
                    user-select: none
                &::after
                    position: absolute
                    content: ''
                    width: 100%
                    height: 100%
                    top: 0
                    left: 0
                    background-color: $white
                    transition: background-color .4s, transform .4s, opacity .4s
                    opacity: 0
            &:checked
                ~ .indicator
                    background-color: $primary
                    &::after
                        transform: scale(.4)
                        opacity: 1
</style>
