<template>
  <component-wrapper :nested="nested">
    <div :class="containerClass">
      <div class="collection-columns columns is-mobile">
        <component :is="itemComponent"
                   v-for="item in component.collection"
                   :component="item"
                   :key="item['@id']"
        />
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'

  export default {
    mixins: [ComponentMixin],
    data () {
      return {
        itemComponent: null
      }
    },
    methods: {
      resolveItemComponent () {
        let resourceParts = this.component.resource.split('\\')
        this.itemComponent = () => ({
          component: import('./Item/' + resourceParts[resourceParts.length - 1])
        })
      }
    },
    created () {
      this.resolveItemComponent()
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins
  +mobile
    .collection-columns
      justify-content: center
</style>
