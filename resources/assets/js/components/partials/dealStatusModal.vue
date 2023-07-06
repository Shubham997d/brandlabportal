<template>
  <div class="status-modal" v-if="dealStatusComponentLoaded == true">
    <div      
      :class="      
        deal && deal.deal && deal.deal.status
          ? GetDealStatus(parseInt(deal.deal.status)).toLowerCase() +
            ' dealsDetail-deal-status'
          : 'dealsDetail-deal-status'
      "
    >
      <div
        class="deal-status-wrap"
        v-if="deal && deal.deal && deal.deal.status"
      >
        <h4>
          {{
            deal && deal.deal && deal.deal.status
              ? GetDealStatus(parseInt(deal.deal.status))
              : ""
          }}
        </h4>
        <span>Status</span>
      </div>
      <div class="deal-status-dropdown" id="popover-deal-status">
        <font-awesome-icon
          :icon="faCaretDown"
          class="cursor-pointer text-sm"
        ></font-awesome-icon>
        <!--popover-->
        <b-popover
          custom-class="deal-status-popover"
          target="popover-deal-status"
          triggers="click"
          :show.sync="popoverStatusShow"
          placement="leftbottom"
          container="my-container"
          ref="popover"
        >
          <b-form @submit="onSubmitDealStatus">
            <b-form-group label="Deal Status" label-for="statusSelect">
              <b-form-select
                id="statusSelect"
                v-model="formDealStatus.status"
                required
              >
                <option
                  v-for="(selectOption, indexOpt) in deal &&
                  deal.deal &&
                  deal.deal.status_list
                    ? deal.deal.status_list
                    : []"
                  :key="indexOpt"
                  :value="selectOption"
                >
                  {{ indexOpt }}
                </option>
              </b-form-select>
            </b-form-group>
            <div class="popover-footer">
              <div class="transferButtons">
                <b-button
                  type="button"
                  @click="onCloseStatus"
                  class="main-button m-b-bordered"
                  >Cancel</b-button
                >
                <b-button type="submit" class="main-button m-b-success"
                  >Save</b-button
                >
              </div>
            </div>
          </b-form>
        </b-popover>
        <!--popover-->
      </div>
    </div>
    <div class="Deal-lost-modal">
      <b-modal ref="myModalRef" hide-footer class="lost-model" id="deal-lost-modal" title="Deal Lost Form">
        <form ref="form" @submit.prevent="onSubmitDealLostForm">

          <div class="form-group">
            <label for="country" class="text-sm text-gray-700"
              >{{ "Reason" | localize }}
            </label>
            <model-select
              :options="dealLostDropdown"
              v-model="formDealLost.reason"
              placeholder="Reason"
              class="form-control lost-reason-select"
              option-value="value"
              option-text="text"
            ></model-select>
            <div
              class="error"
              v-if="displayErrorMessage($v.formDealLost.reason.required)"
            >
              {{ validationErrorMessages.required }}.
            </div>
          </div>
          <div class="form-group">
            <label for="address"
              >{{ "Notes" | localize }}
            </label>
            <!-- <input
              v-model="formDealLost.notes"
              id="notes"
              ref="focusInput"
              class="form-control"
              type="text"
            /> -->
            <textarea v-model="formDealLost.notes"  id="notes" rows="4" cols="50">
            </textarea>
          </div>
          <div class="status-submit">
            <button type="submit" class="main-button">
              {{ "Submit" | localize }}
            </button>
          </div>
        </form>
      </b-modal>
    </div>
      <div class="deal-color-picker" v-click-outside="closeColorMenu">
            <div @click="toggleColorMenu()"  :style="'background-color: ' + dealColor" class="text-gray-900 font-semiboldcursor-pointer deal-status">
                  <font-awesome-icon :icon="faCaretDown" class="color-down-arrow"></font-awesome-icon>
            </div>
            <div class="absolute rounded deal-status-dropdown" v-if="colorMenuShown == true">
              <div v-for="(color,index) in onlyDealColorsCodes" :key="index" :style="'background-color: ' + color" @click="dealColourUpdate(color)" class="cursor-pointer hover:bg-white font-semibold px-4 py-2 deal-status-color">            
          </div>
        </div>
      </div>

    <b-form-group class="f-g-icon f-g-i-right" id="deal-category">          
          <multiselect 
            v-model="dealCategory"
            :options="dealCategories.original"
            label="name"
            track-by="id"
            @input="updateDealCategory()"
            :searchable='false'
          ></multiselect>
          <font-awesome-icon :icon="faCaretDown"></font-awesome-icon>
    </b-form-group>
    <div id="cs-model-container" v-if="createProjectModalOpen === true">
        <create-project-model @closeProjectModalOpen = "closeProjectModalOpen" ></create-project-model> 
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { ModelSelect } from "vue-search-select";
import { required } from "vuelidate/lib/validators";
import { faCaretDown } from "@fortawesome/free-solid-svg-icons";
import createProjectModel from './createProjectModel.vue'
export default {
  components: {
    ModelSelect,
    createProjectModel
  },
  data: () => ({
    faCaretDown,
    dealLostDropdown: Constants.dealLostDropdown.label,
    validationErrorMessages: Constants.validationErrorMessages,
    defaultDealStatus: Constants.defaultDealStatus,
    formLostButtonClicked: false,
    popoverStatusShow: false,
    dealColor: '',
    formDealLost: {
      reason: "",
      notes: "",
    },
    formDealStatus: {
      status: 1,
      deal_id: "",
    },
    availableDealColors: Constants.dealColors,
    colorMenuShown: false,
    dealsListingDefaultColor: Constants.dealslisting.filters.deals.default.dealColor,
    onlyDealColorsCodes: Constants.dealColors.colorCodes.slice(1),
    dealCategory: {},
    createProjectModalOpen: false
  }),

  validations: {
    formDealLost: {
      reason: {
        required,
      },
    },
  },

  computed: {
    ...mapState({
      currentView: (state) => state.currentView,
      deal: (state) => state.deal,
      users: (state) => state.deal.users,
      dealCategories: state => state.deal.categories,
    }),

    dealStatusComponentLoaded(){
      if(typeof this.deal.deal.status != 'undefined' && this.deal.deal.status != null ){
          this.formDealStatus.status = this.deal.deal.status;
          this.dealColor = this.deal.deal.get_stage_data[0].get_deal_organisation.deal_current_colour;
          this.dealCategory = this.deal.deal.get_stage_data[0].get_deal_category[0].category_details[0];          
          // this.dealCategory = this.deal.deal.get_stage_data[0].get_deal_organisation.deal_current_colour;
          return true;
      }else{
          return false;
      }

    },
    isDealsStatusButtonAvailable(){
      var hasPermission = false
      if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"updateDealStatus","deal-frontend-btn")){ 
            hasPermission = true
      }    
      return hasPermission
    },
     hasCreateProjectPermission(){
        var hasPermission = false
        if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,"viewProjectCreateModel","project-frontend-btn")){ 
              hasPermission = true
        }
        return hasPermission
    }    
       
  },
  created() {
  
  },

  methods: {
    ...mapActions([
      "showNotification",
      "toggleLoading",
      "getDeal",
      "getDealUsers",
    ]),
    displayErrorMessage(value) {
      if (!value && this.formLostButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    GetDealStatus(status) {
      return Object.keys(this.deal.deal.status_list).find(
        (key) => this.deal.deal.status_list[key] === status
      );
    },
    getAllDealsData() {
      this.getDeal(this.$route.params.id);
      // this.getDealUsers();
    },

    onSubmitDealLostForm(event) {
      this.formLostButtonClicked = true;
      if (this.$v.$invalid == true) {
        return false;
      } else {
        event.preventDefault();
        axios
          .post(
            "/data/deal-lost-reason/" + this.deal.deal.get_stage_data[0].id,
            {
              data: this.formDealLost,
            }
          )
          .then((response) => {
            if (response.data.status === "success") {
              axios
                .post("/data/deal/status/update", {
                  data: this.formDealStatus,
                })
                .then((response) => {
                  this.toggleLoading(false);
                  if (response.data.status === "success") {
                    this.$refs.myModalRef.hide();
                    this.formDealLost.reason = "",
                    this.formDealLost.notes = "",
                    this.getAllDealsData();
                    this.showNotification({
                      type: response.data.status,
                      message: response.data.message,
                    });
                      this.formLostButtonClicked = false;
                  }
                })
                .catch((error) => {
                  this.toggleLoading(false);
                  this.popoverStatusShow = false;
                  this.showNotification({
                    type: error.response.data.status,
                    message: error.response.data.message,
                  });
                });
            }
          })
          .catch((error) => {
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
      }
    },

    onCloseStatus() {
      this.popoverStatusShow = false;
    },
    async onSubmitDealStatus(event) {
      event.preventDefault();
      this.formDealStatus.deal_id = this.$route.params.id;
      var projectHasDeal = await this.checkIfProjectHasDeal()
      if (projectHasDeal != 'none') {
        if (this.formDealStatus.status == this.defaultDealStatus.won) {
            if(projectHasDeal == false){
                  this.popoverStatusShow = false
                  this.createProjectModalOpen = true
            }
            if(projectHasDeal == true){ // just update the deal status 
                  this.updateDealStatus(this.formDealStatus.status) 
            }
        } else if (this.formDealStatus.status == this.defaultDealStatus.lost) {
            this.popoverStatusShow = false;
            this.$refs.myModalRef.show();
        } else {
             this.updateDealStatus(this.formDealStatus.status)
        }        
      }
    },

    dealColourUpdate(inputColor){ 
      this.toggleLoading(true);

        axios
          .post("/data/deal/color/update", {
            color: inputColor,
            deal_id: this.$route.params.id
          })
          .then((response) => {
            this.toggleLoading(false);
            if (response.data.status === "success") {
                  this.closeColorMenu()
                  this.dealColor = inputColor
                  this.showNotification({
                    type: response.data.status,
                    message: response.data.message,
              });
            }
          })
          .catch((error) => {
            this.toggleLoading(false);
            this.closeColorMenu()
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
    },
    toggleColorMenu () {
      this.colorMenuShown = !this.colorMenuShown
    },
    closeColorMenu () {
      this.colorMenuShown = false
    },
    closeProjectModalOpen(val){
          this.createProjectModalOpen = val
    },
    updateDealCategory(){
       this.toggleLoading(true);
        axios
          .post("/data/deal/category/update", {
            dealCategory: this.dealCategory,
            deal_id: this.$route.params.id
          })
          .then((response) => {
            this.toggleLoading(false);
            if (response.data.status === "success") {                  
                  this.showNotification({
                    type: response.data.status,
                    message: response.data.message,
              });
            }
          })
          .catch((error) => {
            this.toggleLoading(false);
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
        
    },
    updateDealStatus(status = null){        
        axios
            .post("/data/deal/status/update", {
              data: {
                'status' : (status == null) ? this.defaultDealStatus.won : status, // as deal status is changed to won
                'deal_id' : this.$route.params.id
              },
            })
            .then((response) => {
              // console.log("response", response);
              this.toggleLoading(false);
              if (response.data.status === "success") {
                this.popoverStatusShow = false;
                this.getAllDealsData();
                this.showNotification({
                  type: response.data.status,
                  message: response.data.message,
                });
              }
            })
            .catch((error) => {
              this.toggleLoading(false);
              this.popoverStatusShow = false;
              this.showNotification({
                type: error.response.data.status,
                message: error.response.data.message,
              });
            });
      },
      async checkIfProjectHasDeal(){        
        let hasDeal  = await axios.post("/data/project/deal/exists", {
              data: {
                'deal_id' : this.$route.params.id
              },
            }).catch((error) => {
              this.showNotification({
                type: error.response.data.status,
                message: error.response.data.message,
              });
            });
            
        return (typeof hasDeal != 'undefined') ? hasDeal.data.project_deal : 'none';

      }
      
  },
};
</script>