<template>
<div>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" v-model="token">
    <input type="file" name="avatar" id="avatar" accept="image/*" @change="selectFile" class="hidden">
    <button onclick="document.getElementById('avatar').click(); return false;" 
      class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold p-4 py-2 rounded shadow hover:shadow-lg">
      {{ 'Change Your Avatar' | localize }}
    </button>
  </form>
</div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data: () => ({
    user: user,
    token: Laravel.csrfToken,
  }),

  methods: {
    ...mapActions([
      'showNotification',
      'getAvailableUsers'
    ]),
    selectFile (e) {
      if (!e.target.files.length) return
      let file = e.target.files[0]
      let reader = new FileReader()
      reader.readAsDataURL(file)

      reader.onload = e => {
        this.$emit('image-loaded', e.target.result)
      }

      this.uploadImage(file)
    },
    uploadImage (file) {
      let data = new FormData()
      data.append('avatar', file)
      data.append('token', this.token)
      axios.post('data/users/' + this.user.username + '/avatar', data)
        .then((response) => {          
          this.showNotification({type: response.data.status, message: response.data.message})
          this.user.avatar = response.data.avatar
          EventBus.$emit('avatarUpdated')
        })
        .catch((error) => {
          this.showNotification({type: error.response.data.status, message: error.response.data.message})
        })
    }
  }
}
</script>
