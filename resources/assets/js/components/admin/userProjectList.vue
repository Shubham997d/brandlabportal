<template>
<div>
  <div class="container bg-gray-100 rounded shadow-lg z-40 user-project-all-detail" >
    <div class="m-auto flex-col flex">
        <label class="block uppercase tracking-wide text-gray-600 text-xs font-bold text-center text-lg p-4 rounded" for="user">
          {{'User Project List' | localize }}
        </label>
    <div class="projects-details">
      <ul v-for="project in projects" :key="project.id" class="list-reset">
        <li class="p-4 py-6 hover:bg-blue-100 bg-white flex flex-row items-center justify-content-center">
          <div class="flex flex-row items-center">
            <router-link :to="'/project/'+project.project_id+'/detail'" >
                <span class="text-xl">{{ project.project_name }}</span>
            </router-link>
          </div>
          <div class="flex flex-row items-center">
            <span class="text-xl text-duration">{{ $functions.timeFormat(project.duration) }}</span>
          </div>
        </li>
      </ul>
    </div>

      <h1 v-if="projects.length === 0" class="not-started-yet">Not Started Yet</h1>

      <div class="flex flex-row-reverse p-4 rounded-b">
        <button @click="closeModal" class="text-red-400 hover:font-bold main-button">{{'Close' | localize }}</button>
      </div>
    </div>
  </div>
  <div @click="closeModal" class="h-screen w-screen fixed inset-0 bg-gray-900 opacity-25 z-30"></div>
</div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  props: {  
    projects: {
      required: true
    }
  },

  data: () => ({
  }),
  created (){
  },
  methods: {
    ...mapActions([
      'closeComponent',
    ]),
    closeModal () {
      this.closeComponent()
    }, 
  },
  mounted () {  
  }
}
</script>
