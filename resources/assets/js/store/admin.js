import router from '../router/index'

export default {
  state: {
    loading: false,
    users: [],
    listing: [],    // keep same data as reIntialize function
    summary: {  
      items : [],
      fields : [],
      isBusy : true
    },
    logs : {
      activity : {        
        filters: Object.assign({}, Constants.logs.activity.filters), 
        data : {}
      }
    },
    miscellaneous: { // will be reintialized when ever admin-reports page is loaded or unloaded So keep the params same as reIntializeAdminReports
      listing: {
        items: []
      },
      filter:{
        params: {}        
      },
      busy: {
        listing: true, // First Tab
        userDealsModel: true
      },     
      activeTab: {
        tabName: Constants.adminReportsTabs[0],
        tabIndex: 0 // default for summary tab
      },
      reportsLoading: true,      
      usersLoading: true,
      logs :{
        activityLogsLoading: true
      }
    }
    
  },

  mutations: {    
    getUsers (state, users) {
      state.users = users
    },
    getReportsListingDataForAdmin (state, listing){
      state.listing = listing
    },
    getUpdatedChartData(state,chartData){
      state.listing.chartData = chartData
    },
    changeSummaryBusyState(state,isBusy = true){
      state.summary.isBusy = isBusy
    },
    updateDealsDataForAdmin(state,listing = []){
      state.listing.listing = listing
    },
    addDataInMiscellaneousDeals(state,data = []){
      state.miscellaneous.listing.items = data
    },
    updateMiscellaneousFilterParams(state,data = {}){
      state.miscellaneous.filter.params = data
    },
    updateMiscellaneousBusyState(state,data){
      state.miscellaneous.busy = data
    },
    getReportsSummaryDataForAdmin (state, summary){
      state.summary = summary
    },     
    updateMiscellaneousActiveTab (state, data){
      state.miscellaneous.activeTab = data
    },
    updateMiscellaneousReportsLoading (state, isLoading){
      state.miscellaneous.reportsLoading = isLoading
    },    
    updateMiscellaneousUsersLoading (state, isLoading){
      state.miscellaneous.usersLoading = isLoading
    },
    setMiscellaneousActivityLogsLoading (state, isLoading){
      state.miscellaneous.logs.activityLogsLoading = isLoading
    },
    setActivityLogs (state, logs){
      state.logs.activity.data = logs
    },
    setActivityLogsFilters (state, filtersParams){
      state.logs.activity.filters.dateRange = filtersParams.dateRange === undefined ? state.logs.activity.filters.dateRange : filtersParams.dateRange
      state.logs.activity.filters.user    = filtersParams.user === undefined ? state.logs.activity.filters.user : filtersParams.user
      state.logs.activity.filters.page      = filtersParams.page === undefined ? state.logs.activity.filters.page : filtersParams.page
    },
    reIntializeAdminReports(state){
      state.listing = []
      state.summary = { items : [], fields : [], isBusy : true }
      state.miscellaneous = { listing: { items: [] }, filter:{ params: {} }, busy: { listing: true, userDealsModel: true }, activeTab: { tabName: Constants.adminReportsTabs[0], tabIndex: 0  }, reportsLoading: true, usersLoading: true, logs : { activityLogsLoading: true } }
    } 
  },

  actions: {
    getUsers ({ commit },usersParams = null) {
      usersParams = usersParams == null ?  [] : usersParams
      commit('toggleLoading', true)
      commit('updateMiscellaneousUsersLoading', true)
      axios.get('data/users',{ params: usersParams}).then((response) => {        
          if (response.data.status === 'success') {
            commit('getUsers', response.data.users)
            commit('updateMiscellaneousUsersLoading', false)            
          }
          if (response.data.status == 'error') {
            commit('getUsers', response.data.users)
            commit('updateMiscellaneousUsersLoading', false)            
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          }
          commit('toggleLoading', false)
            
        })
        .catch((error) => {          
          commit('toggleLoading', false)
          commit('updateMiscellaneousUsersLoading', false)          
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
          this.dispatch('getErrorResponseAccordingToError', error)
        })
    },

    getReportsListingDataForAdmin({ commit },reportsParams) {
      
      // Handle if Deals Does not Have Request Param

      if(!reportsParams.request){
          reportsParams.request = null
      }      
      commit('updateMiscellaneousBusyState', { listing: true, userDealsModel: this.state.admin.miscellaneous.busy.userDealsModel})
      
      commit('toggleLoading', true)
      axios.get('data/admin/report/deals/'+reportsParams.type,{ params: reportsParams.request}).then((response) => {
            commit('toggleLoading', false)
          if (response.data.status === 'success') {  
            if (response.data.listing.items.length === 0) {
              commit('getReportsSummaryDataForAdmin', {items : [], fields : [], isBusy : true})
            }          
            commit('getReportsListingDataForAdmin', response.data.listing)
            commit('updateMiscellaneousBusyState', { listing: false, userDealsModel: this.state.admin.miscellaneous.busy.userDealsModel})
           }
           
          if (response.data.status == 'error') {
            commit('getReportsListingDataForAdmin', response.data.listing)
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          }          
          commit('updateMiscellaneousReportsLoading', false)   
        })
        .catch((error) => {
            commit('updateMiscellaneousReportsLoading', false)
            commit('toggleLoading', false)
            this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
            this.dispatch('getErrorResponseAccordingToError', error)
        })

    },

    getReportsSummaryDataForAdmin({ commit },reportsParams) {
      
      // Handle if Deals Does not Have Request Param
      
      if(!reportsParams.request){
          reportsParams.request = null
      }
      commit('changeSummaryBusyState', true)
      commit('toggleLoading', true)
      axios.get('data/admin/report/deals/'+reportsParams.type+'/summary/data',{ params: reportsParams.request}).then((response) => {
            commit('toggleLoading', false)
          if (response.data.status === 'success') {
            if (response.data.summary.items.length === 0) { // Commit empty objects
                commit('updateDealsDataForAdmin', response.data.summary.items)
                commit('updateMiscellaneousActiveTab', { tabName: Constants.adminReportsTabs[0], tabIndex: 0 })  // default for summary tab 
                commit('getUpdatedChartData', {})                
            }
            
            commit('getReportsSummaryDataForAdmin', response.data.summary)
            commit('getUpdatedChartData',response.data.summary.chartData)
            
          }
          if (response.data.status == 'error') {
            commit('getReportsSummaryDataForAdmin', response.data.summary)
            
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          }
          commit('updateMiscellaneousReportsLoading', false)
            
        })
        .catch((error) => {
            commit('updateMiscellaneousReportsLoading', false)
            commit('toggleLoading', false)
            this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
            this.dispatch('getErrorResponseAccordingToError', error)
        })

    },
    getActivityLogs ({ commit, state },params = null) {   
      commit('toggleLoading', true)
      commit('setMiscellaneousActivityLogsLoading', true)
      if (params != null) {
            commit('setActivityLogsFilters',params)        
      }
      axios.post('data/logs/activity',state.logs.activity.filters).then((response) => {        
          if (response.data.status === 'success') {            
            commit('setActivityLogs', response.data.activityLog) } 
            commit('setMiscellaneousActivityLogsLoading', false)        
            commit('toggleLoading', false)
        })
        .catch((error) => {          
            commit('toggleLoading', false)
            commit('setMiscellaneousActivityLogsLoading', false)          
            this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
            this.dispatch('getErrorResponseAccordingToError', error)
        })
    },

    updateMiscellaneousFilterParams({ commit },parmas) {
        commit('updateMiscellaneousFilterParams', parmas)
    },
    updateMiscellaneousBusyState({ commit },currentState) {
      commit('updateMiscellaneousBusyState', currentState)
    },   
    updateMiscellaneousActiveTab({commit},activeTab){
      commit('updateMiscellaneousActiveTab', activeTab)
    },
    reIntializeAdminReports({commit}){
      commit('reIntializeAdminReports')
    },
    updateMiscellaneousReportsLoading({commit},data){      
      commit('updateMiscellaneousReportsLoading',data)
    }       

  },
  getters: { 
    adminReportsLoading: (state) => (data) => {
        return state.miscellaneous.reportsLoading
    }
  }

}
