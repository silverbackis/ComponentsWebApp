<template>
  <nuxt-link v-if="!item.child"
             class="navbar-item"
             :to="toRoute"
             :active-class="activeClass"
             :exact="toRoute === '/'"
             @click.native="navClick"
  >
    {{ item.label }}
  </nuxt-link>
  <div v-else class="navbar-item has-dropdown is-hoverable">
    <nuxt-link class="navbar-link"
               :to="toRoute"
               :active-class="activeClass"
               :exact="toRoute === '/'"
               @click.native="navClick"
    >
      {{ item.label }}
    </nuxt-link>
    <div class="navbar-dropdown">
      <bulma-navbar-item v-for="(childItem, childIndex) in childItems"
                         :key="childIndex"
                         :item="childItem"
                         @navClick="$emit('navClick')"
      />
    </div>
  </div>
</template>

<script>
  import NavItemMixin from '../NavItemMixin'

  export default {
    name: 'BulmaNavbarItem',
    mixins: [NavItemMixin],
    methods: {
      navClick (event) {
        if (event.target.href !== '#') {
          this.$emit('navClick')
        }
      }
    }
  }
</script>
