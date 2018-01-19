<template>
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
      <div class="pswp__container">
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
      </div>

      <div class="pswp__ui pswp__ui--hidden">

        <div class="pswp__top-bar">
          <div class="pswp__counter"></div>

          <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

          <button class="pswp__button pswp__button--share" title="Share"></button>

          <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

          <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

          <div class="pswp__preloader">
            <div class="pswp__preloader__icn">
              <div class="pswp__preloader__cut">
                <div class="pswp__preloader__donut"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
          <div class="pswp__share-tooltip"></div>
        </div>

        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
        </button>

        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
        </button>

        <div class="pswp__caption">
          <div class="pswp__caption__center"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import 'photoswipe/dist/photoswipe.css'
  import 'photoswipe/dist/default-skin/default-skin.css'

  import PhotoSwipe from 'photoswipe/dist/photoswipe'
  import PhotoSwipeDefaultUI from 'photoswipe/dist/photoswipe-ui-default'

  export default {
    methods: {
      open (index, items, $el, options) {
        if (!options) {
          options = {
            fullscreenEl: true,
            history: false,
            shareEl: false,
            tapToClose: true
          }
        }
        let baseOps = {
          index: index,
          getThumbBoundsFn () {
            const thumbnail = $el.getElementsByTagName('img')[0]
            const pageYScroll = window.pageYOffset || document.documentElement.scrollTop
            const rect = thumbnail.getBoundingClientRect()
            return {
              x: rect.left,
              y: rect.top + pageYScroll,
              w: rect.width
            }
          },
          showHideOpacity: true,
          bgOpacity: 0.9
        }
        const ops = Object.assign(baseOps, options)
        this.photoswipe = new PhotoSwipe(this.$el, PhotoSwipeDefaultUI, items, ops)
        this.photoswipe.init()
      },
      close () {
        this.photoswipe.close()
      }
    }
  }
</script>

<style lang="sass">
  .pswp__caption__center
    text-align: center
</style>
