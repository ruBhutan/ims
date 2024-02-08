<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'CounselingService\Mapper\CounselingMapperInterface' => 'CounselingService\Factory\ZendDbSqlMapperFactory',
			'CounselingService\Service\CounselingServiceInterface'=> 'CounselingService\Factory\CounselingServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Counseling' => 'CounselingService\Factory\CounselingControllerFactory',
		),
		'invokables' => array(
			'ApplyCounseling' => 'ApplyCounseling\Controller\ApplyCounselingController',
		),
	),
	'router' => array(
 		'routes' => array(
 			'appointcounselor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/appointcounselor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'appointCounselor',
 					),
 				),
 			),

 			'updatecounselorstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecounselorstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'updateCounselorStatus',
 					),
 				),
 			),

 			'counselingappointment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingappointment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'seekCounselingAppointment',
 					),
 				),
 			),

 			'editcounselingappointment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcounselingappointment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'editIndCounselingAppointment',
 					),
 				),
 			),

 			'viewindcounselingappointment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewindcounselingappointment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewIndCounselingAppointment',
 					),
 				),
 			),

			'grantappointment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/grantappointment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'grantCounselingAppointment',
 					),
 				),
 			),
			'appointmentlists' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/appointmentlists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewSeekingAppointmentLists',
 					),
 				),
 			),
			'appointmentlistdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/appointmentlistdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewCounselingAppointmentDetail',
 					),
 				),
 			),
			'viewappointments' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewappointments[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewCounselingAppointments',
 					),
 				),
 			),
			'recommendcounseling' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recommendcounseling[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'recommendCounseling',
 					),
 				),
 			),
 			'recommendstaffcounseling' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recommendstaffcounseling[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'recommendStaffCounseling',
 					),
 				),
 			),
			'recommendstdcounseling' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recommendstdcounseling[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'recommendStudentCounseling',
 					),
 				),
 			),
			'recommendcounselinglist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recommendcounselinglist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'recommendCounselingList',
 					),
 				),
 			),

 			'editrecommendcounseling' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editrecommendcounseling[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'editRecommendCounseling',
 					),
 				),
 			),

 			'viewrecommendcounselingdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewrecommendcounselingdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewRecommendCounselingDetails',
 					),
 				),
 			),

 			'recommendedcounselinglist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recommendedcounselinglist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'viewRecommendedCounselingList',
 					),
 				),
 			),

 			'grantrecommendedappointment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/grantrecommendedappointment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'grantRecommendedCounselingAppointment',
 					),
 				),
 			),

			'counselingrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'counselingRecord',
 					),
 				),
 			),
			//record for student counseling
			'counselingnotes' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingnotes[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'counselingNotes',
 					),
 				),
 			),
			'listcounselingrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listcounselingrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'counselingRecordList',
 					),
 				),
 			),

 			'downloadcounselingrecorded' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadcounselingrecorded[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'downloadCounselingRecordedFile',
 					),
 				),
 			),

			'counselingrecorddetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingrecorddetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'counselingRecordDetails',
 					),
 				),
 			),

 			'editcounselingrecorddetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editcounselingrecorddetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Counseling',
					'action' => 'editCounselingRecordDetails',
 					),
 				),
 			),

			'counselingapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'counselingapplication',
 					),
 				),
 			), 
                    'forwardcounseling' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/forwardcounseling[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'forwardcounseling',
 					),
 				),
 			),
                    'viewcounselingrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcounselingrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'viewcounselingrecord',
 					),
 				),
 			), 
                    
                      'updatecounselingrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecounselingrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'updatecounselingrecord',
 					),
 				),
 			), 
                       'counselingapplicationfilledform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/counselingapplicationfilledform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'counselingapplicationfilledform',
 					),
 				),
 			),
                     'updatecounselingdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecounselingdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ApplyCounseling',
					'action' => 'updatecounselingdetail',
 					),
 				),
 			),
     
 		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'CounselingService' => __DIR__ . '/../view',
		),
	),
);

