<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Hostel\Mapper\HostelMapperInterface' => 'Hostel\Factory\ZendDbSqlMapperFactory',
			'Hostel\Service\HostelServiceInterface'=> 'Hostel\Factory\HostelServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Hostel' => 'Hostel\Factory\HostelControllerFactory',
		),
		'invokables' => array(
			'HostelDetail' => 'HostelDetail\Controller\HostelDetailController',
			'HostelRoom' => 'HostelRoom\Controller\HostelRoomController',
			'HostelAllocation' => 'HostelAllocation\Controller\HostelAllocationController',
			'HostelChange' => 'HostelChange\Controller\HostelChangeController',
                                                                       
		),
	),
	'router' => array(
 		'routes' => array(		                                                   
            'addhosteldetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addhosteldetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'addHostelDetail',
 					),
 				),
 			),
			'edithosteldetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithosteldetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'editHostelDetail',
 					),
 				),
 			),   
           'viewhostelroom' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewhostelroom[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'viewHostelRoom',
 					),
 				),
			 ),
			 
			 'addadditionalhostelroom' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/addadditionalhostelroom[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Hostel',
				   'action' => 'addAdditionalHostelRoom',
					),
				),
			),

			'deleteaddedhostelroom' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/deleteaddedhostelroom[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Hostel',
				   'action' => 'deleteAddedHostelRoom',
					),
				),
			),

			'edithostelroom' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithostelroom[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'editHostelRoom',
 					),
 				),
 			),
            'identifyroom' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/identifyroom[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'identifyRoom',
 					),
 				),
 			),
			'edithostelinventory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithostelinventory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'editHostelInventory',
 					),
 				),
 			),
           'allocatehostel' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatehostel[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'allocateHostel',
 					),
 				),
 			),
            'allocatedhostel' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatedhostel[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'allocatedHostelList',
 					),
 				),
 			),
			'allocatedhosteldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatedhosteldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'allocatedHostelDetails',
 					),
 				),
			 ),

			 'allocatehostelroom' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/allocatehostelroom[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Hostel',
				   'action' => 'allocateHostelRoom',
					),
				),
			),
			 
			 'removehostelallocatedstudent' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/removehostelallocatedstudent[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Hostel',
				   'action' => 'removeHostelAllocatedStudent',
					),
				),
			),

            'hostelchangeapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hostelchangeapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'hostelChangeApplication',
 					),
 				),
 			),
			'edithostelchangeapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithostelchangeapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'editHostelChangeApplication',
 					),
 				),
 			),
            'hostelchangeapplicationlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hostelchangeapplicationlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'hostelChangeApplicationList',
 					),
 				),
 			),
 			'studentselfhostel' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentselfhostel[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Hostel',
					'action' => 'studentSelfHostel',
 					),
 				),
 			),
        ),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'Hostel' => __DIR__ . '/../view',
		),
	),
);