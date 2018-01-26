<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      <div v-for="n in 3"
           :class="columnsClass(n)">
        <figure class="column is-narrow">
          <app-link to="https://nuxtjs.org/">
            <image-loader
              :class="imageClass"
              :src="getApiUrl('images/nuxt.svg')"
              :smallSrc="getApiUrl('images/api-platform-spider.svg')"
              alt="Nuxt Framework Logo"
            />
          </app-link>
        </figure>
        <div class="column has-text-centered-mobile">
          <div class="content">
            <h3>Some Title</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.
            </p>
            <app-link to="https://nuxtjs.org/" class="button is-primary">
              Visit NuxtJS Website
            </app-link>
          </div>
        </div>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import ImageLoader from '~/components/Utils/ImageLoader'
  import AppLink from '~/components/Utils/AppLink'
  import { mapGetters } from 'vuex'

  export default {
    mixins: [ComponentMixin],
    components: {
      ImageLoader,
      AppLink
    },
    props: {
      switchAlignment: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        imageClass: 'image feature-media-item'
      }
    },
    computed: {
      ...mapGetters(['getApiUrl'])
    },
    methods: {
      columnsClass (count) {
        let useCount = this.switchAlignment ? count + 1 : count
        return {
          'feature-media': true,
          columns: true,
          reversed: useCount % 2 === 0
        }
      }
    }
  }
</script>

<style lang="sass" scoped>
  @import ~bulma/sass/utilities/mixins

  .feature-media-item
    display: block
    position: relative
    height: 100%
    margin: auto auto 1rem
    width: 200px
    +mobile
      width: 200px

  .columns.reversed
    flex-direction: row-reverse

  .feature-media + .feature-media
    padding-top: 1.5rem
    margin-top: 1.5rem
    border-top: 1px solid rgba($grey-lighter, .5)
</style>

