<template>
  <div class="conversation" :class="getClassIfMessageEditorAdded">
    <vue-custom-scrollbar
      class="scroll-area"
      :settings="customSrollBarSettings"
      @ps-scroll-y="scrollHandle"
    >
      <chat-sidebar />
    </vue-custom-scrollbar>

    <vue-custom-scrollbar
      class="scroll-area-coversation"
      :settings="customSrollBarSettings"
      @ps-scroll-y="scrollHandle"
      @ps-y-reach-start="loadData($event)"
    >
      <router-view :key="routerKey" />
    </vue-custom-scrollbar>
  </div>
</template> 

<script>
import { mapState, mapActions } from "vuex";
import { VEmojiPicker } from "v-emoji-picker";
import chatSidebar from "./chatSidebar";
import createWorkspace from "./createWorkspace";
import vueCustomScrollbar from "vue-custom-scrollbar";

export default {
  name: "chat.coversation",
  components: {
    VEmojiPicker,
    chatSidebar,
    vueCustomScrollbar,
    createWorkspace,
  },
  data: () => ({
    emoji: {
      data: null,
    },
    customSrollBarSettings:
      Constants.miscellaneous.customScrollBar.withoutXScroll.settings,
    routerKey: 8000,
  }),
  async created() {
        
  },
  computed: {
    ...mapState({
      createWorkspace: (state) => state.chat.createWorkspace,
      directUsers: (state) => state.chat.directUsers,
      miscellaneous: (state) => state.chat.miscellaneous,
    }),
    getClassIfMessageEditorAdded() {
      return this.$route.meta.root() === "messages"
        ? "message-editor-availabe"
        : "";
    },
  },
  methods: {
    ...mapActions(["getActiveDirectUserMessages"]),
    selectEmoji(emoji) {
      this.emoji = emoji;
    },
    scrollHandle(evt) {},
    loadData() {
      if (this.$route.name === "chat.coversation.chatboard.messages") {
        this.loadCurrentActiveMessages();
      }
    },
    loadCurrentActiveMessages() {
      if (
        this.miscellaneous.activeBoardType ===
        this.miscellaneous.availableBoardTypes[0]
      ) {
      }
      if (
        this.miscellaneous.activeBoardType ===
        this.miscellaneous.availableBoardTypes[1]
      ) {
        if (this.directUsers.active.messages.next_page_url && typeof this.directUsers.active.user.id !== 'undefined') {
          this.getActiveDirectUserMessages({
            loadingType: Constants.chat.boardTypes[1],
            for: "old",
            receiver_id: this.directUsers.active.user.id,
            loadMore: true,
            page: this.$functions.getParticularQueryParam(
              this.directUsers.active.messages.next_page_url,
              "page"
            ),
          });
        }
      }
    },
  },
};
</script>
