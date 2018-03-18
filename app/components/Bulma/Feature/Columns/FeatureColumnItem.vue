<template>
  <component :is="dynamicComponent"
             :to="toRoute"
             :class="className"
  >
    <div v-if="component.filePath">
      <image-loader
        :class="imageClass"
        :src="getApiUrl(component.thumbnailPath || component.filePath)"
        :smallSrc="component.placeholderPath ? getApiUrl(component.placeholderPath) : null"
        :alt="component.title"
      />
    </div>
    <h4 class="title is-4">{{ component.title }}</h4>
    <h5 class="subtitle is-size-6-touch">{{ component.description }}</h5>
  </component>
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
      dynamicComponent () {
        return this.toRoute ? 'app-link' : 'div'
      },
      className () {
        return [
          'column',
          this.component.className || ''
        ]
      },
      toRoute () {
        return this.component.url || (this.component.route ? this.component.route.route : null)
      }
    }
  }
</script>
