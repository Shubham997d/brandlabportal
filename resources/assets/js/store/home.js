import Vuex from 'vuex'
import Vue from 'vue'
import router from '../router/index'
import project from './project'
import team from './team'
import chat from './chat'
import office from './office'
import admin from './admin'
import deal from './deal'
import cycle from './modules/cycle'
import notification from './modules/notification'
import dropdown from './modules/dropdown'
import timer from './modules/timer'
import functions from '../common/functions.js'

window.Vue.use(Vuex)
export default new Vuex.Store({
  modules: {
    project,
    office,
    team,
    chat,
    cycle,
    notification,
    dropdown,
    timer,
    deal,
    admin
  },

  state: {
    // currentWork, // eslint-disable-line
    projects: [],
    teams: [],
    offices: [],
    currentView: 'home',
    resourceName: '', // currently showed project/team/office name
    groupType: '',
    groupId: '',
    members: [],
    breadcrumb: [],
    loading: false,
    tags: [],
    salesPipelines: [],
    projectPageNum: 1,
    projectsDataLoaded: false,
    projectLoadingStarted: false,
    projectsTotal: 0,
    projectsLastPage: '',
    isUserAdmin: {},
    roles: [],
    availableUsers: [],
    projectCategories: {
      original: [],
      projectCategoriesForRoutes: [
        { id: 0, name: "All Projects", slug: 'all-projects' } // show this at first position
      ]
    },
    notifications: {
      data: [],
      unreadNotificationsCount: 0,
      notificationDetails: [],
      hasClickableNotificationContent: false,
      nextPage: null,
      defaultPage: 1,
      lastPage: null,
      loadMore: false,
      isCurrentlyLoading: false,
      total: 0
    },
    miscellaneous: {
      loaded: {
        commonData: false
      }
    }
  },

  mutations: {
    setGroup(state, group) {
      state.groupType = group.type
      state.groupId = group.id
    },
    setProjectPageNum(state, projectPageNum) {
      state.projectPageNum = projectPageNum
    },
    setIsUserAdmin(state, isAdmin) {
      state.isUserAdmin = isAdmin
    },
    setProjectsDataLoaded(state, dataLoaded) {
      state.projectsDataLoaded = dataLoaded
    },
    setProjectsLastPage(state, projectsLastPage) {
      state.projectsLastPage = projectsLastPage
    },
    updateFrontendOnProjectsAction(state, projectParams) {  /* Perform different actions when a project is added/updated/deleted */
      var root = router.app.$root._route
      var status = Constants.project.slug[root.params.status]  /* get status value from slug */
      if (root.name == 'projects') {
        if (typeof projectParams.project != 'undefined') {
          /* Match if current project has same category and status as active project route  */
          if ((root.params.category == projectParams.project.project_categories[0].category_details[0].slug && status == projectParams.project.status) || (root.params.category == 'all-projects' && status == projectParams.project.status)) {
            if (projectParams.action == 'addProject') {
              this.commit("addProject", projectParams.project)
              state.projectsTotal += 1
            }
            if (projectParams.action == 'removeProject') {
              this.commit("removeProject", projectParams.index)
              state.projectsTotal -= 1
            }
          } else {
            if (projectParams.action == 'updateProject') {
              this.commit("removeProject", projectParams.index)
              state.projectsTotal -= 1
            }
          }
        }
      }

    },
    setProjectsTotal(state, projectsTotal) {
      state.projectsTotal = projectsTotal
    },
    setProjectLoadingStarted(state, projectLoadingStarted) {
      state.projectLoadingStarted = projectLoadingStarted
    },
    setCurrentView(state, view) {
      state.currentView = view
    },
    setAvailableUsers(state, users) {
      state.availableUsers = users
    },
    updateBreadcrumb(state, view) {
      if (!state.breadcrumb.includes(view)) {
        state.breadcrumb.splice(1, 1, view)
      }
    },
    setResourceName(state, name) {
      state.resourceName = name
    },
    getProjects(state, projectsGetParams) {
      if (projectsGetParams.loadMore === true) {
        state.projects.push(...projectsGetParams.projects)
      } else {
        state.projects = projectsGetParams.projects
      }
    },
    getSalesPipelines(state, salesPipelines) {
      state.salesPipelines = salesPipelines
    },
    getTeams(state, teams) {
      state.teams = teams
    },
    getOffices(state, offices) {
      state.offices = offices
    },
    addProject(state, project) {
      state.projects.unshift(project)
    },
    addTeam(state, team) {
      state.teams.push(team)
    },
    addOffice(state, office) {
      state.offices.push(office)
    },
    removeProject(state, index) {
      state.projects.splice(index, 1)
    },
    removeProjectForCurrentStatus(state, index) {
      state.projects.splice(index, 1)
    },
    removeTeam(state, index) {
      state.teams.splice(index, 1)
    },
    removeOffice(state, index) {
      state.offices.splice(index, 1)
    },
    toggleLoading(state, status) {
      state.loading = status
    },
    getMembers(state, members) {
      state.members = members
    },
    getTags(state, tags) {
      state.tags = tags
    },
    getRoles(state, roles) {
      state.roles = roles
    },
    reIntializeProjects(state) {
      state.projects = [],
        state.projectPageNum = 1
      state.projectsDataLoaded = false
      state.projectLoadingStarted = false
      state.projectsTotal = 0
      state.projectsLastPage = ''
    },
    updateProject(state, project) {
      var index = functions.findIndexOfMultidimensionalArray(state.projects, project.id, 'id')
      Vue.set(state.projects, index, project)
    },
    getNotifications(state, notificationsData) {

      state.notifications.isCurrentlyLoading = true
      state.notifications.total = notificationsData.total
      state.notifications.unreadNotificationsCount = notificationsData.unreadNotificationsCount
      state.notifications.nextPage = notificationsData.next_page_url != null ? parseInt(new URL(notificationsData.next_page_url).searchParams.get("page")) : notificationsData.last_page + 1
      state.notifications.lastPage = notificationsData.last_page
      if (notificationsData.loadMore === true) { /* when using loadmore feature we need to check for dulplicate ids */
        state.notifications.data = (state.notifications.data.length == 0) ? notificationsData.data : state.notifications.data
        for (let index = 0; index < notificationsData.data.length; index++) {
          var idExists = functions.findIndexOfMultidimensionalArray(state.notifications.data, notificationsData.data[index].id, 'id')
          if (idExists == false) {
            state.notifications.data.push(notificationsData.data[index])
          }
        }
        state.notifications.isCurrentlyLoading = false

      } else { /* for first time just add notifications in the array */
        state.notifications.data = []
        state.notifications.data.push(...notificationsData.data)
        state.notifications.isCurrentlyLoading = false
      }

    },
    setNotificationMarked(state, param) {
      if (param.markAll == true) { /* For mark as read all */
        for (let index = 0; index < state.notifications.data.length; index++) {
          if (typeof state.notifications.data[index].read_at == 'undefined' || state.notifications.data[index].read_at == null) {
            Vue.set(state.notifications.data[index], 'read_at', new Date)
          }
        }
        state.notifications.unreadNotificationsCount = 0
      } else {
        if (param.read == true) {   /* For mark as read */
          Vue.set(state.notifications.data[param.index], 'read_at', new Date)
          state.notifications.unreadNotificationsCount = state.notifications.unreadNotificationsCount - 1
        } else {  /* For mark as unread */
          Vue.set(state.notifications.data[param.index], 'read_at', null)
          state.notifications.unreadNotificationsCount = state.notifications.unreadNotificationsCount + 1
        }
      }
    },
    getNotificationDetails(state, params) {
      state.notifications.notificationDetails = state.notifications.data[params.index].data.extra_data
      state.notifications.hasClickableNotificationContent = state.notifications.data[params.index].data.has_clickable_notification_content
    },
    setNoficationsParams(state, params) {
      state.notifications.lastPage = params.lastPage ? params.lastPage : null
      state.notifications.nextPage = params.nextPage ? params.nextPage : null
      state.notifications.loadMore = params.loadMore ? params.loadMore : false
    },
    setNoficationsAreLoading(state, loading) {
      state.notifications.isCurrentlyLoading = loading
    },
    setProjectCategories(state, projectCategories) {
      state.projectCategories.original = projectCategories
      if (state.projectCategories.projectCategoriesForRoutes.length === 1) { // run only first time
        state.projectCategories.projectCategoriesForRoutes = [...state.projectCategories.projectCategoriesForRoutes, ...projectCategories]
      }
    },
    setLoadedDataForMiscellaneous(state, params) {
      state.miscellaneous.loaded[params.type] = params.loading
    }
  },

  actions: {
    setGroup({ commit }, group) {
      commit('setGroup', group)
    },
    setCurrentView({ commit }, view) {
      commit('setCurrentView', view)
    },
    updateBreadcrumb({ commit }, view) {
      commit('updateBreadcrumb', view)
    },
    setResourceName({ commit }, name) {
      commit('setResourceName', name)
    },
    async getProjects({ commit }, projectsGetParams) {
      if (projectsGetParams.loadMore == false) {
        commit('setProjectPageNum', 1)
        commit('setProjectsDataLoaded', false)
      }
      // Run if projectsDataLoaded is not true & current Project page greater than projecLastPage

      if ((projectsGetParams.loadMore === true && this.state.projectsDataLoaded === false && this.state.projectsLastPage >= this.state.projectPageNum) || projectsGetParams.loadMore === false) {

        var getProjectParams = { loadMore: projectsGetParams.loadMore }
        commit('toggleLoading', true)
        if (projectsGetParams.loadMore) {
          commit('setProjectLoadingStarted', true)
        }

        axios({
          url: 'data/projects/get/' + projectsGetParams.category + '/' + projectsGetParams.status,
          params: {
            page: this.state.projectPageNum,
          }
        }).then((response) => {
          if (response.data.status === 'success') {
            var getProjectsFunctionRan = false
            commit('setProjectsLastPage', response.data.projects.last_page)
            getProjectParams.projects = response.data.projects.data
            commit('setProjectsTotal', response.data.projects.total)

            // For final Page Data
            if (this.state.projectPageNum === this.state.projectsLastPage) {
              getProjectsFunctionRan = true
              commit('getProjects', getProjectParams)
              commit('setProjectsDataLoaded', true)
            } else {
              commit('setProjectsDataLoaded', false)
            }

            if (getProjectsFunctionRan === false) {

              if (this.state.projectPageNum != this.state.projectsLastPage) {
                var newPageNum = this.state.projectPageNum + 1
                commit('setProjectPageNum', newPageNum)
              }
              commit('getProjects', getProjectParams)
            }

            commit('setProjectLoadingStarted', false)
            commit('toggleLoading', false)
          }

          if (response.data.status == 'error') {
            commit('setProjectLoadingStarted', false)
            commit('toggleLoading', false)
            getProjectParams.projects = response.data.projects.data
            commit('getProjects', getProjectParams)
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          }
        })
          .catch((error) => {
            commit('setProjectLoadingStarted', false)
            commit('toggleLoading', false)
            this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
            this.dispatch('getErrorResponseAccordingToError', error)
          })
      }
    },
    getTeams({ commit }) {
      commit('toggleLoading', true)
      axios.get('teams')
        .then((response) => {
          if (response.data.status === 'success') {
            commit('toggleLoading', false)
            commit('getTeams', response.data.teams)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    getAvailableUsers({ commit }) {
      commit('toggleLoading', true)
      axios.get('data/available-users')
        .then((response) => {
          if (response.data.status === 'success') {
            commit('toggleLoading', false)
            commit('setAvailableUsers', response.data.users)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    getCommonData({ commit }) {
      commit('toggleLoading', true)
      commit('setLoadedDataForMiscellaneous', { type: 'commonData', loading: false })
      axios.get('data/common-data')
        .then((response) => {
          if (response.data.status === 'success') {
            commit('toggleLoading', false)
            commit('setAvailableUsers', response.data.users)
            commit('setProjectCategories', response.data.project_categories)
            commit('setWorkspaces', response.data.workspaces)
            this.dispatch('setChatDirectUsers', { users: response.data.users, rename: true }) // chat users data set
            commit('setLoadedDataForMiscellaneous', { type: 'commonData', loading: true })
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    getRoles({ commit }) {
      commit('toggleLoading', true)
      axios.get('/data/roles')
        .then((response) => {
          if (response.data.status === 'success') {
            commit('toggleLoading', false)
            commit('getRoles', response.data.roles)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },

    getOffices({ commit }) {
      commit('toggleLoading', true)
      axios.get('offices')
        .then((response) => {
          if (response.data.status === 'success') {
            commit('toggleLoading', false)
            commit('getOffices', response.data.offices)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    addProject({ commit }, formData) {
      commit('toggleLoading', true)
      axios.post('data/projects', {
        name: formData.name,
        project_manager_id: formData.project_manager_id,
        cost: formData.cost,
        deadline: formData.deadline,
        currency: formData.currency,
        commission_user_id: formData.commission_user_id,
        commission_user_value: formData.commission_user_value,
        project_category: formData.project_category,
        monthly_usage_fee: formData.monthly_usage_fee,
        contact_term: formData.contact_term,
        deal_id: (typeof formData.deal_id != 'undefined' || formData.deal_id != null) ? formData.deal_id : null
      })
        .then((response) => {
          if (response.data.status === 'success') {
            if (response.data.project != null) {
              commit('updateFrontendOnProjectsAction', { 'action': 'addProject', 'project': response.data.project })
            }
            commit('toggleLoading', false)
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    removeProject({ commit }, data) {
      commit('toggleLoading', true)
      axios.delete(`data/projects/${data.id}`)
        .then((response) => {
          commit('updateFrontendOnProjectsAction', { 'action': 'removeProject', 'project': this.state.projects[data.index], 'index': data.index })
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    addTeam({ commit }, formData) {
      axios.post('/teams', {
        name: formData.name,
        description: formData.description
      }).then((response) => {
        if (response.data.status === 'success') {
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
          commit('addTeam', response.data.team)
        }
      }).catch((error) => {
        this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
      })
    },
    removeTeam({ commit }, data) {
      axios.delete(`data/teams/${data.id}`)
        .then((response) => {
          commit('deleteTeam', data.index)
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
        })
        .catch((error) => {
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    addOffice({ commit }, formData) {
      axios.post('/offices', {
        name: formData.name,
        description: formData.description
      })
        .then((response) => {
          if (response.data.status === 'success') {
            this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
            commit('addOffice', response.data.office)
          }
        })
        .catch((error) => {
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    removeOffice({ commit }, data) {
      axios.delete(`/offices/${data.id}`)
        .then((response) => {
          commit('removeOffice', data.index)
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
        })
        .catch((error) => {
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },
    updateProject({ commit }, project) {
      commit('updateProject', project)
    },
    getMembers({ commit }, members) {
      commit('getMembers', members)
    },
    getTags({ commit }, tags) {
      commit('getTags', tags)
    },
    toggleLoading({ commit }, state) {
      commit('toggleLoading', state)
    },
    setIsUserAdmin({ commit }) {
      var isAdmin = {}
      if (parseInt(authUser.role_id) === parseInt(Constants.adminDetails.role)) {
        isAdmin.status = true
        isAdmin.message = 'Is admin'
      } else {
        isAdmin.status = false
        isAdmin.message = Constants.error.errorMessage
      }
      commit('setIsUserAdmin', isAdmin)
    },
    getErrorResponseAccordingToError({ commit }, error) {  /* Error functionality works on the basis for error code returned form the route of laravel */
      error.response.status == 403 ? router.push({ name: 'access.denied' }) : false;
      error.response.status == 404 ? router.push({ name: 'not.found' }) : false;

    },
    reIntializeProjects({ commit }) {
      commit('reIntializeProjects')
    },
    async getNotifications({ commit }, notificationsParam = {}) {
      if (notificationsParam.loadMore == false) { commit('setNoficationsParams', { 'params': {} }) } // set default params
      if (functions.checkIfNotificationsShouldLoad(notificationsParam, this.state) === false) { return false }
      var requestedParams = (notificationsParam.loadMore == true) ? { 'page': this.state.notifications.nextPage } : { 'page': this.state.notifications.defaultPage }
      if (this.state.notifications.isCurrentlyLoading == false) {
        commit('setNoficationsAreLoading', true)
        commit('toggleLoading', true)
        axios.get('/data/notifications', { params: requestedParams })
          .then((response) => {
            if (response.data.status === 'success') {
              commit('setNoficationsParams', { 'params': { 'isCurrentlyLoading': true } }) // set loading to true
              commit('toggleLoading', false)
              response.data.data.loadMore = notificationsParam.loadMore
              response.data.data.unreadNotificationsCount = response.data.unreadNotificationsCount
              commit('getNotifications', response.data.data)
            }
          })
          .catch((error) => {
            commit('toggleLoading', false)
            this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
          })
      }
    },
    markNotification({ commit }, params = {}) {
      commit('toggleLoading', true)
      var requiredParams = (typeof params.id == 'undefined' || params.id == null) ? { 'mark_all': true } : { 'id': params.id }
      axios.put('/data/notifications', requiredParams).then((response) => {
        response.data.index = params.index
        commit('setNotificationMarked', response.data)
        commit('toggleLoading', false)
        this.dispatch('showNotification', { type: response.data.status, message: response.data.message })

      }).catch((error) => {
        commit('toggleLoading', false)
        this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
      })
    },
    getNotificationDetails({ commit }, params) {
      commit('toggleLoading', true)
      commit('getNotificationDetails', params)
      commit('toggleLoading', false)
    },


  }
})
