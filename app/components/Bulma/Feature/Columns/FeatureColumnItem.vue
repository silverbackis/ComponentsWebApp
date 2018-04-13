<template>
  <component :is="dynamicComponent"
             :to="toRoute"
             :class="className"
  >
    <div v-if="component.filePath">
      <image-loader
        :class="imageClass"
        :src="getApiUrl(injectDynamicData(component.thumbnailPath) || injectDynamicData(component.filePath))"
        :smallSrc="injectDynamicData(component.placeholderPath) ? getApiUrl(injectDynamicData(component.placeholderPath)) : null"
        :alt="injectDynamicData(component.title)"
      />
    </div>
    <h4 class="title is-4">{{ injectDynamicData(component.title) }}</h4>
    <h5 class="subtitle is-size-6-touch">{{ injectDynamicData(component.description) }}</h5>
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
          this.injectDynamicData(this.component.className) || ''
        ]
      },
      toRoute () {
        return this.component.url || (this.component.route ? this.component.route.route : null)
      }
    }
  }
</script>
