<template>
    <ul v-if="navItems.length && !navItems[0].menuLabel"
        class="menu-list">
      <bulma-menu-item v-for="(component, index) in navItems"
                       :key="index"
                       :component="component"
      />
    </ul>
    <div v-else>
      <bulma-menu-item v-for="(component, index) in navItems"
                       :key="index"
                       :component="component"
      />
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
