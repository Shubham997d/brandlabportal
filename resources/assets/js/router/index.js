import Vue from "vue";
import VueRouter from "vue-router";


// Routes
import deals from "./routes/deals";
import projects from "./routes/projects";
import admin from "./routes/admin";
import chat from "./routes/chat";
import common from "./routes/common";


Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  scrollBehavior(to, from) {
    if (to.name === 'chat.coversation.chatboard' && from.name === 'chat.coversation.chatboard') {
      return {  } // don't scroll to top on chatboard
    }else{
      return { x: 0, y: 0 } // Scroll to top
    } 
    
  },
  routes: [
    ...deals,
    ...projects,
    ...admin,
    ...common,
    ...chat
  ],
});

router.beforeEach((to, from, next) => {  
  if(to.meta.pageTitle != undefined || to.meta.pageTitle != null ){
    document.title = process.env.MIX_APP_NAME+' | '+to.meta.pageTitle    
  }else{
    document.title = process.env.MIX_APP_NAME
  }
  return next();
});


// Error handling

function errorComponent(){
    router.push('/page-not-found')
}

router.onError(errorComponent)

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
// router.afterEach(() => {
//   // Remove initial loading
//   const appLoading = document.getElementById("loading-bg");
//   if (appLoading) {
//     appLoading.style.display = "none";
//   }
// });

export default router;
