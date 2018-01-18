<template>
  <component-wrapper :className="['hero', 'feature-horizontal', className]"
                     :extendClass="false"
                     :nested="nested"
  >
    <div class="hero-body">
      <div class="container has-text-centered">
        <h3 class="title features-title">Feature List</h3>
        <div class="is-inline-block-mobile">
          <div class="columns is-centered has-text-left">
            <div v-for="(features) in featureChunks()"
                 class="column is-narrow">
              <ul class="fa-ul">
                <li v-for="(feature) in features">
              <span class="fa-li">
                <font-awesome-layers class="fa-lg">
                  <font-awesome-icon icon="circle" class="has-text-success" />
                  <font-awesome-icon icon="check" class="has-text-white" transform="shrink-6" />
                </font-awesome-layers>
              </span>
                  <app-link v-if="feature.link" :to="feature.link">
                    <strong>{{ feature.text }}</strong>
                  </app-link>
                  <span v-else>
                {{ feature.text }}
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
    data () {
      return {
        features: [
          {
            text: 'Link back home',
            link: '/'
          },
          {
            text: 'Link external to BW',
            link: 'https://www.britishwebsites.co.uk'
          },
          {
            text: 'Feature 3'
          },
          {
            text: 'Feature 4'
          },
          {
            text: 'Feature 5'
          },
          {
            text: 'Feature 6'
          },
          {
            text: 'Feature 7'
          },
          {
            text: 'Yet another feature varying in length'
          }
        ],
        columns: 3
      }
    },
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
        return _.chunk(this.features, Math.ceil(this.features.length / this.columns))
      }
    }
  }
</script>

<style lang="sass" scoped>
  @import ~bulma/sass/utilities/mixins
  +mobile
    .column
      padding-top: 0
      padding-bottom: 0
</style>
