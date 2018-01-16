<template>
  <section :class="sectionClass">
    <div :class="containerClass">
      <div class="content" v-html="data.content"></div>
    </div>
  </section>
</template>

<script>
  export default {
    name: 'Content',
    props: ['data', 'wrap'],
    computed: {
      sectionClass () {
        return this.wrap ? 'section' : ''
      },
      containerClass () {
        return this.wrap ? 'container' : ''
      }
    }
  }
</script>
