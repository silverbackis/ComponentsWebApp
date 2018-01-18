<template>
  <component-wrapper :depth="depth">
    <div :class="containerClass">
      <div class="content" v-html="data.content"></div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import ComponentWrapper from '../componentWrapper'

  export default {
    components: {ComponentWrapper},
    name: 'Content',
    mixins: [ComponentMixin],
    computed: {
      containerClass () {
        return this.wrap ? 'container' : ''
      }
    }
  }
</script>
