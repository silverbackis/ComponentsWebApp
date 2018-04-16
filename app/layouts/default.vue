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
    <footer :class="{'footer': true, 'authorized': getAuthUser}">
      <div class="container has-text-centered has-text-weight-bold">
        <div v-if="!getAuthUser">
          Try out the admin? <app-link to="/login">Admin login</app-link>
        </div>
        <div v-else>
          You are logged in <a href="#" @click.prevent="logout">Logout</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex'
  import BulmaNavbar from '~/components/Bulma/Nav/Navbar/Navbar.vue'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    components: {
      BulmaNavbar,
      AppLink
    },
    computed: {
      ...mapGetters({
        layout: 'layout/getLayout',
        getAuthUser: 'getAuthUser',
        getApiUrl: 'getApiUrl'
      })
    },
    methods: {
      ...mapMutations({
        setAuthUser: 'setAuthUser'
      }),
      logout () {
        this.$axios.post('/logout',
          {
            _action: this.getApiUrl('logout')
          },
          {
            baseURL: null
          }
        )
          .then(() => {
            // this.addNotification('You have successfully logged out')
            this.setAuthUser(null)
          })
          .catch((err) => {
            console.warn(err)
          })
      }
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
    padding-bottom: 3rem
    &.authorized
      background-color: $success
      color: $white
</style>
