<template>
<div>
  <div class="absolute container md:mx-auto w-full md:max-w-2xl bg-gray-100 rounded shadow-lg member-custom custom-modal" style="top: 10vh;left: 0;right: 0;">
    <div class="m-auto flex-col flex">
        <label class="block uppercase tracking-wide text-gray-600 text-xs font-bold text-center text-lg p-4 rounded" for="user">
          {{'Members List' | localize }}
        </label>

      <ul v-for="(member, index) in members" :key="member.id" class="list-reset">
        <li class="p-4 py-6 hover:bg-blue-100 bg-white flex flex-row items-start justify-between">
          <div class="flex flex-row items-start">
            <a :href="'/user/' + member.username"  class="no-underline text-gray-800 text-lg">
              <img :src="generateUrl(member.avatar)" class="rounded-full w-8 h-8 mr-4 align-middle" :alt="'profile pic of ' + member.name">
            </a>
            <span class="text-xl">{{ member.username }}</span>
          </div>
          <button @click="removeMember(index, member)" class="remove-member" title="delete">
            <font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm"></font-awesome-icon>
          </button>
        </li>
      </ul>

      <div class="flex flex-row-reverse p-4 rounded-b">
        <button @click="closeModal" class="hover:font-bold main-button">{{'Close' | localize }}</button>
      </div>
    </div>
  </div>
  <div @click="closeModal" class="h-screen w-screen fixed inset-0 bg-gray-900 opacity-25 z-30"></div>
</div>
</template>

<script>
import { mapActions } from 'vuex'
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons/faTrashAlt'

export default {
  props: {
    resourceType: {
      type: String,
      required: true
    },
    resourceId: {
      type: Number,
      required: true
    },
    members: {
      type: Array,
      required: true
    }
  },

  data: () => ({
    faTrashAlt
  }),

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification'
    ]),
    closeModal () {
      this.closeComponent()
    },
    removeMember (index, member) {
      if(member.user_id == undefined) {
           member.user_id =  member.id
      }
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

            axios.delete('/data/members/', {
              params: {
                user_id: member.user_id, 
                group_type: this.resourceType, 
                group_id: this.resourceId
              }
            })
              .then((response) => {
                if(response.data.status == "success"){
                  this.members.splice(index, 1)
                  this.$emit('member-removed')
                  this.closeModal()
                  this.showNotification({type: response.data.status, message: response.data.message})                  
                }
                if(response.data.status == "error"){                
                  this.closeModal();
                  this.showNotification({type: response.data.status, message: response.data.message})
                }
              })
              .catch((error) => {
                console.log(error)
                // this.showNotification({type: error.response.data.status, message: error.response.data.message})
              })

        }})
    }
    
  }
 
}
</script>
