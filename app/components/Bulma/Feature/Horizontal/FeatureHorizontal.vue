<template>
  <section :class="sectionClass">
    <div :class="containerClass">
      <div class="content" v-html="data.content"></div>
    </div>
  </section>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    name: 'Content',
    mixins: [ComponentMixin],
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
