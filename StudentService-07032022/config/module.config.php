<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Achievements\Mapper\AchievementsMapperInterface' => 'Achievements\Factory\ZendDbSqlMapperFactory',
			'Achievements\Service\AchievementsServiceInterface'=> 'Achievements\Factory\AchievementsServiceFactory',
			'CharacterCertificate\Mapper\CharacterCertificateMapperInterface' => 'CharacterCertificate\Factory\ZendDbSqlMapperFactory',
			'CharacterCertificate\Service\CharacterCertificateServiceInterface'=> 'CharacterCertificate\Factory\CharacterCertificateServiceFactory',
			'Discipline\Mapper\DisciplineMapperInterface' => 'Discipline\Factory\ZendDbSqlMapperFactory',
			'Discipline\Service\DisciplineServiceInterface'=> 'Discipline\Factory\DisciplineServiceFactory',
			'StudentLeave\Mapper\StudentLeaveMapperInterface' => 'StudentLeave\Factory\ZendDbSqlMapperFactory',
			'StudentLeave\Service\StudentLeaveServiceInterface'=> 'StudentLeave\Factory\StudentLeaveServiceFactory',
			'Responsibilities\Mapper\ResponsibilitiesMapperInterface' => 'Responsibilities\Factory\ZendDbSqlMapperFactory',
			'Responsibilities\Service\ResponsibilitiesServiceInterface'=> 'Responsibilities\Factory\ResponsibilitiesServiceFactory',
			'StudentImage\Mapper\StudentImageMapperInterface' => 'StudentImage\Factory\ZendDbSqlMapperFactory',
			'StudentImage\Service\StudentImageServiceInterface'=> 'StudentImage\Factory\StudentImageServiceFactory',
			'MedicalRecord\Mapper\MedicalRecordMapperInterface' => 'MedicalRecord\Factory\ZendDbSqlMapperFactory',
			'MedicalRecord\Service\MedicalRecordServiceInterface'=> 'MedicalRecord\Factory\MedicalRecordServiceFactory',
			'StudentSuggestions\Mapper\StudentSuggestionsMapperInterface' => 'StudentSuggestions\Factory\ZendDbSqlMapperFactory',
			'StudentSuggestions\Service\StudentSuggestionsServiceInterface'=> 'StudentSuggestions\Factory\StudentSuggestionsServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

			'StudentFeeDetails\Mapper\StudentFeeDetailsMapperInterface' => 'StudentFeeDetails\Factory\ZendDbSqlMapperFactory',
            'StudentFeeDetails\Service\StudentFeeDetailsServiceInterface' => 'StudentFeeDetails\Factory\StudentFeeDetailsServiceFactory',

            'StudentStipend\Mapper\StudentStipendMapperInterface' => 'StudentStipend\Factory\ZendDbSqlMapperFactory',
            'StudentStipend\Service\StudentStipendServiceInterface' => 'StudentStipend\Factory\StudentStipendServiceFactory',
		),
	),
	'controllers' => array(
		'factories' => array(
			'Achievements' => 'Achievements\Factory\AchievementsControllerFactory',
			'CharacterCertificate' => 'CharacterCertificate\Factory\CharacterCertificateControllerFactory',
			'Discipline' => 'Discipline\Factory\DisciplineControllerFactory',
			'StudentLeave' => 'StudentLeave\Factory\StudentLeaveControllerFactory',
			'Responsibilities' => 'Responsibilities\Factory\ResponsibilitiesControllerFactory',
			'StudentImage' => 'StudentImage\Factory\StudentImageControllerFactory',
			'MedicalRecord' => 'MedicalRecord\Factory\MedicalRecordControllerFactory',
			'StudentSuggestions' => 'StudentSuggestions\Factory\StudentSuggestionsControllerFactory',

			'StudentFeeDetails' => 'StudentFeeDetails\Factory\StudentFeeDetailsControllerFactory',

            'StudentStipend' => 'StudentStipend\Factory\StudentStipendControllerFactory',
			
		),
	),
	'router' => array(
 		'routes' => array(
			//Achievements
			'addachievementcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addachievementcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'addAchievementCategory',
 					),
 				),
 			),
			'editachievementcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editachievementcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'editAchievementCategory',
 					),
 				),
 			),
			'studentachievement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentachievement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'studentAchievement',
 					),
 				),
 			),
			'addachievement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addachievement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'addAchievements',
 					),
 				),
 			),
			'viewachievement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewachievement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'viewAchievements',
 					),
 				),
 			),
			'editachievement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editachievement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'editAchievements',
 					),
 				),
 			),
			'viewstudentachievement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentachievement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'viewStudentAchievement',
 					),
 				),
 			),
 			'downloadstdachievementfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadstdachievementfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Achievements',
					'action' => 'downloadStudentAchievementFile',
 					),
 				),
 			),
			//Disciplinary Actions
			'disciplinarycategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/disciplinarycategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'addDisciplinaryCategory',
 					),
 				),
 			),
			'viewdisciplinarycategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewdisciplinarycategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'viewDisciplinaryCategory',
 					),
 				),
 			),
			'editdisciplinarycategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editdisciplinarycategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'editDisciplinaryCategory',
 					),
 				),
 			),
			'disciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/disciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'disciplinaryRecord',
 					),
 				),
 			),
			'adddisciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/adddisciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'addDisciplinaryRecord',
 					),
 				),
 			),
			'viewdisciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewdisciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'viewDisciplinaryRecord',
 					),
 				),
 			),
			'editdisciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editdisciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'editDisciplinaryRecord',
 					),
 				),
 			),
			'addstddisciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstddisciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'addIndividualDisciplinary',
 					),
 				),
 			),
			'viewdisciplinarydetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewdisciplinarydetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Discipline',
					'action' => 'viewIndividualDisciplinaryRecord',
 					),
 				),
 			),
			//Responsibilities
			'responsibilitycategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/responsibilitycategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'addResponsibilityCategory',
 					),
 				),
 			),
			'editresponsibilitycategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editresponsibilitycategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'editResponsibilityCategory',
 					),
 				),
 			),
			'listresponsibilities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listresponsibilities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'listResponsibilityCategory',
 					),
 				),
 			),
			'viewresponsibilities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewresponsibilities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'viewResponsibilityCategory',
 					),
 				),
 			),
			'editresponsibilities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editresponsibilities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'editResponsibilityCategory',
 					),
 				),
 			),
			'studentresponsibility' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentresponsibility[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'studentResponsibility',
 					),
 				),
 			),
			'addstdresponsibility' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstdresponsibility[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'addStudentResponsibility',
 					),
 				),
 			),
			'viewstdresponsibility' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdresponsibility[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'viewStudentResponsibility',
 					),
 				),
 			),
			'stdresponsibilitydetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdresponsibilitydetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'viewStudentResponsibilityDetail',
 					),
 				),
 			),
			'editstdresponsibility' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdresponsibility[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Responsibilities',
					'action' => 'editStudentResponsibility',
 					),
 				),
 			),
			//Student Leave
			'stdleavecategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdleavecategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'addStudentLeaveCategory',
 					),
 				),
 			),
			'viewstdleavecategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdleavecategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'viewLeaveCategory',
 					),
 				),
 			),
			'editstdleavecategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdleavecategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'editLeaveCategory',
 					),
 				),
 			),
			'applystdleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applystdleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'applyStudentLeave',
 					),
 				),
 			),
 			'applystdouting' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applystdouting[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'applyStudentOuting',
 					),
 				),
 			),
			'viewstdleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'viewStudentLeave',
 					),
 				),
 			),
			'editstdleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'viewStudentLeave',
 					),
 				),
 			),

 			'stdleaveapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdleaveapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'stdLeaveApproval',
 					),
 				),
 			),

			'approvestdleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvestdleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'approveStudentLeave',
 					),
 				),
 			),

 			'downloadstdleaveapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadstdleaveapplication[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'downloadStdLeaveApplication',
 					),
 				),
 			),

 			'downloadstdleavefile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadstdleavefile[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'downloadStdLeaveFile',
 					),
 				),
 			),

			'searchstdleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/searchstdleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentLeave',
					'action' => 'searchStudentLeave',
 					),
 				),
 			),
			//Character Certificate
			'identifyccevaluator' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/identifyccevaluator[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'characterCertificateEvaluator',
 					),
 				),
 			),
			'studentccevaluation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentccevaluation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'studentEvaluation',
 					),
 				),
 			),
			'ccevaluation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ccevaluation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'characterCertificateEvaluation',
 					),
 				),
 			),
			'ccgeneration' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ccgeneration[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'characterCertificateGeneration',
 					),
 				),
			 ),
			 'downloadccpdf' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/downloadccpdf[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'CharacterCertificate',
				   'action' => 'downloadCharacterCertificatePdf',
					),
				),
			),
			'viewcharactercert' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcharactercert[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'viewCharacterCertificate',
 					),
 				),
 			),
			'editcharactercert' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcharactercert[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'editCharacterCertificate',
 					),
 				),
 			),

 			'updatecharacterevaluation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecharacterevaluation[/:action][/:id][/:did]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					'did' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'updateCharacterCertificateEvaluation',
 					),
 				),
 			),

			'charactercriteria' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/charactercriteria[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'addCharacterEvaluationCriteria',
 					),
 				),
 			),
			'editcharactercriteria' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcharactercriteria[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CharacterCertificate',
					'action' => 'editCharacterEvaluationCriteria',
 					),
 				),
 			),
			//Medical Records
			'stdmedicalrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdmedicalrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'MedicalRecord',
					'action' => 'studentMedicalRecord',
 					),
 				),
 			),
			'addstdmedicalrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstdmedicalrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'MedicalRecord',
					'action' => 'addMedicalRecord',
 					),
 				),
 			),
			'viewstdmedicalrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdmedicalrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'MedicalRecord',
					'action' => 'viewMedicalRecord',
 					),
 				),
 			),
			'viewindividualmedicalrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewindividualmedicalrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					 'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'MedicalRecord',
					'action' => 'viewIndividualMedicalRecord',
 					),
 				),
 			),
 			'editmedicalrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmedicalrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'MedicalRecord',
					'action' => 'editMedicalRecord',
 					),
 				),
 			),
			//Student Suggestions
			'suggestioncommittee' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/suggestioncommittee[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'addSuggestionCommittee',
 					),
 				),
 			),
			'editsuggestioncommittee' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuggestioncommittee[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'editSuggestionCommittee',
 					),
 				),
 			),

 			'updatesuggestioncommitteememberstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatesuggestioncommitteememberstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'updateSuggestionCommitteeMemberStatus',
 					),
 				),
 			),

			'stdsuggestioncategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdsuggestioncategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'addStudentSuggestionCategory',
 					),
 				),
 			),

 			'editsuggestioncategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsuggestioncategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'editStudentSuggestionCategory',
 					),
 				),
 			),

			'poststdsuggestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/poststdsuggestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'postStudentSuggestion',
 					),
 				),
 			),

 			'studentsuggestiondetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentsuggestiondetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'studentSuggestionDetails',
 					),
 				),
 			),

 			'viewsuggestiontocommittee' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewsuggestiontocommittee[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'viewSuggestionToCommittee',
 					),
 				),
 			),

 			'viewpostedsuggestiondetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewpostedsuggestiondetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'viewPostedSuggestionDetails',
 					),
 				),
 			),

			'viewstdsuggestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdsuggestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentSuggestions',
					'action' => 'viewStudentSuggestion',
 					),
 				),
 			),

			//ProfilePicture
 			'studentprofilepicture' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentprofilepicture[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentImage',
					'action' => 'studentProfilePicture',
 					),
 				),
 			),

 			'addstudentprofilepicture' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentprofilepicture[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentImage',
					'action' => 'addStudentProfilePicture',
 					),
 				),
 			),

 			'student-fees-details' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/student-fees-details[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'StudentFeeDetails',
                        'action' => 'index',
                    ),
                ),
            ),

            'student-stipend' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/student-stipend[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'StudentStipend',
                        'action' => 'index',
                    ),
                    //'may_terminate' => true,
                    'child_routes' => array(
                        'add' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' => '/add[/:action[/:id]]',
                                'constraints' => array(
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                                ),
                            ),
                        ),
                    )
                ),
            ),

		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'StudentService' => __DIR__ . '/../view',
		),
	),
);