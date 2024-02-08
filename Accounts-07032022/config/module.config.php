<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Accounts\Controller\Index' => 'Accounts\Controller\IndexController',
            'Accounts\Controller\Master' => 'Accounts\Controller\MasterController',
            'Accounts\Controller\Asset' => 'Accounts\Controller\AssetController',            
            'Accounts\Controller\Chartaccount' => 'Accounts\Controller\ChartaccountController',
            'Accounts\Controller\Transaction' => 'Accounts\Controller\TransactionController',
            'Accounts\Controller\Report' => 'Accounts\Controller\ReportController',
        	'Accounts\Controller\Payment' => 'Accounts\Controller\PaymentController',
        	'Accounts\Controller\Payroll' => 'Accounts\Controller\PayrollController',        	
            'Accounts\Controller\PayrollReport' => 'Accounts\Controller\PayrollReportController',
			'Accounts\Controller\Hreport'    => 'Accounts\Controller\HreportController',
			'Accounts\Controller\Cheque' => 'Accounts\Controller\ChequeController',
			'Accounts\Controller\Closing' => 'Accounts\Controller\ClosingController',
        ),
	),
	
    'router' => array(
        'routes' => array(
			'account' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/acc[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Index',
						'action'        => 'index',
					),
				),
			),
			'master' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/master[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Master',
						'action'        => 'bankreftype',
					),
				),
			),
			'chartaccount' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/c[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Chartaccount',
						'action'        => 'index',
					),
				),
			),			
			'asset' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/asst[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Asset',
						'action'        => 'bankaccount',
					),
				),
			),	
					
			'transaction' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/t[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Transaction',
						'action'        => 'index',
					),
				),
			),	
					
			'report' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/accrep[/:action[/:id]]',
					'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
					'defaults' => array(
						'controller' => 'Accounts\Controller\Report',
						'action'        => 'ledger',
					),
				),
			),	
            'preport' => array(
				'type'    => 'Segment',
				'options' => array(
						'route'    => '/pr-report[/:action][/:id]',
						'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						),
						'defaults' => array(
								'controller' => 'Accounts\Controller\PayrollReport',
								'action'   => 'payregister',
						),
				),
            ),
            'cheque' => array(
				'type'    => 'Segment',
				'options' => array(
						'route'    => '/cheque[/:action[/:id]]',
						'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						),
						'defaults' => array(
								'controller' => 'Accounts\Controller\Cheque',
								'action'        => 'index',
						),
				),
        	),
			'closing' => array(
				'type'    => 'Segment',
				'options' => array(
						'route'    => '/closing[/:action[/:id]]',
						'constraints' => array(
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
						),
						'defaults' => array(
								'controller' => 'Accounts\Controller\Closing',
								'action'        => 'closing',
						),
				),
        	),
			'payroll' => array(
        			'type'    => 'Segment',
        			'options' => array(
        					'route'    => '/pr[/:action][/:id]',
        					'constraints' => array(
       								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
       						),
       						'defaults' => array(
        							'controller' => 'Accounts\Controller\Payroll',
        							'action'   => 'index',
       						),
       				),
       		),
		),
	),	
	'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view/',
        ),
		'display_exceptions' => true,
    ),
);
