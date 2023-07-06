<template>
  <div class="chat-message-toggle">
    <b-dropdown>
      <b-dropdown-item>Edit</b-dropdown-item>
      <b-dropdown-item @click="deleteData()">Delete</b-dropdown-item>
    </b-dropdown>
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
  components: {},
  props: {
    message: {
      required : true,
      type: Object
    }
  },
  data: () => ({}),
  created() {},
  computed: {
    ...mapState({}),
  },
  methods: {
    ...mapActions(["deleteMessage"]),

    deleteData(){
          this.deleteMessage(this.message);
    }
  },
};
</script>
