<template>
<div class="w-full">
  <!-- <invite-modal></invite-modal> -->

  <div class="md:rounded text-gray-900">
    <div class="userboard-main-header">
        <h3 class="userboard-heading">{{ 'All Users' | localize }}</h3>  
        <span @click="openInvitePopup" class="bg-indigo-500 shadow-xl w-8 h-8 d-flex justify-center items-center rounded-full text-indigo-500 cursor-pointer text-center p-2 mr-1 add-project invite-user">
             <font-awesome-icon :icon="faPlus" class="text-white"></font-awesome-icon>
      </span>
    </div>

    <div class="all-users-detail">
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>User</th>
              <th>Colour</th>
              <th>Total Work Duration</th>
              <th>Salary (PH)</th>
              <th>Job Title</th>
              <th>User Role</th>
              <th>View Projects</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>    
            <tr v-for="user in usersList" :key="user.id">
              <td>
                <router-link :to="'/user/' + user.username" class="">
                   <profile-card :user="user" :duration="user.userTotalDuration" :isRelative="'relative'"></profile-card>
                </router-link>                
                <p class="text-center admin-user-p">{{ user.username }}</p>
              </td>
              <td><div class="colour-picker-grp-item">
                   <input v-model='user.colour' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" type="text" :placeholder="$options.filters.localize('Colour')" lazy @change="updateProfile($event,user)">
                   <span class="input-group-addon color-picker-container">

                    <span class="current-color" :style="'background-color: '+ user.colour"></span>
                  </span>
              </div></td>
              <td>{{ $functions.timeFormat (user.userTotalDuration) }}</td>
              <td><input v-model="user.salary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" type="number" step="0.01" :placeholder="$options.filters.localize('Salary')" lazy @change="updateProfile($event,user)">
              </td>  
               <td><input v-model="user.designation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" type="text" :placeholder="$options.filters.localize('Job title')" lazy @change="updateProfile($event,user)">
              </td>   
              <td>
                <select lazy @change="updateProfile($event,user)" v-model="user.role_id" id="userRole">
                  <option :value="role.id"  v-for="role in roles" :key="role.id" class="my-2 text-lg">{{ role.name }}</option>            
                </select> 
              </td>
              <!-- <td> <p class="text-center">{{ user.user_role.name }}</p></td>              -->
              <td><font-awesome-icon :icon="faEye" class="cursor-pointer view-user-projects" @click="showModal('userProjectList',user.user_project_duration)"></font-awesome-icon></td>                
              <td><toggle-button :width="90" :font-size="12" :height="30" @change="updateProfile($event,user)" :value="user.active == 1  ? true : false" :labels="{checked: 'Active', unchecked: 'Inactive'}"/></td>    
              <td><font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm delete-user" @click="showModal('tranferDataModal',user)"></font-awesome-icon></td>

           </tr>
          </tbody>
        </table>
      </div>
        <user-project-list v-if="currentComponent === 'userProjectList'" :projects="currentUserProjects[0]" ></user-project-list>        
        <tranfer-data-modal v-if="currentComponent === 'tranferDataModal'" :user="currentUser"></tranfer-data-modal>
        <invite-modal></invite-modal>
        
    </div>
  </div>  
  
</div>


</template>

<script>
import { mapState, mapActions } from 'vuex'
// import inviteModal from './../partials/inviteModal.vue'
import userProjectList from './userProjectList.vue'
import profileCard from './../partials/profileCard.vue'
import tranferDataModal from '../partials/tranferDataModal.vue'
import inviteModal from '../partials/inviteModal.vue'
import { 
  faUserSecret,
  faUserSlash,
  faEye,
  faTrashAlt,
  faPlus
} from '@fortawesome/free-solid-svg-icons'
import { ToggleButton } from 'vue-js-toggle-button'
export default {
  components: {
      // inviteModal, 
      profileCard,
      userProjectList,
      ToggleButton,
      tranferDataModal,
      inviteModal 
  },
  props: {  
    users: {
      required: true,
      type: Array
    }
  },
  data: () => ({
    faUserSecret,
    faTrashAlt,
    faUserSlash,
    faEye,
    faPlus,
    modelSet: false,
    currentUserProjects : [],
    currentUser : {}    
  }),
  computed: {
     ...mapState({
      currentComponent: state => state.dropdown.currentComponent,
      roles: state => state.roles,
    }),    
    usersList: function () {
        return this.users
    },   
  },

  created (){
        this.getRoles() // Get Roles
  },
  methods: {
    ...mapActions([
      'showNotification',
      'setCurrentComponent',
      'toggleLoading',
      'getUsers',
      'getRoles',
      'getAvailableUsers'
    ]),

    userBooleanValue(val){      
      if (val == 1) {
        return true
      }else{
        return false
      }
    },

    updateProfile (event,user) {
        //active - inactive user
        var userStatus = user.active
        if (event.value != undefined) { userStatus = event.value }
        this.toggleLoading(true)
        axios.post('data/users/profile/update/'+ user.username, {                  
          username: user.username,
          colour: user.colour,
          salary: user.salary,
          active: userStatus,
          designation: user.designation,
          role_id: user.role_id
        })
          .then((response) => {            
            if (response.data.status === 'success') {
              this.getAvailableUsers()
              this.showNotification({type: response.data.status, message: response.data.message})
            }
            if (response.data.status === 'error') {              
              this.showNotification({type: response.data.status, message: response.data.message})
            }
            this.toggleLoading(false)
          })
          .catch((error) => {   
            this.toggleLoading(false)         
            this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
      },     
    showModal (modalName,data = null) {
        if (modalName == 'userProjectList') {
            var data = JSON.parse(JSON.stringify(data))
            this.currentUserProjects.length = 0                
            this.currentUserProjects.push(data)        
         }
         if (modalName == 'tranferDataModal') {
            this.currentUser = data 
         }
        this.setCurrentComponent(modalName) 
        
    },      
    closeModal () {
        this.closeComponent()
     },
    openInvitePopup (){
       this.$bvModal.show('invite-user-modal')
     }   

  },
  
    
  
}
</script>
