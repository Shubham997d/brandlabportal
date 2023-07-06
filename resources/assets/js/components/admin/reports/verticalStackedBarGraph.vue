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
    maxCount:{
      required: false      
    }
  },
  methods: {
     
  },
  
  mounted () {    


    this.addPlugin({
        id: 'my-plugin',
        afterDraw: function (chart) {
              var empty = []          
              for (let i = 0; i < chart.data.datasets.length; i++) {
                  if (chart.data.datasets[i].data.every(item => item === 0)) {
                        empty.push(true) 
                  }else{
                        empty.push(false) 
                  }
              }
              if (empty.every(item => item === true)) { 
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
               
        },
        
        afterDatasetsDraw: function(chart) {
             
            
              // let ctx = chart.chart.ctx;
              // let width = chart.chart.width;
              // let height = chart.chart.height;
              // var sourceCanvas = ctx.canvas;
              // console.log(sourceCanvas,'sourceCanvas')
              // return false
              // var copyWidth = this.scale.xScalePaddingLeft - 5;
              // // the +5 is so that the bottommost y axis label is not clipped off
              // // we could factor this in using measureText if we wanted to be generic
              // var copyHeight = this.scale.endPoint + 5;
              // var targetCtx = document.getElementById("myChartAxis").getContext("2d");
              // targetCtx.canvas.width = copyWidth;
              // targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
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
                     beginAtZero:true,
                     precision: 0                                           
                },
            gridLines: {
                display: true,
                drawBorder: true,
              },
            stacked: true
            }],
            xAxes: [{
              ticks: {                   
                  beginAtZero:true,
                  precision: 0
              },
              gridLines: {
                display: true,
                drawBorder: true,
              },
              stacked: true
          }]          
        },
      animation:{
        // onComplete : function(e){
        //       var sourceCanvas = e.chart.ctx.canvas;
        //       var copyWidth = e.chart.scales['x-axis-0'].paddingLeft - 5;
        //       var copyHeight = e.chart.scales['x-axis-0'].height + 5;
        //       var targetCtx = document.getElementById("bar-chart").getContext("2d");
        //       console.log(targetCtx,'targetCtx')
              
        //       return false
        //       targetCtx.canvas.width = copyWidth;
        //       targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
        // }
    }
    })
  }
}
</script>
