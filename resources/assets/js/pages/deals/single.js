import './../../bootstrap'
import componentMixin from './../commonComponent'
import single from './../../components/deals/single.vue'
import store from './../../store/deal'

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
  mixins: [componentMixin],
  components: {
    single
  },
  store
})
