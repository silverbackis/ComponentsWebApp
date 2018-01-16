<template>
  <div>
    <page-wrapper :wrap="wrap"
                  :depth="depth"
                  :childComponentGroups="childComponentGroups"
                  :data="data"
                  :noChild="noChild"
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
  </div>
</template>

<script>
  import BulmaTabItem from './TabItem'
  import PageWrapper from './TabPageWrapper'

  export default {
    components: {
      BulmaTabItem,
      PageWrapper
    },
    props: {
      noChild: {
        type: Boolean,
        default: false
      },
      depth: {
        type: Number,
        required: false
      },
      data: {
        type: Object,
        required: false
      },
      items: {
        type: Array,
        required: false
      },
      childComponentGroups: {
        type: Array
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
      },
      wrap: {
        type: Boolean
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
        let items = this.data ? this.data.items : this.items
        return items || []
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
