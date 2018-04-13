<template>
  <component-wrapper v-if="component" :nested="nested">
    <div :class="containerClass">
      <div class="content" v-html="injectDynamicData(component.content)"></div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    mixins: [ComponentMixin]
  }
</script>
