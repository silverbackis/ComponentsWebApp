<template>
  <div class="bulma-components">
    <component v-for="component in _components"
               :is="name(component)"
               :key="component.id"
               :data="component"
               :wrap="wrap"
               :depth="depth"
    ></component>
  </div>
</template>

<script>
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
      wrap: {
        type: Boolean,
        required: true
      }
    },
    components: {
      BulmaHero: () => import('~/components/Bulma/Hero/Hero.vue'),
      BulmaContent: () => import('~/components/Bulma/Content/Content.vue'),
      BulmaTabs: () => import('~/components/Bulma/Nav/Tabs/Tabs.vue'),
      BulmaMenu: () => import('~/components/Bulma/Nav/Menu/Menu.vue'),
      BulmaForm: () => import('~/components/Bulma/Form/Form.vue')
    },
    methods: {
      name (component) {
        return 'bulma-' + component.type
      }
    },
    computed: {
      _components () {
        return this.pageData.components
      }
    }
  }
</script>
