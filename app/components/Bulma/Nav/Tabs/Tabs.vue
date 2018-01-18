<template>
  <page-wrapper :nested="nested"
                :childComponentGroups="childComponentGroups"
                :data="data"
                :nuxtChild="nuxtChild"
                :depth="depth"
  >
    <nav class="tabs" :class="this.classModifiers">
      <ul>
        <bulma-tab-item v-for="(item, index) in _items"
                        :key="index"
                        :item="item"
        />
      </ul>
    </nav>
  </page-wrapper>
</template>

<script>
  import NuxtChildMixin from '~/components/nuxtChildMixin'
  import BulmaTabItem from './TabItem'
  import PageWrapper from './TabPageWrapper'

  export default {
    mixins: [NuxtChildMixin],
    components: {
      BulmaTabItem,
      PageWrapper
    },
    props: {
      nuxtChild: {
        type: Boolean,
        default: true
      },
      align: {
        type: String,
        default: null,
        validator: function (value) {
          return ['centered', 'right'].indexOf(value) !== false
        }
      },
      size: {
        type: String,
        default: null,
        validator: function (value) {
          return ['small', 'medium', 'large'].indexOf(value) !== false
        }
      },
      _style: {
        type: String,
        default: null,
        validator: function (value) {
          return ['boxed', 'toggle', 'toggle-rounded'].indexOf(value) !== false
        }
      },
      fullwidth: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      classModifiers () {
        return [
          this.isser(this.align),
          this.isser(this.size),
          this.isser(this.styleClassFixer(this._style)),
          this.isser(this.fullwidthClassFixer(this.fullwidth))
        ]
      },
      _items () {
        return this.data.items || []
      },
      wrapperClassName () {
        return this.nested ? [] : ['navbar', 'has-shadow']
      }
    },
    methods: {
      isser (values) {
        if (!values) {
          return false
        }
        if (typeof values === 'string') {
          values = [values]
        }
        let classes = []
        values.map((value) => {
          if (value) {
            classes.push('is-' + value)
          }
        })
        return classes
      },
      styleClassFixer (cls) {
        return cls === 'toggle-rounded' ? ['toggle', cls] : cls
      },
      fullwidthClassFixer (cls) {
        return cls ? 'fullwidth' : false
      }
    }
  }
</script>
