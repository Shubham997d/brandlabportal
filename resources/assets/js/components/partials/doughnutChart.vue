<script>
import { Doughnut } from 'vue-chartjs'

export default {
  extends: Doughnut,
  props:['data'],
  
  data: () => ({
  
  }),
  methods: {
    update () {
      this.$data._chart.update()
    },
    checkData (){
      console.log(this.data)
    }
  },
  mounted () {
    // const options = {
    //     responsive: true, 
    //     maintainAspectRatio: false, 
    //     animation: {
    //       animateRotate: false
    //     }
    // }

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
    this.renderChart(this.data)
  }
}
</script>



