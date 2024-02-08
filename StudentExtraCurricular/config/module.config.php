<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Clubs\Mapper\ClubsMapperInterface' => 'Clubs\Factory\ZendDbSqlMapperFactory',
			'Clubs\Service\ClubsServiceInterface'=> 'Clubs\Factory\ClubsServiceFactory',
			'StudentContribution\Mapper\StudentContributionMapperInterface' => 'StudentContribution\Factory\ZendDbSqlMapperFactory',
			'StudentContribution\Service\StudentContributionServiceInterface'=> 'StudentContribution\Factory\StudentContributionServiceFactory',
			'StudentParticipation\Mapper\StudentParticipationMapperInterface' => 'StudentParticipation\Factory\ZendDbSqlMapperFactory',
			'StudentParticipation\Service\StudentParticipationServiceInterface'=> 'StudentParticipation\Factory\StudentParticipationServiceFactory',
			'ExtraCurricularAttendance\Mapper\ExtraCurricularAttendanceMapperInterface' => 'ExtraCurricularAttendance\Factory\ZendDbSqlMapperFactory',
			'ExtraCurricularAttendance\Service\ExtraCurricularAttendanceServiceInterface'=> 'ExtraCurricularAttendance\Factory\ExtraCurricularAttendanceServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Clubs' => 'Clubs\Factory\ClubsControllerFactory',
			'StudentContribution' => 'StudentContribution\Factory\StudentContributionControllerFactory',
			'StudentParticipation' => 'StudentParticipation\Factory\StudentParticipationControllerFactory',
			'ExtraCurricularAttendance' => 'ExtraCurricularAttendance\Factory\ExtraCurricularAttendanceControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(		                                                   
            //Clubs and Units
			'addclub' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addclub[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'addClub',
 					),
 				),
 			),
			'viewclub' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewclub[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'viewClub',
 					),
 				),
 			),
			'editclub' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editclub[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'editClub',
 					),
 				),
 			),
			'applymembership' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applymembership[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'applyClubMembership',
 					),
 				),
 			),
			'addclubmembers' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addclubmembers[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'addClubMembers',
 					),
 				),
 			),
			'clubmembershipstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/clubmembershipstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'viewClubMembershipStatus',
 					),
 				),
 			),
			'clubmembers' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/clubmembers[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'viewClubMembers',
 					),
 				),
 			),
			'approveclubmembers' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approveclubmembers[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'approveClubMembers',
 					),
 				),
 			),
			'rejectclubmembers' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectclubmembers[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Clubs',
					'action' => 'rejectClubMembers',
 					),
 				),
 			),

			//delete club
			'deleteclub' => array(
	                'type' => 'segment',
	                'options' => array(
	                'route' => '/deleteclub[/:action][/:id]',
	                'constraints' => array(
	                     'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
	                     'id' => '[a-zA-Z0-9_-]*',
						),
						//'may_terminate' => true,
	                'defaults' => array(
	                   'controller' => 'Clubs',
	                   'action' =>'deleteClub',
	                    ),
	                ),
	            ),

 			
			//Student Contribution
			'stdcontributioncategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdcontributioncategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'studentContributionCategory',
 					),
 				),
 			),
			'editstdcontributioncategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdcontributioncategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'editStudentContributionCategory',
 					),
 				),
 			),
			'stdcontribution' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdcontribution[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'studentContribution',
 					),
 				),
 			),
			'addstdcontribution' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstdcontribution[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'addStdContribution',
 					),
 				),
 			),
			'viewstdcontribution' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdcontribution[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'viewStdContribution',
 					),
 				),
 			),
			'viewstdcontributiondetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdcontributiondetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'viewStudentContributionDetail',
 					),
 				),
 			),
			'editstdcontribution' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdcontribution[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentContribution',
					'action' => 'editStdContribution',
 					),
 				),
 			),
			//StudentParticipation
			'stdparticipationcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdparticipationcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'studentParticipationCategory',
 					),
 				),
 			),
			'editstdparticipationcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdparticipationcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'editStudentParticipationCategory',
 					),
 				),
 			),
			'stdparticipation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdparticipation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'studentParticipation',
 					),
 				),
 			),
			'addstdparticipation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstdparticipation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'addStdParticipation',
 					),
 				),
 			),
			'viewstdparticipation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdparticipation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'viewStdParticipation',
 					),
 				),
 			),
			'viewstdparticipationdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdparticipationdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'viewStudentParticipationDetail',
 					),
 				),
 			),
			'editstdparticipation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdparticipation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentParticipation',
					'action' => 'editStdParticipation',
 					),
 				),
 			),
			//Extra-Curricular Attendance
           'clubsattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/clubsattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'clubsAttendance',
 					),
 				),
 			),
			'addclubsattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addclubsattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'addClubsAttendance',
 					),
 				),
 			),

 			'editclubsattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editclubsattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'editClubsAttendance',
 					),
 				),
 			),

 			'updateclubsattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateclubsattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'updateClubsAttendance',
 					),
 				),
 			),

 			'viewclubsattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewclubsattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'viewClubsAttendance',
 					),
 				),
 			),

			'addsocialevent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsocialevent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'addSocialEvent',
 					),
 				),
 			),
			'editsocialevent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsocialevent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'editSocialEvent',
 					),
 				),
 			),
		   'studentextracurricular' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentextracurricular[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'studentExtraCurricular',
 					),
 				),
 			),
		   'extracurricularattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/extracurricularattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'addExtraCurricularAttendance',
 					),
 				),
 			),
			'viewextracurricularattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewextracurricularattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'viewExtraCurricularAttendance',
 					),
 				),
 			),
			'editextracurricularattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editextracurricularattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'editExtraCurricularAttendance',
 					),
 				),
 			),  

 			'updateextracurricularattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateextracurricularattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ExtraCurricularAttendance',
					'action' => 'updateExtraCurricularAttendance',
 					),
 				),
 			), 

       ),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'StudentExtraCurricular' => __DIR__ . '/../view',
		),
	),
);