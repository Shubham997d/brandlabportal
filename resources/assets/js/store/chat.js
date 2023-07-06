// import router from '../router/index'
import functions from '../common/functions.js'
var _ = require('underscore');

export default {
  state: {
    directUsers: {
      list: [],
      active: {
        user: {},
        messages: {}
      }
    },
    workspaces: {
      list: [],
      active: {
        workspace: {},
        messages: {}
      }
    },
    loading: {
      renamedUsers: false,
      activeWorkspaceMessages: false,
      activeWorkspaceOldMessages: false,
      activeWorkspaceNewMessages: false,
      activeDirectUserMessages: false,
      activeDirectUserOldMessages: false,
      activeDirectUserNewMessages: false,
    },
    loaded: {
      renamedUsers: false,
      activeWorkspaceMessages: false,
      activeWorkspaceOldMessages: false,
      activeWorkspaceNewMessages: false,
      activeDirectUserMessages: false,
      activeDirectUserOldMessages: false,
      activeDirectUserNewMessages: false,
    },
    miscellaneous: {
      availableBoardTypes: Constants.chat.boardTypes,
      activeBoardType: null
    }

  },

  mutations: {
    getTeam(state, team) {
      state.team = team
    },
    setChatDirectUsers(state, users) {
      state.directUsers.list = users
    },
    setChatDirectActiveUser(state, user) {
      state.directUsers.active.user = user
    },
    setWorkspaces(state, workspaces) {
      state.workspaces.list = workspaces
    },
    setChatItemLoading(state, params) {
      state.loading[params.type] = params.value
    },
    setChatItemIsLoaded(state, params) {
      state.loaded[params.type] = params.value
    },
    addWorkspace(state, workspace) {
      state.workspaces.list.push(...workspace)
    },
    activeBoardType(state, active) {
      state.miscellaneous.activeBoardType = active
    },
    setActiveDirectUserMessages(state, messages) {
      state.directUsers.active.messages = messages
    },
    addActiveDirectUserMessage(state, message) {
      state.directUsers.active.messages.data.unshift(message) // add message in front of array as we are using css for reversing messages
    },
    addMultipleActiveDirectUserMessages(state, messages) { // add message in back of array as when we are loading chat history
      if (messages.length === 0) {
        return false
      }
      messages.forEach((element, index) => {   // logic for not allowing dulpicate messages with same id
        var idExists = functions.findIndexOfMultidimensionalArray(state.directUsers.active.messages.data, messages[index].id, 'id')
        if (idExists == false) {
          state.directUsers.active.messages.data.push(element)
        }
      });
    },
    setParamsForPagination(state, params) {
      if (params.forDirectUsers === true) {
        state.directUsers.active.messages.current_page = params.current_page
        state.directUsers.active.messages.first_page_url = params.first_page_url
        state.directUsers.active.messages.from = params.from
        state.directUsers.active.messages.last_page = params.last_page
        state.directUsers.active.messages.last_page_url = params.last_page_url
        state.directUsers.active.messages.next_page_url = params.next_page_url
        state.directUsers.active.messages.path = params.path
        state.directUsers.active.messages.per_page = params.per_page
        state.directUsers.active.messages.prev_page_url = params.prev_page_url
        state.directUsers.active.messages.to = params.to
        state.directUsers.active.messages.total = params.total
      }
    },
    setTypingUser(state, params) {
      if (params.type === 'directUser') {
        let index = state.directUsers.list.findIndex(
          (x) => x.id === params.data.id
        );
        if (state.directUsers.active.user.id === params.data.id) {
          state.directUsers.active.user.typing.active = params.data.active
          state.directUsers.active.user.typing.message = params.data.message
        } else {
          state.directUsers.list[index].typing.active = params.data.active
          state.directUsers.list[index].typing.message = params.data.message
        }
      } else {
        // state.miscellaneous.typing.directUser = params.data
      }

    },
    emptyActiveSpaces(state, params) {
      if (params.activeBoard == Constants.chat.boardTypes[0]) {
        state.directUsers.active.user = {}
        state.directUsers.active.messages = {}
      }
      if (params.activeBoard == Constants.chat.boardTypes[1]) {
        state.workspaces.active.workspace = {}
        state.workspaces.active.messages = {}
      }
    },
    deleteMessage(state, params) {
      console.log('params-111',params)
      if (state.miscellaneous.activeBoardType == Constants.chat.boardTypes[0]) {

      }
      if (state.miscellaneous.activeBoardType == Constants.chat.boardTypes[1]) {
        let index = state.directUsers.active.messages.data.findIndex((x) => x.id === params.id);
        console.log('index', index)
        // return false
        // state.directUsers.active.messages.data.slice(index, 1);
      }
    },

    setLatestDirectUserMessage(state, params) {
      state.directUsers.list[params.index].latestMessage = params.latestMessage
    },

    setLatestMessageForAllDirectUsers(state, params) {
      if (state.directUsers.list.length > 0) {  // add messages 
        state.directUsers.list.forEach((user) => {
          let currentUser = params.directUsers.find(
            (x) => x.id === user.id
          );
          let message = currentUser.latestMessage
          if (message) {
            let latestMessage = functions.assignObject(currentUser.latestMessage, 'user');
            user.latestMessage = { message: latestMessage, user: currentUser.latestMessage.user }
          }
        });
      }

    },

    sortDirectUsersAndWorkspaceList(state, params) {
      var oldDate = '2022-05-18T15:37:31.000000Z';
      if (params.type === Constants.chat.boardTypes[0]) {

      }
      if (params.type === Constants.chat.boardTypes[1]) {
        state.directUsers.list = _.sortBy(state.directUsers.list, function (o) {   // return messages in descending order
          return -functions.getDate(typeof o.latestMessage.message.created_at === 'undefined' ? oldDate : o.latestMessage.message.created_at, false);
        })
        state.directUsers.list.forEach((o) => {
          functions.getDate(typeof o.latestMessage.message.created_at === 'undefined' ? oldDate : o.latestMessage.message.created_at, false).ts;
        });
      }
    }

  },

  actions: {
    setChatDirectUsers({ commit }, params) {
      if (params.rename !== undefined && params.rename === true) {
        let renamedUsers = params.users.map(x => ({ 'username': x.text, 'id': x.value, 'avatar': x.avatar, typing: { active: false, message: null }, latestMessage: { user: {}, message: {} } }))
        var index = renamedUsers.findIndex((x) => x.id === user.id);
        renamedUsers.splice(index, 1); // remove current user the list
        commit('setChatDirectUsers', renamedUsers)
      }
    },

    activeBoardType({ commit }, active) {
      commit('activeBoardType', active)
      this.dispatch('emptyActiveSpaces', { activeBoard: active }) // empty inactive workspacess as well. direct and workspace

    },

    setChatItemLoading({ commit }, params) {
      commit('setChatItemLoading', params)
    },

    setChatItemIsLoaded({ commit }, params) {
      commit('setChatItemIsLoaded', params)
    },

    async setChatDirectActiveUser({ commit, state }, params) {
      let activeUser = state.directUsers.list.find(
        (x) => x.username === params.user
      );
      commit('setChatDirectActiveUser', activeUser)
    },

    setWorkspaces({ commit }, params = null) {
      commit('toggleLoading', true)
      axios.get('/data/workspaces', { 'params': params })
        .then((response) => {
          if (response.data.status === 'success') {
            commit('setWorkspaces', response.data.workspaces)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },

    createWorkspace({ commit }, params) {
      axios.post('/data/workspace', params).then((response) => {
        if (response.data.status === 'success') {
          commit('addWorkspace', response.data.workspace)
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
        }
      }).catch((error) => {
        this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
      })
    },

    getActiveDirectUserMessages({ commit, state }, params) {
      commit('toggleLoading', true)
      if (state.loading.activeDirectUserMessages === true) { return false } // don't load messages if they are already loading
      this.dispatch('getChatActiveLoading', { ...params, ...{ loadingStatus: true } })
      axios.get('/data/direct-messages', { 'params': params })
        .then((response) => {
          if (response.data.status === 'success') {

            if (params.loadMore === true) {
              commit('addMultipleActiveDirectUserMessages', response.data.messages.data)
              commit('setParamsForPagination', { ...response.data.messages, ...{ forDirectUsers: true } })
            } else {
              commit('setActiveDirectUserMessages', response.data.messages)
            }
            this.dispatch('getChatActiveLoading', { ...params, ...{ loadingStatus: false } })
            commit('toggleLoading', false)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
          this.dispatch('getChatActiveLoading', { ...params, ...{ loadingStatus: false } })
          this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
        })
    },

    addActiveDirectUserMessage({ commit, state }, params) {
      if (state.loading.activeDirectUserMessages === true) { return false }
      commit('addActiveDirectUserMessage', params.message)
      let latestMessage = functions.assignObject(params.message, 'user');
      this.dispatch('setLatestDirectUserMessage', { ...{ message: latestMessage }, ...{ user: params.message.user }, ...{ messageFor: params.messageFor } })
    },

    getChatActiveLoading({ commit }, params) {
      if (params.loadingType == Constants.chat.boardTypes[0]) {
        switch (params.for) {
          case 'old':
            this.dispatch('setChatItemLoading', { type: 'activeWorkspaceOldMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeWorkspaceOldMessages', value: !params.loadingStatus })
            break;
          case 'new':
            this.dispatch('setChatItemLoading', { type: 'activeWorkspaceNewMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeWorkspaceNewMessages', value: !params.loadingStatus })
            break;
          default:
            this.dispatch('setChatItemLoading', { type: 'activeWorkspaceMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeWorkspaceMessages', value: !params.loadingStatus })
        }
      }
      if (params.loadingType == Constants.chat.boardTypes[1]) {
        switch (params.for) {
          case 'old':
            this.dispatch('setChatItemLoading', { type: 'activeDirectUserOldMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeDirectUserOldMessages', value: !params.loadingStatus })
            break;
          case 'new':
            this.dispatch('setChatItemLoading', { type: 'activeDirectUserNewMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeDirectUserNewMessages', value: !params.loadingStatus })
            break;
          default:
            this.dispatch('setChatItemLoading', { type: 'activeDirectUserMessages', value: params.loadingStatus })
            this.dispatch('setChatItemIsLoaded', { type: 'activeDirectUserMessages', value: !params.loadingStatus })
        }
      }
    },

    setTypingUser({ commit }, params) {
      commit('setTypingUser', params)
    },

    emptyActiveSpaces({ commit }, params) {
      commit('emptyActiveSpaces', params)
    },

    setLatestDirectUserMessage({ commit, state }, params) {
      // console.log('params', params)
      if (params.messageFor == 'current') {
        var index = state.directUsers.list.findIndex(
          (x) => x.id === params.message.receiver_id
        );
      } else {
        var index = state.directUsers.list.findIndex(
          (x) => x.id === params.message.sender_id
        );
        delete params.messageFor;
      }
      commit('setLatestDirectUserMessage', { 'index': index, latestMessage: params })
      commit('sortDirectUsersAndWorkspaceList', { type: Constants.chat.boardTypes[1] })
    },

    getLatestMessagesForAll({ commit }, params = {}) {
      commit('toggleLoading', true)
      axios.get('/data/conversation/common', { 'params': params })
        .then((response) => {
          if (response.data.status === 'success') {
            if (response.data.directUsers && response.data.directUsers.length > 0) {
              commit('setLatestMessageForAllDirectUsers', { directUsers: response.data.directUsers })
              this.dispatch('sortDirectUsersAndWorkspaceList', { type: Constants.chat.boardTypes[1] })
            }
            commit('toggleLoading', false)
          }
        })
        .catch((error) => {
          commit('toggleLoading', false)
        })

    },

    sortDirectUsersAndWorkspaceList({ commit }, params) {
      if (params.type == Constants.chat.boardTypes[0]) {
        commit('sortDirectUsersAndWorkspaceList', { type: Constants.chat.boardTypes[0] })
      }
      if (params.type == Constants.chat.boardTypes[1]) {
        commit('sortDirectUsersAndWorkspaceList', { type: Constants.chat.boardTypes[1] })
      }
    },

    deleteMessage({ commit, state }, params) {
      console.log('params',params)
      if (state.miscellaneous.activeBoardType == Constants.chat.boardTypes[0]) {

      }
      if (state.miscellaneous.activeBoardType == Constants.chat.boardTypes[1]) {
        this.dispatch('deleteDirectMessage', params)
      }
    },

    deleteDirectMessage({ commit, state }, params) {
      axios.delete('/data/direct-messages', { params: params }).then((response) => {
        if (response.data.status === 'success') {
          commit('deleteMessage', params)
          this.dispatch('showNotification', { type: response.data.status, message: response.data.message })
        }
      }).catch((error) => {
        this.dispatch('showNotification', { type: error.response.data.status, message: error.response.data.message })
      })
    }




  }
}
