import router from '../router/index'
import Constants from '../constants/constants.js'

export default {
    state: {
        loading: false,
        deals: [],
        groupType: '',
        groupId: '',
        deal: null,
        searchDeals: null,
        users: null,
        categories: { 
                      original: [],
                      forNormalDropdown : [
                        { id: 0, name: "All Categories" }
                      ],                      
                    },
        filters: {  // Default Filters for deals listing pages
            deals: {
                dealOwner : Constants.dealslisting.filters.deals.default.dealOwner,  // everyone
                dealColor : Constants.dealslisting.filters.deals.default.dealColor, // default
                dealCategory : Constants.dealslisting.filters.deals.default.dealCategory,  // default is 0
                dealDefaultPage : Constants.dealslisting.filters.deals.default.dealDefaultPage, // params for pagination                
                dealCurrentPage : Constants.dealslisting.filters.deals.default.dealDefaultPage
            }
        }
    },

    mutations: {
        setGroup(state, group) {
            state.groupType = group.type
            state.groupId = group.id
        },
        getSearchDeals(state, dealsParam) {
            state.searchDeals = dealsParam
        },
        getDeals(state, dealsParam) {
            if (dealsParam.loadMore === true) {
                state.deals.push(...dealsParam.deals) // for scroll loading 
            } else { 
                state.deals = dealsParam.deals // for pagination
            }
        },
        getDeal(state, deal) {
            state.deal = deal
        },
        getDealUsers(state, users) {
            state.users = users
        },      
        getDealsCurrentPage(state, currentPage){
            state.filters.deals.dealCurrentPage = typeof currentPage != 'undefined' && currentPage != null ?  currentPage : state.deal.filters.deals.dealDefaultPage
        },
        updateDealFilters(state, params){ // set default values if not exists
            state.filters.deals.dealOwner = typeof params.dealOwner != 'undefined' && params.dealOwner != null ?  params.dealOwner : Constants.dealslisting.filters.deals.default.dealOwner
            state.filters.deals.dealColor = typeof params.dealColor != 'undefined' && params.dealColor != null ?  params.dealColor : Constants.dealslisting.filters.deals.default.dealColor
            state.filters.deals.dealCategory = typeof params.dealCategory != 'undefined' && params.dealCategory != null ?  params.dealCategory : Constants.dealslisting.filters.deals.default.dealCategory            
        } ,
        getAvailableDealCategories(state, availableCategories){           
            state.categories.original = availableCategories
            if (state.categories.forNormalDropdown.length === 1 ) { // run only first time
                state.categories.forNormalDropdown = [...state.categories.forNormalDropdown,...availableCategories]                
            }
            // state.categories.forMultiSelectDropdown = availableCategories.map(function(category) {
            //     return { value : category.id, text : category.name } // rename keys
            //  })
        }       
    },

    actions: {
        setGroup({ commit }, group) {
            commit('setGroup', group)
        },
        getSearchDeals({ commit }, dealsParam) {
            axios.post('data/deals/search', { data: dealsParam }).then((response) => {
                    
                if (response.data.status === 'success') {                    
                    dealsParam = response.data.all_org_detail;
                    commit('getSearchDeals', dealsParam);
                }

                if (response.data.status == 'error') {
                    commit('getSearchDeals', response.data)
                    this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
                }
            }).catch((error) => {                
                this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                this.dispatch('getErrorResponseAccordingToError', error)
            })
        },
        getDeals({ commit }, dealsParam) {
            commit('toggleLoading', true);
            var pageNum = typeof dealsParam.goToPage != 'undefined' && dealsParam.goToPage != null ?  dealsParam.goToPage : this.state.deal.filters.deals.dealCurrentPage
            axios.post('data/deals?page='+pageNum, { data: dealsParam }).then((response) => {
                                       
                    if (response.data.status === 'success') {
                        for (let i = 1; i <= 5; i++) { // Keep Deal Stages Same 
                            let ind = response.data.all_steps_data.data.findIndex((x) => parseInt(x.pipeline_stage) == parseInt(i));
                            if (ind == -1) {
                                response.data.all_steps_data.data.splice(i - 1, 0, { get_data_by_stages: [], pipeline_stage: i });
                            }
                        }
                        dealsParam.deals = response.data
                        commit('getDeals', dealsParam)
                        commit('getDealsCurrentPage', response.data.pagination.current_page)
                        commit('toggleLoading', false) 
                    }
                    if (response.data.status == 'error') {
                        commit('toggleLoading', false) 
                        commit('getDeals', response.data)
                        this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
                    }

                })
                .catch((error) => {         
                    commit('toggleLoading', false)
                    this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                    this.dispatch('getErrorResponseAccordingToError', error)
                })
        },
        async getDeal({ dispatch, commit, state }, dealId) {
            setTimeout(() => {
                commit('toggleLoading', true);
                axios.get(
                        'data/deals/' + dealId
                    )
                    .then((response) => {
                        if (response.data.status === 'success') {
                            commit('getDeal', response.data.data)
                            commit('setResourceName', response.data.data.organization)
                            commit('toggleLoading', false)
                        }
                    })
                    .catch((error) => {                        
                        commit('toggleLoading', false)
                        this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                        this.dispatch('getErrorResponseAccordingToError', error)                        
                    })

                dispatch('setCurrentView', 'deal')
            });
        },
        async getDealUsers({ dispatch, commit, state }) {
            axios.post('data/deals/users').then((response) => {
                    if (response.data.status === 'success') {
                        commit('getDealUsers', response.data.users)
                    }
                    if (response.data.status == 'error') {
                        commit('getDeals', response.data)
                        this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
                    }
                })
                .catch((error) => {
                    this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                })


        },
        async getAvailableDealCategories({ dispatch, commit, state }) {
            axios.post('data/deals/categories').then((response) => {
                    if (response.data.status === 'success') {
                        commit('getAvailableDealCategories', response.data.categories)
                    }
                    if (response.data.status == 'error') {
                        this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
                    }
                })
                .catch((error) => {
                    this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                })


        },

       
        async getDealsFiltersData({ dispatch, commit, dataNeeded = [] }) {
            axios.get('data/filters/deals-frontend', {params: dataNeeded}).then((response) => {
                    if (response.data.status === 'success') {
                        // get all if dataNeeded length is 0
                        if (dataNeeded.find(element => element == 'users') || dataNeeded.length == 0) {
                            commit('getDealUsers', response.data.users)  // deal users                            
                        }
                        if (dataNeeded.find(element => element == 'categories') || dataNeeded.length == 0) {
                            commit('getAvailableDealCategories', response.data.categories) // deals categories                            
                        }
                    }
                    if (response.data.status == 'error') {
                        this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
                    }
                })
                .catch((error) => {
                    this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
                })


        },
        updateDealFilters({ commit }, params){
                commit('updateDealFilters', params)
        },
        updateDealCurrentPage({ commit }, currentPage){
            commit('getDealsCurrentPage', currentPage
        )
    }        

    }
}