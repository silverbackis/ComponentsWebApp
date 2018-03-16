<template>
  <div v-if="component.menuLabel"
       class="menu-label">
    {{ component.label }}
  </div>
  <li v-else>
    <nuxt-link :to="toRoute"
               :active-class="activeClass"
               :exact="toRoute === '/'"
    >
      {{ component.label }}
    </nuxt-link>
    <ul v-if="component.child">
    </ul>
  </li>
</template>
<script>
  import NavItemMixin from '../navItemMixin'

  export default {
    name: 'BulmaMenuItem',
    mixins: [NavItemMixin]
  }
</script>