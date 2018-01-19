module.exports = {
  performance: {
    gzip: true
  },
  /**
   * Headers of the page
   */
  head: {
    titleTemplate: '%s - Starter Website',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' }
    ]
  },
  /**
   * Global CSS
   */
  css: ['~/assets/css/main.sass'],
  /**
   * Add axios globally
   */
  build: {
    vendor: ['axios', 'lodash'],
    /*
    ** Run ESLINT on save
    */
    extend (config, ctx) {
      if (ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/,
          options : {
            fix : true
          }
        })
      }
    }
  },
  /**
   * Cache settings
   */
  cache: true,
  /**
   * Plugins
   */
  plugins: [
    { src: '~/plugins/quill', ssr: false },
    // Without ssr we get a warning ssr and browser rendering do not match as of 19 Jan 18
    { src: '~/plugins/fontawesome', ssr: true }
  ],
  /**
   * Modules
   */
  modules: [
    '@nuxtjs/component-cache',
    [
      '@nuxtjs/pwa',
      {
        icon: {
          iconSrc: 'static/icons/bw-logo-1024x1024.png',
          sizes: [1024, 512, 144]
        },
        manifest: true,
        meta: false,
        workbox: true,
        optimize: {
          cssnano: {
            zindex: false
          }
        }
      }
    ],
    [
      '@nuxtjs/axios',
      {
        credentials: true,
        debug: false
      }
    ],
    ['@nuxtjs/google-tag-manager', { id: 'GTM-MVSWS73' }],
  ],
  /**
   * Manifest for mobile app
   */
  manifest: {
    name: 'BW Starter Website',
    short_name: 'BWStarter',
    description: 'A starter de-coupled front-end/back-end website with common functionality including an admin login',
    lang: 'en',
    background_color: '#FFFFFF',
    theme_color: '#4770fb'
  },
  /**
   * Router
   */
  router: {
    middleware: ['initErrorHandler', 'page']
  }
}
