<template>
<div class="users-online-status">
  <div class="flex justify-between items-center w-full relative">
        <div class="flex flex-row flex-wrap items-center">
          <span class="bg-indigo-500 shadow-xl w-8 h-8 d-flex justify-center items-center rounded-full text-indigo-500 cursor-pointer text-center p-2 mr-1 display-users" v-if="availableUsers.length > userTotalAllowedPopup" @click="displayUsers()" >
            <font-awesome-icon :icon="faPlus" class="text-white" ></font-awesome-icon>
          </span>
          <div v-for="(user,index) in availableUsers" :key="user.value" class="mx-1 a-user-status" :class="user.online == false || user.online == null || typeof user.online == 'undefined'   ? 'not-online' : ''" v-if="index < userTotalAllowedPopup">
          <router-link :to="'/user/' + user.text" v-if="availableUsers.length > 0">
              <profile-card :user="user" :userId="user.value"></profile-card>              
          </router-link>
      </div>
  </div>
  </div>
  <b-modal id="user-online-details" title="Users"> 
      <div class="flex justify-between items-center w-full relative">
        <div class="flex flex-row flex-wrap items-center pt-2">
          <div v-for="(user) in availableUsers" :key="user.value" class="mx-1 a-user-status" :class="user.online == false || user.online == null || typeof user.online == 'undefined'   ? 'not-online' : ''">
          <router-link :to="'/user/' + user.text" v-if="availableUsers.length > 0" >
              <profile-card :user="user" :userId="user.value" :showStatus="true" ></profile-card>              
          </router-link>
      </div>
  </div>
  </div>
   </b-modal> 
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import profileCard from './profileCard'
import { faCircle,faBell, faCheck, faPlus } from '@fortawesome/free-solid-svg-icons'
export default {
  components: { profileCard },
  data: () => ({    
    token: Laravel.csrfToken,
    url: navUrl,
    unreadNotification: false,
    user: authUser,
    faBell,
    faCircle,
    faCheck,
    faPlus,
    totalAllowedOutsidePopup: Constants.miscellaneous.userOnline.totalAllowedOutsidePopup
  }),

  created () {
    
  },

  mounted () {
      this.listen()
      
  },

  computed: {
    ...mapState({
      availableUsers: state => state.availableUsers,
    }),
    userTotalAllowedPopup(){
      if(screen.width > this.totalAllowedOutsidePopup.desktop.width ){
            return this.totalAllowedOutsidePopup.desktop.count
      }else if(screen.width > this.totalAllowedOutsidePopup.tablet.width && screen.width < this.totalAllowedOutsidePopup.desktop.width){
            return this.totalAllowedOutsidePopup.tablet.count
      }else{
            return this.totalAllowedOutsidePopup.mobile.count
      }          
    }  
  },

  methods: {
    ...mapActions([
      'setCurrentComponent',
      'closeComponent',
      'showNotification',
      'toggleLoading'
    ]),
    fetchNotifications(){
       
    },  
    listen() {
      Echo.join(`users-online-status`)
        .here((users) => {
            this.getAlreadyAvailableUsers(users)
        })
        .joining((user) => {
          var userIndex = this.$functions.findIndexOfMultidimensionalArray(this.availableUsers,user.id,'value')
          this.$set(this.availableUsers[userIndex], 'online', true)
        })
        .leaving((user) => {
          var userIndex = this.$functions.findIndexOfMultidimensionalArray(this.availableUsers,user.id,'value')
          this.$set(this.availableUsers[userIndex], 'online', false)
        })
        .error((error) => {
            console.error(error);
      });
    },    
    getAlreadyAvailableUsers(users){
      for (var i = 0; i < users.length; i++) {
             var userIndex = this.$functions.findIndexOfMultidimensionalArray(this.availableUsers,users[i].id,'value')
             this.$set(this.availableUsers[userIndex], 'online', true)
        } 
    },
    displayUsers(){
          this.$bvModal.show('user-online-details')      
    },
    
  }
}
</script>


