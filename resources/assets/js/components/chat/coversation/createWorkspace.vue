<template>
  <div class="chat-workspace">
    <div class="add-workspace-wrap">
      <form @submit.prevent="createNewWorkspace">
        <div class="a-p-image">
          <img
            src="/storage/images/create-workspace.svg"
            alt="create-workspace"
          />
        </div>
        <div class="a-p-text">
          <h2>Create a new workspace</h2>
          <input
            type="text"
            placeholder="Enter workspace name"
            v-model="workspace.name"
          />
          <div
            class="error"
            v-if="displayErrorMessage($v.workspace.name.required)"
          >
            {{ validationErrorMessages.required }}
          </div>
          <multi-select
            :options="availableUsers"
            :selected-options="workspace.selectedUsers"
            placeholder="Add Users"
            @select="onSelect"
            class="a-multi-users"
          >
          </multi-select>
          <div
            class="error"
            v-if="displayErrorMessage($v.workspace.selectedUsers.required)"
          >
            {{ validationErrorMessages.required }}
          </div>
          <div
            class="error"
            v-if="
              displayErrorMessage($v.workspace.selectedUsers.mustHaveLength)
            "
          >
            {{ validationErrorMessages.mustHaveLength }}
          </div>
        </div>
        <button type="submit" class="btn-custom-main">
          Create a new workspace
        </button>
      </form>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import { MultiSelect } from "vue-search-select";
const len = helpers.regex("alpha", /^[a-zA-Z]*$/);
const mustHaveLength = (value) => value.length > 1 || value.length == 0;
import {
  required,
  requiredIf,
  between,
  numeric,
  helpers,
} from "vuelidate/lib/validators";
import {
  faCommentDots,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";

export default {
  name: "chat.coversation.createWorkspace",
  components: {
    MultiSelect,
  },
  data: () => ({
    faCommentDots,
    faVideo,
    faCalendarAlt,
    userId: null,
    createWorkspaceButtonClicked: false,
    validationErrorMessages: Constants.validationErrorMessages,
    workspace: {
      name: null,
      selectedUsers: [],
    },
  }),
  validations: {
    workspace: {
      name: {
        required,
      },
      selectedUsers: {
        required,
        mustHaveLength,
      },
    },
  },
  created() {},
  computed: {
    ...mapState({
      availableUsers: (state) => state.availableUsers,
    }),
  },
  methods: {
    ...mapActions(["showNotification", "toggleLoading", "createWorkspace"]),

    onSelect(items, lastSelectItem) {
      this.workspace.selectedUsers = items;
    },
    createNewWorkspace() {
      this.$v.$touch();
      this.createWorkspaceButtonClicked = true;
      if (!this.$v.$invalid) {
        this.createWorkspace(this.workspace)
      }
    },
    displayErrorMessage(value) {
      if (!value && this.createWorkspaceButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>
