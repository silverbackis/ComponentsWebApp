<template>
  <div>
    <div v-if="!nested" class="navbar has-shadow">
      <div v-if="!nested" class="container">
        <div class="column is-paddingless">
          <slot></slot>
        </div>
      </div>
    </div>
    <slot v-else></slot>
    <nuxt-child v-if="nuxtChild"
                :key="childKey"
                :componentGroups="childComponentGroups"
    />
  </div>
</template>

<script>
  import NuxtChildMixin from '../../../nuxtChildMixin'

  export default {
    mixins: [NuxtChildMixin],
    props: {
      nuxtChild: {
        type: Boolean,
        default: true
      }
    }
  }
</script>
