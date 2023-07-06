<template>
<div class="bg-white rounded shadow py-8 mt-8 user-profile-fields">
  <div class="flex flex-col items-center">
    <img :src="avatar" :alt="'Avatar of ' + user.name" class="w-32 h-32 rounded-full">
    <div class="text-gray-800 text-2xl font-semibold py-4">
      {{user.name}}
    </div>
    <div class="">
      <avatar-upload @image-loaded="updateImage"></avatar-upload>
    </div>
  </div>
  <form class="px-8 pt-6" @submit.prevent="updateProfile">
    <div class="mb-4">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="name">
        {{ 'Name' | localize }}
      </label>
      <input v-model='user.name' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="name" type="text" placeholder="Nehal Hasnayeen">
    </div>
    <div class="mb-4">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="title">
        {{ 'Job Title' | localize }}
      </label>
      <input v-model='user.designation' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="title" type="text" :placeholder="$options.filters.localize('Developer')">
    </div>
    <div class="mb-4">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="title">
        {{ 'Short Bio' | localize }}
      </label>
      <textarea v-model="bio" class="shadow appearance-none resize-none border rounded w-full py-2 px-3 text-gray-700" id="bio" type="text" rows="3" :placeholder="$options.filters.localize('About Yourself')"></textarea>
      <div class="error" v-if="displayErrorMessage($v.bio.maxLength)">{{ validationErrorMessages.maxlength.description }}</div>
    </div>
    <!--  <div class="mb-6 colour-picker-group">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="colour-picker">
        {{ 'Pick a Colour' | localize }}
      </label>
      
      <div class="colour-picker-grp-item">
       <input v-model='user.colour' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="title" type="text" :placeholder="$options.filters.localize('Developer')">
       <span class="input-group-addon color-picker-container">
        <span class="current-color" :style="'background-color: ' + user.colour"></span>
      </span>
    </div>
    </div> -->
    <div class="mb-4">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="title">
        {{ 'Location' | localize }}
      </label>
      <input v-model='user.location' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="title" type="text" placeholder="State, Country">
    </div>
    <div class="mb-4">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="title">
        {{ 'Yearly salary' | localize }}
      </label>
      <input v-model='user.yearly_salary' step="any" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="title" type="number" placeholder="Yearly salary">
    </div>
    <!-- <div class="mb-6">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="timezone">
        {{ 'Time Zone' | localize }}
      </label>
      <div class="relative">
        <select v-model='user.timezone' class="shadow appearance-none border rounded w-full px-3 py-2 text-gray-700 bg-gray-200" id="grid-state">
          <template v-for="timezone in timezones">
            <option :value="timezone">{{ timezone}}</option>
          </template>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
    </div> -->
    <!-- <div class="mb-6">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="weekstart">
        {{ 'First Day of the Week' | localize }}
      </label>
      <div class="relative">
        <select v-model='user.week_start' class="shadow appearance-none border rounded w-full px-3 py-2 text-gray-700 bg-gray-200" id="weekstart">
          <option>{{ 'Saturday' | localize }}</option>
          <option>{{ 'Sunday' | localize }}</option>
          <option>{{ 'Monday' | localize }}</option>
          <option>{{ 'Tuesday' | localize }}</option>
          <option>{{ 'Wednesday' | localize }}</option>
          <option>{{ 'Thursday' | localize }}</option>
          <option>{{ 'Friday' | localize }}</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
    </div> -->
    <!-- <div class="mb-6">
      <label class="block text-gray-600 text-sm font-bold mb-2" for="weekstart">
        {{ 'Preferred Language' | localize }}
      </label>
      <div class="relative">
        <select v-model='user.lang' class="shadow appearance-none border rounded w-full px-3 py-2 text-gray-700 bg-gray-200" id="weekstart">
          <template v-for="(locale, key) in locales">
            <option :value="key">{{ locale }}</option>
          </template>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
    </div> -->
    <div class="mt-8">
      <button type="submit" class="main-button">
        {{ 'Update' | localize }}
      </button>
      <div></div>
    </div>
  </form>
</div>
</template>

<script>
import { mapActions } from 'vuex'
import { maxLength} from 'vuelidate/lib/validators'
import avatarUpload from './../partials/avatarUpload'
import ColourPicker from 'vue-colour-picker'


export default {
  name: 'Own',
  components: {avatarUpload,ColourPicker},
  props: ['user'],

  data: () => ({
    avatar: null,
    bio: '',
    submitButtonClicked : false,
    validationErrorMessages: Constants.validationErrorMessages    
  }),

   validations: {
    bio: {
      maxLength: maxLength(250)
    } 
  },

  created (){
      this.setSomeProfileFields()
  },

  methods: {
    ...mapActions([
      'showNotification'
    ]),
    updateImage (imageUrl) {
      this.avatar = imageUrl
    },
    setSomeProfileFields(){
        this.bio = this.user.bio
    },
    displayErrorMessage(value){
        if (!value && this.submitButtonClicked) {
            return true
        }else{
            return false
        }
    },
    updateProfile () {
      this.$v.$touch()
      this.submitButtonClicked = true
      if (!this.$v.$invalid) {
        axios.put('data/users/' + this.user.username + '/profile', {
          name: this.user.name,
          bio: this.bio,
          designation: this.user.designation,
          location: this.user.location,
          yearly_salary: this.user.yearly_salary,
          timezone: this.user.timezone,
          week_start: this.user.week_start,
          lang: this.user.lang,
          colour: this.user.colour
        })
          .then((response) => {
            if (response.data.status === 'success') {             
                this.showNotification({type: response.data.status, message: response.data.message})
            }
          })
          .catch((error) => {
            this.showNotification({type: error.response.data.status, message: error.response.data.message})
          })
      }
    }
  },
  mounted () {
    this.avatar = this.generateUrl(this.user.avatar)
    
  }
}
</script>


