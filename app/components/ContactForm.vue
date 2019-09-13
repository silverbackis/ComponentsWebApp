<template>
  <div class="section is-contact">
    <div class="container">
      <h2 class="page-section-title has-text-centered title is-primary">Get In Touch</h2>
      <form-component v-bind="$props">
        <template slot="form-append">
          <div class="field">
            <div class="field form-wrapper is-button">
              <div class="control">
                <div>
                  <button
                    id="contact_submit" 
                    type="submit" 
                    name="contact[submit]" 
                    class="button is-large is-primary is-fullwidth is-rounded">
                    <span class="send-button-text">Send</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template slot="success">
          <div class="card">
            <div class="card-content">
              <h1 class="title has-text-success has-text-weight-bold">Thank you</h1>
              <p class="has-text-weight-bold">Your message has been sent and will reply to your query as soon as possible.</p>
            </div>
          </div>
        </template>
      </form-component>
    </div>
  </div>
</template>

<script>
import ComponentMixin from '~/.nuxt/bwstarter/bulma/components/componentMixin'
import FormComponent from '~/.nuxt/bwstarter/bulma/components/Form/FormInner'

export default {
  components: { FormComponent },
  mixins: [ComponentMixin]
}
</script>

<style lang="sass">
@import 'assets/sass/utilities'

.section.is-contact
  .page-section-title
    margin-bottom: 1rem
  .container
    max-width: 650px
  .send-button-text
    display: inline-block
    background: url('/icons/paper-plane-send.svg') 100% 100% no-repeat
    padding-right: 60px
    margin-right: -30px
</style>
