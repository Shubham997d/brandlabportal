export default [


  {
    path: '/chat',
    name: 'chat',
    component: () => import('../../components/chat/index.vue'),
    meta: {
      root: (route) => {
        return 'chat'
      },
      breadcrumb: (route) => {
        return [
          {
            text: 'Chat',
            active: true,
          }
        ]
      },
    },
    children: [     
      {
        name: 'chat.coversation',
        path: 'coversation',
        component: () => import('../../components/chat/coversation/index.vue'),
        meta: {
          root: (route) => {
            return 'coversation'
          },
          breadcrumb: (route) => {
            return [
              {
                text: 'Chat',
              },
              {
                text: 'Coversation',
                active: true,
              },
            ]
          }
        },
        children: [
          {
            name: 'chat.coversation.chatboard',
            path: 'direct/:name',
            alias: 'workspace/:name',
            component: () => import('../../components/chat/coversation/chatBoard.vue'),
            meta: {
              root: (route) => {
                return 'chatboard'
              },
              breadcrumb: (route) => {
                return [
                  {
                    text: 'Chat',
                  },
                  {
                    text: 'Chat Board',
                    active: true,
                  },
                ]
              }
            },
            children: [
              { 
                name: 'chat.coversation.chatboard.messages',
                path: 'messages',
                component: () => import('../../components/chat/coversation/chatMessages.vue'),
                meta: {
                  root: (route) => {
                    return 'messages'
                  },
                  breadcrumb: (route) => {
                    return [
                      {
                        text: 'Chat',
                      },
                      {
                        text: 'Chat Board',                        
                      },
                      {
                        text: 'Messages',
                        active: true,
                      },
                    ]
                  }
                },
              },
              { 
                name: 'chat.coversation.chatboard.files',
                path: 'files',
                component: () => import('../../components/chat/coversation/chatFiles.vue'),
                meta: {
                  root: (route) => {
                    return 'files'
                  },
                  breadcrumb: (route) => {
                    return [
                      {
                        text: 'Chat',
                      },
                      {
                        text: 'Chat board',                        
                      },
                      {
                        text: 'files',
                        active: true,
                      },
                    ]
                  }
                },
              },
              { 
                name: 'chat.coversation.chatboard.photos',
                path: 'photos',
                component: () => import('../../components/chat/coversation/chatPhotos.vue'),
                meta: {
                  root: (route) => {
                    return 'photos'
                  },
                  breadcrumb: (route) => {
                    return [
                      {
                        text: 'Chat',
                      },
                      {
                        text: 'Chat board',                        
                      },
                      {
                        text: 'photos',
                        active: true,
                      },
                    ]
                  }
                },
              }
            ]
          },
          {
            name: 'chat.coversation.createWorkspace',
            path: 'create-workspace',
            component: () => import('../../components/chat/coversation/createWorkspace.vue'),
            meta: {
              root: (route) => {
                return 'createWorkspace'
              },
              breadcrumb: (route) => {
                return [
                  {
                    text: 'Chat',
                  },
                  {
                    text: 'Create workplace',
                    active: true,
                  },
                ]
              }
            }
          },
        ]
      },
      {
        name: 'chat.meet',
        path: 'meet',
        component: () => import('../../components/chat/meet/index.vue'),
        meta: {
          root: (route) => {
            return 'meet'
          },
          breadcrumb: (route) => {
            return [
              {
                text: 'Chat',
              },
              {
                text: 'Meet',
                active: true,
              },
            ]
          }
        }
      },
      {
        name: 'chat.calendar',
        path: 'calendar',
        component: () => import('../../components/chat/calendar/index.vue'),
        meta: {
          root: (route) => {
            return 'calendar'
          },
          breadcrumb: (route) => {
            return [
              {
                text: 'Chat',
              },
              {
                text: 'Calendar',
                active: true,
              },
            ]
          }
        }
      },
    ]
  },

]  