<template>
<div id="profile-dropdown-container" class="flex items-center cursor-pointer">


  <div id="profile-dropdown" class="flex flex-row items-center">
        <b-dropdown id="profile-dropdown" text="Settings" class="m-md-2" no-caret>
          <template #button-content>
                <h6 class="user-name">{{user.username}}</h6>
                <img class="w-8 h-8 rounded-full" :src="generateUrl(user.avatar)+'?'+new Date().getTime()">    
          </template>
          <li> <b-dropdown-item :to="profileUrl" class="profile-item" exact>
              <span class="w-6 inline-block">
                <font-awesome-icon :icon="faUser" class="pr-1"></font-awesome-icon>
            </span> {{ 'Your Profile' | localize }}
          </b-dropdown-item> </li>
           <li> <b-dropdown-item :to="'/admin'" class="profile-item" v-if="isUserAdmin.status" exact>
              <span class="w-6 inline-block">
                <font-awesome-icon :icon="faShieldAlt"></font-awesome-icon>
              </span> {{ 'Admin' | localize }}
          </b-dropdown-item> </li>
           <li> <b-dropdown-item :to="'/logout'" class="profile-item" exact>
                <span class="w-6 inline-block">
                    <font-awesome-icon :icon="faSignOutAlt" class="pr-1 font-regular"></font-awesome-icon>
                </span> {{ 'Logout' | localize }}
          </b-dropdown-item> </li>
        </b-dropdown>
      </div>

  <form id="logout-form" :action="logoutUrl" method="POST" style="display: none;">
      <input type="hidden" name="_token" :value="token">
  </form>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import {
  faAngleDown,
  faCog,
  faShieldAlt,
  faSignOutAlt,
  faUser,
  faEnvelope,
  faUserMinus,
  faStopwatch
} from '@fortawesome/free-solid-svg-icons'

export default {
  data: () => ({
    user: user,
    token: Laravel.csrfToken,
    url: navUrl,
    avatar: '',
    logoutUrl: window.location.origin+'/data/logout',  
    profileUrl: '/profile/' + user.username,
    impersonating: impersonating,    
    faAngleDown,
    faCog,
    faShieldAlt,
    faSignOutAlt,
    faUser,
    faUserMinus,
    faEnvelope,
    faStopwatch,
  }),
  created (){
  },

  computed: {
    ...mapState({
      currentComponent: state => state.dropdown.currentComponent,
      isUserAdmin: state => state.isUserAdmin
    }),
  },

  methods: {
    ...mapActions([
      'setCurrentComponent',
      'closeComponent'
    ]),   
    toggleProfileDropdown (event) {
      if (this.currentComponent === 'profile-dropdown') {
        this.hideProfileDropdown(event)
        document.body.removeEventListener('keyup', this.hideProfileDropdown)
      } else {
        this.showProfileDropdown()
        document.body.addEventListener('keyup', this.hideProfileDropdown)
      }
    },
    showProfileDropdown (event) {
      if (this.notificationShown) {
        this.notificationShown = false
      }
      this.setCurrentComponent('profile-dropdown')
    },
    hideProfileDropdown (event) {
      if (event.type === 'keyup' && event.key !== 'Escape') {
        return false
      }
      if (this.currentComponent === 'profile-dropdown') {
        this.closeComponent('')
      }
    },
    showTimer (event) {
      this.setCurrentComponent('timer') 
    }
    
  }  
}
</script>
