<?php

return array(
	'controllers' => array(
		'invokables' => array(
			'AddGuestHouse' => 'AddGuestHouse\Controller\AddGuestHouseController',
                        'GuestHouseRoom' => 'GuestHouseRoom\Controller\GuestHouseRoomController',
                        'BookGuestHouse' => 'BookGuestHouse\Controller\BookGuestHouseController',
                        'GuestRoomBookApproval' => 'GuestRoomBookApproval\Controller\GuestRoomBookApprovalController',
                        
		),
	),
	'router' => array(
 		'routes' => array(
 			'addguesthouse' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addguesthouse[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'AddGuestHouse',
					'action' => 'addguesthouse',
 					),
 				),
 			),  
                    
                    'addguesthouseroom' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addguesthouseroom[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'GuestHouseRoom',
					'action' => 'addguesthouseroom',
 					),
 				),
 			),  
                    
                    'bookguesthouseroom' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/bookguesthouseroom[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'BookGuestHouse',
					'action' => 'bookguesthouseroom',
 					),
 				),
 			),  
                    
                    'viewguestroombook' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewguestroombook[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[0-9]+',
 					),
				'defaults' => array(
					'controller' => 'GuestRoomBookApproval',
					'action' => 'viewguestroombook',
 					),
 				),
 			),           
                                     
                                                           
 		),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'GuestHouseManagement' => __DIR__ . '/../view',
		),
	),
);