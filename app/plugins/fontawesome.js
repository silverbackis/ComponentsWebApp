import Vue from 'vue'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

import fontawesome from '@fortawesome/fontawesome'
// import regular from '@fortawesome/fontawesome-free-regular'
import faGithub from '@fortawesome/fontawesome-free-brands/faGithub'
import faCheckCircle from '@fortawesome/fontawesome-free-solid/faCheckCircle'

fontawesome.library.add(
  faGithub, faCheckCircle
)

Vue.use(FontAwesomeIcon)
Vue.component('font-awesome-icon', FontAwesomeIcon)

export default fontawesome
