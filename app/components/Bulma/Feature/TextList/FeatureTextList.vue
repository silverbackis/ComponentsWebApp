<template>
  <component-wrapper :className="['hero', 'feature-list', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h3 class="title features-title" v-if="data.title">{{ data.title }}</h3>
        <div class="is-inline-block-mobile">
          <div class="columns is-centered has-text-left">
            <div v-for="(features) in featureChunks()"
                 class="column is-narrow">
              <ul class="fa-ul">
                <li v-for="(feature) in features" :class="feature.className">
                  <span class="fa-li">
                    <font-awesome-icon icon="check-circle" class="has-text-success" size="lg" />
                  </span>
                  <app-link v-if="feature.link" :to="feature.link">
                    <strong>{{ feature.label }}</strong>
                  </app-link>
                  <span v-else>
                    {{ feature.label }}
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
  import { mapGetters } from 'vuex'
  import _ from 'lodash'
  import AppLink from '~/components/Utils/AppLink'

  export default {
    mixins: [ComponentMixin],
    components: {
      AppLink
    },
    computed: {
      ...mapGetters(['getApiUrl']),
      className () {
        return this.data.className || 'is-light'
      }
    },
    methods: {
      featureChunks () {
        return _.chunk(this.data.items, Math.ceil(this.data.items.length / this.data.columns))
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
