<template>
<div class="activity-listing-pg" v-if="activityLogs.data">
  <div class="markAll-ListBtn my-4 pb-1 border-b border-gray-400">
    <h5>Activity Logs ({{activityLogs.total}})</h5>
     <div class="current-page-group"> 
              <font-awesome-icon
                  :icon="faArrowLeft"
                   class="cursor-pointer text-sm"
                  :class="checkIfFirstPage"
                  @click="getActivityLogs({'page':previousPage})"
                ></font-awesome-icon>
              <h4 class="current-page"> {{activityLogs.current_page}} </h4>
              <font-awesome-icon
                  :icon="faArrowRight"
                   class="cursor-pointer text-sm"
                  :class="checkIfLastPage"
                  @click="getActivityLogs({'page':nextPage})"
              ></font-awesome-icon>
      </div>    
  </div>
<div class="self-center relative notification-dropdown notification-list">
  <div class="notification-group" v-if="activityLogs.data.length > 0">
    <div class="h-128 overflow-y-auto">
      <div class="flex flex-row items-center text-gray-700 cursor-pointer border-b actvity-list" href="#" v-for="(activity,index) in activityLogs.data" :key="index">
        <div class="self-start my-2 activity-user">
          <img class="w-10 h-10 rounded-full mr-2" :src="'/'+activity.data.subject.avatar">
        </div>
         <div class="self-start my-2 activity-group">
                <span v-if="activity.group_type"> {{ activity.group_type }}</span>
                <span v-else>Not-Available</span>
        </div>
        <div class="self-start my-2 activity-action">
          <div>
              <span v-if="activity.action_name">{{activity.action_name}}</span>
              <span v-else>Unknown</span>
          </div>
            <div class="py-1 text-xs"> 
            {{ activity.date }}
          </div>
        </div>
        <div class="self-start my-2 activity-content">
                <span v-if="activity.data.activity_content" v-html="activity.data.activity_content"></span>
                <span v-else>Not-Available</span>                 
        </div>    
      </div>      
    </div>    
     <span class="block border-t"></span>
      <div class="cs-pagination"> 
              <Pagination :data="activityLogs" :limit=2 :align="'center'" @pagination-change-page="getActivityLogsData" />
      </div>      
  </div>
</div>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import LaravelVuePagination from 'laravel-vue-pagination';
import { faArrowRight, faArrowLeft } from "@fortawesome/free-solid-svg-icons";
export default {  
  components: {
    'Pagination': LaravelVuePagination
  },
  data: () => ({
    faArrowRight,
    faArrowLeft
  }),
  computed: {
    ...mapState({
      activityLogs: state => state.admin.logs.activity.data,
      filters: state => state.admin.logs.activity.filters
    }),
    nextPage(){
         return this.activityLogs.next_page_url != null ?  parseInt(new URL(this.activityLogs.next_page_url).searchParams.get("page")) : null
    },
    checkIfLastPage(){
      return this.activityLogs.last_page === this.activityLogs.current_page ?  'btn-disabled' : '';  // add disabled class depending on button
    }, 
    checkIfFirstPage(){
      return this.activityLogs.current_page === 1 ?  'btn-disabled' : '';
    },
    previousPage(){
      return this.activityLogs.current_page - 1
    } 
  },
  methods: {
      ...mapActions([
        'getActivityLogs'
      ]),      
      getActivityLogsData(page = 1){
            this.getActivityLogs({'page':page})
            window.scrollTo({ top: 0, behavior: 'smooth' }); // scroll to top when any change
      }
  }
}
</script>
