<template>
<div class="project-details">
  <div v-if="userDataLoaded" class="project-detail w-full">
    <ul class="hours-detail-sec">
         <li class="hours-detail-wrap grey-heading">
            <h4>{{ projectMiscellaneous.totalHours }}</h4>
            <p>Total Hours</p>        
          </li>

        <li class="hours-detail-wrap grey-heading">     
            <h4>{{ projectMiscellaneous.daysSpent }}</h4>
            <p>Days Spent</p>  
        </li>

        <li class="hours-detail-wrap grey-heading">
            <h4>{{ projectMiscellaneous.projectUsers.usersCount }}</h4>
            <p>Users Involved</p>  
        </li>

         <li class="hours-detail-wrap grey-heading">
            <h4>{{ projectMiscellaneous.contactTermLength }}</h4>
            <p>Contact Term (Months)</p>  
        </li>

        <li class="hours-detail-wrap grey-heading">
            <h4>{{ currencyFormatter(projectMiscellaneous.contractValue) }}</h4>
            <p>Contract Value</p>     
        </li>

        <li class="hours-detail-wrap profit-color" v-if="projectMiscellaneous.profitPercentage != undefined">
            <h4>{{ currencyFormatter(projectMiscellaneous.profit) }} </h4>
            <p>{{projectMiscellaneous.profitPercentage}}% Profit</p>            
        </li>
        <li class="hours-detail-wrap" v-else>
            <h4>{{ currencyFormatter(projectMiscellaneous.profit) }}</h4>
            <p>{{ projectMiscellaneous.lossPercentage }}% Loss</p>              
        </li>

        <li class="hours-detail-wrap cost-color"> 
            <h4> {{ currencyFormatter(projectMiscellaneous.cost) }}</h4>
            <p>{{ projectMiscellaneous.costPercentage }}% Cost</p>     
        </li>

         <li class="hours-detail-wrap monthly-usage-color"> 
            <h4> {{ currencyFormatter(projectMiscellaneous.monthlyUsageFee) }}</h4>
            <p>{{ projectMiscellaneous.monthlyUsageFeePercentage }}% Monthly usage fee</p>                 
        </li>

        <li class="hours-detail-wrap commision-color"> 
            <h4> {{ currencyFormatter(projectMiscellaneous.commision) }}</h4>
            <p>{{ projectMiscellaneous.commissionPercentage }}% Commission</p>                 
        </li>
         <li class="hours-detail-wrap asset-color"> 
            <h4> {{ currencyFormatter(projectMiscellaneous.assetCost) }}</h4>            
            <p>{{ projectMiscellaneous.assetCostPercentage }}% Asset Cost</p>         
        </li>
         <li class="hours-detail-wrap cost-color"> 
            <h4> {{ currencyFormatter(projectMiscellaneous.totalCost) }}</h4>
            <p>Total Cost</p>     
        </li>
        

      </ul>

      <div class="chart-wrapper">
          <div class="user-duration-chart">
            <doughnut-chart :data="projectUserChartData" :key="userChartKey"></doughnut-chart>
          </div>

          <div class="project-profit-cost-chart">
            <doughnut-chart :data="projectProfitAndCostChartData" :key="projectCostChartKey"></doughnut-chart>
          </div>
      </div>
</div>
</div>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import doughnutChart from './doughnutChart.vue'
import accessDenied from './accessDenied.vue'

export default {
  name : 'project.detail',

  components: { doughnutChart,accessDenied },

  data: () => ({
      userDataLoaded : false,
      projectUserChartData: {},
      projectProfitAndCostChartData: {},
      projectMiscellaneous : [],
      projectId: '',
      userChartKey : 500,
      projectCostChartKey : 1000
  }),

  computed: {
    ...mapState({
      currentView: state => state.currentView
    })   
    
  },
  created () {  
      this.projectId = this.$route.params.id    
      this.getAllProjectDetails(true) 
  },

  methods: {
    ...mapActions([
      'closeComponent',
      'showNotification',
      'setCurrentComponent',
      'toggleLoading',
      'getErrorResponseAccordingToError'
    ]),
    closeModal () {
      this.closeComponent()
    },
    getAllProjectDetails (shouldRun = false){      
      if (shouldRun ===  true || shouldRun == 'refreshCharts') {
          this.getProjectDetails(true).then((response) => {
              if (response.status == 'success') {  
                  this.projectMiscellaneous = response.projectMiscellaneous
                  this.projectUserChartData = response.projectUserChartData
                  this.projectProfitAndCostChartData = response.projectProfitAndCostChartData
                  this.userDataLoaded = true
                   if (shouldRun === 'refreshCharts') {
                        this.forceRenderer()
                    }
              }          
          }).catch((error) => {                        
                this.toggleLoading(false)            
                this.showNotification({type: error.response.data.status, message: error.response.data.message})
            })
      }
     
    },
    forceRenderer(){
      this.userChartKey  += 1
      this.projectCostChartKey += 1
    },
    currencyFormatter: (value) => {
      if (value != null || value != undefined ) {
        let formatter = new Intl.NumberFormat(Constants.miscellaneous.currency.default.format, {
          style: "currency",
          currency: value.currency_code,
          minimumFractionDigits: 2,
          currencyDisplay: 'narrowSymbol'
        });
        return formatter.format(value.data)        
      }
    },
    async getProjectDetails (update){
      try {
        this.toggleLoading(true)
        if (update) {
          let { data } = await axios({
            url: 'data/project/details/'+this.projectId,
            params: {
              project_id: this.projectId,
            }
          })
          this.toggleLoading(false)
          if (data.status == 'success') {               
              return data
          }
        }

        return []
      } catch (error) {
        this.toggleLoading(false)
        this.getErrorResponseAccordingToError(error);
        this.showNotification({type: error.response.data.status, message: error.response.data.message})
      }
    },
  },
  mounted() {         
     EventBus.$on('refresh-project-details', this.getAllProjectDetails)
  }
 
}
</script>
