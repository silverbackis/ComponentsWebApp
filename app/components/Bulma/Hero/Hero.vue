<template>
  <div>
    <component-wrapper :className="['hero', className]"
                       :extendClass="false"
                       :nested="nested"
    >
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
                      :nuxtChild="false"
                      :nested="true"
                      :depth="depth"
          />
        </div>
      </div>
    </component-wrapper>
    <nuxt-child v-if="data.nav"
                :key="childKey"
                :componentGroups="childComponentGroups"
                :nested="false"
    />
  </div>
</template>

<script>
  import NuxtChildMixin from '~/components/nuxtChildMixin'

  export default {
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
