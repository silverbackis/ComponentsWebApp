<template>
  <div class="layout">
    <div class="site-content">
      <div>
        <bulma-navbar
          v-if="structure && structure.navBar"
          :component="getEntity(structure.navBar['@id'])"
          :items-at-end="true"
        >
          <template slot="logo">
            <nuxt-link
              class="navbar-item is-logo"
              to="/"
              exact
              active-class="logo-active">
              <img 
                src="/images/logo.svg"
                alt="Silverback Web Apps Logo"
              >
            </nuxt-link>
          </template>
          <template
            slot="navbar-end">
            <a
              v-if="!!token"
              class="button is-small is-danger is-outlined is-logout is-radiusless" 
              href="#" 
              @click.prevent="$bwstarter.logout">Logout</a>
          </template>
        </bulma-navbar>
      </div>
      <nuxt/>
    </div>
    <footer :class="{'footer has-text-centered-mobile': true, 'is-authorized': !!token, 'is-admin': $bwstarter.isAdmin}">
      <div class="footer-help help columns align-end">
        <div class="column is-5">
          Silverback Web Apps &copy; {{ new Date().getFullYear() }} | <app-link to="/terms-privacy">Terms & Privacy</app-link>
        </div>
        <div class="column is-narrow has-text-centered">
          <img
            src="/images/logo.svg"
            alt="Silverback Web Apps Logo"
            class="footer-logo"
          >
        </div>
        <div class="column is-5 has-text-right-tablet">
          <app-link to="https://www.silverbackwebapps.com">Silverback Web Apps</app-link>
        </div>
      </div>
    </footer>
    <notifications/>
    <admin-bar v-if="$bwstarter.isAdmin"/>
  </div>
</template>

<script>
import LayoutMixin from '~/.nuxt/bwstarter/components/layoutMixin'
import Notifications from '~/.nuxt/bwstarter/bulma/components/Notifications/Notifications'

export default {
  components: {
    BulmaNavbar: () =>
      import('~/.nuxt/bwstarter/bulma/components/Nav/Navbar/Navbar.vue'),
    AppLink: () => import('~/.nuxt/bwstarter/components/Utils/AppLink'),
    AdminBar: () => import('~/.nuxt/bwstarter/bulma/components/Admin/AdminBar'),
    Notifications
  },
  mixins: [LayoutMixin]
}
</script>

<style lang="sass">
  @import 'assets/sass/utilities'

  $nav-bar-height: 4rem
  $nav-bar-height-desktop: 6.5rem
  $nav-bar-padding-vertical: 1rem

  $nav-bar-item-img-max-height: calc(#{$nav-bar-height} - #{$nav-bar-padding-vertical * 2})
  $nav-bar-item-img-max-height-desktop: calc(#{$nav-bar-height-desktop} - #{$nav-bar-padding-vertical * 2})

  .nuxt-progress
    z-index: 2 !important
  #__layout
    z-index: 1
    position: relative
  .layout
    padding-top: $nav-bar-height
    display: flex
    min-height: 100vh
    flex-direction: column
    .page
      padding-bottom: 3rem
    +desktop
      padding-top: $nav-bar-height-desktop
      .navbar
        height: $nav-bar-height-desktop
        padding: 0 .8rem
    .site-content
      flex: 1 0 auto
    .footer
      background: $grey-background
      color: $grey
      margin-top: 0
      padding-top: 2rem
      padding-bottom: 1rem
      &.is-admin
        margin-bottom: 2.5rem
      a
        color: inherit
      .footer-help
        justify-content: space-between
        .column
          margin-top: auto
      .footer-logo
        width: 30px

  .navbar
    box-shadow: 0 2px 3px 0 $grey-darker
    .navbar-item,
    .navbar-link
      padding: $nav-bar-padding-vertical .7rem
      img
        +desktop
          max-height: $nav-bar-item-img-max-height-desktop

    .navbar-item
      transition: color .3s
      > span:not(.button)
        padding: 0 .3em
        border-bottom: 2px solid transparent
        transition: border-bottom-color .3s
      &:hover
        color: $primary
      &:hover,
      &.is-active
        > span
          border-bottom-color: $primary
      &.is-logo
        flex-wrap: wrap
        text-align: center
        justify-content: start
    .navbar-link
      padding-right: 2.5em

    +touch
      .navbar-burger
        height: auto
        display: flex
      .navbar-menu
        position: absolute
        width: 100%

  .button.is-logout
    position: absolute
    top: -1px
    right: -1px
    border-width: 0 0 1px 1px
    padding-top: .375em
    padding-bottom: .375em
    height: 2.4em
</style>
