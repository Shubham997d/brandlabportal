<template>
<div id="tools-menu" class="pt-4 pb-2 bg-white shadow text-gray-600 sticky tab-sticky-top z-20">
  <div class="mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl flex">
    <div v-if="authenticated" @click="activateThisTab('project_detail')"
      :class="[(active === 'project_detail') ? 'text-indigo-600 font-semibold border-indigo-500 border-b-2 pb-2 -mb-2' : 'cursor-pointer', 'text-center mr-8 flex justify-center items-center']">
      <font-awesome-icon :icon="faProjectDiagram" class="sm:hidden md:inline mr-2"></font-awesome-icon>
      <span class="hidden sm:block font-regular">{{ 'Project' | localize }}</span>
    </div>
    <div @click="activateThisTab('tasks')"
      :class="[(active === 'tasks') ? 'text-indigo-600 font-semibold border-indigo-500 border-b-2 pb-2 -mb-2' : 'cursor-pointer', 'text-center mr-8 flex justify-center items-center']">
      <font-awesome-icon :icon="faTasks" class="sm:hidden md:inline mr-2"></font-awesome-icon>
      <span class="hidden sm:block font-regular">{{ 'Tasks' | localize }}</span>
    </div>

  </div>
</div>
</template>

<script>
import { mapState } from 'vuex'

import {
  faBolt,
  faCalendarAlt,
  faCircle,
  faClipboardList,
  faComments,
  faFileAlt,
  faTasks,
  faProjectDiagram
} from '@fortawesome/free-solid-svg-icons'

export default {
  props: {
    active: {
      required: true,
      type: String
    },    
    groupId: {
      required: true
    },
    authenticated:{
      required: true
    }
  },
  data: () => ({
    faBolt,
    faCalendarAlt,
    faCircle,
    faClipboardList,
    faComments,
    faFileAlt,
    faProjectDiagram,
    faTasks,
    hasUnreadMessage: false,
    projectStatusAuth: false,

  }),
  computed: {
    displayUnreadMessageBadge () {
      return this.hasUnreadMessage &&
        !this.isTabActive('messages')
    },    
    ...mapState({
      
    })
  },
  created () { 

  },
  methods: {

    activateThisTab (tab) {
      this.updateUrl({"tool": tab})
      this.$emit('activate', tab)
    },
    isTabActive (tab) {
      return this.active === tab
    },
    onMessagePushed () {
      this.setHasUnreadMessage(!this.isTabActive('messages'))
    },
    onMessagesTabClicked () {
      this.setHasUnreadMessage(false)
      this.activateThisTab('messages')
      EventBus.$emit('clear-title-notification')
    },
    setHasUnreadMessage (flag) {
      this.hasUnreadMessage = flag
    },
    /* async getProjectStatus(update){
      
      try {
        if (update) {
          let { data } = await axios({
            url: '/project/status',
            params: {
              project_id: this.groupId,
            }
          })
          if (data.status == 'success') {               
              return data
          }
        }

        return []
      } catch (error) {
        console.error(error)
      }
    } */

  },
  mounted () {
    EventBus.$on('messagePushed', this.onMessagePushed)
  }
}
</script>
