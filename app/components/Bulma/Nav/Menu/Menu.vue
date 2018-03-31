<template>
  <component-wrapper :nested="nested">
    <div class="container">
      <div class="columns">
        <div class="column is-narrow">
          <aside class="menu">
            <bulma-menu-item-group v-for="(itemGroup, index) in navItemsGrouped"
                                   :navItems="itemGroup"
                                   :key="index"
            />
          </aside>
        </div>
        <div class="column">
          <nuxt-child :key="childKey"
                      :componentGroup="component.childComponentGroup"
                      :nested="true"
          />
        </div>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import NuxtChildMixin from '~/components/nuxtChildMixin'
  import BulmaMenuItemGroup from './MenuItemGroup'

  export default {
    mixins: [NuxtChildMixin],
    components: {
      BulmaMenuItemGroup
    },
    computed: {
      navItems () {
        return this.childComponents[0]
      },
      navItemsGrouped () {
        let groups = []
        let currentGroup = []
        let previousItem
        this.navItems.forEach((navItem) => {
          navItem = this.getComponent(navItem)
          if (previousItem && (previousItem.menuLabel || navItem.menuLabel)) {
            groups.push(currentGroup)
            currentGroup = []
          }
          currentGroup.push(navItem)
          previousItem = navItem
        })
        groups.push(currentGroup)
        return groups
      }
    }
  }
</script>

<style lang="sass">
  @import "../../../../assets/css/vars"

  aside.menu
    padding: .75rem
    border: 1px solid $grey-lighter
    min-width: 250px
</style>
