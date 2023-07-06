<template>

<div class="text-4xl text-gray-700 container mx-auto text-center font-bold error-page">
    <div class="text-big-error">404</div>
    <div class="mb-8">WHOOPS! Page Not Found</div>
    <router-link :to="'/'" class="go-home">← Go To Home</router-link>
</div>

</template>

<script>
export default {
  name: 'not.found',
  data: () => ({
    
  }),
  created () {
    // console.log('tesadsf');
  },
  // computed: {
   
  // },

  // methods: {
   
  // }
}
</script>
