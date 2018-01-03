<template>
    <ul v-if="!navItems[0].menuLabel"
        class="menu-list">
      <bulma-menu-item v-for="(item, index) in navItems"
                       :key="index"
                       :item="item" />
    </ul>
    <div v-else>
      <bulma-menu-item v-for="(item, index) in navItems"
                       :key="index"
                       :item="item" />
    </div>
</template>

<script>
  import BulmaMenuItem from './MenuItem'

  export default {
    components: {
      BulmaMenuItem
    },
    props: {
      navItems: {
        type: Array,
        required: true
      }
    }
  }
</script>
