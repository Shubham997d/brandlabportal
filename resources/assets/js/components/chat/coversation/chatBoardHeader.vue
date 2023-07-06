<template>
  <div class="chat-board-header">
    <div
      class="chat-board-active-user"
      v-if="
        miscellaneous.activeBoardType === miscellaneous.availableBoardTypes[1]
      "
    >
      <profile-card :user="directUsers.active.user" />
      <h3>{{ directUsers.active.user.username }}</h3>
    </div>
    <div
      class="chat-board-active-workspace"
      v-if="
        miscellaneous.activeBoardType === miscellaneous.availableBoardTypes[0]
      "
    >
      <h3>{{ workspaces.active.name }}</h3>
    </div>
    <ul class="chat-board-header-tabs">
      <li>
        <router-link :to="getActiveRouterLink()"> Chat </router-link>
      </li>
      <li>
        <router-link :to="getActiveRouterLink('files')"> Files </router-link>
      </li>
      <li>
        <router-link :to="getActiveRouterLink('photos')"> Photos </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import profileCard from "./../../partials/profileCard.vue";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  components: {
    profileCard,
  },
  data: () => ({}),
  created() {},
  computed: {
    ...mapState({
      directUsers: (state) => state.chat.directUsers,
      workspaces: (state) => state.chat.workspaces,
      miscellaneous: (state) => state.chat.miscellaneous,
    }),
  },
  methods: {
    ...mapActions(["setChatDirectActiveUser"]),

    getActiveRouterLink(type = "messages") {
      let mainUrl = "/chat/coversation/direct/";
      return mainUrl + this.directUsers.active.user.username + "/" + type;
    },
  },
  mounted() {},
};
</script>
