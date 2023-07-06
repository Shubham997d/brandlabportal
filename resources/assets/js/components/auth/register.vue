<template>
<div class="md:bg-white container md:mx-4 md:mx-auto md:shadow-lg rounded md:mt-32 flex md:flex-row flex-col md:max-w-2xl lg:max-w-4xl justify-between user-registration">
  <div class="md:w-1/2 text-center px-8 py-4 md:block">
    <div class="text-indigo-500 font-bold text-4xl md:pt-8">BrandLab360</div>
    <p class="text-indigo-500 text-xl">{{ 'Register' | localize }}</p>
    <img src="/image/register.svg" alt="work desk" class="pt-16 hidden md:block">
  </div>
  <div class=""></div>
  <div class="w-full md:bg-indigo-100 md:w-1/2 p-8">
    <form role="form" method="POST" :action="url" enctype="multipart/form-data">
      <input type="hidden" name="_token" v-model="token">
      <div class="">
        <div class="py-4">
          <input id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="text" name="name" placeholder="Full Name * " v-model="userInputs.name">
          <span class="text-red-700 block pt-2" v-if="errors.name"><p v-for="(error) in errors.name" :key="error">{{ error }}</p></span>
        </div>
        <div class="py-4">
          <input id="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="text" name="username" placeholder="Username * " v-model="userInputs.username">
          <span class="text-red-700 block pt-2" v-if="errors.username"><p v-for="(error) in errors.username" :key="error">{{ error }}</p></span>
        </div>
        <div class="py-4">
          <input id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="text" name="email" placeholder="E-mail * " v-model="userInputs.email">
          <span class="text-red-700 block pt-2" v-if="errors.email"> <p v-for="(error) in errors.email" :key="error">{{ error }}</p></span>
        </div>
        <div class="py-4">
         <select name='role_id' class="shadow appearance-none border rounded w-full px-3 py-2 text-gray-700 bg-gray-200" id="weekstart" v-model="mainVal.role_id">
          <option disabled value="" selected>Member Type * </option>
          <option v-for="(role) in roles" :key="role.id" :value="role.id">{{ role.name }}</option>         
        </select>
        <span class="text-red-700 block pt-2" v-if="errors.role_id"> <p v-for="(error) in errors.role_id" :key="error">{{ error }}</p></span>
        </div>
          <div class="py-4">
          <input id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" :type="[password ? 'text' : 'password']" name="password" placeholder="Password * " v-model="mainVal.password">
          <div class="password-toggle" @click="password = !password">
               <font-awesome-icon :icon="password ? faEye : faEyeSlash" class="password-toggle" ></font-awesome-icon>
        </div>
          <span class="text-red-700 block pt-2" v-if="errors.password"><p v-for="(error) in errors.password" :key="error">{{ error }}</p></span>
        </div>
         <div class="py-4">
          <input id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800"  :type="[confirmPassword ? 'text' : 'password']" name="password_confirmation" placeholder="Confirm Password * " v-model="mainVal.password_confirmation">
          <div class="password-toggle" @click="confirmPassword = !confirmPassword">
                <font-awesome-icon :icon="confirmPassword ? faEye : faEyeSlash" class="password-toggle" ></font-awesome-icon>
        </div>
          <span class="text-red-700 block pt-2" v-if="errors.password_confirmation">
            <p v-for="(error) in errors.password_confirmation" :key="error">{{ error }}</p>
          </span>
         </div>
         <div class="py-4">
          <input id="job_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="text" name="job_title" placeholder="Job Title * " v-model="userInputs.job_title">
          <span class="text-red-700 block pt-2" v-if="errors.job_title"> <p v-for="(error) in errors.job_title" :key="error">{{ error }}</p></span>
        </div>
         <div class="py-4">
          <input min="0" id="salary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="number" name="salary" placeholder="Yearly salary * " v-model="userInputs.salary">
          <span class="block pt-2 note text-indigo-500">Please note, this will not be shown publicly</span>
          <span class="text-red-700 block pt-2" v-if="errors.salary" ><p v-for="(error) in errors.salary" :key="error">{{ error }}</p> </span>
        </div>
        <div class="py-4">
          <button class="avatar-upload shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="button" @click="avatarUpload">
            <font-awesome-icon :icon="faUpload" class="text-white"></font-awesome-icon>
            Choose your profile picture</button>
            <div class="selected-image">{{ mainVal.imageName }}</div>
          <input id="avatar" style="display :none" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800" type="file" name="avatar">
          <span class="text-red-700 block pt-2" v-if="errors.avatar" ><p v-for="(error) in errors.avatar" :key="error">{{ error }}</p> </span>
        </div>
        <div class="py-4">
          <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white text-xl font-medium py-2 px-4 rounded">{{ 'Register' | localize }}</button>
        </div>
        <a href="login" class="no-underline text-indigo-500 text-sm">{{ 'Go to Login' | localize }}</a>
      </div>
    </form>
  </div>
</div>
</template>

<script>
import { faUpload } from '@fortawesome/free-solid-svg-icons/faUpload'
import {
    faEye,
    faEyeSlash  
  } from '@fortawesome/free-solid-svg-icons'

export default {
   data: function() {                           // <== changed this line
    return {
      token: Laravel.csrfToken,    
      url: url,
      faUpload,
      faEye,
      faEyeSlash,
      mainVal: {    // FormData
          imageName : '',
          name : '',
          username : '',
          email : '',
          password : '',
          password_confirmation : '',
          job_title : '',
          salary : '',
          role_id: '',
      },
      password : false,
      confirmPassword : false
    }
},
  props: {
    roles: {
      required: true,         
    },
    errors: {
      required: false,
    },
    userInputs: {
      required: true,
    }            
  },
  created (){
      this.setFieldsValues()
  },
  methods: {
    display (){
      if(this.userInputs){
        // console.log(this.userInputs)
      }      
    },
    setFieldsValues(){
        this.mainVal.name = (this.userInputs.name == undefined) ? '' : this.userInputs.name;
        this.mainVal.username = (this.userInputs.username == undefined) ? '' : this.userInputs.username;
        this.mainVal.email = (this.userInputs.email == undefined) ? '' : this.userInputs.email;
        this.mainVal.password = (this.userInputs.password == undefined) ? '' : this.userInputs.password;
        this.mainVal.password_confirmation = (this.userInputs.password_confirmation == undefined) ? '' : this.userInputs.password_confirmation;
        this.mainVal.job_title = (this.userInputs.job_title == undefined) ? '' : this.userInputs.job_title;
        this.mainVal.salary = (this.userInputs.salary == undefined) ? '' : this.userInputs.salary;
        this.mainVal.role_id = (this.userInputs.role_id == undefined) ? '' : this.userInputs.role_id;
    },
    avatarUpload(){
      $('#avatar').click();
    },
  },
  mounted (){
      // For Avatar upload
        var app = this;
        $('input[type="file"]').change(function(e) {            
            var fileName = e.target.files[0].name;            
            app.mainVal.imageName = fileName
        });
  }
    
  }
</script>
