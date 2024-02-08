<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Accounts\Mapper\FeeStructureMapperInterface' => 'Accounts\Factory\ZendDbSqlMapperFactory',
            'Accounts\Service\FeeStructureServiceInterface' => 'Accounts\Factory\FeeStructureServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Accounts\Mapper\PayrollMapperInterface' => 'Accounts\Factory\PayrollDbSqlMapperFactory',
            'Accounts\Service\PayrollServiceInterface' => 'Accounts\Factory\PayrollServiceFactory',
            'Accounts\Mapper\MasterMapperInterface' => 'Accounts\Factory\MasterDbSqlMapperFactory',
            'Accounts\Service\MasterServiceInterface' => 'Accounts\Factory\MasterServiceFactory',
            'Accounts\Mapper\AssetMapperInterface' => 'Accounts\Factory\AssetDbSqlMapperFactory',
            'Accounts\Service\AssetServiceInterface' => 'Accounts\Factory\AssetServiceFactory',

            'Accounts\Mapper\ChequeMapperInterface' => 'Accounts\Factory\ChequeDbSqlMapperFactory',
            'Accounts\Service\ChequeServiceInterface' => 'Accounts\Factory\ChequeServiceFactory',

            'Accounts\Mapper\GenerateTdsReportMapperInterface' => 'Accounts\Factory\GenerateTdsReportDbSqlMapperFactory',
            'Accounts\Service\GenerateTdsReportServiceInterface' => 'Accounts\Factory\GenerateTdsReportServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Accounts\Controller\Index' => 'Accounts\Controller\IndexController',
            'Accounts\Controller\Chartaccount' => 'Accounts\Controller\ChartaccountController',
            'Accounts\Controller\Asset' => 'Accounts\Controller\AssetController',
            'Accounts\Controller\Payment' => 'Accounts\Controller\PaymentController',
            'Accounts\Controller\PayrollReport' => 'Accounts\Controller\PayrollReportController',
            'Accounts\Controller\Hreport' => 'Accounts\Controller\HreportController',
            'Accounts\Controller\Cheque' => 'Accounts\Controller\ChequeController',
            'Accounts\Controller\Closing' => 'Accounts\Controller\ClosingController',
        ),
        'factories' => array(
            'FeeStructure' => 'Accounts\Factory\FeeStructureControllerFactory',
            'Accounts\Controller\Payroll' => 'Accounts\Factory\PayrollControllerFactory',
            'StudentFeeCategory' => 'Accounts\Factory\StudentFeeCategoryControllerFactory',
            'StudentFeeReport' => 'Accounts\Factory\StudentFeeReportControllerFactory',
            'Master' => 'Accounts\Factory\MasterControllerFactory',
            'Asset' => 'Accounts\Factory\AssetControllerFactory',
            'Chartaccount' => 'Accounts\Factory\ChartaccountControllerFactory',
            'Report' => 'Accounts\Factory\ReportControllerFactory',
            'Transaction' => 'Accounts\Factory\TransactionControllerFactory',

            'Cheque' => 'Accounts\Factory\ChequeControllerFactory',
            'GenerateTdsReport' => 'Accounts\Factory\GenerateTdsReportControllerFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'account' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/acc[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'master' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/master[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Master',
                        'action' => 'bankreftype',
                    ),
                ),
            ),
            'chartaccount' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/c[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Chartaccount',
                        'action' => 'index',
                    ),
                ),
            ),
            'asset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/asst[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Asset',
                        'action' => 'bankaccount',
                    ),
                ),
            ),

            'transaction' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/transaction[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Transaction',
                        'action' => 'index',
                    ),
                ),
            ),

            'report' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/accrep[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'ledger',
                    ),
                ),
            ),
            'trialbalance' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/trialbalance[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'trialbalance',
                    ),
                ),
            ),
            'profitloss' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profitloss[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'profitloss',
                    ),
                ),
            ),
            'balancesheet' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/balancesheet[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'balancesheet',
                    ),
                ),
            ),
            'bankreconciliation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/bankreconciliation[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'bankreconciliation',
                    ),
                ),
            ),
            'bankstatement' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/bankstatement[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'bankstatement',
                    ),
                ),
            ),
            'preport' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pr-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\PayrollReport',
                        'action' => 'payregister',
                    ),
                ),
            ),
            'cheque' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cheque[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Cheque',
                        'action' => 'index',
                    ),
                ),
            ),
            'closing' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/closing[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Closing',
                        'action' => 'closing',
                    ),
                ),
            ),
            'payroll' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payroll[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Payroll',
                        'action' => 'index',
                    ),
                ),
            ),
            'payrollemployee' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payrollemployee[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Payroll',
                        'action' => 'payrollemployee',
                    ),
                ),
            ),
            'payslip' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/payslip[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Payroll',
                        'action' => 'payslip',
                    ),
                ),
            ),
            'cashflow' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cashflow[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Report',
                        'action' => 'cashflow',
                    ),
                ),
            ),
            'netpayble' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/netpayble-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Accounts\Controller\Payroll',
                        'action' => 'viewnetpayablereport',
                    ),
                ),
            ),

            'fee-structure' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/fee-structure[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'FeeStructure',
                        'action' => 'index',
                    ),
                ),
            ),

            'edit-fee-structure' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit-fee-structure[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'FeeStructure',
                        'action' => 'edit',
                    ),
                ),
            ),

            'student-fee-category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/student-fee-category[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'StudentFeeCategory',
                        'action' => 'index',
                    ),
                ),
            ),

            'student-fee-report' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/student-fee-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'StudentFeeReport',
                        'action' => 'index',
                    ),
                ),
            ),

            'generate-tds-report' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/generate-tds-report[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'GenerateTdsReport',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    /*'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view/',
        ),
        'display_exceptions' => true,
    ),*/
    'view_manager' => array(
        'template_path_stack' => array(
            'Accounts' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
