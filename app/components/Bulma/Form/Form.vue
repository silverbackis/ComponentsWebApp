<template>
  <div class="columns is-centered form-columns">
    <div class="column is-8-tablet">
      <div class="card">
        <div class="card-content">
          <form-tag :form="form">
            <slot>
              <form-input v-for="input in form.children"
                          :key="input.vars.unique_block_prefix"
                          :input="input"
                          :formId="formId"
                          :wrapped="true"
              />
              <div class="field">
                <div class="control">
                  <button type="submit" id="contact_submit" name="contact[submit]" class="button is-large is-primary is-fullwidth">
                    Send
                  </button>
                </div>
              </div>
            </slot>
          </form-tag>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { getFormId } from '~/components/Form/_FormId'
  import FormTag from '~/components/Form/Form'
  import FormInput from '~/components/Form/FormInput'

  export default {
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    computed: {
      form () {
        return this.data.form
      },
      formId () {
        return getFormId(this.form.vars)
      }
    },
    components: {
      FormTag,
      FormInput
    }
  }
</script>
