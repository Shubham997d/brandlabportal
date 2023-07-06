<template>
  <div>
    <form @submit.prevent="createProject">
    <div @click="closeCreateProjectModal" class="fixed inset-0 opacity-25 bg-black z-50"></div>
    <div class="fixed inset-x-0 lg:max-w-lg z-50 mx-auto rounded pop-top create-project-popup">
      <div class="bg-white p-6 rounded-t-lg">
        <div class="flex justify-between items-center pb-2">
          <div class="text-lg text-gray-800">{{ getText('model_title','miscellaneous')}}</div>
          <div @click="closeCreateProjectModal">
            <font-awesome-icon :icon="faTimes" class="text-gray-600 cursor-pointer"></font-awesome-icon>
          </div>
        </div>

        <div class="py-2" :class="{ 'form-group--error': $v.name.$error }">
          <label for="title" class="text-sm text-gray-700">{{ getText('title')}}<span class="astrik"> * </span></label>
          <input name="title" maxlength="50" ref="focusInput" class="form-control" type="text" v-model="name" :placeholder="getText('title','placeholder')" >
          <div class="error" v-if="displayErrorMessage($v.name.required)">{{ validationErrorMessages.required }}</div>
          <div class="error" v-if="displayErrorMessage($v.name.maxLength)">{{ validationErrorMessages.maxlength.title }}</div>
          <span class="hidden"></span> 
        </div>
        
        <div class="py-2">
          <label for="project_manager_id" class="text-sm text-gray-700">{{ getText('project_manager_id') }}<span class="astrik"> * </span>  </label>
          <!-- <textarea name="project_manager_id" id="" cols="30" rows="2" v-model="project_manager_id" class="form-control" :placeholder="$options.filters.localize('Add details')"></textarea> -->
         <model-select
            :options="availableUsers"
            v-model="project_manager_id"
            :placeholder="getText('project_manager_id','placeholder')"
            class="form-control"
            option-value="value"
            option-text="text"
            ></model-select>
          <div class="error" v-if="displayErrorMessage($v.project_manager_id.required)">{{ validationErrorMessages.required }}.</div>
          <span class="hidden"></span>
        </div>

        <div class="py-2">
          <label for="cost" class="text-sm text-gray-700">{{ getText('cost') }}<span class="astrik"> * </span></label>
          <input min="0" v-model="cost" ref="focusInput" class="form-control" type="number" :placeholder="getText('cost','placeholder')" step="0.01">
          <div class="error" v-if="displayErrorMessage($v.cost.required)">{{ validationErrorMessages.required }}</div>          
        </div>

        <div class="py-2">
          <label for="currency" class="text-sm text-gray-700">{{ getText('currency') }}<span class="astrik"> * </span></label>
                  <multiselect
                        class="currency-dropdown"
                        v-model="currency"
                        :options="currencies"
                        :placeholder="getText('currency','placeholder')"
                        label="name"
                        track-by="name"
                      ></multiselect>
          <div class="error" v-if="displayErrorMessage($v.currency.required)">{{ validationErrorMessages.required }}.</div>
        </div>

         <div class="py-2">
              <label for="monthly_usage_fee" class="text-sm text-gray-700">{{ getText('monthly_usage_fee') }}
              </label>
              <input v-model="monthly_usage_fee" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="number" :placeholder="getText('monthly_usage_fee','placeholder')" step="0.01">
          </div>

            <div class="py-2">
            <label for="contact_term" class="text-sm text-gray-700">{{ getText('contact_term') }}
              <span class="astrik"> * </span>
            </label>
            <input v-model="contact_term" ref="focusInput" class="w-full appearance-none border rounded mt-2 py-2 px-3 text-gray-800" type="number" :placeholder="getText('contact_term','placeholder')" step="1">                  
              <div class="error" v-if="displayErrorMessage($v.contact_term.required)">{{ validationErrorMessages.required }}.</div>
              <div class="error" v-if="displayErrorMessage($v.contact_term.integer)">{{ validationErrorMessages.integer }}.</div>
              <div class="error" v-if="displayErrorMessage($v.contact_term.between)">{{ betweenValidationText }}.</div>
          </div>

        <div class="py-2">
          <label for="project_categories" class="text-sm text-gray-700">{{ getText('project_categories') }}<span class="astrik"> * </span></label>
                  <multiselect
                        class="currency-dropdown"
                        v-model="projectCategory"
                        :options="projectCategories.original"
                        :placeholder="getText('project_categories','placeholder')"
                        label="name"
                        track-by="name"
                    ></multiselect>
          <div class="error" v-if="displayErrorMessage($v.projectCategory.required)">{{ validationErrorMessages.required }}.</div>
        </div>

         <div class="py-2">
          <label for="commission_user_id" class="text-sm text-gray-700">{{ getText('commission_user_id') }}  </label>
          <!-- <textarea name="project_manager_id" id="" cols="30" rows="2" v-model="project_manager_id" class="form-control" :placeholder="$options.filters.localize('Add details')"></textarea> -->
         <model-select
            :options="availableUsers"
            v-model="commission_user_id"
            :placeholder="getText('commission_user_id','placeholder')"
            class="form-control"
            option-value="value"
            option-text="text"
            ></model-select>
          <div class="error" v-if="displayErrorMessage($v.commission_user_id.required)">{{ validationErrorMessages.required }}.</div>
          <span class="hidden"></span>
        </div>  

        <div class="py-2">
          <label for="commission_user_value" class="text-sm text-gray-700">{{ getText('commission_user_value') }}</label>
          <input min="0" max="100"  v-model="commission_user_value" ref="focusInput" class="form-control" type="number" :placeholder="getText('commission_user_value','placeholder')" step="1">
          <div class="error" v-if="displayErrorMessage($v.commission_user_value.required)">{{ validationErrorMessages.required }}</div>          
        </div>

          <div class="py-2">
           <label for="deadline" class="text-sm text-gray-700">{{ getText('deadline') }}<span class="astrik"> * </span></label>
          <input maxlength="50" ref="focusInput" class="form-control" type="text" v-model="deadline" :placeholder="getText('deadline','placeholder')" >
          <div class="error" v-if="displayErrorMessage($v.deadline.required)">{{ validationErrorMessages.required }}.</div>
        </div>
      </div>
      <div class="flex flex-row justify-end bg-gray-200 p-6 rounded-b-lg">
        <button @click="closeCreateProjectModal" class="border bg-white py-2 px-3 mr-4 rounded">{{ 'Cancel' | localize }}</button>
        <button type="  " class="main-button">{{ 'Create' | localize }}</button>
      </div>
    </div>
    </form>
  </div>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
import doughnutChart from './doughnutChart.vue'
import { required,requiredIf, maxLength,between, integer} from 'vuelidate/lib/validators'
import { ModelSelect } from "vue-search-select";
import { faPlus, faTimes } from '@fortawesome/free-solid-svg-icons'


export default {
  components: { Datepicker,doughnutChart ,ModelSelect },
  data: () => ({
    name: '',
    project_manager_id: '',
    commission_user_id: '',
    commission_user_value: '',
    cost: '',
    deadline: '',
    currency: '',
    projectCategory: '',
    monthly_usage_fee: '',
    contact_term: '',
    currencies: Constants.currrenciesDetail,
    createProjectButtonClicked: false,
    faTimes,
    faPlus,
    validationErrorMessages: Constants.validationErrorMessages
  }),

   validations: {
    name: {
      required,
      maxLength: maxLength(50)
    },
    contact_term: {
      required,
      integer,
      between: between(1, 36)  /* between 1-36 months */
    },
    cost: {
      required,
    },
    currency:{
      required,
    }, 
    projectCategory:{
      required,
    },  
    deadline: {
      required,
    },
    project_manager_id: {
      required
    },
    currency:{
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
    },
    hasCreateProjectPermission(){
        var hasPermission = false
        if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateModel","project-frontend-btn")){ 
              hasPermission = true
        }
        return hasPermission
    }
    
  },
  created () {     
  },

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification',
      'setCurrentComponent',
      'toggleLoading',
      'addProject',
      'removeProject'
    ]),
    closeCreateProjectModal () {
      this.$emit('closeProjectModalOpen',false)      
      this.createProjectButtonClicked = false
    },
    getCurrentStatus(){
       let currentParam = this.$route.params.status    
       let status = Constants.project.slug[currentParam]
       return status
    },
    createProject () {
      this.$v.$touch()
      this.createProjectButtonClicked = true
      if (!this.$v.$invalid) {
        var dealId = (this.$route.name == 'deal-details') ? this.$route.params.id : null
        if(this.hasCreateProjectPermission == true){
          this.addProject({name: this.name, project_manager_id: this.project_manager_id, cost: this.cost, deadline: this.deadline, currency :this.currency, commission_user_id: this.commission_user_id, commission_user_value : this.commission_user_value, project_category : this.projectCategory, monthly_usage_fee: this.monthly_usage_fee, contact_term: this.contact_term, deal_id: dealId  })
            .then(() => {  
                  if(dealId){
                    this.$parent.getAllDealsData()
                  }
                    
              })
            .catch((error) => {              
                this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
              
        }else{
          if (this.$route.name == 'deal-details') { // right now only create project request from deal detail page
              this.createProjectRequest({name: this.name, project_manager_id: this.project_manager_id, cost: this.cost, deadline: this.deadline, currency :this.currency, commission_user_id: this.commission_user_id, commission_user_value : this.commission_user_value, project_category : this.projectCategory, monthly_usage_fee: this.monthly_usage_fee, contact_term: this.contact_term , deal_id: dealId })
          }
        }        
        this.closeCreateProjectModal()
        this.emtpyData()
      }    
    },
    displayErrorMessage(value){
        if (!value && this.createProjectButtonClicked) {
            return true
        }else{
            return false
        }
    },
    emtpyData(){
        this.name = ''
        this.project_manager_id = '',
        this.deadline = '',
        this.cost = '',
        this.currency = ''
        this.projectCategory = ''
        this.commission_user_id = ''
        this.commission_user_value = ''
        this.monthly_usage_fee = ''
        this.contact_term = ''
    },
    getText(val,type = 'label'){
        if(this.hasCreateProjectPermission == true){
             return Constants.project.createProject.fromProjectPage[type][val]
        }else{
          if (this.$route.name == 'deal-details') {
              return Constants.project.createProject.fromDealPage[type][val]
          }          
        }        
        return 'N/A';
    },
    createProjectRequest(requestData) {
        axios.post('data/request/'+Constants.userRequests.resource[0]+'/'+Constants.userRequests.request[Constants.userRequests.resource[0]][2], {
                resource_id: null,
                request_data: requestData, 
                token: Laravel.csrfToken
          })
            .then((response) => {  
                this.showNotification({type: response.data.status, message: response.data.message})
                this.closeCreateProjectModal();
                this.emtpyData()
                this.toggleLoading(false)
            })
            .catch((error) => {              
                this.showNotification({type: error.response.data.status, message: error.response.data.message})
                this.toggleLoading(false)
          })
    }
  
  },
  mounted() {
      
       
  }
 
}
</script>
