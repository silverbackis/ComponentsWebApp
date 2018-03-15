<template>
  <nuxt-link v-if="!childComponents.length"
             class="navbar-item"
             :to="toRoute"
             :active-class="activeClass"
             :exact="toRoute === '/'"
  >
    {{ component.label }}
  </nuxt-link>
  <div v-else class="navbar-item has-dropdown is-hoverable">
    <nuxt-link class="navbar-link"
               :to="toRoute"
               :active-class="activeClass"
               :exact="toRoute === '/'"
    >
      {{ component.label }}
    </nuxt-link>
    <div class="navbar-dropdown"
         v-for="(group, index) in childComponents"
         :key="index"
    >
      <bulma-navbar-item v-for="(childComponent, index) in group"
                         :key="index"
                         :component="childComponent"
      />
    </div>
  </div>
</template>

<script>
  import NavItemMixin from '../navItemMixin'

  export default {
    name: 'BulmaNavbarItem',
    mixins: [NavItemMixin]
  }
</script>
