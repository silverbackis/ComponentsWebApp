<!--
This component was inspired by: Louis Zawadzki
Github: https://github.com/louiszawadzki/vue-lazy-img-loading
Medium blog post: https://www.theodo.fr/blog/2017/02/medium-like-image-loading-with-vue-js-part-2/

Author modified: Daniel <daniel@silverback.is>
-->
<template>
  <transition-group
    name="fade"
    tag="div"
    :class="loaderClass"
  >
    <div v-show="loadedRes === null"
         :key="'placeholder'"
         class="placeholder"
    />
    <img v-show="true"
         :key="'image-placeholder'"
         :src="currentSrc"
         class="image-placeholder"
         ref="imagePlaceholder"
    />
    <canvas v-show="loadedRes === 'low'"
            :key="'canvas'"
            ref="canvas"
            class="image-small"
    />
    <transition-group name="fade"
                      tag="div"
                      :class="imageHolderClass"
                      :key="'image-holder'"
                      v-show="true"
    >
      <img v-show="loadedRes === 'high'"
           :key="'image'"
           :src="src"
           :alt="alt"
           :class="imageClass" />
    </transition-group>
    <div v-show="loadedRes !== 'high'"
         :key="'loader'"
         class="loader-outer"
    >
      <div class="loader"></div>
    </div>
  </transition-group>
</template>

<script>
  import canvasCover from '~/components/Utils/canvasCover'
  import stackBlur from '~/components/Utils/stackBlur'

  export default {
    props: {
      src: {
        type: String,
        required: true
      },
      smallSrc: {
        type: String,
        required: false
      },
      alt: {
        type: String,
        required: false
      },
      cover: {
        type: Boolean,
        default: false
      }
    },
    data: () => ({
      currentSrc: null,
      loadedRes: null,
      portrait: false
    }),
    computed: {
      loaderClass () {
        return [
          'image-loader',
          this.cover ? 'cover' : 'contain'
        ]
      },
      imageClass () {
        return [
          'image'
        ]
      },
      imageHolderClass () {
        return [
          'image-holder',
          this.portrait ? 'portrait' : 'landscape'
        ]
      }
    },
    mounted () {
      let loResImg = new Image()
      // HTML5 - send Origin header - no credentials though
      loResImg.crossOrigin = 'anonymous'

      let hiResImg = new Image()
      let loResCanvas = this.$refs.canvas

      hiResImg.onload = () => {
        loResImg.onload = null
        this.portrait = hiResImg.width < hiResImg.height
        this.currentSrc = this.src
        this.loadedRes = 'high'
      }
      loResImg.onload = () => {
        let matchSizeEl = this.cover ? this.$el : loResImg
        let ctx = loResCanvas.getContext('2d')
        loResCanvas.width = matchSizeEl.clientWidth || matchSizeEl.width
        loResCanvas.height = matchSizeEl.clientHeight || matchSizeEl.height
        if (this.cover) {
          canvasCover(ctx, loResImg)
        } else {
          ctx.drawImage(loResImg, 0, 0)
        }
        stackBlur(ctx, 0, 0, loResCanvas.width, loResCanvas.height, 8)
        this.currentSrc = this.smallSrc
        this.loadedRes = 'low'
      }
      if (this.smallSrc) { // && that.src.split('.').pop() !== 'svg'
        this.currentSrc = this.smallSrc
        loResImg.src = this.smallSrc
      }
      hiResImg.src = this.src
    }
  }
</script>

<style lang="sass">
  @import ~assets/css/_vars

  .fade-enter-active,
  .fade-leave-active
    transition: opacity .8s

  .fade-enter,
  .fade-leave-to
    opacity: 0

  .image-loader
    .placeholder
      position: absolute
      top: 0
      left: 0
      width: 100%
      height: 100%
      background-color: rgba($grey-lightest, 0.1)
      filter: blur(30px)
    .image-placeholder
      position: relative
      display: block
      max-width: 100%
      max-height: 100%
      opacity: 0
    .image-holder
      position: absolute
      top: 0
      left: 0
      width: 100%
      height: 100%
    .image-small
      position: absolute
      top: 0
      left: 0
    .image
      position: relative
      display: block
    .loader-outer
      position: absolute
      top: 50%
      left: 50%
      width: 16px
      height: 16px
      margin-top: -8px
      margin-left: -8px
    &.contain
      .image-small
        max-width: 100%
        max-height: 100%
      .image-holder
        max-width: 100%
        max-height: 100%
        .image
          display: inline-block
          max-width: 100%
          max-height: 100%
    &.cover
      .image-placeholder
        width: 100%
        height: 100%
      .image-small
        width: 100%
        height: 100%
      .image-holder
        min-width: 100%
        min-height: 100%
        &.landscape
          height: 100%
          width: auto
          left: 50%
          .image
            margin-left: -50%
            height: 100%
            width: auto
            max-width: none
        &.portrait
          position: relative
          width: 100%
          height: 100%
          margin-top: -100%
          .image
            position: absolute
            top: -50%
            bottom: -50%
            width: 100%
            height: auto
            margin: auto
</style>
