import Echo from 'laravel-echo'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ClickOutside from 'vue-click-outside'
import * as linkify from 'linkifyjs'
import linkifyElement from 'linkifyjs/element'
import mention from 'linkifyjs/plugins/mention'
import Swal from 'sweetalert2'
import Constants from './constants/constants.js'
import functions from './common/functions.js'
import Vuelidate from 'vuelidate'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../css/style.css'
import '../css/chat/style.css'
import underscore from 'vue-underscore';
import VueConfirmDialog from 'vue-confirm-dialog'
import Multiselect from 'vue-multiselect'


window.pusher = require('pusher-js')

const $ = require('jquery')

var AddToCalendar = require('vue-add-to-calendar');

window.$ = $

window.Swal = Swal

window.Constants = Constants

mention(linkify)

window.Vue = require('vue')

window.Vue.prototype.$functions = functions

window.axios = require('axios')

window.luxon = require('luxon')

Vue.use(AddToCalendar);
Vue.use(Vuelidate)
Vue.use(underscore);

// Make BootstrapVue available throughout the project
Vue.use(BootstrapVue)
// Installed the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(VueConfirmDialog)

Vue.component('vue-confirm-dialog', VueConfirmDialog.default)

// register globally
Vue.component('multiselect', Multiselect)

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

window.Toast = Toast

window.axios.defaults.headers.common = {
  'X-CSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest'
}
// To set every request base/root path
window.axios.defaults.baseURL = window.location.origin;

// Handle 401 Request Globally For Inactive Users & users not have enough access


axios.interceptors.response.use(
  response => response,
  error => {
    let statusCode = error.response.status
    if (statusCode == 401) { //Redirect To Login Page if user not logged-in or was deleted
      Swal.fire({
        icon: Constants.unauhorizeSwalPopup.icon,
        title: Constants.unauhorizeSwalPopup.title,
        text: Constants.unauhorizeSwalPopup.text        
      }).then((result) => {            
            window.location.href = window.location.origin
      })
    }
   
    if (statusCode == 419) { //Refresh current page when token expires
      Swal.fire({
        icon: Constants.tokeExipireSwalPopup.icon,
        title: Constants.tokeExipireSwalPopup.title,
        text: Constants.tokeExipireSwalPopup.text        
      }).then((result) => {            
            window.location.reload()
      })
    }
    
    return Promise.reject(error)
  });

// setup for socket 

// Pusher.logToConsole = true;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  wsHost: window.location.hostname,
  wsPort: process.env.MIX_LARAVEL_WEBSOCKETS_PORT,
  wssPort: process.env.MIX_LARAVEL_WEBSOCKETS_PORT,
  enabledTransports: ['ws','wss'],
  forceTLS: process.env.MIX_PUSHER_APP_FORCE_TLS == 'true' ? true : false,
  namespace: 'App.Base.Events',
  encrypted: process.env.MIX_LARAVEL_ENCRYPTED == 'true' ? true : false,
  authEndpoint: '/broadcasting/auth'
})


window.Vue.mixin({
  methods: {
    generateUrl: function (value) {
      if (!value) return 'http://' + window.location.host + '/image/avatar.jpg'
      value = value.toString()
      return window.location.protocol + '//' + window.location.host + '/' + value
    },
    updateUrl: function (params) {
      const url = new URL(window.location.href)
      for (const key in params) {
        if (url.searchParams.has(key)) {
          url.searchParams.delete(key)
        }
        if (params[key] !== null) {
          url.searchParams.append(key, params[key])
        }
      }
      window.history.pushState({ path: url.href }, '', url.href)
    }
  }
})

window.Vue.filter('localize', function (value) {
  if (!value) return ''
  value = value.toString()
  return window.lang[value] ? window.lang[value] : value
})

window.Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
})

window.Vue.filter('clip', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.substr(0, 20) + '...'
})

/* disable scroll on number input field */
document.addEventListener("wheel", function(event){
  if(document.activeElement.type === "number"){
      document.activeElement.blur();
  }
});
/* disable scroll on number input field*/

window.Vue.directive('linkify', {
  inserted: function (el) {
    linkifyElement(el, {
      className: 'text-blue-500',
      formatHref: function (href, type) {
        if (type === 'mention') {
          return window.location.origin + '/users' + href
        }
        return href
      }
    })
  }
})

window.Vue.directive('click-outside', ClickOutside)

window.Vue.component('font-awesome-icon', FontAwesomeIcon)

window.EventBus = new Vue()
