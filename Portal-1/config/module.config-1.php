<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'StaffPortal\Mapper\StaffPortalMapperInterface' => 'StaffPortal\Factory\ZendDbSqlMapperFactory',
			'StaffPortal\Service\StaffPortalServiceInterface'=> 'StaffPortal\Factory\StaffPortalServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

			'StudentPortal\Mapper\StudentPortalMapperInterface' => 'StudentPortal\Factory\ZendDbSqlMapperFactory',
			'StudentPortal\Service\StudentPortalServiceInterface'=> 'StudentPortal\Factory\StudentPortalServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

		),
	),
	'controllers' => array(
		'factories' => array(
			'StaffPortal' => 'StaffPortal\Factory\StaffPortalControllerFactory',
			'StudentPortal' => 'StudentPortal\Factory\StudentPortalControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'staffdashboard' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staff-dashboard[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffDashboard',
 					),
 				),
 			), 

 			'staffprofile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffprofile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffProfile',
 					),
 				),
 			),

 			'staffleavestatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffleavestatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffLeaveStatus',
 					),
 				),
 			),

 			'staffrejectedleavedetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffrejectedleavedetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffRejectedLeaveDetails',
 					),
 				),
 			),

 			'stafftourstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stafftourstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffTourStatus',
 					),
 				),
 			),

 			'stafftourdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stafftourdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffTourDetails',
 					),
 				),
 			),

 			'printapprovedtraveldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/printapprovedtraveldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'printApprovedTravelDetails',
 					),
 				),
 			),

 			'staffjobapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffjobapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffJobApplicationStatus',
 					),
 				),
 			),

 			'staffpromotionstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffpromotionstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffPromotionStatus',
 					),
 				),
 			),

 			'staffresignationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffresignationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffResignationStatus',
 					),
 				),
 			),


 			'stafftransferstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stafftransferstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffTransferStatus',
 					),
 				),
 			),


 			'staffattendancerecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffattendancerecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffAttendanceRecord',
 					),
 				),
 			),

 			'staffleaveencashmentstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/staffleaveencashmentstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StaffPortal',
					'action' => 'staffLeaveEncashmentStatus',
 					),
 				),
 			),


 			'studentdashboard' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentdashboard[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentDashboard',
 					),
 				),
 			),

 			'studentprofile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentprofile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentProfile',
 					),
 				),
 			),


 			'studentacademicmodule' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentacademicmodule[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentAcademicModule',
 					),
 				),
 			),

 			'viewacademictimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewacademictimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewAcademicTimetable',
 					),
 				),
 			),

 			'studentrecheckmarkstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentrecheckmarkstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentRecheckMarkStatus',
 					),
 				),
 			),

 			'studentreassessmentstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentreassessmentstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentReassessmentStatus',
 					),
 				),
 			),

 			'studentrepeatmodules' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentrepeatmodules[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentRepeatModules',
 					),
 				),
 			),

 			'viewstudenthosteldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudenthosteldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStudentHostelDetails',
 					),
 				),
 			),

 			'hostelchangeapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hostelchangeapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'hostelChangeApplicationStatus',
 					),
 				),
 			),

 			'studentclubapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentclubapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentClubApplicationStatus',
 					),
 				),
 			),

 			'viewstdclubapplicationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdclubapplicationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStdClubApplicationDetails',
 					),
 				),
 			),

 			'studentclublist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentclublist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentClubList',
 					),
 				),
 			),

 			'viewstudentclubmemberlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentclubmemberlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStudentClubMemberList',
 					),
 				),
 			),

 			'viewstudentclubattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentclubattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStudentClubAttendance',
 					),
 				),
 			),

 			'viewstdextracurricularattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdextracurricularattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStdExtraCurricularAttendance',
 					),
 				),
 			),

 			'viewcounselingappointmentstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcounselingappointmentstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewCounselingAppointmentStatus',
 					),
 				),
 			),

 			'counselingappointmentdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingappointmentdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'counselingAppointmentDetail',
 					),
 				),
 			),

 			'counselingscheduleddetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingscheduleddetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'counselingScheduledDetail',
 					),
 				),
 			),

 			'studentdisciplinaryrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentdisciplinaryrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentDisciplinaryRecord',
 					),
 				),
 			),

 			'studentdisciplinaryrecorddetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentdisciplinaryrecorddetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentDisciplinaryRecordDetails',
 					),
 				),
 			),

 			'studentmedicalrecords' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentmedicalrecords[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentMedicalRecords',
 					),
 				),
 			),

 			'stdmedicalrecorddetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/stdmedicalrecorddetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'stdMedicalRecordDetails',
 					),
 				),
 			),

 			'studentleavestatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentleavestatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'studentLeaveStatus',
 					),
 				),
 			),

 			'viewstudentleavedetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentleavedetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStudentLeaveDetails',
 					),
 				),
 			),

 			'viewstdexamtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstdexamtimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentPortal',
					'action' => 'viewStdExamTimetable',
 					),
 				),
 			),
 		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'Portal' => __DIR__ . '/../view',
		),
	),
);