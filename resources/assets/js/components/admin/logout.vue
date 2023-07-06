<template>
  <div>
  <!-- just for logout -->
  </div>

</template>

<script>
export default {
  name: 'logout',
  data: () => ({
  }),

  created () {    
      this.logoutUser()
  },

  methods: { 
    logoutUser () {
      // form in profileDropdown Component
      document.getElementById('logout-form').submit()
    }
  },
}
</script>
