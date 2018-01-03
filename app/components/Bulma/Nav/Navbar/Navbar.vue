<template>
  <nav class="navbar is-fixed-top has-shadow"
       :style="{transform: 'translateY(' + this.navY + 'px)'}"
       ref="nav"
  >
    <div class="navbar-brand">
      <nuxt-link class="navbar-item" to="/" exact>
        <img src="~/assets/images/bw-logo.svg" alt="British Websites logo" class="logo" />
      </nuxt-link>
      <div class="navbar-burger burger" @click="isActive=!isActive" :class="{ 'is-active': isActive }">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <div class="navbar-menu" :class="{ 'is-active': isActive }">
      <div class="navbar-start">
        <bulma-navbar-item v-for="(item, index) in navItems" :key="index" :item="item" />
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="field is-grouped">
            <p class="control">
              <a class="button is-outlined is-dark" href="https://github.com/silverbackis/BwStarterWebsite" rel="noopener">
                <span class="icon">
                  <i class="fa fa-lg fa-github"></i>
                </span>
                <span>GitHub</span>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
  import { mapState } from 'vuex'
  import BulmaNavbarItem from './NavbarItem'

  export default {
    components: {
      BulmaNavbarItem
    },
    data () {
      return {
        isActive: false,
        windowY: 0,
        lastWindowY: 0,
        yTicking: false,
        navY: 0
      }
    },
    watch: {
      // whenever question changes, this function will run
      isActive: function (isActive) {
        if (isActive) {
          this.navY = 0
        }
      }
    },
    methods: {
      updateWindowY () {
        this.windowY = Math.max(window.scrollY, 0)
        this.requestYTick()
      },
      requestYTick () {
        if (!this.yTicking) {
          this.yTicking = true
          requestAnimationFrame(this.updateNavY)
        }
      },
      updateNavY () {
        let diff = this.windowY - this.lastWindowY
        this.lastWindowY = this.windowY
        // iOS does not always trigger a scroll event (e.g. when bouncing)
        // so we stop ticking when we see there has been no movement
        this.yTicking = diff !== 0
        if (this.yTicking) {
          requestAnimationFrame(this.updateNavY)
        }

        this.navY = this.isActive ? 0 : Math.min(Math.max(this.navY - diff, this.$refs.nav.clientHeight * -1), 0)
      }
    },
    computed: {
      ...mapState({
        navItems: state => state.layout.data.nav.items
      })
    },
    mounted () {
      // Not using font awesome immediately in this website, so no need to include it in the head of page
      require('font-awesome/css/font-awesome.css')
      window.addEventListener('scroll', this.updateWindowY)
    },
    beforeDestroy () {
      window.removeEventListener('scroll', this.updateWindowY)
    }
  }
</script>

<style lang="sass">
  @import "../../../../assets/css/vars"

  .logo
    width: auto
    height: 28px

  .navbar.has-shadow
    .tabs
      &:not(.is-boxed):not(.is-toggle)
        > ul
          border: 0
        li
          a
            margin-bottom: 0
            min-height: $navbar-height
            border-bottom-color: transparent
            &:hover
              color: $primary
              border-color: $primary
          &.is-active
            a
              border-bottom-width: 2px
              border-color: $primary
</style>
