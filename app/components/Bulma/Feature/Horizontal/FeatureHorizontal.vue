<template xmlns="http://www.w3.org/1999/html">
  <component-wrapper :className="['hero', 'feature-horizontal', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h3 class="title features-title">What's being used?...</h3>
        <nav class="columns">
          <app-link :to="'https://nuxtjs.org/'" class="column">
            <div>
              <image-loader
                :class="imageClass"
                :src="getApiUrl('images/nuxt.svg')"
                alt="Nuxt Framework Logo"
              />
            </div>
            <h4 class="title is-4">Nuxt</h4>
            <h5 class="subtitle is-size-6-touch">Server-side rendering for <app-link :to="'https://vuejs.org/'">VueJS</app-link></h5>
          </app-link>
          <app-link :to="'https://api-platform.com/'"
                class="column">
            <div>
              <image-loader
                :class="imageClass"
                :src="getApiUrl('images/api-platform-spider.svg')"
                alt="API Platform Logo"
              />
            </div>
            <h4 class="title is-4">API Platform</h4>
            <h5 class="subtitle is-size-6-touch">API Framework built on <app-link :to="'https://symfony.com/'">Symfony</app-link></h5>
          </app-link>
          <app-link :to="'http://bulma.io/'"
                class="column"
          >
            <div>
              <image-loader
                :class="imageClass"
                :src="getApiUrl('images/bulma.svg')"
                alt="Bulma CSS Framework Logo"
              />
            </div>
            <h4 class="title is-4">Bulma</h4>
            <h5 class="subtitle is-size-6-touch">Light-weight CSS Framework</h5>
          </app-link>
        </nav>
      </div>
    </div>
  </component-wrapper>
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
        imageClass: 'image feature-horizontal-item'
      }
    },
    computed: {
      ...mapGetters(['getApiUrl']),
      className () {
        return this.data.className || 'is-dark'
      }
    },
    methods: {
      linkProps (url) {
        if (url.match(/^(http(s)?|ftp):\/\//)) {
          return {
            is: 'a',
            href: url,
            target: '_blank',
            rel: 'noopener'
          }
        }
        return {
          is: 'router-link',
          to: url
        }
      }
    }
  }
</script>

<style lang="sass" scoped>
  @import ~bulma/sass/utilities/mixins

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
