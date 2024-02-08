<?php
return array(
	'service_manager'=>array(
		'factories'=> array(
			'Administration\Mapper\AdministrationMapperInterface' => 'Administration\Factory\ZendDbSqlMapperFactory',
			'Administration\Service\AdministrationServiceInterface'=> 'Administration\Factory\AdministrationServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Administration' => 'Administration\Factory\AdministrationControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			'adduser' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/adduser[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addUser',
						),
 				),
 			),
			'listUser' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listUser[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'listUser',
						),
 				),
 			),
			'edituser' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/edituser[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editUser',
						),
 				),
 			),
			'addroles' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addroles[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addRoles',
						),
 				),
 			),
			'editroles' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editroles[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editRoles',
						),
 				),
 			),
            'addlevelzeromodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addlevelzeromodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addLevelZeroModule',
						),
 				),
 			),
			'editlevelzeromodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editlevelzeromodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editLevelZeroModule',
						),
 				),
 			),

 			'addmodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addmodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addModule',
						),
 				),
 			),
 			'editusermodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editusermodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editUserModule',
						),
 				),
 			),
 			'addsubmenu' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addsubmenu[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addSubMenu',
						),
 				),
 			),
 			'editsubmenu' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editsubmenu[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editSubMenu',
						),
 				),
 			),
			'addlevelonemodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addlevelonemodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addLevelOneModule',
						),
 				),
 			),
			'editlevelonemodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editlevelonemodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editLevelOneModule',
						),
 				),
 			),
			'addleveltwomodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addleveltwomodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addLevelTwoModule',
						),
 				),
 			),
			'editleveltwomodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editleveltwomodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editLevelTwoModule',
						),
 				),
 			),
			'addlevelthreemodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addlevelthreemodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addLevelThreeModule',
						),
 				),
 			),
			'editlevelthreemodule' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editlevelthreemodule[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editLevelThreeModule',
						),
 				),
 			),
			'configureuserroutes' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/configureuserroutes[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'configureUserRoutes',
						),
 				),
 			),
			'adduserroutes' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/adduserroutes[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addUserRoutes',
						),
 				),
 			),
			'listuserroutes' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listuserroutes[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'listUserRoutes',
						),
 				),
 			),
			'edituserroutes' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/edituserroutes[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editUserRoutes',
						),
 				),
 			),
			'adduserworkflow' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/adduserworkflow[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'addUserWorkFlow',
						),
 				),
 			),
			'edituserworkflow' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/edituserworkflow[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'editUserWorkFlow',
						),
 				),
 			),
			'viewworkflow' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewworkflow[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'viewWorkFlow',
						),
 				),
 			),
			'changeuserpassword' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/changeuserpassword[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'changeUserPassword',
						),
 				),
 			),
			'changepassword' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/changepassword[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'changePassword',
						),
 				),
 			),

 			'updateuserpassword' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updateuserpassword[/:action][/:id][/:user_type_id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
						'user_type_id' => '[a-zA-Z0-9_-]*',
						),
					'defaults' => array(
						'controller' => 'Administration',
						'action' => 'updateUserPassword',
						),
 				),
 			),

			

 		),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
			'Administration' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);