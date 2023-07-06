<template>
  <div class="chat-photos">
    <h1>Photos</h1>
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
  name: "chat.coversation.chatboard.photos",
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
