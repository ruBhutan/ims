<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'StudentAdmission\Mapper\StudentAdmissionMapperInterface' => 'StudentAdmission\Factory\ZendDbSqlMapperFactory',
			'StudentAdmission\Service\StudentAdmissionServiceInterface'=> 'StudentAdmission\Factory\StudentAdmissionServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

		),
	),
	'controllers' => array(
		'factories' => array(
			'StudentAdmission' => 'StudentAdmission\Factory\StudentAdmissionControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'register-new-student' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/register-new-student[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'registerNewStudent',
 					),
 				),
 			), 

 			'registered-student-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/registered-student-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'registeredStudentList',
 					),
 				),
 			),

 			'registered-student-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/registered-student-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'registeredStudentDetails',
 					),
 				),
 			),

 			'reported-student-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/reported-student-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'reportedStudentList',
 					),
 				),
 			),
			
			'studentpersonaldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentpersonaldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentPersonalDetails',
 					),
 				),
 			),

 			'viewstudentpersonaldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentpersonaldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentPersonalDetails',
 					),
 				),
 			),

 			'addstudentpersonaldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentpersonaldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentPersonalDetails',
 					),
 				),
 			),


 			'studentpermanentaddressdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentpermanentaddressdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentPermanentAddressDetails',
 					),
 				),
 			),

 			'viewstudentpermanentaddressdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentpermanentaddressdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentPermanentAddressDetails',
 					),
 				),
 			),

 			'addstudentpermanentaddrdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentpermanentaddrdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentPermanentAddrDetails',
 					),
 				),
 			),

 			'editstudentpermanentaddrdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentpermanentaddrdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentPermanentAddrDetails',
 					),
 				),
 			),

 			'studentrelationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentrelationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentRelationDetails',
 					),
 				),
 			),

 			'viewstudentrelationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentrelationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentRelationDetails',
 					),
 				),
 			),

 			'editstudentrelationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentrelationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentRelationDetails',
 					),
 				),
 			),

 			'deletestudentrelation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletestudentrelation[/:action][/:id][/:stdId]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					'stdId' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'deleteStudentRelation',
 					),
 				),
 			),

 			'editstdinitialrelationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstdinitialrelationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStdInitialRelationDetails',
 					),
 				),
 			),


 			'studentparentdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentparentdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentParentDetails',
 					),
 				),
 			),


 			'viewstudentparentdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentparentdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentParentDetails',
 					),
 				),
 			),


 			'addstudentparentdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentparentdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentParentDetails',
 					),
 				),
 			),


 			'editstudentparentdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentparentdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentParentDetails',
 					),
 				),
 			),


 			'studentguardiandetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentguardiandetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentGuardianDetails',
 					),
 				),
 			),

 			'viewstudentguardiandetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentguardiandetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentGuardianDetails',
 					),
 				),
 			),

 			'updatestudentguardiandetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatestudentguardiandetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateStudentGuardianDetails',
 					),
 				),
 			),


 			'studentpreviousschooldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentpreviousschooldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentPreviousSchoolDetails',
 					),
 				),
 			),


 			'viewstudentpreviousschooldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentpreviousschooldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentPreviousSchoolDetails',
 					),
 				),
 			),


 			'addstudentpreviousschooldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentpreviousschooldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentPreviousSchoolDetails',
 					),
 				),
 			),


 			'editstudentpreviousschooldetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentpreviousschooldetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentPreviousSchoolDetails',
 					),
 				),
 			),


 			'add-new-student-section' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-new-student-section[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addNewStudentSection',
 					),
 				),
 			),

 			'updatenewstudentsection' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatenewstudentsection[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateNewStudentSection',
 					),
 				),
 			),

 			'add-new-student-house' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-new-student-house[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addNewStudentHouse',
 					),
 				),
 			),

 			'updatenewstudenthouse' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatenewstudenthouse[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateNewStudentHouse',
 					),
 				),
 			),

 			'edit-student-section' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-student-section[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentSection',
 					),
 				),
 			),

 			'updateeditedstudentsection' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateeditedstudentsection[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateEditedStudentSection',
 					),
 				),
 			),


 			'edit-student-house' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-student-house[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentHouse',
 					),
 				),
 			),

 			'updateeditedstudenthouse' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateeditedstudenthouse[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateEditedStudentHouse',
 					),
 				),
 			),

 			'register-student-semester' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/register-student-semester[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'registerStudentSemester',
 					),
 				),
 			),

 			'update-student-semester' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/update-student-semester[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateStudentSemester',
 					),
 				),
 			),


 			'semesterreportedstudentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/semesterreportedstudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'semesterReportedStudentList',
 					),
 				),
 			),


 			'updatenotreportedstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatenotreportedstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateNotReportedStudent',
 					),
 				),
 			),


 			'view-student-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-student-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewStudentList',
 					),
 				),
 			),

 			'deletenotreportedstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletenotreportedstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'deleteNotReportedStudent',
 					),
 				),
 			), 

 			'new-registered-student-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/new-registered-student-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'newRegisteredStudentDetails',
 					),
 				),
 			), 

 			'new-registered-student-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/new-registered-student-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'newRegisteredStudentList',
 					),
 				),
 			),

 			'reportnewstudent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/reportnewstudent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'reportNewStudent',
 					),
 				),
 			),

 			'new-reported-student-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/new-reported-student-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'newReportedStudentList',
 					),
 				),
 			),


 			'generatestudentid' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generatestudentid[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'generateStudentId',
 					),
 				),
 			),


 			'report-new-registered-student' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/report-new-registered-student[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'reportNewRegisteredStudent',
 					),
 				),
 			), 


 			'report-new-student' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/report-new-student[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'reportNewStudent',
 					),
 				),
 			),

 			'add-new-student' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-new-student[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addNewStudent',
 					),
 				),
 			), 

 			'add-student-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-student-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentType',
 					),
 				),
 			),

 			'edit-student-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-student-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentType',
 					),
 				),
 			), 

 			'delete-student-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-student-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'deleteStudentType',
 					),
 				),
 			),

 			'add-student-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-student-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addStudentCategory',
 					),
 				),
 			),



 			'downloadstudentexcellist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadstudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'downloadStudentExcelList',
 					),
 				),
 			),


 			'uploadbulkstudentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/uploadbulkstudentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'uploadBulkStudentList',
 					),
 				),
 			),



 			'add-new-house' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-new-house[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'addNewHouse',
 					),
 				),
 			),

 			'edit-house' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-house[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editHouse',
 					),
 				),
 			),

 			'edit-student-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-student-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'editStudentCategory',
 					),
 				),
 			), 

 			'delete-student-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-student-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'delete-student-category',
 					),
 				),
 			),

 			'upload-student-lists' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/upload-student-lists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'uploadStudentLists',
 					),
 				),
 			),

 			'view-new-student-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-new-student-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewNewStudentDetails',
 					),
 				),
 			),

 			'studentlists' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentlists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentLists',
 					),
 				),
 			),

 			'programmechange' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/programmechange[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'programmeChange',
 					),
 				),
 			),

 			'updatestudentchangeprogramme' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatestudentchangeprogramme[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'updateStudentChangeProgramme',
 					),
 				),
 			),

 			'studentprogrammechangedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentprogrammechangedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'studentProgrammeChangedList',
 					),
 				),
 			),

 			'parentportalaccess' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/parentportalaccess[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'parentPortalAccess',
 					),
 				),
 			),

 			'assignfatheraccess' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignfatheraccess[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'assignFatherAccess',
 					),
 				),
 			),

 			'assignmotheraccess' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignmotheraccess[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'assignMotherAccess',
 					),
 				),
 			),

 			'assignguardianaccess' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/assignguardianaccess[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'assignGuardianAccess',
 					),
 				),
 			),

 			'viewparentportalaccessdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewparentportalaccessdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAdmission',
					'action' => 'viewParentPortalAccessDetails',
 					),
 				),
 			),

 		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'StudentAdmission' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);