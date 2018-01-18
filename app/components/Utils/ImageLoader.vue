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
    >
        <div v-show="currentSrc === null" class="placeholder" :key="'placeholder'"></div>
        <canvas v-show="currentSrc === smallSrc" :key="'canvas'"></canvas>
        <img v-show="currentSrc === src" :src="src" :key="'image'" :alt="alt" />
        <div class="loader-outer" :key="'loader'" v-show="currentSrc !== src"><div class="loader"></div></div>
    </transition-group>
</template>

<script>
  export default {
    props: [
      'src',
      'smallSrc',
      'alt'
    ],
    data: () => ({
      currentSrc: null
    }),
    mounted () {
      let loResImg, hiResImg, that, context
      loResImg = new Image()
      hiResImg = new Image()
      that = this
      context = this.$el.getElementsByTagName('canvas')[0].getContext('2d')
      hiResImg.onload = function () {
        loResImg.onload = null
        that.currentSrc = that.src
      }
      loResImg.onload = function () {
        context.drawImage(loResImg, 0, 0)
        that.currentSrc = that.smallSrc
      }
      if (that.smallSrc && that.src.split('.').pop() !== 'svg') {
        loResImg.src = that.smallSrc
      }
      hiResImg.src = that.src
    }
  }
</script>

<style lang="sass" scoped>
    @import ~assets/css/_vars

    .fade-enter-active, .fade-leave-active
      transition: opacity .6s

    .fade-enter,
    .fade-leave-to
      opacity: 0

    .loader-outer
      position: absolute
      top: 50%
      left: 50%
      margin-top: -8px
      margin-left: -8px

    img,
    canvas,
    .placeholder
      height: auto
      width: auto
      position: relative
      top: 0
      left: 0
      max-height: 100%

    .placeholder
      background-color: rgba($grey-lightest, 0.1)
      filter: blur(30px)
      position: absolute
      width: 100%
      height: 100%

    canvas
      filter: blur(5px)
</style>
