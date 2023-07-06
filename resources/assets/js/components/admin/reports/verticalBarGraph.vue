<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,

  props: {
    labels: {
      required: true,
      type: Array
    },
    datasets: {
      required: true,
    },
    maxDeals:{
      required: true      
    }
  },
  methods: {
      handle (point, event) {
        EventBus.$emit('displayModel',event)
    }
  },
  
  mounted () {    
    
    this.renderChart({
      labels: this.labels,
      datasets: this.datasets
      }, {  // Add Options in this parameter
      responsive: true, 
      maintainAspectRatio: false,
      onClick: this.handle,
      legend: {
          onHover: function(e) {
            e.target.style.cursor = 'pointer';
            }
      },
      hover: {
          onHover: function(e) {
            var point = this.getElementAtEvent(e);
            if (point.length) e.target.style.cursor = 'pointer';
            else e.target.style.cursor = 'default';
          }
      },
      scales: {
            yAxes: [{
                ticks: {                    
                     beginAtZero:true                     
                },
            gridLines: {
                display: true,
                drawBorder: true,
              }
            }],
            xAxes: [{
              ticks: {                   
                    beginAtZero:true,
                    precision: 0                   
              },
              gridLines: {
                display: true,
                drawBorder: true,
              }
          }]          
        }
    })
  }
}
</script>
