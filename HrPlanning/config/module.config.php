<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'HrdPlan\Mapper\HrdPlanMapperInterface' => 'HrdPlan\Factory\ZendDbSqlMapperFactory',
			'HrdPlan\Service\HrdPlanServiceInterface'=> 'HrdPlan\Factory\HrdPlanServiceFactory',
			'HrmPlan\Mapper\HrmPlanMapperInterface' => 'HrmPlan\Factory\ZendDbSqlMapperFactory',
			'HrActivation\Service\HrActivationServiceInterface'=> 'HrActivation\Factory\HrActivationServiceFactory',
			'HrActivation\Mapper\HrActivationMapperInterface' => 'HrActivation\Factory\ZendDbSqlMapperFactory',
			'HrmPlan\Service\HrmPlanServiceInterface'=> 'HrmPlan\Factory\HrmPlanServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'HrdPlan' => 'HrdPlan\Factory\HrdPlanControllerFactory',
			'HrmPlan' => 'HrmPlan\Factory\HrmPlanControllerFactory',
			'HrActivation' => 'HrActivation\Factory\HrActivationControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
            //activation of HR Proposal
            'activatehrproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/activatehrproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrActivation',
					'action' => 'activateHrProposal',
 					),
 				),
 			),
			'editactivationdate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editactivationdate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrActivation',
					'action' => 'editActivationDate',
 					),
 				),
 			), 
            'hrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'hrdproposal',
 					),
 				),
 			),
			'viewhrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewhrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'viewHrdProposal',
 					),
 				),
 			),
			'edithrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'editHrdProposal',
 					),
 				),
 			),
			'deletehrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletehrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'deleteHrdProposal',
 					),
 				),
 			),
			'approvehrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvehrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'approveHrdProposal',
 					),
 				),
 			), 
			'rejecthrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejecthrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'rejectHrdProposal',
 					),
 				),
 			), 
			'hrmapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrmapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'hrmapprovallist',
 					),
 				),
 			),
			'viewhrdapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewhrdapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'viewHrdApproval',
 					),
 				),
 			),
			'edithrdapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithrdapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'editHrdApproval',
 					),
 				),
 			),
			'deletehrdapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletehrdapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'deleteHrdApproval',
 					),
 				),
 			),
			'hrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'hrmproposal',
 					),
 				),
 			),
			'viewhrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewhrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'viewHrmProposal',
 					),
 				),
 			),
			'edithrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edithrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'editHrmProposal',
 					),
 				),
 			),
			'deletehrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletehrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'deleteHrmProposal',
 					),
 				),
 			),
           'hrmapprovedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrmapprovedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'hrmapprovedlist',
 					),
 				),
 			),  
             'hrmapprovedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrmapprovedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'hrmapprovedlist',
 					),
 				),
 			),  
             'updatehrmapprovedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatehrmapprovedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'updatehrmapprovedlist',
 					),
 				),
 			), 
			'updatehrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatehrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'updateHrmProposal',
 					),
 				),
 			),
			'approvehrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvehrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'approveHrmProposal',
 					),
 				),
 			),
			'rejecthrmproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejecthrmproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'rejectHrmProposal',
 					),
 				),
 			),
			'emphrdproposalfilledform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emphrdproposalfilledform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'emphrdproposalfilledform',
 					),
 				),
 			),
             'hrdapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrdapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'hrdapprovallist',
 					),
 				),
 			),  
             'hrdapprovedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/hrdapprovedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'hrdapprovedlist',
 					),
 				),
 			),
			'updatehrdproposal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatehrdproposal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrdPlan',
					'action' => 'updateHrdProposal',
 					),
 				),
 			),
			'empworkforceproposalfilledform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empworkforceproposalfilledform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'HrmPlan',
					'action' => 'empworkforceproposal',
 					),
 				),
 			), 
        ),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'HrPlanning' => __DIR__ . '/../view',
		),
		'strategies' => array(
                'ViewJsonStrategy',
        ),
	),
	// Doctrine config
    'db' => array(
        'username' => 'rub',
        'password' => 'password',
    ),
);

