<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'AcademicAllocation\Mapper\AcademicAllocationMapperInterface' => 'AcademicAllocation\Factory\ZendDbSqlMapperFactory',
			'AcademicAllocation\Service\AcademicAllocationServiceInterface'=> 'AcademicAllocation\Factory\AcademicAllocationServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

		),
	),
	'controllers' => array(
		'factories' => array(
			'AcademicAllocation' => 'AcademicAllocation\Factory\AcademicAllocationControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'allocatedmoduleassessmentcomponent' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/allocatedmoduleassessmentcomponent[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicAllocation',
						'action' => 'allocatedModuleAssessmentComponent',
					),
 				),
			 ),
			 
			 'editmoduleassessmentcomponent' => array(
				'type' => 'segment',
				'options' => array(
				   'route' => '/editmoduleassessmentcomponent[/:action][/:id]',
				   'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					   ),
				   'defaults' => array(
					   'controller' => 'AcademicAllocation',
					   'action' => 'editModuleAssessmentComponent',
				   ),
				),
			),
 		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'AcademicAllocation' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);