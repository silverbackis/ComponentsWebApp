<template>
  <li itemscope
      itemtype="https://schema.org/ImageGallery"
  >
    <div class="gallery-thumb">
      <figure itemprop="associatedMedia" itemscope itemtype="https://schema.org/ImageObject">
        <a class="gallery-link"
           :href="item.src"
           itemprop="contentUrl"
           @click.prevent="$photoswipe.open(index, items, $el)"
        >
          <image-loader class="image gallery-image"
                        :src="item.src"
                        :smallSrc="item.src"
                        :cover="true"
          />
          <img src="/img/1x1.png" class="square-space" />
        </a>
        <meta itemprop="width" :content="item.w">
        <meta itemprop="height" :content="item.h">
        <figcaption v-if="item.caption"
                    itemprop="caption description" class="sr-only"
                    v-html="item.caption"
        />
      </figure>
    </div>
  </li>
</template>

<script>
  import ImageLoader from '~/components/Utils/ImageLoader'

  export default {
    props: {
      item: {
        type: Object,
        required: true
      },
      items: {
        type: Array,
        required: true
      },
      index: {
        type: Number,
        required: true
      },
      $photoswipe: {
        type: Object
      }
    },
    components: {
      ImageLoader
    }
  }
</script>
