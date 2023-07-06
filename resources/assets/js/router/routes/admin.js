export default [    
  {
    path: '/admin/users',
    name: 'users',
    component: () => import('../../components/admin/index.vue'),
    meta: {
      pageTitle: 'Users',
      root: (route) => {
        return 'admin'
      },
      breadcrumb: (route) => { 
        return [
        {
          text: 'Home',
          to: '/'
        },
        {
          text: 'Admin',
          to: '/admin'
        },
        {
          text: 'Users',
          active: true,
        },
      ]
      }
    }
   },

   // Redirect home to admin users page
  { 
     path: '/admin', 
     redirect: '/admin/users' 
  }, 
  

  // Admin Deals Report Routes

 {
    path: '/admin/report/deals/:reportType',
    name: 'admin.reports',
    component: () => import('../../components/admin/reports/index.vue'),
    beforeEnter: (to, from, next) => {
      let paramsAvailable = ['started','won-over-time','progress','duration','lost-by-reasons','activity']
      if(paramsAvailable.includes(to.params.reportType) == true){
            next()
      }else{
            next(error)
      }
    },
    meta: {
      pageTitle: 'Deal Reports',
      root: (route) => {
        return 'admin'
      },   
      breadcrumb: (route) => { 
        if(route.path.includes("/report/deals/started")){
        return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals Started',
              active: true,
            },
          ]
        }else if(route.path.includes("/report/deals/won-over-time")){
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals won over time',
              active: true,
            },
          ]
        }else if(route.path.includes("/report/deals/progress")){
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals progress',
              active: true,
            },
          ]
        }else if(route.path.includes("/report/deals/duration")){
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals duration',
              active: true,
            },
          ]

        }else if(route.path.includes("/report/deals/lost-by-reasons")){
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals lost by reasons',
              active: true,
            },
          ]
        }else if(route.path.includes("/report/deals/activity")){
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals activity',
              active: true,
            },
          ]
        }else{
          return  [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Admin',
              to: '/admin'
            },
            {
              text: 'Deals',
              active: true,
            },
          ]
        }
      }
    }
  },
  
  {
    path: '/admin/log/activity',
    name: 'admin.logs',
    component: () => import('../../components/admin/logs/index.vue'),    
    meta: {
      pageTitle: 'Activity log',
      root: (route) => {
        return 'admin'
      },
      breadcrumb: (route) => { 
        return [
        {
          text: 'Home',
          to: '/'
        },
        {
          text: 'Admin',
          to: '/admin'
        },      
        {
          text: 'Activity Log',
          active: true
        },
      ]
      }
    }
   },
  
]  