<template>
  <div>
    <div v-if="wrap" class="navbar has-shadow">
      <div class="container">
        <div class="column is-paddingless">
          <slot></slot>
        </div>
      </div>
    </div>
    <slot v-else></slot>
    <nuxt-child v-if="!noChild" :key="childKey" :componentGroups="childComponentGroups" />
  </div>
</template>

<script>
  import NuxtChildMixin from '../NuxtChildMixin'

  export default {
    mixins: [NuxtChildMixin],
    props: {
      noChild: {
        type: Boolean,
        default: false
      },
      wrap: {
        type: Boolean,
        default: false
      },
      depth: {
        required: false
      }
    }
  }
</script>
