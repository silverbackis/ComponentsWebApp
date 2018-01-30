<template>
  <div>
    <figure class="column is-narrow">
      <component :is="component"
                 :to="data.link"
      >
        <image-loader
          :class="imageClass"
          :src="getApiUrl(data.thumbnailPath || data.filePath)"
          :smallSrc="data.placeholderPath ? getApiUrl(data.placeholderPath) : null"
          :alt="data.label"
        />
      </component>
    </figure>
    <div class="column has-text-centered-mobile">
      <div class="content">
        <h3>{{ data.label }}</h3>
        <p v-html="data.description"></p>
        <app-link v-if="data.link && data.buttonText"
                  :to="data.link"
                  :class="data.buttonClass || 'button is-primary'"
        >
          {{ data.buttonText }}
        </app-link>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import ImageLoader from '~/components/Utils/ImageLoader'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    components: {
      ImageLoader,
      AppLink
    },
    data () {
      return {
        imageClass: 'image feature-stacked-item'
      }
    },
    computed: {
      ...mapGetters(['getApiUrl']),
      component () {
        return this.data.link ? 'app-link' : 'div'
      },
      className () {
        return [
          'column',
          this.data.className || ''
        ]
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
    margin: auto auto 1rem
    width: 100%
    min-width: 50px
    max-width: 200px
    +mobile
      width: 200px
</style>
