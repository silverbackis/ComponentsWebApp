<template xmlns="http://www.w3.org/1999/html">
  <component-wrapper :className="['hero', 'feature-horizontal', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h3 class="title features-title">{{ injectDynamicData(component.title) }}</h3>
        <nav class="columns"
             v-for="(components, index) in this.childComponents"
             :key="index"
        >
          <feature-column-item v-for="(feature, count) in components"
                               :component="feature"
                               :key="count"
          />
        </nav>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import FeatureColumnItem from './FeatureColumnItem'

  export default {
    mixins: [ComponentMixin],
    components: {
      FeatureColumnItem
    },
    data () {
      return {
        imageClass: 'image feature-columns-item'
      }
    },
    computed: {
      className () {
        return this.component.className || 'is-dark'
      }
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins
  .feature-horizontal
    .features-title
      margin-bottom: 1.5rem

    .column
      margin-top: auto

    .feature-horizontal-item
      display: block
      position: relative
      height: 55px
      margin: auto auto 1rem
      min-width: 155px
      width: 100%
      +desktop
        height: 110px

    .hero.is-dark .subtitle a:not(.button)
      color: $grey-lighter
      font-weight: bold
</style>
