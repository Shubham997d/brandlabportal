<template>
<div class="graphs-section">
    <horizontal-bar-graph :labels="chartData.horizontalBarGraphData.main" :datasets="chartData.horizontalBarGraphData.datasets" :maxCount="chartData.horizontalBarGraphData.maxCount" v-if="chartData.horizontalBarGraphData !== undefined"></horizontal-bar-graph>
    <pie-graph :labels="chartData.pieGraphData.main" :datasets="chartData.pieGraphData.datasets" :maxCount="chartData.pieGraphData.maxCount" v-if="chartData.pieGraphData !== undefined"></pie-graph>
    <vertical-stacked-bar-graph :labels="chartData.verticalStackedBarGraphData.main" :maxCount="chartData.verticalStackedBarGraphData.maxCount" :datasets="chartData.verticalStackedBarGraphData.datasets"  v-if="chartData.verticalStackedBarGraphData !== undefined"></vertical-stacked-bar-graph>
</div>
</template>
<script>
// import { mapActions,mapState } from 'vuex'
import horizontalBarGraph from './horizontalBarGraph';
import pieGraph from './pieGraph';
import verticalStackedBarGraph  from './verticalStackedBarGraph';


export default { 

  components: {
    horizontalBarGraph,
    pieGraph,
    verticalStackedBarGraph    
  },
  props: {
    chartData: {
      required: false,
      type: Object,
      default: {}      
    }
   },

  computed: {
    // ...mapState({
    //   chartData: state => state.admin.listing.chartData,
    // })    
  },
   
  created (){
  },
  methods: {  
    
  },
  mounted () {  
  }
}
</script>
