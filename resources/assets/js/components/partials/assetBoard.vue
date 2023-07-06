<template>      
    
<div id="task-container" class="w-full asset-container">


  <!-- Asset List -->

  <div v-if="assets != undefined">
   <div class="task-list">
      <div class="task-list-c">
        <div class="toggle-task-list">
          <!-- <span class="toggle-task" :style="'background-color: ' ">
          <font-awesome-icon :icon="faChevronUp" class="user-task-dropdown user-assigned-icon">
          </font-awesome-icon>
          </span> -->
          <!-- <h6 class="user-assigned-name">{{ name }}</h6> -->
        </div>
      <div class="user-task-tb asset-list">

      <table>
        <thead>
          <tr >
          <th class="task-th-1 test">Asset Type</th>
          <th class="task-th-2">Asset Cost</th>
          <th class="task-th-3">
            <div class="total-hours-table asset-create-btn">
              <span class="add-task" @click="createAsset()" :style="'background-color: #aad2ff;'">
              <font-awesome-icon :icon="faPlus" class="add-task-btn" ></font-awesome-icon>
              </span>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(asset) in groupedAssets" :key="asset.id" :style="'border-left: 3px solid #aad2ff;'">
          <td class="task-name-td"><input v-model="asset.asset_type" type="text" lazy @change="updateAsset(asset)" placeholder="Add Asset Type" ></td>
          <td class="task-name-td"><input v-model="asset.asset_cost" type="number" lazy @change="updateAsset(asset)" placeholder="Add Asset Cost" ></td>  
          <td>                 
            <button @click="deleteAsset(asset)" class="w-8 h-8 bg-red-200 text-red-700 rounded-full flex justify-center items-center" title="delete">
                <font-awesome-icon :icon="faTrashAlt" class="cursor-pointer text-sm"></font-awesome-icon>
            </button>
          </td>
       </tr>
      </tbody>
    </table>

    
  </div>
    </div>
  </div>
  </div>

  <div v-if="assets == undefined" class="flex flex-col items-center pt-8">
    <div class="pb-4">{{'Assets are being loaded' | localize }}</div>
    <img src="/image/tasks.svg" alt="task list" class="w-96">
  </div>
  
  <!-- Task List -->
  

</div>

</template>

<script>
import Datepicker from 'vuejs-datepicker'
import VueTimepicker from 'vue2-timepicker'
import accessDenied from './accessDenied.vue'
import moment from 'moment'
import { mapState, mapActions } from 'vuex'
import {
  faQuestionCircle,
  faChevronDown,
  faSpinner,
  faFilter,
  faAngleDoubleRight,
  faThLarge,
  faThList,
  faChevronCircleDown,
  faTrashAlt,
  faPlus,
  faChevronUp,  
} from '@fortawesome/free-solid-svg-icons'

export default {
  name: 'project.assets',
  components: {Datepicker, VueTimepicker},

  data: () => ({
    assets: [],
    faQuestionCircle,
    faChevronDown,
    faSpinner,
    faFilter,
    faAngleDoubleRight,
    faThLarge,
    faThList,
    faTrashAlt,
    faPlus,
    faChevronUp,
    faChevronCircleDown,
    statusMenuShown: false,
  }), 

  async created () {      
    
    this.setParamsForProject()
    this.assets = await this.getAllAssets(true)    

  },

  watch: { },

  computed: {  

    ...mapState({
      members: state => state.members,
    }),

    groupedAssets: function () {
      return this.assets
    }
    
  },

  methods: {
    ...mapActions([
      'showNotification',
      'toggleLoading',
      'getProject',
      'getErrorResponseAccordingToError'
    ]),
   
    async getAllAssets (update = false) {      
      try {
        this.toggleLoading(true)
        if (this.assets.length < 1 || update) {
          
          let { data } = await axios({
            url: '/data/project/assets',
            params: {
              project_id: this.project_id,
            }})
          this.assets = data.assets
          this.toggleLoading(false)
          return data.assets
        }
        return []
      } catch (error) {
        this.toggleLoading(false)
        this.getErrorResponseAccordingToError(error); // this funtion redirects if user does not have permission for resource
        this.showNotification({type: error.response.data.status, message: error.response.data.message})
      }
    },
   
    deleteAsset (asset,assigned_to) {      
        Swal.fire({
            title: Constants.swalPopup.title,
            text: Constants.swalPopup.text,
            icon: Constants.swalPopup.icon,
            showCancelButton: Constants.swalPopup.showCancelButton,
            confirmButtonColor: Constants.swalPopup.confirmButtonColor,
            cancelButtonColor: Constants.swalPopup.cancelButtonColor,
            confirmButtonText: Constants.swalPopup.confirmButtonText
          }).then((result) => {
            if (result.isConfirmed) {
                  axios.delete('/data/project/assets', {
                      params: {
                        'project_id': this.project_id,
                        'id': asset.id,
                      }
                    })
                    .then((response) => {
                      this.getAllAssets(true).then((data) => {
                            this.showNotification({type: response.data.status, message: response.data.message})
                      })
                    })
                    .catch((error) => {
                      this.showNotification({type: error.response.data.status, message: error.response.data.message})
                })
            }
        })
      
    },        
    updateAsset (asset) {
        this.toggleLoading(true)
        axios.put('/data/project/assets', {
          'project_id': this.project_id,
          'asset': asset
        })
          .then((response) => {
            this.toggleLoading(false)
            if (response.data.status === 'success') {              
              this.showNotification({type: response.data.status, message: response.data.message})            
            }
            if (response.data.status === 'error') {              
              this.showNotification({type: response.data.status, message: response.data.message})            
            }
          })
          .catch((error) => {
            this.toggleLoading(false)
            this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
    },  
       
    
    createAsset () {           
        axios.post('/data/project/assets', {
          'project_id': this.project_id,
        })
         .then((response) => {
              this.getAllAssets(true).then((data) => {
                   this.showNotification({type: response.data.status, message: response.data.message})
              })
            })
            .catch((error) => {
              this.showNotification({type: error.response.data.status, message: error.response.data.message})
        })
     
    },             

    setParamsForProject(){
          this.project_id = this.$route.params.id
    },

  },
  mounted () {
   
  }
}


</script>

