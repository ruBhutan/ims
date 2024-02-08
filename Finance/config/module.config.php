<?php
return array(
	'service_manager'=>array(
		'factories'=> array(
			'Masters\Mapper\MastersMapperInterface' => 'Masters\Factory\ZendDbSqlMapperFactory',
			'Masters\Service\MastersServiceInterface'=> 'Masters\Factory\MastersServiceFactory',
			'PayrollManagement\Mapper\PayrollManagementMapperInterface' => 'PayrollManagement\Factory\ZendDbSqlMapperFactory',
			'PayrollManagement\Service\PayrollManagementServiceInterface'=> 'PayrollManagement\Factory\PayrollManagementServiceFactory',
			'ChequeManagement\Mapper\ChequeManagementMapperInterface' => 'ChequeManagement\Factory\ZendDbSqlMapperFactory',
			'ChequeManagement\Service\ChequeManagementServiceInterface'=> 'ChequeManagement\Factory\ChequeManagementServiceFactory',
			'Voucher\Mapper\VoucherMapperInterface' => 'Voucher\Factory\ZendDbSqlMapperFactory',
			'Voucher\Service\VoucherServiceInterface'=> 'Voucher\Factory\VoucherServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'Masters' => 'Masters\Factory\MastersControllerFactory',
			'PayrollManagement' => 'PayrollManagement\Factory\PayrollManagementControllerFactory',
			'ChequeManagement' => 'ChequeManagement\Factory\ChequeManagementControllerFactory',
			'Voucher' => 'Voucher\Factory\VoucherControllerFactory',
		),
		'invokables' => array(
			'FeesCategory' => 'FeesCategory\Controller\FeesCategoryController',
			'FeeSubCategory' => 'FeeSubCategory\Controller\FeeSubCategoryController',
			'FeeAllocation' => 'FeeAllocation\Controller\FeeAllocationController',
			'FeeImport' => 'FeeImport\Controller\FeeImportController',
			'FeeCollection' => 'FeeCollection\Controller\FeeCollectionController',
			'FeesReport' => 'FeesReport\Controller\FeesReportController',
			'AccountGroup' => 'AccountGroup\Controller\AccountGroupController',
			'VoucherMaster' => 'VoucherMaster\Controller\VoucherMasterController',
			'VoucherHead' => 'VoucherHead\Controller\VoucherHeadController',
			'CreateVoucher' => 'CreateVoucher\Controller\CreateVoucherController',
			'Finance' => 'Finance\Controller\FinanceController',                  
		),
	),
	'router' => array(
 		'routes' => array(
			//Masters Route
			//"Masters" take care of the creation of various entitites for the Finance Module
			'viewstaffdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewstaffdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'viewStaffDetails',
						),
 				),
 			),
			'viewstaffpositionlevel' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewstaffpositionlevel[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'viewStaffPositionLevel',
						),
 				),
 			),
			'addfinancialinstitutions' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addfinancialinstitutions[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'addFinancialInstitutions',
						),
 				),
 			),
			'editfinancialinstitutions' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editfinancialinstitutions[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'editFinancialInstitutions',
						),
 				),
 			),
			'viewfinancialinstitutions' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewfinancialinstitutions[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'viewFinancialInstitutions',
						),
 				),
 			),
			'addbankdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addbankdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'addBankDetails',
						),
 				),
 			),
			'viewbankdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewbankdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'viewBankDetails',
						),
 				),
 			),
			'editbankdetails' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editbankdetails[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'editBankDetails',
						),
 				),
 			),
			'staffhousingallowance' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/staffhousingallowance[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'staffHousingAllowance',
						),
 				),
 			),
			'staffteachingallowance' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/staffteachingallowance[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'Masters',
						'action' => 'staffTeachingAllowance',
						),
 				),
 			),
			'addvouchermaster' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addvouchermaster[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'addVoucherMaster',
					),
				),
			),
			'editvouchermaster' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editvouchermaster[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'editVoucherMaster',
					),
				),
			),
			'viewvouchermaster' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewvouchermaster[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'viewVoucherMaster',
					),
				),
			),
			'addfixeddeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfixeddeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'addFixedDeductions',
					),
				),
			),
			'editfixeddeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editfixeddeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'editFixedDeductions',
					),
				),
			),
			'viewfixeddeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewfixeddeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'viewFixedDeductions',
					),
				),
			),
			'addfloatingdeductionstype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfloatingdeductionstype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'addFloatingDeductionsType',
					),
				),
			),
			'editfloatingdeductionstype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editfloatingdeductionstype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'editFloatingDeductionsType',
					),
				),
			),
			'viewfloatingdeductionstype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewfloatingdeductionstype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'viewFloatingDeductionsType',
					),
				),
			),
			'addfloatingdeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfloatingdeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'addFloatingDeductions',
					),
				),
			),
			'viewfloatingdeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewfloatingdeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'viewFloatingDeductions',
					),
				),
			),
			'editfloatingdeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editfloatingdeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Masters',
						'action' =>'editFloatingDeductions',
					),
				),
			),
			//Payroll Management
			'staffpaydetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/staffpaydetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'PayrollManagement',
						'action' =>'viewStaffPayDetails',
					),
				),
			),
			'stafffloatingdeductions' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/stafffloatingdeductions[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'PayrollManagement',
						'action' =>'addStaffFloatingDeductions',
					),
				),
			),
			//ChequeManagement
			'registerchequebook' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/registerchequebook[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'ChequeManagement',
						'action' =>'registerChequeBook',
					),
				),
			),
			//Voucher 
			'addvoucher' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addvoucher[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Voucher',
						'action' =>'addVoucher',
					),
				),
			),
			'voucherverification' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/voucherverification[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Voucher',
						'action' =>'voucherVerification',
					),
				),
			),
			'viewvoucher' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewvoucher[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Voucher',
						'action' =>'viewVoucher',
					),
				),
			),
			'journalvoucher' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/journalvoucher[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'journalvoucher',
					),
				),
			),
			'paymentvoucher' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/paymentvoucher[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'paymentvoucher',
					),
				),
			),
			'receiptvoucher' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/receiptvoucher[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'receiptvoucher',
					),
				),
			),
			//old routes
			'addfeescategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/feescategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeesCategory',
						'action' =>'addfeescategory',
					),
				),
			),
			
			 'addfeesubcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfeesubcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeeSubCategory',
						'action' =>'addfeesubcategory',
					),
				),
			),
			
			 'addfeeallocation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfeeallocation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeeAllocation',
						'action' =>'addfeeallocation',
					),
				),
			),
			
			
		  'feeimport' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/feeimport[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeeImport',
						'action' =>'feeimport',
					),
				),
			), 
			
			  'feecollection' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/feecollection[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeeCollection',
						'action' =>'feecollection',
					),
				),
			),   
			
			   'feesreport' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/feesreport[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'FeesReport',
						'action' =>'feesreport',
					),
				),
			), 
			
			'createaccountgroup' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/createaccountgroup[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'AccountGroup',
						'action' =>'createaccountgroup',
					),
				),
			),   
			
			
			
			'empsalary' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empsalary[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'empsalary',
					),
				),
			),
			'leaveencashment' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/leaveencashment[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'leaveencashment',
					),
				),
			),
			'ltc' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/ltc[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'ltc',
					),
				),
			),
			'empallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'empallowance',
					),
				),
			),
			'bankreconciliation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/bankreconciliation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'bankreconciliation',
					),
				),
			),
			'emptada' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emptada[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'emptada',
					),
				),
			),
			'revenuegeneration' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/revenuegeneration[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'revenuegeneration',
					),
				),
			),
			'cheque' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/cheque[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'cheque',
					),
				),
			),
			'bankname' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/bankname[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'bankname',
					),
				),
			),
			'incometype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/incometype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'incometype',
					),
				),
			),
			
			'financetype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/financetype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'financetype',
					),
				),
			),
			'financetype' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/financetype[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'financetype',
					),
				),
			),
			
			'tdspercentage' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/tdspercentage[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'tdspercentage',
					),
				),
			),
			
			
			'createledger' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/createledger[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'createledger',
					),
				),
			),
			'creategroup' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/creategroup[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'creategroup',
					),
				),
			),
			
			
			'chequeissuedetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/chequeissuedetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'chequeissuedetails',
					),
				),
			),
			
			'chqreplace' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/chqreplace[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'chqreplace',
					),
				),
			),
			
			'bankstatement' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/bankstatement[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'bankstatement',
					),
				),
			),
			'empdeduction' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empdeduction[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'empdeduction',
					),
				),
			),
			
			'emploan' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emploan[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'emploan',
					),
				),
			),
			'addbudgethead' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addbudgethead[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'addbudgethead',
					),
				),
			),
			'addparty' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addparty[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'Finance',
						'action' =>'addparty',
					),
				),
			),
                    
         	),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'Finance' => __DIR__ . '/../view',
		),
	),
);