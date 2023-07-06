<script>
import { Pie } from 'vue-chartjs'

export default {
  extends: Pie,
   data: () => ({
      
  }),
  props: {
    labels: {
      required: true,
      type: Array
    },
    datasets: {
      required: true,
    },
    maxCount:{
      required: true      
    }
  },
  created() {
      
  },
  methods: {
    //   handle (toolTipInfo) {
    //     EventBus.$emit('displayModel',toolTipInfo)        
    // }
  },
  
  mounted () {    
      this.addPlugin({
        id: 'my-plugin',
        afterDraw: function (chart) {
            if (chart.data.datasets[0].data.every(item => item === 0)) {
                let ctx = chart.chart.ctx;
                let width = chart.chart.width;
                let height = chart.chart.height;
                chart.clear();
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('No data to display', width / 2, height / 2);
                ctx.restore();
            }
        }
    });
    this.renderChart({  
      labels: this.labels,
      datasets: this.datasets
      }, {  // Add Options in this parameter
      responsive: true, 
      maintainAspectRatio: false,
      onClick: function(evt,array) {
         if (array.length != 0) {
              const chartIndex = this.getElementAtEvent(evt);
              const datasetIndex = chartIndex[0]._datasetIndex;
              const position = chartIndex[0]._index;
              const toolTipInfo = {
                  datasetIndex: datasetIndex,
                  valueIndex: position,
                  label: this.tooltip._data.labels[position],
                  value: this.tooltip._data.datasets[datasetIndex].data[position]
              };
              EventBus.$emit('displayModel',toolTipInfo)
    
        }  

      },
      legend: {
          onHover: function(e) {
            e.target.style.cursor = 'pointer';
            },
      },
      hover: {
          onHover: function(e) {
            var point = this.getElementAtEvent(e);
            if (point.length) e.target.style.cursor = 'pointer';
            else e.target.style.cursor = 'default';
          }
      },
      scales: {
           
        }
    })
  }
}
</script>
