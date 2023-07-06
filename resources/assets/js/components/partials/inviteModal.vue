<template>
<div>
<b-modal id="invite-user-modal" hide-footer  size="sm" title="Invite user" ref="invite-user-modal">
        <b-form @submit.prevent="onInviteUser">
            <b-form-group label="Email address">
              <b-form-input v-model="inviteUserForm.emailAddress" :type="'email'"></b-form-input>
                <div class="error" v-if=" displayErrorMessageUploadDeal($v.inviteUserForm.emailAddress.required) " > {{ validationErrorMessages.required }}. </div>
                <div class="error" v-if=" displayErrorMessageUploadDeal($v.inviteUserForm.emailAddress.email) " > {{ validationErrorMessages.email }}. </div>
            </b-form-group>
            <div class="custom-modal-footer">
              <b-button
                id="invite-user-close"
                class="border bg-white py-2 px-3 mr-4 rounded"
                block
                @click="$bvModal.hide('invite-user-modal')"
                >Cancel</b-button
              >
              <b-button
                type="submit"
                id="invite-user-submit"
                class="bg-indigo-400 text-white font-medium py-2 px-3 rounded"
                variant="success"
                >Invite</b-button
              >
            </div>
      </b-form>
</b-modal>
</div>
</template>

<script>
import { mapActions } from 'vuex'
import {required, email} from 'vuelidate/lib/validators'
export default {
  data: () => ({
      inviteUserForm: {
        emailAddress : null
      },
      inviteUserSubmitBtnClicked : false,
      validationErrorMessages: Constants.validationErrorMessages,
  }),
  validations: {
    inviteUserForm:{
      emailAddress: {
          required,
          email
        },
    }
  },
  mounted () {
    
  },

  methods: {
    ...mapActions([
      'showNotification',
      'toggleLoading'
    ]),
    displayErrorMessageUploadDeal(value) {
      if (!value && this.inviteUserSubmitBtnClicked) {
        return true
      } else {
        return false
      }
    },

    onInviteUser(event){
      this.inviteUserSubmitBtnClicked = true
        if (this.$v.inviteUserForm.$invalid == true) {
             return false
        } else {
      $('#invite-user-submit').attr('disabled',true)
            this.toggleLoading(true) 
            this.inviteUserSubmitBtnClicked = false            
            axios.post("/data/register/invite",this.inviteUserForm)
                .then((response) => {
                  this.toggleLoading(false)                  
                  if (response.data.status === "success") {                 
                    $('#invite-user-submit').attr('disabled',false)
                    this.$bvModal.hide('invite-user-modal')
                    this.showNotification({
                      type: response.data.status,
                      message: response.data.message,
                    })                    
                  }
                  
                })
                .catch((error) => {
                      $('#invite-user-submit').attr('disabled',false)
                      this.toggleLoading(false)          
                      this.showNotification({
                        type: error.response.data.status,
                        message: error.response.data.message,
                      })
                })
        
      }
    },

  }    
}
</script>
