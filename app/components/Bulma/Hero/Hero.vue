<template>
  <div>
    <section :class="['hero', className]">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            {{ data.title }}
          </h1>
          <h2 class="subtitle">
            {{ data.subtitle }}
          </h2>
        </div>
      </div>
      <div v-if="data.nav"
           class="hero-foot">
        <div class="container">
          <bulma-tabs _style="boxed"
                      :items="data.nav.items"
                      :data="data.nav"
          />
        </div>
      </div>
    </section>
    <nuxt-child v-if="data.nav" :key="childKey" :componentGroups="childComponentGroups" />
  </div>
</template>

<script>
  import NuxtChildMixin from '~/components/Bulma/Nav/NuxtChildMixin'

  export default {
    name: 'Hero',
    mixins: [NuxtChildMixin],
    computed: {
      className () {
        return this.data.className || 'is-primary is-bold'
      }
    },
    components: {
      BulmaTabs: () => import('~/components/Bulma/Nav/Tabs/Tabs.vue')
    }
  }
</script>
