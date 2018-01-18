<template>
  <component :is="component" :class="finalClass">
    <slot></slot>
  </component>
</template>

<script>
  export default {
    props: {
      class: {
        type: String,
        default: ''
      },
      depth: {
        type: Number,
        required: true
      }
    },
    computed: {
      component () {
        return this.depth ? 'div' : 'section'
      },
      componentClass () {
        return this.depth ? 'component' : 'section'
      },
      finalClass () {
        return [
          this.class,
          this.componentClass
        ]
      }
    }
  }
</script>
