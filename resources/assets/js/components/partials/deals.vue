<template>
  <div>
  <div v-if="Object.keys(deals).length != 0">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div class="deals-wrap">
      <div class="deals-header">
        <h4>Deals</h4>
        <div class="h-center">
          <div class="select-container">
            <font-awesome-icon
              :icon="faSearch"
              class="cursor-pointer text-sm"
            ></font-awesome-icon>
            <model-list-select
              :list="allDealsDataVal"
              v-model="dealSearchModel"
              placeholder="Search Deals"
              class="form-control"
              id="dealSearchElem"
              option-value="title"
              option-text="title"
              v-if="allDealsDataVal != null"
            >
            </model-list-select>
          </div>
        </div>

        <div class="current-page-group"> 
            <font-awesome-icon
                :icon="faArrowLeft"
                class="cursor-pointer text-sm"
                :class="checkIfFirstPage"
                @click="getDealsListingData(previousPage)"
              ></font-awesome-icon>
            <h4 class="current-page"> {{dealsFilters.dealCurrentPage}} </h4>
            <font-awesome-icon
                :icon="faArrowRight"
                class="cursor-pointer text-sm"
                :class="checkIfLastPage"
                @click="getDealsListingData(nextPage)"
            ></font-awesome-icon>
        </div>

      </div>
      <div class="deals-body">
        <div class="deals-filter">
          <div class="d-f-left">
            <a
              href="javascript:void(0);"
              class="main-button"
              v-b-modal.add-deal-modal
              @click="StageSelectedStatusChange(1)"
              ><font-awesome-icon :icon="faPlus"></font-awesome-icon>Deal</a
            >
          </div>
          <div class="d-f-middle">
              <!-- <b-form-file id="deal-upload" 
               ></b-form-file> -->
            <b-button v-b-modal.upload-deal-modal class="main-button"><font-awesome-icon :icon="faUpload"></font-awesome-icon>Import Deals</b-button>
            <b-modal id="upload-deal-modal" hide-footer  size="sm" title="Import Deals" ref="upload-deals-modal">
             <b-form @submit.prevent="onUploadDealsFile">
             <b-form-group label="Import Deals:">
                <b-form-file v-model="uploadDeals" @input="uploadFile($event)" accept=".xlsx" id="file-default"></b-form-file>
                 <div
                      class="error"
                      v-if="
                        displayErrorMessageUploadDeal($v.uploadDeals.required)
                      "
                    >
                      {{ validationErrorMessages.required }}.
                    </div>
              </b-form-group>
            <div class="custom-modal-footer">
              <b-button
                id="add-deal-close"
                class="border bg-white py-2 px-3 mr-4 rounded"
                block
                @click="$bvModal.hide('upload-deal-modal')"
                >Cancel</b-button
              >
              <b-button
                type="submit"
                id="upload-deal-submit"
                class="bg-indigo-400 text-white font-medium py-2 px-3 rounded"
                variant="success"
                >Submit</b-button
              >
            </div>
          </b-form>
            </b-modal>
          
          </div>
          <div class="d-f-left" style="margin-left: 12px;">
             <b-button @click="exportDeals()" id="export-deals" class="main-button"><font-awesome-icon :icon="faFileExport"></font-awesome-icon>Export Deals</b-button>
          </div>
          <div class="d-f-right">
            <span
              >{{
                deals.all_steps_data.data[0].stage_data &&
                deals.all_steps_data.data[0].stage_data[0] &&
                deals.all_steps_data.data[0].stage_data[0]
                  .get_deal_organisation &&
                deals.all_steps_data.data[0].stage_data[0]
                  .get_deal_organisation.currency_symbol
                  ? deals.all_steps_data.data[0].stage_data[0]
                      .get_deal_organisation.currency_symbol
                  : ""
              }}{{ formatPrice(deals.sum_of_turnover) }}</span
            ><span>.</span>
            <span
              >{{ deals.total_records }}
              {{ deals.total_records > 1 ? "deals" : "deal" }}</span
            >
             <div class="deal-color-picker" v-click-outside="closeColorMenu">
                <div @click="toggleColorMenu()"  :style="'background-color: ' + dealColor" class="text-gray-900 font-semiboldcursor-pointer deal-status">
                  <font-awesome-icon :icon="faCaretDown" class="color-down-arrow"></font-awesome-icon>
                </div>
                <div class="absolute rounded deal-status-dropdown" v-if="colorMenuShown == true">
                    <div v-for="(color,index) in dealColors.colorCodes" :key="index" :style="'background-color: ' + color" @click="dealColourFilter(color)" class="cursor-pointer hover:bg-white font-semibold px-4 py-2 deal-status-color">  
                      <span class="deal-color-text" v-if="color === dealsListingDefaultColor">  All Deals </span> <!-- Add text value -->         
                    </div>
                </div>
            </div>
            <div class="filter-everyone">
              <div>
                <b-form-select
                  v-model="dealsFilters.dealCategory"
                  :custom-label="userSelectFormat"
                  :options="dealCategories.forNormalDropdown"
                  text-field="name"
                  value-field="id"
                  @change="dealCategoryFilter($event)"
                ></b-form-select>
              </div>
            </div>
          <div class="filter-everyone">
              <div>
                <b-form-select
                  v-model="dealsFilters.dealOwner"
                  :custom-label="userSelectFormat"
                  :options="everyOneFilter"
                  text-field="username"
                  value-field="id"
                  @change="filterMemberList($event)"
                ></b-form-select>
              </div>
            </div>
          </div>
        </div>
        <div
          class="
            grid grid-cols-1
            md:grid-cols-2
            lg:grid-cols-5
            gap-6
            draggable_row
          "
       v-click-outside="closeDealColorMenu" >
          <div
            v-for="(stage_name, index_no) in stagesDetail"
            :key="'step_' + index_no + 1"
          >
            <div class="list-group-container">
              <div class="list-group-heading">
                <h3>{{ stage_name }}</h3>
                <svg
                  class="arrow"
                  width="16"
                  height="55"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g fill="none" fill-rule="evenodd">
                    <path
                      class="arrow__right"
                      fill="#ebebeb"
                      d="M0 0h16v56H0z"
                    ></path>
                    <path
                      class="arrow__border"
                      fill="#dddddd"
                      d="M1 0l8 28-8 28H0V0z"
                    ></path>
                    <path
                      class="arrow__left"
                      fill="#ebebeb"
                      d="M0 1l8 27-8 27z"
                    ></path>
                  </g>
                </svg>
                <div class="l-g-amount-n-deal">
                  <p>
                    {{
                      deals.all_steps_data.data[index_no] &&
                      deals.all_steps_data.data[index_no].stage_data &&
                      deals.all_steps_data.data[index_no].stage_data[0] &&
                      deals.all_steps_data.data[index_no].stage_data[0]
                        .get_deal_organisation &&
                      deals.all_steps_data.data[index_no].stage_data[0]
                        .get_deal_organisation.currency_symbol
                        ? deals.all_steps_data.data[index_no].stage_data[0]
                            .get_deal_organisation.currency_symbol
                        : "$"
                    }}
                    <span>{{
                      deals.all_steps_data.data[index_no] &&
                      deals.all_steps_data.data[index_no].stage_data
                        ? formatPrice(
                            dealsTurnover(
                              deals.all_steps_data.data[index_no].stage_data
                            )
                          )
                        : 0
                    }}</span>
                    .
                    <span
                      >{{
                        deals.all_steps_data.data[index_no] &&
                        deals.all_steps_data.data[index_no].stage_data
                          ? deals.all_steps_data.data[index_no].stage_data
                              .length
                          : 0
                      }}
                      {{
                        deals.all_steps_data.data[index_no] &&
                        deals.all_steps_data.data[index_no].stage_data &&
                        deals.all_steps_data.data[index_no].stage_data
                          .length > 1
                          ? "deals"
                          : "deal"
                      }}</span
                    >
                  </p>
                </div>
              </div>
              <draggable
                :class="'list-group stage_' + (parseInt(index_no) + 1)"
                :list="
                  deals.all_steps_data.data[index_no] &&
                  deals.all_steps_data.data[index_no].stage_data &&
                  parseInt(
                    deals.all_steps_data.data[index_no].pipeline_stage
                  ) ==
                    index_no + 1
                    ? deals.all_steps_data.data[index_no].stage_data
                    : []
                "
                group="steps"
                @change="log(parseInt(index_no) + 1, $event)"
                v-bind="getOptions"
              >
                <div
                  class="list-group-item "
                    :style="[getDealBackgroundColor(element.get_deal_organisation.deal_id,element.get_deal_organisation.deal_current_colour)]"
                  v-for="element in deals.all_steps_data.data[index_no]
                    .stage_data"
                  :key="element.id"
                >
                <div class="list-item"> 
                  <h5>
                    <router-link
                      :to="'/deal/' + element.id"
                      class="deals-individual-detail"
                      >{{
                        element.get_deal_organisation.title
                          ? element.get_deal_organisation.title
                          : ""
                      }}</router-link
                    >
                    <span class="deal-category">{{element.get_deal_category[0].category_details[0].name}}</span>  
                  </h5>
                    <div class="deal-color-picker">
                      <div @click="toggleDealColorMenu(element.get_deal_organisation.deal_id)"  :style="[getDealBackgroundColor(element.get_deal_organisation.deal_id,element.get_deal_organisation.deal_current_colour)]" class="text-gray-900 font-semiboldcursor-pointer deal-status">
                        <font-awesome-icon :icon="faCaretDown" class="color-down-arrow"></font-awesome-icon>
                      </div>                      
                      <div class="absolute rounded deal-status-dropdown" v-if="typeof dealColorMenu[element.get_deal_organisation.deal_id] != 'undefined' && dealColorMenu[element.get_deal_organisation.deal_id] == true">
                          <div v-for="(color,index) in onlyDealColorsCodes" :key="index" :style="'background-color: ' + color" @click="dealColourUpdate(element.get_deal_organisation.deal_id,color)" class="cursor-pointer hover:bg-white font-semibold px-4 py-2 deal-status-color">  
                          </div>
                      </div>                       
                    </div>
                  </div>
                  <p>
                    <router-link
                      :to="'/deal/' + element.id"
                      class="deals-individual-detail"
                      >{{
                        element.get_deal_organisation &&
                        element.get_deal_organisation.organisation
                          ? element.get_deal_organisation.organisation
                          : ""
                      }}</router-link
                    >
                  </p>
                  <div class="list-group-anchors" v-if="users">
                    <router-link
                      :to="'/deal/' + element.id"
                      class="deals-individual-amount"
                      ><profile-card
                        :user="GetUserById(element.owner_id)"
                        :userId="element.owner_id"
                      ></profile-card>
                      <!-- {{
                        element.get_deal_organisation &&
                        element.get_deal_organisation.currency_symbol
                          ? element.get_deal_organisation.currency_symbol
                          : ""
                      }} -->
                      <span>{{
                      currencyFormatter(element.get_deal_organisation)
                      }}</span>
                      </router-link>
                    <!-- <router-link :to="'/deal/'+element.id" class="deals-individual-amount"><font-awesome-icon :icon="faUser"></font-awesome-icon> {{ element.get_deal_organisation && element.get_deal_organisation.currency_symbol ? element.get_deal_organisation.currency_symbol : '' }} <span>{{ element.get_deal_organisation && element.get_deal_organisation.turnover ? formatPrice(element.get_deal_organisation.turnover) : '' }}</span></router-link> -->

                    <a
                      href="javascript:void(0);"
                      @click="deleteDeal(element.id)"
                      class="deals-individual-remove"
                      ><font-awesome-icon
                        :icon="faTrashAlt"
                      ></font-awesome-icon>
                    </a>
                  </div>
                </div>
                <div
                  class="add_deal"
                  v-b-modal.add-deal-modal
                  @click="StageSelectedStatusChange(parseInt(index_no) + 1)"
                >
                  <font-awesome-icon
                    :icon="faPlus"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                </div>
              </draggable>
            </div>
          </div>
        </div>
      </div>
      <div class="cs-pagination"> 
      <Pagination :data="deals.pagination" :limit=2 :align="'center'" @pagination-change-page="getDealsListingData" />
      </div>
    </div>
    <!-- string value -->
    <!-- <model-select :options="options2"
      v-model="item2"
      placeholder="select item2">
      </model-select> --> 

    <div>
      <b-modal id="add-deal-modal" size="xl" title="Add deal">
        <div class="add-deal-form">
          <b-form @submit.prevent="onSubmitDeal" autocomplete="off" @reset="onResetDeal" v-if="showDeal">
            <b-row>
              <b-col sm="4" class="modal-seperator">
                <b-form-group
                  class="f-g-icon"
                  id="input-group-contact-person"
                  label="Contact person * "
                  label-for="contact-person"
                >
                  <b-form-input
                    id="contact-person"
                    v-model="formDeal.contactPerson"
                    type="text"
                    @keyup="addTitle($event)"
                  ></b-form-input>
                  <font-awesome-icon :icon="faUser"></font-awesome-icon>
                    <div class="error" v-if="displayErrorMessage($v.formDeal.contactPerson.required)">{{ validationErrorMessages.required }}.</div>
                </b-form-group>

                <b-form-group
                  class="f-g-icon"
                  id="input-group-organization"
                  label="Organization * "
                  label-for="organisation"
                >
                  <b-form-input
                    id="organisation"
                    v-model="formDeal.organisation"
                    listType
                    type="text"
                    @keyup="addTitle($event)"
                  ></b-form-input>
                  <font-awesome-icon :icon="faBuilding"></font-awesome-icon>
                    <div class="error" v-if="displayErrorMessage($v.formDeal.organisation.required)">{{ validationErrorMessages.required }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.formDeal.organisation.maxLength)"> Organisation must have at most {{$v.formDeal.organisation.$params.maxLength.max}} letters.
                  </div>

                </b-form-group>

                <b-form-group
                  id="input-group-title"
                  label="Title * "
                  label-for="title"
                >
                  <b-form-input
                    id="title"
                    v-model="formDeal.title"
                    type="text"
                  ></b-form-input>
                    <div class="error" v-if="displayErrorMessage($v.formDeal.title.required)">{{ validationErrorMessages.required }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.formDeal.title.maxLength)"> Title must have at most {{$v.formDeal.title.$params.maxLength.max}} letters.
                    </div>
                </b-form-group>

                <b-form-group
                  class="pipeline-stage-radio"
                  label="Pipeline stage"
                  v-slot="{ ariaDescribedby }"
                >
                  <b-form-radio
                    v-model="formDeal.stage"
                    :class="{
                      'stage-completed': stage <= formDeal.stage,
                    }"
                    :aria-describedby="ariaDescribedby"
                    name="pipeline-stage"
                    :checked="stage === 1"
                    :value="stage"
                    v-for="stage in stages"
                    :key="stage"
                    v-on:change="CheckStageValue(formDeal.stage)"
                  ></b-form-radio>
                </b-form-group>
                
                <b-row>
                  <b-col sm="6">
                    <b-form-group
                      id="input-group-value"
                      label="Value"
                      label-for="value"
                    >
                      <b-form-input
                        id="value"
                        v-model="formDeal.turnover"
                        type="number"
                      ></b-form-input>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formDeal.turnover.required)">{{ validationErrorMessages.required }}.</div> -->
                    <div class="error" v-if="displayErrorMessage($v.formDeal.turnover.between)"> Turnover must be between {{$v.formDeal.turnover.$params.between.min}} and {{$v.formDeal.turnover.$params.between.max}}.</div>
                      
                    </b-form-group>
                  </b-col>
                  <b-col sm="6">
                    <b-form-group class="f-g-icon f-g-i-right f-g-currency">
                      <multiselect
                        class="currency-dropdown"
                        v-model="formDeal.currency"
                        :options="currencies"
                        :custom-label="CurrencyFormat"
                        placeholder=""
                        label="name"
                        track-by="name"
                      ></multiselect>
                      <font-awesome-icon
                        :icon="faCaretDown"
                      ></font-awesome-icon>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formDeal.currency.required)">{{ validationErrorMessages.required }}.</div> -->

                    </b-form-group>
                  </b-col>
                </b-row>

                <b-form-group class="f-g-icon f-g-i-right" id="deal-category">
                  <label>Category</label>
                  <multiselect
                    v-model="formDeal.category"
                    :options="dealCategories.original"
                    placeholder="Category"                    
                    label="name"
                    track-by="id"
                  ></multiselect>
                  <font-awesome-icon :icon="faCaretDown"></font-awesome-icon>
                </b-form-group> 


                <b-form-group>
                  <label for="deal-close-date">Expected close date</label>
                  <datepicker
                    v-model="formDeal.closeDate"
                    ref="closeDate"
                    :placeholder="$options.filters.localize('Select Date')"
                    :format="customFormatter"
                    input-class="form-control"
                    lazy
                    
                    @input="updateCloseDate()"
                  ></datepicker>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formDeal.closeDate.required)">{{ validationErrorMessages.required }}.</div> -->
                
                </b-form-group>

                <b-form-group class="f-g-icon f-g-i-right" id="deal-owner" v-if="$functions.checkIfUserHasPermissionToViewButtons(currentAuthUser.availableFrontendPermissions,'addDealOnwer','deal-frontend-input')">
                  <label>Owner *</label>
                  <multiselect
                    v-model="formDeal.owner"
                    :options="ownerList"
                    :custom-label="OwnerWithFormat"
                    placeholder="Owner"
                    label="name"
                    track-by="name"
                    required
                  ></multiselect>
                  <font-awesome-icon :icon="faCaretDown"></font-awesome-icon>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formDeal.owner.required)">{{ validationErrorMessages.required }}.</div> -->
                </b-form-group>

                 

              </b-col>
              <b-col sm="3" class="modal-seperator">
                <div class="f-g-heading">
                  <span>
                    <font-awesome-icon :icon="faUser"></font-awesome-icon>
                    Person
                  </span>
                </div>

                <b-form-group
                  id="input-group-title"
                  label="Source of Lead"
                  label-for="source_of_lead"
                >
                  <b-form-input
                    id="source_of_lead"
                    v-model="formDeal.source_of_lead"
                    type="text"
                  ></b-form-input>
                </b-form-group>

                <b-form-group
                  class="f-g-add"
                  id="input-group-phone"
                  label="Phone"
                  label-for="phone"
                  v-bind:class="{
                    'option-delete': phoneIndex > 0,
                  }"
                  v-bind:key="'phone' + phoneIndex"
                  v-bind:value="phonefield.value"
                  v-for="(phonefield, phoneIndex) in formDeal.phone"
                >
                  <!-- @keyup="addPhone(phoneIndex, $event)" -->
                  <div class="exact">
                    <b-form-input
                      id="phone"
                      v-model="phonefield.value"
                      type="number"
                    ></b-form-input>

                    <a
                      href="javascript:void(0);"
                      v-if="phoneIndex > 0"
                      @click="removePhone('phone_' + phoneIndex)"
                    >
                      <font-awesome-icon :icon="faTrashAlt"></font-awesome-icon>
                    </a>
                  </div>
                  <div class="add_more_phone">
                    <a
                      href="javascript:void(0);"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="Add More"
                      class="add-phone-deal"
                      v-if="phoneIndex == formDeal.phone.length - 1"
                      @click="addPhoneClick()"
                    >
                      <font-awesome-icon :icon="faPlus"></font-awesome-icon> Add
                      More
                    </a>
                  </div>
                </b-form-group>
                <b-form-group
                  class="f-g-add"
                  id="input-group-email"
                  label="Email"
                  label-for="email"
                  v-bind:class="{
                    'option-delete': emailIndex > 0,
                  }"
                  v-bind:key="'email' + emailIndex"
                  v-bind:value="emailfield.value"
                  v-for="(emailfield, emailIndex) in formDeal.email"
                >
                  <!-- @keyup="addEmail(emailIndex, $event)" -->
                  <div class="exact">
                    <b-form-input
                      id="email"
                      v-model="emailfield.value"
                      type="text"
                      v-bind:class="
                        validateEmail(emailfield.value) ? 'error_input' : ''
                      "
                    ></b-form-input>

                    <a
                      href="javascript:void(0);"
                      v-if="emailIndex > 0"
                      @click="removeEmail('email_' + emailIndex)"
                    >
                      <font-awesome-icon :icon="faTrashAlt"></font-awesome-icon>
                    </a>
                  </div>

                  <div class="error_message">
                    <span class="error" v-if="validateEmail(emailfield.value)"
                      >Email is not valid</span
                    >
                  </div>

                  <div class="add_more_email">
                    <a
                      href="javascript:void(0);"
                      data-toggle="tooltip"
                      title="Add More"
                      class="add-email-deal"
                      v-if="emailIndex == formDeal.email.length - 1"
                      @click="addEmailClick()"
                    >
                      <font-awesome-icon :icon="faPlus"></font-awesome-icon> Add
                      More
                    </a>
                  </div>
                </b-form-group>
              </b-col>
              <b-col sm="5" class="modal-seperator">
               <div class="activity-group-div">
                 <h3>Activity</h3>
                <div class="activity-btab">
                  <b-tabs
                    pills
                    card
                    end
                    active-tab-class="activity-event-value"
                  >
                    <b-tab active>
                      <template #title>
                        <font-awesome-icon
                          :icon="faPhoneAlt"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Call"
                          :data-value="1"
                          type="text"
                          placeholder="Call"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                    <b-tab>
                      <template #title>
                        <font-awesome-icon
                          :icon="faUserFriends"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Meeting"
                          placeholder="Meeting"
                          type="text"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                    <b-tab>
                      <template #title>
                        <font-awesome-icon
                          :icon="faClock"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Task"
                          placeholder="Task"
                          type="text"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                    <b-tab>
                      <template #title>
                        <font-awesome-icon
                          :icon="faFlag"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Deadline"
                          placeholder="Deadline"
                          type="text"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                    <b-tab>
                      <template #title>
                        <font-awesome-icon
                          :icon="faPaperPlane"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Email"
                          placeholder="Email"
                          type="text"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                    <b-tab>
                      <template #title>
                        <font-awesome-icon
                          :icon="faLinkedin"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                      </template>
                      <b-card-text
                        ><b-form-input
                          v-model="formDeal.formActivity.events.Linkedin"
                          placeholder="Linkedin"
                          type="text"
                        ></b-form-input>
                      </b-card-text>
                    </b-tab>
                  </b-tabs>
                </div>
                <div class="form-activity-date-time">
                  <font-awesome-icon
                    :icon="faClock"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <div class="date-activity">
                      <datepicker
                        v-model="formDeal.formActivity.events.start_date"
                        ref="dueOnDate"
                        :placeholder="$options.filters.localize('Start Date')"
                        :disabled-dates="formActivityStart.disabledDates"
                        :format="customFormatter"
                        :highlighted="state.highlighted"
                        input-class="form-control"
                        @closed="disabledDates"
                      >
                      </datepicker>
                      <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formDeal.formActivity.events.start_date.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div>
                    </div>
                    <div class="time-activity">
                    <vue-timepicker
                      lazy
                      v-model="formDeal.formActivity.events.start_time"
                      ref="taskDuration"
                      format="HH:mm"
                      :minute-interval="15"
                      input-class="form-control"
                    >
                    </vue-timepicker>
                     <!-- <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formActivity.events.start_time.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div> -->
                    </div>  
                  </div>
                </div>
                <div class="form-activity-date-time">
                  <font-awesome-icon
                    :icon="faClock"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <div class="date-activity">
                      <datepicker
                        v-model="formDeal.formActivity.events.end_date"
                        ref="dueOnDate"
                        :disabled-dates="formActivityEnd.disabledDates"
                        :placeholder="$options.filters.localize('End Date')"
                        :highlighted="state.highlighted"
                        :format="customFormatter"
                        input-class="form-control"
                        @closed="disabledDates"
                      >
                      </datepicker>
                      <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formDeal.formActivity.events.end_date.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div>
                    </div>
                    <div class="time-activity">
                    <vue-timepicker
                      lazy
                      v-model="formDeal.formActivity.events.end_time"
                      ref="taskDuration"
                      format="HH:mm"
                      :minute-interval="15"
                      input-class="form-control"
                    >
                    </vue-timepicker>
                    <!-- <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formActivity.events.end_time.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div> -->
                    </div>  
                  </div>
                </div>

                <div class="form-acticty-select">
                  <font-awesome-icon
                    :icon="faCalendar"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <b-form-select
                      v-model="formDeal.formActivity.scheduler"
                      :options="options_scheduler"
                    ></b-form-select>
                  </div>
                </div>
                <div class="activity-textarea">
                  <font-awesome-icon
                    :icon="faClipboardList"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <b-form-textarea
                      v-model="formDeal.formActivity.notes"
                      id="textarea-rows"
                      rows="8"
                      placeholder="Notes"
                    ></b-form-textarea>
                    <div
                      class="error"
                      v-if="displayErrorMessage($v.formDeal.formActivity.notes.required)"
                    >
                      {{ validationErrorMessages.required }}.
                    </div>
                  </div>
                </div>
                <div class="form-acticty-select">
                  <font-awesome-icon
                    :icon="faUserCircle"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <b-form-select v-model="formDeal.formActivity.userId">
                      <b-form-select-option :value="null"
                        >Please select a user</b-form-select-option
                      >
                      <option
                        v-for="selectOption in usersDropDownDown"
                        :key="selectOption.id"
                        :value="selectOption.id"
                      >
                        {{ selectOption.name }}
                      </option>
                    </b-form-select>
                    <div
                      class="error"
                      v-if="
                        displayErrorMessage($v.formDeal.formActivity.userId.required)
                      "
                    >
                      {{ validationErrorMessages.required }}.
                    </div>
                  </div>
                </div>
                <div class="form-activity-input">
                  <font-awesome-icon
                    :icon="faLink"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <b-form-input
                      v-model="formDeal.formActivity.deal"
                      type="text"
                    ></b-form-input>
                  </div>
                </div>
                <div class="form-activity-checkbox">
                  <font-awesome-icon
                    :icon="faCheckCircle"
                    class="cursor-pointer text-sm"
                  ></font-awesome-icon>
                  <div class="activity-inputs">
                    <b-form-checkbox
                      id="checkbox-1"
                      v-model="formDeal.formActivity.status"
                    >
                      Mark as done
                    </b-form-checkbox>
                  </div>
                </div>
              </div>
              </b-col>
            </b-row>
            <div class="custom-modal-footer">
              <b-button
                id="add-deal-close"
                class="border bg-white py-2 px-3 mr-4 rounded"
                block
                @click="$bvModal.hide('add-deal-modal')"
                >Cancel</b-button
              >
              <b-button
                type="submit"
                id="add-deal-submit"
                class="bg-indigo-400 text-white font-medium py-2 px-3 rounded"
                variant="success"
                >Submit</b-button
              >
            </div>
          </b-form>
        </div>
      </b-modal>
    </div>
  </div>
    <div v-else class="flex flex-col items-center pt-8 report-pg-loading">
          <div class="pb-4"><p>{{'Loading Deals' | localize }}</p></div>
          <img src="/image/tasks.svg" alt="Deals" class="w-96">
    </div>  
</div>
  
</template>
<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";
import Datepicker from "vuejs-datepicker";
import rawDisplayer from "./raw-displayer.vue";
import VueTimepicker from "vue2-timepicker";
// import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import draggable from "vuedraggable";
import { ModelSelect, ModelListSelect } from "vue-search-select";
import profileCard from "./profileCard.vue";
import Multiselect from "vue-multiselect";
import LaravelVuePagination from 'laravel-vue-pagination';
import {required, requiredIf, maxLength, between, helpers} from 'vuelidate/lib/validators';
const alpha = helpers.regex('alpha', /^[a-zA-Z ]*$/);
import {
  faEye,
  faCaretDown,
  faPencilAlt,
  faUser,
  faSearch,
  faBuilding,
  faAngleRight,
  faArrowRight,
  faArrowLeft,
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
  faPhoneAlt,
  faUserFriends,
  faClock,
  faFlag,
  faPaperPlane,
  faUserCircle,
  faUtensils,
  faLink,
  faClipboardList,
  faStickyNote,
  faCalendar,
  faCalendarCheck,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCheckCircle,
  faUpload,
  faFileExport
  
} from "@fortawesome/free-solid-svg-icons";
import { faLinkedin } from "@fortawesome/free-brands-svg-icons";
export default {
  name: "two-lists",
  display: "Two Lists",
  order: 1,
  components: {
    draggable,
    rawDisplayer,
    ModelSelect,
    ModelListSelect,
    Multiselect,
    Datepicker,
    profileCard,
    VueTimepicker,
    'Pagination': LaravelVuePagination
  },
  data() {
    return {
      stages: [1, 2, 3, 4, 5], // stages
      dropdownMenuShown: false,
      currentAuthUser: authUser,
      dropdownMenuShownPipeline: false,
      createDealButtonClicked: false,
      uploadDealButtonClicked: false,
      dealSearchModel: {},
      formDeal: {
        contactPerson: "",
        organisation: "",
        title: "",
        turnover: "",
        closeDate: "",
        owner: "",
        stage: Constants.dealslisting.filters.deals.default.dealStage, // default
        category: Constants.dealslisting.filters.deals.default.dealCategoryDefaultForMultiSelect, // default
        currency: Constants.currrenciesDetail[0],
        phone: [{ value: "" }],
        email: [{ value: "" }],
        source_of_lead: "",
        formActivity: {
          userId: null,
          status: false,
          events: {
            type: "",
            value: "",
            Call: "",
            Meeting: "",
            Task: "",
            Deadline: "",
            Email: "",
            Linkedin: "",
            start_date: "",
            end_date: "",
            start_time: "",
            end_time: "",
          },
          scheduler: "free",
          deal: "",
          notes: "",
        },
      },
    formActivityEnd: {
      disabledDates: {
        to: ""
      }
    },
    formActivityStart:{
      disabledDates: {
        from: ""
      }
    },
     state:{
      highlighted: {
          dates:[
            new Date()
          ]
      }
    },
    options_scheduler: [
      // { value: null, text: 'Please select an option' },
      { value: "free", text: "Free" },
      { value: "busy", text: "Busy" },
    ],
    validationErrorMessages: Constants.validationErrorMessages,
      currency_value: Constants.currrenciesDetail[0],
      currencies: Constants.currrenciesDetail,
      ownerValue: { name: authUser.name, id: authUser.id ,role: authUser.role_id },
      admin_roleIds: Constants.permissions.salespipeline[0].changeDealStatus.roleIds,
      ownerList: [],
      selectedPipelineStage: "1",
      StageSelectedStatus: true,
      showDeal: true,
      faSearch,
      faPlus,
      faAngleRight,
      faUser,
      faCaretDown,
      faPencilAlt,
      faBuilding,
      faEye,
      faTrashAlt,
      faPhoneAlt,
      faUserFriends,
      faClock,
      faFlag,
      faPaperPlane,
      faUserCircle,
      faUtensils,
      faLink,
      faClipboardList,
      faStickyNote,
      faCalendar,
      faUpload,
      faCalendarCheck,
      faChevronCircleLeft,
      faChevronCircleRight,
      faCheckCircle,
      faLinkedin,
      faArrowRight,
      faArrowLeft,
      options: [{ text: "- Choose 1 -", value: "" }, "Red", "Green", "Blue"],
      // selectedEveryone: 0,
      everyOneFilter: [],
      stagesDetail: Constants.stagesDetail,
      uploadDeals: [],
      colorMenuShown: false,
      dealColors: Constants.dealColors,
      dealsListingDefaultColor: Constants.dealslisting.filters.deals.default.dealColor,
      faFileExport,
      dealColorMenu: [],      
      dealActiveColors: [],   
      onlyDealColorsCodes: Constants.dealColors.colorCodes.slice(1) // remove first color as it is not needed


      // allDealsDataVal: [],
    };
  },

    validations: {
    formDeal:{
     contactPerson:{
         required
     },
     organisation:{
       required,
        maxLength: maxLength(100)
     },
     turnover:{
      //  required,
        between: between(0, 10000000000)
     },
      //  closeDate:{
      //    required,
      //  },
      //  currency:{
      //    required,
      //  },
     title:{
       required,
       maxLength: maxLength(100)
     },
    //  owner:{
    //    required: requiredIf(function () {
    //         if (
    //           this.admin_roleIds.includes(this.ownerValue.role)
    //         ) {
    //           return true;
    //         }
    //       }),
    //  },
     formActivity: {
      events: {
        start_date: {
          required: requiredIf(function () {
            if(this.formDeal.formActivity.events.end_date != "") {
              return true;
            }
          }),
        },
        end_date: {
          required: requiredIf(function () {
            if(this.formDeal.formActivity.events.start_date != "") {
              return true;
            }
          }),
        },
      },
      notes: {
        required: requiredIf(function () {
            if(this.formDeal.formActivity.events.start_date != "") {
              return true;
            }
          }),
      },
      userId: {
        required: requiredIf(function () {
            if(this.formDeal.formActivity.events.start_date != "") {
              return true;
            }
          }),
      },
    },
    },
    uploadDeals:{
      required
    }
  },
  watch: {
    dealSearchModel: function (val, oldval) {    
      if (val != '' && val != null && typeof val != 'undefined' && this.$_.isEmpty(val) === false) {
          var dealTitle = (typeof val.title != 'undefined' && val.title != null ) ? val.title : val;
          this.updateDealFilters( {dealOwner: Constants.dealslisting.filters.deals.default.dealOwner, dealColor: Constants.dealslisting.filters.deals.default.dealColor, dealCategory: Constants.dealslisting.filters.deals.default.dealCategory}) // set default
          this.getDeals({ title: dealTitle, loadMore: false, status: this.getCurrentStatus(false)});

            // this.updateDealFilters( {dealOwner: this.dealsFilters.dealOwner, dealColor: this.dealColor})
            // this.getDeals({ ...this.dealsFilters, title: dealTitle, loadMore: false, status: this.getCurrentStatus(false)});                    
      }else{
          this.getDealsWithUpdatedParams();
      }
    }
   
  },
  created() {
        
  },
  computed: {
    ...mapState({
      currentView: (state) => state.currentView,
      breadcrumb: (state) => state.breadcrumb,
      currentComponent: (state) => state.dropdown.currentComponent,
      deals: (state) => state.deal.deals,
      searchDeals: (state) => state.deal.searchDeals,
      dealsFilters: (state) => state.deal.filters.deals,
      users: (state) => state.deal.users,
      dealCategories: state => state.deal.categories,
    }),
     usersDropDownDown() {
      return this.getOnlyAvailableUsers();
    },    
    getOptions () { // Disable Dragging if user does not have any permission for it
          if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,'updateDealStage','project-frontend-drag') === false){
             return {disabled : true}
          }else{
             return {disabled : false}
          } },
    allDealsDataVal () { // Get Computed Values of search deals
          this.everyOneFilter = Object.assign([], this.users); // Allow all users including deleted ones
          this.everyOneFilter.unshift({ id: 0, username: "Everyone" });
          this.ownerList = Object.assign([], this.getOnlyAvailableUsers()); // Allow only activated users
          return this.searchDeals;
      },
    dealColor() {
      return this.dealsFilters.dealColor // select deal color
    },
    checkIfLastPage(){
      return this.deals.pagination.last_page === this.dealsFilters.dealCurrentPage ?  'btn-disabled' : '';  // add disabled class depending on button
    }, 
    checkIfFirstPage(){
      return this.dealsFilters.dealCurrentPage === 1 ?  'btn-disabled' : '';
    },
    nextPage(){
      return this.dealsFilters.dealCurrentPage + 1
    },
    previousPage(){
      return this.dealsFilters.dealCurrentPage - 1
    } 
  },  

  methods: {
    ...mapActions([
      "showNotification",
      "toggleLoading",
      "getDeals",
      "getSearchDeals",
      "updateDealFilters",
      "getDealsFiltersData"      

    ]),
    selectFromParentComponent2() {
      // select option from parent component
      this.dealSearchModel = this.allDealsDataVal[0].id;
    },
    filterMemberList(event) {            
      this.getDealsWithUpdatedParams(this.dealsFilters.dealDefaultPage)     
      this.dealSearchModel = {};  // Empty Search model     
    },
    dealsTurnover(dealData) {
      let sum = dealData.reduce(
        (previousValue, currentValue) =>
          previousValue +
          (currentValue.get_deal_organisation &&
          currentValue.get_deal_organisation.turnover
            ? currentValue.get_deal_organisation.turnover
            : 0),
        0
      );
      return sum;
    },    
    getOnlyAvailableUsers(){ // get only not deleted users in dropdown for create/transfer module
       if(this.users != null) {
         var users =  this.users.map((item) => {
              return item.deleted == false ? item : null;              
         });
         return Object.fromEntries(Object.entries(users).filter(([_, v]) => v != null));
       }else {
         return []
       }
    },
    disabledDates(){
      if (this.formDeal.formActivity.events.start_date) {
        this.formActivityEnd.disabledDates.to = this.formDeal.formActivity.events.start_date;
      }
        if (this.formDeal.formActivity.events.end_date) {
        this.formActivityStart.disabledDates.from = this.formDeal.formActivity.events.end_date;
      }
    },
    formatPrice(value) {
      return parseFloat(value)
        .toFixed(2)
        .replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,");
    },
    customFormatter(date) {
      return moment(date).format(Constants.customDateFormat.format);
    },
    updateCloseDate() {},
    add: function () {
      this.list.push({ name: "New User" });
    },
    replace: function () {
      this.list = [{ name: "Replace user" }];
    },
    clone: function (el) {
      return {
        name: el.organisation + " cloned",
      };
    },
    log: function (listType, evt) {
      if (evt.added) {
        setTimeout(() => {
          this.UpdateStage(evt.added.element.id, listType);
        });
      }
    },
    startDealRecording(){          
      
    },
    deleteDeal(deal) {
      this.$confirm({
        message: `Are you sure you want to delete this deal?`,
        button: {
          no: "No",
          yes: "Yes",
        },
        /**
         * Callback Function
         * @param {Boolean} confirm
         */
        callback: (confirm) => {
          if (confirm) {
            this.toggleLoading(true);
            axios
              .delete("/data/deals/" + deal)
              .then((response) => {
                this.toggleLoading(false);
                if (response.data.status === "success") {                  
                  this.getDealsWithUpdatedParams()
                  this.getSearchDeals({status: this.getCurrentStatus(false)})
                  if (response && response.data && response.data.status) {
                    this.showNotification({
                      type: response.data.status,
                      message: response.data.message,
                    });
                  }
                }
              })
              .catch((error) => {
                this.toggleLoading(false);
                this.showNotification({
                  type: error.response.data.status,
                  message: error.response.data.message,
                });
              });
          }
        },
      });
    },
    UpdateStage(record_id, stage_id) {
      this.toggleLoading(true);
      axios
        .post("/data/deal-stage-update", {
          id: record_id,
          stage_id: stage_id,
        })
        .then((response) => {          
          this.toggleLoading(false);
          if (response.data.status === "success") {            
            this.getDealsWithUpdatedParams()
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
    reset() {
      this.item = {};
    },
    allDealsData(objArray) {
      var result = objArray.map((item) => item.get_deal_organisation);
      return result.flat(Infinity);
    },
    selectFromParentComponent1() {
      // select option from parent component
      this.item = this.all_steps[0].data.list;
    },
    toggleDropdownMenu() {
      this.dropdownMenuShown = !this.dropdownMenuShown;
    },
    closeDropdownMenu() {
      this.dropdownMenuShown = false;
    },
    toggleDropdownMenuPipeline() {
      this.dropdownMenuShownPipeline = !this.dropdownMenuShownPipeline;
    },
    closeDropdownMenuPipeline() {
      this.dropdownMenuShownPipeline = false;
    },
    displayErrorMessage(value) {
      if (!value && this.createDealButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    displayErrorMessageUploadDeal(value) {
      if (!value && this.uploadDealButtonClicked) {
        return true;
      } else {
        return false;
      }
    },   
    onSubmitDeal(event) {
    this.createDealButtonClicked = true;
      if (this.$v.formDeal.$invalid == true) {
        return false;
      } else {
  if (this.formDeal.formActivity.events.start_date != "") {
     var activityvalue = $(".activity-event-value")
          .find("input[class='form-control']")
          .val();
        var activitytype = $(".activity-event-value")
          .find("input[class='form-control']")
          .attr("placeholder");
        if (activityvalue) {
          this.formDeal.formActivity.events.value = activityvalue;
          this.formDeal.formActivity.events.type = activitytype;
        } else {
          this.formDeal.formActivity.events.value = activitytype;
          this.formDeal.formActivity.events.type = activitytype;
        }
          this.formDeal.formActivity.events.start_time = this.$functions.setTimeCorrect(this.formDeal.formActivity.events.start_time);
          this.formDeal.formActivity.events.end_time = this.$functions.setTimeCorrect(this.formDeal.formActivity.events.end_time);
         
          this.formDeal.formActivity.events.start_date = this.$functions.setDateFormat(this.formDeal.formActivity.events.start_date);
          this.formDeal.formActivity.events.end_date = this.$functions.setDateFormat(this.formDeal.formActivity.events.end_date);
          var start = this.formDeal.formActivity.events.start_date.concat("T", this.formDeal.formActivity.events.start_time);
          var end = this.formDeal.formActivity.events.end_date.concat("T", this.formDeal.formActivity.events.end_time);
          if (end >= start) {
          
          }
          else{
            this.showNotification({
                type: "error",
                message: "Deal Activity End Date/Time Should be Greater Than Start Date/Time",
              });
              return false;
        }
      }
      $('#add-deal-submit').attr('disabled',true);
      this.createDealButtonClicked = false;
      event.preventDefault();
      this.toggleLoading(true);

      var closeDate = this.formDeal.closeDate;
      var closeDateField = closeDate
        ? window.luxon.DateTime.fromISO(closeDate.toISOString()).toISODate()
        : null;

      let phoneData = this.formDeal.phone;
      phoneData = phoneData.filter((x) => x.value);
      let emailData = this.formDeal.email;
      emailData = emailData.filter((x) => x.value);
      axios
        .post("/data/add-deal", {
          data: this.formDeal,
          closeDateField: closeDateField,
          phoneData: JSON.stringify(phoneData),
          emailData: JSON.stringify(emailData),
        })
        .then((response) => {
          this.toggleLoading(false);
          if (response.data.status === "success") {
            this.getDealsWithUpdatedParams()
            this.getSearchDeals({status: this.getCurrentStatus(false)})
            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });
            this.onResetDeal();
            document.getElementById("add-deal-close").click();
           
            // $bvModal.hide('add-deal-modal');
          }
          $('#add-deal-submit').attr('disabled',false);
        })
        .catch((error) => {
          this.toggleLoading(false);
          $('#add-deal-submit').attr('disabled',false);
          this.showNotification({
            type: error.response.data.status,
            message: error.response.data.message,
          });
        });
     
      }
    },
    getCurrentStatus(forProjects = true) {
      if (forProjects === true) {
        var currentParam = this.$route.params.status;
        var status = Constants.project.slug[currentParam];
      } else {
        var currentParam = this.$route.params.status;
        var status = Constants.dealStatus[currentParam];
      }
      return status;
    },
    onResetDeal(event) {
      // Reset our form values

      this.formDeal.contactPerson = "";
      this.formDeal.organisation = "";
      this.formDeal.title = "";
      this.formDeal.turnover = "";
      this.formDeal.closeDate = "";
      this.formDeal.owner = "";
      this.formDeal.stage = 1;
      this.formDeal.currency = "";
      this.formDeal.phone = [{ value: "" }];
      this.formDeal.email = [{ value: "" }];
      this.formDeal.category = Constants.dealslisting.filters.deals.default.dealCategoryDefaultForMultiSelect;
      // Trick to reset/clear native browser form validation state
      this.showDeal = false;
      
      this.$nextTick(() => {
        this.showDeal = true;
      });
    },
    OwnerWithFormat({ name, id }) {
      return `${id == this.ownerValue.id ? name + " (You)" : name}`;
    },
    CurrencyFormat({ name }) {
      return `${name}`;
    },
    userSelectFormat({ name }) {
      return `${name.name}`;
    },
    customLabelVisibleTo({ title, desc }) {
      return `${title} – ${desc}`;
    },
    CheckStageValue(stageValue) {
      if (stageValue == this.formDeal.stage) {
        this.StageSelectedStatus = false;
      }

      if (this.StageSelectedStatus == true) {
        return "stage-completed";
      }
    },
    StageSelectedStatusChange(stageVal) {
      this.formDeal.stage = stageVal;
    },
    addPhoneClick() {
      this.formDeal.phone.push({
        value: "",
      });
    },
    addEmailClick() {
      this.formDeal.email.push({
        value: "",
      });
    },
    GetUserById(userId) {
      var recordData = this.users.find((x) => x.id === userId);
      return recordData;
    },
    addPhone(index, event) {
      // if(index + 1 == this.formDeal.phone.length){
      //   this.formDeal.phone.push({
      //     value: ''
      //   });
      // }
      if (event.target.value.length) {
        if (index + 1 == this.formDeal.phone.length) {
          this.formDeal.phone.push({
            value: "",
          });
        }
      } else {
        if (this.formDeal.phone[index + 1] !== undefined) {
          if (this.formDeal.phone[index + 1].value.length) {
            this.formDeal.phone.splice(index, 1);
          } else {
            this.formDeal.phone.splice(index + 1, 1);
          }
        }
      }
    },
    addEmail(index, event) {
      if (event.target.value.length) {
        if (index + 1 == this.formDeal.email.length) {
          this.formDeal.email.push({
            value: "",
          });
        }
      } else {
        if (this.formDeal.email[index + 1] !== undefined) {
          if (this.formDeal.email[index + 1].value.length) {
            this.formDeal.email.splice(index, 1);
          } else {
            this.formDeal.email.splice(index + 1, 1);
          }
        }
      }
    },
    removeEmail(i) {
      this.formDeal.email.splice(parseInt(i.replace("email_", "")), 1);
    },
    removePhone(i) {
      this.formDeal.phone.splice(parseInt(i.replace("phone_", "")), 1);
    },
    addTitle(event) {
      if (event.target.value.length) {
        if (event.target.id == "contact-person") {
          if (this.formDeal.organisation == "") {
            this.formDeal.title = event.target.value;
          }
        } else {
          this.formDeal.title = event.target.value;
        }
      }
    },
    validateEmail(value) {
      if (value.length) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    },
    getSearchDealsFn(dealsParam) {
      this.getSearchDeals(dealsParam);
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
    uploadFile(event){
      var fileExtension = event.name.slice(event.name.length - 5);
      if (fileExtension != '.xlsx'  ) { 
            this.uploadDeals = [];
            this.showNotification({
                type: 'error',
                message: 'Please upload xlsx file only',
              });
        }   
        if(event.size > 1000000){
            this.uploadDeals = [];
            this.showNotification({
                type: 'error',
                message: 'Please upload xlsx file less than 1MB',
              });
        }
    },
     onUploadDealsFile(event){
        this.uploadDealButtonClicked = true;
        if (this.$v.uploadDeals.$invalid == true) {
             return false;
        } else {
      $('#upload-deal-submit').attr('disabled',true);

            this.toggleLoading(true); 
            this.uploadDealButtonClicked = false;
            var formData = new FormData();
            formData.append("uploadDeals", this.uploadDeals);
            axios.post("/data/deals/upload",formData)
                .then((response) => {
                  this.toggleLoading(false);                  
                  if (response.data.status === "success") {
                    this.getDealsWithUpdatedParams(this.dealsFilters.dealDefaultPage)   
                    this.getSearchDeals({status: this.getCurrentStatus(false)})
                    $('#upload-deal-submit').attr('disabled',false);
                    this.$refs["upload-deals-modal"].hide();
                    this.showNotification({
                      type: response.data.status,
                      message: response.data.message,
                    });                    
                  }
                  
                })
                .catch((error) => {
                      $('#upload-deal-submit').attr('disabled',false);
                  this.toggleLoading(false);          
                  this.showNotification({
                    type: error.response.data.status,
                    message: error.response.data.message,
                  });
                });
        
      }
    },
    dealColourFilter(inputColor){              
        this.updateDealFilters( {dealOwner: this.dealsFilters.dealOwner, dealColor: inputColor, dealCategory: this.dealsFilters.dealCategory})
        this.getDealsWithUpdatedParams(this.dealsFilters.dealDefaultPage)
        this.closeColorMenu()
    },
    dealCategoryFilter(event){             
        this.updateDealFilters( {dealOwner: this.dealsFilters.dealOwner, dealColor: this.dealsFilters.dealColor, dealCategory: this.dealsFilters.dealCategory})
        this.getDealsWithUpdatedParams(this.dealsFilters.dealDefaultPage)
    },
    toggleColorMenu () {
      this.colorMenuShown = !this.colorMenuShown
    },
    closeColorMenu () {
      this.colorMenuShown = false
    },
    getDealsWithUpdatedParams(defaultPage = null){
        this.updateDealFilters( {dealOwner: this.dealsFilters.dealOwner, dealColor: this.dealsFilters.dealColor, dealCategory: this.dealsFilters.dealCategory})
        this.getDeals({...this.dealsFilters, goToPage: defaultPage, loadMore:false , status: this.getCurrentStatus(false) })
    },
    exportDeals(){        
        this.toggleLoading(true)           
        $('#export-deals').attr('disabled',true);
        var url = window.location.origin+'/data/export/deals'
        var currentParams = {...this.dealsFilters, status: this.getCurrentStatus(false)}
        axios.get(url,{ params: currentParams, responseType: 'blob'}).then((response) => { 
              if (response.data.status == 'error') {
                    $('#export-deals').attr('disabled',false);
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
              this.toggleLoading(false)
              $('#export-deals').attr('disabled',false);
            })
          .catch((error) => {
                $('#export-deals').attr('disabled',false);                     
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
    closeDealColorMenu(event){
      if (event.target.className != 'text-gray-900 font-semiboldcursor-pointer deal-status') {
          this.dealColorMenu = [] // empty array
      }
    },
    toggleDealColorMenu(dealId){                
        if(this.dealColorMenu[[dealId]] == true){
            this.$set(this.dealColorMenu, dealId, false)
        }else if(this.dealColorMenu[[dealId]] == false){
            this.$set(this.dealColorMenu, dealId, true)
        }else{
            this.dealColorMenu = []
            this.$set(this.dealColorMenu, dealId, true) // for not set and undefined cases
        }
    },
    dealColorMenuShown(dealId){
          
    },
    dealColourUpdate(dealId,inputColor){ 
      this.toggleLoading(true);
        axios
          .post("/data/deal/color/update", {
            color: inputColor,
            deal_id: dealId
          })
          .then((response) => {
            this.toggleLoading(false);
            if (response.data.status === "success") {
                if(this.dealsFilters.dealColor === this.dealsListingDefaultColor){
                    this.dealColorMenu = []
                    this.$set(this.dealActiveColors, dealId, inputColor)                
                  }else{
                      this.dealColorMenu = [] // reset these
                      this.dealActiveColors = []                      
                      this.getDealsWithUpdatedParams()
                  }                                    
                  this.showNotification({
                    type: response.data.status,
                    message: response.data.message,
              });
            }
          })
          .catch((error) => {
            this.toggleLoading(false);
            this.dealColorMenu = []
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
    },
    getDealBackgroundColor(dealId,dealColor){
        let styleRadarContainer = {
           'background-color' : this.dealActiveColors[dealId]  ? this.dealActiveColors[dealId] : dealColor
        }
        return styleRadarContainer;
    },
    getDealsListingData(page = 1){                  
       this.getDeals({...this.dealsFilters, goToPage: page, loadMore:false , status: this.getCurrentStatus(false) })          
       this.getSearchDeals({status: Constants.dealStatus[this.$route.params.status]})
       this.getDealsFiltersData()
       this.updateDealFilters( {dealOwner: this.dealsFilters.dealOwner, dealColor: this.dealsFilters.dealColor, dealCategory: this.dealsFilters.dealCategory})
       window.scrollTo({ top: 0, behavior: 'smooth' }); // scroll to top when any change
    },
    
  },
  mounted: function mounted() {
    // var app = this;
    // window.setTimeout(function () {
    //   $("body")
    //     .find("#dealSearchElem")
    //     .keyup(function (e) {
    //       var dealsParam = { title: this.value };
    //       app.getSearchDealsFn(dealsParam);
    //     });
     
    // }, 1000);
  },
  deactivated(){
        
  }
};
</script>