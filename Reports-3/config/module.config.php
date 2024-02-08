<?php
return array(
	'service_manager'=>array(
		'factories'=> array(
			'Reports\Mapper\ReportsMapperInterface' => 'Reports\Factory\ZendDbSqlMapperFactory',
			'Reports\Service\ReportsServiceInterface'=> 'Reports\Factory\ReportsServiceFactory',
			'StudentReports\Mapper\StudentReportsMapperInterface' => 'StudentReports\Factory\ZendDbSqlMapperFactory',
			'StudentReports\Service\StudentReportsServiceInterface'=> 'StudentReports\Factory\StudentReportsServiceFactory',
			'PlanningReports\Mapper\PlanningReportsMapperInterface' => 'PlanningReports\Factory\ZendDbSqlMapperFactory',
			'PlanningReports\Service\PlanningReportsServiceInterface' => 'PlanningReports\Factory\PlanningReportsServiceFactory',
			'InventoryReports\Mapper\InventoryReportsMapperInterface' => 'InventoryReports\Factory\ZendDbSqlMapperFactory',
			'InventoryReports\Service\InventoryReportsServiceInterface' => 'InventoryReports\Factory\InventoryReportsServiceFactory',

			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Reports' => 'Reports\Factory\ReportsControllerFactory',
			'StudentReports' => 'StudentReports\Factory\StudentReportsControllerFactory',
			'PlanningReports' => 'PlanningReports\Factory\PlanningReportsControllerFactory',
			'InventoryReports' => 'InventoryReports\Factory\InventoryReportsControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			//for generating reports
			'hrplanningreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrplanningreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrPlanningReports',
					),
				),
			),
            'hrrecruitmentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrrecruitmentreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrRecruitmentReports',
					),
				),
			),
                'hradministrationreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hradministrationreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrAdministrationReports',
					),
				),
			),
            'hrdevelopmentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrdevelopmentreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrDevelopmentReports',
					),
				),
			),
			'hrlifecyclereports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrlifecyclereports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrLifeCycleReports',
					),
				),
			),
                    //old function
            'hrcategoryreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrcategoryreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrCategoryReports',
					),
				),
			),
                    //old function
			'hrtrainingreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrtrainingreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'hrTrainingReports',
					),
				),
			),            
			'generatehrreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/generatehrreports[/:reporttype]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'reporttype' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'generateHrReports',
					),
				),
			),
			'studentfeedbackreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/studentfeedbackreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'studentFeedbackReports',
					),
				),
			),
			
			//student reports
			'studentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/studentreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'studentReports',
					),
				),
			),

			'overallstudentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/overallstudentreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'StudentReports',
						'action' =>'OverallStudentReports',
					),
				),
			),
			'yearwisestudentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/yearwisestudentreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'StudentReports',
						'action' =>'yearwiseStudentReports',
					),
				),
			),
			
			'generatestudentreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/generatestudentreports[/:reporttype]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'reporttype' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'StudentReports',
						'action' =>'generateStudentReports',
					),
				),
			),

			//academic reports
			'academicreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/academicreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'academicReports',
					),
				),
			),
			'generateacademicreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/generateacademicreports[/:reporttype]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'reporttype' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'generateAcademicReports',
					),
				),
			),

			'academicresultsreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/academicresultsreports[/:reporttype]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'reporttype' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'academicResultsReports',
					),
				),
			),
			
			//research reports
			'researchreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/researchreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'researchReports',
					),
				),
			),
			'generateresearchreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/generateresearchreports[/:reporttype]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'reporttype' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'Reports',
						'action' =>'generateResearchReports',
					),
				),
			),
			
			//planning reports
			'planningreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/planningreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'PlanningReports',
						'action' =>'planningReports',
					),
				),
			),

			'printcompiledplanningreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/printcompiledplanningreports[/:action][/:report_name][/:organisation][/:position][/:financial_year]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'report_name' => '[a-zA-Z0-9_-]*',
					   'organisation' => '[a-zA-Z0-9_-]*',
					   'position' => '[a-zA-Z0-9_-]*',
					   'financial_year' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'PlanningReports',
						'action' =>'printCompiledPlanningReports',
					),
				),
			),

			//inventory reports
			'inventoryreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/inventoryreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'InventoryReports',
						'action' =>'inventoryReports',
					),
				),
			),
      	),
 	),

	'view_manager' => array(
		'template_path_stack' => array(
		'Reports' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);