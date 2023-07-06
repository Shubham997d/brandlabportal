<template>

<div class="text-4xl text-gray-700 container mx-auto text-center font-bold error-page">
    <div class="text-big-error">403</div>
    <div class="mb-8">WHOOPS! Access Denied</div>
    <router-link :to="'/'" class="go-home">← Go To Home</router-link>
</div>

</template>

<script>
export default {
  name: 'access.denied',
  data: () => ({
    
  }),
  created () {
  },
  // computed: {
   
  // },

  // methods: {
   
  // }
}
</script>
