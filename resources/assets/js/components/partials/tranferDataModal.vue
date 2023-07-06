<template>
<div>
   <div @click="closeModal" class="fixed inset-0 opacity-25 bg-black z-50"></div>
      <div class="fixed inset-x-0 lg:max-w-lg z-50 mx-auto rounded project-detail-model tranfer-modal">

        <div class="bg-white p-6 rounded-t-lg">
        <div class="on-delete-user-form">
          <h3 class="cs-modal-heading">{{ 'Change Project Managers & Deal owners for '+user.username | localize }}</h3>
          <b-form @submit.prevent="tranferData" autocomplete="off" @reset="reset" v-if="noTranferableDataAvailable === false">
            <b-row class="tranfer-projects" :class="[user.hasDeals == false ? 'no-deals' : '']">
                <b-form-group label-cols="12" :label="project.project_name" class="tranfer-project-managers" :label-for="project.project_name" v-for="(project,index) in  usersMangerInProjects"  :key="project.id">
                  <model-select
                          :options="availableUsers"
                          v-model="form.tranferProjectManagerTo[index].project_manager_id"
                          placeholder="Select project manager"
                          class="form-control tranfer-dropdown"
                          option-value="value"
                          option-text="text"
                  ></model-select> 
                </b-form-group>
             </b-row>
             <b-row class="tranfer-deals" v-if="user.hasDeals == true">
                <b-form-group label-cols="12" label="Transfer all deals" class="tranfer-deals" :label-for="'user-dropdown-for-all-deals'">
                <model-select
                        :options="availableUsers"
                        v-model="form.tranferDealsTo"
                        placeholder="Select user"
                        class="form-control tranfer-dropdown"
                        option-value="value"
                        option-text="text"
                ></model-select> 
                </b-form-group>
             </b-row>
            <div class="custom-modal-footer">
              <b-button
                id="on-delete-user-close"
                class="border bg-white py-2 px-3 mr-4 rounded"
                block
                @click="closeModal"
                >Cancel</b-button
              >
              <b-button
                type="submit"
                id="on-delete-user-submit"
                class="bg-indigo-400 text-white font-medium py-2 px-3 rounded"
                variant="success"
                >Delete</b-button
              >
            </div>
          </b-form>
          <h3 v-else>{{ 'No Transferable Data available'}}</h3>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import Datepicker from 'vuejs-datepicker'
import { ModelSelect } from "vue-search-select";


export default {
  components: { Datepicker ,ModelSelect },    
  data: () => ({
      form : {
        tranferProjectManagerTo : [],
        tranferDealsTo : null,
        tranferFromUser: {}
      },
      formDataLoaded : false,
      submitButtonClicked: false,
      usersMangerInProjects : []
  }),
  props : {
    user : {
      required : true,
      type : Object,
    }
  },
  computed: {
    ...mapState({
      availableUsers: state => state.availableUsers,
    }),    

    noTranferableDataAvailable() {
          return this.form.tranferProjectManagerTo.length == 0 && this.user.hasDeals == false
    }
    
  },
  created () {     
        this.loadFormData()
  },

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification',
      'setCurrentComponent',
      'toggleLoading',
      'getAvailableUsers',
      'getUsers'    
    ]),
    closeModal () {
      this.closeComponent()
      this.submitButtonClicked = false
    },
    displayErrorMessage(value){
        if (!value && this.submitButtonClicked) {
            return true
        }else{
            return false
        }
    }, 
    tranferData (event){        
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
                  this.form.tranferFromUser = this.user
                  axios.post('data/transfer-data', this.form)
                  .then((response) => {          
                    this.handleResponse (response)
                    this.toggleLoading(false)
                  })
                  .catch((error) => {
                    this.showNotification({type: error.response.data.status, message: error.response.data.message})
                    this.toggleLoading(false)
                  })
              }
        })
    },
    
    reset(){

    }, 

    loadFormData(){
          this.user.user_project_duration.find((element) => { 
                if (element.is_project_manager === true) {
                    let index = this.usersMangerInProjects.findIndex(x => x.project_id == element.project_id ); 
                    if(index === -1){
                        this.usersMangerInProjects.push(element)
                        this.form.tranferProjectManagerTo.push({'project_id': element.project_id , 'project_manager_id': element.user_id })
                    }
                }
           } )            
          this.form.tranferDealsTo = this.user.id         
          this.form.tranferProjectManagerTo.length == 0 && this.user.hasDeals == false ? this.deleteUser(this.user) : null // do nothing
    },
    deleteUser(user){
      this.closeComponent()
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
                  axios.post('data/users/delete/'+ user.id, { })
                    .then((response) => {            
                      this.handleResponse (response)
                      this.toggleLoading(false)
                    })
                    .catch((error) => {   
                      this.toggleLoading(false)     
                      this.showNotification({type: error.response.data.status, message: error.response.data.message})
                    })
            }
        })
    },
    handleResponse (response){
            if (response.data.status === 'success') {
                this.getAvailableUsers()
                this.getUsers({"withDeleted" : false}).then((data) => {        
                    this.showNotification({type: response.data.status, message: response.data.message})
                    this.closeComponent()
                })
            }
            if (response.data.status === 'error') {              
              this.showNotification({type: response.data.status, message: response.data.message})
            }
    } 
   
  
  },
  mounted() {
     
       
  }
 
}
</script>
