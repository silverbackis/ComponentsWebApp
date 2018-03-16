import Page from './Page.vue'

export default function createPageView (depth) {
  return {
    name: `page-${depth}`,

    asyncData ({ store: { getters } }) {
      let pageData = getters['getContent'](depth)
      return {
        pageData
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
      componentGroup: {
        type: Object,
        required: false
      },
      nested: {
        type: Boolean,
        default: false
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
      if (this.pageData || this.componentGroup) {
        return h(Page, {
          props: {
            depth,
            pageData: this.pageData || this.componentGroup,
            nested: this.nested
          }
        })
      }
    },

    transition () {
      return {
        name: 'page',
        mode: 'out-in'
      }
    }
  }
}
