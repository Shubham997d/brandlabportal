<template>

<div class="cs-filters">
   
    <model-select
            :options="availableUsers"
            v-model="filters.user"
            placeholder="Users"
            class="form-control"
            option-value="value"
            option-text="text"
    ></model-select>

    <date-range-picker
            ref="picker"            
            :dateFormat="dateFormat"
            :today="true"
            :opens="opens"
            :locale-data="{ firstDay: 1, cancelLabel: 'Cancel', applyLabel: 'Filter Log', format: 'dddd, mmmm dS, yyyy' }"
            :date-range="filters.dateRange"
             @update="filter($event)" 
    >        
    </date-range-picker>   


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
      
  }),
  computed: {
    ...mapState({
          filters: state => state.admin.logs.activity.filters,
          availableUsers: state => state.availableUsers
    }),    
  },  
  methods: {
    ...mapActions([
        'getActivityLogs'
    ]),    
    dateFormat (classes, date) {
      return classes
    },  
  
    filter(event = null){     
      if (event !== null) {           
        this.getActivityLogs({ dateRange :  { 'startDate': dateUtil.format(event.startDate, 'isoDate'), 'endDate': dateUtil.format(event.endDate, 'isoDate') }, users: this.filters.user, page: 1 })        
      }
    },
    
  },
  mounted () {  
     
  },
  destroyed() {
     
  },
}
</script>
