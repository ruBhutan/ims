<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'LeaveCategory\Mapper\LeaveCategoryMapperInterface' => 'LeaveCategory\Factory\ZendDbSqlMapperFactory',
			'LeaveCategory\Service\LeaveCategoryServiceInterface'=> 'LeaveCategory\Factory\LeaveCategoryServiceFactory',
			'EmployeeLeave\Mapper\EmployeeLeaveMapperInterface' => 'EmployeeLeave\Factory\ZendDbSqlMapperFactory',
			'EmployeeLeave\Service\EmployeeLeaveServiceInterface'=> 'EmployeeLeave\Factory\EmployeeLeaveServiceFactory',
			'LeaveEncashment\Mapper\LeaveEncashmentMapperInterface' => 'LeaveEncashment\Factory\ZendDbSqlMapperFactory',
			'LeaveEncashment\Service\LeaveEncashmentServiceInterface'=> 'LeaveEncashment\Factory\LeaveEncashmentServiceFactory',
			'EmpAttendance\Mapper\EmpAttendanceMapperInterface' => 'EmpAttendance\Factory\ZendDbSqlMapperFactory',
			'EmpAttendance\Service\EmpAttendanceServiceInterface'=> 'EmpAttendance\Factory\EmpAttendanceServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'LeaveCategory' => 'LeaveCategory\Factory\LeaveCategoryControllerFactory',
			'EmployeeLeave' => 'EmployeeLeave\Factory\EmployeeLeaveControllerFactory',
			'LeaveEncashment' => 'LeaveEncashment\Factory\LeaveEncashmentControllerFactory',
			'EmpAttendance' => 'EmpAttendance\Factory\EmpAttendanceControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			//leave category
			'addempleavecategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addempleavecategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveCategory',
					'action' => 'addEmployeeLeaveCategory',
 					),
 				),
 			),
			'editempleavecategory' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editempleavecategory[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveCategory',
					'action' => 'editEmployeeLeaveCategory',
 					),
 				),
 			),
 			'empleaveapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleaveapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empApplyLeave',
 					),
 				),
 			), 
 			'applyonbehalfleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applyonbehalfleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'applyOnBehalfLeave',
 					),
 				),
 			), 
			'editempleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editempleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'editEmpLeave',
 					),
 				),
 			), 
			'viewempleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewempleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'viewEmpLeave',
 					),
 				),
 			), 
            'leavestatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/leavestatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'leaveStatus',
 					),
 				),
 			), 
            'empleaveapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleaveapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empLeaveApproval',
 					),
 				),
 			),  
            'empleavestatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleavestatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empLeaveStatus',
 					),
 				),
 			),
 			'empapprovedleavelist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empapprovedleavelist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empApprovedLeaveList',
 					),
 				),
 			),
 			'updateempapprovedleave' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateempapprovedleave[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'updateEmpApprovedLeave',
 					),
 				),
 			),
 			'empleavedetaillist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleavedetaillist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empLeaveDetailList',
 					),
 				),
 			),

 			'editempleavedetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editempleavedetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'editEmpLeaveDetails',
 					),
 				),
 			),

			'empassignofficiatingsupervisor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empassignofficiatingsupervisor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'empAssignOfficiatingSupervisor',
 					),
 				),
 			),

 			'downloadempofficiatingfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadempofficiatingfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'downloadEmpOfficiatingFile',
 					),
 				),
 			),


			'editofficiatingsupervisor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editofficiatingsupervisor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'editOfficiatingSupervisor',
 					),
 				),
 			),
			'downloadleaveapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadleaveapplication[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeLeave',
					'action' => 'downloadLeaveApplication',
 					),
 				),
 			),
			//Leave Encashment
            'empleaveencashmentapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleaveencashmentapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveEncashment',
					'action' => 'applyLeaveEncashment',
 					),
 				),
 			),  
            'empleaveencashmentlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empleaveencashmentlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveEncashment',
					'action' => 'viewLeaveEncashment',
 					),
 				),
 			),
			'leaveencashmentstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/leaveencashmentstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveEncashment',
					'action' => 'viewLeaveEncashmentStatus',
 					),
 				),
 			),  
            'approveleaveencashment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approveleaveencashment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveEncashment',
					'action' => 'approveLeaveEncashment',
 					),
 				),
 			),
			'rejectleaveencashment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectleaveencashment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'LeaveEncashment',
					'action' => 'rejectLeaveEncashment',
 					),
 				),
			 ), 
			 
			 'empleaveencashmentorder' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/empleaveencashmentorder[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'LeaveEncashment',
				   'action' => 'empLeaveEncashmentOrder',
					),
				),
			),

			'updateempleaveencashmentorder' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/updateempleaveencashmentorder[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'LeaveEncashment',
				   'action' => 'updateEmpLeaveEncashmentOrder',
					),
				),
			),

			'viewleaveencashmentorderdetails' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/viewleaveencashmentorderdetails[/:action][/:id]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'LeaveEncashment',
				   'action' => 'viewLeaveEncashmentOrderDetails',
					),
				),
			),

			'downloadleaveencashmentorderfile' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/downloadleaveencashmentorderfile[/:filename]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'LeaveEncashment',
				   'action' => 'downloadLeaveEncashmentOrderFile',
					),
				),
			),

			//employee attendance
			'empattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpAttendance',
					'action' => 'addEmployeeAttendance',
 					),
 				),
 			), 
			'recordempattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordempattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpAttendance',
					'action' => 'recordEmployeeAttendance',
 					),
 				),
 			),
			'viewempattendance' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewempattendance[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpAttendance',
					'action' => 'viewEmployeeAttendance',
 					),
 				),
 			),
 		),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'Employeeleave' => __DIR__ . '/../view',
		),
	),
);