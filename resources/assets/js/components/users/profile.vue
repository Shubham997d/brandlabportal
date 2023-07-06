<template>
  <div class="profile-content" v-if="Object.keys(user).length > 1">
    <template>
      <div class="flex flex-col md:mx-auto my-8 max-w-xl">
        <div class="bg-white rounded shadow flex flex-row text-center text-gray-800">
          <div @click="activateTab('profile')" class="w-1/2 p-4" :class="[(activeTab === 'profile') ? 'text-white bg-indigo-400 rounded-l' : 'cursor-pointer']">
            {{ 'Profile' | localize }}
          </div>
          <div @click="activateTab('account')" class="w-1/2 p-4" :class="[(activeTab === 'account') ? 'text-white bg-indigo-400 rounded-r' : 'cursor-pointer']">
            {{ 'Account' | localize }}
          </div>
        </div>
        <account v-if="activeTab === 'account'" :user="user" ></account>
        <own v-if="activeTab === 'profile'" :user="user"></own>
      </div>
    </template>
  </div>
</template>

<script>
import Account from './account'
import Own from './own'
import { mapActions } from 'vuex'
export default {
  components: {Account, Own},
  data: () => ({
     activeTab: 'profile',
     user: {}
  }),
  created () {
    this.user = authUser
    this.showLoadingToggle()
  },
  methods: {
    ...mapActions([
      'toggleLoading',
    ]),
    activateTab (tab) {
      this.activeTab = tab
    },
    showLoadingToggle() {
      this.toggleLoading(true)
      this.toggleLoading(false)
    }

  }
}
</script>

