<template>
  <div class="section">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-5">
          <div class="columns is-multiline">
            <div
              v-if="!success"
              class="column is-12">
              <h2 class="title has-text-primary is-4">Password assistance</h2>
              <p class="has-text-grey">Enter the email address used to log into your account.</p>
            </div>
            <div class="column is-12">
              <form
                v-if="!success"
                class="card" 
                @submit.prevent="submitRequest">
                <div class="card-content">
                  <div class="field">
                    <label
                      for="emailAddress"
                      class="label">
                      Email
                    </label>
                    <div class="control">
                      <input
                        id="emailAddress"
                        v-model="emailAddress"
                        type="email"
                        class="input"
                        required>
                    </div>
                    <div 
                      v-if="errors.length"
                      class="help is-danger">
                      <ul>
                        <li 
                          v-for="(error, errorIndex) in errors" 
                          :key="errorIndex" 
                          v-html="error"/>
                      </ul>
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">
                      <button
                        :disabled="submitting"
                        type="submit"
                        class="button is-success is-fullwidth">Continue</button>
                    </div>
                  </div>
                  <div class="has-text-left">
                    <nuxt-link
                      to="/login"
                      class="button is-link is-small is-inverted">&lt;&lt; Back to login</nuxt-link>
                  </div>
                </div>
              </form>
              <div v-else>
                <div class="content form-result">
                  <h1 class="has-text-success has-text-weight-bold">An email is coming</h1>
                  <p><strong>We have send an email to you with a link so you can reset your password</strong></p>
                  <p>You will only receive one email every 24 hours. If you do not see the email in your inbox, please check your junk/spam folder.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Utilities } from '~/.nuxt/bwstarter/core/server'

export default {
  data() {
    return {
      emailAddress: '',
      submitting: false,
      success: false,
      errors: []
    }
  },
  async asyncData({
    store: { dispatch, getters },
    app: { $axios, $bwstarter },
    res
  }) {
    let response = await $bwstarter.fetchAndStoreLayout(null, true)
    if (process.server) {
      Utilities.setResponseCookies(res, response)
    }
  },
  methods: {
    async submitRequest() {
      if (this.emailAddress === '') {
        this.errors = ['Please enter your email address']
        return
      }
      this.submitting = true
      this.errors = []
      try {
        await this.$axios.get(
          '/password/reset/request/' +
            encodeURIComponent(this.emailAddress) +
            '?resetPath=/password/reset/{{ email }}/{{ token }}'
        )
        this.success = true
      } catch (e) {
        if (e.response && e.response.status === 404) {
          this.errors = ['We could not find a user with that email address']
        } else {
          console.error(e)
        }
      }
      this.submitting = false
    }
  }
}
</script>
