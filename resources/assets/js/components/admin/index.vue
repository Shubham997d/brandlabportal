<template>
<div>
  <!-- <tab-menu :active="active" @activate="activateThisTab"></tab-menu> -->
  <div class="container md:mx-auto md:mb-6 w-full xl:max-w-6xl lg:max-w-5xl md:max-w-3xl border-t md:border-0">
    <div class="flex flex-row flex-wrap justify-start usersBoard" v-if="usersLoading === false">
      <usersBoard :users="users" ></usersBoard>
    </div>   
    <div v-else class="flex flex-col items-center pt-8 report-pg-loading">
        <div class="pb-4"><p>{{'Users are being loaded' | localize }}</p></div>
        <img src="/image/tasks.svg" alt="Users" class="w-96">
    </div>
  </div>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import usersBoard from './usersBoard.vue'
// import tabMenu from './tabMenu.vue'

export default {
  components: {
    usersBoard,
  },
  data: () => ({
  }),
  computed: {
    ...mapState({
      users: state => state.admin.users,
      usersLoading: state => state.admin.miscellaneous.usersLoading
    })
  },
  created (){
      this.getUsers({"withDeleted" : false}) // Get Users
      // console.log(this.$store.state,'state')
  },
  methods: {
      ...mapActions([
        'getUsers'   
      ])  
  }
}
</script>
