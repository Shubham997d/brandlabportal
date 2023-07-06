<template>

<div class="cs-filters">

    <model-select
            :options="availablefilterSelectTypes"
            v-model="selectFilterFirst"
            placeholder="Filters"
            class="form-control first-filter"
            id="selectFilterTypeFirst"
            option-value="value"
            option-text="text"
    ></model-select>

    <date-range-picker
            ref="picker"            
            :dateFormat="dateFormat"
            :today="true"
            :opens="opens"
            :locale-data="{ firstDay: 1, cancelLabel: 'Cancel', applyLabel: 'Filter Report', format: 'dddd, mmmm dS, yyyy' }"
            :date-range="dateRange"
             @update="filter($event)" 
             @finishSelection="logEvent('event: finishSelection', $event)"
             v-if="currentSecondfilterType == 'daterange'"
    >        
    </date-range-picker>

     <model-select
            :options="filterSelectedData"
            v-model="selectFilterSecond"
            placeholder="Filters"
            class="form-control second-filter"
            id="selectFilterType"
            option-value="value"
            option-text="text"
            v-if="currentSecondfilterType == 'select'"
    ></model-select>

     <b-input-group class="mt-3 input-type-filter" v-if="currentSecondfilterType == 'input'" :prepend="selectFilterFirst.prependName">
        <b-input-group-append is-text class="filter-prepend"><b>$</b></b-input-group-append>
        <b-form-input v-model="inputTypeFilter" class="input-type-filter-field" :type="selectFilterFirst.type" min="0.00" max="1000000000.00"></b-form-input>
        <b-input-group-append>
          <b-button @click="inputTypeFilterClick">Filter</b-button>
        </b-input-group-append>
    </b-input-group>


    <model-select
            :options="availablechartSortTypeFilters"
            v-model="chartSortType"
            placeholder="Chart Type"
            class="form-control sort-filter"
            :class="disableChartSortTypeFilter"
            id="sortFilterType"
            option-value="value"
            option-text="text" 
            v-if="chartSortTypeAvailable === true"           
    ></model-select>


</div>
</template>

<script>
import { mapActions,mapState } from 'vuex'
import DateRangePicker from 'vue2-daterange-picker'
import dateUtil from 'vue2-daterange-picker/src/components/date_util/native'
import { ModelSelect } from "vue-search-select";

export default { 
  components: { DateRangePicker,ModelSelect },
  data: () => ({
      opens: 'right',
      filterSelectTypes : Constants.dealFilters.dealSelectFilters,
      selectFilterFirst:  {}, 
      filterSelectFields: Constants.dealFilters.dealSelectFiltersFields,
      selectFilterSecond: {},
      filterSelectedData: [],
      currentSecondfilterType: 'daterange',
      inputTypeFilter: '',
      chartSortTypeOptions: Constants.dealCharts.chartSortType,
      chartSortType:  {},
      currentDateRangeSelected : {}
  }),
  created (){
          
  },
  computed: {
    ...mapState({
          dateRange: state => state.admin.listing.defaulFilterDateValues,
          listing: state => state.admin.listing,
          activeTab: state => state.admin.miscellaneous.activeTab,
          filterParams: state => state.admin.miscellaneous.filter.params 
    }),
    disableChartSortTypeFilter(){ // check if report type has disabled particular sort method available
       var currentReportType = this.$route.params.reportType       
       return Constants.dealReports.reportType[currentReportType].filters.disabled.chartSortType === true ? 'disabled' : ''; 
    },
    availablefilterSelectTypes() {  // check if report type has particular filters available
          return this.getAvailableFiltersAccordingToReportType(this.filterSelectTypes,'availableFilters','filterName') 
    },
    availablechartSortTypeFilters() {  // check if report type has particular sort filters available
          return this.getAvailableFiltersAccordingToReportType(this.chartSortTypeOptions,'availableSortTypeFilters','value') 
    },
    chartSortTypeAvailable (){ // check if report type has chartSortTypeAvailable available
          return Constants.dealReports.reportType[this.$route.params.reportType].filters.chartSortTypeAvailable
    }   
  },
  watch: {
    selectFilterFirst: function(currentVal) {           
       
      if (typeof currentVal != 'undefined' && currentVal != null) {          
          this.getSecondFilterOptions(currentVal)
           // reIntialize here for default report params
          if(typeof this.selectFilterFirst.reIntialize == 'undefined'){
                $('#selectFilterTypeFirst').next().css("display","block");
                $('.default-filter-text').css("display","none");
          }else{
                $('#selectFilterType').next().text('Filters') // add filter text dynamically
          }
      }      
    },
    selectFilterSecond: function(currentVal) {      
      // reIntialize here for default report params
      if (typeof this.selectFilterFirst.value != 'undefined' && typeof this.selectFilterFirst.value != null && this.$_.isEmpty(typeof this.selectFilterFirst.value) === false && typeof currentVal.reIntialize == 'undefined') {   
          this.filter()
      }
    },
    chartSortType: function(currentVal) {        
      if (typeof currentVal != 'undefined' && currentVal != null && typeof currentVal.reIntialize == 'undefined') {
            this.filter()        
      }
    }
      
  
  },
  methods: {
    ...mapActions([
        'getReportsListingDataForAdmin',
        'showNotification',
        'getReportsSummaryDataForAdmin',
        'updateMiscellaneousFilterParams',
        'updateMiscellaneousActiveTab'
    ]),    
    dateFormat (classes, date) {
      return classes
    },
    logEvent (msg,event){

    },
  
    filter(event = null){
      var params = {}
      // event will be null for second select filter
      if(event != null){
        
        params = {
            'startDate': dateUtil.format(event.startDate, 'isoDate'),
            'endDate': dateUtil.format(event.endDate, 'isoDate')
          }
          this.currentDateRangeSelected.startDate = dateUtil.format(event.startDate, 'isoDate')
          this.currentDateRangeSelected.endDate = dateUtil.format(event.endDate, 'isoDate')
        } 
        if (this.currentSecondfilterType === 'daterange') {           
              params.startDate = params.startDate == null || params.startDate == 'undefined' ? this.currentDateRangeSelected.startDate : params.startDate
              params.endDate = params.endDate == null || params.endDate == 'undefined' ? this.currentDateRangeSelected.endDate : params.endDate
              params = this.getFirstFilterValues(params)
        }
        if (this.currentSecondfilterType === 'select' || this.currentSecondfilterType === 'input' ) {       
          params = this.getSecondFilterValues(params)
            if (this.currentSecondfilterType === 'input') {
                params.isRangeType = this.selectFilterFirst.isRangeType
                params.range = this.selectFilterFirst.range              
            }
        }
        params.filterType = this.currentSecondfilterType
        params.filterField = this.selectFilterFirst.filterField
        params.isDefaultFilterField = this.selectFilterFirst.isDefaultFilterField
        params.isDynamic = this.selectFilterFirst.isDynamic
        params.chartSortType = this.chartSortType.value
        
        var status  = this.$route.path.split("/").pop();        
        var dealsParam = { type: status, request: params  } 
        this.updateMiscellaneousFilterParams(params)
        this.getDataAccordingToActiveTab(dealsParam,params)
                
    },
    getSecondFilterOptions(data){
          this.currentSecondfilterType = data.filterType
          if(this.currentSecondfilterType === 'select'){
                //is Dynamic Means: Data is coming from request instead of Constant file
                if (data.isDynamic === true) {
                    this.filterSelectedData = this.listing.filter[data.value.value]
                  
                }else{
                  this.filterSelectedData = this.filterSelectFields[data.value.value]
                }
          }
          if(typeof this.currentSecondfilterType == 'undefined' || this.currentSecondfilterType == null){
                    this.currentSecondfilterType = 'daterange' // Active Default 
          }
    },
    getFirstFilterValues(params){
      if (this.selectFilterFirst.value) {
          params = {...params,...this.selectFilterFirst.value}
      }
      return params
    },
    getSecondFilterValues(params){
      if (this.currentSecondfilterType === 'select') {
          if (this.selectFilterSecond.value) {
            var combinedParams =  {
                  field : this.selectFilterFirst.value.field,
                  value : this.selectFilterSecond.value
              }
              params = {...params,...combinedParams}
          }
      }
       if (this.currentSecondfilterType === 'input') {
          if (this.inputTypeFilter) {
            var combinedParams =  {
                  field : this.selectFilterFirst.value.field,
                  value : this.inputTypeFilter
              }
              params = {...params,...combinedParams}
          }
      }
      return params
    },
    getDataAccordingToActiveTab(dealsParam){      
        if(this.activeTab.tabName === Constants.adminReportsTabs[0]){            
              this.getReportsListingDataForAdmin(dealsParam)            
        }
        if(this.activeTab.tabName === Constants.adminReportsTabs[1]){
              this.getReportsSummaryDataForAdmin(dealsParam)
        }
    },
    checkIfFiltersAreReady(data){       
        var ifFiltersAreReady;
        this.$_.isEqual(this.defaultFiltersObject(), data) === true ? ifFiltersAreReady = false : ifFiltersAreReady = true
        return ifFiltersAreReady
    },
    defaultFiltersObject(){
      return  {
              filterField: undefined,
              filterType: "daterange",
              isDefaultFilterField: undefined,
              isDynamic: undefined
        }
    },
    getActiveTab(tabDetails){      
        this.updateMiscellaneousActiveTab(tabDetails)
        this.filter()
    },
    reIntializeDealsData(){      
        // Default Params
        this.selectFilterFirst = {}
        this.filterSelectTypes = Constants.dealFilters.dealSelectFilters
        this.filterSelectFields = Constants.dealFilters.dealSelectFiltersFields       
        this.selectFilterSecond = {}
        this.filterSelectedData = []
        this.currentSecondfilterType = 'daterange';
        this.inputTypeFilter = ''
        // var defaultChartSortType = Constants.dealCharts.defaultChartSortType
        // defaultChartSortType.reIntialize = true
        this.chartSortType = Constants.dealReports.reportType[this.$route.params.reportType].filters.defaultParams.chartSortType 
        this.setReportDefaultFilters() 
        
    },
    inputTypeFilterClick(){
        if (this.inputTypeFilter != '') {
              this.filter()
        }else{
              this.showNotification({type: 'error', message: 'Please fill the input field'})
        }
    },
    setReportDefaultFilters(){
       var currentReportType = this.$route.params.reportType         
       this.selectFilterFirst = Constants.dealReports.reportType[currentReportType].filters.defaultParams.selectFilterFirst != null ? Constants.dealReports.reportType[currentReportType].filters.defaultParams.selectFilterFirst : {}
       this.selectFilterSecond = Constants.dealReports.reportType[currentReportType].filters.defaultParams.selectFilterSecond != null ? Constants.dealReports.reportType[currentReportType].filters.defaultParams.selectFilterSecond : {}
       this.updateMiscellaneousFilterParams({...this.selectFilterFirst,...this.selectFilterSecond})  // Set Empty
       $('#selectFilterTypeFirst').next().css("display","none"); // Set Main Display None
       $('.default-filter-text').remove(); // Remove it
       $('#selectFilterTypeFirst').next().after('<div data-vss-custom-attr="" class="text default-filter-text">'+this.selectFilterFirst.text+' </div>'); // Add cloned for Default       
    },
    getAvailableFiltersAccordingToReportType(filterArray,filterType,shouldIncludeValue){
          var availableFilters = Constants.dealReports.reportType[this.$route.params.reportType].filters[filterType]
          var availablefilterSelectTypes = []
          filterArray.forEach(function (item) {
                 availableFilters.includes(item[shouldIncludeValue]) == true ? availablefilterSelectTypes.push(item) : false;
          });
          return availablefilterSelectTypes
    },

    checkIfCustomPlaceholderTextShouldBeVisible(length,display = 'none'){
        if(length > 0 && typeof this.selectFilterFirst.reIntialize != 'undefined'){
              $('.default-filter-text').css("display",display);
        }
        if((length === 0 && typeof this.selectFilterFirst.reIntialize == 'undefined') || (length === 0 && typeof this.selectFilterFirst.reIntialize != 'undefined') ){
            if( $(".default-filter-text").prev().css('display') == 'none') { 
                   $('.default-filter-text').css("display",display);
            }
        } 
    }
  },
  mounted () {  
       EventBus.$on('displayTabData', this.getActiveTab)
       this.reIntializeDealsData()
       var app = this;
       // For First Select Filter Custom Placholder As Vue select search is not working properly with v-model multi array value/property object
        $("#selectFilterTypeFirst").on("input", function() {        
            app.checkIfCustomPlaceholderTextShouldBeVisible($(this).val().length,'none')
        });

        $("#selectFilterTypeFirst").blur(function(){
            app.checkIfCustomPlaceholderTextShouldBeVisible($(this).val().length,'block')        
        });
     
  },
  destroyed() {
      // Destroy event once
       EventBus.$off('displayTabData'); 
  },
}
</script>
