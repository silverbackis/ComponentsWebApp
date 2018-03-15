<template>
  <div class="layout">
    <div class="site-content">
      <header v-if="layout">
        <bulma-navbar v-if="layout.navBar"
                      :component="layout.navBar"
        />
      </header>
      <nuxt />
    </div>
    <footer class="footer">

    </footer>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import BulmaNavbar from '~/components/Bulma/Nav/Navbar/Navbar.vue'

  export default {
    components: {
      BulmaNavbar
    },
    computed: {
      ...mapGetters({
        layout: 'layout/getLayout'
      })
    },
    head () {
      return {
        title: 'Loading...',
        meta: [
          { hid: 'theme', name: 'theme-color', content: '#4770fb' }
        ],
        htmlAttrs: { lang: 'en' }
      }
    }
  }
</script>

<style lang="sass">
  @import assets/css/_vars.sass

  a
    transition: color .25s, border .25s, background-color .25s

  .appear-active
    transition: opacity .4s ease
  .page-enter-active, .page-leave-active
    transition: all .2s ease
  .appear, .page-enter, .page-leave-active
    opacity: 0

  =selection
    color: $white
    background: $primary

  ::selection
    +selection

  ::-moz-selection
    +selection

  body,
  html
    height: 100%

  .layout
    padding-top: 3.75rem
    display: flex
    min-height: 100vh
    flex-direction: column

  .site-content
    flex: 1 0 auto

  .footer
    margin-top: 3rem
</style>
