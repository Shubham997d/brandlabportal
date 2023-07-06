<template></template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  components: {},
  data: () => ({
    authUser: user,
  }),
  created() {
    this.listen();
  },
  computed: {
    ...mapState({
      directUsers: (state) => state.chat.directUsers,
    }),
  },
  methods: {
    ...mapActions(["addActiveDirectUserMessage", "setTypingUser","setLatestDirectUserMessage"]),

    listen() {      
      Echo.private("User." + this.authUser.id)
        .listen(".directMessageCreated", (event) => {
          this.addDirectMessage(event);
        })
        .listenForWhisper("typing", (event) => {
          this.setTyping(event);
        });
    },

    addDirectMessage(event) {
      if (event.user.id === this.directUsers.active.user.id) {
        this.addActiveDirectUserMessage({
          message: { ...event.message, ...{ user: event.user } },
        });
        this.$functions.scrollToBottomOfElement("message-box");
      } else {
        this.setLatestDirectUserMessage({ ...{message: event.message}, ...{ user: event.user } })
      }
    },

    setTyping(event) {
      this.setTypingUser({
        type: "directUser",
        data: { ...event, ...{ active: true } },
      });
      setTimeout(() => {
        // remove current typing user
        this.setTypingUser({
          type: "directUser",
          data: { ...event, ...{ active: false, message: null } }, 
        });
      }, 600);
    },
  },
};
</script>
