<template>
  <div class="chat-messages">
    <ul
      class="chat-message-runway"
      id="message-box"
      v-if="
        loading.activeDirectUserMessages === false &&
        loading.activeWorkspaceMessages === false &&
        messages
      "
    >
      <div v-if="messages.length === 0">
        <li class="chat-item no-messages-wrap">
          <div class="no-messages">
            <p>Start a new conversation</p>
          </div>
        </li>
      </div>
      <template v-for="(message, index) in messages">
        <div :key="message.id">
          <li class="chat-item" :class="getUserClass(message)">
            <div class="chat-item-chatbox">
              <div class="chatbox-user" v-if="!belongsToAuthUser(message)">
                <profile-card :user="message.user" />
              </div>
              <div class="chatbox-content">
                <div class="chatbox-message-header">
                  <div class="message-auther" v-if="!belongsToAuthUser(message)">
                    <h3>{{ message.user.username }}</h3>
                  </div>
                  <div class="message-datetime">
                    <span
                      >{{ $functions.getDate(message.created_at, true) }}
                      {{ $functions.getTime(message.created_at) }}</span
                    >
                  </div>
                  <chat-message-toggle v-if="belongsToAuthUser(message)" :message="message" />
                </div>
                <div
                  class="chatbox-message-content"
                  v-html="message.body"
                ></div>
              </div>
            </div>
          </li>
          <li
            class="chat-item"
            v-if="getDivider(message, index === 0 ? null : messages[index - 1])"
          >
            <div class="divider">
              <span>{{
                $functions.getDate(
                  index === 0 ? null : messages[index - 1].created_at,
                  true,
                  true
                )
              }}</span>
            </div>
          </li>
          <li
            class="chat-item loading-chat-messages-wrap-fixed"
            v-if="
              loading.activeDirectUserOldMessages ||
              loading.activeWorkspaceOldMessages
            "
          >
            <div class="loading-chat-messages">
              <loading-dots />
            </div>
          </li>
        </div>
      </template>
    </ul>
    <ul class="chat-message-runway" id="message-box" v-else>
      <li class="chat-item loading-chat-messages-wrap">
        <div class="loading-chat-messages">
          <loading-dots />
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import profileCard from "./../../partials/profileCard.vue";
import loadingDots from "./../../partials/loadingDots.vue";
import chatMessageToggle from "./chatMessageToggle.vue";

import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation.chatboard.messages",
  components: {
    profileCard,
    loadingDots,
    chatMessageToggle,
  },
  data: () => ({
    authUser: user,
  }),
  created() {},
  computed: {
    ...mapState({
      availableUsers: (state) => state.availableUsers,
      directUsers: (state) => state.chat.directUsers,
      miscellaneous: (state) => state.chat.miscellaneous,
      loading: (state) => state.chat.loading,
      loaded: (state) => state.chat.loaded,
    }),

    messages() {
      return this.getActiveMessages();
    },
  },
  watch: {
    //   "directUsers.list": {
    //    handler(newVal, oldVal){
    //      this.$nextTick(function () {
    //        this.sortDirectUsersAndWorkspaceList({ type: Constants.chat.boardTypes[1] })
    //     console.log('working',newVal)
    //     console.log('oldVal',oldVal)
    //     })
    //    },
    //    deep: true
    // }
    "loaded.activeDirectUserMessages": function (newVal, oldVal) {
      if (newVal === true && oldVal === false) {
        this.$functions.scrollToBottomOfElement("message-box", 100);
      }
    },
    "loaded.activeWorkspaceMessages": function (newVal, oldVal) {
      if (newVal === true && oldVal === false) {
        this.$functions.scrollToBottomOfElement("message-box", 100);
      }
    },
  },
  methods: {
    ...mapActions(["sortDirectUsersAndWorkspaceList"]),
    getActiveMessages() {
      let messages = [];
      if (
        !(
          this.loading.activeDirectUserMessages &&
          this.loading.activeWorkspaceMessages
        )
      ) {
        if (
          this.miscellaneous.activeBoardType ===
          this.miscellaneous.availableBoardTypes[0]
        ) {
          messages = [];
        }
        if (
          this.miscellaneous.activeBoardType ===
          this.miscellaneous.availableBoardTypes[1]
        ) {
          messages =
            this.directUsers.active.messages.data !== undefined
              ? this.directUsers.active.messages.data
              : [];
        }
      }
      return messages;
    },
    getUserClass(message) {
      return this.authUser.id === message.sender_id ? "sender" : "receiver";
    },
    belongsToAuthUser(message) {
      return this.authUser.id === message.sender_id ? true : false;
    },
    getDivider(currentMessage, oldMessage) {
      if (oldMessage == null || typeof oldMessage == "undefined") {
        return false;
      }
      let currentMessageDateTime = this.$functions.getDate(
        currentMessage.created_at,
        false,
        true
      );
      let oldMessageDateTime = this.$functions.getDate(
        oldMessage.created_at,
        false,
        true
      );
      if (oldMessageDateTime.ts > currentMessageDateTime.ts) {
        return true;
      } else {
        return false;
      }
    },
    removeUserClassFromDivider() {
      $(".chat-item")
        .children(".divider")
        .parent()
        .removeClass("sender receiver");
    },
  },
  mounted() {},
};
</script> 
