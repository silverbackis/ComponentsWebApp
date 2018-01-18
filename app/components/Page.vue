<template>
  <div>
    <bulma-components v-if="pageData && pageData.components.length"
                      :pageData="pageData"
                      :depth="depth"
                      :nested="nested"
    />
    <bulma-components v-else-if="componentGroups.length"
                      v-for="(pageData, index) in componentGroups"
                      :key="index"
                      :pageData="pageData"
                      :depth="depth"
                      :nested="nested"
    />
    <nuxt-child v-else-if="childKey" :key="childKey" />
    <h1 v-else>No components or children configured for this page</h1>
  </div>
</template>

<script>
  import BulmaComponents from '~/components/Bulma/components.vue'

  export default {
    components: {
      BulmaComponents
    },
    props: {
      depth: {
        type: Number,
        required: true
      },
      nested: {
        type: Boolean,
        default: false
      },
      pageData: {
        type: Object
      },
      componentGroups: {
        type: Array,
        required: false,
        default () {
          return []
        }
      },
      noContainer: {
        type: Boolean,
        required: true
      }
    },

    computed: {
      childKey () {
        return this.$route.params['page' + (this.depth + 2)]
      }
    }
  }
</script>
