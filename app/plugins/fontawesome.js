import Vue from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
// import { faGithub } from '@fortawesome/free-brands-svg-icons/faGithub'
import { faCheck, faExclamationTriangle, faSave, faEdit, faSync } from '@fortawesome/free-solid-svg-icons'

library.add(faCheck, faExclamationTriangle, faSave, faEdit, faSync)

Vue.component('font-awesome-icon', FontAwesomeIcon)
