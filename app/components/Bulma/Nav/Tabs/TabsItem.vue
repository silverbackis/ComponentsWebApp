<template>
  <nuxt-link tag="li"
             :to="toRoute"
             :active-class="activeClass"
             :exact="toRoute === '/'"
  >
    <a>{{ component.label }}</a>
  </nuxt-link>
</template>

<script>
  import NavItemMixin from '../navItemMixin'

  export default {
    mixins: [NavItemMixin]
  }
</script>
