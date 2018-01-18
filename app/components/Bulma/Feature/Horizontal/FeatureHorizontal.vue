<template>
  <component-wrapper :className="['hero', 'feature-horizontal', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div :class="containerClass">
        <nav class="columns has-text-centered">
          <component v-bind="linkProps('https://nuxtjs.org/')" class="column">
            <image-loader
              :class="imageClass"
              :src="getApiUrl('images/nuxt.svg')"
              alt="Nuxt Framework Logo"
            />
            <h4 class="title is-4">Nuxt</h4>
            <h5 class="subtitle">Server-side rendering for <component v-bind="linkProps('https://vuejs.org/')">VueJS</component></h5>
          </component>
          <component v-bind="linkProps('https://api-platform.com/')" class="column">
            <image-loader
              :class="imageClass"
              :src="getApiUrl('images/api-platform-spider.svg')"
              alt="API Platform Logo"
            />
            <h4 class="title is-4">API Platform</h4>
            <h5 class="subtitle">Comprehensive API bundle built on <component v-bind="linkProps('https://symfony.com/')">Symfony</component></h5>
          </component>
          <component v-bind="linkProps('http://bulma.io/')" class="column">
            <image-loader
              :class="imageClass"
              :src="getApiUrl('images/bulma.svg')"
              alt="Bulma CSS Framework Logo"
            />
            <h4 class="title is-4">Bulma</h4>
            <h5 class="subtitle">Light-weight CSS Framework</h5>
          </component>
        </nav>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import { mapGetters } from 'vuex'
  import ImageLoader from '~/components/Utils/ImageLoader'

  export default {
    mixins: [ComponentMixin],
    components: {
      ImageLoader
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

<style lang="sass">
  @import ~bulma/sass/utilities/mixins

  .feature-horizontal .column
    margin-top: auto

  .feature-horizontal-item
    display: block
    position: relative
    height: 55px
    margin: auto auto 1rem
    min-width: 155px
    +desktop
      height: 110px

  .hero.is-dark .subtitle a:not(.button)
    color: $grey-lighter
    font-weight: bold
</style>
