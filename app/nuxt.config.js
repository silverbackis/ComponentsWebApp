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
    // analyze: true,
    vendor: ['axios', 'lodash'],
    maxChunkSize: 300000,
    /*
    ** Run ESLINT on save
    */
    extend (config, ctx) {
      config.resolve.alias['vue'] = 'vue/dist/vue.common'

      if (ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /node_modules/,
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
    // Without ssr we get a warning ssr and browser rendering do not match as of 19 Jan 18
    { src: '~/plugins/fontawesome', ssr: true }
  ],
  /**
   * Modules
   */
  modules: [
    '@cwamodules/core',
    '@cwamodules/components',
    '@cwamodules/bulma',
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
        // Causes issues in safari when requesting pages when authentication changes (annonymous/authenticated user)
        // workbox: {
        //   runtimeCaching: [
        //     {
        //       urlPattern: process.env.API_URL_BROWSER + '/.*',
        //       handler: 'networkFirst',
        //       method: 'GET'
        //     }
        //   ]
        // },
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
    [
      '@nuxtjs/google-tag-manager',
      {
        id: 'GTM-MVSWS73'
      }
    ]
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
    middleware: ['initErrorHandler'],
    extendRoutes (routes, resolve) {
      routes.push({
        path: "/news-blog/:page1?",
        component: resolve('~/.nuxt/bwstarter/bulma/pages/_base'),
        name: "news-blog-page1"
      })
    }
  },
  loading: {
    color: '#23d160'
  },
  bwstarter: {
    pagesDepth: 3
  }
}
