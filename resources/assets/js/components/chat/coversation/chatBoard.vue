<template>
  <div class="chat-board" v-if="directUsers.active.user">
    <chat-board-header />
    <router-view :key="routerKey" />
    <chat-message-editor
      v-if="$route.name === 'chat.coversation.chatboard.messages'"
    />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import chatBoardHeader from "./chatBoardHeader";
import vueCustomScrollbar from "vue-custom-scrollbar";
import chatMessageEditor from "./chatMessageEditor";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation.chatboard",
  components: {
    chatBoardHeader,
    vueCustomScrollbar,
    chatMessageEditor,
  },
  data: () => ({
    routerKey: 2100,
    customSrollBarSettings:
      Constants.miscellaneous.customScrollBar.withoutXScroll.settings,
    routerKey: 8000,
  }),
  created() {},
  computed: {
    ...mapState({
      availableUsers: (state) => state.availableUsers,
      directUsers: (state) => state.chat.directUsers,
    }),
  },
  methods: {
    ...mapActions([]),
    scrollHandle(event) {},
  },
};
</script>
