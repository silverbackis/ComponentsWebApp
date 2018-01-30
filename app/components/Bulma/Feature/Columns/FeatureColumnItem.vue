<template>
  <component :is="component"
             :to="data.link"
             :class="className"
  >
    <div>
      <image-loader
        :class="imageClass"
        :src="getApiUrl(data.thumbnailPath || data.filePath)"
        :smallSrc="data.placeholderPath ? getApiUrl(data.placeholderPath) : null"
        :alt="data.label"
      />
    </div>
    <h4 class="title is-4">{{ data.label }}</h4>
    <h5 class="subtitle is-size-6-touch">{{ data.description }}</h5>
  </component>
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
        imageClass: 'image feature-horizontal-item'
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
