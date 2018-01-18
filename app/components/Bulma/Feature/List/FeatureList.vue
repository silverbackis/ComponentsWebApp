<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      Feature List
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    mixins: [ComponentMixin]
  }
</script>
