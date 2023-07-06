<template>
  <div class="chat-files">
    <h1>Files</h1>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation.chatboard.files",
  components: {},
  data: () => ({}),
  created() {},
  computed: {
    ...mapState({
      availableUsers: (state) => state.availableUsers,
    }),
  },
  methods: {
    ...mapActions([]),
  },
};
</script>
