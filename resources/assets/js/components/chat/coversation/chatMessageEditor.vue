<template>
  <div class="chat-message-editor">
    <chat-typing />
    <button
      type="button"
      class="send-message"
      @click="sendMessage()"
      ref="sendMessage"
    >
      <font-awesome-icon
        :icon="faPaperPlane"
        class="cursor-pointer text-sm"
      ></font-awesome-icon>
    </button>
    <chat-users-toggle
      v-if="usersListToggle && quill != null"
      :users="filteredUsersList"
      @user-selected="userSelected"
      :left="getAbsolutePositonForUsersList"
    />

    <VueEditor
      v-model="messageBody"
      v-if="editorOptions.placeholder"
      ref="chatEditor"
      :editorOptions="editorOptions"
      @keydown.native="onKeydown($event)"
      @text-change="textChange"
      @selection-change="selectionChange"
      @focus="toggleFocus($event)"
      @blur="toggleFocus($event, false)"
    />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { VueEditor, Quill } from "vue2-editor";
import Emoji from "quill-emoji";
import "quill-emoji/dist/quill-emoji.css";
import {
  faPaperPlane,
  faVideo,
  faCalendarAlt,
} from "@fortawesome/free-solid-svg-icons";
import ChatUsersToggle from "./chatUsersToggle.vue";
import ChatTyping from "./chatTyping.vue";
Quill.register(
  {
    "formats/emoji": Emoji.EmojiBlot,
    "modules/short_name_emoji": Emoji.ShortNameEmoji,
    "modules/toolbar_emoji": Emoji.ToolbarEmoji,
    "modules/textarea_emoji": Emoji.TextAreaEmoji,
  },
  true
);
export default {
  components: {
    VueEditor,
    ChatUsersToggle,
    ChatTyping,
  },
  data: () => ({
    messageBody: "",
    toggleToolBar: false,
    faPaperPlane,
    messageEditorActive: false,
    usersListToggle: false,
    customSrollBarSettings:
      Constants.miscellaneous.customScrollBar.withoutXScroll.settings,
    indexOfTheAtTheRate: null,
    usernameText: null,
    indexOfTheSpaceAfterAtTheRate: null,
    authUser: user,
    isMounted: false,
  }),
  created() {},
  computed: {
    ...mapState({
      directUsers: (state) => state.chat.directUsers,
      activeBoardType: (state) => state.chat.miscellaneous.activeBoardType,
      availableBoardTypes: (state) =>
        state.chat.miscellaneous.availableBoardTypes,
    }),
    getAbsolutePositonForUsersList() {
      return this.moveUsersList();
    },
    filteredUsersList() {
      if (
        this.usernameText &&
        this.usernameText.length >= 1 &&
        this.usernameText.charAt(0) != " " // check if first character is not a blank space
      ) {
        return this.filterArray(this.directUsers.list, this.usernameText);
      } else {
        return this.directUsers.list;
      }
    },
    editorOptions() {
      let options = Constants.chat.editorOptions;
      if (typeof this.getPlaceHolderText() !== "undefined") {
        // set placeholder
        options.placeholder = "Message " + this.getPlaceHolderText(); // change object here
        if (this.quill != null) {
          this.$refs.chatEditor.quill.root.setAttribute(
            "data-placeholder",
            "Message " + this.getPlaceHolderText()
          );
        }
      }
      return options;
    },
    quill() {
      if (
        this.isMounted == true &&
        typeof this.$refs.chatEditor !== "undefined" &&
        this.$refs.chatEditor != null
      ) {
        return this.$refs.chatEditor.quill;
      } else {
        return null;
      }
    },
  },
  methods: {
    ...mapActions(["addActiveDirectUserMessage"]),

    openToolBar() {
      this.toggleToolBar = !this.toggleToolBar;
      if (this.toggleToolBar === true) {
        let containerData = [
          ["bold", "italic", "underline", "strike"],
          [{ list: "ordered" }, { list: "bullet" }],
          [{ direction: "rtl" }],
          ["link"],
          ["emoji"],
        ];
        this.editorOptions.modules.toolbar.container = [];
        this.editorOptions.modules.toolbar.container.push(...containerData);
      }
    },
    sendMessage() {
      if (this.activeBoardType == this.availableBoardTypes[0]) {
      }
      if (this.activeBoardType == this.availableBoardTypes[1]) {
        this.sendNewDirectMessage();
      }
    },

    // sendMessage(e) {
    //   if (!this.selectedUser.id) {
    //     return false;
    //   }
    //   if (e.shiftKey) {
    //     this.message = this.message + "\n";
    //   } else if (this.message.length > 0) {
    //     var msg = this.message;
    //     this.message = "";
    //     if (this.editing.hasOwnProperty("messageIndex")) {
    //       this.sendEditedMessage(msg);
    //     } else {
    //       this.sendNewDirectMessage(msg);
    //     }
    //   }
    // },
    // sendEditedMessage(msg) {
    //   axios
    //     .put("/direct-messages/" + this.editing.message.id, {
    //       body: msg,
    //       receiver_id: this.selectedUser.id,
    //     })
    //     .then((response) => {
    //       if (response.data.status === "success") {
    //         response.data.message.user = user;
    //         this.messages.splice(
    //           this.editing.messageIndex,
    //           1,
    //           response.data.message
    //         );
    //         this.editing = {};
    //       }
    //     })
    //     .catch((error) => {
    //       this.showNotification({
    //         type: error.response.data.status,
    //         message: error.response.data.message,
    //       });
    //     });
    // },
    sendNewDirectMessage() {
      if (this.checkWhetherMessageIsEmpty() === false) {
        axios
          .post("/data/direct-messages", {
            body: this.$functions.sanitizeString(this.messageBody),
            receiver_id: this.directUsers.active.user.id,
          })
          .then((response) => {
            if (response.data.status === "success") {
              this.addActiveDirectUserMessage({
                message: response.data.message,
                messageFor: "current",
              });
              this.$functions.scrollToBottomOfElement("message-box");
              this.quill.deleteText(0, this.quill.getLength()); // keep editor active after sending message and delete everything  in it
            }
          })
          .catch((error) => {
            console.log("error", error);
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
      }
    },
    onKeydown(event) {
      // send message on enter key as well
      if (!event.shiftKey && event.which == 13) {
        this.quill.deleteText(this.quill.getSelection(true).index - 1, 1); // delete enter key delta value
        this.$refs.sendMessage.click();
      }
      if (event.key == "@" && event.which == 50) {
        this.indexOfTheAtTheRate = this.quill.getSelection(true).index;
        this.toggleUsersList();
      }
      if (this.indexOfTheAtTheRate != null) {
        this.checkWhetherToHideUsersList(event);
      }
      this.listenToTyping();
    },
    toggleUsersList() {
      this.usersListToggle = true;
      this.usernameText = null;
    },
    scrollHandle(evt) {},
    textChange(delta, oldDelta, source) {
      if (this.indexOfTheAtTheRate != null) {
        // set only values that are entered after the @
        this.getUserText(delta);
        // console.log("delta", delta);
        // console.log("oldDelta", oldDelta);
        // console.log("source", source);
      }
    },
    selectionChange(range, oldRange, source) {
      // if (this.indexOfTheAtTheRate != null) {
      //   if (this.usernameText == null) { // hide users list if selection is moved without
      //     this.usersListToggle = false;
      //   }
      //   console.log("this.usernameText", this.usernameText);
      //   console.log("range", range);
      //   console.log("oldRange", oldRange);
      //   console.log("source", source);
      // }
    },
    userSelected(data) {
      this.addSelectedUsername(data);

      // set focus of editor at the end of username
      this.quill.setSelection(
        this.indexOfTheAtTheRate + data.username.length + 2
      );
      this.hideUsersList();
    },
    toggleFocus(event, focused = true) {
      if (!focused) {
        setTimeout(() => {
          this.usersListToggle = false;
        }, 200); // wait for adding username in editor
      }
    },
    moveUsersList() {
      if (this.quill) {       
        let left = this.quill
          ? this.quill.getBounds(this.indexOfTheAtTheRate).left
          : null;
        return left ? Math.round(left) + "px" : "";
      }
      return "";
    },
    initializeANewEmptyString() {
      this.quill.insertText(
        this.quill.getSelection(true).index,
        " ",
        { color: "#333333", bold: false },
        true
      );
    },
    addSelectedUsername(data) {
      var delta = {
        ops: [
          { retain: this.indexOfTheAtTheRate + 1 },
          {
            insert: data.username,
            attributes: { bold: true, color: "#0d6efd" },
          },
          { insert: " ", attributes: { bold: false, color: "#333333" } },
        ],
      };
      if (this.usernameText != null && this.usernameText.length > 0) {
        delta.ops.splice(1, 0, { delete: this.usernameText.length });
      }

      this.quill.updateContents(delta);
    },
    filterArray(array, textToSearch) {
      if (
        this.$functions.hasWhiteSpace(
          this.$functions.getLastCharacter(textToSearch)
        )
      ) {
        textToSearch = textToSearch.slice(0, -1); // removing /n from coming string
      }
      return array.filter((str) => {
        return str.username.toLowerCase().indexOf(textToSearch) !== -1;
      });
    },
    getUserText(delta) {
      if (delta.ops[1]) {
        if (delta.ops[1].insert) {
          this.usernameText = this.usernameText
            ? this.usernameText + delta.ops[1].insert
            : delta.ops[1].insert;
        }
        if (delta.ops[1].delete) {
          this.usernameText = this.usernameText
            ? this.usernameText.slice(0, -delta.ops[1].delete)
            : this.usernameText;
        }
      }

      if (this.usernameText && this.usernameText.charAt(0) == "@") {
        this.usernameText = this.usernameText.substring(1);
      }
      if (this.usernameText && this.usernameText.length === 0) {
        this.usernameText = null;
      }
    },
    checkWhetherToHideUsersList(event) {
      setTimeout(() => {
        // waiting for @ the character to showing in quil object
        if (this.indexOfTheAtTheRate != null) {
          let atTheRate = this.quill.getText(
            this.indexOfTheAtTheRate,
            this.usernameText ? this.usernameText.length + 1 : 2
          );

          if (atTheRate.charAt(0) == "@") {
            this.indexOfTheSpaceAfterAtTheRate =
              this.quill.getSelection(true).index;
          }

          if (
            atTheRate.charAt(0) != "@" ||
            (this.$functions.hasWhiteSpace(atTheRate.charAt(1)) === true &&
              atTheRate.length > 2)
          ) {
            this.hideUsersList();
          }
        }
      }, 200);
    },

    hideUsersList() {
      this.indexOfTheSpaceAfterAtTheRate = null;
      this.usersListToggle = false;
      this.usernameText = null;
      this.indexOfTheAtTheRate = null;
    },

    listenToTyping() {
      setTimeout(() => {
        if (this.activeBoardType == this.availableBoardTypes[0]) {
        }
        if (this.activeBoardType == this.availableBoardTypes[1]) {
          Echo.private("User." + this.directUsers.active.user.id).whisper(
            "typing",
            {
              id: this.authUser.id,
              username: this.authUser.username,
              message: this.authUser.username + " is typing",
            }
          );
        }
      }, 300);
    },

    getPlaceHolderText() {
      if (this.activeBoardType == this.availableBoardTypes[0]) {
        return "To Workspace";
      }
      if (this.activeBoardType == this.availableBoardTypes[1]) {
        return this.directUsers.active.user.username;
      }
    },

    checkWhetherMessageIsEmpty() {
      let message =
        this.quill !== null
          ? this.quill.getText(0, this.quill.getLength())
          : "";
      return message.trim().length > 0 ? false : true;
    },
  },
  mounted() {
    this.isMounted = true;
  },
};
</script>
