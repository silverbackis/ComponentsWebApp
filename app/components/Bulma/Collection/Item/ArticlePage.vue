<template>
  <div class="column is-10-mobile is-6-tablet is-4-desktop is-3-fullhd">
    <div class="card">
      <component :is="linkComponent" class="card-image" :to="toRoute">
        <image-loader
          class="image"
          :src="getApiUrl(component.thumbnailPath || component.filePath)"
          :smallSrc="component.placeholderPath ? getApiUrl(component.placeholderPath) : null"
          :alt="component.title"
        />
      </component>
      <div class="card-content">
        <h4 class="title is-4">{{ component.title }}</h4>
        <h5 class="subtitle is-6">{{ component.subtitle }}</h5>
        <app-link v-if="component.routes.length"
                  :to="toRoute"
                  class="button is-primary"
        >View</app-link>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import ComponentMixin from '~/components/componentMixin'
  import ImageLoader from '~/components/Utils/ImageLoader'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    mixins: [ ComponentMixin ],
    components: {
      ImageLoader,
      AppLink
    },
    computed: {
      ...mapGetters([ 'getApiUrl' ]),
      linkComponent () {
        return this.component.routes.length ? 'app-link' : 'div'
      },
      toRoute () {
        if (this.component.routes.length) return this.component.routes[0].route
        return null
      }
    }
  }
</script>

<style lang="sass">
  .card-image
    min-height: 50px
    .image,
    .image-loader .image-placeholder
      width: 100%
</style>
