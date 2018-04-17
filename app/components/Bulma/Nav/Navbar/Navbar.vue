<template>
  <component-wrapper ref="nav"
                     dom-tag="nav"
                     :extendClass="false"
                     :class-name="['navbar', 'is-fixed-top', 'has-shadow', component.className]"
                     :style="{transform: 'translateY(' + this.navY + 'px)'}"
  >
    <div class="navbar-brand">
      <nuxt-link class="navbar-item" to="/" exact>
        <img src="~/assets/images/bw-logo.svg" alt="British Websites logo" class="logo"/>
      </nuxt-link>
      <div class="navbar-burger burger" @click="isActive=!isActive" :class="{ 'is-active': isActive }">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <div class="navbar-menu" :class="{ 'is-active': isActive }">
      <div class="navbar-start" v-if="childComponents.length">
        <bulma-navbar-item v-for="(component, index) in childComponents[0]"
                           :component="component"
                           :key="index"
        />
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="field is-grouped">
            <p class="control">
              <a class="button is-primary" :href="getApiUrl('')" rel="noopener" target="_blank">
                <span class="icon">
                  <font-awesome-icon icon="book"/>
                </span>
                <span>
                  API Docs
                </span>
              </a>
            </p>
            <p class="control">
              <a class="button is-outlined" href="https://github.com/silverbackis/BwStarterWebsite" rel="noopener" target="_blank">
                <span class="icon">
                  <font-awesome-icon :icon="['fab', 'github']" size="lg"/>
                </span>
                <span>GitHub</span>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import { mapGetters } from 'vuex'
  import BulmaNavbarItem from './NavbarItem'
  import componentMixin from '~/components/componentMixin'

  export default {
    components: {
      BulmaNavbarItem
    },
    mixins: [ componentMixin ],
    data () {
      return {
        isActive: false,
        windowY: 0,
        lastWindowY: 0,
        yTicking: false,
        navY: 0
      }
    },
    computed: {
      ...mapGetters([ 'getApiUrl' ])
    },
    watch: {
      // whenever question changes, this function will run
      isActive: function (isActive) {
        if (isActive) {
          this.navY = 0
        }
      },
      $route () {
        this.isActive = false
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
        this.navY = this.isActive ? 0 : Math.min(Math.max(this.navY - diff, this.$refs.nav.$el.clientHeight * -1), 0)
      }
    },
    mounted () {
      window.addEventListener('scroll', this.updateWindowY)
    },
    beforeDestroy () {
      window.removeEventListener('scroll', this.updateWindowY)
    }
  }
</script>

<style lang="sass">
  @import "~assets/css/vars"

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
