export default [

  {   
    path: "*", 
    component: () => import('../../components/partials/404.vue'),
    name: 'not.found',    
    meta: {
      pageTitle: 'Page Not Found',
      root: (route) => {
        return 'not-found'
      },    
      breadcrumb: (route) => {
        return [
          {
            text: 'Page not found',
            to: '/'
          }
        ]
      }
    }
  },
  {   
    path: "/access-denied", 
    component: () => import('../../components/partials/accessDenied.vue'),
    name: 'access.denied',    
    meta: {
      pageTitle: 'Access Denied',
      root: (route) => {
        return 'access-denied'
      },    
      breadcrumb: (route) => {
        return [
          {
            text: 'Access denied',
            to: '/'
          }
        ]
      }
    }
  },
  {
    path: '/profile/:username',
    name: 'profile',
    component: () => import('../../components/users/profile.vue'),
    meta: {
      pageTitle: 'Profile',
      root: (route) => {
        return 'profile'
      },
      breadcrumb: (route) => { 
        return [
        {
          text: 'Home',
          to: '/'
        },
        {
          text: 'Profile',
          active: true,
        },
       ]
      }
    }
  },
  {
    path: '/user/:username',
    name: 'other.user',
    component: () => import('../../components/users/other.vue'),
    meta: {
      pageTitle: 'User',
      root: (route) => {
        return 'user'
      }, 
      breadcrumb: (route) => { 
        return [
          {
            text: 'Home',
            to: '/'
          },
          {
            text: 'User',
            active: true,
          },
        ]
      }
    }
  },
  {
    path: '/logout',
    name: 'logout',
    component: () => import('../../components/admin/logout.vue'),
    meta: {
      pageTitle: 'logout',
      root: (route) => {
        return 'admin'
      }, 
      breadcrumb: (route) => { 
        return 
          [
            {
            text: 'logout',
            active: true,
          },
        ]
      }
    }
  },
  {   
    path: "/notifications", 
    component: () => import('../../components/partials/notificationsList.vue'),
    name: 'notifications',    
    meta: {
      pageTitle: 'Notifications',
      root: (route) => {
        return 'notifications'
      },    
      breadcrumb: (route) => {
        return [
          {
            text: 'Notifications',
            to: '/'
          }
        ]
      }
    }
    },
    /* Redirect chat to coversation*/
    {
      path: '/chat',
      redirect: '/chat/coversation'
    },
    /* Redirect home to projects in progress */
    {   
      path: '/', 
      redirect: '/projects/all-projects/in-progress', 
    },

    
]  


