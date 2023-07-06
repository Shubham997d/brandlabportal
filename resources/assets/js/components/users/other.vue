<template>
<div>  
  <div class="bg-white rounded shadow max-w-2xl mx-auto mt-16" v-if="userDetailLoaded == true || Object.keys(this.user).length != 0">
    <div class="flex flex-col items-center">
      <div class="w-full h-48 opacity-25 bg-cover rounded-t"
        :style="{backgroundImage: 'url(' + generateUrl(user.avatar) + ')'}">
      </div>
      <div class="absolute h-48">
        <div class="w-32 h-32 bg-cover bg-center mt-8 rounded-full"
          :style="{backgroundImage: 'url(' + generateUrl(user.avatar) + ')'}">
        </div>
      </div>
      <div class="flex flex-col items-center w-full py-8">
        <div class="text-gray-700 text-4xl font-semibold px-8 leading-none">
          {{user.name}}
        </div>
        <div class="text-gray-600 text-lg py-1 px-8">
          {{user.designation}}
        </div>
        <!-- <div class="text-gray-600 text-sm px-8">
          {{ user.location }} <span class="font-bold text-xl">{{user.location ? '•' : ''}}</span> {{userLocalTime}}
        </div> -->
      </div>  
      <div class="p-8 pt-0 min-w-full">
        <div class="text-gray-800 bg-blue-100 px-8 pb-20 leading-normal rounded-lg">
          <div class="text-6xl text-gray-700 pt-2 -mb-8">❝</div>
          <div v-if="user.bio" class="px-8">
            {{user.bio}}
          </div>
          <div v-else class="text-center">
            User has not set their bio yet. ¯\_(ツ)_/¯
          </div>
          <div class="text-6xl text-gray-700 float-right">❞</div>
        </div>
      </div>
      <div class="text-gray-800 self-start p-8 bg-gray-100 w-full rounded-b n-project-members">
        <div>
          <div class="flex items-center text-gray-700">
            <div>
              {{ 'Member of' | localize }}
            </div>
            <div class="px-2 p-wrap">
              <div class="bg-indigo-200 text-indigo-700 font-bold flex justify-center items-center h-8 mr-2 rounded-full">{{ user.projects.length  }}</div>
              <span>{{user.projects.length > 1 ? 'projects' : 'project'}}</span>
            </div>            
            
          </div>
          <ul class="pt-4 -mx-2 user-projects">
            <li v-for="project in user.projects" class="px-2 py-2" :key="project.id">
              <router-link :to="'/project/' + project.id+'/tasks'" class="no-underline">
                {{project.name}}
              </router-link>
            </li>
           
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="h-12"></div>
</div>
</template>

<script>
import { DateTime } from 'luxon'
import { mapState, mapActions } from 'vuex'

export default {
  data () {
      return {
        user: {}
      }
  },
  name: 'other.user',
  
  computed: {
    userLocalTime () {
      return DateTime.local().setZone(this.user.timezone).toLocaleString(DateTime.TIME_SIMPLE) + ' (' + DateTime.local().setZone(this.user.timezone). offsetNameShort +')'
    },   
    async userDetailLoaded(){
        this.user = await this.getUserDetail()  
        return Object.keys(this.user).length != 0 ? true : false;
    }
  },
  created (){
        // this.user=  
  },
  watch: {
      
    },
  methods : {
    ...mapActions([    
      'toggleLoading',    
    ]),
    async getUserDetail(){
            try {
              this.toggleLoading(true)
                let { data } = await axios({
                  url: '/data/users/'+this.$route.params.username,
                  })                   
                this.toggleLoading(false)
                return data.user
              return []
            } catch (error) {
              console.log(error)
              this.toggleLoading(false)
            }
    }
  }
}
</script>
