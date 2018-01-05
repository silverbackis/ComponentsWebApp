<template>
  <div class="columns is-centered form-columns">
    <div class="column is-8-tablet">
      <div class="card">
        <div class="card-content">
          <form-tag :form="form" :action="action">
            <slot name="errors">
              <div>
                <ul class="content" v-if="formErrors">
                  <li v-for="(error, index) in formErrors" :key="index"><h4 class="help is-danger" v-html="error"></h4></li>
                </ul>
              </div>
            </slot>
            <slot v-if="!getFormValid(formId)">
              <api-input v-for="input in form.children" :key="input.vars.unique_block_prefix" :input="input" :formId="formId" :disableValidation="disableValidation" />
              <div class="field">
                <div class="control">
                  <button type="submit" id="contact_submit" name="contact[submit]" class="button is-large is-primary is-fullwidth">
                    Send
                  </button>
                </div>
              </div>
            </slot>
            <slot name="success" v-else>
              <div class="content form-result">
                <h1 class="is-success">Thank you</h1>
                <p>The form has been successfully submitted. This is just a test form so nothing has happened except validation.</p>
                <p>On a real system you can easily add the functionality to send an email or any other action in the API.</p>
                <p><strong>You now continue your website expedition.</strong></p>
              </div>
            </slot>
          </form-tag>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import FormTag from './_FormTag'
  import FormId from './_FormId'
  import ApiInput from './Input'
  import { mapGetters } from 'vuex'

  export default {
    name: 'Form',
    props: {
      data: Object,
      wrap: Boolean,
      novalidate: {
        'default': true
      },
      disableValidation: {
        type: Boolean,
        default: false
      },
      successFn: {
        type: Function // ,
        // required: true
      },
      failFn: {
        type: Function,
        required: false,
        default: null
      },
      apiUrl: {
        type: Boolean,
        required: false,
        default: true
      }
    },
    mixins: [FormId],
    components: {
      FormTag,
      ApiInput
    },
    computed: {
      form () {
        return this.data.form
      },
      action () {
        return this.data['@id']
      },
      ...mapGetters({
        getFormValid: 'forms/getFormValid',
        getFormErrors: 'forms/getFormErrors'
      }),
      formErrors () {
        return this.getFormErrors(this.formId)
      }
    }
  }
</script>

<style>
  .field:not(:last-child) {
    margin-bottom: .75rem;
  }
</style>
