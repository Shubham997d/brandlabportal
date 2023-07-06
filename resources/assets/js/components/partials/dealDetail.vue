<template>
  <div class="deal-detail-page">
    <div v-if="deal.deal != null && isDealDataLoaded === true">
    <vue-confirm-dialog></vue-confirm-dialog>
    <div
      class="
        dealsDetail
        sm:container sm:mx-auto
        w-full
        sm:max-w-xl
        md:max-w-3xl
        lg:max-w-5xl
        xl:max-w-6xl
      "
    >
      <div class="container">
        <div class="dealsDetail-header">
          <!-- <div class="dealsDetail-heading">
            <h2>
              {{
                deal &&
                deal.deal &&
                deal.deal.get_stage_data[0] &&
                deal.deal.get_stage_data[0].get_deal_organisation.organization
                  ? deal.deal.get_stage_data[0].get_deal_organisation
                      .organization
                  : ""
              }}
            </h2>
          </div> --> 
           <!-- include all left aligned dropdowns  -->
          <deal-status-modal></deal-status-modal> 

          

          <div class="dealsDetail-header-select">
            <div class="icon-user">
              <!-- <font-awesome-icon
                :icon="faUser"
                class="cursor-pointer text-sm"
              ></font-awesome-icon> -->
              <profile-card
                :user="GetUserById(deal.deal.owner_id)"
                :userId="deal.deal.owner_id"
                v-if="deal && deal.deal && deal.deal.owner_id"
              ></profile-card>
            </div>
            <div class="owner-wrap">
              <h4>
                {{
                  deal && deal.deal && deal.deal.owner_id
                    ? GetOwnerName(parseInt(deal.deal.owner_id))
                    : ""
                }}
              </h4>
              <span>Owner</span>
            </div>
            <div class="owner-dropdown" id="popover-owner">
              <font-awesome-icon
                :icon="faCaretDown"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <!--popover-->
              <b-popover
                custom-class="deal-detail-popover"
                target="popover-owner"
                triggers="click"
                :show.sync="popoverOwnerShow"
                placement="leftbottom"
                container="my-container"
                ref="popover"
              >
                <b-form @submit="onSubmitOwnerTransfer">
                  <b-form-group
                    label="Transfer ownership"
                    label-for="ownerSelect"
                  >
                    <b-form-select
                      v-if="users && users.length > 0"
                      id="ownerSelect"
                      :options="getOnlyAvailableUsers()"
                      v-model="formTransfer.owner"
                      text-field="username"
                      value-field="id"
                    ></b-form-select>
                  </b-form-group>
                  <div class="popover-footer">
                    <div class="transferButtons">
                      <b-button
                        type="button"
                        @click="onCloseOwner"
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
          <div class="pipelineSummary">
            <div class="pipelineStages open">
              <ul>
                <li
                  v-for="(stage_name, index_no) in stagesDetail"
                  :key="'step_' + index_no + 1"
                  :id="'step_' + parseInt(index_no + 1)"
                  :class="[
                    deal &&
                    deal.deal &&
                    deal.deal.pipeline_stage >= parseInt(index_no + 1)
                      ? 'active'
                      : '',
                  ]"
                >
                  <div class="stageContent">
                    <span class="stagename">{{
                      deal &&
                      deal.deal &&
                      deal.deal.get_stage_data[0].get_stage_detail[index_no] &&
                      deal.deal.get_stage_data[0].get_stage_detail[index_no]
                        .start_datetime
                        ? GetStageDateDiif(
                            deal.deal.get_stage_data[0].get_stage_detail[
                              index_no
                            ].start_datetime
                          )
                        : pipelineStageConstants.PipelineMsg3
                    }}</span>
                    <span class="stageArrow"></span>
                  </div>

                  <b-tooltip
                    :target="'step_' + parseInt(index_no + 1)"
                    triggers="hover"
                  >
                    <b>{{ stage_name }}</b>  <br>
                  {{
                      deal &&
                      deal.deal &&
                      deal.deal.get_stage_data[0].get_stage_detail[index_no] &&
                      deal.deal.get_stage_data[0].get_stage_detail[index_no]
                        .start_datetime
                        ? pipelineStageConstants.PipelineMsg2+GetStageDateDiif(
                            deal.deal.get_stage_data[0].get_stage_detail[
                              index_no
                            ].start_datetime
                          )
                        : pipelineStageConstants.PipelineMsg1
                    }}
                  </b-tooltip>
                </li>
              </ul>
            </div>

            <div class="additionalInfo">
              <div
                class="pipelineInfo"
                v-for="(stage_name, index_no) in stagesDetail"
                :key="'stepinfo_' + index_no + 1"
              >
                {{ stage_name }}
              </div>
            </div>
          </div>
        </div>
       

        <div class="deal-tab">
          <b-tabs content-class="mt-3" fill>
            <b-tab active>
              <template #title>
                <font-awesome-icon
                  :icon="faStickyNote"
                  class="cursor-pointer text-sm"
                ></font-awesome-icon>
                Notes
              </template>
              <div class="container collpse deal-notes-details">
                <div>
                  <!-- Using modifiers -->
                  <b-button
                    v-b-toggle.collapse-add-note
                    class="m-1 main-button"
                    value="add"
                    @click="collapseDealNotes($event)"
                  >
                    <font-awesome-icon
                      :icon="faPlus"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                    Note
                  </b-button>
                  <!-- @click="collapseDealNotes($event)" -->
                  <!-- Using value -->
                  <b-button
                    v-b-toggle="'collapse-view-notes'"
                    class="m-1 main-button"
                    value="view"
                    @click="collapseDealNotes($event)"
                    ><font-awesome-icon
                      :icon="faEye"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                    View Notes</b-button
                  >

                  <!-- Element to collapse -->
                  <div id="collapse-add-note" v-if="add_note == true">
                    <div>
                      <b-form @submit.prevent="onSubmitDealNote">
                        <b-row>
                          <b-col sm="8">
                            <b-form-group
                              id="input-deal-note-value"
                              label="Title * "
                              label-for="deal-note-title"
                            >
                              <b-form-input
                                id="deal-note-title"
                                v-model="formDealNote.title"
                                placeholder="Enter title"
                                
                              ></b-form-input>
                    <div class="error" v-if="displayErrorMessageNotes($v.formDealNote.title.required)">{{ validationErrorMessages.required }}.</div>

                            </b-form-group>

                            <b-form-group
                              id="input-deal-note-description"
                              label="Description * "
                              label-for="value"
                            >
                              <b-form-textarea
                                id="input-deal-note-description"
                                v-model="formDealNote.description"
                                placeholder="Enter description..."
                                rows="3"
                                max-rows="6"
                              ></b-form-textarea>
                    <div class="error" v-if="displayErrorMessageNotes($v.formDealNote.description.required)">{{ validationErrorMessages.required }}.</div>

                            </b-form-group>
                          </b-col>
                        </b-row>
                        <div class="deal-note-submit">
                          <b-button type="submit" class="main-button"
                            >Save</b-button
                          >
                        </div>
                      </b-form>
                    </div>
                  </div>
                  <!-- collapse end -->

                  <div id="collapse-update-note" v-if="update_note == true">
                    <div>
                      <b-form @submit.prevent="onUpdateDealNote">
                        <b-row>
                          <b-col sm="8">
                            <!-- <b-form-input id="note_id" v-model="updateDealNote.note_id" type="hidden"></b-form-input> -->
                            <b-form-group
                              id="input-deal-note-value"
                              label="Title * "
                              label-for="deal-note-title"
                            >
                              <b-form-input
                                id="deal-note-title"
                                v-model="updateDealNote.title"
                                placeholder="Enter title"
                             
                              ></b-form-input>
                    <div class="error" v-if="displayErrorMessageNotesUpdate($v.updateDealNote.title.required)">{{ validationErrorMessages.required }}.</div>

                            </b-form-group>

                            <b-form-group
                              id="input-deal-note-description"
                              label="Description * "
                              label-for="value"
                            >
                              <b-form-textarea
                                id="input-deal-note-description"
                                v-model="updateDealNote.description"
                                placeholder="Enter description..."
                                rows="3"
                                max-rows="6"
                                
                              ></b-form-textarea>
                    <div class="error" v-if="displayErrorMessageNotesUpdate($v.updateDealNote.description.required)">{{ validationErrorMessages.required }}.</div>

                            </b-form-group>
                          </b-col>
                        </b-row>
                        <div class="deal-note-submit">
                          <b-button type="submit" class="main-button"
                            >Save</b-button
                          >
                        </div>
                      </b-form>
                    </div>
                  </div>

                  <div
                    id="collapse-view-notes"
                    v-if="
                      view_note == true &&
                      deal &&
                      deal.deal &&
                      deal.deal.get_deal_notes
                    "
                  >
                    <div>
                      <!-- Main table element -->
                      <h3>Notes Detail</h3>
                      <b-table
                        class="striped dealnotes"
                        :items="
                          this.deal.deal.get_deal_notes
                            ? this.deal.deal.get_deal_notes
                            : []
                        "
                        :fields="fields"
                        show-empty
                        small
                      >
                        <template #cell(actions)="row">
                          <b-button
                            size="sm"
                            @click="deleteNote(row.item.id)"
                            class="mr-1 main-button"
                          >
                            <font-awesome-icon
                              :icon="faTrashAlt"
                            ></font-awesome-icon>
                            Delete
                          </b-button>

                          <b-button
                            size="sm"
                            @click="updateNote(row.item.id)"
                            class="mr-1 main-button"
                          >
                            <font-awesome-icon
                              :icon="faEdit"
                            ></font-awesome-icon>
                            Update
                          </b-button>
                        </template>
                      </b-table>
                    </div>
                  </div>
                </div>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <font-awesome-icon
                  :icon="faCalendar"
                  class="cursor-pointer text-sm"
                ></font-awesome-icon>
                Activity
              </template>
              <deal-activity @refreshActvity="refreshActvity"></deal-activity>
            </b-tab>
            <b-tab v-if="isTabHidden === false">
              <template #title>
                <font-awesome-icon
                  :icon="(r = faReceipt)"
                  class="cursor-pointer text-sm"
                ></font-awesome-icon>
                Invoice
              </template>
              <deal-invoice @refreshActvity="refreshActvity"></deal-invoice>
            </b-tab>
          </b-tabs>
        </div>
        <!-- deal notes start -->
         <div class="dealsDetail-body">
          <b-row>
            <b-col sm="6">
              <div class="dealsDetail-container">
                <h3>Organization</h3>
                <!-- <h3>Organization<font-awesome-icon
                      :icon="faPencilAlt"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon></h3> -->
                <div class="dealsDetail-c-heading">
                  <div class="dealsDetail-icon">
                    <font-awesome-icon
                      :icon="faBuilding"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                  </div>
                  <h4>
                    {{
                      deal && deal.deal && deal.deal.get_stage_data[0]
                        ? deal.deal.get_stage_data[0].get_deal_organisation
                            .organisation
                        : ""
                    }}
                  </h4>
                </div>
                <b-form @submit.prevent="onSubmitDealOrganisation">
                  <div class="organisation-group-div">
                    <h3>Organisation Details</h3>

                    <b-form-group
                      label="Organisation * "
                      label-for="Organisation"
                    >
                      <b-form-input
                        id="Organisation"
                        v-model="formOrganization.Organisation"
                        type="text"
                      ></b-form-input>
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.Organisation.required)">{{ validationErrorMessages.required }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.Organisation.maxLength)"> Organisation must have at most {{$v.formOrganization.Organisation.$params.maxLength.max}} letters.</div>

                    </b-form-group>

                    <b-form-group
                      label="Title * "
                      label-for="organisationTitle"
                    >
                      <b-form-input
                        id="organisationTitle"
                        v-model="formOrganization.Title"
                        type="text"
                      ></b-form-input>
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.Title.required)">{{ validationErrorMessages.required }}.</div>
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.Title.maxLength)"> Title must have at most {{$v.formOrganization.Title.$params.maxLength.max}} letters.</div>

                    </b-form-group>

                    <b-form-group
                      label="Address"
                      label-for="organizationAddress"
                    >
                      <b-form-input
                        id="organizationAddress"
                        v-model="formOrganization.Address"
                        type="text"
                      ></b-form-input>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.Address.required)">{{ validationErrorMessages.required }}.</div> -->

                    </b-form-group>

                    <b-form-group
                      id="input-group-value"
                      label="Value"
                      label-for="Value"
                    >
                      <b-form-input
                        id="value"
                        v-model="formOrganization.turnover"
                        type="number"
                      ></b-form-input>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.turnover.required)">{{ validationErrorMessages.required }}.</div> -->
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.turnover.between)"> Turnover must be between {{$v.formOrganization.turnover.$params.between.min}} and {{$v.formOrganization.turnover.$params.between.max}}.</div>

                    </b-form-group>

                    <b-form-group
                      label="Currency"
                      label-for="organizationCurrrency"
                    >
                      <multiselect
                        id="organizationCurrrency"
                        class="currency-dropdown1"
                        v-model="formOrganization.currency"
                        :options="currencies"
                        :custom-label="CurrencyFormat"
                        placeholder=""
                        label="name"
                        value="symbol"
                        track-by="name"
                      ></multiselect>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.currency.required)">{{ validationErrorMessages.required }}.</div> -->

                    </b-form-group>

                    <b-form-group class="deal-detail-clode-date">
                      <label for="deal-close-date">Expected close date</label>
                      <datepicker
                        v-model="formOrganization.closeDate"
                        ref="closeDate"
                        :placeholder="$options.filters.localize('Select Date')"
                        :format="customFormatter"
                        input-class="form-control"
                        lazy
                      ></datepicker>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.closeDate.required)">{{ validationErrorMessages.required }}.</div> -->

                    </b-form-group>

                    <b-form-group
                      label="Phone Number"
                      label-for="organizationPhone"
                    >
                      <b-form-input
                        id="organizationPhone"
                        v-model="formOrganization.Phone"
                        type="number"
                      ></b-form-input>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.Phone.required)">{{ validationErrorMessages.required }}.</div> -->
                    <div class="error" v-if="displayErrorMessage($v.formOrganization.Phone.maxLength)"> Phone Number must have at most {{$v.formOrganization.Phone.$params.maxLength.max}} letters.</div>

                    </b-form-group>

                    <b-form-group
                      label="Website"
                      label-for="organizationWebsite"
                    >
                      <b-form-input
                        id="organizationWebsite"
                        v-model="formOrganization.Website"
                        type="url"
                      ></b-form-input>
                    <!-- <div class="error" v-if="displayErrorMessage($v.formOrganization.Website.required)">{{ validationErrorMessages.required }}.</div> -->

                    </b-form-group>
                  </div>

                  <div class="deal-detail-submit">
                    <b-button type="submit" class="main-button">Save</b-button>
                  </div>
                </b-form>
              </div>
            </b-col>
            <b-col sm="6">
              <div class="dealsDetail-container">
                <h3>Person</h3>
                <!-- <h3>Person <font-awesome-icon
                      :icon="faPencilAlt"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon></h3> -->
                <div class="dealsDetail-c-heading">
                  <div class="dealsDetail-icon">
                    <font-awesome-icon
                      :icon="faUser"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                  </div>
                  <h4>
                    {{
                      deal &&
                      deal.deal &&
                      deal.deal.get_stage_data[0].get_deal_person &&
                      deal.deal.get_stage_data[0].get_deal_person.first_name
                        ? deal.deal.get_stage_data[0].get_deal_person.first_name
                        : ""
                    }}
                    {{
                      deal &&
                      deal.deal &&
                      deal.deal.get_stage_data[0].get_deal_person &&
                      deal.deal.get_stage_data[0].get_deal_person.last_name
                        ? " " +
                          deal.deal.get_stage_data[0].get_deal_person.last_name
                        : ""
                    }}
                  </h4>
                </div>
                <b-form
                  id="dealPersonForm"
                  @submit.prevent="onSubmitDealPerson"
                  enctype="multipart/form-data"
                >
                  <div class="person-group-div">
                    <b-form-group
                      label="First Name *"
                      label-for="personFirstName"
                    >
                      <b-form-input
                        id="personFirstName"
                        v-model="formPerson.personFirstName"
                        type="text"
                        
                      ></b-form-input>
                    <div class="error" v-if="displayErrorMessagePerson($v.formPerson.personFirstName.required)">{{ validationErrorMessages.required }}.</div>

                    </b-form-group>
                    <b-form-group label="Last Name" label-for="personLastName">
                      <b-form-input
                        id="personLastName"
                        v-model="formPerson.personLastName"
                        type="text"
                      ></b-form-input>
                    </b-form-group>
                    <b-form-group
                      label="Source of Lead"
                      label-for="source_of_lead"
                    >
                      <b-form-input
                        id="source_of_lead"
                        v-model="formPerson.source_of_lead"
                        type="text"
                      ></b-form-input>
                    </b-form-group>
                  </div>


                   <div class="phone-group-div">
                    <h3>Extra Person Details</h3>
                    <b-form-group
                      v-for="(personIndexData, personNameIndex) in formPerson.personName"
                      class="phone_group"
                      v-bind:key="'personName' + personNameIndex"
                      v-bind:label="'Name ' + (personNameIndex + 1)"
                      label-for="personName"
                    >
                      <b-form-input
                        v-if="personIndexData.value != null"
                        id="personName"
                        v-model="personIndexData.value"
                        type="text"
                      ></b-form-input>
                      <a
                        href="javascript:void(0);"
                        v-show="formPerson.personName.length"
                        @click="removePersonName(personNameIndex)"
                        class="phone-individual-remove"
                        ><font-awesome-icon
                          :icon="faTrashAlt"
                        ></font-awesome-icon>
                      </a>
                    </b-form-group>

                    <a
                      href="javascript:void(0);"
                      class="phone-individual-add main-button"
                      @click="addPersonPersonName()"
                    >
                      + Add extra name
                    </a>
                  </div>

                  <div class="phone-group-div">
                    <h3>Contact Details</h3>
                    <b-form-group
                      v-for="(phoneData, phoneIndex) in formPerson.personPhone"
                      class="phone_group"
                      v-bind:key="'personPhone' + phoneIndex"
                      v-bind:label="'Phone ' + (phoneIndex + 1)"
                      label-for="personPhone"
                    >
                      <b-form-input
                        id="personPhone"
                        v-model="phoneData.value"
                        type="text"
                      ></b-form-input>
                      <a
                        href="javascript:void(0);"
                        v-show="formPerson.personPhone.length > 1"
                        @click="removePhone(phoneIndex)"
                        class="phone-individual-remove"
                        ><font-awesome-icon
                          :icon="faTrashAlt"
                        ></font-awesome-icon>
                      </a>
                    </b-form-group>

                    <a
                      href="javascript:void(0);"
                      class="phone-individual-add main-button"
                      @click="addPersonPhone()"
                    >
                      + Add phone
                    </a>
                  </div>
                  <div class="email-group-div">
                    <h3>Email Details</h3>
                    <b-form-group
                      v-for="(emailData, emailIndex) in formPerson.personEmail"
                      class="email_group"
                      v-bind:key="'personEmail' + emailIndex"
                      v-bind:label="'Email ' + (emailIndex + 1)"
                      label-for="personEmail"
                    >
                      <div class="email_div_content">
                        <b-form-input
                          id="personEmail"
                          v-model="emailData.value"
                          type="email"
                        ></b-form-input>

                        <a
                          href="javascript:void(0);"
                          v-show="formPerson.personEmail.length > 1"
                          @click="removeEmail(emailIndex)"
                          class="email-individual-remove"
                          ><font-awesome-icon
                            :icon="faTrashAlt"
                          ></font-awesome-icon>
                        </a>
                      </div>

                      <!-- <span class="error" v-if="validateEmail(emailData.value)"
                        >Email is not valid</span
                      > -->
                    </b-form-group>

                    <a
                      href="javascript:void(0);"
                      class="email-individual-add main-button"
                      @click="addPersonEmail()"
                    >
                      + Add email
                    </a>
                  </div>
                  <div class="deal-detail-submit">
                    <b-button type="submit" class="main-button">Save</b-button>
                  </div>
                </b-form>
                <div class="file-group-div">
                  <h3>File Details</h3>
                  <VueFileAgent
                    ref="vueFileAgent"
                    :theme="'grid'"
                    :multiple="true"
                    :deletable="true"
                    :linkable="linkable"
                    :meta="true"
                    :maxFiles="700"
                    :thumbnailSize="120"
                    @select="filesSelected($event)"
                    @beforedelete="onBeforeDelete($event)"
                    @delete="fileDeleted($event)"
                    v-model="fileRecords"
                  ></VueFileAgent>
                  <button
                    class="btn main-button btn-secondary mt-3"
                    @click="uploadFiles()"
                  >
                    Upload files
                  </button>
                </div>
              </div>
            </b-col>
          </b-row>
        </div>
      </div>
    </div>
    </div>
    <div v-else class="flex flex-col items-center pt-8 report-pg-loading">
        <div class="pb-4"><p>{{'Loading Deal' | localize }}</p></div>
        <img src="/image/tasks.svg" alt="Report" class="w-96">
    </div>    
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import Datepicker from "vuejs-datepicker";
import VueTimepicker from "vue2-timepicker";
import moment from "moment";
import profileCard from "./profileCard.vue";
import VueFileAgent from "vue-file-agent";
import dealActivity from "./dealActivity.vue";
import dealInvoice from "./dealInvoice.vue";
import dealStatusModal from "./dealStatusModal.vue";
import {required, maxLength, between} from 'vuelidate/lib/validators';
Vue.use(VueFileAgent);

import {
  faCaretDown,
  faUser,
  faBuilding,
  faPencilAlt,
  faTrashAlt,
  faEdit,
  faEye,
  faPlus,
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
  faReceipt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "deal-details",
  components: {
    Datepicker,
    profileCard,
    dealActivity,
    VueTimepicker,
    dealInvoice,
    dealStatusModal
  },
  data() {
    return {
      files: null,
      add_note: false,
      view_note: true,
      update_note: false,
      fields: [
        { key: "title", label: "Title", sortable: true, sortDirection: "desc" },
        {
          key: "description",
          label: "Description",
          class: "adjst_note",
          sortable: true,
          sortDirection: "desc",
        },
        { key: "actions", label: "Actions" },
      ],
      faTrashAlt,
      faEdit,
      faCaretDown,
      faBuilding,
      faUser,
      faPencilAlt,
      faEye,
      faPlus,
      faPhoneAlt,
      faUserFriends,
      faClock,
      faFlag,
      faUserCircle,
      faPaperPlane,
      faUtensils,
      faLink,
      faClipboardList,
      faStickyNote,
      faCalendar,
      faCalendarCheck,
      faReceipt,
      popoverOwnerShow: false,
      popoverStatusShow: false,
      updateDealOrganisationClicked: false,
      updateDealPersonClicked: false,
      submitDealNotesClicked: false,
      updateDealNotesClicked: false,
      ownerValue: { name: authUser.name, id: authUser.id },
      currencies: Constants.currrenciesDetail,
      validationErrorMessages: Constants.validationErrorMessages,
      formDealNote: {
        title: "",
        description: "",
        deal_id: "",
      },
      updateDealNote: {
        title: "",
        description: "",
        note_id: "",
      },
      formTransfer: {
        owner: null,
      },

      formOrganization: {
        Address: "",
        Phone: null,
        Website: "",
        Title: "",
        Organisation: "",
        currency: "",
        turnover: "",
        closeDate: "",
      },
      formPerson: {
        personFirstName: "",
        personLastName: "",
        source_of_lead: "",
        personName: [],
        personPhone: [{ value: "" }],
        personEmail: [{ value: "" }],        
        personFile: [{ value: null }],
      },
      selected_option: null,
      options_scheduler: [
        // { value: null, text: 'Please select an option' },
        { value: "free", text: "Free" },
        { value: "busy", text: "Busy" },
      ],

      stagesDetail: Constants.stagesDetail,
      submitRecords: [],
      fileRecords: [],
      linkable: true,
      pipelineStageConstants: Constants.miscellaneous,
      uploadUrl: window.location.origin + Constants.miscellaneous.storage.files,
      uploadHeaders: { "X-Test-Header": "vue-file-agent" },
      fileRecordsForUpload: []// maintain an upload queue
    };
  },
      validations: {
    formOrganization:{
    //  Address:{
    //      required,
    //  },
     Phone:{
      //  required,
        maxLength: maxLength(15)
     },
    //  Website:{
    //    required,
    //  },
     Title:{
       required,
       maxLength: maxLength(100)
     },
     Organisation:{
       required,
       maxLength: maxLength(100)
     },
    //  currency:{
    //    required,
    //  },
      turnover:{
      // required,
      between: between(0, 10000000000)
     },
    //   closeDate:{
    //    required,
    //  },
    },
    formPerson:{
      personFirstName:{
        required
      }
    },
    formDealNote:{
    title:{
      required
    },
    description:{
      required
    }
    },
    updateDealNote:{
    title:{
      required
    },
    description:{
      required
    }
    }
  },
  computed: {
    ...mapState({
      currentView: (state) => state.currentView,
      deal: (state) => state.deal,
      users: (state) => state.deal.users      
    }),
    isTabHidden(){ // Hide Tab If user role does not match or is not an owner
        if(typeof this.deal.deal != undefined && this.deal.deal != null){
          var hasPermission = authUser.id == this.deal.deal.owner_id ? true : false
            if(this.$functions.checkIfUserHasPermissionToViewButtons(authUser.availableFrontendPermissions,'viewInvoiceTab','project-frontend-tab',hasPermission)){
               return false
            }else{
               return true
            }
        }else{
          return true
        }
    },
    isDealDataLoaded(){
       if(typeof this.deal.deal != undefined && this.deal.deal != null){
        this.formTransfer.owner =  this.deal.deal.owner_id;
          this.formPerson.personFirstName =
            this.deal.deal.get_stage_data[0].get_deal_person.first_name;
            if(this.deal.deal.get_stage_data[0].get_deal_person.last_name != null){
          this.formPerson.personLastName =
            this.deal.deal.get_stage_data[0].get_deal_person.last_name;
          }
          if(this.deal.deal.get_stage_data[0].get_deal_person.source_of_lead != null){
          this.formPerson.source_of_lead =
            this.deal.deal.get_stage_data[0].get_deal_person.source_of_lead;
          }
          if (
            this.deal.deal.get_stage_data[0].get_deal_person.phone &&
            this.deal.deal.get_stage_data[0].get_deal_person.phone.length != 0
          ) {
            this.formPerson.personPhone = JSON.parse(
              this.deal.deal.get_stage_data[0].get_deal_person.phone
            );
          }
          if (
            this.deal.deal.get_stage_data[0].get_deal_person.extra_names &&
            this.deal.deal.get_stage_data[0].get_deal_person.extra_names.length != 0
          ) {
            this.formPerson.personName = JSON.parse(
              this.deal.deal.get_stage_data[0].get_deal_person.extra_names
            );
          }

          if (
            this.deal.deal.get_stage_data[0].get_deal_person.email &&
            this.deal.deal.get_stage_data[0].get_deal_person.email.length != 0
          ) {
            this.formPerson.personEmail = JSON.parse(
              this.deal.deal.get_stage_data[0].get_deal_person.email
            );
          }

          if (
            this.deal.deal.get_stage_data[0].get_deal_person.files &&
            this.deal.deal.get_stage_data[0].get_deal_person.files.length != 0
          ) {
              this.Records = this.deal.deal.get_stage_data[0].get_deal_person.files;
              this.fileRecords = this.getFileRecordsInitial(this.Records);
              this.submitRecords = this.fileRecords;              
          }

          this.formOrganization.Address =
            this.deal.deal.get_stage_data[0].get_deal_organisation.address;
          this.formOrganization.Phone =
            this.deal.deal.get_stage_data[0].get_deal_organisation.phone;
          this.formOrganization.Website =
            this.deal.deal.get_stage_data[0].get_deal_organisation.website;
          this.formOrganization.Organisation =
            this.deal.deal.get_stage_data[0].get_deal_organisation.organisation;
          this.formOrganization.Title =
            this.deal.deal.get_stage_data[0].get_deal_organisation.title;
          this.formOrganization.turnover =
            this.deal.deal.get_stage_data[0].get_deal_organisation.turnover;
          var self = this           
          this.formOrganization.currency = this.currencies.filter(function (currency) { return currency.code == self.deal.deal.get_stage_data[0].get_deal_organisation.currency_code });
          this.formOrganization.closeDate =
            this.deal.deal.get_stage_data[0].get_deal_organisation.expected_close_date;
          return true;
       }else{
          return false;
       }
    }
    
  },
  created() {
    // this.getAllDealsData();    Deal Data is loaded From Index File
  },

  methods: {
    ...mapActions([
      "showNotification",
      "toggleLoading",
      "getDeal",
      "getDealUsers",
    ]),
    GetUserById(userId) {
      var recordData = this.users.find((x) => x.id === userId);
      return recordData;
    },
    updateNote(note_id) {
      this.updateDealNote.note_id = note_id;
      // console.log("note_id", note_id);
      // console.log("get_deal_notes", this.deal.deal.get_deal_notes);

      var recordData = this.deal.deal.get_deal_notes.find(
        (x) => x.id === note_id
      );
      // console.log("recordData", recordData);
      this.update_note = true;
      this.add_note = false;
      this.view_note = false;

      this.updateDealNote.title = recordData.title;
      this.updateDealNote.description = recordData.description;
      // this.updateDealNote.title = note_id;
      // this.updateDealNote.description = note_id;
    },
    deleteNote(note_id) {
      this.$confirm({
        message: `Are you sure you want to delete deal note?`,
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
              .delete("/data/deal/note/" + note_id,{params: {'deal_id': this.$route.params.id}})
              .then((response) => {
                // console.log("response", response);
                this.toggleLoading(false);
                if (response.data.status === "success") {
                  this.getAllDealsData();
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
    collapseDealNotes(event) {
      // setTimeout(function(){
      if (event.target.attributes[0].nodeValue == "view") {
        // console.log('view', event.target.attributes[0].nodeValue);
        // console.log('view', this.view_note);
        if (this.view_note == false) {
          this.add_note = false;
          this.update_note = false;
          this.view_note = true;
        } else {
          // this.view_note = false;
        }
      }

      if (event.target.attributes[0].nodeValue == "add") {
        // console.log('add', event.target.attributes[0].nodeValue);
        // console.log('add', this.add_note);
        if (this.add_note == false) {
          this.view_note = false;
          this.update_note = false;
          this.add_note = true;
        } else {
          // this.add_note = false;
        }
      }
      // }, 400);
    },
    onResetDealNote(event) {
      this.formDealNote.title = "";
      this.formDealNote.description = "";
    },
    displayErrorMessageNotesUpdate(value) {
      if (!value && this.updateDealNotesClicked) {
        return true;
      } else {
        return false;
      }
    },
    onUpdateDealNote(event) {
       this.updateDealNotesClicked = true;
    if (this.$v.updateDealNote.$invalid == true) {
        return false;
      } else {
        this.updateDealNotesClicked = false;

      event.preventDefault();
      // console.log("updateDealNote", this.updateDealNote);
      this.toggleLoading(true);
      axios
        .post("/data/deal/update/note", {
          data: {...this.updateDealNote,...{'deal_id': this.$route.params.id}},
        })
        .then((response) => {
          this.toggleLoading(false);
          if (response.data.status === "success") {
            this.getAllDealsData();
            this.update_note = false;
            this.view_note = true;

            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });

            this.popoverOwnerShow = false;
          }
        })
        .catch((error) => {
          this.toggleLoading(false);
          // console.log("error", error);
          this.showNotification({
            type: error.response.data.status,
            message: error.response.data.message,
          });
          this.popoverOwnerShow = false;
        });
      }
    },
    displayErrorMessageNotes(value) {
      if (!value && this.submitDealNotesClicked) {
        return true;
      } else {
        return false;
      }
    },

    onSubmitDealNote(event) {
      this.submitDealNotesClicked = true;
    if (this.$v.formDealNote.$invalid == true) {
        return false;
      } else {
        this.submitDealNotesClicked = false;

      event.preventDefault();
      this.formDealNote.deal_id = this.$route.params.id;
      this.toggleLoading(true);
      axios
        .post("/data/deal/add/note", {
          data: this.formDealNote,
        })
        .then((response) => {
          this.toggleLoading(false);
          if (response.data.status === "success") {
            this.onResetDealNote();
            this.getAllDealsData();
            this.add_note = false;
            this.view_note = true;
            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });

            this.popoverOwnerShow = false;
          }
        })
        .catch((error) => {
          this.toggleLoading(false);
          // console.log("error", error);
          this.showNotification({
            type: error.response.data.status,
            message: error.response.data.message,
          });
          this.popoverOwnerShow = false;
        });
      }
    },
    customFormatter(date) {
      return moment(date).format(Constants.customDateFormat.format);
    },
    onSubmitOwnerTransfer(event) {
      event.preventDefault();
      this.toggleLoading(true);
      // console.log("formTransfer.owner", this.formTransfer.owner);
      axios
        .post(
          "/data/deal-transfer-owner/update/" +
            this.deal.deal.get_stage_data[0].id,
          {
            owner: this.formTransfer.owner,
          }
        )
        .then((response) => {
          // console.log("response", response);
          this.toggleLoading(false);
          if (response.data.status === "success") {
            var dealsParam = { loadMore: false };
            this.getAllDealsData();
            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });

            this.popoverOwnerShow = false;
          }
        })
        .catch((error) => {
          this.toggleLoading(false);
          // console.log("error", error);
          this.showNotification({
            type: error.response.data.status,
            message: error.response.data.message,
          });
          this.popoverOwnerShow = false;
        });
    },

     displayErrorMessage(value) {
      if (!value && this.updateDealOrganisationClicked) {
        return true;
      } else {
        return false;
      }
    },
    onSubmitDealOrganisation(event) {
    this.updateDealOrganisationClicked = true;
    if (this.$v.formOrganization.$invalid == true) {
        return false;
      } else {
        this.updateDealOrganisationClicked = false;
        event.preventDefault();
        this.toggleLoading(true);
        var closeDateField = this.$functions.setDateFormat(this.formOrganization.closeDate);
      // var closeDate = this.formOrganization.closeDate;
      // if (closeDate.length != 10) { // Meaning close date already exists
      //   var closeDateField = closeDate
      //     ? window.luxon.DateTime.fromISO(closeDate.toISOString()).toISODate()
      //     : null;
      // } else {
      //   var closeDateField = closeDate
      //     ? window.luxon.DateTime.fromISO(closeDate).toISODate()
      //     : null;
      // }

      axios
        .post(
          "/data/deal-organisation/update/" +
            this.deal.deal.get_stage_data[0].id,
          {
            data: this.formOrganization,
            closeDateField: closeDateField,
          }
        )
        .then((response) => {
          // console.log("response", response);
          this.toggleLoading(false);
          if (response.data.status === "success") {
            var dealsParam = { loadMore: false };
            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });
          }
        })
        .catch((error) => {
          this.toggleLoading(false);
          // console.log("error", error);
          this.showNotification({
            type: error.response.data.status,
            message: error.response.data.message,
          });
        });
      }
    },
    getAllDealsData() {
      this.getDeal(this.$route.params.id);
      // this.getDealUsers();
    },

    fileformatNames(files) {
      return files.length === 1
        ? files[0].name
        : `${files.length} files selected`;
    },
    onFileChange(event) {
      var fileData = [];
      $(".fileData").each(function (index) {
        fileData.push($(this)[0].files[0]);
      });
      this.files = fileData;
    },

    displayErrorMessagePerson(value) {
      if (!value && this.updateDealPersonClicked) {
        return true;
      } else {
        return false;
      }
    },
    onSubmitDealPerson(event) {
      this.updateDealPersonClicked = true;
      if (this.$v.formPerson.$invalid == true) {
        return false;
      } else {
        this.updateDealPersonClicked = false;
      event.preventDefault();
      this.toggleLoading(true);
      let fileData = this.files;
      let personNameData = this.formPerson.personName;
      let phoneData = this.formPerson.personPhone;
      let emailData = this.formPerson.personEmail;
      let personFirstName = this.formPerson.personFirstName;
      let personLastName = this.formPerson.personLastName;
      let source_of_lead = this.formPerson.source_of_lead;

      const formData = new FormData();
      formData.append("personNameData", JSON.stringify(personNameData));
      formData.append("phoneData", JSON.stringify(phoneData));
      formData.append("emailData", JSON.stringify(emailData));
    
      if (typeof fileData !== "undefined" && fileData != null) {
        for (var t = 0; t < fileData.length; t++) {
          formData.append("fileData[]", fileData[t], fileData[t].name);
        }
      }

      formData.append("deal_id", this.deal.deal.get_stage_data[0].id);
      formData.append("personFirstName", personFirstName);
      formData.append("personLastName", personLastName);
      formData.append("source_of_lead", source_of_lead);

      axios
        .post(
          "/data/deal-person/update/" + this.deal.deal.get_stage_data[0].id,
          formData
        )
        .then((response) => {
          this.toggleLoading(false);
          if (response.data.status === "success") {
            var dealsParam = { loadMore: false };
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
      }
    },
    onCloseOwner() {
      this.popoverOwnerShow = false;
    },
    onCloseStatus() {
      this.popoverStatusShow = false;
    },
    // validateEmail(value) {
    //   if (value && value.length) {
    //     if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
    //       return false;
    //     } else {
    //       return true;
    //     }
    //   } else {
    //     return false;
    //   }
    // },
    removeFile(i) {
      this.formPerson.personFile.splice(parseInt(i), 1);
    },
    removeEmail(i) {
      this.formPerson.personEmail.splice(parseInt(i), 1);
    },
    removePhone(i) {
      this.formPerson.personPhone.splice(parseInt(i), 1);
    },
    removePersonName(i) {
      this.formPerson.personName.splice(parseInt(i), 1);
    },
    addPersonPhone() {
      this.formPerson.personPhone.push({
        value: "",
      });
    },
    addPersonPersonName() {
      this.formPerson.personName.push({
        value: "",
      });
    },
    addPersonFile() {
      this.formPerson.personFile.push({
        value: null,
      });
    },
    addPersonEmail() {
      this.formPerson.personEmail.push({
        value: "",
      });
    },
    getOnlyAvailableUsers(){ // get only not deleted users in dropdown
       if(this.users != null) {
         var users =  this.users.map((item) => {
                return item.deleted == false ? item : null;              
         });
         return Object.fromEntries(Object.entries(users).filter(([_, v]) => v != null));
       }else {
         return []
       }
    },

    GetOwnerName(owner_id) {
      var GetDetail = this.users.filter(function (obj) {
        return obj.id === owner_id;
      });

      return GetDetail[0].name;
    },
    OwnerWithFormat({ name, id }) {
      return `${id == this.ownerValue.id ? name + " (You)" : name}`;
    },
    CurrencyFormat({ name }) {
      return `${name}`;
    },
    GetStageDateDiif(then) {
      if (then == null) {
        var s = 0;
        s = s + " days";
      } else {
        var s = 0;
     
        var myDate = new Date(then);
        myDate = moment(myDate).format("YYYY-MM-DD");
        var startDate = moment(myDate, "YYYY-MM-DD")._i;

        var toDay = moment();
       
        s = toDay.diff(startDate, "days");
        if (s > 1) {
          s = s + " days";
        } else {
          s = s + " day";
        }
      }

      return s;
    },
    //File Upload code start..............................

    getFileRecordsInitial: function (records) {
      for (let i = 0; i < records.length; i++) {
            if (typeof records[i].size != 'undefined' || records[i].size != null ) {
                  return records;
            }
      }      
        
      var newRecords = [];
      if (records && records.length > 0) {        
        records.forEach(function (fd) {
          fd.url =
            window.location.origin +
            Constants.miscellaneous.storage.files +
            fd.name;
          newRecords.push(fd);
        });       
      }
      return newRecords;
    },

    uploadFiles: function () {
      // console.log(this.submitRecords.length);
      if(this.fileRecordsForUpload.length == 0 && this.submitRecords.length == 0){
        this.showNotification({
            type: 'error',
            message: 'Select the file to upload',
          });
        return false;
      }else{
      var formData_new = new FormData();
      for (var t = 0; t < this.fileRecordsForUpload.length; t++) {
        formData_new.append(
          "fileData_new[]",
          this.fileRecordsForUpload[t].file
        );
      }

      formData_new.append("fileData", JSON.stringify(this.submitRecords));

      axios
        .post(
          "/data/deal-files/" + this.deal.deal.get_stage_data[0].id,
          formData_new,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          this.toggleLoading(false);
          if (response.data.status === "success") {
            var dealsParam = { loadMore: false };
            this.showNotification({
              type: response.data.status,
              message: response.data.message,
            });
          }
          if (response.data.status === "error") {
            var dealsParam = { loadMore: false };
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
      }
    },
    prefilesSelected: function (fileRecords) {
      var validFileRecords = [];
      for (var i = 0; i < fileRecords.length; i++) {
        if (!fileRecords[i].error) {
          validFileRecords.push(fileRecords[i]);
        }
      }
      this.fileRecordsForUpload =
        this.fileRecordsForUpload.concat(validFileRecords);
    },
    filesSelected: function (fileRecords) {
      var validFileRecords = [];
      for (var i = 0; i < fileRecords.length; i++) {
        if (!fileRecords[i].error) {
          validFileRecords.push(fileRecords[i]);
        }
      }
      this.fileRecordsForUpload =
        this.fileRecordsForUpload.concat(validFileRecords);
    },
    deleteUploadedFile: function (fileRecord) {
      // Using the default uploader. You may use another uploader instead.
      this.$refs.vueFileAgent.deleteUpload(
        this.uploadUrl,
        this.uploadHeaders,
        fileRecord
      );
    },
    onBeforeDelete: function (fileRecord) {
      var i = this.fileRecordsForUpload.indexOf(fileRecord);
      var j = this.submitRecords.indexOf(fileRecord);

      if (i !== -1) {
        // queued file, not yet uploaded. Just remove from the arrays
        this.fileRecordsForUpload.splice(i, 1);
        var k = this.fileRecords.indexOf(fileRecord);
        if (k !== -1) this.fileRecords.splice(k, 1);
      } else {
        if (confirm("Are you sure you want to delete?")) {
          this.$refs.vueFileAgent.deleteFileRecord(fileRecord); // will trigger 'delete' event
        }
      }
    },
    fileDeleted: function (fileRecord) {
      var i = this.fileRecordsForUpload.indexOf(fileRecord);
      var j = this.submitRecords.indexOf(fileRecord);

      if (i !== -1) {
        this.fileRecordsForUpload.splice(i, 1);
      } else {
        this.submitRecords.splice(j, 1);
        // this.deleteUploadedFile(fileRecord);
      }
    },
    refreshActvity: function () {
      this.getDeal(this.$route.params.id)
    },       

  
  },
  
  mounted() {
    this.toggleLoading(false);
  },
};
</script>

