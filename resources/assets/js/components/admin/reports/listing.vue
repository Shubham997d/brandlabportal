<template>  
  <div class="container deals-listing" v-if="listing">
      <div class="deals-summary-wrap" v-if="isInModel">
         <b-table striped hover :items="userDeals" :fields="fields" class="deals-listing" :busy="isModalBusy" :per-page="modelPerPage" :current-page="modelCurrentPage" id="listing-table">
              <template #cell(deal_link)="data">
                <b-link :target="'_blank'" :href="getDealUrl(data.item.id)">{{data.item.title}}</b-link>
             </template>
             <template #cell(deal_price_with_currency)="data">
                {{ currencyFormatter(data.item.deal_price_with_currency) }}
             </template>
             <template #table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ loading }}</strong>
              </div>
            </template>
          </b-table>
           <b-pagination
              v-model="modelCurrentPage"
              :total-rows="modelRows"
              :per-page="modelPerPage"
              aria-controls="listing-table"
              v-if="isModalBusy == false"
          ></b-pagination>      
    </div>    
    <div class="deals-summary-wrap" v-else>

      <b-tabs content-class="mt-3"  @activate-tab="changedTab" v-model="activeTab.tabIndex" class="listing-tabs">
          <template #tabs-end v-if="activeTab.tabIndex == 0">
            <button class="main-button" @click="exportData">Export</button>
        </template>
        <b-tab title="Deals" active>
          <b-table striped hover :items="listing" :fields="fields" class="deals-listing" :busy="isListingBusy" id="listing-table" :per-page="listingPerPage" :current-page="listingCurrentPage">
              <template #cell(deal_link)="data">
                <b-link :target="'_blank'" :href="getDealUrl(data.item.id)">{{data.item.title}}</b-link>
             </template>
             <template #cell(deal_price_with_currency)="data">
                {{ currencyFormatter(data.item.deal_price_with_currency) }}
             </template>
             <template #table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ loading }}</strong>
              </div>
            </template>
          </b-table>
           <b-pagination
              v-model="listingCurrentPage"  
              :total-rows="listingRows"
              :per-page="listingPerPage"
              aria-controls="listing-table"
              v-if="isListingBusy == false"
          ></b-pagination>                
        </b-tab>
        <b-tab title="Summary">          
           <h1 class="alert alert-dark" v-if="isCurrencyNoteForSummaryTabAvailable == true">Summary Information is calculated based on default currency i.e {{ defaultCurrency.name }}</h1> 
           <b-table striped hover :items="summary.items" :fields="summary.fields" :busy="summary.isBusy" class="deals-summary deals-listing">
              <template #cell()="data">
                {{ currencyFormatterForSummary(data,false) }}
              </template>  
             <template #table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ loading }}</strong>
              </div>
            </template> 
             <template #bottom-row="data">
                <td v-for="(field,index) in data.fields" :key="index">
                      {{calculateTotalValue(field)}}
                </td>                    
           </template>
          </b-table>          
        </b-tab>
      </b-tabs>
    </div>    
  </div>
  
</template>

<script>
import { mapActions, mapState } from "vuex";

export default 
{
  props: {
    isInModel: {
      required: false,
      type: Boolean,
      default: false      
    },
    userDeals: {
      required: false,
      type: Array
    },
    maxDeals:{
      required: false      
    },
    isModalBusy:{
      required: false,
      default: false
    },
    isListingBusy:{
      required: false,
      default: false      
    }
  }, 
  data() { 
  return {
      dealsDataLoaded: false,
      origin : window.location.origin,
      loading: Constants.miscellaneous.loadingText,
      defaultCurrency : Constants.miscellaneous.currency.default,
      // tabIndex: 0, // default 0 for showing first tab selected & allow export for only this tab
      modelPerPage: Constants.miscellaneous.admin.reports.pagination.default.model.perPage,
      modelCurrentPage: Constants.miscellaneous.admin.reports.pagination.default.model.currentPage,
      listingPerPage: Constants.miscellaneous.admin.reports.pagination.default.listing.perPage,
      listingCurrentPage: Constants.miscellaneous.admin.reports.pagination.default.listing.currentPage  
    }
  },

  created() {
      

  }, 
  computed: {
    ...mapState({
      listing: state => state.admin.listing.items,
      summary: state => state.admin.summary,
      fields: state => state.admin.listing.fields,
      activeTab: state => state.admin.miscellaneous.activeTab
    }),
    isCurrencyNoteForSummaryTabAvailable(){
      return Constants.dealReports.reportType[this.$route.params.reportType].summaryNotAvailable
    },
    modelRows(){
      return this.userDeals.length
    },
    listingRows(){
      return this.listing.length
    }
    
    }, 
  methods: {
    ...mapActions([

    ]),
    getDealUrl(dealId) {
        if (dealId != null || dealId != undefined ) {
          var url = window.location.origin+'/deal/'+dealId 
          return url
        }
      },

    changedTab(newTabIndex){          
          EventBus.$emit('displayTabData',{tabName: Constants.adminReportsTabs[newTabIndex], tabIndex: newTabIndex })
      },

    exportData(){          
          this.$emit('exportData')
      },
    currencyFormatter: (value) => {
      if (value != null || value != undefined ) {
        let formatter = new Intl.NumberFormat(Constants.miscellaneous.currency.default.format, {
          style: "currency",
          currency: value.currency_code,
          minimumFractionDigits: 2,
          currencyDisplay: 'narrowSymbol'
        });
        return formatter.format(value.turnover)        
      }
    },
    currencyFormatterForSummary: (data,onlyValue = false) => {
      if (onlyValue === true) {
        let formatter = new Intl.NumberFormat(Constants.miscellaneous.currency.default.format, {
            style: "currency",
            currency: Constants.miscellaneous.currency.default.code,
            minimumFractionDigits: 2,
            currencyDisplay: 'narrowSymbol'
          });
          return formatter.format(data)
      }
      
      if (typeof data != null || typeof data != undefined ) {
        if (data.field.fieldType === 'currency') {
          let formatter = new Intl.NumberFormat(Constants.miscellaneous.currency.default.format, {
            style: "currency",
            currency: Constants.miscellaneous.currency.default.code,
            minimumFractionDigits: 2,
            currencyDisplay: 'narrowSymbol'
          });
          return formatter.format(data.value)
          
        }else if(data.field.fieldType === 'number'){
            if (typeof data.value == undefined || typeof data.value == null || data.value === "") {
                return 0
            }else{
                return data.value    
            }
        }else{
          return data.value
        }
      }
    },
    setIsBusyProp(tabIndex){            
        tabIndex === 0 ? this.isListingBusy = true : false
    },
    // setTabIndex(data){
    //     this.activeTab.tabIndex = data       
    // },
    calculateTotalValue(field){
       var lastVal = ''
       if(lastVal = field.fieldType === 'label' && field.lastRowlabel != false){ 
            lastVal = field.lastRowlabel
        }else{   
          lastVal = 0 // Default to 0
           this.summary.items.map(function(element) {            
                  lastVal = typeof element[field.key] != undefined && isNaN(element[field.key]) == false ?  lastVal + element[field.key] : lastVal;
            })        

            lastVal = field.fieldType == 'currency' ? this.currencyFormatterForSummary(lastVal,true) : lastVal
       }
       return lastVal

    },  
    
    roundToTwo(num) {    
        return +(Math.round(num + "e+2")  + "e-2");
    }
    
    
  },
  mounted() {
  },
  destroyed() {
  },
};
</script>
