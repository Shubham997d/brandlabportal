<template>
  <b-card no-body>
    <b-tabs pills card>
      <b-tab title="Create Activity" active
        ><b-card-text>
          <div class="deal-activity-details">
            <b-form ref="form_activity" @submit.prevent="onSubmitDealActivity">
              <div class="activity-group-div">
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
                          v-model="formActivity.events.Call"
                          :data-value="1"
                          type="text"
                          placeholder="Call"
                          @change="eventschanged"
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
                          v-model="formActivity.events.Meeting"
                          placeholder="Meeting"
                          type="text"
                          @change="eventschanged"
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
                          v-model="formActivity.events.Task"
                          placeholder="Task"
                          type="text"
                          @change="eventschanged"
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
                          v-model="formActivity.events.Deadline"
                          placeholder="Deadline"
                          type="text"
                          @change="eventschanged"
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
                          v-model="formActivity.events.Email"
                          placeholder="Email"
                          type="text"
                          @change="eventschanged"
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
                          v-model="formActivity.events.Linkedin"
                          placeholder="Linkedin"
                          type="text"
                          @change="eventschanged"
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
                        v-model="formActivity.events.start_date"
                        ref="dueOnDate"
                        @closed="eventschanged"
                        :placeholder="$options.filters.localize('Start Date')"
                        :disabled-dates="formActivityStart.disabledDates"
                        :format="customFormatter"
                        :highlighted="state.highlighted"
                        input-class="form-control"
                      >
                      </datepicker>
                      <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formActivity.events.start_date.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div>
                    </div>
                    <div class="time-activity">
                    <vue-timepicker
                      lazy
                      v-model="formActivity.events.start_time"
                      ref="taskDuration"
                      format="HH:mm"
                      :minute-interval="15"
                      input-class="form-control"
                      @change="eventschanged"
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
                        v-model="formActivity.events.end_date"
                        ref="dueOnDate"
                        :disabled-dates="formActivityEnd.disabledDates"
                        :placeholder="$options.filters.localize('End Date')"
                        :highlighted="state.highlighted"
                        :format="customFormatter"
                        input-class="form-control"
                        @closed="eventschanged"
                      >
                      </datepicker>
                      <div
                        class="error"
                        v-if="
                          displayErrorMessage(
                            $v.formActivity.events.end_date.required
                          )
                        "
                      >
                        {{ validationErrorMessages.required }}.
                      </div>
                    </div>
                    <div class="time-activity">
                    <vue-timepicker
                      lazy
                      v-model="formActivity.events.end_time"
                      ref="taskDuration"
                      format="HH:mm"
                      :minute-interval="15"
                      input-class="form-control"
                      @change="eventschanged"
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
                      v-model="formActivity.scheduler"
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
                      v-model="formActivity.notes"
                      id="textarea-rows"
                      rows="8"
                      placeholder="Notes"
                    ></b-form-textarea>
                    <div
                      class="error"
                      v-if="displayErrorMessage($v.formActivity.notes.required)"
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
                    <b-form-select v-model="formActivity.userId">
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
                        displayErrorMessage($v.formActivity.userId.required)
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
                      v-model="formActivity.deal"
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
                      v-model="formActivity.status"
                    >
                      Mark as done
                    </b-form-checkbox>
                  </div>
                </div>
              </div>

              <div class="deal-activity-submit">
                <b-button type="submit" class="main-button">Save</b-button>
              </div>
            </b-form>
            <v-row class="fill-height cstm-activity-calender">
              <v-col class="calender-container">
                <v-sheet height="64">
                  <v-toolbar flat>
                    <v-btn
                      outlined
                      class="mr-4"
                      color="grey darken-2"
                      @click="setToday"
                    >
                      Today
                    </v-btn>
                    <font-awesome-icon
                      :icon="faChevronCircleLeft"
                      @click="prev"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                    <font-awesome-icon
                      :icon="faChevronCircleRight"
                      @click="next"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                    <v-toolbar-title v-if="$refs.calendar">
                      {{ $refs.calendar.title }}
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
            <span class="activity-type-name">{{ typeToLabel[type] }}</span>
             <b-dropdown class="activity-type-dropdown" :value= typeToLabel[type] >
                <b-dropdown-item @click="type = 'day'">Day</b-dropdown-item>
                <b-dropdown-item @click="type = 'week'">Week</b-dropdown-item>
                <b-dropdown-item @click="type = 'month'">Month</b-dropdown-item>
                <b-dropdown-item @click="type = '4day'">4 days</b-dropdown-item>
              </b-dropdown>
       </v-toolbar>
     </v-sheet>
                <v-sheet height="600">
                  <v-calendar
                    ref="calendar"
                    v-model="focus"
                    color="primary"
                    :events="events"
                    :event-color="getEventColor"
                    :type="type"
                    @click:more="viewDay"
                    @click:date="viewDay"
                  >
                    <template v-slot:event="{ event, timed, eventSummary }">
                      <div class="event-popup" @click="triggerEvent(event)">
                        <font-awesome-icon
                          :icon="faEye"
                          class="cursor-pointer text-sm"
                        ></font-awesome-icon>
                        <div
                          class="v-event-draggable"
                          v-html="eventSummary()"
                        ></div>
                      </div>
                      <div
                        v-if="timed"
                        class="v-event-drag-bottom"
                        @mousedown.stop="extendBottom(event)"
                      ></div>
                    </template>
                  </v-calendar>
                </v-sheet>
              </v-col>
            </v-row>
          </div> </b-card-text
      ></b-tab>
      <b-tab title="Activity List"
        ><b-card-text>
          <div class="invoice_list_table">
            <b-table
              ref="table"
              class="striped dealactivity"
              :items="activityList ? activityList : []"
              :fields="fields"
              show-empty
              small
            >
              <template #cell(actions)="row">
                <b-button
                  size="sm"
                  @click="deleteActivty(row.item.id)"
                  class="mr-1 main-button"
                >
                  <font-awesome-icon :icon="faTrashAlt"></font-awesome-icon>
                  Delete
                </b-button>

                <b-button
                  size="sm"
                  @click="updateActivity(row.item.id)"
                  class="mr-1 main-button"
                >
                  <font-awesome-icon :icon="faEdit"></font-awesome-icon>
                  Update
                </b-button>
              </template>
            </b-table>
          </div>
        </b-card-text></b-tab
      >
    </b-tabs>
    <div class="update-activity-modal">
      <b-modal
        ref="activity-modal"
        id="update-activity"
        hide-footer
        title="Update Activity"
      >
        <b-form ref="form_activity" @submit.prevent="onUpdateDealActivity">
          <div class="activity-group-div">
            <div class="activity-btab">
              <b-tabs pills card end active-tab-class="update-activity-event-value" v-model="updateFormActivity.tabIndex"> 
                <b-tab>
                  <template #title>
                    <font-awesome-icon
                      :icon="faPhoneAlt"
                      class="cursor-pointer text-sm"
                    ></font-awesome-icon>
                  </template>
                  <b-card-text
                    ><b-form-input
                      v-model="updateFormActivity.events.Call"
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
                      v-model="updateFormActivity.events.Meeting"
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
                      v-model="updateFormActivity.events.Task"
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
                      v-model="updateFormActivity.events.Deadline"
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
                      v-model="updateFormActivity.events.Email"
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
                      v-model="updateFormActivity.events.Linkedin"
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
                    lazy
                    v-model="updateFormActivity.events.start_date"
                    :placeholder="$options.filters.localize('Start Date')"
                    :format="customFormatter"
                    :highlighted="state.highlighted"
                    :disabled-dates="updateFormActivityStart.disabledDates"
                    @closed="eventUpdate"
                    input-class="form-control"
                  >
                  </datepicker>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageUpdate(
                        $v.updateFormActivity.events.start_date.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="time-activity">
                <vue-timepicker
                  lazy
                  v-model="updateFormActivity.events.start_time"
                  ref="taskDuration"
                  format="HH:mm"
                  :minute-interval="15"
                  input-class="form-control"
                >
                </vue-timepicker>
                <!-- <div
                    class="error"
                    v-if="
                      displayErrorMessageUpdate(
                        $v.updateFormActivity.events.start_time.required
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
                    v-model="updateFormActivity.events.end_date"
                    :format="customFormatter"
                    :highlighted="state.highlighted"
                    :disabled-dates="updateFormActivityEnd.disabledDates"
                    :placeholder="$options.filters.localize('End Date')"
                    @closed="eventUpdate"
                    input-class="form-control"
                  >
                  </datepicker>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageUpdate(
                        $v.updateFormActivity.events.end_date.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="time-activity">
                <vue-timepicker
                  lazy
                  v-model="updateFormActivity.events.end_time"
                  ref="taskDuration"
                  format="HH:mm"
                  :minute-interval="15"
                  input-class="form-control"
                >
                </vue-timepicker>
                <!-- <div
                    class="error"
                    v-if="
                      displayErrorMessageUpdate(
                        $v.updateFormActivity.events.end_time.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div> -->
                </div>  
              </div>
            </div>

            <div class="form-acticty-select width-full">
              <font-awesome-icon
                :icon="faCalendar"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <div class="activity-inputs">
                <b-form-select
                  v-model="updateFormActivity.scheduler"
                  :options="options_scheduler"
                ></b-form-select>
              </div>
            </div>
            <div class="activity-textarea width-full">
              <font-awesome-icon
                :icon="faClipboardList"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <div class="activity-inputs">
                <b-form-textarea
                  v-model="updateFormActivity.notes"
                  id="textarea-rows"
                  rows="8"
                  placeholder="Notes"
                ></b-form-textarea>
                <div
                  class="error"
                  v-if="
                    displayErrorMessageUpdate(
                      $v.updateFormActivity.notes.required
                    )
                  "
                >
                  {{ validationErrorMessages.required }}.
                </div>
              </div>
            </div>
            <div class="form-acticty-select width-full">
              <font-awesome-icon
                :icon="faUserCircle"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <div class="activity-inputs">
                <b-form-select v-model="updateFormActivity.userId">
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
                    displayErrorMessageUpdate(
                      $v.updateFormActivity.userId.required
                    )
                  "
                >
                  {{ validationErrorMessages.required }}.
                </div>
              </div>
            </div>
            <div class="form-activity-input width-full">
              <font-awesome-icon
                :icon="faLink"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <div class="activity-inputs">
                <b-form-input
                  v-model="updateFormActivity.deal"
                  type="text"
                ></b-form-input>
              </div>
            </div>
            <div class="form-activity-checkbox ">
              <font-awesome-icon
                :icon="faCheckCircle"
                class="cursor-pointer text-sm"
              ></font-awesome-icon>
              <div class="activity-inputs">
                <b-form-checkbox  id="checkbox-1" v-model="updateFormActivity.status">
                  Mark as done
                </b-form-checkbox>
              </div>
            </div>
          </div>

          <div class="update-activity-submit">
            <b-button
              class="bg-white border rounded btn-secondary btn-block"
              block
              @click="$bvModal.hide('update-activity')"
              >Cancel</b-button
            >
            <b-button type="submit" class="main-button">Update</b-button>
          </div>
        </b-form>
      </b-modal>
    </div>
  </b-card>
</template>

<script>
import { mapActions, mapState } from "vuex";
import Datepicker from "vuejs-datepicker";
import { required } from "vuelidate/lib/validators";
import moment from "moment";
import VueTimepicker from "vue2-timepicker";
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
  faChevronCircleLeft,
  faChevronCircleRight,
  faCheckCircle,
} from "@fortawesome/free-solid-svg-icons";

import { faLinkedin } from "@fortawesome/free-brands-svg-icons";

export default {
  name: "deal-activity",
  components: {
    Datepicker,
    VueTimepicker,
  },
  data: () => ({
    createProjectButtonClicked: false,
    updateProjectButtonClicked: false,
    focus: "",
    events: [],
    type: 'month',
    typeToLabel: {
      month: 'Month',
      week: 'Week',
      day: 'Day',
      '4day': '4 Days',
    },
    dragEvent: null,
    dragStart: null,
    createEvent: null,
    createStart: null,
    extendOriginal: null,
    fields: [
      // { key: "Id", label: "ID", sortable: true, sortDirection: "desc" },
      {
        key: "activity_subject",
        label: "Activity Subject",
        sortable: true,
        sortDirection: "desc",
      },
      {
        key: "activity_type",
        label: "Activity Type",
        sortable: true,
        sortDirection: "desc",
      },
      {
        key: "timeline_time_in",
        label: "Start Date",
        sortable: true,
        sortDirection: "desc",
      },
      {
        key: "timeline_time_out",
        label: "End Date",
        sortable: true,
        sortDirection: "desc",
      },
      { key: "actions", label: "Actions" },
    ],
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
    updateFormActivity: {
      userId: null,
      activityId: "",
      status: false,
      tabIndex: 0,
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

    state:{
      highlighted: {
          dates:[
            new Date()
          ]
      }
    },
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
    faChevronCircleLeft,
    faChevronCircleRight,
    faLinkedin,
    faCheckCircle,
    selected_option: null,
    activity_types: Constants.dealDetail.activity_types,
    options_scheduler: [
      // { value: null, text: 'Please select an option' },
      { value: "free", text: "Free" },
      { value: "busy", text: "Busy" },
    ],
    validationErrorMessages: Constants.validationErrorMessages,
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
    updateFormActivityEnd: {
      disabledDates: {
        to: ""
      }
    },
    updateFormActivityStart:{
      disabledDates: {
        from: ""
      }
    }
  }),

  validations: {
    formActivity: {
      events: {
        start_date: {
          required,
        },
        end_date: {
          required,
        },
        // start_time: {
        //   required
        // },
        // end_time : {
        //   required
        // }
      },
      notes: {
        required,
      },
      userId: {
        required,
      },
    },
    updateFormActivity: {
      events: {
        start_date: {
          required,
        },
        end_date: {
          required,
        },
        //  start_time: {
        //   required
        // },
        // end_time : {
        //   required
        // }
      },
      notes: {
        required,
      },
      userId: {
        required,
      },
    },
  },
  computed: {
    ...mapState({
      currentView: (state) => state.currentView,
      deal: (state) => state.deal,
      users: (state) => state.deal.users,
    }),
    usersDropDownDown() {
      return this.getOnlyAvailableUsers();
    },
    activityList() {
        if (this.deal.deal.get_stage_data[0].get_deal_activity != null) {             
            var value = this.deal.deal.get_stage_data[0].get_deal_activity;
            const eventval = [];
            for (let i = 0; i < value.length; i++) {
              var activity_data = JSON.parse(value[i].activity_data)
                activity_data.id = value[i].id ? value[i].id : null           
                eventval.push(activity_data);              
            }
            this.getEvents(eventval);
            return this.deal.deal.get_stage_data[0].get_deal_activity;
        }else{
            return []
        }
    }
  },
  created() {
    // setTimeout(() => {
    //   if (this.deal.deal.get_stage_data[0].get_deal_activity != null) {
    //     this.activityList = this.deal.deal.get_stage_data[0].get_deal_activity;
    //     var value = this.deal.deal.get_stage_data[0].get_deal_activity;
    //     const eventval = [];
    //     for (let i = 0; i < value.length; i++) {
    //       eventval.push(JSON.parse(value[i].activity_data));
    //     }
    //     this.getEvents(eventval);
    //   }
    // }, 1000);
  },

  methods: {
    ...mapActions(["toggleLoading", "showNotification","getDeal"]),

    customFormatter(date) {
      return moment(date).format(Constants.customDateFormat.format);
    },
    getOnlyAvailableUsers() {
      // get only not deleted users in dropdown
      if (this.users != null) {
        var users = this.users.map((item) => {
          return item.deleted == false ? item : null;
        });
        return Object.fromEntries(
          Object.entries(users).filter(([_, v]) => v != null)
        );
      } else {
        return [];
      }
    },

    
    triggerEvent(value) {   
      this.updateActivity(value.activity_id)
      return false
      Swal.fire({
        title: Constants.dealStatusSwalPopup.lost.title,
        text: Constants.dealStatusSwalPopup.lost.text,
        icon: Constants.dealStatusSwalPopup.lost.icon,
        showCancelButton: Constants.dealStatusSwalPopup.lost.showCancelButton,
        confirmButtonColor:
          Constants.dealStatusSwalPopup.lost.confirmButtonColor,
        cancelButtonColor: Constants.dealStatusSwalPopup.lost.cancelButtonColor,
        confirmButtonText: Constants.dealStatusSwalPopup.lost.confirmButtonText,
      }).then((result) => {
        if (result.isConfirmed) {
          this.events = this.arrayRemove(this.events, value);
          axios
            .post(
              "/data/deal-activity-delete/" +
                this.deal.deal.get_stage_data[0].id,
              {
                data: value,
              }
            )
            .then((response) => {
              // console.log("response", response);
              if (response.data.status === "success") {
                this.$emit('refreshActvity');
                this.$refs.form_activity.reset();
                var dealsParam = { loadMore: false };
                this.showNotification({
                  type: response.data.status,
                  message: response.data.message,
                });
              }
            })
            .catch((error) => {
              console.log("error", error);
              this.showNotification({
                type: error.response.data.status,
                message: error.response.data.message,
              });
            });
        } else {
          return false;
        }
      });
    },
    arrayRemove(arr, value) {
      return arr.filter(function (ele) {
        return ele != value;
      });
    },
    displayErrorMessage(value) {
      if (!value && this.createProjectButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    displayErrorMessageUpdate(value) {
      if (!value && this.updateProjectButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    onSubmitDealActivity(event) {
      this.createProjectButtonClicked = true;
      if (this.$v.formActivity.$invalid == true) {
        return false;
      } else {
        var activityvalue = $(".activity-event-value")
          .find("input[class='form-control']")
          .val();
        var activitytype = $(".activity-event-value")
          .find("input[class='form-control']")
          .attr("placeholder");
        if (activityvalue) {
          this.formActivity.events.value = activityvalue;
          this.formActivity.events.type = activitytype;
        } else {
          this.formActivity.events.value = activitytype;
          this.formActivity.events.type = activitytype;
        }
        this.formActivity.events.start_date = this.$functions.setDateFormat(this.formActivity.events.start_date);
        this.formActivity.events.end_date = this.$functions.setDateFormat(this.formActivity.events.end_date);

        this.formActivity.events.start_time = this.$functions.setTimeCorrect(this.formActivity.events.start_time);
        this.formActivity.events.end_time = this.$functions.setTimeCorrect(this.formActivity.events.end_time);
        let start = this.formActivity.events.start_date.concat("T", this.formActivity.events.start_time);
      let end = this.formActivity.events.end_date.concat("T", this.formActivity.events.end_time);
      if (end >= start) {
        
        event.preventDefault();
        axios
          .post("/data/deal-activity/" + this.deal.deal.get_stage_data[0].id, {
            data: this.formActivity,
          })
          .then((response) => {
            // console.log("response", response);
            if (response.data.status === "success") {
               this.createProjectButtonClicked = false;
               this.$refs.form_activity.reset();
               this.onResetDealActivity();
               var dealsParam = { loadMore: false };
                this.$emit('refreshActvity');
                this.showNotification({
                  type: response.data.status,
                  message: response.data.message,
                });
            }
          })
          .catch((error) => {
            // console.log("error", error);
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
       }else{
         this.showNotification({
                type: "error",
                message: "End Date/Time Should be Greater Than Start Date/Time",
              });
       }
      }
    },
 onResetDealActivity(event) {
      this.formActivity.userId = null;
      this.formActivity.status = false;
      this.formActivity.scheduler = "free";
      this.formActivity.deal = "";
      this.formActivity.notes = "";
      this.formActivity.events.type = "";
      this.formActivity.events.value = "";
      this.formActivity.events.call = "";
      this.formActivity.events.meeting = "";
      this.formActivity.events.task = "";
      this.formActivity.events.deadline = "";
      this.formActivity.events.linkedin = "";
      this.formActivity.events.email = "";
      this.formActivity.events.start_date = "";
      this.formActivity.events.end_date = "";
      this.formActivity.events.start_time = "";
      this.formActivity.events.end_time = "";
      this.formActivityEnd.disabledDates.to = "";
      this.formActivityStart.disabledDates.from = "";
    },
     
    setToday() {
      this.focus = "";
    },
    viewDay ({ date }) {
          this.focus = date
          this.type = 'day'
        },
    prev() {
      this.$refs.calendar.prev();
    },
    next() {
      this.$refs.calendar.next();
    },
    getEventColor(event) {
      const rgb = parseInt(event.color.substring(1), 16);
      const r = (rgb >> 16) & 0xff;
      const g = (rgb >> 8) & 0xff;
      const b = (rgb >> 0) & 0xff;

      return event === this.dragEvent
        ? `rgba(${r}, ${g}, ${b}, 0.7)`
        : event === this.createEvent
        ? `rgba(${r}, ${g}, ${b}, 0.7)`
        : event.color;
    },
    getEvents(value) {
      const events = [];
      for (let i = 0; i < value.length; i++) {
        // const min = new Date(value[i].start).getTime();
        // const max = new Date(value[i].end).getTime();
        // const start = min - (max % 900000);
        // const end = max;
        var arr = Constants.DealActivityEvents.colors;
        const items = arr.filter((item) => item.name === value[i].type);
        events.push({
          name: value[i].value,
          color: items[0].color,
          start: value[i].start,
          end: value[i].end,
          activity_id: value[i].id,
          timed: true,
        });
      }
      // console.log(events, "events");
      this.events = events;
    },
    eventUpdate(){
      if (this.updateFormActivity.events.start_date) {
        this.updateFormActivityEnd.disabledDates.to = new Date(this.updateFormActivity.events.start_date);
      }
        if (this.updateFormActivity.events.end_date) {
        this.updateFormActivityStart.disabledDates.from = new Date(this.updateFormActivity.events.end_date);
      }
    },
    eventschanged() {
      if (this.formActivity.events.start_date.length == 10 && this.formActivity.events.start_date.length != "undefined") {
        this.formActivity.events.start_date = new Date(this.formActivity.events.start_date);
      }
      if (this.formActivity.events.end_date.length == 10 && this.formActivity.events.end_date.length != "undefined") {
        this.formActivity.events.end_date = new Date(this.formActivity.events.end_date);
      }
      if (this.formActivity.events.start_date) {
        this.formActivityEnd.disabledDates.to = this.formActivity.events.start_date;
      }
        if (this.formActivity.events.end_date) {
        this.formActivityStart.disabledDates.from = this.formActivity.events.end_date;
      }
      var start_date = moment(this.formActivity.events.start_date).format(
        "YYYY-MM-DD"
      );
      var end_date = moment(this.formActivity.events.end_date).format(
        "YYYY-MM-DD"
      );
        if (this.formActivity.events.start_time) {

        this.formActivity.events.start_time = this.$functions.setTimeCorrect(this.formActivity.events.start_time);
        }
        if (this.formActivity.events.end_time) {
        this.formActivity.events.end_time = this.$functions.setTimeCorrect(this.formActivity.events.end_time);
        }
      let start = start_date.concat("T", this.formActivity.events.start_time);
      let end = end_date.concat("T", this.formActivity.events.end_time);
      var activityvalue = $(".activity-event-value")
        .find("input[class='form-control']")
        .val();
      var activitytype = $(".activity-event-value")
        .find("input[class='form-control']")
        .attr("placeholder");
      var arr = Constants.DealActivityEvents.colors;
      // const event = [];
      const items = arr.filter((item) => item.name === activitytype);
      if (activityvalue) {
        var name = activityvalue;
      } else {
        var name = activitytype;
      }
      if (
        this.formActivity.events.start_date &&
        this.formActivity.events.end_date &&
        this.formActivity.events.start_time &&
        this.formActivity.events.end_time
      ) {
        if (end >= start) {
          this.events.push({
            name: name,
          color: items[0].color,
          start,
          end,
          timed: true,
        });
          }else{
             this.showNotification({
                type: "error",
                message: "End Date/Time Should be Greater Than Start Date/Time",
              });
          }
      }
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
    rndElement(arr) {
      return arr[this.rnd(0, arr.length - 1)];
    },

    deleteActivty(activity_id) {
      // this.items= this.items.filter(item=>item.Id!=activity_id)

      this.$confirm({
        message: `Are you sure you want to delete deal Activity?`,
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
              .delete("/data/deal/activity/" + activity_id)
              .then((response) => {
                // console.log("response", response);
                this.toggleLoading(false);
                if (response.data.status === "success") {
                  // this.getAllDealsData();
                  // this.$refs.table.refresh()
                    // const index = this.activityList.findIndex(
                    //   (activity) => activity.id === activity_id
                    // ); // find the post index
                    // if (~index)
                    //   // if the activity exists in array
                    // this.activityList.splice(index, 1);
                    this.$emit('refreshActvity');
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
      });
    },

    updateActivity(activity_id) {
      var recordData = this.deal.deal.get_stage_data[0].get_deal_activity.find(
        (x) => x.id === activity_id
      );
      var tabIndexKey = Object.keys(this.activity_types).find(x => this.activity_types[x].type === recordData.activity_type); // get key of tab
      this.updateFormActivity.tabIndex = this.activity_types[tabIndexKey].tabIndex
      this.updateFormActivity.events[this.activity_types[tabIndexKey].type] = recordData.activity_subject
      this.updateFormActivity.userId = recordData.user_id;
      this.updateFormActivity.notes = recordData.notes;
      this.updateFormActivity.scheduler = recordData.schedule_status;
      this.updateFormActivity.activityId = recordData.id;

      var start =recordData.timeline_time_in.split(" ");
      this.updateFormActivity.events.start_date = new Date(start[0]);
      this.updateFormActivity.events.start_time = start[1];

      var end =recordData.timeline_time_out.split(" ");
      this.updateFormActivity.events.end_date = new Date(end[0]);
      this.updateFormActivity.events.end_time = end[1];
      
      this.updateFormActivityEnd.disabledDates.to = this.updateFormActivity.events.start_date;
      this.updateFormActivityStart.disabledDates.from = this.updateFormActivity.events.end_date;

      if (recordData.status == 1) {
        this.updateFormActivity.status = true;
      }
      this.$refs["activity-modal"].show();
    },

     onUpdateDealActivity(event) {

      this.updateProjectButtonClicked = true;
      if (this.$v.updateFormActivity.$invalid == true) {
        return false;
      } else {
        event.preventDefault();
         var activityvalue = $(".update-activity-event-value")
          .find("input[class='form-control']")
          .val();
        var activitytype = $(".update-activity-event-value")
          .find("input[class='form-control']")
          .attr("placeholder");

        // this.updateFormActivity.events.start_date = this.updateFormActivity.events.start_date ? window.luxon.DateTime.fromISO(this.updateFormActivity.events.start_date.toISOString()).toISODate() : null;
        this.updateFormActivity.events.start_date = this.$functions.setDateFormat(this.updateFormActivity.events.start_date);
        this.updateFormActivity.events.end_date = this.$functions.setDateFormat(this.updateFormActivity.events.end_date);
        if (activityvalue) {
          this.updateFormActivity.events.value = activityvalue;
          this.updateFormActivity.events.type = activitytype;
        } else {
          this.updateFormActivity.events.value = activitytype;
          this.updateFormActivity.events.type = activitytype;
        }
        axios
          .post("/data/update-deal-activity/" + this.deal.deal.get_stage_data[0].id, {
            data: this.updateFormActivity,
          })
          .then((response) => {
            if (response.data.status == "success") {
              this.$refs["activity-modal"].hide();
              this.updateProjectButtonClicked = false;
              // this.onResetUpdateDealActivity();
              var dealsParam = { loadMore: false };
              this.$emit('refreshActvity');
              this.showNotification({
                type: response.data.status,
                message: response.data.message,
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

    displayErrorMessageUpdate(value) {
      if (!value && this.updateProjectButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
   
  },
};
</script>