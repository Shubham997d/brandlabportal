<template>
  <div class="chat-sidebar">
    <div class="cs-header">
      <h2>Chat</h2>
      <div class="cs-h-options">
        <a href="#" id="cs-h-search" @click="showSearchBar(true)"
          ><font-awesome-icon
            :icon="faSearch"
            class="cursor-pointer text-sm"
          ></font-awesome-icon
        ></a>
        <a href="#"
          ><font-awesome-icon
            :icon="faVideo"
            class="cursor-pointer text-sm"
          ></font-awesome-icon
        ></a>
        <router-link :to="'/chat/coversation/create-workspace'">
          <font-awesome-icon
            :icon="faPlus"
            class="cursor-pointer text-sm"
          ></font-awesome-icon>
        </router-link>
      </div>
      <div id="cs-h-search-field" v-if="showSearchBarField">
        <input
          type="search"
          id="input-chat-search"
          placeholder="Filter by name"
        />
        <a href="#" @click="showSearchBar(false)"
          ><font-awesome-icon
            :icon="faTimes"
            class="cursor-pointer text-sm"
          ></font-awesome-icon
        ></a>
      </div>
    </div>
    <div class="cs-body">
      <div class="collapse-container">
        <b-button v-b-toggle.collapse-3 class="m-1">
          <font-awesome-icon
            :icon="faCaretDown"
            class="cursor-pointer text-sm"
          ></font-awesome-icon>
          Workspaces
        </b-button>
        <b-collapse visible id="collapse-3">
          <b-card
            v-for="(workspace, index) in workspaces.list"
            :key="index"
            class="a-chat-workspaces-list"
            :class="getActiveRouterClass(workspace.slug)"
          >
            <span
              class="a-c-link"
              @click="
                goToChatBoard(
                  '/chat/coversation/workspace/' + workspace.slug + '/messages'
                )
              "
              ># {{ workspace.name }}</span
            >
          </b-card>
        </b-collapse>
        <b-button v-b-toggle.collapse-4 class="m-1">
          <font-awesome-icon
            :icon="faCaretDown"
            class="cursor-pointer text-sm"
          ></font-awesome-icon>
          Users
        </b-button>
        <b-collapse visible id="collapse-4">
          <div v-for="(user, index) in directUsers.list" :key="index">
            <b-card
              class="a-chat-direct-users-list"
              @click="
                goToChatBoard(
                  '/chat/coversation/direct/' + user.username + '/messages'
                )
              "
              :class="getActiveRouterClass(user.username)"
            >
              <profile-card :user="user" />
              <div class="message-info-chat">
                <div class="a-chat-user-name">{{ user.username }}</div>
                <div
                  class="user-last-mess"
                  v-if="
                    Object.keys(user.latestMessage.message).length != 0 &&
                    user.typing.active == false
                  "
                >
                  <span
                    v-if="user.latestMessage.message.sender_id === authUser.id"
                  >
                    You:
                  </span>
                  <span v-html="user.latestMessage.message.body"></span>
                </div>
                <chat-typing v-if="user.typing.active" :user="user" :onSideBar="true" />
              </div>
              <div class="message-date"></div>
            </b-card>
          </div>
        </b-collapse>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import profileCard from "./../../partials/profileCard.vue";
import ChatTyping from "./chatTyping.vue";
import {
  faSearch,
  faVideo,
  faEdit,
  faTimes,
  faCaretDown,
  faPlus,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation",
  components: {
    profileCard,
    ChatTyping,
  },
  data: () => ({
    faSearch,
    faVideo,
    faEdit,
    faTimes,
    faCaretDown,
    faPlus,
    showSearchBarField: false,
    authUser: user,
  }),
  created() {},
  computed: {
    ...mapState({
      createWorkspace: (state) => state.chat.createWorkspace,
      directUsers: (state) => state.chat.directUsers,
      workspaces: (state) => state.chat.workspaces,
      miscellaneous: (state) => state.chat.miscellaneous,
    }),
  },
  methods: {
    ...mapActions([]),
    showSearchBar(show = false) {
      this.showSearchBarField = show;
    },
    goToChatBoard(link) {
      if (this.$route.fullPath != link) {
        this.$router.push({ path: link, params: { savedPosition: true } });
      }
    },
    getActiveRouterClass(userName) {
      return this.$route.params.name === userName ? "active-route" : "";
    },
    checkIfUserLatestMessageBelongToAuthUser(data, type = "direct") {
      if (type == this.miscellaneous.availableBoardTypes[0]) {
      }
      if (type == this.miscellaneous.availableBoardTypes[1]) {
      }
    },
  },
};
</script>
