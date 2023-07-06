<template>
<!-- <div v-if="loading || localLoadingState">
  <div class="fixed h-screen w-screen z-60 flex flex-row items-center justify-center">
    <div class="bg-white p-8 rounded-lg">
      <div class="circle w-32 h-32 flex flex-row justify-between items-center">
      </div>
    </div>
  </div>
  <div class="h-screen w-screen fixed inset-0 bg-gray-900 opacity-25 z-50"></div>
</div> -->
<vue-topprogress ref="topProgress" :zIndex="this.zIndex" :color="this.color" :errorColor="this.errorColor" :height="this.height" :speed="this.speed"></vue-topprogress>
</template>

<script>
import { vueTopprogress } from 'vue-top-progress'
import { mapState } from 'vuex'

export default {
  components: {
    vueTopprogress
  },
  props: {
    localLoadingState: {
      required: false,
      type: Boolean
    }
  },
  data: () => ({
    color: Constants.progressBarFeatures.color,
    errorColor: Constants.progressBarFeatures.errorcolor,
    height: Constants.progressBarFeatures.height,
    speed: Constants.progressBarFeatures.speed,
    zIndex: Constants.progressBarFeatures.zIndex
  }), 
  computed: mapState([
    'loading'
  ]),
 
  methods: {
    toggleTopProgress(param) {
        if (param == true) {
          this.$refs.topProgress.start()        
        }else if(param == false){
            this.$refs.topProgress.done()  
        }else if (param == 'pause'){
            this.$refs.topProgress.pause()
        }else if (param == 'fail'){
           this.$refs.topProgress.fail()
        }else{

        }
    },

  },
   watch: {
    loading (newValue, oldValue) {
        if (newValue == true) {
          this.toggleTopProgress(true)
        }else if(newValue == false){
          this.toggleTopProgress(false)
        }else if(newValue == 'pause'){
          this.toggleTopProgress('pause')
        }else if (newValue == 'fail'){
          this.$refs.topProgress.fail()   
        }else{
            
        }        
    }
  }

}
</script>

