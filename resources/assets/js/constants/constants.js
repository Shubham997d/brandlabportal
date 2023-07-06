// Common Variables

// Keep Same Variables as config/Constants.php As They Share Same Scope

const swalPopup = {
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#ebebeb',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}

const joinProjectSwalPopup = {
  title: 'Join project',
  text: "Send request to admin ?",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#ebebeb',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes'
}

const unauhorizeSwalPopup = {
  title: 'Oops...',
  text: "Your Session may have expired or your account status has been changed to inactive or your account has been deleted by the admin",
  icon: 'error',
  confirmButtonColor: '#000',
}


const tokeExipireSwalPopup = {
  title: 'Oops...',
  text: "Your token have expire we need to refresh your page",
  icon: 'error',
  confirmButtonColor: '#000',
}

const adminDetails = {
  role: 1,
}

const userRequests = {
  request: {  // resource name
    project: [
      'join-project',
      'create-project',
      'create-project-of-deal'
    ]
  },
  resource: [
    'project'
  ]

}

const progressBarFeatures = {
  color: '#f4c2c2',
  errorcolor: '#f44336',
  height: 5,
  speed: 350,
  zIndex: 999999
}

const currrenciesDetail = [
  { name: "UK Pound (GBP)", code: "GBP", symbol: "£" },
  { name: "US Dollar (USD)", code: "USD", symbol: "$" },
  { name: "EU Euro (EUR)", code: "EUR", symbol: "€" },
];

const customDateFormat = {
  format: 'dddd Do MMMM',
  formatWithYear: 'dddd Do MMMM YYYY',
  formatDate: 'dddd Do MMMM YYYY'
}

const otherFeatures = {
  color: '#667eea',
  errorcolor: '#f44336'
}

const error = {
  errorStatus: 'error',
  errorMessage: 'You do not have permission for this action'
}

const validationErrorMessages = {
  required: 'This field is required',
  between: 'This field should have value between',
  integer: "This field should have value only in integers",
  email: "Please provie a valid email address",
  mustHaveLength: "Please select more than 1 user",
  maxlength: {
    title: 'This field must not have more than 50 Characters',
    description: 'This field must not have more than 250 Characters'
  }
}

const miscellaneous = {
  storage: {
    files: '/storage/files/',
    pdf: '/storage/pdf/'
  },
  filter: {
    noData: 'Results cannot be displayed with current filters or grouping options.'
  },
  userOnline: {
    totalAllowedOutsidePopup: {
      desktop: {
        width: 1200,
        count: 25
      },
      tablet: {
        width: 641,
        count: 5
      },
      mobile: {
        width: 0,
        count: 0
      },
    }
  },
  currency: {
    default: {
      code: 'GBP',
      name: 'UK Pound (GBP)',
      symbol: '£',
      format: "en-GB"
    }
  },
  PipelineMsg1: 'This deal has not been in this stage yet',
  PipelineMsg2: 'Been here for ',
  PipelineMsg3: '0 day',
  loadingText: 'Loading...',
  notAvailableText: 'Not available',
  admin: {
    reports: {
      pagination: {
        default: {
          model: {
            perPage: 50,
            currentPage: 1
          },
          listing: {
            perPage: 50,
            currentPage: 1
          }
        }
      }
    }
  },
  customScrollBar: {
    withoutXScroll: {
      settings: {
        suppressScrollY: false,
        suppressScrollX: true,
        wheelPropagation: false,
      }
    }
  }

}


const homePage = {
  view: 'projects'
}

const projectManagers = {
  0: {
    projectManager: {
      name: 'Dan',
      email: 'test@gmail.com'
    }
  },
  1: {
    projectManager: {
      name: 'Jan',
      email: 'test@gmail.com'
    }
  }
}

const dealLostDropdown = {

  label: [
    {
      value: 1,
      text: 'Unresponsive',
    },
    {
      value: 2,
      text: 'No longer interested',
    },
    {
      value: 3,
      text: 'Out of their budget',
    },
    {
      value: 4,
      text: 'Bounce back email',
    },
    {
      value: 5,
      text: 'Went with a competitor',
    },
    {
      value: 6,
      text: 'Other',
    },

  ]
}

const Country = {

  label: [
    {
      value: 'UK',
      text: 'UK',
    },
    {
      value: 'Greece',
      text: 'Greece',
    },
    {
      value: 'Portugal',
      text: 'Portugal',
    },
    {
      value: 'USA',
      text: 'USA',
    },
    {
      value: 'Italy',
      text: 'Italy',
    },
    {
      value: 'Australia',
      text: 'Australia',
    },
    {
      value: 'Poland',
      text: 'Poland',
    },
    {
      value: 'Germany',
      text: 'Germany',
    },
    {
      value: 'New Zealand',
      text: 'New Zealand',
    },
    {
      value: 'France',
      text: 'France',
    },
    {
      value: 'Denmark',
      text: 'Denmark',
    },
  ]
}


const stagesDetail = ['Initial Outreach', 'Lead / Interest', 'Demo', 'Proposal', 'Negotiations']


const dealStatus =
{
  'open': [1],
  'lost': [2],
  'won': [3],
  'paused': [4],
  'all': [1, 2, 3, 4]
}

const defaultDealStatus =
{
  'open': 1,
  'lost': 2,
  'won': 3,
  'paused': 4,
}


// For Frontend Permissions
const permissions = {
  'salespipeline': [
    {
      viewInvoiceTab: {
        isDynamic: true,
        roleIds: [1, 2]   // Admin (1) & Sales Team(5) & Account Team (2) Roles  
      },
      updateDealStage: {
        isDynamic: false,
        roleIds: [1, 5]
      },
      changeDealStatus: {
        isDynamic: true,
        roleIds: [1]
      },
    }
  ]
}

const chat = {
  boardTypes: ['workspace', 'direct'],
  editorOptions: {
    modules: {
      toolbar: {
        container: [
          ["bold", "italic", "underline", "strike"],
          [{ list: "ordered" }, { list: "bullet" }],
          ["link"],
          ["emoji"],
        ],
        handlers: {
          emoji: function () { },
        },
      },
      short_name_emoji: true,
      toolbar_emoji: true,
      textarea_emoji: false,
    },
    placeholder: "Message",
  },
}

// Common Variables

const dealStatusSwalPopup = {
  'won': {
    title: 'Are you sure?',
    text: "A Project request will be need to be filled",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ebebeb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, create it'
  },
  'lost': {
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ebebeb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }
}




const project = {
  status: {
    in_progress: 1,
    completed: 2
  },
  label: {
    in_progress: 'In-Progress',
    completed: 'Completed',
  },
  slug: {
    'in-progress': 1,
    'completed': 2
  },
  createProject: {
    fromProjectPage: {
      'label': {
        'title': 'Title',
        'project_manager_id': 'Project Manager',
        'cost': 'Contract Value',
        'currency': 'Currency',
        'monthly_usage_fee': "Average Monthly Usage Fee",
        'contact_term': 'Contract Term Length (Months)',
        'project_categories': 'Category',
        'commission_user_id': 'Commission User',
        'commission_user_value': "User's Commission (%)",
        'deadline': "Deadline"
      },
      'placeholder': {
        'title': 'Add title',
        'project_manager_id': 'Choose project manager',
        'cost': 'Add contract value',
        'currency': 'Choose currency',
        'monthly_usage_fee': "Add average monthly usage fee",
        'contact_term': 'Add contract term length (Months)',
        'project_categories': 'Choose category',
        'commission_user_id': 'Choose commission user',
        'commission_user_value': "Add user's commission (%)",
        'deadline': "Add deadline"
      },
      'miscellaneous': {
        'model_title': 'Create a new project'
      },
    },
    fromDealPage: {
      'label': {
        'title': 'Title',
        'project_manager_id': 'Who is the project manager',
        'cost': 'What is the final contract value agreed with the client',
        'currency': 'What is the currency of the project',
        'monthly_usage_fee': "What is the Average Monthly Usage Fee",
        'contact_term': 'What is the contract term length (Months)',
        'project_categories': 'Category',
        'commission_user_id': 'Who is commission User',
        'commission_user_value': "Was this a self-sourced or inbound lead? If self-sourced, please input the commission as 10%, if inbound, please input 5%",
        'deadline': "What is the deadline of the project",
      },
      'placeholder': {
        'title': 'Add title',
        'project_manager_id': 'Add project manager',
        'cost': 'Add contract value',
        'currency': 'Choose currency',
        'monthly_usage_fee': "Add average monthly usage fee",
        'contact_term': 'Add contract term length (Months)',
        'project_categories': 'Choose category',
        'commission_user_id': 'Choose commission user',
        'commission_user_value': "Add user's commission (%)",
        'deadline': "Add deadline"
      },
      'miscellaneous': {
        'model_title': 'Create a new project request'
      },
    }
  },
}


const DealActivityEvents = {

  colors: [
    {
      name: 'Call',
      color: '#317ae2'
    },
    {
      name: 'Meeting',
      color: '#3F51B5'
    }, {
      name: 'Task',
      color: '#673AB7'
    }, {
      name: 'Deadline',
      color: '#00BCD4'
    }, {
      name: 'Email',
      color: '#4CAF50'
    }, {
      name: 'Linkedin',
      color: '#FF9800'
    },
  ]

}


const adminReportsTabs = {
  0: 'listing',
  1: 'summary'
}


const Invoice_type = {

  type: [
    {
      value: '',
      text: 'Please select a option',
    },
    {
      value: '1',
      text: 'Invoice',
    },
    {
      value: '2',
      text: 'Statement',
    },
  ]
}


const defaultInvoiceFormType = {

  type: [
    {
      value: '',
      text: 'Please select a option'
    },
    {
      value: '1',
      text: 'Bank Form'
    },
    {
      value: '2',
      text: 'Address Form'
    }
  ]

}

const fieldsInvoice = {
  'Invoice': 1,
  'Statement': 2
}

const defaultFieldsInvoice = {
  'Bank_Form': 1,
  'Address_Form': 2
}

const dealColors = {
  colorCodes: ['#D3D3D3', '#ffffff', '#f4cbcc', '#fce5ce', '#d9ead3']
}


const dealslisting = {
  filters: {   // Default values for filters for deals listing pages
    deals: {
      default: {
        dealOwner: 0,  // everyone
        dealColor: '#D3D3D3',
        dealCategory: 0,  // for every color
        dealCategoryDefaultForMultiSelect: {
          "id": 1,
          "name": "BrandLab360"
        },
        dealStage: 1, // first stage
        dealDefaultPage: 1, // default page      
      }
    }
  }
}

const dealDetail = {
  activity_types: [
    {
      type: 'Call',
      tabIndex: 0
    },
    {
      type: 'Meeting',
      tabIndex: 1
    },
    {
      type: 'Task',
      tabIndex: 2
    },
    {
      type: 'Deadline',
      tabIndex: 3
    },
    {
      type: 'Email',
      tabIndex: 4
    },
    {
      type: 'Linkedin',
      tabIndex: 5
    }
  ]
}

// For Admin Pages

// Logs state params

const logs = {
  activity: {
    filters: { // default params
      page: 1,
      dateRange: {
        startDate: new Date().getFullYear() + '-01-01', /* start date of current year */
        endDate: new Date().getFullYear() + '-12-31'  /* end date of current year */
      },
      user: {
        text: 'All users',
        value: null
      }
    }
  }
}

// Deal Reports

const dealFilters = {
  // field are mostly database fields
  dealSelectFilters: [
    {
      value: {
        field: 'status',
        value: [1, 2, 3, 4]
      },
      text: 'Deals Created',
      filterType: 'daterange',
      filterField: 'deals.created_at',
      filterName: 'deals_created',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_categories_data.category_id', // Field Value
        value: 'categories' // Add Value For Filter Array in Laravel
      },
      text: 'Deal Category',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'deal_category',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'status',
        value: 3
      },
      text: 'Won Time',
      filterType: 'daterange',
      filterField: 'deals.deal_won_datetime',
      filterName: 'won_time',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'status',
        value: 2
      },
      text: 'Loss Time',
      filterType: 'daterange',
      filterField: 'deals.deal_lost_datetime',
      filterName: 'loss_time',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'status',
        value: [2, 3]
      },
      text: 'Deal Closed On',
      filterType: 'daterange',
      filterField: 'deals.created_at',
      filterName: 'deal_closed_on',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'expected_close_date',
        value: 'expected_close_date'
      },
      text: 'Expected Close Date',
      filterType: 'daterange',
      filterField: 'expected_close_date',
      filterName: 'expected_close_date',
      isDefaultFilterField: false,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'pipeline_stage',
        value: 'pipeline_stage'
      },
      text: 'Current Stage',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'current_stage',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false

    },
    {
      value: {
        field: 'users.id', // Field Value
        value: 'users' // Add Value For Filter Array in Laravel
      },
      text: 'Owner',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'owner',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'creator_id', // Field Value
        value: 'users' // Add Value For Filter Array in Laravel
      },
      text: 'Creater',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'creater',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deals.status',
        value: 'deal_status'
      },
      text: 'Status',
      filterType: 'select',
      filterName: 'status',
      filterField: 'deals.created_at',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_lost_reasons.reason_id',
        value: 'deal_lost_reasons'
      },
      text: 'Deal lost reasons',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'deal_lost_reasons',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_organisations.currency_code',
        value: 'currency_code'
      },
      text: 'Currency',
      filterType: 'select',
      filterField: 'deals.created_at',
      filterName: 'currency',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_organisations.turnover',
        value: 'turnover'
      },
      text: 'Value',
      filterType: 'input',
      filterField: 'deals.created_at',
      filterName: 'value',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: true,
      prependName: 'More than or equal to',
      isRangeType: true,
      range: '>=',
      type: 'number'
    },
    {
      value: {
        field: 'deal_activities.status',
        value: [0, 1]
      },
      text: 'Activity created',
      filterType: 'daterange',
      filterField: 'deal_activities.created_at',
      filterName: 'activity_created',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_activities.status',
        value: [0, 1]
      },
      text: 'Activity start at',
      filterType: 'daterange',
      filterField: 'deal_activities.timeline_time_in',
      filterName: 'activity_start_at',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'user_id',
        value: 'users'
      },
      text: 'Activity owner',
      filterType: 'select',
      filterField: 'deal_activities.created_at',
      filterName: 'activity_owner',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'creator_id',
        value: 'users'
      },
      text: 'Activity creator',
      filterType: 'select',
      filterField: 'deal_activities.created_at',
      filterName: 'activity_creator',
      isDefaultFilterField: true,
      isDynamic: true,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_activities.status',
        value: 'activity_status'
      },
      text: 'Activity status',
      filterType: 'select',
      filterField: 'deal_activities.created_at',
      filterName: 'activity_status',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },
    {
      value: {
        field: 'deal_activities.activity_type',
        value: 'activity_types'
      },
      text: 'Activity type',
      filterType: 'select',
      filterField: 'deal_activities.created_at',
      filterName: 'activity_type',
      isDefaultFilterField: true,
      isDynamic: false,
      haveThirdFilter: false
    },

  ],
  dealSelectFiltersFields: {
    pipeline_stage: [
      {
        value: 1,
        text: 'Initial Outreach'
      },
      {
        value: 2,
        text: 'Lead / Interest'
      },
      {
        value: 3,
        text: 'Demo'
      },
      {
        value: 4,
        text: 'Proposal'
      },
      {
        value: 5,
        text: 'Negotiations'
      },
    ],
    deal_status: [
      {
        value: 1,
        text: 'Open'
      },
      {
        value: 2,
        text: 'Lost'
      },
      {
        value: 3,
        text: 'Won'
      },
      {
        value: 4,
        text: 'Paused'
      }
    ],
    deal_lost_reasons: [
      {
        value: 1,
        text: 'Unresponsive'
      },
      {
        value: 2,
        text: 'No longer interested'
      },
      {
        value: 3,
        text: 'Out of their budget'
      },
      {
        value: 4,
        text: 'Bounce back email'
      },
      {
        value: 5,
        text: 'Went with a competitor'
      },
      {
        value: 6,
        text: 'Other'
      }

    ],
    currency_code: [
      {
        value: 'GBP',
        text: 'UK Pound (GBP)'
      },
      {
        value: 'USD',
        text: 'US Dollar (USD)'
      },
      {
        value: 'EUR',
        text: 'EU Euro (EUR)'
      }
    ],
    activity_status: [
      {
        value: '0',
        text: 'To do'
      },
      {
        value: '1',
        text: 'Done'
      }
    ],
    activity_types: [
      {
        value: 'Call',
        text: 'Call'
      },
      {
        value: 'Meeting',
        text: 'Meeting'
      },
      {
        value: 'Task',
        text: 'Task'
      },
      {
        value: 'Deadline',
        text: 'Deadline'
      },
      {
        value: 'Email',
        text: 'Email'
      },
      {
        value: 'Linkedin',
        text: 'Linkedin'
      }
    ],
  }
}



const dealReports = {
  // field are mostly database fields (table  & fiedl name)     // dynamic means if values are coming from custom object
  // add reIntialize: false to defaultParams for filters
  reportType: {
    'started': { // report page slug 
      extraFilter: { // For Model 
        extraField: 'users.username',
        type: 'basic'
      },
      summaryNotAvailable: true,
      dynamicValue: false,
      modelTitle: 'Deals',
      filters: {
        chartSortTypeAvailable: true,
        disabled: {
          chartSortType: false
        },
        defaultParams: {
          selectFilterFirst: {
            value: {
              field: 'status',
              value: [1, 2, 3, 4]
            },
            text: 'Deals Created',
            filterType: 'daterange',
            filterField: 'deals.created_at',
            isDefaultFilterField: true,
            isDynamic: false,
            haveThirdFilter: false,
            reIntialize: true
          },
          selectFilterSecond: {
            reIntialize: true
          },
          chartSortType: {
            value: 'number_of_deals',
            text: 'Number Of Deals',
            reIntialize: true
          }
        },
        availableFilters: ['deals_created', 'deal_category', 'won_time', 'loss_time', 'status', 'deal_closed_on', 'expected_close_date', 'current_stage', 'owner', 'creater', 'deal_lost_reasons', 'currency', 'value'],
        availableSortTypeFilters: ['number_of_deals', 'price_of_deals']

      }

    },
    'lost-by-reasons': {
      extraFilter: {
        extraField: 'deal_lost_reasons.reason_id',
        type: 'basic'
      },
      summaryNotAvailable: true,
      dynamicValue: true,
      modelTitle: 'Deals',
      values: {
        'Unresponsive': 1,
        'No longer interested': 2,
        'Out of their budget': 3,
        'Bounce back email': 4,
        'Went with a competitor': 5,
        'Other': 6
      },
      filters: {
        chartSortTypeAvailable: true,
        disabled: {
          chartSortType: false
        },
        defaultParams: {
          selectFilterFirst: {
            value: {
              field: 'status',
              value: 2
            },
            text: 'Loss Time',
            filterType: 'daterange',
            filterField: 'deals.deal_lost_datetime',
            isDefaultFilterField: true,
            isDynamic: false,
            haveThirdFilter: false,
            reIntialize: true
          },
          selectFilterSecond: {
            reIntialize: true
          },
          chartSortType: {
            value: 'number_of_deals',
            text: 'Number Of Deals',
            reIntialize: true
          }
        },
        availableFilters: ['deals_created', 'deal_category', 'won_time', 'loss_time', 'status', 'deal_closed_on', 'expected_close_date', 'current_stage', 'owner', 'creater', 'deal_lost_reasons', 'currency', 'value'],
        availableSortTypeFilters: ['number_of_deals', 'price_of_deals']


      }
    },
    'progress': {
      multipleChartSortType: true,   // meaning different types of graph data like on deal progress page    
      extraFilter: {
        'number_of_deals': {
          extraField: 'stage_details.start_datetime',
          type: 'monthYear'
        },
        'group_by_users': {
          extraField: 'users.username',
          type: 'basic'
        }
      },
      summaryNotAvailable: false,
      dynamicValue: false,
      modelTitle: 'Deals',
      filters: {
        chartSortTypeAvailable: true,
        disabled: {
          chartSortType: false
        },
        defaultParams: {
          selectFilterFirst: {
            value: {
              field: 'status',
              value: [1, 2, 3, 4]
            },
            text: 'Deals Created',
            filterType: 'daterange',
            filterField: 'deals.created_at',
            isDefaultFilterField: true,
            isDynamic: false,
            haveThirdFilter: false,
            reIntialize: true
          },
          selectFilterSecond: {
            reIntialize: true
          },
          chartSortType: {
            value: 'number_of_deals',
            text: 'Number Of Deals',
            reIntialize: true
          }
        },
        availableFilters: ['deals_created', 'deal_category', 'won_time', 'loss_time', 'status', 'deal_closed_on', 'expected_close_date', 'current_stage', 'owner', 'creater', 'deal_lost_reasons', 'currency', 'value'],
        availableSortTypeFilters: ['number_of_deals', 'group_by_users']
      }
    },
    'won-over-time': {
      extraFilter: {
        extraField: 'deals.deal_won_datetime',
        type: 'monthYear'
      },
      summaryNotAvailable: true,
      dynamicValue: false,
      modelTitle: 'Deals',
      filters: {
        chartSortTypeAvailable: true,
        disabled: {
          chartSortType: false
        },
        defaultParams: {
          selectFilterFirst: {
            value: {
              field: 'status',
              value: 3
            },
            text: 'Won Time',
            filterType: 'daterange',
            filterField: 'deals.deal_won_datetime',
            isDefaultFilterField: true,
            isDynamic: false,
            haveThirdFilter: false,
            reIntialize: true
          },
          selectFilterSecond: {
            reIntialize: true
          },
          chartSortType: {
            value: 'number_of_deals',
            text: 'Number Of Deals',
            reIntialize: true
          }
        },
        availableFilters: ['deals_created', 'deal_category', 'won_time', 'loss_time', 'status', 'deal_closed_on', 'expected_close_date', 'current_stage', 'owner', 'creater', 'deal_lost_reasons', 'currency', 'value'],
        availableSortTypeFilters: ['number_of_deals', 'price_of_deals']
      }
    },
    'activity': {
      extraFilter: {
        extraField: 'deal_activities.status',
        type: 'basic'
      },
      summaryNotAvailable: false,
      dynamicValue: false,
      modelTitle: 'Activities',
      filters: {
        chartSortTypeAvailable: true,
        disabled: {
          chartSortType: true
        },
        defaultParams: {
          selectFilterFirst: {
            value: {
              field: 'deal_activities.status',
              value: [0, 1]
            },
            text: 'Activity created',
            filterType: 'daterange',
            filterField: 'deal_activities.created_at',
            filterName: 'activity_created',
            isDefaultFilterField: true,
            isDynamic: true,
            haveThirdFilter: false,
            reIntialize: true
          },
          selectFilterSecond: {
            reIntialize: true
          },
          chartSortType: {
            value: 'number_of_activities',
            text: 'Number of Activities',
            reIntialize: true
          }
        },
        availableFilters: ['activity_created', 'activity_creator', 'activity_start_at', 'activity_owner', 'activity_status', 'activity_type'],
        availableSortTypeFilters: ['number_of_activities']
      }
    }

  }
}

const dealCharts = {
  chartSortType: [
    {
      value: 'number_of_deals',
      text: 'Number Of Deals'
    },
    {
      value: 'price_of_deals',
      text: 'Price of Deals'
    },
    {
      value: 'number_of_activities',
      text: 'Number of Activities'
    },
    {
      value: 'group_by_users',
      text: 'Group by Users'
    }
  ],
  defaultChartSortType: {
    value: 'number_of_deals',
    text: 'Number Of Deals'
  }
}

// For Admin Pages

export default {
  swalPopup: swalPopup,
  adminDetails: adminDetails,
  error: error,
  otherFeatures: otherFeatures,
  progressBarFeatures: progressBarFeatures,
  project: project,
  Country: Country,
  dealLostDropdown: dealLostDropdown,
  Invoice_type: Invoice_type,
  defaultInvoiceFormType: defaultInvoiceFormType,
  customDateFormat: customDateFormat,
  projectManagers: projectManagers,
  homePage: homePage,
  currrenciesDetail: currrenciesDetail,
  stagesDetail: stagesDetail,
  unauhorizeSwalPopup: unauhorizeSwalPopup,
  validationErrorMessages: validationErrorMessages,
  tokeExipireSwalPopup: tokeExipireSwalPopup,
  miscellaneous: miscellaneous,
  dealFilters: dealFilters,
  DealActivityEvents: DealActivityEvents,
  dealStatus: dealStatus,
  defaultFieldsInvoice: defaultFieldsInvoice,
  fieldsInvoice: fieldsInvoice,
  defaultDealStatus: defaultDealStatus,
  adminReportsTabs: adminReportsTabs,
  dealStatusSwalPopup: dealStatusSwalPopup,
  dealCharts: dealCharts,
  dealReports: dealReports,
  permissions: permissions,
  dealColors: dealColors,
  dealslisting: dealslisting,
  dealDetail: dealDetail,
  joinProjectSwalPopup: joinProjectSwalPopup,
  userRequests: userRequests,
  logs: logs,
  chat: chat
}