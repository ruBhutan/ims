<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Examinations\Mapper\ExaminationsMapperInterface' => 'Examinations\Factory\ZendDbSqlMapperFactory',
			'Examinations\Service\ExaminationsServiceInterface'=> 'Examinations\Factory\ExaminationsServiceFactory',
			'Reassessment\Mapper\ReassessmentMapperInterface' => 'Reassessment\Factory\ZendDbSqlMapperFactory',
			'Reassessment\Service\ReassessmentServiceInterface'=> 'Reassessment\Factory\ReassessmentServiceFactory',
			'RecheckMarks\Mapper\RecheckMarksMapperInterface' => 'RecheckMarks\Factory\ZendDbSqlMapperFactory',
			'RecheckMarks\Service\RecheckMarksServiceInterface'=> 'RecheckMarks\Factory\RecheckMarksServiceFactory',
			'RepeatModules\Mapper\RepeatModulesMapperInterface' => 'RepeatModules\Factory\ZendDbSqlMapperFactory',
			'RepeatModules\Service\RepeatModulesServiceInterface'=> 'RepeatModules\Factory\RepeatModulesServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Examinations' => 'Examinations\Factory\ExaminationsControllerFactory',
			'Reassessment' => 'Reassessment\Factory\ReassessmentControllerFactory',
			'RecheckMarks' => 'RecheckMarks\Factory\RecheckMarksControllerFactory',
			'RepeatModules' => 'RepeatModules\Factory\RepeatModulesControllerFactory',
		),
		'invokables' => array(
			'ExamHall' => 'ExamHall\Controller\ExamHallController',
			'ExamHallArrangement' => 'ExamHallArrangement\Controller\ExamHallArrangementController',
			'EligibleStudent' => 'EligibleStudent\Controller\EligibleStudentController',
			'ExamInvigilator' => 'ExamInvigilator\Controller\ExamInvigilatorController',
			'CodeGeneration' => 'CodeGeneration\Controller\CodeGenerationController',
			'EntryCard' => 'EntryCard\Controller\EntryCardController',
                                                                       
		),
	),
	'router' => array(
 		'routes' => array(
			//Reassessment
			'applyreassessment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyreassessment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Reassessment',
					'action' => 'applyReassessment',
 					),
 				),
 			),
			'listreassessmentapplicants' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listreassessmentapplicants[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Reassessment',
					'action' => 'listReassessmentApplicants',
 					),
 				),
			 ),
			 'updatereassessmentmodulestatus' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updatereassessmentmodulestatus[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Reassessment',
				   'action' => 'updateReassessmentModuleStatus',
					),
				),
			),

			'approvedreassessmentapplicants' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/approvedreassessmentapplicants[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Reassessment',
				   'action' => 'approvedReassessmentApplicants',
					),
				),
			),
			'updateapprovedreassessmentmodulestatus' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updateapprovedreassessmentmodulestatus[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Reassessment',
				   'action' => 'updateApprovedReassessmentModuleStatus',
					),
				),
			),
			'reassessmentapplicationdetails' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/reassessmentapplicationdetails[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Reassessment',
				   'action' => 'reassessmentApplicationDetails',
					),
				),
			),
			//Recheck Module
			'applyforrecheck' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyforrecheck[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'RecheckMarks',
					'action' => 'applyRecheck',
 					),
 				),
 			),
			'listrecheckapplicants' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listrecheckapplicants[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'RecheckMarks',
					'action' => 'listRecheckApplicants',
 					),
 				),
			 ),
			 
			 'updaterecheckmarksstatus' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updaterecheckmarksstatus[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'RecheckMarks',
				   'action' => 'updateRecheckMarksStatus',
					),
				),
			),

			'approvedrecheckapplicants' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/approvedrecheckapplicants[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'RecheckMarks',
				   'action' => 'approvedRecheckApplicants',
					),
				),
			),

			'updateapprovedrecheckmarksstatus' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updateapprovedrecheckmarksstatus[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'RecheckMarks',
				   'action' => 'updateApprovedRecheckMarksStatus',
					),
				),
			),

			'deleteunpaidrecheckapplicant' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteunpaidrecheckapplicant[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'RecheckMarks',
						'action' =>'deleteUnpaidRecheckApplicant',
					),
				),
			),

			'recheckreevaluationmarks' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/recheckreevaluationmarks[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'RecheckMarks',
						'action' =>'recheckReevaluationMarks',
					),
				),
			),


			'updatechangedmarks' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updatechangedmarks[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'RecheckMarks',
						'action' =>'updateChangedMarks',
					),
				),
			),


			'recheckapplicationdetails' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/recheckapplicationdetails[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'RecheckMarks',
				   'action' => 'recheckApplicationDetails',
					),
				),
			),

			//Repeat Modules
			'applyrepeatmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyrepeatmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'RepeatModules',
					'action' => 'applyRepeatModule',
 					),
 				),
 			),
			'listrepeatmoduleapplicants' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listrepeatmoduleapplicants[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'RepeatModules',
					'action' => 'listRepeatModuleApplicants',
 					),
 				),
 			),
			'listrepeatmodulestudents' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listrepeatmodulestudents[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'RepeatModules',
					'action' => 'listRepeatModuleStudents',
 					),
 				),
 			),
			//Examinations
			'addexamhall' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addexamhall[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'addExaminationHall',
 					),
 				),
 			),
			'editexamhall' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editexamhall[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'editExaminationHall',
 					),
 				),
 			),
			'addexamtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addexamtimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'addExaminationTimetable',
 					),
 				),
 			),
			'editexamtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editexamtimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'editExaminationTimetable',
 					),
 				),
 			),
			'viewexamtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewexamtimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'viewExaminationTimetable',
 					),
 				),
 			),
			'examhallarrangement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/examhallarrangement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'examHallArrangement',
 					),
 				),
 			),
			'assignexaminvigilator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignexaminvigilator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'assignExamInvigilator',
 					),
 				),
 			),
			'editassignexaminvigilator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editassignexaminvigilator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'editAssignExamInvigilator',
 					),
 				),
 			),
			'deleteexaminvigilator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteexaminvigilator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'deleteExamInvigilator',
 					),
 				),
 			),
			'eligiblestudentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/eligiblestudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'eligibleStudentList',
 					),
 				),
 			),
			'noneligiblestudentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/noneligiblestudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'nonEligibleStudentList',
 					),
 				),
 			),
			'viewnoneligibilityreasons' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewnoneligibilityreasons[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'viewNonEligibilityReasons',
 					),
 				),
 			),
			'changestudenteligibility' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/changestudenteligibility[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'changeStudentEligibility',
 					),
 				),
 			),
			'generateexamcode' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generateexamcode[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'generateExamCode',
 					),
 				),
 			),
			'viewexamcode' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewexamcode[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'viewExamCode',
 					),
 				),
 			),
			'exammoderation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/exammoderation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'semesterExamModeration',
 					),
 				),
 			),
			'backpapergeneration' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/backpapergeneration[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'backpaperListGeneration',
 					),
 				),
 			),
			//to manually add students with backpaper
			'studentbackpaper' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentbackpaper[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'studentBackPaper',
 					),
 				),
 			),
			'addstudentbackpaper' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentbackpaper[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'addStudentBackPaper',
 					),
 				),
 			),
			//to manually add students with backyear
			'studentbackyear' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentbackyear[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'studentBackYear',
 					),
 				),
 			),
			'addstudentbackyear' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentbackyear[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'addStudentBackYear',
 					),
 				),
			 ),
			 'updaterepeatsemestermodule' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updaterepeatsemestermodule[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Examinations',
				   'action' => 'updateRepeatSemesterModule',
					),
				),
			),
			'declareresults' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/declareresults[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'declareResults',
 					),
 				),
 			),
 			'declarepreviousresult' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/declarepreviousresult[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'declarePreviousResult',
 					),
 				),
 			),
 			'blockresults' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/blockresults[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'blockResults',
 					),
 				),
 			),
 			'addblockstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addblockstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'addBlockStudent',
 					),
 				),
 			),
 			'removeblockstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/removeblockstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'removeBlockStudent',
 					),
 				),
 			),
			'exammoderationbyprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/exammoderationbyprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'semesterExamModerationByProgramme',
 					),
 				),
 			),
			//no need. same as exam code
			'generatesecretcode' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generatesecretcode[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Examinations',
					'action' => 'generateSecretCode',
 					),
 				),
 			),
        ),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'Examination' => __DIR__ . '/../view',
		),
	),
);