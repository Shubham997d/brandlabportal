import router from '../router/index'

export default {
  state: {
    loading: false,
    project: null    
  },

  mutations: {
    getProject (state, project) {
      state.project = project
    },
    updateProjectSettings (state, settings) {
      state.project.settings = settings
    }   
  },

  actions: {
    async getProject ({ dispatch, commit }, projectId) {
      commit('toggleLoading', true)
      await axios.get(
        'data/projects/' + projectId
      )
        .then((response) => {
           if (response.data.status === 'success') {
            commit('getProject', response.data.project)
            commit('setResourceName', response.data.project.name)
            dispatch('getMembers', response.data.project.members)
            // dispatch('selectCycle', response.data.current_cycle)
            // dispatch('getTags', response.data.project.tags)
            commit('toggleLoading', false)
          }
        })
        .catch((error) => {          
          commit('toggleLoading', false)
          if(error.response.status == 404){ // Updated error message when error status is 404 for project
            this.dispatch('getErrorResponseAccordingToError', error)
              this.dispatch('showNotification', { type: 'error', message: 'Project not found' })
            
          }else{
              this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
          } 
          this.dispatch('getErrorResponseAccordingToError', error)
          
        })

      dispatch('setCurrentView', 'project')
    },
    updateProjectSettings ({ commit }, settings) {
      commit('updateProjectSettings', settings)
    }
   
  }
}
