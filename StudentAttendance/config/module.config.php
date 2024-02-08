<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'StudentAttendance\Mapper\StudentAttendanceMapperInterface' => 'StudentAttendance\Factory\ZendDbSqlMapperFactory',
			'StudentAttendance\Service\StudentAttendanceServiceInterface'=> 'StudentAttendance\Factory\StudentAttendanceServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'StudentAttendance' => 'StudentAttendance\Factory\StudentAttendanceControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			'studentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'studentAttendance',
 					),
 				),
 			),
 			'recordmissingstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordmissingstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'recordMissingStudentAttendance',
 					),
 				),
 			),
			'recordstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'recordStudentAttendance',
 					),
 				),
 			), 
			'extraclassattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/extraclassattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'extraClassAttendance',
 					),
 				),
 			),
			'recordextraclassattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordextraclassattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'recordExtraClassAttendance',
 					),
 				),
 			), 
			'cancelledlectures' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/cancelledlectures[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'cancelledLectures',
 					),
 				),
 			),
			'recordcancelledlectures' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordcancelledlectures[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'recordCancelledLectures',
 					),
 				),
 			),
			'editcancelledlectures' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcancelledlectures[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'editCancelledLectures',
 					),
 				),
 			),
			'viewstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'viewStudentAttendance',
 					),
 				),
 			), 
			'editstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'editStudentAttendance',
 					),
 				),
 			),
 			'deletestudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletestudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'deleteStudentAttendance',
 					),
 				),
			 ),
			 'tutordeletestudentattendance' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/tutordeletestudentattendance[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'StudentAttendance',
				   'action' => 'tutorDeleteStudentAttendance',
					),
				),
			),
 			'updatedeletedstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatedeletedstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'updateDeletedStudentAttendance',
 					),
 				),
 			),
			'recordeditedstudentattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordeditedstudentattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'recordEditedStudentAttendance',
 					),
 				),
 			),
			'viewattendancerecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewattendancerecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'viewStudentAttendanceRecord',
 					),
 				),
 			),
			'viewconsolidatedattendancerecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewconsolidatedattendancerecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'viewConsolidatedStudentAttendanceRecord',
 					),
 				),
 			),
			'individualstudentattendancerecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/individualstudentattendancerecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'individualStudentAttendanceRecord',
 					),
 				),
 			),
			'generateconsolidatedattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generateconsolidatedattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'StudentAttendance',
					'action' => 'generateConsolidatedAttendance',
 					),
 				),
 			),
         ),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'StudentAttendance' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);