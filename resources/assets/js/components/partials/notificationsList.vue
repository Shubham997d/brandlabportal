<template>
<div class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl notification-listing-pg">
  <div class="markAll-ListBtn my-4 pb-1 border-b border-gray-400">
    <h5>Notifications ({{notificationsTotal}})</h5>
    <button class="markAll" v-if="notifications.length > 0" @click="markNotification()">Mark all read</button>
  </div>
<div class="self-center relative notification-dropdown notification-list">
  <div class=" bg-white  flex flex-col mt-5 shadow-lg rounded z-50 notification-group">
    <div v-if="notifications.length > 0" class="h-128 overflow-y-auto">
      <div class="flex flex-row items-center px-4 py-2 text-gray-700 cursor-pointer border-b" href="#" v-for="(notification,index) in notifications" :key="index" :class="getNotificatonClass(notification)" >
        <div class="w-16 self-start my-2">
          <img class="w-10 h-10 rounded-full mr-2" :src="'/'+notification.data.subject.avatar">
        </div>
        <div class="notification-content">
          <div>                
                <span v-if="notification.data.notification_content_start" v-html="notification.data.notification_content_start"></span> 
                <span v-if="notification.data.notification_content_middle" v-html="notification.data.notification_content_middle" class="notification-view-details" @click="displayGroupData(index)"></span>
                <span v-if="notification.data.notification_content_end" v-html="notification.data.notification_content_end"></span>
          </div>          
          <div class="py-1 text-xs"> 
            {{ notification.date }}
          </div>
        </div>
         <div class="w-28 self-start my-2 unread-check-icon">
          <font-awesome-icon :icon="faCheck" @click="markNotification({'id': notification.id, 'index': index})" class="font-bold text-sm"></font-awesome-icon>
        </div>
      </div>
    </div>
    <div v-if="notifications.length === 0" class="px-4 py-2 text-sm text-gray-600 block">{{ 'No notifications right now. You\'re all caught up' | localize }}</div>
     <span class="block border-t"></span>      
  </div>
</div>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { faCircle,faBell, faCheck } from '@fortawesome/free-solid-svg-icons'
export default {
  name: 'notifications',
  data: () => ({
    token: Laravel.csrfToken,
    url: navUrl,
    user: authUser,
    faBell,
    faCircle,
    faCheck,
  }),

  created () {
    
  },

  mounted () {
        window.addEventListener("scroll", this.loadMore, true)
  },

  computed: {
    ...mapState({
      currentComponent: state => state.dropdown.currentComponent,
      notifications: state => state.notifications.data,
      unreadNotificationsCount: state => state.notifications.unreadNotificationsCount,
      notificationDetails: state => state.notifications.notificationDetails,
      hasClickableNotificationContent: state => state.notifications.hasClickableNotificationContent,
      areNotificationsCurrentlyLoading: state => state.notifications.isCurrentlyLoading,
      notificationsTotal: state => state.notifications.total,
    }),    
  },

  methods: {
    ...mapActions([
      'setCurrentComponent',
      'closeComponent',
      'showNotification',
      'toggleLoading',
      'getNotifications',
      'markNotification',
      'getNotificationDetails'
    ]),    
    toggleNotification (event) {
      if (this.currentComponent === 'notification-dropdown') {
        document.body.removeEventListener('keyup', this.hideNotification)
        this.hideNotification(event)
      } else {
        document.body.addEventListener('keyup', this.hideNotification)
        this.showNotifications()
      }
    },
    showNotifications (event) {
      this.fetchNotifications()
      this.setCurrentComponent('notification-dropdown')
    },
    hideNotification (event) {
      if (event.type === 'keyup' && event.key !== 'Escape') {
            return false
      }
      if (this.currentComponent === 'notification-dropdown') {
            this.closeComponent('')
      }
    },   
     
    showNotificationBox () {
      this.setCurrentComponent('notification-box')
    },  
    getNotificatonClass(array){
        if(array.read_at === null) {
            return 'unread'
        }
    },
    displayGroupData(index){
          this.getNotificationDetails({'index': index })
          this.$bvModal.show('notification-details')  // model availabe in notifcationDropdown.vue     
    },
    loadMore() {        
      if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
          if (this.areNotificationsCurrentlyLoading === false) {
                  this.getNotifications({'loadMore': true})          
          } 
      }    
    },   
  }
  
}
</script>
