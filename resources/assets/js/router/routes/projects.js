export default [    
  {
    path: '/projects/:category/:status',
    alias: '/admin/projects/:category/:status',
    name: 'projects',
    beforeEnter: (to, from, next) => {
      let paramsAvailable = ['in-progress','completed']
      if(paramsAvailable.includes(to.params.status) == true){
            next()
      }else{
        next(error)
      }
    },
    component: () => import('../../components/home/projects.vue'),    
    meta: {
      pageTitle: 'Projects',
      root: (route = null) => {
        if(route && route.path.includes("/admin")){
            return 'admin'
        }else{
            return 'projects'
        }
      },      
      breadcrumb: (route) => {
        if(route.path.includes("/admin")){          
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
              text: 'Projects Completed'              
            },
            {
              text: route.params.category,
              active: true
            }
          ]           
        } 
        else {
          return [
            {
              text: 'Projects',
            },
            {
              text: route.params.category,
              active: true
            },
          ] 
        }
      }
    },
  },
  
  {
    path: '/project/:id',
    name: 'project',
    alias: '/admin/project/:id',
    component: () => import('../../components/projects/single.vue'),
    children: [
      { 
        name: 'project.detail',     
        path: 'detail',
        alias: '/admin/project/:id/detail',
        component: () => import('../../components/partials/projectDetail.vue'),
        meta: {
          root: (route) => {            
              return 'project'
          },                    
          breadcrumb: (route) => {
            if(route.path.includes("/admin")){
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
                    text: 'Projects',
                    to: '/admin/projects/completed'
                  },          
                  {
                    text: 'Project Details',
                    active: true,
                  },
                ]
            }else{
              
              return [
                 {
                   text: 'Projects',
                   to: '/projects/in-progress'
                 },          
                 {
                   text: 'Project Details',
                   active: true,
                 },
               ]
            }
           }
        }
      },
      {
        name: 'project.tasks',      
        path: 'tasks',
        alias: '/admin/project/:id/tasks',
        component: () => import('../../components/partials/taskBoard.vue'),
        meta: {
          root: (route) => {
              return 'project'
          },          
          breadcrumb: (route) => {
            
            if(route.path.includes("/admin")){
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
                  text: 'Projects',
                  to: '/admin/projects/completed'
                },          
                {
                  text: 'Tasks',
                  active: true,
                },
              ]
            }else{
              return [
                 {
                   text: 'Projects',
                   to: '/projects/in-progress'
                 },          
                 {
                   text: 'Tasks',
                   active: true,
                 },
               ]
            }
           }
        }
      },
      {
        name: 'project.assets',      
        path: 'assets',
        alias: '/admin/project/:id/assets',
        component: () => import('../../components/partials/assetBoard.vue'),
        meta: {
          root: (route) => {
              return 'project'
          },          
          breadcrumb: (route) => {
            
            if(route.path.includes("/admin")){
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
                  text: 'Projects',
                  to: '/admin/projects/completed'
                },          
                {
                  text: 'Assets',
                  active: true,
                },
              ]
            }else{
              return [
                 {
                   text: 'Projects',
                   to: '/projects/in-progress'
                 },          
                 {
                   text: 'Assets',
                   active: true,
                 },
               ]
            }
           }
        }
      }
    ],
    meta: {
      pageTitle: 'Project',
      root: (route) => {
        if(route.path.includes("/admin")){
            return 'admin'
        }else{
            return 'project'
        }
      },      
      breadcrumb: (route) => {
        if(route.path.includes("/admin")){
          return [
            {
              text: 'Home',
              to: '/'
            },
            {
              text: 'Project',
              active: true,
            },
          ]
        }else{
          return [
             {
               text: 'Project',
             },
           ]
          
        }
      }
    }
  }  
  
    
]  

