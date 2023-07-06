<template>

<div class="container md:mx-auto md:mb-6 w-full xl:max-w-6xl lg:max-w-5xl md:max-w-3xl md:border-0">
    
    <div v-if="reportsLoading === false">
      <filters></filters>
      <div v-if="listing.items && listing.items != undefined" class="cs-graphs-with-listing">
          <div v-if="listing.items.length > 0 || summaryItems.length > 0">
              <graphs :key="refreshGraphsData" :chartData="chartData"></graphs>
              <listing :isListingBusy="isListingBusy" @exportData="exportData"></listing>
              <b-modal id="deals-listing-model" size="xl" :title="modelTitle" ok-title="Export" @ok="handleOk">
                  <listing :isInModel="true"  :isModalBusy="isModalBusy" :isListingBusy="isListingBusy" :userDeals="userDeals"></listing>
              </b-modal>   
          </div>
          <div v-else>
            <h1 class="alert alert-warning" style="margin-top: 20px;">{{ noDataMsg }}</h1>
          </div> 
      </div>
    </div>   
    <div v-else class="flex flex-col items-center pt-8 report-pg-loading">
        <div class="pb-4"><p>{{'Reports are being loaded' | localize }}</p></div>
        <img src="/image/tasks.svg" alt="Report" class="w-96">
    </div>
</div>
</template>

<script>

import { mapActions,mapState,mapGetters } from 'vuex'
import listing from './listing.vue'
import graphs from './graphs.vue'
import filters from './filters.vue'
import accessDenied from '../../partials/accessDenied.vue'

export default {   
  name: 'admin.reports',
  data: () => ({
      refreshGraphsData: 1000, // Set Key
      noDataMsg: Constants.miscellaneous.filter.noData,
      modelTitle: '',
      userDeals: [],
      currentModelValue: ''      
  }), 
  components: {
    listing,
    graphs,
    filters,
    accessDenied
  },
  created (){
        // Deals Data Is Getting Loaded From Index File in Home Folder
  },
  computed: {
    ...mapState({
      chartData: state => state.admin.listing.chartData,
      listing: state => state.admin.listing,
      filterParams: state => state.admin.miscellaneous.filter.params,
      summaryItems: state => state.admin.summary.items,
      isListingBusy: state => state.admin.miscellaneous.busy.listing, 
      isModalBusy: state => state.admin.miscellaneous.busy.userDealsModel
    }),
    reportsLoading () {
            return this.$store.getters.adminReportsLoading(true)
        },
    currentReport(){
            return this.$route.params.reportType
    }
  },
  watch : {
      chartData(currentVal){             
        this.$nextTick(() => { 
          if(currentVal != undefined ){      
              this.refreshGraphsData = this.refreshGraphsData + 1
            }
        })
      }      
    
  },
  methods: {
    ...mapActions([
      'toggleLoading',
      'showNotification',
      'updateMiscellaneousBusyState'
    ]),
    console(){
   
    },
    async getOnlyReportListingData (paramsData) {  
      try {        
          this.updateMiscellaneousBusyState({ listing: this.isListingBusy, userDealsModel:true })
           this.toggleLoading(true)            
            var newParams = this.getExtraParamsAccordingToReport(true)
              let { data } = await axios({
              url: '/data/admin/report/deals/'+this.currentReport+'/reports-listing-data',
              params:  newParams })
              this.toggleLoading(false)
              this.updateMiscellaneousBusyState({ listing: this.isListingBusy, userDealsModel:false })
            return data
      } catch (error) {
        console.log('error',error)
        this.showNotification({type: error.response.data.status, message: error.response.data.message})
        this.toggleLoading(false)
      }
    },    
    displayModel(paramsData){  //  Display Model With Data
      
      if(paramsData != null){
        this.getModelTitle(paramsData)
        this.getOnlyReportListingData(paramsData).then((data) => {
          this.userDeals = data.listing
            this.$bvModal.show('deals-listing-model')
        })
      }     
    },    
    export(forModel){        
        this.toggleLoading(true)
        var filterParams;        
        if (forModel === true) {                        
            filterParams = this.getExtraParamsAccordingToReport(true)
        }else{
          
            filterParams = this.filterParams
        }
        var url = window.location.origin+'/data/admin/report/deals/'+this.currentReport+'/export/data'
        axios.get(url,{ params: filterParams, responseType: 'blob'}).then((response) => { 
            if (response.data.status == 'error') {
                  this.showNotification({type: response.data.status, message: response.data.message})
                  this.toggleLoading(false)
                  return false
            }
            var disposition = response.headers.content-disposition
            var matches = /"([^"]*)"/.exec(disposition);
            var filename = (matches != null && matches[1] ? matches[1] : 'deals.xlsx');
            var blob = new Blob([response.data], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            window.open(link.href, '_blank').focus();
            this.toggleLoading(false); 
        }).catch((error) => {                                     
                this.toggleLoading(false);          
                if (error.response.status == 400) {
                    this.showNotification({
                      type: 'error',
                      message: 'No Data Available',
                    });                  
                }
                else{
                    this.showNotification({
                      type: 'error',
                      message: error.response.statusText,
                    });    
                }

          });
        
    },
    getExtraParamsAccordingToReport(forModel = true){       

        var extraFilter =  this.getExtraFilter()
        var extraVal = this.currentModelValue;
        if (Constants.dealReports.reportType[this.currentReport].dynamicValue  === true) {
               extraVal = Constants.dealReports.reportType[this.currentReport].values[extraVal]               
        }
        extraFilter.extraValue = extraVal
        extraFilter.tabType = (forModel === true) ? 'model' : 'listing';
        return {...this.filterParams,...extraFilter}
    },
    handleOk(){
        this.export(true)
    },
    getModelTitle(paramsData){            
         this.modelTitle = paramsData.label.trim()+ ' '+Constants.dealReports.reportType[this.currentReport].modelTitle
         this.currentModelValue = paramsData.label
    },
    getExtraFilter(){
        var extraFilter = ''
        if (typeof Constants.dealReports.reportType[this.currentReport].multipleChartSortType != 'undefined' && Constants.dealReports.reportType[this.currentReport].multipleChartSortType != null && Constants.dealReports.reportType[this.currentReport].multipleChartSortType == true) {
            var chartSortType = ''
              if (typeof this.filterParams.chartSortType == 'undefined' || this.filterParams.chartSortType == null) {
                chartSortType = Constants.dealReports.reportType[this.$route.params.reportType].filters.defaultParams.chartSortType.value
              }else{
                chartSortType = this.filterParams.chartSortType
              }
             extraFilter = Constants.dealReports.reportType[this.currentReport].extraFilter[chartSortType]
        }else{
             extraFilter = Constants.dealReports.reportType[this.currentReport].extraFilter;
          }
      return extraFilter
    },
    exportData(){
        this.export(false)
    }    
  },
  mounted () { 
       EventBus.$on('displayModel', this.displayModel)
  },
  destroyed() {      
       EventBus.$off('displayModel');  // Destroy event once
  },
}
</script>
