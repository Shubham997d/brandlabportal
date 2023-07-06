import './../bootstrap'
import componentMixin from './commonComponent'
import VueRouter from 'vue-router'
import router from '../router/index'
import home from './../components/home/index.vue'
import store from '../store/home'
import Vuetify from 'vuetify'
Vue.use(Vuetify)
Vue.use(VueRouter)
/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
  router,
  mixins: [componentMixin],
  components: {
    home
  },
  vuetify: new Vuetify(),
  store
})
