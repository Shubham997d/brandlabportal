<template>

<div class="container md:mx-auto md:mb-6 w-full xl:max-w-6xl lg:max-w-5xl md:max-w-3xl md:border-0">
    
    <div v-if="activityLogsLoading === false">
      <filters></filters>
      <activity></activity>
    </div>   
    <div v-else class="flex flex-col items-center pt-8 report-pg-loading">
        <div class="pb-4"><p>{{'Logs are being loaded' | localize }}</p></div>
        <img src="/image/tasks.svg" alt="Report" class="w-96">
    </div>
</div>
</template>

<script>

import { mapActions,mapState } from 'vuex'
import filters from './filters.vue'
import activity from './activity.vue'


export default {   
  name: 'admin.logs',
  data: () => ({
  }), 
  components: {
    activity,
    filters
  },
  created (){
        
  },
  computed: {
    ...mapState({
      activityLogsLoading: state => state.admin.miscellaneous.logs.activityLogsLoading
    }),   
  
  },
  methods: {
    ...mapActions([
      'getActivityLogs'
    ]),
    
  },
  mounted () { 
  },
  destroyed() {      
  },
}
</script>
