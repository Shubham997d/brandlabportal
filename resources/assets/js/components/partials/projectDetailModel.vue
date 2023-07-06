<template>
<div v-if="typeof project != 'undefined' && project != null">

      <div @click="closeModal" class="fixed inset-0 opacity-25 bg-black z-50"></div>
      <div class="fixed inset-x-0 lg:max-w-lg z-50 mx-auto rounded project-detail-model">

        <div class="bg-white p-6 rounded-t-lg">

        <div class="modal-tab">
            <ul class="custom-nav-tab">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab1">{{ 'Project Details' | localize }}</a>
              </li>       
          </ul>

        
          <div class="tab-content">
            <div class="tab-pane container active" id="tab1">

              <!-- Project Edit Form -->

              <form @submit.prevent="updateProject">

                <div class="py-2">
                  <label for="name" class="text-sm text-gray-700">{{ 'Title' | localize }} 
                    <span class="astrik"> * </span>
                  </label>
                  <input v-model="name" maxlength="50" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="text">
                  <div class="error" v-if="displayErrorMessage($v.name.required)">{{ validationErrorMessages.required }}</div>
                  <div class="error" v-if="displayErrorMessage($v.name.maxLength)">{{ validationErrorMessages.maxlength.title }}</div>
                </div>

                <div class="py-2">
                  <label for="project_manager_id" class="text-sm text-gray-700">{{ 'Project Manager' | localize }}
                    <span class="astrik"> * </span>
                  </label>
                 <model-select
                  :options="availableUsers"
                  v-model="project_manager_id"
                  placeholder="Users"
                  class="form-control"
                  option-value="value"
                  option-text="text"
                  ></model-select>
                  <div class="error" v-if="displayErrorMessage($v.project_manager_id.required)">{{ validationErrorMessages.required }}</div>
                </div> 
                <div class="py-2">
                  <label for="deadline" class="text-sm text-gray-700">{{ 'Deadline' | localize }}
                    <span class="astrik"> * </span>
                  </label>
                 <input maxlength="50" ref="focusInput" class="form-control" type="text" v-model="deadline" :placeholder="$options.filters.localize('Add project deadline')" >

                  <div class="error" v-if="displayErrorMessage($v.deadline.required)">{{ validationErrorMessages.required }}.</div>
                </div>

                 <div class="py-2">
                  <label for="monthly_usage_fee" class="text-sm text-gray-700">{{ 'Average Monthly Usage Fee' | localize }}
                  </label>
                  <input v-model="monthly_usage_fee" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="number" :placeholder="$options.filters.localize('Add average monthly usage fee')" step="0.01">
                </div>

                 <div class="py-2">
                  <label for="contact_term" class="text-sm text-gray-700">{{ 'Contract Term Length (Months)' | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input v-model="contact_term" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="number" :placeholder="$options.filters.localize('Add cost of project')" step="1">                  
                    <div class="error" v-if="displayErrorMessage($v.contact_term.required)">{{ validationErrorMessages.required }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.contact_term.integer)">{{ validationErrorMessages.integer }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.contact_term.between)">{{ betweenValidationText }}.</div>
                </div>
                
                 <div class="py-2">
                  <label for="cost" class="text-sm text-gray-700">{{ 'Contract Value' | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input v-model="cost" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="number" :placeholder="$options.filters.localize('Add cost of project')" step="0.01">
                  <div class="error" v-if="displayErrorMessage($v.cost.required)">{{ validationErrorMessages.required }}.</div>
                </div>

                <div class="py-2">

              <label for="currency" class="text-sm text-gray-700">{{ 'Currency' | localize }}<span class="astrik"> * </span></label>
                <multiselect
                      class="currency-dropdown"
                      v-model="currency"
                      :options="currencies"
                      placeholder="Add currency of project"
                      label="name"
                      track-by="name"
                    ></multiselect>
                <div class="error" v-if="displayErrorMessage($v.currency.required)">{{ validationErrorMessages.required }}.</div>
              </div>

                <div class="py-2">
                  <label for="project_categories" class="text-sm text-gray-700">{{ 'Category' | localize }}<span class="astrik"> * </span></label>
                          <multiselect
                                class="currency-dropdown"
                                v-model="projectCategory"
                                :options="projectCategories.original"
                                placeholder="Select Category"
                                label="name"
                                track-by="name"
                            ></multiselect>
                  <div class="error" v-if="displayErrorMessage($v.projectCategory.required)">{{ validationErrorMessages.required }}.</div>
              </div>

                <div class="py-2">
                    <label for="commission_user_id" class="text-sm text-gray-700">{{ 'Commission User' | localize }}  </label>
                  <model-select
                      :options="availableUsers"
                      v-model="commission_user_id"
                      placeholder="Users"
                      class="form-control"
                      option-value="value"
                      option-text="text"
                      ></model-select>
                    <div class="error" v-if="displayErrorMessage($v.commission_user_id.required)">{{ validationErrorMessages.required }}.</div>
                    <span class="hidden"></span>
               </div>

                <div class="py-2"> 
                  <label for="commission_user_value" class="text-sm text-gray-700">{{ "User's Commission (%)" | localize }}</label>
                  <input min="0" max="100"  v-model="commission_user_value" ref="focusInput" class="form-control" type="number" :placeholder="$options.filters.localize('Add Commission')" step="1">
                  <div class="error" v-if="displayErrorMessage($v.commission_user_value.required)">{{ validationErrorMessages.required }}</div>          
                </div>

                <div class="py-2">
                  <label for="status" class="text-sm text-gray-700">{{ 'Project Status' | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <select v-model='status' class="shadow appearance-none border rounded w-full px-3 py-2 text-gray-700 bg-gray-200" id="status">
                        <option :value="projectStatusConstant.status.in_progress">{{ projectStatusConstant.label.in_progress | localize }}</option>
                        <option :value="projectStatusConstant.status.completed">{{ projectStatusConstant.label.completed | localize }}</option>
                  </select>
                  <div class="error" v-if="displayErrorMessage($v.status.required)">{{ validationErrorMessages.required }}.</div>
              </div> 

              <div class="flex flex-row justify-end p-6 rounded-b-lg">
                  <button @click="closeModal" class="border bg-white py-2 px-3 mr-4 rounded">{{ 'Cancel' | localize }}</button>
                  <button type="submit" class="main-button">{{ 'Update' | localize }}</button>
                </div>
              </form>

            </div>
          </div>
        </div>
    </div>
  </div>
</div>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
import doughnutChart from './doughnutChart.vue'
import { required,requiredIf, maxLength, between, integer} from 'vuelidate/lib/validators'
import { ModelSelect } from "vue-search-select";


export default {
  components: { Datepicker,doughnutChart ,ModelSelect },
  props: {  
    project: {
      required: true 
    },
    refreshProjectDetailPage: {
      default: false,
      type: Boolean
    }
  },

  data: () => ({
    projectStatusConstant: Constants.project,
    projectStatus: '',    
    auth_admin_default_role_id: Constants.adminDetails.role,
    projectManagers: Constants.projectManagers,
    sendmail: false,
    userDurationchartData: {}, 
    userDataLoaded: false,
    groupType: new URL(location.href).searchParams.get('group_type'),
    submitButtonClicked: false,
    validationErrorMessages: Constants.validationErrorMessages,
    currencies: Constants.currrenciesDetail, 
    name: '',
    project_manager_id: '',
    project_manager_username: '',
    cost: '',
    status: '', 
    deadline: '',
    currency: '',
    commission_user_id: '',
    projectCategory: '',
    commission_user_value: '',
    monthly_usage_fee: '',
    contact_term: '',
  }),

   validations: {
    name: {
      required,
      maxLength: maxLength(50)
    },
    cost: {
      required,
    },
    contact_term: {
      required,
      integer,
      between: between(1, 36)  /* between 1-36 months */
    },
    currency:{
      required,
    },
    status: {
      required,
    },
    deadline: {
      required,
    },
    project_manager_id: {
      required
    },
    projectCategory:{
      required
    },
    commission_user_value: { 
          required: requiredIf(function () {
            if(this.commission_user_id != "") {
              return true;
            }
          }),  
    },
    commission_user_id: { 
          required: requiredIf(function () {
            if(this.commission_user_value != "") {
              return true;
            }
          }),  
    }
  }, 

  computed: {
    ...mapState({
      currentView: state => state.currentView,
      projects: state => state.projects,
      availableUsers: state => state.availableUsers,
      projectCategories: state => state.projectCategories,
    }),
    betweenValidationText(){
      return this.validationErrorMessages.between+' 1 and 36';
    }
    
  },
  created () {     
      this.setProjectData()
  },

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification',
      'setCurrentComponent',
      'toggleLoading',
      'getProjects',
      'removeProject'
    ]),
    closeModal () {
      this.closeComponent()
      this.submitButtonClicked = false
    },
    setProjectData(){
        this.name = this.project.name
        this.cost = this.project.cost
        this.status = this.project.status
        this.project_manager_id = this.project.project_manager_id
        this.deadline = this.project.deadline
        this.commission_user_id = this.project.commission_users[0] ? this.project.commission_users[0].user_id : ''
        this.commission_user_value = this.project.commission_users[0] ? this.project.commission_users[0].commission_value : ''
        this.projectCategory = this.project.project_categories[0].category_details[0] ? this.project.project_categories[0].category_details[0] : ''
        var self = this
        this.currency = this.currencies.filter(function (currency) { return currency.code == self.project.currency_code });
        this.monthly_usage_fee = this.project.monthly_usage_fee
        this.contact_term = this.project.contact_term
    },
    getProjectData(response){   
          this.project.name = this.name
          this.project.cost = this.cost
          this.project.status = this.status
          this.project.project_manager_id = this.project_manager_id
          this.project.deadline = this.deadline
          this.commission_user_id = this.project.commission_user_id
          this.$set(this.project, 'commission_users', response.data.project.commission_users);
          this.commission_user_value = this.project.commission_user_value
          this.project.project_manager_username = response.data.project.project_manager_username
          this.project.members = response.data.project.members
          this.project.currency_code = response.data.project.currency_code
          this.project.monthly_usage_fee = response.data.project.monthly_usage_fee
          this.project.contact_term = response.data.project.contact_term
          this.$set(this.project, 'project_categories', response.data.project.project_categories);
    },
     displayErrorMessage(value){
        if (!value && this.submitButtonClicked) {
            return true
        }else{
            return false
        }
    }, 
    updateProject () { 
      this.$v.$touch()
      this.submitButtonClicked = true        
      if (!this.$v.$invalid) { 
        this.toggleLoading(true)                
          axios.post('/data/projects/' + this.project.id, {
            name: this.name,
            id: this.project.id,
            project_manager_id: this.project_manager_id,
            status: this.status,
            cost: this.cost,
            deadline: this.deadline,
            currency: this.currency,
            project_category: this.projectCategory,
            commission_user_id: this.commission_user_id,
            commission_user_value: this.commission_user_value,
            monthly_usage_fee: this.monthly_usage_fee,
            contact_term: this.contact_term,
          })
            .then((response) => {
              if (response.data.status === 'success') {      
                this.getProjectData(response)                                
                this.$store.commit('updateFrontendOnProjectsAction',{'action': 'updateProject','project': response.data.project,'index': this.getIndexOfTheCurrentProject() })
                this.showNotification({type: response.data.status, message: response.data.message})
                this.closeModal()
                this.toggleLoading(false)
                if (this.refreshProjectDetailPage === true) {
                      EventBus.$emit('refresh-project-details','refreshCharts')                  
                }
              }
            })
            .catch((error) => {
              this.toggleLoading(false)
              this.showNotification({type: error.response.data.status, message: error.response.data.message})
            })

      }
     },    
    customFormatter(date) {      
      return moment(date).format(Constants.customDateFormat.formatWithYear);
    },
    isValidDate (dateString) {      
      var isValid = true
      var date = String(dateString) 
      if (date.length > 12) {
          isValid = false
      }      
      return isValid
    },
    authCheck () {
        return this.auth_admin_default_role_id === authUser.role_id ? true : false         
    },    
    json2array (json){
        var result = [];
        var keys = Object.keys(json);
        keys.forEach(function(key,index){
            result.push(json[key]);
        });
        var finalResult = [];
        finalResult[0] = [];
        // arr.push({title : link});
        finalResult[0]['backgroundColor'] = result[0]
        finalResult[0]['data'] = result[1]
        return finalResult;
    },
    getIndexOfTheCurrentProject(){
        for( var i = 0, len = this.projects.length; i < len; i++ ) {        
            if( this.projects[i]['id'] == this.project.id ) {
                return i
            }
          }
    },
    getCurrentStatus(){
       let currentParam = this.$route.params.status    
       let status = Constants.project.slug[currentParam]
       return status
    }
  
  },
  mounted() {
      // TAB
     /*   $( ".nav-item" ).click(function() {
            var id = $(this).children('a').data('toggle');
            $(".nav-item").children('.nav-link').removeClass('active');          
            $(this).children('a').addClass('active');
            $(".tab-pane:not([class*='fade'])").addClass("fade");
            $(".tab-pane").removeClass("active")
            $('#'+id).removeClass('fade');                
            $('#'+id).addClass('active');            
       }); */
       
  }
 
}
</script>
