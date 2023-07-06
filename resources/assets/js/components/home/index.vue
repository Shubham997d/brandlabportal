<template></template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  name: "app",
  components: {},
  data: () => ({
    user,
    pageNum: 1,
  }),
  // APIs for most of the main components are here also reintializing Store data as well for some routes
  watch: {
    $route(to, from) {
      this.commonFunctionsForDifferentRoutes();
      if (this.$route.name === "projects") {
        this.getAllProjects();
      }
      if (this.$route.name === "admin.reports") {
        this.getAdminDealsReport(to, from);
      }
      if (from.name === "admin.reports" && to.name != "admin.reports") {
        this.reIntializeAdminReports(); // Reintialize data so that projects and deal reports data of different params does not get mixed up
      }
      if (from.name === "projects" && to.name != "projects") {
        this.reIntializeProjects();
      }
      if (this.$route.name === "deals") {
        this.getDealsListingData();
      }
      if (this.$route.name === "deal-details") {
        this.getAllDealsData();
      }
      if (this.$route.name === "admin.logs") {
        this.getAllLogsData();
      }
      if (this.$route.fullPath.indexOf(this.availableBoardTypes[0]) !== -1) {
        this.getActiveWorkspaceData();
      }
      if (this.$route.fullPath.indexOf(this.availableBoardTypes[1]) !== -1) {
        this.getActiveChatUserData();
      }
      if (this.$route.fullPath.indexOf("chat") !== -1) {
        this.getChatCommonData();
      }
      if (
        to.fullPath.indexOf("chat/coversation") !== -1 &&
        from.fullPath.indexOf("chat/coversation") === -1
      ) {
        this.getCommonDataForConversation();
      }
    },
    loadedCommonDataForConversation(newValue) {
      if (newValue) {
        if (this.$route.fullPath.indexOf("chat/coversation")) {
          this.getLatestMessagesForAll();
        }
      }
    },
  },

  computed: {
    ...mapState({
      activeTab: (state) => state.admin.miscellaneous.activeTab, // Admin Dashboard Deals Listing
      isUserAdmin: (state) => state.isUserAdmin,
      dealsFilters: (state) => state.deal.filters.deals, // For Deals Listing page
      availableUsers: (state) => state.availableUsers,
      availableBoardTypes: (state) =>
        state.chat.miscellaneous.availableBoardTypes, // For Chat
      directUsers: (state) => state.chat.directUsers,
      loadedCommonDataForConversation: (state) => state.miscellaneous.loaded.commonData,
    }),
  },
  created() {
    // check if user admin
    this.setIsUserAdmin();
    this.getCommonData(); // use for getting users

    // this.getAvailableUsers()
    // this.listenToNotifications()
  },

  methods: {
    ...mapActions([
      "getProjects",
      "setIsUserAdmin",
      "getReportsListingDataForAdmin",
      "getDeals",
      "getSearchDeals",
      "getDealUsers",
      "updateMiscellaneousActiveTab",
      "reIntializeAdminReports",
      "reIntializeProjects",
      "updateMiscellaneousReportsLoading",
      "getAvailableUsers",
      "getDeal",
      "getDealsFiltersData",
      "getCommonData",
      "getActivityLogs",
      "setChatDirectUsers",
      "setChatDirectActiveUser",
      "activeBoardType",
      "getActiveDirectUserMessages",
      "getLatestMessagesForAll",
    ]),
    getCurrentNav() {
      if (this.$route.meta.root(this.$route) == "project") {
        EventBus.$emit("current-nav", "project-nav");
      } else if (this.$route.meta.root(this.$route) == "admin") {
        if (this.isUserAdmin.status === true) {
          // check if user is admin otherwise set main nav
          EventBus.$emit("current-nav", "admin-nav");
        } else {
          EventBus.$emit("current-nav", "main-nav");
        }
      } else {
        EventBus.$emit("current-nav", "main-nav");
      }
    },
    getCurrentStatus(forProjects = true) {
      if (forProjects === true) {
        var currentParam = this.$route.params.status;
        var status = Constants.project.slug[currentParam];
      } else {
        var currentParam = this.$route.params.status;
        var status = Constants.dealStatus[this.$route.params.status];
      }
      return status;
    },
    getAllProjects() {
      // Commit Default Mutations
      this.$store.commit("setProjectPageNum", 1);
      this.$store.commit("setProjectsDataLoaded", false);
      this.getProjects({
        status: this.getCurrentStatus(),
        category: this.$route.params.category,
        loadMore: false,
      });
    },
    getAdminDealsReport(to, from) {
      this.updateMiscellaneousReportsLoading(true);
      this.getReportsListingDataForAdmin({
        type: this.$route.params.reportType,
        request: null,
      });
    },
    getDealsListingData() {
      this.getDeals({
        ...this.dealsFilters,
        goToPage: this.dealsFilters.dealDefaultPage,
        loadMore: false,
        status: this.getCurrentStatus(false),
      });
      this.getSearchDeals({
        status: Constants.dealStatus[this.$route.params.status],
      });
      this.getDealsFiltersData();
    },
    getAllDealsData() {
      this.getDeal(this.$route.params.id);
      this.getDealsFiltersData();
    },
    commonFunctionsForDifferentRoutes() {
      this.getCurrentNav(); // use for nav
      this.activeBoardType(null);
      this.chatPageHmlClass(true);
    },
    getBackGroundColor() {
      var colour = this.$route.meta.colour(this.$route);
    },
    listenToNotifications() {
      // this.listenToDealColorChanges();
    },
    getAllLogsData() {
      this.getActivityLogs(Constants.logs.activity.filters);
    },
    getOtherData() {},
    getActiveChatUserData() {
      if (this.$route.params.name) {
        this.setChatDirectActiveUser({ user: this.$route.params.name });
        this.activeBoardType(this.availableBoardTypes[1]);
        if (
          typeof this.directUsers.active !== "undefined" &&
          typeof this.directUsers.active.user !== "undefined" &&
          typeof this.directUsers.active.user.id !== "undefined"
        ) {
          this.getActiveDirectUserMessages({
            loadingType: Constants.chat.boardTypes[1],
            for: "first",
            receiver_id: this.directUsers.active.user.id,
            loadMore: false,
            page: 1,
          });
        }
      }
    },
    getActiveWorkspaceData() {
      if (this.$route.params.name) {
        this.activeBoardType(this.availableBoardTypes[0]);
      }
    },
    getChatCommonData() {
      this.chatPageHmlClass(false);
    },
    chatPageHmlClass(remove = true) {
      // stop default scroll on main chat
      if (remove) {
        $("html").removeClass("main-chat-page");
      } else {
        if ($("html").hasClass() === false) {
          $("html").addClass("main-chat-page");
        }
      }
    },
    getCommonDataForConversation() {
      
    },
  },
};
</script>
