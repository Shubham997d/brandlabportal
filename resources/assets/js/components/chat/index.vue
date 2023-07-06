<template>
<div class="chat-wrap">
    <div class="chat-body" v-if="directUsers">
      <main-sidebar/>
      <router-view :key="routerKey"/>
    </div>
</div>
</template> 

<script>
import { mapState, mapActions } from 'vuex'
import { VEmojiPicker } from 'v-emoji-picker';
import mainSidebar  from './mainSidebar';

export default {
  name: 'chat',
  components: {
    VEmojiPicker,
    mainSidebar,
  },
  data: () => ({
   emoji: {
     data : null
   },
   routerKey: 11100
  }),
  created () {
   
  },
  computed: {
    ...mapState({
      directUsers: (state) => state.chat.directUsers,
    })
  },
  methods: {
    ...mapActions([
    ]),
     selectEmoji(emoji) {
      this.emoji = emoji
    },

  }
}
</script>
