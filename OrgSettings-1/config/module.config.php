<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'OrgSettings\Mapper\OrgSettingsMapperInterface' => 'OrgSettings\Factory\ZendDbSqlMapperFactory',
			'OrgSettings\Service\OrgSettingsServiceInterface'=> 'OrgSettings\Factory\OrgSettingsServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'OrgSettings' => 'OrgSettings\Factory\OrgSettingsControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
 			'orgsettings' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/orgsettings[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'organisationSettings',
 					),
 				),
 			),
			'addorganisation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addorganisation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'addOrganisation',
 					),
 				),
 			),
			'vieworganisation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/vieworganisation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'viewOrganisation',
 					),
 				),
 			), 
			'editorganisation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editorganisation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'editOrganisation',
 					),
 				),
 			), 
            'adddepartment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/adddepartment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'addDepartment',
 					),
 				),
 			),
			'viewdepartment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewdepartment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'viewDepartment',
 					),
 				),
 			),
			'editdepartment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editdepartment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'editDepartment',
 					),
 				),
 			),
			'addunit' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addunit[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'addUnit',
 					),
 				),
 			),
			'viewunit' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewunit[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'viewUnit',
 					),
 				),
 			),
			'editunit' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editunit[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'OrgSettings',
					'action' => 'editUnit',
 					),
 				),
 			),  
 		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'OrgSettings' => __DIR__ . '/../view',
		),
	),
);