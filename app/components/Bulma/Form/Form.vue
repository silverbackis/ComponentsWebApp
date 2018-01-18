<template>
  <component-wrapper :nested="nested">
    <div :class="[...containerClass, 'form-container']">
      <div class="card">
        <div class="card-content">
          <form-tag :form="form">

            <slot name="errors" v-if="formErrors.length">
              <div>
                <ul class="content">
                  <li v-for="(error, index) in formErrors" :key="index"><h4 class="help is-danger" v-html="error"></h4></li>
                </ul>
              </div>
            </slot>

            <slot v-if="!formValid">
              <form-input v-for="input in form.children"
                          :key="input.vars.unique_block_prefix"
                          :input="input"
                          :formId="formId"
                          :wrapped="true"
              />
            </slot>

            <slot name="success" v-else>
              <div class="content form-result">
                <h1 class="has-text-success has-text-weight-bold">Thank you</h1>
                <p>The form has been successfully submitted. This is just a test form so nothing has happened except validation.</p>
                <p>On a real system you can easily add the functionality to send an email or any other action in the API.</p>
                <p><strong>You now continue your website expedition.</strong></p>
              </div>
            </slot>

          </form-tag>
        </div>
      </div>
    </div>
  </component-wrapper>
</template>

<script>
  import ComponentMixin from '~/components/componentMixin'
  import FormTag from '~/components/Form/Form'
  import FormInput from '~/components/Form/FormInput'
  import FormMixin from '~/components/Form/_Mixin'

  export default {
    mixins: [ComponentMixin, FormMixin],
    computed: {
      form () {
        return this.data.form
      },
      formErrors () {
        // the form is initialised in the store in the form tag which is a child component
        return this.storeForm ? this.storeForm.vars.errors : []
      },
      formValid () {
        return this.storeForm ? this.storeForm.vars.valid : false
      }
    },
    components: {
      FormTag,
      FormInput
    }
  }
</script>

<style>
  .form-container {
    max-width: 800px;
  }
</style>
