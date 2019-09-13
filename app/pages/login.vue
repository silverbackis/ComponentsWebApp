<template>
  <div :class="['page', pageClass]">
    <component
      :is="componentName(heroComponent)"
      :component="getEntity(heroComponent)"
      :nested="true"
      :depth="depth"
    />
    <div class="section">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-5">
            <component
              :is="componentName(loginFormComponent)"
              :component="getEntity(loginFormComponent)"
              :nested="true"
              :depth="depth"
              :success-fn="loginSuccess"
            >
              <template slot="form-append">
                <div class="has-text-right">
                  <nuxt-link
                    to="/forgot-password"
                    class="button is-link is-small is-inverted">Forgot password?</nuxt-link>
                </div>
              </template>
              <template slot="success">
                <div class="content form-result">
                  <h1 class="has-text-success has-text-weight-bold">You are now logged in</h1>
                  <p><strong>Please wait while we redirect you to <nuxt-link to="/">your home page</nuxt-link>...</strong></p>
                </div>
              </template>
            </component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import pageMixin from '~/.nuxt/bwstarter/components/pageMixin'
import bulmaComponentsMixin from '~/.nuxt/bwstarter/bulma/components'

export default {
  mixins: [pageMixin, bulmaComponentsMixin],
  computed: {
    heroComponent() {
      return this.getEntity(this.realPageData.componentLocations[0]).component
    },
    loginFormComponent() {
      return this.getEntity(this.realPageData.componentLocations[1]).component
    }
  },
  methods: {
    handleSuccess(data, path) {
      if (!data.token) {
        console.error('Authentication token not returned in data', data)
        return
      }
      this.$bwstarter.$storage.setState('token', data.token)
      this.$router.replace(path)
    },
    loginSuccess(data) {
      this.handleSuccess(data, '/')
    }
  }
}
</script>
