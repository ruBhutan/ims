<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'Budgeting\Mapper\BudgetingMapperInterface' => 'Budgeting\Factory\ZendDbSqlMapperFactory',
			'Budgeting\Service\BudgetingServiceInterface'=> 'Budgeting\Factory\BudgetingServiceFactory',
			'BudgetTransactions\Mapper\BudgetTransactionsMapperInterface' => 'BudgetTransactions\Factory\ZendDbSqlMapperFactory',
			'BudgetTransactions\Service\BudgetTransactionsServiceInterface'=> 'BudgetTransactions\Factory\BudgetTransactionsServiceFactory',
			'FinanceCodes\Mapper\FinanceCodesMapperInterface' => 'FinanceCodes\Factory\ZendDbSqlMapperFactory',
			'FinanceCodes\Service\FinanceCodesServiceInterface'=> 'FinanceCodes\Factory\FinanceCodesServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'invokables' => array(
			'BudgetingCategory' => 'BudgetingCategory\Controller\BudgetingCategoryController',
			'BudgetProposal' => 'BudgetProposal\Controller\BudgetProposalController',
			'BudgetApproval' => 'BudgetApproval\Controller\BudgetApprovalController',
			'IncomeSource' => 'IncomeSource\Controller\IncomeSourceController',
			'IncomeDetails' => 'IncomeDetails\Controller\IncomeDetailsController',
			'IncomeApproval' => 'IncomeApproval\Controller\IncomeApprovalController',
			'ExpenditureCategory' => 'ExpenditureCategory\Controller\ExpenditureCategoryController',
			'ExpenditureDetails' => 'ExpenditureDetails\Controller\ExpenditureDetailsController',
			'ExpenditureApproval' => 'ExpenditureApproval\Controller\ExpenditureApprovalController',
		),
		'factories' => array(
			'CurrentBudget' => 'Budgeting\Factory\CurrentBudgetControllerFactory',
			'CapitalBudget' => 'Budgeting\Factory\CapitalBudgetControllerFactory',
			'BudgetTransactions' => 'BudgetTransactions\Factory\BudgetTransactionsControllerFactory',
			'FinanceCodes' => 'FinanceCodes\Factory\FinanceCodesControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			//Finance Codes
			'accountsgrouphead' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/accountsgrouphead[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'addAccountsGroupHead',
						),
 				),
 			),
			'editaccountsgrouphead' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editaccountsgrouphead[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'editAccountsGroupHead',
						),
 				),
 			),
			'chartaccounts' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/chartaccounts[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'addChartAccounts',
						),
 				),
 			),
			'editchartaccounts' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editchartaccounts[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'editChartAccounts',
						),
 				),
 			),
			'viewchartaccounts' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewchartaccounts[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'viewChartAccounts',
						),
 				),
 			),
			'viewaccountsgrouphead' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewaccountsgrouphead[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'viewAccountsGroupHead',
						),
 				),
 			),
			'broadheadname' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/broadheadname[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'addBroadHeadName',
						),
 				),
 			),
			'editbroadheadname' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editbroadheadname[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'editBroadHeadName',
						),
 				),
 			),
			'objectcode' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/objectcode[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'addObjectCode',
						),
 				),
 			),
			'editobjectcode' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editobjectcode[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'FinanceCodes',
						'action' => 'editObjectCode',
						),
 				),
 			),
			//current budget
			'budgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/budgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'addBudgetProposal',
						),
 				),
 			),
			'viewbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'viewBudgetProposal',
						),
 				),
 			),
			'editbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'viewBudgetProposal',
						),
 				),
 			),
			'orgbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/orgbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'viewOrganisationBudgetProposal',
						),
 				),
 			),
			'approvedbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/approvedbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'approvedBudgetProposal',
						),
 				),
 			),
			'updatebudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updatebudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'updateBudgetProposal',
						),
 				),
 			),
			'budgetledger' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/budgetledger[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'addBudgetLedger',
						),
 				),
 			),
			'viewbudgetledger' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewbudgetledger[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'viewBudgetLedger',
						),
 				),
 			),
			'editbudgetledger' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editbudgetledger[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'editBudgetLedger',
						),
 				),
 			),
			'deletebudgetledger' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deletebudgetledger[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'deleteBudgetLedger',
						),
 				),
 			),
			//capital budget
			'capitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/capitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'addCapitalBudgetProposal',
						),
 				),
 			),
			'viewcapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'viewCapitalBudgetProposal',
						),
 				),
 			),
			'editcapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'editCapitalBudgetProposal',
						),
 				),
 			),
			'deletecapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/deletecapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'deleteCapitalBudgetProposal',
						),
 				),
 			),
			'orgcapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/orgcapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'viewOrganisationCapitalBudgetProposal',
						),
 				),
 			),
			'approvedcapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/approvedcapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'approvedCapitalBudgetProposal',
						),
 				),
 			),
			'updatecapitalbudgetproposal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updatecapitalbudgetproposal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'updateCapitalBudgetProposal',
						),
 				),
 			),
			//budget reappropriations for both current and capital budget
			//the add is after the budgetreappropriation fields have been selected
			'addbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'addBudgetReappropriation',
						),
 				),
 			),
			'updatebudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updatebudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'updateBudgetReappropriation',
						),
 				),
 			),
			'budgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/budgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'applyBudgetReappropriation',
						),
 				),
 			),
			'viewbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'viewBudgetReappropriation',
						),
 				),
 			),
			'editbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CurrentBudget',
						'action' => 'editBudgetReappropriation',
						),
 				),
 			),
			'addcapitalbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcapitalbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'addCapitalBudgetReappropriation',
						),
 				),
 			),
			'updatecapitalbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/updatecapitalbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'updateCapitalBudgetReappropriation',
						),
 				),
 			),
			'capitalbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/capitalbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'applyCapitalBudgetReappropriation',
						),
 				),
 			),
			'viewcapitalbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcapitalbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'viewCapitalBudgetReappropriation',
						),
 				),
 			),
			'editcapitalbudgetreappropriation' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcapitalbudgetreappropriation[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'CapitalBudget',
						'action' => 'editCapitalBudgetReappropriation',
						),
 				),
 			),
			//budget transactions such as Supplementary, Reappropriation and withdrawal
			'currentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/currentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'applyCurrentSupplementaryBudget',
						),
 				),
 			),
			'viewcurrentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcurrentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'viewCurrentSupplementaryBudget',
						),
 				),
 			),
			'listcurrentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listcurrentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'listCurrentSupplementaryBudget',
						),
 				),
 			),
			'editcurrentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcurrentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'editCurrentSupplementaryBudget',
						),
 				),
 			),
			'addcurrentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcurrentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'addCurrentSupplementaryBudget',
						),
 				),
 			),
			'insertcurrentsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/insertcurrentsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'insertCurrentSupplementaryBudget',
						),
 				),
 			),
			'capitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/capitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'applyCapitalSupplementaryBudget',
						),
 				),
 			),
			'listcapitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listcapitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'listCapitalSupplementaryBudget',
						),
 				),
 			),
			'viewcapitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcapitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'viewCapitalSupplementaryBudget',
						),
 				),
 			),
			'editcapitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcapitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'editCapitalSupplementaryBudget',
						),
 				),
 			),
			'addcapitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcapitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'addCapitalSupplementaryBudget',
						),
 				),
 			),
			'insertcapitalsupplementarybudget' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/insertcapitalsupplementarybudget[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'insertCapitalSupplementaryBudget',
						),
 				),
 			),
			'currentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/currentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'applyCurrentBudgetWithdrawal',
						),
 				),
 			),
			'editcurrentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcurrentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'editCurrentBudgetWithdrawal',
						),
 				),
 			),
			'listcurrentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listcurrentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'listCurrentBudgetWithdrawal',
						),
 				),
 			),
			'viewcurrentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcurrentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'viewCurrentBudgetWithdrawal',
						),
 				),
 			),
			'addcurrentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcurrentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'addCurrentBudgetWithdrawal',
						),
 				),
 			),
			'insertcurrentbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/insertcurrentbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'insertCurrentBudgetWithdrawal',
						),
 				),
 			),
			'capitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/capitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'applyCapitalBudgetWithdrawal',
						),
 				),
 			),
			'editcapitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/editcapitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'editCapitalBudgetWithdrawal',
						),
 				),
 			),
			'listcapitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/listcapitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'listCapitalBudgetWithdrawal',
						),
 				),
 			),
			'viewcapitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/viewcapitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'viewCapitalBudgetWithdrawal',
						),
 				),
 			),
			'addcapitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/addcapitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'addCapitalBudgetWithdrawal',
						),
 				),
 			),
			'insertcapitalbudgetwithdrawal' => array(
 				'type' => 'segment',
 				'options' => array(
					'route' => '/insertcapitalbudgetwithdrawal[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
						),
					'defaults' => array(
						'controller' => 'BudgetTransactions',
						'action' => 'insertCapitalBudgetWithdrawal',
						),
 				),
 			),
      	),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
		'Budgeting' => __DIR__ . '/../view',
		),
	),
);