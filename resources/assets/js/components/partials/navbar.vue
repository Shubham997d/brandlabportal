<template>
<div class="sticky top-0" v-if="$route.name">
  <!-- background: linear-gradient(to right, #8497EC, #3958E3); --> 
  <!-- style="background-color: #fce5e3; -->
  <div class="h-1 top-progress-bar"></div>
  <nav class="bg-white h-12 border-b">
    <div class="sm:container sm:mx-auto px-4 sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl flex flex-row justify-between h-full main-logo">
      <router-link :to="'/'" :active-class="'flex items-center text-indigo-500 text-2xl font-bold md:font-normal no-underline h-12'">   <img class="brand_logo" src="/storage/logos/brandlab_logo.png"></router-link>
      

      <!-- <router-link :to="'/user/' + user.username" class="">
          <profile-card :user="user" :duration="user.userTotalDuration" :isRelative="'relative'"></profile-card>
      </router-link> -->
      <div class="md:flex ct-user-icons">
        <div class="flex flex-row h-full">

          <users-online-status :key="updateAvatarForUserInNav"></users-online-status>
          <router-link :to="'/admin/log/activity'" class="activity-log-icon" :active-class="'active-activity-log-icon'" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewActivityLog','common-frontend-nav')"> 
                <font-awesome-icon :icon="faHistory" class="font-bold text-xl"></font-awesome-icon>         
          </router-link>
           <!-- <router-link :to="'/chat/coversation'" class="chat-icon" :active-class="'active-chat-icon'"> 
                <font-awesome-icon :icon="faCommentAlt" class="font-bold text-xl"></font-awesome-icon>         
          </router-link> -->
          <notification-dropdown :key="updateAvatarForNotification"></notification-dropdown>         
          <profile-dropdown :key="updateProfileAvatar"></profile-dropdown>
        </div>
      </div>
    </div>

<!-- Main Nav -->

    <div class="bg-white shadow" v-if="active === 'main-nav'">    
      <!-- :class="{'text-indigo-700 font-semibold border-indigo-500 border-b-2 pb-2 -mb-3':(route.name === 'projects' && projectStatus == projectStatusInProgress), 'cursor-pointer': (currentView != 'projects'  || projectStatus == projectStatusCompleted)} -->
        <div id="group-menu" class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl flex justify-start mb-8 pb-3 pt-4 text-gray-700">   
          <span class="route-button-back" @click="routeGoBack()"><font-awesome-icon :icon="faArrowAltCircleLeft" class="sm:hidden md:inline mr-2"></font-awesome-icon></span>
          <span class="mr-4 sm:mr-8">
            <b-nav-item-dropdown text="Projects">             
              <div v-for="(categories,index) in projectCategories.projectCategoriesForRoutes" :key="index">            
               <b-dropdown-item  :to="'/projects/'+categories.slug+'/in-progress'" :active-class="'active'" class="route-item" >
                    {{ categories.name | localize }}
                </b-dropdown-item>             
              </div>
            </b-nav-item-dropdown>
                <!-- <router-link :to="'/projects/in-progress'" :active-class="'active'" class="route-item">{{ 'Projects' | localize }}</router-link> -->
          </span>      
          <!-- <span class="mr-4 sm:mr-8" v-if="isUserAdmin.status"> 
            <router-link :to="'/projects/completed'" :active-class="'active'" class="route-item">{{ 'Projects Completed' | localize }}</router-link>
          </span> -->
          <span class="mr-4 sm:mr-8">
             <b-nav-item-dropdown text="Deals">            
               <b-dropdown-item  :to="'/deals/all'" :active-class="'active'" class="route-item" >
                    {{ 'All Deals' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/deals/open'" :active-class="'active'" class="route-item" >
                    {{ 'Deals open' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/deals/paused'" :active-class="'active'" class="route-item" >
                    {{ 'Deals paused' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/deals/won'" :active-class="'active'" class="route-item" >
                    {{ 'Deals won' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/deals/lost'" :active-class="'active'" class="route-item" >
                    {{ 'Deals lost' | localize }}
                </b-dropdown-item>               
            </b-nav-item-dropdown>    
            <!-- <router-link :to="'/deals'" :active-class="'active'" class="route-item">{{ 'Deals' | localize }}</router-link> -->
          </span>
          <ul class="info-website-logo"> 
            <li><a href="https://outlook.office.com/mail/" target="_blank"><img src="/storage/images/outlook.png"></a></li>
            <li><a href="https://www.dropbox.com/" target="_blank"><img src="/storage/images/dropbox.png"></a></li>
            <li><a href="https://trello.com/" target="_blank"><img src="/storage/images/trello.png"></a></li>
            <li><a href="https://brandlab360.charliehr.com/" target="_blank"><img src="/storage/images/charlie-hr.png"></a></li>
            <li><a href="https://docs.google.com/document/d/1U1vdKwqUMNx9uM4-crPyZBYMfjbztOSdH5kuCVu2Yns/edit?usp=sharing" target="_blank"><img src="/storage/images/sales_content.png"></a></li>
            <li><a href="https://docs.google.com/spreadsheets" target="_blank"><img src="/storage/images/google_sheets.png"></a></li>
            <li><a href="https://hub.brandlab360.co.uk/products" target="_blank"><img src="/storage/images/directory.png"></a></li>
            <li><a href="https://docs.google.com/forms/u/1/d/e/1FAIpQLSehLKAiLoMkHc6B8I3NqjWkO-6uOPt_JDBnHOMlN0waQYVUXg/viewform?usp=send_form" target="_blank"><img src="/storage/images/request_for_proposal.png"></a></li>
            <li @click="triggerModel()"><a href="javascript:void(0);" ><img src="/storage/images/copy.png"></a></li>
          </ul>  
          <b-breadcrumb :items="$route.meta.breadcrumb($route)"></b-breadcrumb>     
        </div>
   </div>

<!-- Project Nav -->

    <div class="bg-white shadow" v-if="active === 'project-nav'"> 
      <!-- :class="{'text-indigo-700 font-semibold border-indigo-500 border-b-2 pb-2 -mb-3':(route.name === 'projects' && projectStatus == projectStatusInProgress), 'cursor-pointer': (currentView != 'projects'  || projectStatus == projectStatusCompleted)} -->
        <div id="project-menu" class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl flex justify-start mb-8 pb-3 pt-4 text-gray-700" v-if="$route.meta.root($route) === 'project'">   
          <span class="route-button-back" @click="routeGoBack()"><font-awesome-icon :icon="faArrowAltCircleLeft" class="sm:hidden md:inline mr-2"></font-awesome-icon></span>
         
          <span class="mr-4 sm:mr-8" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewProjectDetail','project-frontend-nav')">
            <router-link :to="getRouterLink+'/project/'+$route.params.id+'/detail'" :active-class="'active'" class="route-item" >
                <font-awesome-icon :icon="faProjectDiagram" class="md:inline mr-2"></font-awesome-icon>
                <span class="sm:block font-regular">{{ 'Project' | localize }}</span>
            </router-link>
          </span>
          <span class="mr-4 sm:mr-8">
            <router-link :to="getRouterLink+'/project/'+$route.params.id+'/tasks'" :active-class="'active'" class="route-item" >
                <font-awesome-icon :icon="faTasks" class="md:inline mr-2"></font-awesome-icon>
                <span class="sm:block font-regular">{{ 'Tasks' | localize }}</span>
            </router-link>
          </span>
          <span class="mr-4 sm:mr-8" >
            <router-link :to="getRouterLink+'/project/'+$route.params.id+'/assets'" :active-class="'active'" class="route-item" >
                <font-awesome-icon :icon="faFileInvoiceDollar" class="md:inline mr-2"></font-awesome-icon>
                <span class="sm:block font-regular">{{ 'Assets' | localize }}</span>
            </router-link>
          </span>
          <span class="mr-4 sm:mr-8" v-if="dealId !== null">
            <router-link :to="'/deal/'+dealId" :active-class="'active'" class="route-item" >
                <font-awesome-icon :icon="faSearchPlus" class="md:inline mr-2"></font-awesome-icon>
                <span class="sm:block font-regular">{{ 'Overview' | localize }}</span>
            </router-link>
          </span>
          <b-breadcrumb :items="$route.meta.breadcrumb($route)"></b-breadcrumb>      
        </div>
   </div> 

  
<!-- Admin Nav -->

    <div class="bg-white shadow" v-if="active === 'admin-nav'"> 
      <!-- :class="{'text-indigo-700 font-semibold border-indigo-500 border-b-2 pb-2 -mb-3':(route.name === 'projects' && projectStatus == projectStatusInProgress), 'cursor-pointer': (currentView != 'projects'  || projectStatus == projectStatusCompleted)} -->
        <div id="admin-menu" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'viewAdmin','project-frontend-nav')" class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl flex justify-start mb-8 pb-3 pt-4 text-gray-700">   
          <span class="route-button-back" @click="routeGoBack()"><font-awesome-icon :icon="faArrowAltCircleLeft" class="sm:hidden md:inline mr-2"></font-awesome-icon></span>
        
          <span class="mr-4">
            <router-link :to="'/admin/users'" :active-class="'active'" class="route-item" >
                <span class="sm:inline-block font-regular">{{ 'Users' | localize }}</span>
            </router-link>
          </span>
           <span class="mr-4"> 
               <b-nav-item-dropdown text="Projects Completed">                 
                  <div v-for="(categories,index) in projectCategories.projectCategoriesForRoutes" :key="index">            
                    <b-dropdown-item  :to="'/admin/projects/'+categories.slug+'/completed'" :active-class="'active'" class="route-item" >
                          {{ categories.name | localize }}
                    </b-dropdown-item>             
                  </div>
            </b-nav-item-dropdown>
          </span>
          <span class="mr-4">
             <b-nav-item-dropdown text="Deal reports" toggle-class="nav-link-custom">            
               <b-dropdown-item  :to="'/admin/report/deals/started'" :active-class="'active'" class="route-item" >
                    {{ 'Deals started' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/admin/report/deals/won-over-time'" :active-class="'active'" class="route-item" > 
                    {{ 'Deals won over time' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/admin/report/deals/progress'" :active-class="'active'" class="route-item" >
                    {{ 'Deals progress' | localize }}
                </b-dropdown-item>
                <b-dropdown-item  :to="'/admin/report/deals/lost-by-reasons'" :active-class="'active'" class="route-item" >
                    {{ 'Deals lost by reasons' | localize }}
                </b-dropdown-item> 
                 <b-dropdown-item  :to="'/admin/report/deals/activity'" :active-class="'active'" class="route-item" >
                    {{ 'Deals activity' | localize }}
                </b-dropdown-item>               
            </b-nav-item-dropdown>           
          </span>
           <span class="mr-4">
            <router-link :to="'/admin/log/activity'" :active-class="'active'" class="route-item" >
                <span class="sm:inline-block font-regular">{{ 'Activity log' | localize }}</span>
            </router-link>
          </span>
           
          <b-breadcrumb :items="$route.meta.breadcrumb($route)"></b-breadcrumb>     
        </div>
   </div> 

  </nav>
    <!-- <message-box></message-box>
    <notification-box></notification-box> -->
    <!-- Model for copying text -->
    <b-modal id="show-copy-content">
       <template #modal-title>
          MetaTown Retail Submission Form
        </template>
          <div class="show-copy-content-group">
             <h3 id="copy-text">{{ copyText }}</h3>
             <font-awesome-icon :icon="faCopy" class="copy-content" @click="copyTexPopup()"></font-awesome-icon>
        </div>
    </b-modal>
</div>
</template>

<script>
  import notificationDropdown from './notificationDropdown'
  import usersOnlineStatus from './usersOnlineStatus'
  import { mapActions, mapState } from 'vuex'
  import profileDropdown from './profileDropdown'
  import { 
    faTasks,
    faProjectDiagram,
    faUser,
    faSearchPlus,
    faArrowAltCircleLeft,
    faFileInvoiceDollar,
    faCopy,
    faHistory,
    faCommentAlt

   } from '@fortawesome/free-solid-svg-icons'

  export default {
    components: {notificationDropdown, profileDropdown, usersOnlineStatus},
    data: () => ({
      token: Laravel.csrfToken,
      url: navUrl,
      faTasks,
      faProjectDiagram,      
      active: 'main-nav', //Set Main
      updateProfileAvatar : 10000,
      updateAvatarForNotification : 11000,
      updateAvatarForUserInNav : 12000,
      faUser,
      faCopy,
      faHistory,
      faCommentAlt,
      items: [],
      projectHasDeal: false,      
      faSearchPlus,
      faArrowAltCircleLeft,
      faFileInvoiceDollar,
      currentAuthUser: authUser,
      copyText: 'https://bit.ly/MetaTownRetailSubmissionForm'
    }),   
   
    computed: {
    ...mapState({
      currentView: state => state.currentView,
      isUserAdmin: state => state.isUserAdmin,
      projectCategories: state => state.projectCategories,
      project: state => state.project.project
    }),

    getRouterLink() {
      if (this.$route.path.includes('/admin')) {
              return '/admin';
        }else{
              return '';
        }      
    },
    dealId (){  /* check if project has deal if not return null */
        return this.project != null && this.project.deal_id !== null ? this.project.deal_id : null         
    }    
    
    }, 
    methods: {
        ...mapActions([
          'showNotification',
          'toggleLoading',
        ]),
        getCurrentNavBar (data){
            this.active = data
        },
        updateUserAvartar(){
            this.updateProfileAvatar += 1
            this.updateAvatarForNotification += 1
            this.updateAvatarForUserInNav += 1
        },
        routeGoBack(){
          this.$router.go(-1);
        },
        copyTexPopup(){                    
            this.toggleLoading(true) 
            navigator.clipboard.writeText(this.copyText);      
            setTimeout(() => { /* Placebo affect for user */
              this.toggleLoading(false) 
              this.showNotification({type: 'success', message: 'Link has been copied'})                  
            }, 100);
        },
        triggerModel(){
            this.$bvModal.show('show-copy-content')
        }     
    },
    mounted () {
      // Receive current nav bar event from main index file
      EventBus.$on('current-nav', this.getCurrentNavBar)
      EventBus.$on('avatarUpdated', this.updateUserAvartar)
    },    
     
  }
</script>
