<?php
return array(
	'service_manager'=>array(
		'factories'=> array(
			'Vacancy\Mapper\VacancyMapperInterface' => 'Vacancy\Factory\ZendDbSqlMapperFactory',
			'Vacancy\Service\VacancyServiceInterface'=> 'Vacancy\Factory\VacancyServiceFactory',
			'JobApplicant\Mapper\JobApplicantMapperInterface' => 'JobApplicant\Factory\ZendDbSqlMapperFactory',
			'JobApplicant\Service\JobApplicantServiceInterface'=> 'JobApplicant\Factory\JobApplicantServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Vacancy' => 'Vacancy\Factory\VacancyControllerFactory',
			'JobApplicant' => 'JobApplicant\Factory\JobApplicantControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
        	'newapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/newapplicant[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'newapplicant',
 					),
 				),
 			),
			'registeredapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registeredapplicant[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'registeredapplicant',
 					),
 				),
 			),
			'employeedetail' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/employeedetail[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'employeedetail',
 					),
 				),
 			),
			'empidgenerate' => array(
 				'type' => 'segment',
					'options' => array(
					'route' => '/empidgenerate[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'empidgenerate',
 					),
 				),
 			),
 			'applicantapplyjob' => array(
 				'type' => 'segment',
					'options' => array(
					'route' => '/applicantapplyjob[/:action][/:id][/:study_level]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						'study_level' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'applicantApplyJob',
 					),
 				),
 			),
 			'appliedjobapplicationstatus' => array(
 				'type' => 'segment',
					'options' => array(
					'route' => '/appliedjobapplicationstatus[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'appliedJobApplicationStatus',
 					),
 				),
 			),
			//Vacancy Module
 			'listvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'listJobVacancy',
 					),
 				),
 			),
			'announceadhocvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/announceadhocvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'announceAdhocVacancy',
 					),
 				),
 			),
 			'viewannouncedvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewannouncedvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'viewAnnouncedVacancy',
 					),
 				),
 			),
 			'editannouncedadhocvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editannouncedadhocvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'editAnnouncedAdhocVacancy',
 					),
 				),
 			),
 			'closeannouncedadhocvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/closeannouncedadhocvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'closeAnnouncedAdhocVacancy',
 					),
 				),
 			),
			'announceplannedlist' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/announceplannedlist[/:action][/:id]',
					'constraints'=> array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'announcePlannedVacancyList',
 					),
 				),
 			),
			'announceplannedvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/announceplannedvacancy[/:action][/:id]',
					'constraints'=> array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'announcePlannedVacancy',
 					),
 				),
 			),
			'addjobvacancy' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addjobvacancy[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'addjobvacancy',
 					),
 				),
 			),
			'jobdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/jobdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'jobDetails',
 					),
 				),
 			),
			'applyjob' => array(
 				'type' => 'segment',
					'options' => array(
					'route' => '/applyjob[/:action][/:id][/:study_level]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						'study_level' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'applyJob',
 					),
 				),
 			),
			'jobapplicantstatus' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/jobapplicantstatus[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'jobApplicantStatus',
 					),
 				),
 			),
 			'pastjobapplicantstatus' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/pastjobapplicantstatus[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'pastJobApplicantStatus',
 					),
 				),
 			),
 			'jobregistrantdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/jobregistrantdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'jobRegistrantDetails',
 					),
 				),
 			),
 			'editjobregistrantdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editjobregistrantdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'JobApplicant',
					'action' => 'editJobRegistrantDetails',
 					),
 				),
 			),
			'viewjobapplicantdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewjobapplicantdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'viewJobApplicantDetails',
 					),
 				),
 			),
			'downloaduploadeddocuments' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloaduploadeddocuments[/:action][/:id][/:column]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						'column' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'downloadUploadedDocuments',
 					),
 				),
 			),
			'generatejobapplicationpdf' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/generatejobapplicationpdf[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'generateJobApplicationPdf',
 					),
 				),
 			),
			'selectjobapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/selectjobapplicant[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'selectJobApplicant',
 					),
 				),
 			),
			'shortlistjobapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/shortlistjobapplicant[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'shortlistJobApplicant',
 					),
 				),
 			),
			'rejectjobapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/rejectjobapplicant[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'rejectJobApplicant',
 					),
 				),
 			),
			'updateselectedapplicantdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updateselectedapplicantdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'updateSelectedApplicantDetails',
 					),
 				),
 			),
                        'listselectedcandidates' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listselectedcandidates[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'listSelectedCandidates',
 					),
 				),
 			),
                        'updateselectedcandidate' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updateselectedcandidate[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'updateSelectedCandidate',
 					),
 				),
			 ),
			 
			 'viewappliedapplicantmarks' => array(
				'type' => 'segment',
				'options' => array(
				   'route' => '/viewappliedapplicantmarks[/:action][/:id]',
				   'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Vacancy',
				   'action' => 'viewAppliedJobApplicantMarks',
					),
				),
			),

			'editjobapplicantmarkdetails' => array(
				'type' => 'segment',
				'options' => array(
				   'route' => '/editjobapplicantmarkdetails[/:action][/:applicant_id][/:category]',
				   'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'applicant_id' => '[a-zA-Z0-9_-]*',
					   'category' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'Vacancy',
				   'action' => 'editJobApplicantMarkDetails',
					),
				),
			),

            'exportapplicanttoexcel' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/exportapplicanttoexcel[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Vacancy',
					'action' => 'exportApplicantToExcel',
 					),
 				),
 			),

                    
 		),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'CareerOpportunity' => __DIR__ . '/../view',
		),
	),
);