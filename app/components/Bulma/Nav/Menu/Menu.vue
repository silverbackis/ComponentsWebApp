<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-narrow">
          <aside class="menu">
            <bulma-menu-item-group v-for="(itemGroup, index) in navItemsGrouped"
                                   :navItems="itemGroup"
                                   :key="index" />
          </aside>
        </div>
        <div class="column">
          <nuxt-child :key="childKey" :componentGroups="childComponentGroups" :noContainer="false" />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import BulmaMenuItemGroup from './MenuItemGroup'
  import NuxtChildMixin from '../NuxtChildMixin'

  export default {
    name: 'Menu',
    mixins: [NuxtChildMixin],
    components: {
      BulmaMenuItemGroup
    },
    computed: {
      navItems () {
        return this.data.items
      },
      navItemsGrouped () {
        let groups = []
        let currentGroup = []
        let previousItem
        for (let i = 0; i < this.navItems.length; i++) {
          let navItem = this.navItems[i]
          if (previousItem && (previousItem.menuLabel || navItem.menuLabel)) {
            groups.push(currentGroup)
            currentGroup = []
          }
          currentGroup.push(navItem)
          previousItem = navItem
        }
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
</style>
