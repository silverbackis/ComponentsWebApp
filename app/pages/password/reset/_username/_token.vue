<template>
  <div class="section">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-5">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h2 class="title has-text-primary is-4">Password assistance</h2>
              <p
                v-if="!success"
                class="has-text-grey">Enter your new password below for <strong>{{ $route.params['username'] }}</strong></p>
            </div>
            <div class="column is-12">
              <form
                v-if="!success"
                class="card"
                @submit.prevent="submit">
                <div class="card-content">
                  <div class="field">
                    <label
                      for="passwordFirst"
                      class="label">
                      Password
                    </label>
                    <div class="control">
                      <input
                        id="passwordFirst"
                        v-model="password.first"
                        type="password"
                        class="input"
                        autocomplete="new-password"
                        required>
                    </div>
                  </div>
                  <div class="field">
                    <label
                      for="passwordSecond"
                      class="label">
                      Repeat password
                    </label>
                    <div class="control">
                      <input
                        id="passwordSecond"
                        v-model="password.second"
                        type="password"
                        class="input"
                        autocomplete="new-password"
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
                        class="button is-success is-fullwidth">Reset</button>
                    </div>
                  </div>
                </div>
              </form>
              <div v-else>
                <div class="content form-result">
                  <h1 class="has-text-success has-text-weight-bold">Password reset</h1>
                  <p><strong>You can now login with your new password</strong></p>
                  <nuxt-link 
                    to="/login"
                    class="button is-success">Go to Login</nuxt-link>
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
import { Utilities } from '~/.nuxt/bwstarter/core/server/index'

export default {
  head: {
    title: 'Reset password'
  },
  data() {
    return {
      password: {
        first: '',
        second: ''
      },
      errors: [],
      success: false,
      submitting: false
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
    async submit() {
      if (this.password.first === '') {
        this.errors = ['Please enter your new password']
        return
      }
      if (this.password.first !== this.password.second) {
        this.errors = ['Your passwords do not match']
        return
      }
      this.submitting = true
      const submitObj = {
        username: this.$route.params['username'],
        token: this.$route.params['token'],
        password: this.password.first
      }
      try {
        await this.$axios.post('/password/reset', submitObj)
        this.success = true
        this.submitting = false
      } catch (error) {
        this.submitting = false
        if (error.response) {
          if (error.response.status === 404) {
            this.errors = [
              'We could not match your username and password reset token. Please make sure you are using the latest reset email and have not already changed your password'
            ]
            return
          }
          if (error.response.status === 400) {
            this.errors = error.response.data
            return
          }
        }
        this.errors = ['unknown error occurred']
        console.error(error)
      }
    }
  }
}
</script>
