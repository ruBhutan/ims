<?php

return array(
	'controllers' => array(
		'invokables' => array(
			'ParentDashboard' => 'ParentDashboard\Controller\ParentDashboardController',
                        
                       
		),
	),
	'router' => array(
 		'routes' => array(

                    'parentdashboard' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/parentdashboard[/:action][/:id]',
                            'constraints' => array(
                               'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
                            ),
                            'defaults' => array(
                                'controller' => 'ParentDashboard',
                                'action' =>'parentdashboard',
                            ),
                        ),
                    ),
                    'parentnotification' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/parentnotification[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'ParentDashboard',
					'action' => 'parentnotification',
 					),
 				),
 			),
                    'moduletutor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/moduletutor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'ParentDashboard',
					'action' => 'moduletutor',
 					),
 				),
 			),
                  
         	),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'ParentDashboard' => __DIR__ . '/../view',
		),
	),
);