<template>
  <nuxt-link tag="li"
             :to="toRoute"
             :active-class="activeClass"
             :exact="toRoute === '/'"
  >
    <a>{{ item.label }}</a>
  </nuxt-link>
</template>

<script>
  import NavItemMixin from '../NavItemMixin'

  export default {
    mixins: [NavItemMixin]
  }
</script>
