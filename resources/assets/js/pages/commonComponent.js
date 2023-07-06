import navbar from './../components/partials/navbar.vue'
import notificationPopup from './../components/partials/notificationPopup.vue'
import messageBox from './../components/partials/messageBox.vue'
import chatListener from './../components/chat/coversation/chatListener.vue'
import notificationBox from './../components/partials/notificationBox.vue'
import loadingModal from './../components/partials/loadingModal.vue'
import timer from './../components/partials/timer.vue'

/* eslint-disable no-unused-vars */
var componentMixin = {
  components: {
    navbar, notificationPopup, notificationBox, messageBox, chatListener, loadingModal, timer
  }
}

export default componentMixin
