<template>
  <div class="user-typing-wrap" v-if="onSideBar == false">
    <div
      class="user-typing"
      v-if="activeBoardType == availableBoardTypes[0]"
    ></div>
    <div class="user-typing" v-if="activeBoardType == availableBoardTypes[1]">
      <div
        class="user-typing-content"
        v-if="directUsers.active.user.typing.active"
      >
        <span>{{ directUsers.active.user.typing.message }}</span>
        <loading-dots />
      </div>
    </div>
  </div>
  <div v-else>
    <div class="sidebar-user-typing" v-if="user.typing.active && user.id != directUsers.active.user.id">
      <span>{{ user.typing.message }}</span>
      <loading-dots />
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import loadingDots from "./../../partials/loadingDots.vue";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation.chatboard.photos",
  props: {
    onSideBar: {
      required: false,
      default: false,
      type: Boolean,
    },
    user: {
      required: false,
      type: Object,
    },
  },
  components: {
    loadingDots,
  },
  data: () => ({}),
  created() {
    
  },
  computed: {
    ...mapState({
      activeBoardType: (state) => state.chat.miscellaneous.activeBoardType,
      availableBoardTypes: (state) =>
        state.chat.miscellaneous.availableBoardTypes,
      directUsers: (state) => state.chat.directUsers,
    }),
  },
  methods: {
    ...mapActions([]),
  },
};
</script>
