<template>
  <div>
    <bulma-components v-if="pageData && pageData.componentLocations.length"
                      :pageData="pageData"
                      :depth="depth"
                      :nested="nested"
    />
    <nuxt-child v-else-if="childKey" :key="childKey" />
    <h1 v-else>No components or children configured for this page</h1>
  </div>
</template>

<script>
  import BulmaComponents from '~/components/Bulma/Components'

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
        type: Object,
        required: false
      },
      componentGroup: {
        type: Object,
        required: false
      }
    },

    computed: {
      childKey () {
        return this.$route.params['page' + (this.depth + 2)]
      }
    }
  }
</script>
