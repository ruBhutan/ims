<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'AcademicCalendar\Mapper\AcademicCalendarMapperInterface' => 'AcademicCalendar\Factory\ZendDbSqlMapperFactory',
			'AcademicCalendar\Service\AcademicCalendarServiceInterface'=> 'AcademicCalendar\Factory\AcademicCalendarServiceFactory',
			'Timetable\Mapper\TimetableMapperInterface' => 'Timetable\Factory\ZendDbSqlMapperFactory',
			'Timetable\Service\TimetableServiceInterface'=> 'Timetable\Factory\TimetableServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'AcademicCalendar' => 'AcademicCalendar\Factory\AcademicCalendarControllerFactory',
			'Timetable' => 'Timetable\Factory\TimetableControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			//Academic Calendar
			'addcalendar' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcalendar[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'addAcademicCalendar',
						),
 				),
 			),
			'editcalendar' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcalendar[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'editAcademicCalendar',
						),
 				),
 			),
			'addacademicevent' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addacademicevent[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'addAcademicEvent',
						),
 				),
 			),
			'editacademicevent' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editacademicevent[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'editAcademicEvent',
						),
 				),
 			),
			'addstaffcalendarevent' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addmycalendarevent[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'addStaffCalendarEvent',
						),
 				),
 			),
			'editstaffcalendarevent' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editstaffcalendarevent[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'AcademicCalendar',
						'action' => 'editStaffCalendarEvent',
						),
 				),
 			),
			//Timetable
			'uploadtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/uploadtimetable[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Timetable',
					'action' => 'uploadTimetable',
 					),
 				),
 			),
            'addtimetabletimings' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addtimetabletimings[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'addTimetableTimings',
						),
 				),
 			),
			'edittimetabletimings' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/edittimetabletimings[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'editTimetableTimings',
						),
 				),
 			),
			'addtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addtimetable[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'addTimetable',
						),
 				),
 			),
			'edittimetable' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/edittimetable[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'editTimetable',
						),
 				),
 			),
			'deletetimetable' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deletetimetable[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'deleteTimetable',
						),
 				),
 			),
			'viewtimetable' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewtimetable[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'viewTimetable',
						),
 				),
 			),
			'viewtutortimetable' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewtutortimetable[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Timetable',
						'action' => 'viewTutorTimetable',
						),
 				),
 			),
      	),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'AcademicCalendar' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);