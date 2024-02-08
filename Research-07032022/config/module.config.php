<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'UniversityResearch\Mapper\UniversityResearchMapperInterface' => 'UniversityResearch\Factory\ZendDbSqlMapperFactory',
			'UniversityResearch\Service\UniversityResearchServiceInterface'=> 'UniversityResearch\Factory\UniversityResearchServiceFactory',
			'CollegeResearch\Mapper\CollegeResearchMapperInterface' => 'CollegeResearch\Factory\ZendDbSqlMapperFactory',
			'CollegeResearch\Service\CollegeResearchServiceInterface'=> 'CollegeResearch\Factory\CollegeResearchServiceFactory',
			'ResearchPublication\Mapper\ResearchPublicationMapperInterface' => 'ResearchPublication\Factory\ZendDbSqlMapperFactory',
			'ResearchPublication\Service\ResearchPublicationServiceInterface'=> 'ResearchPublication\Factory\ResearchPublicationServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
		'invokables' => array(
			'ImageManager'=>'ResearchPublication\Service\FileManager',
		),
	),
	'controllers' => array(
		'factories' => array(
			'UniversityResearch' => 'UniversityResearch\Factory\UniversityResearchControllerFactory',
			'CollegeResearch' => 'CollegeResearch\Factory\CollegeResearchControllerFactory',
			'ResearchPublication' => 'ResearchPublication\Factory\ResearchPublicationControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			//Types of Researches
			'researchtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/researchtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'researchType',
 					),
 				),
 			),
			'editresearchtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editresearchtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'editResearchType',
 					),
 				),
 			),
			//research grant announcement
			'researchannouncementforgrant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/researchannouncementforgrant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'researchAnnouncementForGrant',
 					),
 				),
 			),
			'editresearchgrantannouncement' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editresearchgrantannouncement[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'editResearchGrantAnnouncement',
 					),
 				),
 			),
			//Start of University Grants
			'applyuniversitygrant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyuniversitygrant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'applyAurgTitle',
 					),
 				),
 			),
			'aurgprojectdescription' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/aurgprojectdescription[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'applyAurgProjectDescription',
 					),
 				),
 			),
			'aurgactionplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/aurgactionplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'applyAurgActionPlan',
 					),
 				),
 			),
			'listaurggrants' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listaurggrants[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'listAurgGrants',
 					),
 				),
 			),
			'viewaurgapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewaurgapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'viewAurgApplication',
 					),
 				),
 			),
			'drilaurgapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/drilaurgapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'drilAurgApproval',
 					),
 				),
 			),
			'dreraurgapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dreraurgapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'drerAurgApproval',
 					),
 				),
 			),
			'updateuniversitygrant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateuniversitygrant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'updateUniversityGrant',
 					),
 				),
 			),
			'updateaurg' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateaurg[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'updateAurg',
 					),
 				),
 			),
			'grantapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/grantapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'grantApplicationStatus',
 					),
 				),
			 ),
			 'deleteresearchgrantapplication' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/deleteresearchgrantapplication[/:action][/:id][/:type]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					'type' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'UniversityResearch',
				   'action' => 'deleteResearchGrantApplication',
					),
				),
			),
			'viewaurgapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewaurgapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'viewAurgApplicationStatus',
 					),
 				),
 			),
 			'downloadaurgresearchdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadaurgresearchdocument[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'downloadAurgResearchDocument',
 					),
 				),
 			),
			'viewcargapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcargapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'UniversityResearch',
					'action' => 'viewCargApplicationStatus',
 					),
 				),
 			),
			//Start of College Grants
             'applycollegegrant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applycollegegrant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'applyCollegeGrant',
 					),
 				),
 			),
			'cargproject' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/cargproject[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'applyCargProject',
 					),
 				),
 			),
			'cargactionplan' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/cargactionplan[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'applyCargActionPlan',
 					),
 				),
 			),
			'listcarggrants' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listcarggrants[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'listCargGrants',
 					),
 				),
 			),
			'viewcargapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcargapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'viewCargApplication',
 					),
 				),
 			),
			'drilcargapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/drilcargapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'drilCargApproval',
 					),
 				),
 			),
			'updatecollegegrant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecollegegrant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'updateCollegeGrant',
 					),
 				),
 			),
			'updatecarg' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatecarg[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'updateCarg',
 					),
 				),
 			),
			'downloadresearchdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadresearchdocument[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'CollegeResearch',
					'action' => 'downloadResearchDocument',
 					),
 				),
 			),
			//Research Publications
             'applypublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applypublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'applyResearchPublication',
 					),
 				),
 			),
             'viewcollegepublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewcollegepublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'viewCollegePublication',
 					),
 				),
 			),
			 'viewuniversitypublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewuniversitypublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'viewUniversityPublication',
 					),
 				),
 			),
			'collegepublicationdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/collegepublicationdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'viewCollegePublicationDetail',
 					),
 				),
 			),
			 'universitypublicationdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/universitypublicationdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'viewUniversityPublicationDetail',
 					),
 				),
 			),
            'approvepublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvepublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'approveResearchPublication',
 					),
 				),
 			),
             'editpublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editpublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'editResearchPublication',
 					),
 				),
 			),
			'searchpublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvepublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'searchResearchPublication',
 					),
 				),
 			),
			'requestpublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requestpublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'requestPublication',
 					),
 				),
 			),

 			'editrequestpublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editrequestpublication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'editRequestPublication',
 					),
 				),
 			),

			'downloadpublication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'downloadPublication',
 					),
 				),
 			),
			'announceseminar' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/announceseminar[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'announceSeminar',
 					),
 				),
			 ),
			 
			 'editseminarannouncement' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/editseminarannouncement[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'ResearchPublication',
				   'action' => 'editSeminarAnnouncement',
					),
				),
			),
			//publication types
			'addpublicationtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addpublicationtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'addPublicationType',
 					),
 				),
 			),
			'editpublicationtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editpublicationtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'editPublicationType',
 					),
 				),
 			),
			'listpublicationtype' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listpublicationtype[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'listPublicationType',
 					),
 				),
 			),
			'researchpublicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/researchpublicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'researchPublicationStatus',
 					),
 				),
 			),

 			'viewresearchpublicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewresearchpublicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'ResearchPublication',
					'action' => 'viewResearchPublicationStatus',
 					),
 				),
 			),

		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'Research' => __DIR__ . '/../view',
		),
	),
);