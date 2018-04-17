<template>
  <section class="page-section">
    <div class="columns is-centered">
      <div class="column is-narrow">
        <div class="card" v-if="!!getAuthUser">
          <div class="card-header">
            <h1 class="card-header-title">Redirecting...</h1>
          </div>
          <div class="card-content">
            <p>You are logged in. Please wait while we redirect you.</p>
          </div>
        </div>
        <div class="card" v-else>
          <div class="card-header is-dark">
            <h1 class="card-header-title has-text-white">Login</h1>
          </div>
          <div class="card-content">
            <div v-if="formErrors.length">
              <ul class="content">
                <li v-for="(error, index) in formErrors" :key="index"><h4 class="help is-danger" v-html="error"></h4></li>
              </ul>
            </div>
            <form-tag v-if="form"
                      :form="form"
                      :successFn="formSuccess"
                      :apiAction="false">
              <form-input v-for="input in form.children"
                          :key="input.vars.unique_block_prefix"
                          :input="input"
                          :formId="formId"
                          :wrapped="true"
                          :disableValidation="true"
              />
            </form-tag>
            <span class="help is-success">
                <ul>
                    <li>Username: admin@admin.com</li>
                    <li>Password: admin</li>
                </ul>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex'
  import FormTag from '~/components/Form/Form'
  import FormInput from '~/components/Form/FormInput'
  import FormMixin from '~/components/Form/_Mixin'
  import jwtDecode from 'jwt-decode'
  import cookies from '~/server/api/cookies'

  export default {
    mixins: [FormMixin],
    components: {
      FormTag,
      FormInput
    },
    head: {
      title: 'Admin Login',
      meta: [
        { hid: 'description', name: 'description', content: '' }
      ]
    },
    computed: {
      ...mapGetters({
        getApiUrl: 'getApiUrl',
        getAuthUser: 'getAuthUser'
      }),
      formErrors () {
        return this.storeForm ? this.storeForm.vars.errors : []
      }
    },
    methods: {
      ...mapMutations({
        setAuthUser: 'setAuthUser',
        addNotification: 'notifications/addNotification'
      }),
      formSuccess (data) {
        if (data.token) {
          this.setAuthUser(jwtDecode(data.token))
          this.$router.replace('/')
        }
      }
    },
    mounted () {
      let authUser = this.getAuthUser
      if (authUser) {
        this.$router.replace('/')
        this.addNotification('You are already logged in')
      }
    },
    async asyncData ({ store: { dispatch, getters }, app: { $axios }, res }) {
      let response = await dispatch('layout/init')
      if (process.server) {
        cookies.setCookies(res, response)
      }
      try {
        let { data: { form } } = await $axios.get('login_form')
        form.vars.action = '/login'
        return {
          form
        }
      } catch (err) {
        console.error('Could not load form', err)
      }
    }
  }
</script>

<style lang="sass" scoped>
  @import ~assets/css/_vars

  .card
    width: 300px
    margin: 2rem auto
    .media
      margin: 1rem
    .card-header.is-dark
      background: $dark
</style>
