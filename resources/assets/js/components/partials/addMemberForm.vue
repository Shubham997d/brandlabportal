<template>
<div>
  <div class="absolute container md:mx-auto md:w-1/3 bg-white rounded shadow-lg custom-modal add-mem-form" style="top: 20vh;left: 0;right: 0;" v-if="availableUsers.length > 0">
    <div class="add-mem-select">
      <!-- <div class="p-4"> -->
        <label class="block uppercase tracking-wide text-xs font-bold text-center text-lg mb-4" for="user">
          {{'Add Member' | localize }}
        </label>
        <div class="flex flex-row items-center">
          <select v-model="newMember" class="w-5/6 block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-800 py-3 px-4 pr-8 rounded" id="user">
            <option selected disabled class="my-2 text-sm" :value="'Select User to Add'">{{'Select User to Add' | localize }}</option>
            <template v-for="user in availableUsers">              
              <option :value="user.value" :key="user.value" class="my-2 text-sm">{{ user.text }}</option>
            </template>
          </select>
          <font-awesome-icon :icon="faChevronDown"
            class="w-1/6 pointer-events-none flex items-center text-gray-800 -ml-8">
          </font-awesome-icon>  
        </div>
      <!-- </div> -->
    </div>
    <div class="flex flex-row justify-between">
      <button @click="closeAddMemberForm" class="text-red-lighter hover:font-bold hover:text-red-400 main-button">{{'Cancel' | localize }}</button>
      <button @click="addMember" class="bg-indigo-400 text-white font-medium hover:bg-indigo-600 main-button rounded">
        <template v-if="loading">
          <font-awesome-icon :icon="faSpinner" spin></font-awesome-icon>
        </template>
        {{'Add' | localize }}
      </button>
    </div>
  </div>
  <div class="h-screen w-screen fixed inset-0 bg-gray-900 opacity-25 z-40"></div>
</div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import {
  faChevronDown,
  faSpinner
} from '@fortawesome/free-solid-svg-icons'

export default {
  props: {
    resourceType: {
      type: String,
      required: true
    },
    resource: {
      type: Object,
      required: true
    },
  },

  data: () => ({
    newMember: null,
    loading: false,
    faChevronDown,
    faSpinner
  }),

  computed: {
     ...mapState({
      availableUsers: state => state.availableUsers,
    })
  },

  created () {   
  },

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification'
    ]), 
    addMember () {
      this.loading = true
      axios.post('/data/members', {
        user_id: this.newMember,
        group_type: this.resourceType,
        group_id: this.resource.id
      }).then((response) => {
          if (response.data.status === 'success') {
            this.loading = false
            this.$emit('addMember', response.data)
          }
        })
        .catch((error) => {                    
          this.loading = false
          this.$emit('addMember', { status: error.response.data.status, message: error.response.data.message })
        })
    },
    closeAddMemberForm () {
      this.closeComponent()
    }
  }
}
</script>
