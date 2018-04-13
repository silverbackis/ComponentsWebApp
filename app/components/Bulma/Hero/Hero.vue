<template>
  <div v-if="component">
    <component-wrapper :className="['hero', className]"
                       :extendClass="false"
                       :nested="nested"
    >
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            {{ injectDynamicData(component.title) }}
          </h1>
          <h2 class="subtitle">
            {{ injectDynamicData(component.subtitle) }}
          </h2>
        </div>
      </div>
      <div v-if="tabs"
           class="hero-foot"
      >
        <div class="container">
          <bulma-tabs _style="boxed"
                      :component="tabs"
                      :includeNuxtChild="false"
                      :nested="true"
                      :depth="depth"
          />
        </div>
      </div>
    </component-wrapper>
    <nuxt-child v-if="tabs"
                :key="childKey"
                :componentGroup="tabs.childComponentGroup"
                :nested="false"
    />
  </div>
</template>

<script>
  import NuxtChildMixin from '~/components/nuxtChildMixin'

  export default {
    mixins: [NuxtChildMixin],
    props: ['cid'],
    computed: {
      className () {
        return this.component.className || 'is-primary is-bold'
      },
      tabs () {
        let groups = this.component.componentGroups || []
        if (!groups.length || !groups[0].componentLocations.length) {
          return
        }
        return groups[0].componentLocations[0].component
      }
    },
    components: {
      BulmaTabs: () => import('~/components/Bulma/Nav/Tabs/Tabs.vue')
    }
  }
</script>
