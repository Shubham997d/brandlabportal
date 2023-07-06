<template>
  <div class="inline ct-profile-card" :class="isRelative">
    <div
      :class="[homePage ? 'w-10 h-10' : 'w-8 h-8']"
      @mouseover="showProfileCard()"
      @mouseleave="hideProfileCard()"
    >
      <img
        :src="avatarUrl"
        class="rounded-full mr-1"
        :class="[
          homePage
            ? 'border-white border-2 rounded-full w-10 h-10 ct-profile-avatar'
            : 'w-8 h-8 ct-profile-avatar',
        ]"
      />
    </div>
    <div
      @mouseover="showProfileCard()"
      @mouseleave="hideProfileCard()"
      v-if="profileCardShown"
      class="absolute w-48 -ml-20 pt-4 -mt-2 z-30 profile-card-active"
    >
      <div
        class="
          flex flex-col
          items-center
          justify-center
          bg-blue-900
          text-white
          rounded-lg
          shadow
          py-6
          px-4
        "
      >
        <img
          :src="avatarUrl"
          class="rounded-full w-24 h-24 ct-profile-hover-avatar"
        />
        <div class="pb-2 pt-3 text-xl font-semibold text-center">
          <h3>{{ user.name }}</h3>
        </div>
        <div>
          <span class="text-blue-400">@</span
          >{{ user.username ? user.username : user.text }}
        </div>
        <div v-if="showStatus == true">
          <span class="text-blue-400"></span
          >{{ user.online ? "online" : "offline" }}
        </div>
        <!--    <div class="py-1">
        {{ user.designation }}
      </div> -->
        <div class="text-sm" v-if="duration">
          {{ $functions.timeFormat(duration, userId) }}
        </div>
      </div>
    </div>
  </div>
</template> 

<script>
export default {
  props: {
    user: {
      required: true,
      type: Object,
    },
    duration: {
      required: false,
    },
    homePage: {
      required: false,
      type: Boolean,
    },
    userId: {
      required: false,
    },
    isRelative: {
      required: false,
      default: "",
    },
    showStatus: {
      required: false,
      type: Boolean,
    },
    cachedImage: {
      default: true,
      type: Boolean,
    },
    showHover: {
      default: true,
      type: Boolean,
    },
  },
  created() {
    // console.log(this.duration,'this.duration')
  },
  data: () => ({
    profileCardShown: false,
  }),
  computed: {
    time: function () {
      return window.luxon.DateTime.local()
        .setZone(this.user.timezone)
        .toLocaleString(window.luxon.DateTime.TIME_SIMPLE);
    },
    avatarUrl() {
      return this.cachedImage === true
        ? this.generateUrl(this.user.avatar)
        : this.generateUrl(this.user.avatar) + "?" + new Date().getTime();
    },
  },
  methods: {
    showProfileCard() {
      this.profileCardShown = this.showHover === true ? true : false;
    },
    hideProfileCard() {
      this.profileCardShown = false;
    },
  },
};
</script>
