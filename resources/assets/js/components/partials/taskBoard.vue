<template>      
    
<div id="task-container" class="w-full">
  
<!--   <create-task-form ref="taskform" :resource="resource" :resourceType="resourceType" :form-shown="createTaskFormShown" :tasks="tasks" :task="task" @close="closeCreateTaskForm"></create-task-form> -->

  <!-- <task-details v-if="task" :index="index" :resourceType="resourceType" :resourceId="resource.id" :task="task" :taskDetailsShown="taskDetailsShown" :statuses="statuses" @status-change="updateStatus" @delete="deleteTask" @close="closeTaskDetails"></task-details> -->

  <!-- <div  class="my-4 flex items-center">
   <button @click="createTask" class="no-underline px-3 py-2 my-4 bg-white text-center text-base text-white bg-indigo-500 border border-indigo-500 rounded shadow">{{ 'Create Task' | localize }}</button> 
   <button @click="toggleFilter" class="no-underline px-3 py-2 m-4 text-center text-base rounded bg-white border border-gray-500">
      <font-awesome-icon :icon="faFilter"
        class="pointer-events-none items-center text-gray-600 mr-1">
      </font-awesome-icon>
      {{ 'Filter' | localize }}
    </button>
    <div class="bg-white rounded flex items-center">
      <div :class="{'bg-gray-700 text-white': view === 'row'}" @click="changeView('row')" class="border-r py-2 px-4 cursor-pointer rounded-l">
        <font-awesome-icon :icon="faThLarge"
          class="pointer-events-none items-center mr-1">
        </font-awesome-icon>
      </div>
      <div :class="{'bg-gray-700 text-white': view === 'column'}" @click="changeView('column')" class="py-2 px-4 cursor-pointer rounded-r">
        <font-awesome-icon :icon="faThList"
          class="pointer-events-none items-center mr-1">
        </font-awesome-icon>
      </div>
    </div>
  </div> -->


  <!-- Task Filters -->
 <!--  <div v-if="filterShown" class="mb-8">
    <div class="border-t border-b border-gray-400">
      <div class="pb-1 pt-2">{{'Status' | localize }}</div>
      <div class="inline-flex rounded shadow">
        <div
          v-for="(status, index) in statuses"
          @click="selectStatusFilter(status.id)"
          class="p-2 cursor-pointer"
          :class="[statusFilter === status.id ? 'bg-indigo-200 text-indigo-800 font-medium' : 'bg-gray-100', index === 0 ? 'rounded-l' : 'border-l-2', index + 1 === statuses.length ? 'rounded-r' : '']">
          {{ status.name | localize  }}
        </div>
      </div>
      <div class="flex flex-row flex-wrap text-gray-700">
        <div class="p-4 pl-0">
          <div class="pb-1 flex justify-between items-center">
            <div class="">{{'Assigned To' | localize }}</div>
            <div @click="clearUserFilter" class="text-xs font-medium border-b border-indigo-500 cursor-pointer">{{'Clear' | localize }}</div>
          </div>
          <div class="flex flex-row items-center relative w-64">
            <select v-model="userFilter" class="w-full block appearance-none bg-white border border-gray-500 rounded text-gray-800 py-1 px-4 pr-8">
              <option selected disabled hidden value="">{{ 'Select a User' | localize }}</option>
                <template v-for="member in members">
                  <option :value="member.user_id" class="my-2 text-lg">{{ member.name }}</option>
                </template>
            </select>
            <font-awesome-icon :icon="faChevronDown"
              class="pointer-events-none items-center text-gray-600 absolute right-0 mr-3">
            </font-awesome-icon>
          </div>
        </div>
        <div class="p-4">
          <div class="pb-1 flex justify-between items-center">
            <div class="">{{'Tags' | localize }}</div>
            <div @click="clearTagFilter" class="text-xs font-medium border-b border-indigo-500 cursor-pointer">{{'Clear' | localize }}</div>
          </div>
          <div class="flex flex-row items-center relative w-64">
            <select v-model="tagFilter" class="w-full block appearance-none bg-white border border-gray-500 rounded text-gray-800 py-1 px-4 pr-8">
              <option selected disabled hidden value="">{{ 'Select a Tag' | localize }}</option>
                <template v-for="tag in tags">
                  <option :value="tag.tag_id" class="my-2 text-lg">{{ tag.label }}</option>
                </template>
            </select>
            <font-awesome-icon :icon="faChevronDown"
              class="pointer-events-none items-center text-gray-600 absolute right-0 mr-3">
            </font-awesome-icon>
          </div>
        </div>
        <div class="p-4">
          <div class="pb-1 flex justify-between items-center">
            <div class=""> {{ 'Due On' | localize }} </div>
            <div @click="clearDateFilter" class="text-xs font-medium border-b border-indigo-500 cursor-pointer">{{'Clear' | localize }}</div>
          </div>
          <datepicker v-model="dateFilter" ref="dueOnDate" :placeholder="$options.filters.localize('Select Date')" format="yyyy-MM-dd" input-class="w-full block appearance-none bg-white border border-gray-500 rounded text-gray-800 py-1 px-4 pr-8" wrapper-class=""></datepicker>
        </div>
      </div>
    </div>
  </div> -->
  <!-- Task Filters -->

  <!-- Task List -->

  <div :class="[ view === 'row' ? '' : 'grid grid-cols-5 gap-4']" v-if="tasks != undefined" v-click-outside="closeStatusMenu">
   <div v-for="(user, name) in groupedTasks" class="task-list" :key="user.id">
      <div class="task-list-c">
        <div class="toggle-task-list">
          <span class="toggle-task" :style="'background-color: ' + user.colour">
          <font-awesome-icon :icon="faChevronUp" class="user-task-dropdown user-assigned-icon">
          </font-awesome-icon>
          </span>
          <h6 class="user-assigned-name">{{ name }}</h6>
        </div>
      <div class="user-task-tb">

      <table>
        <thead>
          <tr >
          <th class="task-th-1 test">Task</th>
          <th class="task-th-2">Date</th>
          <th class="task-th-3">Status</th>
          <th class="task-th-4">Duration</th>
          <th class="task-th-5"></th>
        </tr>
      </thead>
      <tbody>
          <!-- :disabled="auth_user_id == task.assigned_to ? false : true" -->

        <tr v-for="(task, index) in user.tasks" :key="task.id" :style="'border-left: 3px solid '+ user.colour">          

          <td class="task-name-td"><input v-model="task.name" lazy @change="updateTaskName(task.id,task.name,task.assigned_to)" placeholder="Add Task Name" ></td>

          <td><datepicker v-model="task.due_on" ref="dueOnDate" :highlighted="state.highlighted" :placeholder="$options.filters.localize('Select Date')" :format="customFormatter" input-class="appearance-none text-gray-800" wrapper-class="appearance-none text-gray-800 py-3 px-4 rounded" lazy @input="updateDueOn(task.id,task.due_on,task.assigned_to)" ></datepicker></td>
          <td>
              <div @click="toggleStatusMenu" :style="'background-color: ' + task.status.color" class="px-4 py-1 text-gray-900 font-semibold rounded-full cursor-pointer task-status">
              {{ task.status.name | localize }}
              </div>
              <div class="absolute rounded shadow-md mt-2 py-1 text-left text-indigo-800 bg-gray-900 status-dropdown">
                <div v-for="status in statuses" :key="status.id"  :style="'color: ' + status.color" @click="changeStatus(task.id,status.id,status.name,status.color,index,task.assigned_to)" class="cursor-pointer hover:bg-white font-semibold px-4 py-2 status-dropdown-childrens"> {{ status.name | localize }} </div>
              </div>
          </td>
          <td><vue-timepicker lazy v-model="task.task_duration" ref="taskDuration" format="HH:mm" :minute-interval="10" @change="updateTimeDuration(task.id,task.task_duration,task.assigned_to)" close-on-complete advanced-keyboard ></vue-timepicker></td>
          <td>
           <!-- <font-awesome-icon :icon="faTrash" class="delete-task" @click="deleteTask(task.id,task.assigned_to)"> </font-awesome-icon> -->
           <button @click="deleteTask(task.id,task.assigned_to)" class="w-8 h-8 bg-red-200 text-red-700 rounded-full flex justify-center items-center" title="delete">
            <font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm"></font-awesome-icon>
            </button>
         </td>
       </tr>
      </tbody>
    </table>

    

    <div class="total-hours-table">
      <span class="add-task" @click="createTask(user.assigned_to)" :style="'background-color: ' + user.colour">
      <font-awesome-icon :icon="faPlus" class="add-task-btn" ></font-awesome-icon>
      </span>
      <p>Total: <span> <br>{{ user.duration }}</span></p>
    </div>
  </div>
    </div>
  </div>
  </div>

  <div v-if="tasks == undefined || tasks.length === 0 " class="flex flex-col items-center pt-8">
    <div class="pb-4">{{'Tasks are being loaded' | localize }}</div>
    <img src="/image/tasks.svg" alt="task list" class="w-96">
  </div>
  
  <!-- Task List -->
  

</div>

</template>

<script>
import Datepicker from 'vuejs-datepicker'
import VueTimepicker from 'vue2-timepicker'
import accessDenied from './accessDenied.vue'
import moment from 'moment'
import { mapState, mapActions } from 'vuex'
// import createTaskForm from './../forms/createTaskForm.vue'
// import taskDetails from './../partials/taskDetails.vue'
import {
  faQuestionCircle,
  faChevronDown,
  faSpinner,
  faFilter,
  faAngleDoubleRight,
  faThLarge,
  faThList,
  faChevronCircleDown,
  faTrashAlt,
  faPlus,
  faChevronUp,  
} from '@fortawesome/free-solid-svg-icons'

export default {
  name: 'project.tasks',
  components: {Datepicker, VueTimepicker},

  data: () => ({
    tasks: [],
    resourceType: '',    
    resource:{},
    task: {},
    statuses: [],    
    index: null,
    filterShown: false,
    statusFilter: null,
    userFilter: '',
    tagFilter: '',
    dateFilter: null,
    view: 'row',
    authenticated,
    faQuestionCircle,
    faChevronDown,
    faSpinner,
    faFilter,
    faAngleDoubleRight,
    faThLarge,
    faThList,
    faTrashAlt,
    faPlus,
    faChevronUp,
    faChevronCircleDown,
    statusMenuShown: false,
    durationTime: [],
    taskDurationFilter: [],
    group_id: '',
    group_type: '',
    auth_user_id: null,
    auth_user_role_id: null,
    auth_admin_default_role_id: Constants.adminDetails.role,
     state:{
      highlighted: {
          dates:[
            new Date()
          ]
      }
    }
  }), 

  async created () {  
    
    this.setParamsForProject()      
    this.statuses = await this.getAllStatuses()    
    this.tasks = await this.getAllTasks(true)

    /*Show Status DropDown*/
    $(document).on('click','.task-status',function(){     
          $(this).next().css('display') == 'block' ? $(this).next().hide() : $(this).next().show();           
    });

  },

  watch: { },

  computed: {  

    ...mapState({
      members: state => state.members      
    }),

    groupedTasks: function () {
      return this.tasks
    }
    
  },

  methods: {
    ...mapActions([
      'showNotification',
      'toggleLoading',
      'getProject',
      'getErrorResponseAccordingToError'
    ]),

    dueOn: function (value) {
      var date = luxon.DateTime.fromSQL(value)      
      return moment(date.ts).format(Constants.customDateFormat.format)
    },   

    async getAllTasks (update = false) {      
      try {
        this.toggleLoading(true)
        if (this.tasks.length < 1 || update) {
          
          let { data } = await axios({
            url: '/data/tasks',
            params: {
              resource_type: this.resourceType,
              resource_id: this.resource.id,
              cycle_id: null,
              status_id: this.statusFilter,
              assigned_to: this.userFilter,
              tag_id: this.tagFilter,
              due_on: this.dateFilter ? window.luxon.DateTime.fromISO(this.dateFilter.toISOString()).toISODate() : null
            }})
          this.tasks = data.tasks
          this.auth_user_id = parseInt(data.auth_user_id)
          this.auth_user_role_id = parseInt(data.auth_user_role_id)         
          this.toggleLoading(false)
          return data.tasks
        }
        return []
      } catch (error) {
        this.toggleLoading(false)
        this.getErrorResponseAccordingToError(error);
        this.showNotification({type: error.response.data.status, message: error.response.data.message})
      }
    },
    async getAllStatuses () {
      try {
        if (this.statuses.length < 1) {
          let { data } = await axios({
            url: '/data/statuses',
          })
          return data.statuses
        }
      } catch (error) {
        console.error(error)
      }
    },
    showTaskDetails (key, index, serial, id) {
      this.updateUrl({"id": id})
      this.index = serial
      this.task = this.groupedTasks[key][index]
      this.taskDetailsShown = true
    },
   
    deleteTask (taskId,taskAssignedId) {      
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
                  axios.delete('/data/tasks/' + taskId, {
                      params: {
                        group_type: this.resourceType,
                        group_id: this.resource.id,
                      }
                    })
                    .then((response) => {
                      this.getAllTasks(true).then((data) => {
                        this.getProject(this.group_id).then((response_n) => {
                            this.$emit('delete', this.index)
                            this.showNotification({type: response.data.status, message: response.data.message})
                            this.$emit('close')
                        })
                      })
                    })
                    .catch((error) => {
                      this.showNotification({type: error.response.data.status, message: error.response.data.message})
                      this.$emit('close')
                })
            }
        })
      
    },
    updateStatus (newValue) {
      this.$set(this.tasks, newValue.index, newValue.task)
      this.task = newValue.task
    },
    selectStatusFilter (id) {
      this.statusFilter = this.statusFilter === id ? null : id
    },
    clearUserFilter () {
      this.userFilter = ''
    },
    clearTagFilter () {
      this.tagFilter = ''
    },
    clearDateFilter () {
      this.dateFilter = null
    },
    toggleFilter () {
      this.filterShown = ! this.filterShown
    },
    changeView (view) {
      this.view = view
    },
    toggleStatusMenu () {
      this.statusMenuShown = !this.statusMenuShown
    },
    closeStatusMenu () {
      // this.statusMenuShown = false
      $(".status-dropdown").hide();
    },
    
    changeStatus (taskId,statusId,statusName,statusColor,index,taskAssignedId) {

        axios.put('/data/tasks/' + taskId + '/statuses/' + statusId, {
            group_type: this.resourceType,
            group_id: this.resourceId,
            assigned_to : taskAssignedId
          })
          .then((response) => {
            this.getAllTasks(true).then((data) => {
              this.statusMenuShown = false
              this.$emit('status-change', {index: this.index, task: response.data.task})
              this.showNotification({type: response.data.status, message: response.data.message})              
              $('.status-dropdown').hide();              
            })
          })
          .catch((error) => {
            this.statusMenuShown = false
            this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
     

    },
    slideToggle(){

    },
    getParsedTaskDuration (taskDuration){
        return taskDuration ? JSON.parse(taskDuration) : null
    },
    closeNotification () {
      this.showNotification = false
    },
    updateTimeDuration (taskId,taskDuration,taskAssignedId){       

        
         axios.put('/data/tasks/' + taskId, {
          task_duration: taskDuration ? taskDuration : null,
          group_id: this.group_id,
          group_type: this.group_type,
          assigned_to : taskAssignedId
        }) 
        .then((response) => {
            this.getAllTasks(true).then((data) => {   
              this.getProject(this.group_id).then((response_n) => {
                this.showNotification({type: response.data.status, message: response.data.message})            
                this.$emit('close', null, response.data.task)
              })                
            })
          })
        .catch((error) => {
          this.showNotification({type: error.response.data.status, message: error.response.data.message})
          this.$emit('close')
        })

      
      
    },
    updateTaskName (taskId,name,taskAssignedId){
         axios.put('/data/tasks/' + taskId, {
          name: name ? name : null,
          group_id: this.group_id,
          group_type: this.group_type,
          assigned_to : taskAssignedId
        }) 
        .then((response) => {
            this.getAllTasks(true).then((data) => {
              
              this.showNotification({type: response.data.status, message: response.data.message})            
              this.$emit('close', null, response.data.task)              
            })
          })
        .catch((error) => {
          this.showNotification({type: error.response.data.status, message: error.response.data.message})
          this.$emit('close')
        })
      
    },
    updateDueOn (taskId,dueOn,taskAssignedId){      

         axios.put('/data/tasks/' + taskId, {
          due_on: dueOn ? window.luxon.DateTime.fromISO(dueOn.toISOString()).toISODate() : null,
          group_id: this.group_id,
          group_type: this.group_type,
          assigned_to : taskAssignedId
        }) 
        .then((response) => {
            this.getAllTasks(true).then((data) => {
                this.showNotification({type: response.data.status, message: response.data.message})            
                this.$emit('close', null, response.data.task)
            })
            
          })
        .catch((error) => {
          this.showNotification({type: error.response.data.status, message: error.response.data.message})
          this.$emit('close')
        })
        
      
    },
    customFormatter(date) {
      return moment(date).format(Constants.customDateFormat.format);
    },
    createTask (taskAssignedId) {    
       
        axios.post('/data/tasks', {
          assigned_to: taskAssignedId,
          group_id: this.group_id,
          group_type: this.group_type,
          status_id: 1,
        })
          .then((response) => {
            if (response.data.status === 'success') {              
              this.getAllTasks(true).then((data) => {
                this.showNotification({type: response.data.status, message: response.data.message})            
                this.$emit('close', response.data.task)
              })
            }
            if (response.data.status === 'error') {              
                this.showNotification({type: response.data.status, message: response.data.message})            
                this.$emit('close', response.data.task)
            }
          })
          .catch((error) => {
            this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
     
     
    },
     updateStatusValues (taskId,statusId,statusName,statusColor) {

      for( var i = 0, len = this.tasks.length; i < len; i++ ) {        
        if( this.tasks[i]['id'] == taskId ) {
            this.tasks[i]['status_id']       =  statusId       
            this.tasks[i]['status']['id']    =  statusId
            this.tasks[i]['status']['name']  =  statusName
            this.tasks[i]['status']['color'] =  statusColor
            break;
        }
      }
    },
     getAssigneNameFromId (id) {          
          let promise = axios({url: '/assigned_to/'+id+'/user/'})          
          promise.then((response) => {
             userName = response.data.user.username             
           })         
    },
    setParamsForProject(){
        this.group_type = this.$route.meta.root(this.$route)
        this.resource.id = this.$route.params.id
        this.group_id = this.resource.id,
        this.resourceType = this.$route.meta.root(this.$route)
    },

  },
  mounted () {
    // Toggle Status dropdown
    $(document).on('click','.task-status',function(){     
          $(this).next().css('display') == 'block' ? $(this).next().hide() : $(this).next().show();           
    });
    /*Toggle User Task Dropdown*/    
    $(document).off().on('click','.toggle-task',function(){
        $(this).parent().parent('.task-list-c').find('.user-task-dropdown').toggleClass("user-assigned-icon user-assigned-name-not-set");
        $(this).parent().parent('.task-list-c').find('.user-task-tb').slideToggle("3000"); 
    });
  
  }
}


</script>

