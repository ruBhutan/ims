<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'JobPortal\Mapper\JobPortalMapperInterface' => 'JobPortal\Factory\ZendDbSqlMapperFactory',
			'JobPortal\Service\JobPortalServiceInterface'=> 'JobPortal\Factory\JobPortalServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'JobPortal' => 'JobPortal\Factory\JobPortalControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			'registrantpersonaldetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantpersonaldetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantPersonalDetails',
						),
 				),
 			),
			'editregistrantpersonaldetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantpersonaldetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantPersonalDetails',
						),
 				),
 			),

 			'downloadjobapplicantcid' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadjobapplicantcid[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadJobApplicantCID',
						),
 				),
 			),

			'registranteducationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registranteducationdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantEducationDetails',
						),
 				),
 			),

 			'editregistranteducationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistranteducationdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantEducationDetails',
						),
 				),
 			),

 			'deleteregistranteducationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deleteregistranteducationdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'deleteRegistrantEducationDetails',
						),
 				),
 			),

 			'deleteregistrantemploymentdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deleteregistrantemploymentdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'deleteRegistrantEmploymentDetails',
						),
 				),
 			),

 			'downloadapplicanteducationfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicanteducationfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantEducationFile',
						),
 				),
 			),

			'registranttrainingdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registranttrainingdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantTrainingDetails',
						),
 				),
 			),

 			'editregistranttrainingdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistranttrainingdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantTrainingDetails',
						),
 				),
 			),

 			'downloadapplicanttrainingfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicanttrainingfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantTrainingFile',
						),
 				),
 			),

			'registrantemploymentrecord' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantemploymentrecord[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantEmploymentRecord',
						),
 				),
 			),

 			'editregistrantemploymentrecord' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantemploymentrecord[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantEmploymentRecord',
						),
 				),
 			),

 			'downloadapplicantemploymentfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicantemploymentfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantEmploymentFile',
						),
 				),
 			),

			'registrantmembershipdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantmembershipdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantMembershipDetails',
						),
 				),
 			),

 			'editregistrantmembershipdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantmembershipdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantMembershipDetails',
						),
 				),
 			),

 			'downloadapplicantmembershipfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicantmembershipfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantMembershipFile',
						),
 				),
 			),

			'registrantcommunityservice' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantcommunityservice[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantCommunityService',
						),
 				),
 			),

 			'editregistrantcommunityservice' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantcommunityservice[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantCommunityService',
						),
 				),
 			),

 			'downloadapplicantcommunityfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicantcommunityfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantCommunityServiceFile',
						),
 				),
 			),

			'registrantlanguageskills' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantlanguageskills[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantLanguageSkills',
						),
 				),
 			),

 			'editregistrantlanguageskills' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantlanguageskills[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantLanguageSkills',
						),
 				),
 			),

			'registrantpublicationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantpublicationdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantPublicationDetails',
						),
 				),
 			),

 			'editregistrantpublicationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantpublicationdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantPublicationDetails',
						),
 				),
 			),

			'registrantawards' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantawards[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantAwards',
						),
 				),
 			),
 			'editregistrantawards' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantawards[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantAwards',
						),
 				),
 			),

 			'downloadapplicantawardfile' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/downloadapplicantawardfile[/:filename]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'downloadApplicantAwardFile',
						),
 				),
 			),

			'registrantreferences' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantreferences[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantReferences',
						),
 				),
 			),

 			'editregistrantreferences' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editregistrantreferences[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'editRegistrantReferences',
						),
 				),
 			),

 			'addapplicantmarks' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addapplicantmarks[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'addApplicantMarks',
						),
 				),
 			),

			'registrantdocuments' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/registrantdocuments[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'JobPortal',
						'action' => 'registrantDocuments',
						),
 				),
 			),
      	),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'JobPortal' => __DIR__ . '/../view',
		),
	), // Doctrine config
   'db' => array(
        'username' => 'rub-ims',
        'password' => 'rub-ims-main123@456',
    ),
);
