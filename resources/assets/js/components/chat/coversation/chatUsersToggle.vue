<template>
  <div
    class="chat-users-toggle"
    :style="{ left: left }"
    v-if="users.length > 0"
  >
    <vue-custom-scrollbar
      class="scroll-area-users-listing"
      id="cs-chat-users-lt"
      :settings="customSrollBarSettings"
      @ps-scroll-y="scrollHandle"
      tabindex="0"
    >
      <div
        class="c-users-list"
        v-for="user in users"
        :key="user.id"
        @click="selectUser(user)"
      >
        <profile-card :user="user" :showHover="false" />
        <span>{{ user.username }}</span>
      </div>
    </vue-custom-scrollbar>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";
import profileCard from "./../../partials/profileCard.vue";
import vueCustomScrollbar from "vue-custom-scrollbar";
export default {
  name: "chat.coversation.chatboard.photos",
  components: { profileCard, vueCustomScrollbar },
  data: () => ({
    customSrollBarSettings:
      Constants.miscellaneous.customScrollBar.withoutXScroll.settings,
    selectedUser: {},
  }),
  props: {
    left: {
      required: true,
      type: String,
    },
    users: {
      required: true,
      type: Array,
    },
  },
  created() {},
  computed: {
    ...mapState({}),
  },
  methods: {
    ...mapActions([]),
    scrollHandle(evt) {
     
    },
    selectUser(user) {
      this.selectedUser = user;
      this.$emit("user-selected", this.selectedUser);
    },
  },
};
</script>
