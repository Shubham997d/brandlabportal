<template>
<div class="registeration-complete-msg">
  <p><span>{{ message.heading }}</span>{{ message.content }}</p>
  <div class="show-home-button">    
      <a :href="siteUrl" class="text-teal-500 no-underline text-2xl">← Go To Home</a>
  </div>
</div>
</template>

<script>
export default {
  data: () => ({
    token: Laravel.csrfToken,    
    url: url,
    siteUrl: window.location.origin
  }),
  props: {
    message: {
      required: true,         
    },      
  },
   methods: {
    display (){
      console.log(this.message)        
    }
  },
  mounted (){
      
    }    
  }
</script>
