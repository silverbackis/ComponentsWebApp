<template>
  <section class="container">
    <div class="content error-content">
      <div class="columns is-centered">
        <div class="column is-narrow">
          <div class="level">
            <div class="level-left">
              <h1 class="error-code">{{ error.statusCode }}</h1>
            </div>
            <div class="level-right is-marginless">
              <h2 class="error-message" v-if="error.statusCode === 404"><span v-html="error.message"></span></h2>
              <h2 class="error-message" v-else><span v-html="error.message"></span><b></b></h2>
            </div>
          </div>
        </div>
      </div>
      <p v-if="error.statusCode === 404">
        <nuxt-link class="error-link" to="/">Back to the home page</nuxt-link>
      </p>
    </div>
  </section>
</template>
<script>
export default {
  name: 'nuxt-error',
  props: ['error']
}
</script>

<style lang="sass">
  @import ~assets/css/vars

  .error-content
    padding-top: 3rem;
    text-align: center;

    .error-code
      color: $secondary
      display: inline-block
      font-size: 1.6rem
      font-weight: 500
      vertical-align: top
      margin: 0 0 .7rem
      +tablet
        border-right: 1px solid $grey-light
        margin: 0 20px 0 0
        padding: 10px 23px

    .error-wrapper-message
      display: inline-block
      text-align: left
      line-height: 49px
      height: 49px
      vertical-align: middle

    .error-message
      font-size: 1rem
      font-weight: normal
      margin: 0
      padding: 0
      text-align: center
      b
        color: $red

    .error-link
      color: $primary
      font-weight: normal
      text-decoration: none
      font-size: 1.4rem
      &:hover
        color: $secondary

</style>
