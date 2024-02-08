<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Planning\Mapper\PlanningMapperInterface' => 'Planning\Factory\ZendDbSqlMapperFactory',
			'Planning\Service\PlanningServiceInterface'=> 'Planning\Factory\PlanningServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Planning' => 'Planning\Factory\PlanningControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			'addfiveyearplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addfiveyearplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addFiveYearPlan',
 					),
 				),
 			),
			'editfiveyearplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editfiveyearplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editFiveYearPlan',
 					),
 				),
 			),
			'addvision' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvision[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVision',
 					),
 				),
 			),
			'editvision' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editvision[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editVision',
 					),
 				),
 			),
			'deletevision' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletevision[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteVision',
 					),
 				),
 			),
			'addmission' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addmission[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addMission',
 					),
 				),
 			),
			'editmission' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmission[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editMission',
 					),
 				),
 			),
			'deletemission' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletemission[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteMission',
 					),
 				),
 			),
			'viewfiveyearplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewfiveyearplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'viewFiveYearPlan',
 					),
 				),
 			),
			'kpi' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/kpi[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'kpi',
 					),
 				),
 			),
			'objectives' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/objectives[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'objectives',
 					),
 				),
 			),
			'editobjectives' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editobjectives[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editObjectives',
 					),
 				),
 			),
			'deleteobjectives' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteobjectives[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteObjectives',
 					),
 				),
 			),

 			'addovcobjectiveweightage' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addovcobjectiveweightage[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addOvcObjectiveWeightage',
 					),
 				),
 			),

 			'editovcobjectiveweightage' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editovcobjectiveweightage[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editOvcObjectiveWeightage',
 					),
 				),
 			),

            'rubactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rubactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addRubActivities',
 					),
 				),
 			),
                        'editrubactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editrubactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editRubActivities',
 					),
 				),
 			),
                        'deleterubactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleterubactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteRubActivities',
 					),
 				),
 			),
			'apadates' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apadates[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addApaDates',
 					),
 				),
 			),
			'editapadates' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editapadates[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editApaDates',
 					),
 				),
 			),
			'activities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/activities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'activities',
 					),
 				),
 			),
 			'editactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editActivities',
 					),
 				),
 			),
			'deleteactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteActivities',
 					),
 				),
 			),
                        //apa for VC
                        //edit and delete of VC activities is same as editacitivities and delete activities
                        'addvcactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvcactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVcActivities',
 					),
 				),
 			),
			'evaluateapa' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/evaluateapa[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'evaluateApa',
 					),
 				),
 			),
                        'evaluatevcapa' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/evaluatevcapa[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'evaluateVcApa',
 					),
 				),
 			),
			'apaselfevaluated' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apaselfevaluated[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'apaSelfEvaluated',
 					),
 				),
 			),
			'successindicator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/successindicator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'successIndicator',
 					),
 				),
 			), 
			'editsuccessindicator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuccessindicator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editSuccessIndicator',
 					),
 				),
 			),
			'deletesuccessindicator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletesuccessindicator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'deleteSuccessIndicator',
 					),
 				),
 			),
            'addvcsuccessindicator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvcsuccessindicator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVcSuccessIndicator',
 					),
 				),
			 ), 
			 
			 'addvckeyaspiration' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/addvckeyaspiration[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Planning',
				   'action' => 'addVcKeyAspiration',
					),
				),
			), 

			'addexecutivekeyaspiration' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/addexecutivekeyaspiration[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Planning',
				   'action' => 'addExecutiveKeyAspiration',
					),
				),
			), 

			'editkeyaspiration' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/editkeyaspiration[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Planning',
				   'action' => 'editKeyAspiration',
					),
				),
			), 

			'addsuccessindicatortrend' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsuccessindicatortrend[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addSuccessIndicatorTrend',
 					),
 				),
 			),
			'editsuccessindicatortrend' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuccessindicatortrend[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editSuccessIndicatorTrend',
 					),
 				),
 			),
                        'addvcsuccessindicatortrend' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvcsuccessindicatortrend[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVcSuccessIndicatorTrend',
 					),
 				),
 			),
			'addsuccessindicatordefinition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsuccessindicatordefinition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addSuccessIndicatorDefinition',
 					),
 				),
 			),
			'editsuccessindicatordefinition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuccessindicatordefinition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editSuccessIndicatorDefinition',
 					),
 				),
 			),
                        'addvcsuccessindicatordefinition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvcsuccessindicatordefinition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVcSuccessIndicatorDefinition',
 					),
 				),
 			),
			'editvcsuccessindicatordefinition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editvcsuccessindicatordefinition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editVcSuccessIndicatorDefinition',
 					),
 				),
 			),
			'addsuccessindicatorrequirements' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsuccessindicatorrequirements[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addSuccessIndicatorRequirements',
 					),
 				),
 			),
			'editsuccessindicatorrequirements' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuccessindicatorrequirements[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editSuccessIndicatorRequirements',
 					),
 				),
 			),
                        'addvcsuccessindicatorrequirements' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addvcsuccessindicatorrequirements[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addVcSuccessIndicatorRequirements',
 					),
 				),
 			),
			'editvcsuccessindicatorrequirements' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editvcsuccessindicatorrequirements[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editVcSuccessIndicatorRequirements',
 					),
 				),
 			),
			'midtermreview' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/midtermreview[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'applyMidTermReview',
 					),
 				),
 			),
                        'vcmidtermreview' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/vcmidtermreview[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'applyVcMidTermReview',
 					),
 				),
 			),
			'addmidtermreview' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addmidtermreview[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addMidTermReview',
 					),
 				),
 			),
                        'budgetoverlay' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/budgetoverlay[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addBudgetOverlay',
 					),
 				),
 			),
                        'editbudgetoverlay' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editbudgetoverlay[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editBudgetOverlay',
 					),
 				),
 			),
                        'organisationbudgetoverlay' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/organisationbudgetoverlay[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'addOrganisationBudgetOverlay',
 					),
 				),
 			),
                        'editorganisationbudgetoverlay' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editorganisationbudgetoverlay[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Planning',
					'action' => 'editOrganisationBudgetOverlay',
 					),
 				),
 			),
            'viewuploadedplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewuploadedplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'UploadPlan',
					'action' => 'viewuploadedplan',
 					),
 				),
 			),  
            'uploadplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/uploadplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'UploadPlan',
					'action' => 'uploadplan',
 					),
 				),
 			),  
            'viewplanmonitoringstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewplanmonitoringstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'PlanMonitoringStatus',
					'action' => 'viewplanmonitoringstatus',
 					),
 				),
 			),
            'updateplanmonitoringstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateplanmonitoringstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'PlanMonitoringStatus',
					'action' => 'updateplanmonitoringstatus',
 					),
 				),
 			),
            'viewplanreviewingstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewplanreviewingstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'PlanReviewingStatus',
					'action' => 'viewplanreviewingstatus',
 					),
 				),
 			),
            'updateplanreviewingstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateplanreviewingstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'PlanReviewingStatus',
					'action' => 'updateplanreviewingstatus',
 					),
 				),
 			),
 		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'Planning' => __DIR__ . '/../view',
		),
	),
);