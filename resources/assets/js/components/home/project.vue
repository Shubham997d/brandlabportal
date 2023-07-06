<template>
<div class="relative bg-white shadow-lg w-full sm:w-64 md:w-80 h-48 sm:mx-4 md:mx-12 lg:mx-4 flex flex-col justify-center items-center rounded-lg m-3 xl:mx-10 md:my-8 project-wrap">
  <div class="w-full p-4 h-32 flex flex-col items-start">    
    <div class="project-settings">
      <router-link :to="getRouterLink()+'/project/'+project.id+'/detail'" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectDetails','project-frontend-btn')">
          <font-awesome-icon :icon="faEye" class="cursor-pointer view-project"></font-awesome-icon>      
      </router-link>      
       <button @click="joinProject(project.id)" class="cursor-pointer view-project" id="join-project" title="join-project" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewJoinProject','project-frontend-btn')">
        <font-awesome-icon :icon="faUserPlus" class="cursor-pointer text-sm delete-project"></font-awesome-icon>
      </button>     
      <button @click="deleteProject" class="w-8 h-8 bg-red-200 text-red-700 rounded-full flex justify-center items-center cs-delete-project" title="delete" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'deleteProject','project-frontend-btn')">
        <font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm delete-project"></font-awesome-icon>
      </button>
      <font-awesome-icon :icon="faEllipsisV" class="project-dropdown" @click="emitProjectData(true)" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectUpdateModel','project-frontend-btn')"> </font-awesome-icon>
    </div>
    <router-link :to="getRouterLink()+'/project/'+project.id+'/tasks'" class="text-pink-500 text-2xl no-underline cursor-pointer">{{ project.name }}</router-link>
    
    <div class="content-info">
      <h6>{{ 'Category' | localize }}<span class="text-gray-500 text-sm self-start">{{ textFormatter('projectCategory',projectCategoryName) }}</span></h6>
      <h6>{{ 'Project Manager' | localize }}<span class="text-gray-500 text-sm self-start">{{ textFormatter('projectManager',project.project_manager_username) }}</span></h6>      
      <h6>{{ 'Deadline' | localize }}<span class="text-gray-500 text-sm self-start">{{ textFormatter('deadline',project.deadline)  }}</span></h6> 
    </div>
  </div>
  <div class="border-t w-full h-16 flex flex-row justify-start items-center px-4 profile-icons">
    <div class="flex flex-row flex-row">
      <div v-for="member in project.members.slice(0,8)" :key="member.id" class="-ml-2" >
        <router-link :to="'/user/' + member.username">
            <profile-card :user="member" :duration="project.durations" :userId= "member.id"></profile-card>
            <!-- <span class="members-dropdown" v-if="project.members.length > 8"><font-awesome-icon :icon="faPlus" class="text-white"></font-awesome-icon> 
            </span> -->
        </router-link>
      </div>
  </div>
 <!--    <span v-if="project.members.length > 8" class="w-10 h-10 bg-indigo-100 text-indigo-700 border-white border font-semibold p-2 -ml-2 rounded-full">{{ project.members.length - 8 }}+</span> -->
 
   
  </div>
</div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import { faEllipsisH, faEllipsisV, faTrashAlt, faPlus, faEye, faUserPlus } from '@fortawesome/free-solid-svg-icons'
import profileCard from './../partials/profileCard.vue'
import moment from 'moment'

export default {
  components: {profileCard},
  props: ['details', 'index'],

  data () {
    return {
      project: this.details,
      dropdownMenuShown: false,
      homePage: true,
      user,
      auth_admin_default_role_id: Constants.adminDetails.role,
      faEllipsisH,
      faEllipsisV,
      faTrashAlt,
      faPlus,
      faEye,
      faUserPlus,
      currentAuthUser: authUser
    }
  },
  computed: {
     ...mapState({
      currentComponent: state => state.dropdown.currentComponent
    }),
    projectCategoryName(){ // assumed that their will be only one category per project
        try {
          return this.project.project_categories[0].category_details[0].name ? this.project.project_categories[0].category_details[0].name : ''
        }
        catch(err) {
           return 'N/A'
        }
    }    
    
  },

  methods: {
    ...mapActions([
      'getProject',
      'setGroup',
      'updateBreadcrumb',
      'setCurrentComponent',
      'removeProject',
      'toggleLoading',
      'setCurrentView',
      'showNotification'
    ]),
    showProject (id) {
      this.updateUrl({'group_type': 'project', 'group_id': id,"project_status": null,'tool': 'tasks'})
      this.updateBreadcrumb('projects')
      this.getProject(id)
      this.setGroup({type: 'project', id: id})
    },
    showProjectDetail (id) {
      this.updateUrl({'group_type': 'project', 'group_id': id,"project_status": null,'tool': 'project_detail'})
      this.updateBreadcrumb('projects')      
      this.getProject(id)
      this.setGroup({type: 'project', id: id})
      // EventBus.$emit('showProjectDetail',id)
    },
    toggleMenu () {
      this.dropdownMenuShown = !this.dropdownMenuShown
    },
    hideMenu () {
      this.dropdownMenuShown = false
    },
    textFormatter(type,value){ // Set default values if null
      if(typeof value == undefined || value == null )  {
        if(type == 'deadline'){
          return 'Deadline Not Set'
        }
        if(type == 'projectManager'){
          return 'N/A'
        }
        if(type == 'projectCategory'){
          return 'N/A'
        }
      }else{
          return value
      }
    },
    deleteProject () {
      Swal.fire({
            title: Constants.swalPopup.title,
            text: Constants.swalPopup.text,
            icon: Constants.swalPopup.icon,
            showCancelButton: Constants.swalPopup.showCancelButton,
            confirmButtonColor: Constants.swalPopup.confirmButtonColor,
            cancelButtonColor: Constants.swalPopup.cancelButtonColor,
            confirmButtonText: Constants.swalPopup.confirmButtonText
          }).then((result) => {
            if (result.isConfirmed) {
            this.toggleLoading(true)
            this.removeProject({
              id: this.project.id,
              index: this.index
            })
            this.toggleLoading(false)
          }
        })
    },  
    emitProjectData (openModel) {      
      if (openModel) {        
              /* check if user has permission to update the project */
              axios.post('data/permissions/project/update/'+this.project.id, {
            }).then((response) => {              
                      this.$emit('open-project-model', this.project)
              }).catch((error) => {
                this.showNotification({type: error.response.data.status, message: error.response.data.message})
            })
        
      }
    }, 
    customFormatter(date) {
      var string = moment(date).format(Constants.customDateFormat.formatWithYear);
      if (string == 'Invalid date') {
          string = 'Deadline date not set'
      }
      return string
    },
    authCheck () {
      return this.auth_admin_default_role_id === authUser.role_id ? true : false         
    },
    getRouterLink(){ // change links based on admin slug
        if (this.$route.meta.root(this.$route) === 'admin') {
              return '/admin';
        }else{
          return '';
        }      
    },
    joinProject(projectId){
      Swal.fire({
            title: Constants.joinProjectSwalPopup.title,
            text: Constants.joinProjectSwalPopup.text,
            icon: Constants.joinProjectSwalPopup.icon,
            showCancelButton: Constants.joinProjectSwalPopup.showCancelButton,
            confirmButtonColor: Constants.joinProjectSwalPopup.confirmButtonColor,
            cancelButtonColor: Constants.joinProjectSwalPopup.cancelButtonColor,
            confirmButtonText: Constants.joinProjectSwalPopup.confirmButtonText
          }).then((result) => {
            if (result.isConfirmed) {
             $('#join-project').prop('disabled', true);
             this.toggleLoading(true)
              axios.post('data/request/'+Constants.userRequests.resource[0]+'/'+Constants.userRequests.request[Constants.userRequests.resource[0]][0], {
                resource_id: projectId,
                token: Laravel.csrfToken
              })
                .then((response) => {  
                  $('#join-project').prop('disabled', false);
                  this.showNotification({type: response.data.status, message: response.data.message})
                  this.toggleLoading(false)
                })
                .catch((error) => {
                  $('#join-project').prop('disabled', false);
                  this.showNotification({type: error.response.data.status, message: error.response.data.message})
                  this.toggleLoading(false)
              })
            
          }
        })

    },    
  
  },
  mounted () {
  }
}
</script>
