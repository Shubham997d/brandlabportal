<template>
  <div>
    <form @submit.prevent="createProjectRequest">
    <div @click="closeCreateProjectRequestModal" class="fixed inset-0 opacity-25 bg-black z-50"></div>
    <div class="fixed inset-x-0 lg:max-w-lg z-50 mx-auto rounded pop-top create-project-popup">
      <div class="bg-white p-6 rounded-t-lg">
        <div class="flex justify-between items-center pb-2">
          <div class="text-lg text-gray-800">{{ 'Create new project request' | localize }}</div>
          <div @click="closeCreateProjectRequestModal">
            <font-awesome-icon :icon="faTimes" class="text-gray-600 cursor-pointer"></font-awesome-icon>
          </div>
        </div>
        <div class="py-2" :class="{ 'form-group--error': $v.name.$error }">
          <label for="title" class="text-sm text-gray-700">{{ 'Title' | localize }}<span class="astrik"> * </span></label>
          <input name="title" maxlength="50" ref="focusInput" class="form-control" type="text" v-model="name" :placeholder="$options.filters.localize('Add project title')" >
          <div class="error" v-if="displayErrorMessage($v.name.required)">{{ validationErrorMessages.required }}</div>
          <div class="error" v-if="displayErrorMessage($v.name.maxLength)">{{ validationErrorMessages.maxlength.title }}</div>
          <span class="hidden"></span> 
        </div>        
     
      <div class="flex flex-row justify-end rounded-b-lg button-group">
        <button @click="closeCreateProjectRequestModal" class="border bg-white py-2 px-3 mr-4 rounded">{{ 'Cancel' | localize }}</button>
        <button type="submit" class="main-button">{{ 'Create' | localize }}</button>
      </div>
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
import { required,requiredIf, maxLength} from 'vuelidate/lib/validators'
import { ModelSelect } from "vue-search-select";
import { faPlus, faTimes } from '@fortawesome/free-solid-svg-icons'


export default {
  components: { Datepicker,doughnutChart ,ModelSelect },
  props: {     
    
  },

  data: () => ({
    name: '',    
    createProjectRequestButtonClicked: false,
    faTimes,
    faPlus,
    validationErrorMessages: Constants.validationErrorMessages
  }),

   validations: {
    name: {
      required,
      maxLength: maxLength(50)
    }    
  }, 

  computed: {
    ...mapState({
      currentView: state => state.currentView,
      projects: state => state.projects,
      availableUsers: state => state.availableUsers,
    })
    
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
    
    closeCreateProjectRequestModal () {
      this.$emit('closeCreateProjectRequestModal',false)      
      this.createProjectRequestButtonClicked = false
    },
    getCurrentStatus(){
       let currentParam = this.$route.params.status    
       let status = Constants.project.slug[currentParam]
       return status
    },
    createProjectRequest () {
      this.$v.$touch()
      this.createProjectRequestButtonClicked = true
      if (!this.$v.$invalid) {
          this.toggleLoading(true)          
          axios.post('data/request/'+Constants.userRequests.resource[0]+'/'+Constants.userRequests.request[Constants.userRequests.resource[0]][1], {
                resource_id: null,
                request_data: {'name': this.name, 'requestCreatedFrom' : 'projects'}, 
                token: Laravel.csrfToken
            })
            .then((response) => {  
                this.showNotification({type: response.data.status, message: response.data.message})
                this.closeCreateProjectRequestModal();
                this.emtpyData()
                this.toggleLoading(false)
            })
            .catch((error) => {              
                this.showNotification({type: error.response.data.status, message: error.response.data.message})
                this.toggleLoading(false)
          })
        
      }    
    },
    displayErrorMessage(value){
        if (!value && this.createProjectRequestButtonClicked) {
            return true
        }else{
            return false
        }
    },
    emtpyData(){
        this.name = ''        
    }

  
  },
  mounted() {
      
       
  }
 
}
</script>
