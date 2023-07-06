import './../../bootstrap'
import componentMixin from './../commonComponent'
import VueRouter from 'vue-router'
import router from '../../router/index'
Vue.use(VueRouter)

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
  router,
  mixins: [componentMixin]
})
