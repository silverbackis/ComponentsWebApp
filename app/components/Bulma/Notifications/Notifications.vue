<template>
  <transition-group name="slide-right"
                    tag="ul"
                    class="notifications-box"
                    v-show="show"
                    @beforeEnter="transBeforeEnter"
                    @afterLeave="transAfterLeave"
  >
    <notification v-for="(notification, index) in notifications" :key="notification.id" :index="index" :notification="notification" />
  </transition-group>
</template>

<script>
  import Notification from './Notification'

  export default {
    components: {
      Notification
    },
    data () {
      return {
        show: false
      }
    },
    methods: {
      transBeforeEnter (el) {
        if (!this.show) {
          this.show = true
        }
      },
      transAfterLeave () {
        if (!this.notifications.length) {
          this.show = false
        }
      }
    },
    computed: {
      notifications () {
        return this.$store.getters['notifications/getNotifications']()
      }
    }
  }
</script>

<style lang="sass">
  @import '~assets/css/_vars'

  .notifications-box
    padding: 1rem 1rem 1rem 0
    position: fixed
    width: 25%
    max-width: 350px
    min-width: 200px
    bottom: 0
    right: 0
    z-index: 100

  .slide-right-enter-active
    transition: opacity .3s, transform .5s

  .slide-right-leave-active
    transition: opacity .3s, transform .3s

  .slide-right-enter
    opacity: 0
    transform: translateX(-50%)

  .slide-right-leave-to
    opacity: 0
    transform: translateX(110%)
</style>
