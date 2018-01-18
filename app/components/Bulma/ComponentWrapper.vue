<template>
  <component :is="component" :class="finalClass">
    <slot></slot>
  </component>
</template>

<script>
  export default {
    props: {
      className: {
        type: Array,
        default () { return [] }
      },
      nested: {
        type: Boolean,
        required: true
      },
      extendClass: {
        type: Boolean,
        default: true
      }
    },
    computed: {
      component () {
        return this.nested ? 'div' : 'section'
      },
      componentClass () {
        return this.nested ? 'component' : 'section'
      },
      finalClass () {
        return this.extendClass ? [
          ...this.className,
          this.componentClass
        ] : this.className
      }
    }
  }
</script>
