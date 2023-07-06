<template>
<div class="self-center relative notification-dropdown" v-click-outside="hideNotification" :class="unreadNotificationsCount == 0 ? 'has-no-unread-notifications' : ''">
  <div id="notification" class="text-indigo-500  text-base no-underline cursor-pointer" @click="toggleNotification">
    <font-awesome-icon :icon="faBell" class="font-bold text-xl"></font-awesome-icon>
    <div class="notification-nbr-count" v-if="unreadNotificationsCount > 0">
    <span v-if="unreadNotificationsCount > 100">99+</span>
    <span v-else>{{ unreadNotificationsCount }}</span>    
    <!-- <font-awesome-icon v-if="unreadNotification" :icon="faCircle" class="text-red-500 text-xs absolute -mt-1 -ml-1" aria-hidden="true"></font-awesome-icon> -->
    </div>
  </div>
  <div v-if="currentComponent === 'notification-dropdown'" class="absolute bg-white w-72 flex flex-col mt-5 -mr-16 right-0 shadow-lg rounded z-50 notification-group">
    <div v-if="notifications.length > 0" class="h-128 overflow-y-auto">
      <div class="flex flex-row items-center px-4 py-2 text-gray-700 cursor-pointer border-b" href="#" v-for="(notification,index) in notifications" :key="index" :class="getNotificatonClass(notification)" >
        <div class="w-16 self-start my-2">
          <img class="w-10 h-10 rounded-full mr-2" :src="'/'+notification.data.subject.avatar">
        </div>
        <div class="w-56">
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
    <div class="notfications-button-group">
     <button class="mark-all-read btn main-button btn-secondary" @click="markNotification()">Mark all as read </button> 
     <router-link :to="'/notifications'" class="btn main-button btn-secondary">
          {{ 'View All' | localize }}
     </router-link>
     </div>
  </div>
  <b-modal id="notification-details" title="Notification Details"> 
    <p class="my-4" v-for="(notification,i) in notificationDetails" :key="i">
       <a v-if="Object.keys(notificationDetails).length > 0 && hasClickableNotificationContent == true " target="_blank" :href="'/deal/'+notification.deal_id"> {{ notification.title }} </a>
       <a v-if="Object.keys(notificationDetails).length > 0 && hasClickableNotificationContent == false " href="javascript:void(0);"> {{ notification.title }} </a>
    </p>
   </b-modal>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { faCircle,faBell, faCheck } from '@fortawesome/free-solid-svg-icons'
export default {
  data: () => ({
    token: Laravel.csrfToken,
    url: navUrl,
    user: authUser,
    faBell,
    faCircle,
    faCheck,    
  }),

  created () {
      this.getNotifications({'loadMore': false})
  },

  mounted () {
      this.listen()
  },

  computed: {
    ...mapState({
        currentComponent: state => state.dropdown.currentComponent,
        notifications: state => state.notifications.data,
        unreadNotificationsCount: state => state.notifications.unreadNotificationsCount,
        notificationDetails: state => state.notifications.notificationDetails,
        hasClickableNotificationContent: state => state.notifications.hasClickableNotificationContent,
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
      this.getNotifications({'loadMore': false})
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
    listen() {
      Echo.private('User.' + this.user.id)
      .notification((notification) => {          
          this.notifications.unshift(notification)          
          this.$store.state.notifications.unreadNotificationsCount = this.unreadNotificationsCount + 1          
        })
    },       
    getNotificatonClass(array){
        if(array.read_at === null) {
            return 'unread'
        }
    },
    displayGroupData(index){
          console.log('index',index)
          this.getNotificationDetails({'index': index })
          this.$bvModal.show('notification-details')      
    },   
  }
}
</script>
