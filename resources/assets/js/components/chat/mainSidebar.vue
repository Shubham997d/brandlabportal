<template>
  <div class="main-sidebar">
    <b-list-group>
      <b-list-group-item :to="'/chat/coversation'" active><font-awesome-icon :icon="faCommentDots" class="cursor-pointer text-sm" ></font-awesome-icon><span>Chat</span></b-list-group-item>
      <b-list-group-item :to="'/chat/meet'"><font-awesome-icon :icon="faVideo" class="cursor-pointer text-sm" ></font-awesome-icon><span>Meet</span></b-list-group-item>
      <b-list-group-item :to="'/chat/calendar'"><font-awesome-icon :icon="faCalendarAlt" class="cursor-pointer text-sm" ></font-awesome-icon><span>Calendar</span></b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { faCommentDots, faVideo, faCalendarAlt } from "@fortawesome/free-solid-svg-icons";

export default {
  components: {},
  data: () => ({
    faCommentDots,
    faVideo,
    faCalendarAlt,
  }),
  created() {},
  computed: {
    ...mapState({}),
  },
  methods: {
    ...mapActions([]),
  },
};
</script>
