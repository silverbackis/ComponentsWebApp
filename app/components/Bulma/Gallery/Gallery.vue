<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      <ul class="columns is-multiline">
        <gallery-item v-for="(item, index) in items"
                      :key="index"
                      class="column is-4 is-3-desktop"
                      :items="items"
                      :item="item"
                      :index="index"
                      :$photoswipe="$photoswipe"
        />
      </ul>
    </div>
  </component-wrapper>
</template>

<script>
  import Vue from 'vue'
  import ComponentMixin from '~/components/componentMixin'
  import PhotoSwipeComponent from './PhotoSwipe'
  import GalleryItem from './GalleryItem'

  export default {
    mixins: [ComponentMixin],
    components: {
      GalleryItem
    },
    data () {
      return {
        items: [
          {
            src: '/img/chewy1.jpg',
            w: 848,
            h: 636
          }, {
            src: '/img/stoney1.jpg',
            w: 636,
            h: 848
          }, {
            src: '/img/silverback2.jpg',
            w: 972,
            h: 972
          }, {
            src: '/img/silverback1.jpg',
            w: 972,
            h: 972
          }, {
            src: '/img/silverback3.jpg',
            w: 972,
            h: 972
          }, {
            src: '/img/silverback2.jpg',
            w: 972,
            h: 972
          }, {
            src: '/img/chewy1.jpg',
            w: 848,
            h: 636
          }, {
            src: '/img/silverback3.jpg',
            w: 972,
            h: 972
          }
        ],
        $photoswipe: null
      }
    },
    created () {
      if (process.browser) {
        const PhotoSwipe = Vue.extend(PhotoSwipeComponent)
        this.$photoswipe = new PhotoSwipe({el: document.createElement('div')})
        document.body.appendChild(this.$photoswipe.$el)
      } else {
        // Create dummy functions for SSR to avoid errors
        const f = () => {
          console.log('Not initialised')
        }
        this.$photoswipe = { open: f, close: f }
      }
    },
    beforeDestroy () {
      if (this.$photoswipe) {
        document.body.removeChild(this.$photoswipe.$el)
        this.$photoswipe = null
      }
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins

  .gallery-thumb
    width: 100%
    display: inline-block
    .gallery-link
      box-shadow: 1px 2px 3px rgba($black, .1)
      position: relative
      display: inline-block
      overflow: hidden
      width: 100%
      .square-space
        display: block
        width: 100%
      .gallery-image
        position: absolute
        top: 0
        left: 0
        width: 100%
        height: 100%
</style>
