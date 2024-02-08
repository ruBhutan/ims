<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'UniversityAdministration\Mapper\UniversityAdministrationMapperInterface' => 'UniversityAdministration\Factory\ZendDbSqlMapperFactory',
			'UniversityAdministration\Service\UniversityAdministrationServiceInterface'=> 'UniversityAdministration\Factory\UniversityAdministrationServiceFactory',
			'EmployeeTask\Mapper\EmployeeTaskMapperInterface' => 'EmployeeTask\Factory\ZendDbSqlMapperFactory',
			'EmployeeTask\Service\EmployeeTaskServiceInterface'=> 'EmployeeTask\Factory\EmployeeTaskServiceFactory',
			'DocumentFiling\Mapper\DocumentFilingMapperInterface' => 'DocumentFiling\Factory\ZendDbSqlMapperFactory',
			'DocumentFiling\Service\DocumentFilingServiceInterface'=> 'DocumentFiling\Factory\DocumentFilingServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

		),
	),
	'controllers' => array(
		'factories' => array(
			'UniversityAdministration' => 'UniversityAdministration\Factory\UniversityAdministrationControllerFactory',
			'EmployeeTask' => 'EmployeeTask\Factory\EmployeeTaskControllerFactory',
			'DocumentFiling' => 'DocumentFiling\Factory\DocumentFilingControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'rubimsinformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/rubimsinformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'rubimsInformation',
						),
 				),
 			),
 			'jobapplicanthelp' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/jobapplicanthelp[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'jobApplicantHelp',
						),
 				),
 			),

 			'pqcmeetinginformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/pqcmeetinginformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'pqcMeetingInformation',
						),
 				),
 			),

 			'ricmeetinginformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/ricmeetinginformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'ricMeetingInformation',
						),
 				),
 			),

 			'uacmeetinginformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/uacmeetinginformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'uacMeetingInformation',
						),
 				),
 			),

 			'meetingtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/meetingtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'addMeetingType',
 					),
 				),
 			),

 			'editmeetingtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmeetingtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'editMeetingType',
 					),
 				),
 			),

 			
 			'departmentfilingtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/departmentfilingtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'DocumentFiling',
					'action' => 'addDepartmentFilingType',
 					),
 				),
 			),

 			'editdepartmentfilingtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editdepartmentfilingtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'DocumentFiling',
					'action' => 'editDepartmentFilingType',
 					),
 				),
 			),

 			'uploaddepartmentdocuments' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/uploaddepartmentdocuments[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'DocumentFiling',
						'action' => 'uploadDepartmentDocuments',
						),
 				),
 			),

 			

 			'downloadfilingdocuments' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadfilingdocuments[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'DocumentFiling',
					'action' => 'downloadFilingDocuments',
 					),
 				),
 			), 

 			'editfilingdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editfilingdocument[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'DocumentFiling',
					'action' => 'editFilingDocument',
 					),
 				),
 			),
 			'viewfilingdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewfilingdocument[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'DocumentFiling',
					'action' => 'viewFilingDocument',
 					),
 				),
 			),

 			'meetingminuteinformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/meetingminuteinformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'meetingMinuteInformation',
						),
 				),
 			),

 			'editmeetingminute' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editmeetingminute[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'editMeetingMinute',
 					),
 				),
 			),

 			'rubmeetingminuteinformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/rubmeetingminuteinformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'rubmeetingMinuteInformation',
						),
 				),
 			),
 			'newspaperinformation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/newspaperinformation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'UniversityAdministration',
						'action' => 'newspaperInformation',
						),
 				),
 			),

			'downloadmeetingminutes' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadmeetingminutes[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'downloadMeetingMinutes',
 					),
 				),
 			), 			

 			'downloadenglishnewspaper' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadenglishnewspaper[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'downloadEnglishNewsPaper',
 					),
 				),
 			),

 			'downloaddzongkhanewspaper' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloaddzongkhanewspaper[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityAdministration',
					'action' => 'downloadDzongkhaNewsPaper',
 					),
 				),
 			),

 			//Disciplinary Actions
			'employeetaskcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/employeetaskcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'addEmployeeTaskCategory',
 					),
 				),
 			),

 			'viewemployeetaskcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewemployeetaskcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'viewEmployeeTaskCategory',
 					),
 				),
 			),
			'editemployeetaskcategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editemployeetaskcategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'editEmployeeTaskCategory',
 					),
 				),
 			),

 			'employeetaskrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/employeetaskrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'EmployeeTaskRecord',
 					),
 				),
 			),
 			'addemployeetaskrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addemployeetaskrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'addEmployeeTaskRecord',
 					),
 				),
 			),
			'viewemployeetaskrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewemployeetaskrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'viewEmployeeTaskRecord',
 					),
 				),
 			),
 			'addempemployeetaskrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addempemployeetaskrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'addIndividualEmployeeTask',
 					),
 				),
 			),
 			'editempemployeetaskrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editempemployeetaskrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'editIndividualEmployeeTaskRecord',
 					),
 				),
 			),
 			'viewemployeetaskdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewemployeetaskdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'viewIndividualEmployeeTaskRecord',
 					),
 				),
 			),

 			'downloademployeetaskfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloademployeetaskfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeTask',
					'action' => 'downloadEmployeeTaskFile',
 					),
 				),
 			),
			
 		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'UniversityAdministration' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);