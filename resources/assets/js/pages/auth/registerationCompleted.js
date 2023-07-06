import './../../bootstrap'
import registerationCompleted from './../../components/auth/registerationCompleted.vue'
import userUpdated from './../../components/auth/userUpdated.vue'
import userMessage from './../../components/partials/userMessage.vue'

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
  components: {
    registerationCompleted,
    userUpdated,
    userMessage     
  }
})
