<?php

return [
    'admin' => [
        'default_role_id' => 1,
        'slug' => 'admin'
    ],
    'emailTrigger' => [
        'triggerDays'  => 14,
        'monthlyMail' => 30
    ],
    'team_member' => [
        'default_role_id' => 3
    ],
    'sales_admin' => [
        'default_role_id' => 2
    ],
    'project' => [
       'status' => [
	       		'in_progress' => 1,
	        	'completed' => 2
        ],
      'requests' => [
            'request_approval_status' => [
                  'approved' => 1,
                  'pending' =>  2,
                  'denied' => 3                
            ],
           'request' => [  
              'project' => [
                  'join-project',
                  'create-project',
                  'create-project-of-deal'
              ]
            ],
            'resource' => [
              'project'
            ],
            'messages' => [ // for frontend
                'accept' => [
                  'heading' => 'Request accepted',
                  'content' => 'User has been notified'
                ],
                'deny' => [
                  'heading' => 'Request denied',
                  'content' => 'User has been notified'
                ],
                'toUser' => [ // on frontend
                    'success' => [
                      'sentForApproval' => 'Your request has been sent for approval'
                    ],
                    'error' => [
                      'requestPendingToJoinProject' => 'Please contact admin your previous request for this project is still pending',
                      'requestPendingToCreateProject' => 'Please contact admin your previous request with this project title is still pending',
                      'requestPendingToCreateProjectOfDeal' => 'Please contact admin your previous request of creating project for this deal is still pending',
                      'projectAlreadyExists' => 'Project with this name already exists',
                      'userAlreadyInProject' => 'You are already part of this project'
                    ]
                ],
                'error' => [
                  'alreadyAccepted' => [ 
                      'heading' =>  'You have already accepted the request'
                  ],
                  'alreadyDenied' => [ 
                      'heading' =>  'You have already denied the request'
                  ],
                  'other' => [ 
                    'heading' =>  'Something went wrong'
                  ],
                  'tokenNotMatched' => [ 
                    'heading' =>  'Your token does not match'
                  ]  
                ]
            ],
            'email' => [ 
              'subjects' => [
                'project' => [ // resource
                  'toAdmin' => [
                    'join-project' => 'A user has requested to join a project',
                    'create-project' => 'A user has requested to create a project',
                    'create-project-of-deal' => 'A user has requested to create a project for a deal'
                  ],
                  'toUser' => [ 
                    'accept' => [
                      'join-project' => 'Your request to join project has been approved',
                      'create-project' => 'Your request to create a project has been approved',
                      'create-project-of-deal' => 'Your request to create project of a deal has been approved'
                    ],
                    'deny' => [
                      'join-project' => 'Your request to join project has been denied',
                      'create-project' => 'Your request to create a project has been denied',
                      'create-project-of-deal' => 'Your request to create project of a deal has been denied'
                    ]
                  ]
 
                ]
              ]
            ]

        ],
       'project_profit_chart' => [
             'labels' => [
                'profit' => 'Profit',
                'loss' => 'Loss',
                'cost'   => 'Cost',
                'user_commission'   => 'Commission',
                'asset_cost'   => 'Asset Cost',
                'monthly_usage'   => 'Monthly usage fee'
            ],
            'colours' => [
                'profit' => '#00FF00',
                'loss' => '#8B0000',
                'cost'   => '#f4c2c2',
                'user_commission' => '#F7E6D2',
                'asset_cost'   => '#aad2ff',
                'monthly_usage'   => '#d3d3d3'
            ]
        ],
        'pagination' => 50,
        'miscellaneous' => [      
          'defaultValues' => [
            'common_slug' => 'all-projects',
            'category' => 1 // brandlab360
          ]
        ]

    ],
    'salespipeline' => [
        'pagination' => 50,
        'deal' => [
                'status' => [
                    'Open' => 1,
                    'Lost' => 2,
                    'Won' => 3,
                    'Paused' => 4
                ]
        ],
        'deal_email_status' =>[
                'zero' => 0, // zero for email not send
                'one' => 1 // one for email is sent
        ],
        'deal_invoice_default' => [
            'bank_form' => 1,
            'address_form' => 2
        ],
        'deal_stages' => [
            'stage' => [
                  1 => 'Initial Outreach',
                  2 => 'Lead / Interest',
                  3 => 'Demo',
                  4 => 'Proposal',
                  5 => 'Negotiations'
            ],
            'colour' => [
                  1 => '#f6adb7',
                  2 => '#fbdcb5',
                  3 => '#ffffb4',
                  4 => '#c4ffc6',
                  5 => '#bee1ff'
            ]
         ],
        'reports' => [
          'reportType' => [ 
            'started' => [   //reportType
              'fields' => [  // vue  bootstrap table format for fields
                'listing' => [ // tab name
                      0 => [
                        "key" => "deal_link",
                        "label" => "Title",
                        "sortable" => true                      
                      ],            
                      1 => [
                        "key" => "creater",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "organisation",
                        "label" => "Organisation",
                        "sortable" => true
                      ],
                      4 =>[
                        "key" => "name",
                        "label" => "Category name",
                        "sortable" => true
                      ],       
                      5 => [
                        "key" => "turnover",
                        "label" => "Value",
                        "sortable" => false,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ],
                      6 => [
                        "key" => "created_at",
                        "label" => "Deal created",
                        "sortable" => true
                      ],
                      7 => [
                        "key" => "deal_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      8 => [
                        "key" => "expected_closed_date_formatted",
                        "label" => "Expected close date",
                        "sortable" => true
                      ],
                      9 =>[
                        "key" => "title",
                        "label" => "Title",
                        "sortable" => true,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ], 
                      10 =>[
                        "key" => "deal_price_with_currency",
                        "label" => "Value",
                        "sortable" => true
                      ],
                      11 =>[
                        "key" => "deal_current_stage",
                        "label" => "Current Stage",
                        "sortable" => true
                      ]                                    
                   ],
                    'summary' => [ // tab
                      0 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true,
                        "fieldType" => 'label',
                        "lastRowlabel" => 'Total'
                      ],
                      1 => [
                        "key" => "average_amount",
                        "label" => "Average Deal Value",
                        "sortable" => true, 
                        "fieldType" => 'currency',
                        "lastRowlabel" => false
                      ],
                      2 => [
                        "key" => "total_amount",
                        "label" => "Total Value of Deals",
                        "sortable" => true,
                        "fieldType" => 'currency',
                        "lastRowlabel" => false
                      ],
                      3 => [
                        "key" => "total_deals",
                        "label" => "Total Count of Deals",
                        "sortable" => true,
                        "fieldType" => 'number',
                        "lastRowlabel" => false            
                      ] 
                  ],    
                  'model' => [ // for model/popup not tab
                      0 => [
                        "key" => "deal_link",
                        "label" => "Title",
                        "sortable" => true                      
                      ],            
                      1 => [
                        "key" => "creater",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "organisation",
                        "label" => "Organisation",
                        "sortable" => true
                      ],
                      4 => [
                        "key" => "name",
                        "label" => "Category name",
                        "sortable" => true
                      ], 
                      5 => [
                        "key" => "turnover",
                        "label" => "Value",
                        "sortable" => false,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ],
                      6 => [
                        "key" => "created_at",
                        "label" => "Deal created",
                        "sortable" => true
                      ],
                      7 => [
                        "key" => "deal_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      8 => [
                        "key" => "expected_closed_date_formatted",
                        "label" => "Expected close date",
                        "sortable" => true
                      ],
                      9 =>[
                        "key" => "title",
                        "label" => "Title",
                        "sortable" => true,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ], 
                      10 =>[
                        "key" => "deal_price_with_currency",
                        "label" => "Value",
                        "sortable" => true
                      ],
                      11 =>[
                        "key" => "deal_current_stage",
                        "label" => "Current Stage",
                        "sortable" => true
                      ]                     
                      // 11 =>[
                      //   "key" => "deal_lost_datetime",
                      //   "label" => "Lost Date Time",
                      //   "sortable" => true
                      // ],         
                      // 12 =>[
                      //   "key" => "deal_lost_reason",
                      //   "label" => "Deal Lost Reason",
                      //   "sortable" => true
                      // ],
                      // 13 =>[
                      //   "key" => "notes",
                      //   "label" => "Deal Lost Reason Notes",
                      //   "sortable" => true
                      // ],
                      // 14 =>[
                      //   "key" => "deal_won_datetime",
                      //   "label" => "Won Date Time",
                      //   "sortable" => true
                      // ]  
                 ]
                ], 
              'sql' => [ 
                'listing' => [ 
                    'frontend' => [ // requestType
                      'get' => ['deals.created_at','deals.owner_id','deals.deal_lost_datetime','deals.deal_won_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                      'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_lost_reason','deal_current_stage','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],     
                      'groupByIsDynamic' => false,
                      'groupBy' => null,  // Should be string if groupByUsingCollectionMethod is false, if true then can add array as well
                      'groupByUsingCollectionMethod' => false, 
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => []                      
                    ],
                    'export' => [ // requestType
                      'get' => ['deals.created_at','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],     
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => []
                    ],
                    'chart' => [ // requestType
                      'get' => ['deals.owner_id','username','deals.id'],
                      'append' => ['converted_value','background_color'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],     
                      'groupByIsDynamic' => false,
                      'groupBy' => ['username'],
                      'groupByUsingCollectionMethod' => true,
                      'haveDynamicFields' => false,
                      'chartHasSameAsGetFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => ['username','total_amount','backgroundColor','total_deals'],
                      'chartDefaultFields' => [
                        'number_of_deals' => [  // Chart Key & Data Value
                          'main' => 'username',
                          'backgroundColor' => 'backgroundColor',
                          'data' => 'total_deals'
                        ],
                        'price_of_deals' => [
                          'main' => 'username',
                          'backgroundColor' => 'backgroundColor',
                          'data' => 'total_amount'
                          ]
                      ]
                    ]
                  ],
                'summary' => [
                  'frontend' => [
                    'get' => ['deals.owner_id','username','deals.id'],
                    'append' => ['converted_value','background_color'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => ['username'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => false, 
                    'remmoveNullKey' => false,
                    'requiredFields' => ['total_amount','average_amount','total_deals','username','backgroundColor']
                  ],
                  'export' => [ 
                      'get' => [],
                      'append' => [],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],     
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => []
                  ],
                  'chart' => [
                    'get' => ['deals.owner_id','username','deals.id'],
                    'append' => ['converted_value','background_color'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => ['username'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => false,
                    'chartHasSameAsGetFields' => true,
                    'remmoveNullKey' => false,
                    'requiredFields' => ['username','total_amount','backgroundColor','total_deals'],
                    'chartDefaultFields' => [
                      'number_of_deals' => [
                        'main' => 'username',
                        'backgroundColor' => 'backgroundColor',
                        'data' => 'total_deals'
                      ],
                      'price_of_deals' => [
                        'main' => 'username',
                        'backgroundColor' => 'backgroundColor',
                        'data' => 'total_amount'
                        ]
                    ]
                  ]
              ],
              'model' => [ // requestType
                'common' => [
                    'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                    'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => []                      
                  ],
                  'export' => [
                    'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                    'append' => ['deal_link','deal_status','creater','deal_price_with_currency_symbol','deal_current_stage','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => []                
                    ]
                  ]
              ],
              'export' => [
                'headings' => [
                  'Title',
                  'Creater',
                  'Owner',
                  'Organisation',
                  'Category name',
                  'Value',
                  'Deal created',
                  'Status',
                  'Expected close date',
                  'Deal Current Stage',
                ],
                'fields' => [
                  'title',
                  'creater',
                  'username',
                  'organisation',
                  'name',
                  'deal_price_with_currency_symbol',
                  'created_at',
                  'deal_status',
                  'expected_closed_date_formatted',
                  'deal_current_stage',
                ]
              ],
            'defaultFilterParams' => [],
            'chart'=>'horizontal',
            'isChartStacked'=> false,
            'reportBelongsTo'=> 'deals'
            ],
            'lost-by-reasons' => [
              'fields' => [
                  'listing' => [
                        0 => [
                          "key" => "deal_link",
                          "label" => "Title",
                          "sortable" => true
                        ],            
                        1 => [
                          "key" => "creater",
                          "label" => "Creater",
                          "sortable" => true
                        ],
                        2 => [
                          "key" => "username",
                          "label" => "Owner",
                          "sortable" => true
                        ],
                        3 => [
                          "key" => "organisation",
                          "label" => "Organisation",
                          "sortable" => true
                        ],
                        4 => [
                          "key" => "name",
                          "label" => "Category name",
                          "sortable" => true
                        ], 
                        5 => [
                          "key" => "turnover",
                          "label" => "Value",
                          "sortable" => false,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ],
                        6 => [
                          "key" => "created_at",
                          "label" => "Deal created",
                          "sortable" => true
                        ],
                        7 => [
                          "key" => "deal_status",
                          "label" => "Status",
                          "sortable" => true
                        ],
                        8 => [
                          "key" => "expected_closed_date_formatted",
                          "label" => "Expected close date",
                          "sortable" => true
                        ],
                        9 =>[
                          "key" => "title",
                          "label" => "Title",
                          "sortable" => true,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ], 
                        10 =>[
                          "key" => "deal_price_with_currency",
                          "label" => "Value",
                          "sortable" => true
                        ],
                        11 =>[
                          "key" => "deal_current_stage",
                          "label" => "Current Stage",
                          "sortable" => true
                        ],
                        12 =>[
                          "key" => "deal_lost_datetime",
                          "label" => "Lost Date Time",
                          "sortable" => true
                        ],         
                        13 =>[
                          "key" => "deal_lost_reason",
                          "label" => "Deal Lost Reason",
                          "sortable" => true
                        ],
                        14 =>[
                          "key" => "notes",
                          "label" => "Deal Lost Reason Notes",
                          "sortable" => true
                        ]                      
                    ],
                      'summary' => [
                        0 => [
                          "key" => "username",
                          "label" => "Owner",
                          "sortable" => true,
                          "fieldType" => 'label',
                          "lastRowlabel" => 'Total'            
                        ],
                        1 => [
                          "key" => "average_amount",
                          "label" => "Average Deal Value",
                          "sortable" => true,
                          "fieldType" => 'currency',
                          "lastRowlabel" => false            
                        ],
                        2 => [
                          "key" => "total_amount",
                          "label" => "Total Value of Deals",
                          "sortable" => true,
                          "fieldType" => 'currency',
                          "lastRowlabel" => false            
                        ],
                        3 => [
                          "key" => "total_deals",
                          "label" => "Total Count of Deals",
                          "sortable" => true,
                          "fieldType" => 'number',
                          "lastRowlabel" => false            
                        ] 
                    ], 
                    'model' => [
                        0 => [
                          "key" => "deal_link",
                          "label" => "Title",
                          "sortable" => true
                        ],            
                        1 => [
                          "key" => "creater",
                          "label" => "Creater",
                          "sortable" => true
                        ],
                        2 => [
                          "key" => "username",
                          "label" => "Owner",
                          "sortable" => true
                        ],
                        3 => [
                          "key" => "organisation",
                          "label" => "Organisation",
                          "sortable" => true
                        ],
                        4 => [
                          "key" => "name",
                          "label" => "Category name",
                          "sortable" => true
                        ], 
                        5 => [
                          "key" => "turnover",
                          "label" => "Value",
                          "sortable" => false,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ],
                        6 => [
                          "key" => "created_at",
                          "label" => "Deal created",
                          "sortable" => true
                        ],
                        7 => [
                          "key" => "deal_status",
                          "label" => "Status",
                          "sortable" => true
                        ],
                        8 => [
                          "key" => "expected_closed_date_formatted",
                          "label" => "Expected close date",
                          "sortable" => true
                        ],
                        9 =>[
                          "key" => "title",
                          "label" => "Title",
                          "sortable" => true,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ], 
                        10 =>[
                          "key" => "deal_price_with_currency",
                          "label" => "Value",
                          "sortable" => true
                        ],
                        11 =>[
                          "key" => "deal_current_stage",
                          "label" => "Current Stage",
                          "sortable" => true
                        ], 
                        12 =>[
                          "key" => "deal_lost_datetime",
                          "label" => "Lost Date Time",
                          "sortable" => true
                        ],    
                        13 =>[
                          "key" => "deal_lost_reason",
                          "label" => "Deal Lost Reason",
                          "sortable" => true
                        ],
                        14 =>[
                          "key" => "notes",
                          "label" => "Deal Lost Reason Notes",
                          "sortable" => true
                        ]                     
                    ]
               ],
              'sql' => [
                'listing' => [
                    'frontend' => [
                      'get' => ['deals.created_at','deals.owner_id','deals.deal_lost_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                      'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','deal_lost_reason','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],                      
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => []
                    ],
                    'export' => [
                      'get' => ['deals.created_at','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                      'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','deal_lost_reason','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],  
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,   
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => []
                    ],
                    'chart' => [
                      'get' => ['deals.owner_id','username','deals.id','reason_id'],
                      'append' => ['deal_lost_reason','converted_value','background_color','reason_background_color','reason_name'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'created_at',
                      ],     
                      'groupByIsDynamic' => false,
                      'groupBy' => ['reason_id'],
                      'groupByUsingCollectionMethod' => true,                      
                      'haveDynamicFields' => false,
                      'chartHasSameAsGetFields' => false,
                      'remmoveNullKey' => true,
                      'requiredFields' => ['reasonName','reasonCount','total_amount','reasonBackgroundColor','total_deals'],
                      'chartDefaultFields' => [
                          'number_of_deals' => [
                            'main' => 'reasonName',
                            'backgroundColor' => 'reasonBackgroundColor',
                            'data' => 'total_deals'
                          ],
                          'price_of_deals' => [
                            // 'dynamic' => false,
                            'main' => 'reasonName',
                            'backgroundColor' => 'reasonBackgroundColor',
                            'data' => 'total_amount'
                            ]
                      ]
                    ]
                  ],  
                'summary' => [
                  'frontend' => [
                    'get' => ['deals.owner_id','username','deals.status','creator_id','pipeline_stage','deals.id','reason_id','notes'],
                    'append' => ['deal_lost_reason','converted_value','background_color'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => ['username'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => true,
                    'remmoveNullKey' => false,
                    'requiredFields' => ['reasonName','reasonCount','total_amount','reasonBackgroundColor','total_deals','username','average_amount'],
                   
                  ],
                  'export' => [ 
                    'get' => [],
                    'append' => [],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],     
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => []                    
                  ],
                  'chart' => [
                    'get' => ['deals.owner_id','username','deals.id','reason_id'],
                    'append' => ['deal_lost_reason','converted_value','background_color','reason_background_color','reason_name'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ], 
                    'groupByIsDynamic' => false,
                    'groupBy' => ['reason_id'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => true,
                    'chartHasSameAsGetFields' => false,
                    'remmoveNullKey' => true,   
                    'requiredFields' => ['reasonName','reasonCount','total_amount','reasonBackgroundColor','total_deals'],                 
                    'chartDefaultFields' => [
                      'number_of_deals' => [
                        'main' => 'reasonName',
                        'backgroundColor' => 'reasonBackgroundColor',
                        'data' => 'total_deals'
                      ],
                      'price_of_deals' => [
                        'main' => 'reasonName',
                        'backgroundColor' => 'reasonBackgroundColor',
                        'data' => 'total_amount'
                        ]
                  ]
                  ]
              ],
              'model' => [ // requestType
                'common' => [ // Common For All Report Types
                  'get' => ['deals.created_at','deals.owner_id','deals.deal_lost_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                  'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','deal_lost_reason','expected_closed_date_formatted'],
                  'with' => [],
                  'join' => [],
                  'defaultDateRange' => [
                    'table' => 'deals',
                    'field' => 'created_at',
                  ],                      
                  'groupByIsDynamic' => false,
                  'groupBy' => null,
                  'groupByUsingCollectionMethod' => false,
                  'haveDynamicFields' => false,
                  'remmoveNullKey' => false,
                  'requiredFields' => []                
                  ],
                  'export' => [
                    'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','reason_id','notes','deal_categories.name'],
                    'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','deal_lost_reason','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'created_at',
                    ],                      
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => []
                    ]
                  ]
              ],
              'export' => [
                'headings' => [
                  'Title',
                  'Creater',
                  'Owner',
                  'Organisation',
                  'Category name',
                  'Value',
                  'Deal created',
                  'Status',
                  'Expected close date',
                  'Deal Current Stage',                  
                  'Deal Lost Reason',
                  'Deal Lost Reason Note',                  
                ],
                'fields' => [
                  'title',
                  'creater',
                  'username',
                  'organisation',
                  'name',
                  'deal_price_with_currency_symbol',
                  'created_at',
                  'deal_status',
                  'expected_closed_date_formatted',
                  'deal_current_stage',
                  'deal_lost_reason',
                  'notes'
                ]
              ],
             'defaultFilterParams' => [
              'field' =>'deals.status',
              'value' => 2,
              'filterType' =>'select',
              'filterField' =>'deals.created_at',
              'isDefaultFilterField' => true,
              'isDynamic' => false,
              'chartSortType' =>'number_of_deals'
             ],
            'chart'=>'pie',
            'isChartStacked'=> false,
            'reportBelongsTo'=> 'deals'
            ],
            'progress' => [
              'fields' => [
                  'listing' => [
                        0 => [
                          "key" => "deal_link",
                          "label" => "Title",
                          "sortable" => true
                        ],            
                        1 => [
                          "key" => "creater",
                          "label" => "Creater",
                          "sortable" => true
                        ],
                        2 => [
                          "key" => "username",
                          "label" => "Owner",
                          "sortable" => true
                        ],
                        3 => [
                          "key" => "organisation",
                          "label" => "Organisation",
                          "sortable" => true
                        ],
                        4 =>[
                          "key" => "name",
                          "label" => "Category name",
                          "sortable" => true
                        ],   
                        5 => [
                          "key" => "turnover",
                          "label" => "Value",
                          "sortable" => false,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ],
                        6 => [
                          "key" => "created_at",
                          "label" => "Deal created",
                          "sortable" => true
                        ],
                        7 => [
                          "key" => "deal_status",
                          "label" => "Status",
                          "sortable" => true
                        ],
                        8 => [
                          "key" => "expected_closed_date_formatted",
                          "label" => "Expected close date",
                          "sortable" => true
                        ],
                        9 =>[
                          "key" => "title",
                          "label" => "Title",
                          "sortable" => true,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ], 
                        10 =>[
                          "key" => "deal_price_with_currency",
                          "label" => "Value",
                          "sortable" => true
                        ],
                        11 =>[
                          "key" => "deal_current_stage",
                          "label" => "Current Stage",
                          "sortable" => true
                        ]            
                    ],
                    'listing' => [
                      0 => [
                        "key" => "deal_link",
                        "label" => "Title",
                        "sortable" => true
                      ],            
                      1 => [
                        "key" => "creater",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "organisation",
                        "label" => "Organisation",
                        "sortable" => true
                      ],
                      4 =>[
                        "key" => "name",
                        "label" => "Category name",
                        "sortable" => true
                      ],
                      5 => [
                        "key" => "turnover",
                        "label" => "Value",
                        "sortable" => false,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ],
                      6 => [
                        "key" => "created_at",
                        "label" => "Deal created",
                        "sortable" => true
                      ],
                      7 => [
                        "key" => "deal_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      8 => [
                        "key" => "expected_closed_date_formatted",
                        "label" => "Expected close date",
                        "sortable" => true
                      ],
                      9 =>[
                        "key" => "title",
                        "label" => "Title",
                        "sortable" => true,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ], 
                      10 =>[
                        "key" => "deal_price_with_currency",
                        "label" => "Value",
                        "sortable" => true
                      ],
                      11 =>[
                        "key" => "deal_current_stage",
                        "label" => "Current Stage",
                        "sortable" => true
                      ]            
                     ],
                  'summary' => [
                        0 => [
                          "key" => "deal_stage_entered_month_year",
                          "label" => "Deal stage entered",
                          "sortable" => true,
                          "fieldType" => 'label',
                          "lastRowlabel" => 'Total'            
                        ]                                     
                    ],
                  'model' => [
                      0 => [
                        "key" => "deal_link",
                        "label" => "Title",
                        "sortable" => true
                      ],            
                      1 => [
                        "key" => "creater",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "organisation",
                        "label" => "Organisation",
                        "sortable" => true
                      ],
                      4 => [
                        "key" => "name",
                        "label" => "Category name",
                        "sortable" => true
                      ],
                      5 => [
                        "key" => "turnover",
                        "label" => "Value",
                        "sortable" => false,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ],
                      6 => [
                        "key" => "created_at",
                        "label" => "Deal created",
                        "sortable" => true
                      ],
                      7 => [
                        "key" => "deal_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      8 => [
                        "key" => "expected_closed_date_formatted",
                        "label" => "Expected close date",
                        "sortable" => true
                      ],
                      9 =>[
                        "key" => "title",
                        "label" => "Title",
                        "sortable" => true,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ], 
                      10 =>[
                        "key" => "deal_price_with_currency",
                        "label" => "Value",
                        "sortable" => true
                      ],
                      11 =>[
                        "key" => "deal_current_stage",
                        "label" => "Current Stage",
                        "sortable" => true
                      ]
                    ]            
               ], 
              'sql' => [
                'listing' => [
                    'frontend' => [
                      'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'stage_details',
                        'field' => 'start_datetime',
                      ],
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => [],
                    ],
                    'export' => [
                      'get' => ['deals.created_at','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'stage_details',
                        'field' => 'start_datetime',
                      ],
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => [],
                    ],
                    'chart' => [
                      'get' => ['deals.owner_id','username','deals.id','stage_details.stage_id','stage_details.start_datetime','stage_details.end_datetime'],
                      'append' => ['background_color','total_deals', 'total_amount','deal_stage','converted_value'],
                      'with' => [],
                      'join' => [
                        'left' => [
                          'secondTable' => 'stage_details', 
                          'secondTableId' =>'stage_details.deal_id', 
                          'operater' => '=', 
                          'mainTable' => 'deals.id'
                        ]
                       ],
                       'defaultDateRange' => [
                          'table' => 'stage_details',
                          'field' => 'start_datetime',
                       ],
                      'groupByIsDynamic' => true,
                      'groupByExtraFields' => [
                        'outerField' => 'start_datetime',                      
                        'format' => 'M Y',
                      ],
                      'groupBy' => ['start_datetime'],
                      'groupByUsingCollectionMethod' => true,                      
                      'haveDynamicFields' => false,
                      'chartHasSameAsGetFields' => false,
                      'remmoveNullKey' => true,
                      'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                      'chartDefaultFields' => [
                          'number_of_deals' => [
                            'dynamic' => true                          
                          ],
                          'group_by_users' => [
                            'dynamic' => true                          
                          ]         
                      ],
                      'multipleChartSortType' => [ // add this parameter if need to change chart type fields too many
                        'number_of_deals' => [
                          'groupBy' => ['start_datetime'],
                          'groupByExtraFields' => [
                            'outerField' => 'start_datetime',                      
                            'format' => 'M Y',
                          ],
                          'groupByUsingCollectionMethod' => true,
                          'defaultFieldsNotAllowed' => true,
                          'groupByIsDynamic' => true,
                          'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                        ],
                        'group_by_users' => [
                          'groupBy' => ['start_datetime'],
                          'groupByExtraFields' => [
                            'outerField' => 'username'                   
                          ],
                          'groupByIsDynamic' => true,
                          'defaultFieldsNotAllowed' => true,
                          'groupByUsingCollectionMethod' => true,
                          'requiredFields' => ['username','stageBackgroundColor','stage','total_deals','dealStages'],
                        ]
                      ]
                    ]
                  ],  
                'summary' => [
                  'frontend' => [
                    'get' => ['deals.owner_id','username','deals.id','stage_details.stage_id','stage_details.start_datetime','stage_details.end_datetime'],
                    'append' => ['background_color','total_deals', 'total_amount','deal_stage','converted_value'],
                    'with' => [],
                    'join' => [
                      'left' => [
                        'secondTable' => 'stage_details', 
                        'secondTableId' =>'stage_details.deal_id', 
                        'operater' => '=', 
                        'mainTable' => 'deals.id'
                      ]
                     ],
                     'defaultDateRange' => [
                        'table' => 'stage_details',
                        'field' => 'start_datetime',
                     ],
                    'groupByIsDynamic' => true,
                    'groupByExtraFields' => [
                      'outerField' => 'start_datetime',                      
                      'format' => 'M Y',
                    ],
                    'groupBy' => ['start_datetime'],
                    'groupByUsingCollectionMethod' => true,                      
                    'haveDynamicFields' => true,
                    'remmoveNullKey' => true,                    
                    'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                    'chartDefaultFields' => [
                        'number_of_deals' => [
                          'dynamic' => true                          
                        ],
                        'group_by_users' => [
                          'dynamic' => true                          
                        ]         
                    ],
                    'multipleChartSortType' => [ // add this parameter if need to change chart type fields too many
                      'number_of_deals' => [
                        'groupBy' => ['start_datetime'],
                        'groupByExtraFields' => [
                          'outerField' => 'start_datetime',                      
                          'format' => 'M Y',
                        ],
                        'groupByUsingCollectionMethod' => true,
                        'defaultFieldsNotAllowed' => true,
                        'groupByIsDynamic' => true,
                        'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                      ],
                      'group_by_users' => [
                        'groupBy' => ['start_datetime'],
                        'groupByExtraFields' => [
                          'outerField' => 'username'                   
                        ],
                        'defaultFieldsNotAllowed' => false,
                        'groupByIsDynamic' => true,
                        'groupByUsingCollectionMethod' => true,
                        'requiredFields' => ['username','stageBackgroundColor','stage','total_deals','dealStages'],
                      ]
                    ]
                  ],
                  'export' => [ 
                    'get' => [],
                    'append' => [],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'stage_details',
                      'field' => 'start_datetime',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => ['username'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],                    
                  ],
                  'chart' => [
                    'get' => ['deals.owner_id','username','deals.id','stage_details.stage_id','stage_details.start_datetime','stage_details.end_datetime'],
                    'append' => ['background_color','total_deals', 'total_amount','deal_stage','converted_value'],
                    'with' => [],
                    'join' => [
                      'left' => [
                        'secondTable' => 'stage_details', 
                        'secondTableId' =>'stage_details.deal_id', 
                        'operater' => '=', 
                        'mainTable' => 'deals.id'
                      ]
                     ],
                     'defaultDateRange' => [
                        'table' => 'stage_details',
                        'field' => 'start_datetime',
                     ],
                    'groupByIsDynamic' => true,
                    'groupByExtraFields' => [
                      'outerField' => 'start_datetime',                      
                      'format' => 'M Y',
                    ],
                    'groupBy' => ['start_datetime'],
                    'groupByUsingCollectionMethod' => true,                      
                    'haveDynamicFields' => false,
                    'chartHasSameAsGetFields' => true,
                    'remmoveNullKey' => true,
                    'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                    'chartDefaultFields' => [
                        'number_of_deals' => [
                          'dynamic' => true                          
                        ],
                        'group_by_users' => [
                          'dynamic' => true                          
                        ]         
                    ],
                    'multipleChartSortType' => [ // add this parameter if need to change chart type fields too many
                      'number_of_deals' => [
                        'groupBy' => ['start_datetime'],
                        'groupByExtraFields' => [
                          'outerField' => 'start_datetime',                      
                          'format' => 'M Y',
                        ],
                        'groupByUsingCollectionMethod' => true,
                        'defaultFieldsNotAllowed' => true,
                        'groupByIsDynamic' => true,
                        'requiredFields' => ['deal_stage_entered_month_year','stageBackgroundColor','stage','total_deals','dealStages'],
                      ],
                      'group_by_users' => [
                        'groupBy' => ['start_datetime'],
                        'groupByExtraFields' => [
                          'outerField' => 'username'                   
                        ],
                        'groupByIsDynamic' => true,
                        'defaultFieldsNotAllowed' => true,
                        'groupByUsingCollectionMethod' => true,
                        'requiredFields' => ['username','stageBackgroundColor','stage','total_deals','dealStages'],
                      ]
                    ]
                  ]
                ],
                'model' => [ // requestType
                  'common' => [
                    'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                    'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [ 
                      'left' => [
                        'secondTable' => 'stage_details', 
                        'secondTableId' =>'stage_details.deal_id', 
                        'operater' => '=', 
                        'mainTable' => 'deals.id'
                      ]
                     ],
                    'defaultDateRange' => [
                      'table' => 'stage_details',
                      'field' => 'start_datetime',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => 'deals.id',
                    'groupByUsingCollectionMethod' => false, // For Sql No Collection
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],   
                  ],
                  'export' => [
                    'get' => ['deals.created_at','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                    'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [ 
                      'left' => [
                        'secondTable' => 'stage_details', 
                        'secondTableId' =>'stage_details.deal_id', 
                        'operater' => '=', 
                        'mainTable' => 'deals.id'
                      ]
                     ],
                    'defaultDateRange' => [
                      'table' => 'stage_details',
                      'field' => 'start_datetime',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => 'deals.id',
                    'groupByUsingCollectionMethod' => false, // For Sql No Collection
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],   
                    ]
                  ]
              ],
              'export' => [
                'headings' => [
                  'Title',
                  'Creater',
                  'Owner',
                  'Organisation',
                  'Category name',
                  'Value',
                  'Deal created',
                  'Status',
                  'Expected close date',
                  'Deal Current Stage'                  
                ],
                'fields' => [
                  'title',
                  'creater',
                  'username',
                  'organisation',
                  'name',
                  'deal_price_with_currency_symbol',
                  'created_at',
                  'deal_status',
                  'expected_closed_date_formatted',
                  'deal_current_stage'
                ]
              ],
            'defaultFilterParams' => [],
            'chart'=>'verticalStacked',
            'isChartStacked'=> true,
            'reportBelongsTo'=> 'deals'
            ],
            'won-over-time' => [
              'fields' => [
                  'listing' => [
                        0 => [
                          "key" => "deal_link",
                          "label" => "Title",
                          "sortable" => true
                        ],            
                        1 => [
                          "key" => "creater",
                          "label" => "Creater",
                          "sortable" => true
                        ],
                        2 => [
                          "key" => "username",
                          "label" => "Owner",
                          "sortable" => true
                        ],
                        3 => [
                          "key" => "organisation",
                          "label" => "Organisation",
                          "sortable" => true
                        ],
                        4 => [
                          "key" => "name",
                          "label" => "Category name",
                          "sortable" => true
                        ],
                        5 => [
                          "key" => "turnover",
                          "label" => "Value",
                          "sortable" => false,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ],
                        6 => [
                          "key" => "created_at",
                          "label" => "Deal created",
                          "sortable" => true
                        ],
                        7 => [
                          "key" => "deal_status",
                          "label" => "Status",
                          "sortable" => true
                        ],
                        8 => [
                          "key" => "expected_closed_date_formatted",
                          "label" => "Expected close date",
                          "sortable" => true
                        ],
                        9 =>[
                          "key" => "title",
                          "label" => "Title",
                          "sortable" => true,
                          "thClass" => "d-none",
                          "tdClass" => "d-none"
                        ], 
                        10 =>[
                          "key" => "deal_price_with_currency",
                          "label" => "Value",
                          "sortable" => true
                        ],
                        11 =>[
                          "key" => "deal_current_stage",
                          "label" => "Current Stage",
                          "sortable" => true
                        ],
                        12 =>[
                          "key" => "deal_won_datetime",
                          "label" => "Won Date Time",
                          "sortable" => true
                        ]              
                    ],
                  'summary' => [
                        0 => [
                          "key" => "deal_won_month_year",
                          "label" => "Won Time",
                          "sortable" => true,
                          "fieldType" => 'label',
                          "lastRowlabel" => 'Total'            
                        ]                  
                    ],
                  'model' => [
                      0 => [
                        "key" => "deal_link",
                        "label" => "Title",
                        "sortable" => true
                      ],            
                      1 => [
                        "key" => "creater",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "username",
                        "label" => "Owner",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "organisation",
                        "label" => "Organisation",
                        "sortable" => true
                      ],
                      4 => [
                        "key" => "name",
                        "label" => "Category name",
                        "sortable" => true
                      ],
                      5 => [
                        "key" => "turnover",
                        "label" => "Value",
                        "sortable" => false,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ],
                      6 => [
                        "key" => "created_at",
                        "label" => "Deal created",
                        "sortable" => true
                      ],
                      7 => [
                        "key" => "deal_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      8 => [
                        "key" => "expected_closed_date_formatted",
                        "label" => "Expected close date",
                        "sortable" => true
                      ],
                      9 =>[
                        "key" => "title",
                        "label" => "Title",
                        "sortable" => true,
                        "thClass" => "d-none",
                        "tdClass" => "d-none"
                      ], 
                      10 =>[
                        "key" => "deal_price_with_currency",
                        "label" => "Value",
                        "sortable" => true
                      ],
                      11 =>[
                        "key" => "deal_current_stage",
                        "label" => "Current Stage",
                        "sortable" => true
                      ],
                      12 =>[
                        "key" => "deal_won_datetime",
                        "label" => "Won Date Time",
                        "sortable" => true
                      ]      
                    ]            
               ], 
              'sql' => [
                'listing' => [
                    'frontend' => [
                      'get' => ['deals.created_at','deals.owner_id','deals.deal_won_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'deal_won_datetime',
                      ],
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => [],
                    ],
                    'export' => [
                      'get' => ['deals.created_at','deals.deal_won_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'deal_won_datetime',
                      ],
                      'groupByIsDynamic' => false,
                      'groupBy' => null,
                      'groupByUsingCollectionMethod' => false,
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => [],
                    ],
                    'chart' => [
                      'get' => ['deals.owner_id','username','deals.id','deals.deal_won_datetime'],
                      'append' => ['background_color','total_deals', 'total_amount','deal_stage','converted_value'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'deal_won_datetime',
                      ],
                      'groupByIsDynamic' => true,
                      'groupByExtraFields' => [
                        'outerField' => 'deal_won_datetime',                      
                        'format' => 'M Y',
                      ],
                      'groupBy' => ['deal_won_datetime'],
                      'groupByUsingCollectionMethod' => true,                      
                      'haveDynamicFields' => false,
                      'chartHasSameAsGetFields' => false,
                      'remmoveNullKey' => true,
                      'requiredFields' => ['total_amount','backgroundColor','total_count_of_deals','users','deal_won_month_year'],
                      'chartDefaultFields' => [
                        'number_of_deals' => [
                          'dynamic' => true                        
                        ],
                        'price_of_deals' => [
                          'dynamic' => true
                        ]                  
                      ]
                    ]
                  ],  
                'summary' => [
                  'frontend' => [
                    'get' => ['deals.owner_id','username','deals.id','deals.deal_won_datetime'],
                    'append' => [],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'deal_won_datetime',
                    ],
                    'groupByIsDynamic' => true,
                    'groupByExtraFields' => [
                      'outerField' => 'deal_won_datetime',                      
                      'format' => 'M Y',
                    ],
                    'groupBy' => ['deal_won_datetime'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => true,
                    'remmoveNullKey' => true,
                    'requiredFields' => ['total_amount','total_count_of_deals','users','deal_won_month_year']
                  ],
                  'export' => [ 
                    'get' => [],
                    'append' => [],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'deal_won_datetime',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => ['username'],
                    'groupByUsingCollectionMethod' => true,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],                    
                  ],
                  'chart' => [
                    'get' => ['deals.owner_id','username','deals.id','deals.deal_won_datetime'],
                    'append' => ['total_amount','backgroundColor','total_deals','users'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'deal_won_datetime',
                    ],
                    'groupByIsDynamic' => true,
                    'groupByExtraFields' => [
                      'outerField' => 'deal_won_datetime',                      
                      'format' => 'M Y',
                    ],
                    'groupBy' => ['deal_won_datetime'],
                    'haveDynamicFields' => true,
                    'chartHasSameAsGetFields' => false,
                    'groupByUsingCollectionMethod' => true,
                    'remmoveNullKey' => true,
                    'requiredFields' =>  ['total_amount','backgroundColor','total_count_of_deals','users','deal_won_month_year'],
                    'chartDefaultFields' => [
                      'number_of_deals' => [ // Keep Dynaimnc True If You want to customize the Chart Data
                        'dynamic' => true                        
                      ],
                      'price_of_deals' => [
                        'dynamic' => true
                      ]                  
                    ]
                  ]
                ],
                'model' => [ // requestType
                  'common' => [
                    'get' => ['deals.created_at','deals.owner_id','deals.deal_won_datetime','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                    'append' => ['deal_link','deal_status','creater','deal_price_with_currency','deal_current_stage','expected_closed_date_formatted'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deals',
                      'field' => 'deal_won_datetime',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => 'deals.id',
                    'groupByUsingCollectionMethod' => false, // if for Sql Collection then false else true
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],   
                  ],
                  'export' => [
                      'get' => ['deals.created_at','deals.deal_won_datetime','deals.owner_id','title','username','organisation','turnover','expected_close_date','deals.id','deals.status','creator_id','pipeline_stage','deal_categories.name'],
                      'append' => ['deal_status','creater','deal_current_stage','deal_price_with_currency_symbol','expected_closed_date_formatted'],
                      'with' => [],
                      'join' => [],
                      'defaultDateRange' => [
                        'table' => 'deals',
                        'field' => 'deal_won_datetime',
                      ],
                      'groupByIsDynamic' => false,
                      'groupBy' => 'deals.id',
                      'groupByUsingCollectionMethod' => false, // For Sql No Collection
                      'haveDynamicFields' => false,
                      'remmoveNullKey' => false,
                      'requiredFields' => [],   
                      ]
                  ],
              ],
              'export' => [
                'headings' => [
                  'Title',
                  'Creater',
                  'Owner',
                  'Organisation',
                  'Category name',
                  'Value',
                  'Deal created',
                  'Status',
                  'Expected close date',
                  'Deal Current Stage',
                  'Won Time'                  
                ],
                'fields' => [
                  'title',
                  'creater',
                  'username',
                  'organisation',
                  'name',
                  'deal_price_with_currency_symbol',
                  'created_at',
                  'deal_status',
                  'expected_closed_date_formatted',
                  'deal_current_stage',
                  'deal_won_datetime'
                ]
              ],
            'defaultFilterParams' => [              
                'field' => 'status',
                'value' => 3,
                'filterType' => 'daterange',
                'filterField' => 'deals.deal_won_datetime',
                'isDefaultFilterField' => true,
                'isDynamic' => false,
                'chartSortType' => 'number_of_deals'
            ],
            'chart'=>'verticalStacked',            
            'isChartStacked'=> true,
            'reportBelongsTo'=> 'deals'
          ],
          'activity' => [
            'fields' => [
                'listing' => [
                      0 => [
                        "key" => "activity_subject",
                        "label" => "Subject",
                        "sortable" => true
                      ],            
                      1 => [
                        "key" => "activity_type",
                        "label" => "Type",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "creater_name",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "username",
                        "label" => "Assigned to user",
                        "sortable" => true
                      ],
                      4 => [
                        "key" => "activity_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      5 => [
                        "key" => "created_at",
                        "label" => "Activity Created Date",
                        "sortable" => true
                      ],
                      6 => [
                        "key" => "timeline_time_in",
                        "label" => "Actvity Start Date",
                        "sortable" => false                    
                      ],
                      7 => [
                        "key" => "timeline_time_out",
                        "label" => "Actvity End Date",
                        "sortable" => true
                      ]                          
                  ],
                'summary' => [
                      0 => [
                        "key" => "activity_status",
                        "label" => "Status",
                        "sortable" => true,
                        "fieldType" => 'label',
                        "lastRowlabel" => 'Total'            
                      ]                  
                  ],
                'model' => [
                      0 => [
                        "key" => "activity_subject",
                        "label" => "Subject",
                        "sortable" => true
                      ],            
                      1 => [
                        "key" => "activity_type",
                        "label" => "Type",
                        "sortable" => true
                      ],
                      2 => [
                        "key" => "creater_name",
                        "label" => "Creater",
                        "sortable" => true
                      ],
                      3 => [
                        "key" => "username",
                        "label" => "Assigned to user",
                        "sortable" => true
                      ],
                      4 => [
                        "key" => "activity_status",
                        "label" => "Status",
                        "sortable" => true
                      ],
                      5 => [
                        "key" => "created_at",
                        "label" => "Activity Created Date",
                        "sortable" => true
                      ],
                      6 => [
                        "key" => "timeline_time_in",
                        "label" => "Actvity Start Date",
                        "sortable" => false                    
                      ],
                      7 => [
                        "key" => "timeline_time_out",
                        "label" => "Actvity End Date",
                        "sortable" => true
                      ]                
                  ]            
             ], 
            'sql' => [
              'listing' => [
                  'frontend' => [
                    'get' => ['deal_activities.activity_subject','deal_activities.activity_type','deal_activities.creator_id','deal_activities.status','deal_activities.created_at','deal_activities.timeline_time_in','deal_activities.timeline_time_out','username','deal_activities.user_id','deal_activities.schedule_status','deal_activities.notes'],
                    'append' => ['activity_status','creater_name'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deal_activities',
                      'field' => 'created_at'
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],
                  ],
                  'export' => [
                    'get' => ['deal_activities.activity_subject','deal_activities.activity_type','deal_activities.creator_id','deal_activities.status','deal_activities.created_at','deal_activities.timeline_time_in','deal_activities.timeline_time_out','username','deal_activities.user_id','deal_activities.schedule_status','deal_activities.notes'],
                    'append' => ['activity_status','creater_name'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deal_activities',
                      'field' => 'created_at'
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false,
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],
                  ],
                  'chart' => [
                    'get' => ['deal_activities.activity_type','deal_activities.status'],
                    'append' => ['activity_background_color','activity_status'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deal_activities',
                      'field' => 'created_at',
                    ],
                    'groupByIsDynamic' => true,
                    'groupByExtraFields' => [
                      'innerField' => 'activity_status'
                    ],
                    'groupBy' => [],
                    'groupByUsingCollectionMethod' => true,
                    // 'goToMapDynamicFuntion' => false,                      
                    'haveDynamicFields' => true,
                    'chartHasSameAsGetFields' => false,
                    'remmoveNullKey' => true,
                    'requiredFields' => ['activity_status','actvity_types','actvity_type','activity_background_color'],
                    'chartDefaultFields' => [
                      'number_of_activities' => [
                        'dynamic' => true
                      ]                  
                    ]
                  ]
                ],  
              'summary' => [
                'frontend' => [
                  'get' => ['deal_activities.activity_type','deal_activities.status'],
                  'append' => ['activity_background_color','activity_status'],
                  'with' => [],
                  'join' => [],
                  'defaultDateRange' => [
                    'table' => 'deal_activities',
                    'field' => 'created_at',
                  ],
                  'groupByIsDynamic' => true,
                  'groupByExtraFields' => [
                    'innerField' => 'activity_status'
                  ],
                  'groupBy' => [],
                  'groupByUsingCollectionMethod' => true,
                  'haveDynamicFields' => true,
                  'remmoveNullKey' => true,
                  'requiredFields' => ['activity_status','actvity_types','actvity_type','activity_background_color','total_count_of_activities'],
                ],
                'export' => [ 
                  'get' => [],
                  'append' => [],
                  'with' => [],
                  'join' => [],
                  'defaultDateRange' => [
                    'table' => 'deals',
                    'field' => 'deal_won_datetime',
                  ],
                  'groupByIsDynamic' => false,
                  'groupBy' => ['username'],
                  'groupByUsingCollectionMethod' => true,
                  'haveDynamicFields' => false,
                  'remmoveNullKey' => false,
                  'requiredFields' => [],                    
                ],
                'chart' => [
                  'get' => ['deal_activities.activity_type','deal_activities.status'],
                  'append' => ['activity_background_color','activity_status'],
                  'with' => [],
                  'join' => [],
                  'defaultDateRange' => [
                    'table' => 'deal_activities',
                    'field' => 'created_at',
                  ],
                  'groupByIsDynamic' => true,
                  'groupByExtraFields' => [
                    'innerField' => 'activity_status'
                  ],
                  'groupBy' => [],
                  'groupByUsingCollectionMethod' => true,
                  // 'goToMapDynamicFuntion' => false,                      
                  'haveDynamicFields' => true,
                  'chartHasSameAsGetFields' => false,
                  'remmoveNullKey' => true,
                  'requiredFields' => ['activity_status','actvity_types','actvity_type','activity_background_color'],
                  'chartDefaultFields' => [
                    'number_of_activities' => [
                      'dynamic' => true
                    ]                  
                  ]
                ]
              ],
              'model' => [ // requestType
                'common' => [
                  'get' => ['deal_activities.activity_subject','deal_activities.activity_type','deal_activities.creator_id','deal_activities.status','deal_activities.created_at','deal_activities.timeline_time_in','deal_activities.timeline_time_out','username','deal_activities.user_id','deal_activities.schedule_status','deal_activities.notes'],
                  'append' => ['activity_status','creater_name'],
                  'with' => [],
                  'join' => [],
                  'defaultDateRange' => [
                    'table' => 'deal_activities',
                    'field' => 'created_at',
                  ],
                  'groupByIsDynamic' => false,
                  'groupBy' => null,
                  'groupByUsingCollectionMethod' => false, // if for Sql Collection then false else true
                  'haveDynamicFields' => false,
                  'remmoveNullKey' => false,
                  'requiredFields' => [],   
                ],
                'export' => [
                  'get' => ['deal_activities.activity_subject','deal_activities.activity_type','deal_activities.creator_id','deal_activities.status','deal_activities.created_at','deal_activities.timeline_time_in','deal_activities.timeline_time_out','username','deal_activities.user_id','deal_activities.schedule_status','deal_activities.notes'],
                  'append' => ['activity_status','creater_name'],
                    'with' => [],
                    'join' => [],
                    'defaultDateRange' => [
                      'table' => 'deal_activities',
                      'field' => 'created_at',
                    ],
                    'groupByIsDynamic' => false,
                    'groupBy' => null,
                    'groupByUsingCollectionMethod' => false, // For Sql No Collection
                    'haveDynamicFields' => false,
                    'remmoveNullKey' => false,
                    'requiredFields' => [],   
                    ]
                ],
            ],
            'export' => [
              'headings' => [
                'Subject',
                'Type',
                'Creater',
                'Assigned to user',
                'Status',
                'Activity Created Date',
                'Actvity Start Date',
                'Actvity End Date'                
              ],
              'fields' => [
                'activity_subject',
                'activity_type',
                'creater_name',
                'username',
                'activity_status',
                'created_at',
                'timeline_time_in',
                'timeline_time_out'
              ]
            ],
          'defaultFilterParams' => [              
              'field' => 'deal_activities.status',
              'value' => [0,1],
              'filterType' => 'daterange',
              'filterField' => 'deal_activities.created_at',
              'isDefaultFilterField' => true,
              'isDynamic' => false,
              'chartSortType' => 'number_of_activities'
          ],
          'chart'=>'verticalStacked',            
          'isChartStacked'=> true,
          'reportBelongsTo'=> 'activities'
        ]
        ]
      ],         
        'miscellaneous' => [          
            'date' => [
              'format' => [
                  'database' => 'Y-m-d',
                  'frontend' => 'm/d/y',
                  'model' =>  'datetime:m/d/Y',
                  'activity' =>  'datetime:m/d/Y H:i',
                  'datetime' => [
                      'frontend' => 'm/d/y | h:m:s',
                      'model' =>  'datetime:m/d/Y | h:m:s'
                  ]             
              ],             
              'current_year' => [
                'start_date' => 'Y-01-01',
                'end_date' => 'Y-12-31',
              ],
              'sortBy' => [
                'format' => 'M Y'
              ]
            ],
            'time' => [
              'format' => [
                'database' => 'h:i:s'
                ]
              ],
            'datetime' => [ 
              'format' => [
                'database' => 'Y-m-d H:i:s'
                ]
              ],
            'graphs' => [
                'horizontal_bargraph_data' => [
                  'maxDealsCount' => 10,
                  'multipleValue' => 10,
                  'multipleValueForLargeData' => 1000000
                ],
                'chartSortType'=> [
                  0 => 'number_of_deals',
                  1 => 'price_of_deals',
                  2 => 'number_of_activities',
                  3 => 'group_by_users'
                ],
                'defaultDatasets' => [
                    'barThickness' => 40,
                    'borderWidth' => 1,
                    'fill' => false
                ]
           ],             
          'deal_lost_reasons' => [
              'label' => [
                1 => 'Unresponsive',
                2 => 'No longer interested',
                3 => 'Out of their budget',
                4 => 'Bounce back email',
                5 => 'Went with a competitor',
                6 => 'Other' 
              ],              
              'colour' => [
                1 => '#f8e6d1', // Keep the sorting order same for reasons
                2 => '#edcdcd',
                3 => '#dccaeb',
                4 => '#efefd9',
                5 => '#c4c6ec',
                6 => '#d8d8d8' 
              ]
            ],
            'actvity_types' => [
              'label' => [
                1 => 'Call',
                2 => 'Meeting',
                3 => 'Task',
                4 => 'Deadline',
                5 => 'Email',
                6 => 'Linkedin' 
              ],
              'colour' => [
                1 => '#317ae2',
                2 => '#3F51B5',
                3 => '#673AB7',
                4 => '#00BCD4',
                5 => '#4CAF50',
                6 => '#FF9800'
              ]             
            ],
            'currency' => [
              'default' => [
                'code' => 'GBP',
                'symbol' => '£',
                'price' => 0 // Default Deal Price
              ]
            ],
            'tabs' => [
                'adminReportsTabs' => [ // key Tab Index & Value Tab name
                  0 => 'listing',
                  1 => 'summary'
                ]
            ],
            'reportTypes' =>[
                1 => 'started',
                2 => 'won-over-time',
                3 => 'progress',
                4 => 'duration',
                5 => 'lost-by-reasons',
                6 => 'activity'
            ],
            'activity_status' =>[ // Key Is status Id
                'label' => [
                  0 => 'To do',
                  1 => 'Done'
                ]
           ],
           'reportBelongsTo' => [ 
                1 => 'deals',
                2 => 'activities'
            ],
            'rolesAllowed' => [
              'forSales' => [1,5] // Admin and sales team
            ],
            'dealColors' => [
              'colors' => [
                'default' => '#ffffff', // Default color of deal
                'dealStagnated' => '#f4cbcc',
                'returnToColor' => '#fce5ce', // change to this color after 7 days if color is green i.e #d9ead3
                'greenColor' => '#d9ead3',  // change to returnToColor if this color exists after 7 days.
                'greyColor' => '#D3D3D3' // Every deal color code
              ],
              'colorCodes' => [ '#D3D3D3', '#ffffff','#f4cbcc','#fce5ce','#d9ead3'], 
              'colorLabels' => [ 'Grey','White','Red','Amber','Green'],
              'changeDealColorNoOfDays' => [ //No of days
                'dealStagnated' => 14, 
                'dealReturnToColor' => 7
              ]
            ],          
           'defaultValuesOfDeals' => [
                  'deal_organisations_title' => 'N/A',
                  'deal_organisations_deal_color' => '#d9ead3',  // green
                  'deals_status' => 1,
                  'deals_pipeline_stage' => 1,
                  'stage_details_stage_id' => 1,
                  'deal_categories_data_category_id' => 1 // bramdportal
           ],           
            'frontend' => [
              'dealsListingPage' => [
                'export' => [
                    'deals' => [
                      'headings' => [  // Sort Excel Data In any order you want
                          'deal_people_email' => 'Deal Person Email 1',
                          'deal_people_phone' => 'Deal Person Phone 1',
                          'deal_people_email_2' => 'Deal Person Email 2',
                          'deal_people_phone_2' => 'Deal Person Phone 2',
                          'deal_people_email_3' => 'Deal Person Email 3',
                          'deal_people_phone_3' => 'Deal Person Phone 3',
                          'title' => 'Title',
                          'name' => 'Category name',
                          'creater' => 'Creater',
                          'owner' => 'Owner',
                          'organisation' => 'Organisation',
                          'address' => 'Address',
                          'website' => 'Website',
                          'deal_organisation_phone' => 'Organisation Phone',
                          'currency_code' => 'Currency Code',
                          'deal_price_with_currency_symbol' => 'Value',
                          'created_at' => 'Deal created',
                          'deal_status' => 'Status',
                          'deal_won_datetime' => 'Deal Won Datetime',
                          'deal_lost_reason' => 'Deal Lost Reason',
                          'deal_lost_datetime' => 'Deal Lost Datetime',
                          'notes' => 'Deal Lost Reason Note',
                          'source_of_lead' => 'Deal Person Source of Lead',   
                          'expected_closed_date_formatted' => 'Expected close date',
                          'deal_current_stage' => 'Deal Current Stage',
                          'first_name' => 'Deal Person First Name',
                          'last_name' => 'Deal Person Last Name',
                          'deal_color_label' => 'Deal Color'
                      ],
                      'get' => [
                        'deals.id',
                        'deal_organisations.title',
                        'deal_organisations.organisation',
                        'deal_categories.name',
                        'deal_organisations.turnover',
                        'deal_organisations.currency_code',
                        'deal_organisations.expected_close_date',
                        'deal_organisations.address',
                        'deal_organisations.website',
                        'deals.created_at',
                        'deals.owner_id',
                        'deals.creator_id',
                        'deals.status',
                        'deals.deal_won_datetime',
                        'deals.deal_lost_datetime',
                        'deals.pipeline_stage',
                        'deal_people.first_name',
                        'deal_people.last_name',
                        'deal_people.email',
                        'deal_people.phone',
                        'deal_people.source_of_lead',                  
                        'deal_lost_reasons.reason_id',
                        'deal_lost_reasons.notes',
                        'deal_organisations.deal_color',
                      ],
                      'append' => [
                          'creater',
                          'owner',
                          'deal_price_with_currency_symbol',
                          'deal_status',
                          'deal_current_stage',
                          'deal_lost_reason',
                          'deal_organisation_phone',
                          'deal_people_phone',
                          'deal_people_email',
                          'expected_closed_date_formatted',
                          'deal_color_label'
                      ],
                      'dateTimeFields' => [ 'deal_won_datetime', 'deal_lost_datetime' ,'created_at' ],
                      'multipleFields' => [ 
                        'index' => [
                          'deal_people_phone' => ['deal_people_phone','deal_people_phone_2','deal_people_phone_3'],
                          'deal_people_email' => ['deal_people_email','deal_people_email_2','deal_people_email_3']                          
                        ],
                        'fields' => [
                          'deal_people_phone', 'deal_people_email' 
                        ]
                      ]                         
                  ]
              ],
              'import' => [
                'deals' => [
                    'columns' => [ 
                            0 => 'deal_organisations_organisation',
                            1 => 'deal_people_contact_person_first_name',
                            2 => 'deal_title', // deal_organisations title coloumn is deal title 
                            3 => 'deal_people_email',
                            4 => 'deal_people_phone',
                            5 => 'deal_people_source_of_lead',
                            6 => 'deal_organisations_value',
                            7 => 'deal_organisations_currency_code',
                            8 => 'deal_organisations_expected_close_date',
                            9 => 'deal_categories_data_category_id',
                      ],
                    'defaultColumns' => [
                            0 => 'deals_owner_id',
                            1 => 'deals_creater_id',
                            2 => 'deal_organisations_deal_color',
                            3 => 'deal_organisations_deal_color_update_datetime',
                            4 => 'stage_details_stage_id',
                            5 => 'stage_details_start_datetime',
                            6 => 'stage_details_end_datetime',
                            7 => 'deal_people_contact_person_last_name',
                            8 => 'deals_status',
                            9 => 'deals_pipeline_stage',
                      ]
                ]                           
              ]            
            ]
          ],
          'every' => [  // for dropdowns
            'dealOwner' => 0,
            'dealCategory' => 0
          ]
        ]
    ],
    'user' => [
        'status' => [
                 'active' => 1,
                 'inactive' => 0
             ]
    ],       
    'mail_addresses' => [ // For Admin mails
        0 => 'sarabteam08@gmail.com',            
        // 1 => 'scott@brandlab-360.com',
        // 2 => 'qa2.deftsoft@gmail.com',
        // 3 => 'noreply@brandlab-360.com'
    ],
    'charts' => [
      'types' => [
          'horizontal' => [
            'field' => 'horizontalBarGraphData'
          ],
          'verticalStacked' => [
            'field' => 'verticalStackedBarGraphData'
          ],
          'doughnut' => [
            'field' => 'doughnutGraphData'
          ],
          'pie' => [
            'field' => 'pieGraphData'
          ]
      ],
      'names' =>[
        1 => 'horizontal',
        2 => 'verticalStacked',
        3 => 'doughnut',
        4 => 'pie'
      ]        
   ],
  'permissions' => [    // this is not in use as permissions are added dynamically now from database
      'salespipeline' => [
        'changeDealStatus' => [
          'isDynamic' => true,
          'roleIds' => [1]
        ]
      ],
      'projects' => [
        'createProject' => [
          'isDynamic' => false,
          'roleIds' => [1],
          'miscellaneous' => [  //For Creating Empty Project Scott's user id is added here
            'defaultUser' => [
              'id' => 70
            ]
          ]
      ],
      ]
    ],
    'logs' => [
        'activity' => [
            'pagination' => 150
        ]
    ],
    'miscellaneous' => [      
      'permissions' => [
          403 => 'You do not have permission for this action',
          404 => 'Page not found'
      ],
      'currrencies' => [ //  app currrencies
            [
              'name' =>  "UK Pound (GBP)" ,
              'code' =>  "GBP" ,
              'symbol' =>  "£" 
            ],
            [
              'name' =>  "US Dollar (USD)" ,
              'code' =>  "USD" ,
              'symbol' =>  "$" 
            ],
            [
              'name' =>  "EU Euro (EUR)" ,
              'code' =>  "EUR" ,
              'symbol' =>  "€" 
            ]        
      ],
      'defaultText' => [
        'notAvailable' => 'Not-Availabe'
      ],
      'mainUserId' => 69 // For Live website database
    ],
    'chat' => [
        'conversation' => [
            'pagination' => 30
        ]
    ],
    'notifications' => [  // available actions
          'action_slug' => [
              0 => 'deal_created',
              1 => 'deal_updated',
              2 => 'deals_exported',
              3 => 'deals_imported',
              4 => 'deal_ownership_changed',
              5 => 'scheduled_deal_color_update',
              6 => 'reminder_daily_task_update',
              7 => 'deal_note_created',
              8 => 'deal_note_updated',
              9 => 'deal_organisation_updated',
              10 => 'deal_person_details_updated',
              11 => 'deal_files_uploaded',
              12 => 'deal_activity_created',
              13 => 'deal_activity_updated',
              14 => 'deal_activity_deleted',
              15 => 'create_project_request',
              16 => 'create_project_request_approved',
              17 => 'create_project_request_denied',
              18 => 'join_project_request',
              19 => 'join_project_request_approved',
              20 => 'join_project_request_denied',
              21 => 'create_project_of_deal_request',
              22 => 'create_project_of_deal_request_approved',
              23 => 'create_project_of_deal_request_denied',
              24 => 'deal_deleted',
              25 => 'deal_note_deleted',
              26 => 'deal_status_updated',
              27 => 'deal_stage_changed',
              28 => 'deal_invoice_created',
              29 => 'deal_invoice_updated',
              30 => 'deal_invoice_default_fields_updated',
              31 => 'deal_color_changed',
              32 => 'deal_category_update',
              33 => 'asset_created',
              34 => 'asset_updated',
              35 => 'asset_deleted',
              36 => 'project_created',
              37 => 'project_updated',
              38 => 'project_deleted',
              39 => 'task_created',
              40 => 'task_updated',
              41 => 'task_deleted',
              42 => 'user_profile_updated',
              43 => 'user_account_deleted',
              44 => 'user_account_updated',
              45 => 'user_profile_image_updated',
              46 => 'member_added_in_project',
              47 => 'member_removed_from_project',
              48 => 'user_registration_started',
              49 => 'user_registration_confirmed',
              50 => 'user_registration_denied',
              51 => 'user_invited'
          ],
          'group_type' => [ // notification belong to one of the following group types
              0 => 'Deals', 
              1 => 'Reminders',
              2 => 'Requests',
              3 => 'Projects',
              4 => 'Users'
          ]
    ]
    
];


// Group Types Used for permissions

// admin-report-deals 
// deals
