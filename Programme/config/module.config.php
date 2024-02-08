<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Programme\Mapper\ProgrammeMapperInterface' => 'Programme\Factory\ZendDbSqlMapperFactory',
			'Programme\Service\ProgrammeServiceInterface'=> 'Programme\Factory\ProgrammeServiceFactory',
			'ExternalExaminer\Mapper\ExternalExaminerMapperInterface' => 'ExternalExaminer\Factory\ZendDbSqlMapperFactory',
			'ExternalExaminer\Service\ExternalExaminerServiceInterface'=> 'ExternalExaminer\Factory\ExternalExaminerServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Programme' => 'Programme\Factory\ProgrammeControllerFactory',
			'ExternalExaminer' => 'ExternalExaminer\Factory\ExternalExaminerControllerFactory',
		),
		'invokables' => array(
            'NewProgramme' => 'NewProgramme\Controller\NewProgrammeController',
		),
	),
	'router' => array(
 		'routes' => array(		                                                   
            //External Examiner
			'addexternalexaminer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addexternalexaminer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExternalExaminer',
					'action' => 'addExternalExaminer',
 					),
 				),
 			),
			'editexternalexaminer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editexternalexaminer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExternalExaminer',
					'action' => 'editExternalExaminer',
 					),
 				),
 			),
			'viewexternalexaminer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewexternalexaminer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExternalExaminer',
					'action' => 'viewExternalExaminer',
 					),
 				),
 			),
			'listexternalexaminer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listexternalexaminer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExternalExaminer',
					'action' => 'listExternalExaminer',
 					),
 				),
 			),
 			'downloadexternalexaminerfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadexternalexaminerfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExternalExaminer',
					'action' => 'downloadExternalExaminerFile',
 					),
 				),
 			),
			//Programme
			'addnewprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addnewprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addProgramme',
 					),
 				),
 			),
			'viewprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewProgramme',
 					),
 				),
 			),
			'editprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editProgramme',
 					),
 				),
 			),
			'listprogrammes' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listprogrammes[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'listProgrammes',
 					),
 				),
 			),
			'updateprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'updateProgramme',
 					),
 				),
 			),
			'programmeshistory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/programmeshistory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'programmesHistory',
 					),
 				),
 			),
			'viewprogrammehistory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewprogrammehistory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewProgrammeHistory',
 					),
 				),
 			),
			'downloaddpd' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloaddpd[/:action][/:id][/:category]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					'category' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'downloadDpd',
 					),
 				),
 			),
			'addnewmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addnewmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addModule',
 					),
 				),
 			),
			'viewmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewModule',
 					),
 				),
 			),
			'editmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editModule',
 					),
 				),
 			),
			'updatemodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatemodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'updateModule',
 					),
 				),
 			),
			'assignmodulecoordinator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignmodulecoordinator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assignModuleCoordinator',
 					),
 				),
 			),
			'editmodulecoordinator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editassignmodulecoordinator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editAssignModuleCoordinator',
 					),
 				),
 			),
			'deletemodulecoordinator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletemodulecoordinator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteModuleCoordinator',
 					),
 				),
 			),
			'assignmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assignModule',
 					),
 				),
 			),
			'editmoduletutor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmoduletutor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editModuleTutor',
 					),
 				),
 			),
			'deletemoduletutor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletemoduletutor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteModuleTutor',
 					),
 				),
 			),	
			'viewacademicyeartutor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewacademicyeartutor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewAcademicYearModuleTutor',
 					),
 				),
 			),		
			'deleteacademicyeartutor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteacademicyeartutor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteAcademicYearModuleTutor',
 					),
 				),
 			),
			'assignmoduletotutors' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignmoduletotutors[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assignModuleToTutors',
 					),
 				),
 			),
			'moduletutorassignment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/moduletutorassignment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'moduleTutorAssignment',
 					),
 				),
 			),
           /* 'uploadmoduletutors' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/uploadmoduletutors[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'uploadModuleTutors',
 					),
 				),
 			),*/
            'crosscheckmoduleassignment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/crosscheckmoduleassignment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'crossCheckModuleAssignment',
 					),
 				),
 			),
            'moduleassessmentcomponent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/moduleassessmentcomponent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'moduleAssessmentComponent',
 					),
 				),
 			),
            'assessmentcomponenttype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assessmentcomponenttype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assessmentComponentType',
 					),
 				),
 			),
			//to allcoate modules to an academic year
			'academicyearmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicyearmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'academicYearModule',
 					),
 				),
 			),
			'allocatemoduleacademicyear' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatemoduleacademicyear[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'allocateModuleAcademicYear',
 					),
 				),
 			),
			'editacademicyearmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editacademicyearmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editAcademicYearModule',
 					),
 				),
 			),
			'viewacademicyearmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewacademicyearmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewAcademicYearModule',
 					),
 				),
 			),
			'allocatemissingmodules' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatemissingmodules[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'allocateMissingModules',
 					),
 				),
 			),
			'electivemoduleallocation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/electivemoduleallocation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'electiveModuleAllocation',
 					),
 				),
 			),
			'assignelectivemodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignelectivemodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assignElectiveModule',
 					),
 				),
 			),
			'assessmentcomponent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assessmentcomponent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'assessmentComponent',
 					),
 				),
 			),
			'editassessmentcomponent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editassessmentcomponent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editAssessmentComponent',
 					),
 				),
 			),
			'addassessmentcomponenttypes' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addassessmentcomponenttypes[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addAssessmentComponentType',
 					),
 				),
 			),
			'addallocationmark' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addallocationmark[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addAssessmentMarkAllocation',
 					),
 				),
 			),
			'editallocationmark' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editallocationmark[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editAssessmentMarkAllocation',
 					),
 				),
 			),
 			'deleteassessmentmarkallocation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteassessmentmarkallocation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteAssessmentMarkAllocation',
 					),
 				),
 			),
			'dpdmarkallocation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dpdmarkallocation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addDpdMarkAllocation',
 					),
 				),
 			),
			'editdpdmarkallocation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editdpdmarkallocation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editDpdMarkAllocation',
 					),
 				),
 			),
			'deletedpdmarkallocation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletedpdmarkallocation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteDpdMarkAllocation',
 					),
 				),
 			),
			'allocatedpdmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/allocatedpdmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'allocateDpdMarks',
 					),
 				),
 			),
			'academicassessment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicassessment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'academicAssessment',
 					),
 				),
 			),
			'addassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addAssessmentMarks',
 					),
 				),
 			),
			'viewassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewAssessmentMarks',
 					),
 				),
 			),
			'editassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editAssessmentMarks',
 					),
 				),
 			),
			'editstudentassessmentmark' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentassessmentmark[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editStudentAssessmentMark',
 					),
 				),
 			),
			'updateassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'updateAssessmentMarks',
 					),
 				),
 			),
 			'deleteassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'deleteAssessmentMarks',
 					),
 				),
 			),
			'compileassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/compileassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'compileAssessmentMarks',
 					),
 				),
 			),
			'viewcompiledassessmentmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcompiledassessmentmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewCompiledAssessmentMarks',
 					),
 				),
 			),
			'semesterassessment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/semesterassessment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'semesterAssessment',
 					),
 				),
 			),
			'addsemestermarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsemestermarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'addSemesterMarks',
 					),
 				),
 			),
			'viewsemestermarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewsemestermarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewSemesterMarks',
 					),
 				),
 			),
			'editsemestermarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsemestermarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editSemesterMarks',
 					),
 				),
 			),
			'compilesemestermarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/compilesemestermarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'compileSemesterMarks',
 					),
 				),
 			),
			'viewcompiledsemestermarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcompiledsemestermarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewCompiledSemesterMarks',
 					),
 				),
 			),
			'editcompiledmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcompiledmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'editCompiledMarks',
 					),
 				),
 			),			
			'viewconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewConsolidatedMarks',
 					),
 				),
 			),
            'viewprogrammeconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewprogrammeconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewProgrammeConsolidatedMarks',
 					),
 				),
 			),
            'liststudentconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/liststudentconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'listStudentConsolidatedMarks',
 					),
 				),
 			),
            'viewstudentconsolidatedmarks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentconsolidatedmarks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'viewStudentConsolidatedMarks',
 					),
 				),
 			),
 			'graduatedstudentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/graduatedstudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'graduatedStudentList',
 					),
 				),
 			),

 			'updategraduatedstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updategraduatedstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Programme',
					'action' => 'updateGraduatedStudent',
 					),
 				),
 			),

         ),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'Programme' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);