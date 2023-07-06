export default [

    {
      path: '/deals/:status',
      name: 'deals',
      component: () => import('../../components/deals/single.vue'),
      beforeEnter: (to, from, next) => {
        let paramsAvailable = ['all','open','paused','won','lost']
        if(paramsAvailable.includes(to.params.status) == true){
              next()
        }else{
              next(error)
        }
      },
      meta: {
        pageTitle: 'Deals',
        root: route => {
          return 'deal'
        },
        colour: route => {
          if(route.path.includes("/deals/all")){
               return '#e2e3e5' 
          }else if(route.path.includes("/deals/open")){
               return '#e2e3e5'
          }else if(route.path.includes("/deals/paused")){
               return '#D5E1FF'
          }else if(route.path.includes("/deals/won")){
              return '#155724'
          }else if(route.path.includes("/deals/lost")){
              return '#721c24'
          }else{
              return '#e2e3e5'
          }
        },
        breadcrumb: route => {
          if(route.path.includes("/deals/all")){
            return  [
              {
                text: 'Deals'                
              },
              {
                text: 'All deals',
                active: true
              }
            ]
            }else if(route.path.includes("/deals/open")){
              return  [
                {
                  text: 'Deals'                
                },
                {
                  text: 'Deals open',
                  active: true
                }
              ]
            }else if(route.path.includes("/deals/paused")){
              return  [
                {
                  text: 'Deals'                
                },
                {
                  text: 'Deals paused',
                  active: true
                }
              ]
            }else if(route.path.includes("/deals/won")){
              return  [
                {
                  text: 'Deals'                
                },
                {
                  text: 'Deals won',
                  active: true
                }
              ]
            }else if(route.path.includes("/deals/lost")){
              return  [
                {
                  text: 'Deals'                
                },
                {
                  text: 'Deals lost',
                  active: true
                }
              ]
            }
            else{
              return  [
                {
                  text: 'Deals',
                  active: true
                }
              ]
            }        
        }
      },
    },


    // Redirect deals to all deals page
    { 
      path: '/deals', 
      redirect: '/deals/all' 
    },
    
    {
      path: '/deal/:id',
      name: 'deal-details',
      component: () => import('../../components/partials/dealDetail.vue'),
      meta: {
        pageTitle: 'Deal Details',
        root: route => {
            return 'deal'
        },
        breadcrumb: route => { 
          return [
            {
              text: 'Deals',
              to: '/deals'
            },          
            {
              text: 'Deal Details',
              active: true,
            },
          ]
        },
      },
    }
    
]  


