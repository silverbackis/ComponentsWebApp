<template>
  <label :for="choice.id">
    <input type="radio">
    <div class="indicator"></div>
    <span class="input-label" v-html="choice.label"></span>
  </label>
</template>

<script>
  export default {
    props: ['choice']
  }
</script>

<style lang="sass">
  .radio
    .custom
      ~ .indicator
        border-radius: 50%
        &::after
          border-radius: 50%
      &:checked
        ~ .indicator
          &::after
            border-radius: 50%
</style>
