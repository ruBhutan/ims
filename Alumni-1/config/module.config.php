<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Alumni\Mapper\AlumniMapperInterface' => 'Alumni\Factory\ZendDbSqlMapperFactory',
			'Alumni\Service\AlumniServiceInterface'=> 'Alumni\Factory\AlumniServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

			),
	),
	'controllers' => array(
		'factories' => array(
			'Alumni' => 'Alumni\Factory\AlumniControllerFactory',
		),	
	),
	'router' => array(
 		'routes' => array(
 			'alumninewregistration' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumninewregistration[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniNewRegistration',
 					),
 				),
 			), 

 			'alumnilist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumnilist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniList',
 					),
 				),
 			), 
			
			/*'registered-member-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/registered-member-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'registeredMemberList',
 					),
 				),
 			),

 			'update-alumni' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/update-alumni[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'update-alumni',
 					),
 				),
 			), 

 			'alumni' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumni[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumni',
 					),
 				),
 			),*/

 			'alumnimemberlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumnimemberlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniMemberList',
 					),
 				),
 			),

 			'createalumnievent' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/createalumnievent[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'createAlumniEvent',
 					),
 				),
 			),


 			'addalumnicontributiondetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addalumnicontributiondetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'addAlumniContributionDetail',
 					),
 				),
 			),

 			/*'view-alumni-event-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-alumni-event-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'view-alumni-event-list',
 					),
 				),
 			),*/
			
			'createalumniresource' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/createalumniresource[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'createAlumniResource',
 					),
 				),
 			),

 			'viewalumniresourcelist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewalumniresourcelist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'viewAlumniResourceList',
 					),
 				),
 			),
			
			'createalumnienquiry' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/createalumnienquiry[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'createAlumniEnquiry',
 					),
 				),
 			),
			
			'viewalumnienquirylist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewalumnienquirylist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'viewAlumniEnquiryList',
 					),
 				),
 			),
			
			'createalumnifaqs' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/createalumnifaqs[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'createAlumniFaqs',
 					),
 				),
 			),
			
			'viewalumnifaqslist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewalumnifaqslist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'viewAlumniFaqsList',
 					),
 				),
 			),

 			'alumniprofile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumniprofile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniProfile',
 					),
 				),
 			),

 			'approvealumnienquiry' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvealumnienquiry[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'approveAlumniEnquiry',
 					),
 				),
 			),

 			/*'list-all-alumni-student' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/list-all-alumni-student[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'listAllAlumniStudent',
 					),
 				),
 			),
			
			'alumni-approval-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumni-approval-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumni-approval-list',
 					),
 				),
 			),*/

 			'addalumnisubscriptiondetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addalumnisubscriptiondetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'addAlumniSubscriptionDetail',
 					),
 				),
 			),


 			'editalumnisubscriptiondetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editalumnisubscriptiondetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'editAlumniSubscriptionDetail',
 					),
 				),
 			),

 			'addalumnisubscription' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addalumnisubscription[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'addAlumniSubscription',
 					),
 				),
 			),

 			'editalumnisubscription' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editalumnisubscription[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'editAlumniSubscription',
 					),
 				),
 			),

 			'alumnisubscriptionlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumnisubscriptionlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniSubscriptionList',
 					),
 				),
 			),

 			'applyalumnisubscription' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyalumnisubscription[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'applyAlumniSubscription',
 					),
 				),
 			),

 			'alumnisubscriberlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/alumnisubscriberlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'alumniSubscriberList',
 					),
 				),
 			),

 			'viewalumnisubscriberdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewalumnisubscriberdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'viewAlumniSubscriberDetails',
 					),
 				),
 			),

 			'updatealumnisubscription' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatealumnisubscription[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'updateAlumniSubscription',
 					),
 				),
 			),

 			'renewalumniexpireddate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/renewalumniexpireddate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Alumni',
					'action' => 'renewAlumniExpiredDate',
 					),
 				),
 			),

 		), 
 	), 
   
	'view_manager' => array(
		'template_path_stack' => array(
		'Alumni' => __DIR__ . '/../view',
		),
	),
);