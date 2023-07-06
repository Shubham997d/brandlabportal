<template>
<div id="content-container" class="sm:container sm:mx-auto px-4 w-full sm:max-w-xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl">
<deals-listing> </deals-listing>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import dealsListing from './../partials/deals.vue'

export default {
  name: 'deals',
  components: {
    dealsListing
    
  },
  
  data: () => ({    
      
  }),

  created () {
      
  },

  computed: {
    ...mapState({      
    })
  },

  methods: {
    ...mapActions([

    ]),    
    
  },mounted () {
     
  }
}
</script>
 