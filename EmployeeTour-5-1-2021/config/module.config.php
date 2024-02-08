<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'EmpTravelAuthorization\Mapper\EmpTravelAuthorizationMapperInterface' => 'EmpTravelAuthorization\Factory\ZendDbSqlMapperFactory',
			'EmpTravelAuthorization\Service\EmpTravelAuthorizationServiceInterface'=> 'EmpTravelAuthorization\Factory\EmployeeServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'EmpTravelAuthorization' => 'EmpTravelAuthorization\Factory\EmpTravelAuthorizationControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
 			'emptraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emptraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'empTravelDetails',
 					),
 				),
			 ),
			 
			 'onbehalfemptraveldetails' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/onbehalfemptraveldetails[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'EmpTravelAuthorization',
				   'action' => 'onBehalfEmpTravelDetails',
					),
				),
			),

 			'updatetravelauthorization' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatetravelauthorization[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					//'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'updateTravelAuthorization',
 					),
 				),
			 ),
			 
			 'updateonbehalftravelauthorization' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updateonbehalftravelauthorization[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					//'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'EmpTravelAuthorization',
				   'action' => 'updateOnBehalfTravelAuthorization',
					),
				),
			),

			'edittraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edittraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'editTravelDetails',
 					),
 				),
 			),
			'deletetraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletetraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'deleteTravelDetails',
 					),
 				),
 			),

 			'updateemptraveldetailstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateemptraveldetailstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'updateEmpTravelDetailStatus',
 					),
 				),
 			),

 			'downloadrelatedtourdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadrelatedtourdocument[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'downloadRelatedTourDocument',
 					),
 				),
 			),

			'emptravellist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emptravellist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'empTravelList',
 					),
 				),
 			), 
            'viewtraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'viewTravelDetails',
 					),
 				),
 			),
 			'updateemptraveldetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateemptraveldetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'updateEmpTravelDetail',
 					),
 				),
 			),  

 			'viewemptraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewemptraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'viewEmpTravelDetails',
 					),
 				),
 			),    

             'emptravelauthorization' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emptravelauthorization[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'empTravelAuthorization',
 					),
 				),
			 ), 
			 
			 'onbehalfemptravelauthorization' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/onbehalfemptravelauthorization[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'EmpTravelAuthorization',
				   'action' => 'onBehalfEmpTravelAuthorization',
					),
				),
			), 

 			'emptravelorder' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emptravelorder[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'empTravelOrder',
 					),
 				),
 			), 

 			'updateemptravelorder' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateemptravelorder[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'updateEmpTravelOrder',
 					),
 				),
 			),

 			'viewtravelorderdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtravelorderdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'viewTravelOrderDetails',
 					),
 				),
 			),

 			'downloadtravelorderfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadtravelorderfile[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTravelAuthorization',
					'action' => 'downloadTravelOrderFile',
 					),
 				),
 			),

 		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'EmpTravelAuthorization' => __DIR__ . '/../view',
		),
	),
);