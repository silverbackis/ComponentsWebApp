import fs from 'fs'
import path from 'path'
import pkg from './package'

const API_URL_BROWSER = process.env.API_URL_BROWSER || 'https://localhost:8443'
const API_URL = process.env.API_URL || API_URL_BROWSER
const CERT_DIR = process.env.CERT_DIR || '/certs'

const https =
  process.env.NODE_ENV === 'production' && process.env.LOCAL_TLS !== '1'
    ? {}
    : {
      key: fs.readFileSync(path.resolve(CERT_DIR + '/localhost.key')),
      cert: fs.readFileSync(path.resolve(CERT_DIR + '/localhost.crt'))
    }

export default {
  mode: 'universal',
  server: {
    host: '0.0.0.0',
    https
  },
  publicRuntimeConfig: {
    API_URL,
    API_URL_BROWSER,
  },
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
        href: 'icons/logo.png'
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
  css: ['~/assets/sass/base.sass'],
  /**
   * Plugins
   */
  plugins: [
    { src: '~/plugins/fontawesome', ssr: true },
    { src: '~/plugins/axios', mode: 'server' }
    ],
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
        components: {
          ContactForm: '~/components/ContactForm'
        }
      }
    ],
    '@nuxtjs/style-resources',
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
    short_name: 'Silverback CWA',
    description: 'Demo PWA from Silverback Web Apps',
    lang: 'en',
    background_color: '#000000',
    theme_color: '#48a2a2'
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
  ],
  styleResources: {
    sass: ['~/assets/sass/vars/*.sass']
  }
}
