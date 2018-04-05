<template>
  <div class="bulma-components" v-if="_components">
    <component v-for="component in _components"
               :is="name(component)"
               :key="component.id"
               :component="getComponent(component['@id'])"
               :nested="nested"
               :depth="depth"
    />
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    props: {
      pageData: {
        type: Object,
        required: true
      },
      depth: {
        type: Number,
        required: true
      },
      nested: {
        type: Boolean,
        required: true
      }
    },
    components: {
      BulmaHero: () => import('~/components/Bulma/Hero/Hero.vue'),
      BulmaContent: () => import('~/components/Bulma/Content/Content.vue'),
      BulmaTabs: () => import('~/components/Bulma/Nav/Tabs/Tabs.vue'),
      BulmaMenu: () => import('~/components/Bulma/Nav/Menu/Menu.vue'),
      BulmaForm: () => import('~/components/Bulma/Form/Form.vue'),
      BulmaFeatureColumns: () => import('~/components/Bulma/Feature/Columns/FeatureColumns.vue'),
      BulmaFeatureStacked: () => import('~/components/Bulma/Feature/Stacked/FeatureStacked.vue'),
      BulmaFeatureTextList: () => import('~/components/Bulma/Feature/TextList/FeatureTextList.vue'),
      BulmaGallery: () => import('~/components/Bulma/Gallery/Gallery.vue'),
      BulmaNews: () => import('~/components/Bulma/News/News.vue')
    },
    methods: {
      name (component) {
        return 'bulma-' + component['@type'].toLowerCase()
      }
    },
    computed: {
      ...mapGetters({
        getComponent: 'component/getComponent'
      }),
      _components () {
        return this.pageData.componentLocations.map(loc => loc.component)
      }
    }
  }
</script>
