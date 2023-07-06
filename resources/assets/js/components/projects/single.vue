<template>
<div>
<div v-if="project" class="container mx-auto px-4 w-full md:max-w-3xl lg:max-w-4xl xl:max-w-6xl middle-content" >

    <div id="project-heading" class="border border-gray-300 bg-white rounded-lg px-8 py-4">
      <div class="text-gray-600 font-semibold text-3xl flex items-center justify-center relative">
        <div class="flex justify-between items-center w-full">
          <!-- <div class="flex flex-col">
            <div class="text-xs font-normal text-gray-700 flex">
              <div v-for="place in breadcrumb" @click="setActiveView(place)" class="mr-1 cursor-pointer" :key="place">{{ place | capitalize | localize }} > </div>
              {{project.name}}
            </div>
            {{project.name}}
          </div>           -->
        </div>
      </div>

        <div class="flex justify-between items-center w-full relative">
            <div class="flex flex-row flex-wrap items-center pt-2">
              <h1 class="project-heading-main">{{project.name}}</h1>
              <span @click="showModal('addMemberForm')" class="bg-indigo-500 shadow-xl w-8 h-8 d-flex justify-center items-center rounded-full text-indigo-500 cursor-pointer text-center p-2 mr-1 add-project" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectMemberAdd','project-frontend-btn')">
                <font-awesome-icon :icon="faPlus" class="text-white"></font-awesome-icon>
              </span>
              <div v-for="member in project.members" :key="member.user_id" class="mx-1">
              <router-link :to="'/user/' + member.username">
                  <profile-card :user="member" :duration="project.durations" :userId="member.user_id" ></profile-card>
                  <!-- <span class="members-dropdown" v-if="project.members.length > 8"><font-awesome-icon :icon="faPlus" class="text-white"></font-awesome-icon> 
                  </span> -->
              </router-link>
          </div>
        </div>


        <div class="absolute right-0" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectSettings','project-frontend-btn')">
          <b-dropdown id="settings-dropdown" size="lg" class="m-md-2" toggle-class="text-decoration-none" no-caret v-if="$route.name == 'project.tasks'">
                <template #button-content>
                <font-awesome-icon :icon="faCog" class="mr-2" ></font-awesome-icon>
            </template>
            <li><b-dropdown-item  @click="showModal('memberListModal')"> {{ 'Show All Members' | localize }}</b-dropdown-item></li>
            <li><b-dropdown-item  @click="deleteProject"> {{ 'Delete' | localize }}</b-dropdown-item></li>
          </b-dropdown>
          <span v-if="$route.name == 'project.detail'" class="ct-project-actions">
              <button @click="deleteProject" class="w-8 h-8 bg-red-200 text-red-700 rounded-full flex justify-center items-center" title="delete">
                <font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm delete-project"></font-awesome-icon>
              </button>
              <font-awesome-icon :icon="faEllipsisV" class="project-dropdown" @click="openModel('projectDetailModel')" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectUpdateModel','project-frontend-btn')"> </font-awesome-icon>
          </span>
        </div>

      </div>
    </div>

    <!-- Modals for dropdown menu -->
    
      <project-detail-model v-if="currentComponent === 'projectDetailModel' && project != null && project != undefined" :project="project" :refreshProjectDetailPage="true"></project-detail-model>
      <members-list-modal v-if="currentComponent === 'memberListModal'" resourceType="project" :resourceId="project.id" :members="project.members" @member-removed="memberDeleted" ></members-list-modal>
      <add-member-form v-if="currentComponent === 'addMemberForm'" resourceType="project" :resource="project" @addMember="addMember"></add-member-form>
      <div class="flex flex-row flex-wrap justify-center">
            <!-- For Project 
            
            children routes -->
            <router-view :key="routerKey"></router-view> 
      </div>
    
</div>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import discussionBoard from './../partials/discussionBoard.vue'
import messagesBoard from './../partials/messagesBoard.vue'
import eventBoard from './../partials/eventBoard.vue'
import fileBoard from './../partials/fileBoard.vue'
import activityBoard from './../partials/activityBoard.vue'
import addMemberForm from './../partials/addMemberForm.vue'
import membersListModal from './../partials/membersListModal.vue'
import roadmapModal from './../partials/roadmapModal.vue'
import profileCard from './../partials/profileCard.vue'
import tabMenu from './../partials/tabMenu.vue'
import projectDetailModel from './../partials/projectDetailModel.vue'
import { faPlus ,faTrashAlt , faCog ,faEllipsisV } from '@fortawesome/free-solid-svg-icons'



export default {
  name: 'project',

  components: {
    discussionBoard,
    messagesBoard,
    eventBoard,
    fileBoard,
    activityBoard,
    addMemberForm,
    membersListModal,
    roadmapModal,
    profileCard,
    tabMenu,
    projectDetailModel    
  },

  data: () => ({
    active: '',
    activeId: 0,
    dropdownMenuShown: false,    
    faPlus,
    faEllipsisV,
    faTrashAlt,
    faCog,    
    routerKey: 0,  //for router data update on event
    currentAuthUser: authUser
  }),

  created () {
      this.getProject(this.$route.params.id)
  },

  computed: {
    ...mapState({
      currentView: state => state.currentView,
      breadcrumb: state => state.breadcrumb,
      project: state => state.project.project,      
      currentComponent: state => state.dropdown.currentComponent,
    })    
  },
  methods: {
    ...mapActions([
      'setCurrentView',
      'getProject',
      'setCurrentComponent',
      'closeComponent',
      'showNotification',
      'updateBreadcrumb',
    ]),    
    showModal (modalName) {
      this.setCurrentComponent(modalName)
    },
    closeModal () {
      this.closeComponent()
    },
    forceRerender() {       
        this.routerKey += 1
    },
    getActiveTool (tool, tabs = null) {
      if (tool !== null && tabs.indexOf(tool) !== -1) {
        this.active = tool
      }
    },
    memberDeleted(){      
        this.forceRerender()
    }, 
    addMember (data) {
      if (data.user) {
          this.project.members.push(data.user)
          this.forceRerender()
          this.showNotification({type: data.status, message: data.message})
      } else {
        this.showNotification({type: data.status, message: data.message})
      }
      this.closeComponent()
    },    
    toggleDropdownMenu () {
      this.dropdownMenuShown = !this.dropdownMenuShown
    },
    closeDropdownMenu () {
      this.dropdownMenuShown = false
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
              axios.delete('data/projects/' + this.project.id)
                .then((response) => {
                  this.showNotification({type: response.data.status, message: response.data.message})
                  window.location.href = '/projects/in-progress'
                })
                .catch((error) => {
                  this.showNotification({type: error.response.data.status, message: error.response.data.message})
                })
             }
        })
    },
    openModel (name) {             
        this.setCurrentComponent(name)
    },    

      
  },mounted () {
      // EventBus.$once('showProjectDetail', this.showAllProjectDetail)
  }
}
</script>
 