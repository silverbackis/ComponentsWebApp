<template>
  <component-wrapper :className="['hero', 'feature-list', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h3 class="title features-title" v-if="component.title">{{ component.title }}</h3>
        <div class="is-inline-block-mobile">
          <div class="columns is-centered has-text-left">
            <div v-for="(features) in featureChunks()"
                 class="column is-narrow">
              <ul class="fa-ul">
                <li v-for="(feature) in features" :class="feature.className">
                  <span class="fa-li">
                    <font-awesome-icon icon="check-circle" class="has-text-success" size="lg" />
                  </span>
                  <app-link v-if="feature.url" :to="feature.url">
                    <strong>{{ feature.title }}</strong>
                  </app-link>
                  <app-link v-else-if="feature.route" :to="feature.route.route">
                    <strong>{{ feature.title }}</strong>
                  </app-link>
                  <span v-else>
                    {{ feature.title }}
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import _ from 'lodash'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    mixins: [ComponentMixin],
    components: {
      AppLink
    },
    computed: {
      className () {
        return this.component.className || 'is-light'
      }
    },
    methods: {
      featureChunks () {
        if (!this.childComponents.length) {
          return []
        }
        return _.chunk(this.childComponents[0], Math.ceil(this.childComponents[0].length / (this.component.columns || 1)))
      }
    }
  }
</script>

<style lang="sass">
  @import ~bulma/sass/utilities/mixins
  +mobile
    .feature-list
      .column
        padding-top: 0
        padding-bottom: 0
</style>
