import Page from './Page.vue'

export default function createPageView (depth) {
  return {
    name: `page-${depth}`,

    async asyncData ({ store, route }) {
      await store.dispatch('page/FETCH_DEPTH_DATA', { depth, route })
      return {
        pageData: await store.getters['page/getPageByDepth'](depth)
      }
    },

    head () {
      if (!this.pageData) {
        return {}
      }
      return {
        title: this.title,
        meta: [
          { name: 'description', content: this.metaDescription }
        ]
      }
    },

    props: {
      componentGroups: {
        type: Array
      },
      noContainer: {
        type: Boolean,
        default: true
      }
    },

    computed: {
      title () {
        return this.pageData.title
      },
      metaDescription () {
        return this.pageData.metaDescription
      }
    },

    render (h) {
      return h(Page, {
        props: {
          depth,
          pageData: this.pageData,
          componentGroups: this.componentGroups,
          noContainer: this.noContainer
        }
      })
    },

    transition () {
      return {
        name: 'page',
        mode: 'out-in'
      }
    }
  }
}
