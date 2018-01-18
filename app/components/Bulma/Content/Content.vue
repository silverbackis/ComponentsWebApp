<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      <div class="content" v-html="data.content"></div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    mixins: [ComponentMixin]
  }
</script>
