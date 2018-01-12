<template>
  <div>
    <div v-if="pageData || componentGroups.length">
      <bulma-components v-if="pageData"
                        :pageData="pageData"
                        :depth="depth"
                        :wrap="wrap"
      />
      <bulma-components v-else
                        v-for="(pageData, index) in componentGroups"
                        :key="index"
                        :pageData="pageData"
                        :depth="depth"
                        :wrap="wrap"
      />
    </div>
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
      wrap () {
        return this.noContainer
      }
    }

    /* async beforeMount () {
      if (this.$root._isMounted) {
        // console.log('before mount, root is mounted')
      } else {
        // console.log('before mount, root is not mounted')
      }
    } */
  }
</script>
