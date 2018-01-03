<template>
  <div v-if="item.menuLabel"
       class="menu-label">
    {{ item.label }}
  </div>
  <li v-else>
    <nuxt-link :to="toRoute"
               :active-class="activeClass"
               :exact="toRoute === '/'"
    >
      {{ item.label }}
    </nuxt-link>
    <ul v-if="item.child">
    </ul>
  </li>
</template>
<script>
  import NavItemMixin from '../NavItemMixin'

  export default {
    name: 'BulmaMenuItem',
    mixins: [NavItemMixin]
  }
</script>