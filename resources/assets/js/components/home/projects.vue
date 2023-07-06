<template>
<div id="content-container" class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl">

  <div v-if="$route.params.status === 'in-progress'" class="pb-4">
    <button @click="toggleModel"  v-if="isProjectButtonAvailable"  class="no-underline p-4 flex flex-col items-center text-center text-lg text-indigo-700 rounded border-2 border-gray-500 border-dashed">
      <font-awesome-icon :icon="faPlus" class="text-sm"></font-awesome-icon>
      {{ getProjectButtonTypeText }}
    </button>
  </div>
  <div class="my-4 pb-1 border-b border-gray-400">
    {{ 'All Projects' | localize }} ({{ projectsTotal }})
  </div>
  <div id="projects-list" class="sm:-mx-4 md:-mx-12 lg:-mx-4 xl:-mx-10 flex flex-row flex-wrap justify-start">
    <project v-for="(project, index) in projects" :key="project.id" :index="index" :details="project" @open-project-model="openProjectModel"></project>
  </div>
  <div class="cs-models">
  <Transition name="fade">
    <project-detail-model v-if="currentComponent === 'projectDetailModel' && currentProject != null && currentProject != undefined" :project="currentProject"></project-detail-model>   
    <create-project-model v-if="createProjectModalOpen === true" @closeProjectModalOpen = "closeProjectModalOpen" ></create-project-model>  
    <project-create-request-model v-if="createProjectRequestModalOpen === true" @closeCreateProjectRequestModal = "closeCreateProjectRequestModal" ></project-create-request-model>  
  </Transition>
  </div>


</div>

</template>

<script>
import { mapState, mapActions } from 'vuex'
import Datepicker from 'vuejs-datepicker'
import project from './project'
import moment from 'moment'
import projectDetailModel from './../partials/projectDetailModel.vue'
import accessDenied from './../partials/accessDenied.vue'
import createProjectModel from './../partials/createProjectModel.vue'
import projectCreateRequestModel from './../partials/projectCreateRequestModel.vue'
import { faPlus, faTimes } from '@fortawesome/free-solid-svg-icons'
import { ModelSelect } from "vue-search-select";


export default {
  name: 'projects',
  components: { project, projectDetailModel, Datepicker,accessDenied ,ModelSelect,createProjectModel,projectCreateRequestModel}, 

  data: () => ({
    user: {},
    faPlus,
    faTimes,
    currentProject: null,  
    currencies: Constants.currrenciesDetail,  
    error: Constants.error,
    createProjectButtonClicked: false,
    createProjectModalOpen: false,
    createProjectRequestModalOpen: false,
    show: false
  }),
  created () {
    
  },

  computed: {  
     ...mapState({
      projects: state => state.projects,
      currentComponent: state => state.dropdown.currentComponent,
      projectsDataLoaded: state => state.projectsDataLoaded,
      projectLoadingStarted:state => state.projectLoadingStarted,
      projectsTotal:state => state.projectsTotal,
      availableUsers: state => state.availableUsers
    }),
    getProjectButtonTypeText(){      
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateModel","project-frontend-btn")){ 
            return 'Create new project'
      }
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateRequest","project-frontend-btn")){ 
            return 'Create new project request'
      }
    },
    isProjectButtonAvailable(){
      var hasPermission = false
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateModel","project-frontend-btn")){ 
            hasPermission = true
      }
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateRequest","project-frontend-btn")){ 
            hasPermission = true
      }
      return hasPermission
    }
 
  },
  watch: {
    projectLoadingStarted: function(hasStarted) {
        this.addProjectLoadingCards(hasStarted)
    },     
    
  },  
  methods: {
    ...mapActions([
      'getProjects',
      'addProject',
      'setCurrentComponent',
      'toggleLoading',
    ]),
    toggleModel () {
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateModel","project-frontend-btn")){ 
            this.createProjectModalOpen = this.createProjectModalOpen == true ? false : true
      }
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateRequest","project-frontend-btn")){ 
            this.createProjectRequestModalOpen = this.createProjectRequestModalOpen == true ? false : true
      }
          
    },
    getCurrentStatus(){
       let currentParam = this.$route.params.status    
       let status = Constants.project.slug[currentParam]
       return status
    },    
    displayErrorMessage(value){
        if (!value && this.createProjectButtonClicked) {
            return true
        }else{
            return false
        }
    },  
    displayProjectModel (modalName,data){      
        this.currentProject = data
        this.setCurrentComponent(modalName)
    },
    openProjectModel (value) {
          this.toggleLoading(true)
          this.displayProjectModel('projectDetailModel',value)
          this.toggleLoading(false)
    },   
    customFormatter(date) {      
      return moment(date).format(Constants.customDateFormat.formatWithYear);
    },
    loadMore() {
      var height = window.innerHeight + window.scrollY + 1 // add 1px in the height
      var documentScrollHeight = document.body.scrollHeight
      if ( height >= documentScrollHeight) {               
        if(this.projectLoadingStarted === false) {
            let projectsGetParams = { status: this.getCurrentStatus() , category: this.$route.params.category, loadMore:  true}          
            this.getProjects(projectsGetParams)
        }
      }    
    },
    addProjectLoadingCards(hasStarted) {
        if(hasStarted === true){
              var projectCardHtml = '<div class="project-loading relative bg-white shadow-lg w-full sm:w-64 md:w-80 h-48 sm:mx-4 md:mx-12 lg:mx-4 flex flex-col justify-center items-center rounded-lg m-3 xl:mx-10 md:my-8 project-wrap"><div class="w-full p-4 h-32 flex flex-col items-start"><div class="project-settings"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="cursor-pointer view-project svg-inline--fa fa-eye fa-w-18"><path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z" class=""></path></svg> <button title="delete" class="w-8 h-8 bg-red-200 text-red-700 rounded-full flex justify-center items-center"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="cursor-pointer text-sm delete-project svg-inline--fa fa-trash-alt fa-w-14"><path fill="currentColor" d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm272-256a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z" class=""></path></svg></button> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-v" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="project-dropdown svg-inline--fa fa-ellipsis-v fa-w-6"><path fill="currentColor" d="M96 184c39.8 0 72 32.2 72 72s-32.2 72-72 72-72-32.2-72-72 32.2-72 72-72zM24 80c0 39.8 32.2 72 72 72s72-32.2 72-72S135.8 8 96 8 24 40.2 24 80zm0 352c0 39.8 32.2 72 72 72s72-32.2 72-72-32.2-72-72-72-72 32.2-72 72z" class=""></path></svg></div> <div class="text-pink-500 text-2xl no-underline cursor-pointer">Too Faced 13</div> <div class="content-info"><h6>Project Manager<span class="text-gray-500 text-sm self-start">Too Faced 13</span></h6> <h6>Deadline<span class="text-gray-500 text-sm self-start">Thursday 16th September 2021</span></h6></div></div> <div class="border-t w-full h-16 flex flex-row justify-start items-center px-4 profile-icons"><div class="flex flex-row flex-row-reverse"><a href="javascript:void(0)" class="-ml-2"><div class="relative inline"><div class="w-10 h-10"><img src="https://www.w3schools.com/html/pic_trulli.jpg" class="rounded-full mr-1 border-white border-2 rounded-full w-10 h-10"></div></div></a></div></div></div>';
              var currentProjectCardsLength = $('.project-wrap').length;              
              var displayCards = 3;               
              var newProjectCardHtml = '';  
              if (displayCards > 1){
                  for (let i = 1; i <= displayCards; i++) {
                      newProjectCardHtml = newProjectCardHtml+projectCardHtml }
                  $('#projects-list').append(newProjectCardHtml);
              }
      }else{
        // Delay 500ms to display loading effect
        setTimeout(() => { $('.project-loading').remove(); }, 500);        
      }
    },
    closeProjectModalOpen(val){
          this.createProjectModalOpen = val
    },
    closeCreateProjectRequestModal(val){
          this.createProjectRequestModalOpen = val
    },
  },
  mounted (){
    // load new projects
    // EventBus.$once('update-projects-listing', this.getAllProjects)
    window.addEventListener("scroll", this.loadMore, true)

  }
}
</script>

  <style>
    
  </style>