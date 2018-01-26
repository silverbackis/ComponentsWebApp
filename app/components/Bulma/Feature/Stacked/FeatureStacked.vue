<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      <feature-stacked-item v-for="(feature, count) in data.items"
                            :data="feature"
                            :class="columnsClass(count)"
                            :key="count"
      />
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import FeatureStackedItem from './FeatureStackedItem'

  export default {
    mixins: [ComponentMixin],
    components: {
      FeatureStackedItem
    },
    methods: {
      columnsClass (count) {
        let useCount = this.data.reverse ? count : count + 1
        return {
          'feature-media': true,
          columns: true,
          reversed: useCount % 2 === 0
        }
      }
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins

  .columns.reversed
    flex-direction: row-reverse

  .feature-media + .feature-media
    padding-top: 1.5rem
    margin-top: 1.5rem
    border-top: 1px solid rgba($grey-lighter, .5)
</style>

