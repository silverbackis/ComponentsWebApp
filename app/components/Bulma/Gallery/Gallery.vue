<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      Gallery
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    mixins: [ComponentMixin]
  }
</script>
