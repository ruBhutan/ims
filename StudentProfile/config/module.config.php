<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'StudentProfile\Mapper\StudentProfileMapperInterface' => 'StudentProfile\Factory\ZendDbSqlMapperFactory',
			'StudentProfile\Service\StudentProfileServiceInterface'=> 'StudentProfile\Factory\StudentProfileServiceFactory',
			//'Job\Mapper\JobMapperInterface' => 'Job\Factory\ZendDbSqlMapperFactory',
			//'Job\Service\JobServiceInterface'=> 'Job\Factory\JobServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'StudentProfile' => 'StudentProfile\Factory\StudentProfileControllerFactory',
			//'Job' => 'Job\Factory\JobControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			 'student-lists' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/student-lists[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'StudentProfile',
						'action' =>'studentLists',
					),
				),
			),
			 'student-personal-details' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/student-personal-details[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'StudentProfile',
						'action' =>'studentPersonalDetails',
					),
				),
			),
 		),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'StudentProfile' => __DIR__ . '/../view',
		),
	),
);



