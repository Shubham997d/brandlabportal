<template>
    <div>
      <h1>calendar test</h1>
      <add-to-calendar title="VueConf" 
                 location="WROCŁAW, POLAND" 
                 :start="new Date()"
                 :end="new Date((new Date).setDate((new Date).getDate() + 1))"
                 details="The first Official Vue.js Conference in the world!" 
                 inline-template>
          <div>
            
            <office365-calendar id="office365-calendar">
              <i class="fa fa-windows"></i> Add to Office365 outlook calendar
            </office365-calendar>
          </div>
      </add-to-calendar>
    </div>
</template> 

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'chat.calendar',
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
