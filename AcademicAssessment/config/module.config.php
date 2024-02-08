<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'AcademicAssessment\Mapper\AcademicAssessmentMapperInterface' => 'AcademicAssessment\Factory\ZendDbSqlMapperFactory',
			'AcademicAssessment\Service\AcademicAssessmentServiceInterface'=> 'AcademicAssessment\Factory\AcademicAssessmentServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

		),
	),
	'controllers' => array(
		'factories' => array(
			'AcademicAssessment' => 'AcademicAssessment\Factory\AcademicAssessmentControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'deletecompiledassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deletecompiledassessmentmarks[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicAssessment',
						'action' => 'deleteCompiledAssessmentMarks',
						),
 				),
			 ),
			 
			 'deletecompiledassessment' => array(
				'type' => 'segment',
				'options' => array(
				   'route' => '/deletecompiledassessment[/:action][/:id]',
				   'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					   ),
				   'defaults' => array(
					   'controller' => 'AcademicAssessment',
					   'action' => 'deleteCompiledAssessment',
					   ),
				),
			),

			 'viewindividualconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewindividualconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'AcademicAssessment',
					'action' => 'viewIndividualConsolidatedMarks',
 					),
 				),
 			),

			'addrepeatconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addrepeatconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'AcademicAssessment',
					'action' => 'addRepeatConsolidatedMarks',
 					),
 				),
			 ),

			'editreconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editreconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'AcademicAssessment',
					'action' => 'editReConsolidatedMarks',
 					),
 				),
			 ),

			'insertrepeatconsolidatedmark' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/insertrepeatconsolidatedmark[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'AcademicAssessment',
				   'action' => 'insertRepeatConsolidatedMark',
					),
				),
			),

			'updatereconsolidatedmark' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updatereconsolidatedmark[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'AcademicAssessment',
				   'action' => 'updateReConsolidatedMark',
					),
				),
			),

			'addreassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addreassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'AcademicAssessment',
					'action' => 'addReAssessmentMarks',
 					),
 				),
			 ),

			'insertreassessmentmark' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/insertreassessmentmark[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'AcademicAssessment',
				   'action' => 'insertReAssessmentMark',
					),
				),
			),
			 
  		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'AcademicAssessment' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);