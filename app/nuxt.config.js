const pkg = require('./package')

export default {
  mode: 'universal',
  /**
   * Headers of the page
   */
  head: {
    titleTemplate: '%s - Components Web App',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description }
    ],
    link: [
      {
        rel: 'icon',
        type: 'image/png',
        href: 'icons/icon.png'
      }
    ]
  },
  /**
   * Customize the progress-bar color
   */
  loading: {
    color: 'hsl(141, 71%,  48%)',
    failedColor: 'hsl(348, 100%, 61%)',
    height: '4px'
  },
  /**
   * Global CSS
   */
  css: ['~/assets/css/main.sass'],
  /**
   * Plugins
   */
  plugins: [{ src: '~/plugins/fontawesome', ssr: true }],
  /**
   * Modules
   */
  modules: [
    '@cwamodules/core',
    '@cwamodules/components',
    [
      '@cwamodules/bulma',
      {
        pagesDepth: 3,
        components: {}
      }
    ],
    '@nuxtjs/component-cache',
    [
      '@nuxtjs/axios',
      {
        credentials: true,
        debug: false
      }
    ],
    // [
    //   '@nuxtjs/google-tag-manager',
    //   {
    //     id: 'GTM-XXXXXXX',
    //     pageTracking: true
    //   }
    // ],
    [
      '@nuxtjs/pwa',
      {
        icon: {
          iconSrc: '~/static/icons/icon.png'
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
    ]
  ],
  /**
   * Manifest for mobile app
   */
  manifest: {
    name: 'Components Web App',
    short_name: 'CWA',
    description: 'Progressive Web App from Silverback Web Apps',
    lang: 'en',
    background_color: '#FFFFFF',
    theme_color: '#000000'
  },
  /**
   * Build configuration
   */
  build: {
    // analyze: true,
    postcss: {
      preset: {
        features: {
          customProperties: false
        }
      }
    },
    /*
    ** Run ESLINT on save
    */
    extend(config, { isDev }) {
      // cwamodules should pre-compile but this will work for now
      config.resolve.alias['vue'] = 'vue/dist/vue.common'
      config.resolve.extensions.push('.sass')

      for (let plugin of config.plugins) {
        if (plugin.constructor.name === 'HtmlWebpackPlugin') {
          plugin.options = Object.assign(plugin.options, {
            chunksSortMode: 'none'
          })
        }
      }

      // Run ESLint on save
      if (isDev && process.client) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  },
  /**
   * Router
   */
  router: {
    middleware: ['initErrorHandler']
  },
  serverMiddleware: [
    // API middleware
    '~/server/index.js'
  ]
}
