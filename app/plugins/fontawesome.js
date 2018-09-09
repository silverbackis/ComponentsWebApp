import Vue from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faGithub } from '@fortawesome/free-brands-svg-icons/faGithub'
import { faBook, faExclamationTriangle, faCheck, faCheckCircle, faChevronUp, faChevronDown, faArrowsAlt, faUpload } from '@fortawesome/free-solid-svg-icons'

library.add(faGithub, faCheckCircle, faBook, faCheck, faExclamationTriangle, faChevronUp, faChevronDown, faArrowsAlt, faUpload)

Vue.component('font-awesome-icon', FontAwesomeIcon)
