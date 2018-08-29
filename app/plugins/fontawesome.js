import Vue from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faGithub } from '@fortawesome/free-brands-svg-icons/faGithub'
import { faBook, faBars, faExclamationTriangle, faCheck, faCheckCircle, faChevronUp, faChevronDown } from '@fortawesome/free-solid-svg-icons'

library.add(faGithub, faCheckCircle, faBook, faCheck, faExclamationTriangle, faBars, faChevronUp, faChevronDown)

Vue.component('font-awesome-icon', FontAwesomeIcon)
