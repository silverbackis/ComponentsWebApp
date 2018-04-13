<template>
  <div>
    <figure class="column is-narrow">
      <component :is="dynamicComponent"
                 :to="toRoute"
      >
        <image-loader
          :class="imageClass"
          :src="getApiUrl(injectDynamicData(component.thumbnailPath) || injectDynamicData(component.filePath))"
          :smallSrc="injectDynamicData(component.placeholderPath) ? getApiUrl(injectDynamicData(component.placeholderPath)) : null"
          :alt="injectDynamicData(component.title)"
        />
      </component>
    </figure>
    <div class="column has-text-centered-mobile">
      <div class="content">
        <h3>{{ injectDynamicData(component.title) }}</h3>
        <p v-html="injectDynamicData(component.description)"></p>
        <app-link v-if="toRoute && component.buttonText"
                  :to="toRoute"
                  :class="injectDynamicData(component.buttonClass) || 'button is-primary'"
        >
          {{ injectDynamicData(component.buttonText) }}
        </app-link>
      </div>
    </div>
  </div>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import { mapGetters } from 'vuex'
  import ImageLoader from '~/components/Utils/ImageLoader'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    mixins: [ComponentMixin],
    components: {
      ImageLoader,
      AppLink
    },
    data () {
      return {
        imageClass: 'image feature-stacked-item has-text-centered'
      }
    },
    computed: {
      ...mapGetters(['getApiUrl']),
      dynamicComponent () {
        return this.toRoute ? 'app-link' : 'div'
      },
      className () {
        return [
          'column',
          this.component.className || ''
        ]
      },
      toRoute () {
        return this.component.url || (this.component.route ? this.component.route.route : null)
      }
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins

  .feature-stacked-item
    display: block
    position: relative
    height: 100%
    max-height: 150px
    width: 100%
    min-width: 50px
    max-width: 200px
    margin: auto
    +mobile
      width: 200px
    +desktop
      margin: auto auto 1rem
</style>
