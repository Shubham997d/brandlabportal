<template>
    <div>
      <h1>meet</h1>
    </div>
</template> 

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'chat.meet',
  components: {
  },
  data: () => ({
   
  }),
  created () {
    
  },
  computed: {
    ...mapState({
        createWorkspace: state => state.chat.createWorkspace
    })
  },
  methods: {
    ...mapActions([

    ]),
     selectEmoji(emoji) {
      this.emoji = emoji
    }   
  }
}
</script>
