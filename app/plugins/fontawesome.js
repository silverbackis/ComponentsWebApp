import Vue from 'vue'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

// import regular from '@fortawesome/fontawesome-free-regular'
import fontawesome from '@fortawesome/fontawesome'
import faGithub from '@fortawesome/fontawesome-free-brands/faGithub'
import faCheckCircle from '@fortawesome/fontawesome-free-solid/faCheckCircle'
import faCheck from '@fortawesome/fontawesome-free-solid/faCheck'
import faExclamationTriangle from '@fortawesome/fontawesome-free-solid/faExclamationTriangle'
import faBook from '@fortawesome/fontawesome-free-solid/faBook'

fontawesome.library.add(
  faGithub, faCheckCircle, faBook, faCheck, faExclamationTriangle
)

Vue.use(FontAwesomeIcon)
Vue.component('font-awesome-icon', FontAwesomeIcon)

export default fontawesome
