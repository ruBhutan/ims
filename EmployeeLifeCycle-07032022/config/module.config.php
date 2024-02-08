<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'EmpTransfer\Mapper\EmpTransferMapperInterface' => 'EmpTransfer\Factory\ZendDbSqlMapperFactory',
			'EmpTransfer\Service\EmpTransferServiceInterface'=> 'EmpTransfer\Factory\EmpTransferServiceFactory',
			'EmpResignation\Mapper\EmpResignationMapperInterface' => 'EmpResignation\Factory\ZendDbSqlMapperFactory',
			'EmpResignation\Service\EmpResignationServiceInterface'=> 'EmpResignation\Factory\EmpResignationServiceFactory',
			'EmpTraining\Mapper\EmpTrainingMapperInterface' => 'EmpTraining\Factory\ZendDbSqlMapperFactory',
			'EmpTraining\Service\EmpTrainingServiceInterface'=> 'EmpTraining\Factory\EmpTrainingServiceFactory',
			'EmpPromotion\Mapper\EmpPromotionMapperInterface' => 'EmpPromotion\Factory\ZendDbSqlMapperFactory',
			'EmpPromotion\Service\EmpPromotionServiceInterface'=> 'EmpPromotion\Factory\EmpPromotionServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'EmpTransfer' => 'EmpTransfer\Factory\EmpTransferControllerFactory',
			'EmpResignation' => 'EmpResignation\Factory\EmpResignationControllerFactory',
			'EmpTraining' => 'EmpTraining\Factory\EmpTrainingControllerFactory',
			'EmpPromotion' => 'EmpPromotion\Factory\EmpPromotionControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
             //Employee Transfer
			 'applytransferform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applytransferform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'applyTransfer',
 					),
 				),
 			),
			'viewtransferfromdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtransferfromdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'viewTransferFromDetail',
 					),
 				),
 			),
 			'viewtransfertodetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtransfertodetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'viewTransferToDetail',
 					),
 				),
 			),
 			'downloademptransferdocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloademptransferdocument[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'downloadEmpTransferDocument',
 					),
 				),
 			),
			'transfertoapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/transfertoapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'transferToApproval',
 					),
 				),
 			),
			'transferfromapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/transferfromapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'transferFromApproval',
 					),
 				),
 			),
             'tocollegeapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/tocollegeapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'viewToCollegeApproval',
 					),
 				),
 			),
            'transferapplicationstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/transferapplicationstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'transferApplicationStatus',
 					),
 				),
 			),
           'fromtransferapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/fromtransferapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'fromCollegeApproval',
 					),
 				),
 			),
			'fromtransferreject' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/fromtransferreject[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'fromCollegeReject',
 					),
 				),
 			),
            'totransferapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/totransferapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'toCollegeApproval',
 					),
 				),
 			),
			'totransferreject' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/totransferreject[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'toCollegeReject',
 					),
 				),
 			),
           'ovctransferapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ovctransferapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'ovctransferapproval',
 					),
 				),
 			),
			'approvedtransfers' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvedtransfers[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'approvedTransfers',
 					),
 				),
 			),
			'updatetransferapplicant' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatetransferapplicant[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'updateTransferApplicant',
 					),
 				),
 			),
			'ovctransferapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ovctransferapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'ovcTransferApprovalList',
 					),
 				),
 			),
			'ovctransferapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ovctransferapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'ovcTransferApproval',
 					),
 				),
 			),
			'ovctransferapproved' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/ovctransferapproved[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'ovcTransferApproved',
 					),
 				),
 			),
			'transferjoiningreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/transferjoiningreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'transferJoiningReport',
 					),
 				),
 			),
 			'transferstaff' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/transferstaff[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'transferStaff',
 					),
 				),
 			),
 			'updatetransferedstaff' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatetransferedstaff[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTransfer',
					'action' => 'updateTransferedStaff',
 					),
 				),
 			),
			//Employee Resignation
            'empresignation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empresignation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'applyResignation',
 					),
 				),
 			), 
            'resignationrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/resignationrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'resignationRecord',
 					),
 				),
 			), 
            'separationrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/separationrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'separationRecord',
 					),
 				),
 			), 
			'separationrecordlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/separationrecordlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'viewSeparationRecordList',
 					),
 				),
 			),
 			'empseparationrecorddetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empseparationrecorddetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'empSeparationRecordDetails',
 					),
 				),
 			),
 			'recordresignedemployee' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordresignedemployee[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'recordResignedEmployee',
 					),
 				),
 			),
 			'recordresignedempdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/recordresignedempdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'recordResignedEmpDetails',
 					),
 				),
 			),
 			'downloadseparationrecordfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadseparationrecordfile[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'downloadSeparationRecord',
 					),
 				),
 			),
 			'issueseparationrecord' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issueseparationrecord[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueSeparationRecord',
 					),
 				),
 			),
           'resignationapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/resignationapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'resignationApprovalList',
 					),
 				),
 			),
			'viewresignationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewresignationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'viewResignationDetails',
 					),
 				),
 			),
			'editresignationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editresignationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'editResignationDetails',
 					),
 				),
 			),
			'deleteresignationdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteresignationdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'deleteResignationDetails',
 					),
 				),
 			),
            'approveresignation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approveresignation[/:action][/:id][/:employee_details_id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					'employee_details_id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'approveResignation',
 					),
 				),
 			),
			'rejectresignation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectresignation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'rejectResignation',
 					),
 				),
 			),
			//due certificate
			'issuestoredues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issuestoredues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueStoreDues',
 					),
 				),
 			),
			'issueaccountsdues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issueaccountsdues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueAccountsDues',
 					),
 				),
 			),
 			'issueitdues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issueitdues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueItDues',
 					),
 				),
 			),
			'issuelibrarydues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issuelibrarydues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueLibraryDues',
 					),
 				),
 			),
			'issueworkshopdues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issueworkshopdues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueWorkshopDues',
 					),
 				),
 			), 
			'issueestatedues' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/issueestatedues[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpResignation',
					'action' => 'issueEstateDues',
 					),
 				),
 			), 
           //Employee Training
           'longtermplannedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermplannedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermPlannedTrainingList',
 					),
 				),
 			),
			'longtermplanned' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermplanned[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermPlannedTraining',
 					),
 				),
 			),
			'longtermadhoc' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermadhoc[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermAdhocTraining',
 					),
 				),
 			),

 			'longtermadhocdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermadhocdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermAdhocTrainingDetails',
 					),
 				),
 			),
			
			'editlongtermadhoctraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editlongtermadhoctraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'editLongTermAdhocTraining',
 					),
 				),
 			),
			
			'deletelongtermadhoctraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletelongtermadhoctraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'deleteLongTermAdhocTraining',
 					),
 				),
 			),

			'shorttermplannedlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermplannedlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermPlannedTrainingList',
 					),
 				),
 			),
			'shorttermplanned' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermplanned[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermPlannedTraining',
 					),
 				),
 			),
			'shorttermadhoc' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermadhoc[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermAdhocTraining',
 					),
 				),
 			),
 			'shorttermadhocdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermadhocdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermAdhocTrainingDetails',
 					),
 				),
 			),
			
			'editshorttermadhoctraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editshorttermadhoctraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'editShortTermAdhocTraining',
 					),
 				),
 			),
			
			'deleteshorttermadhoctraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteshorttermadhoctraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'deleteShortTermAdhocTraining',
 					),
 				),
 			),
			
			'nominatestaff' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/nominatestaff[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'nominationList',
 					),
 				),
 			),
			'trainingnomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/trainingnomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'nominateStaffTraining',
 					),
 				),
 			),
			'applytrainings' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applytrainings[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'applyTrainings',
 					),
 				),
 			),
			'viewtrainingapplications' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtrainingapplications[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'viewTrainingApplications',
 					),
 				),
 			),
			'shorttermtrainingform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermtrainingform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermTrainingForm',
 					),
 				),
 			),
 			'editshorttermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editshorttermapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'editAppliedShortTermApplication',
 					),
 				),
 			),
			'shorttermapplications' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermapplications[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermApplications',
 					),
 				),
 			),

 			'updateshorttermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateshorttermapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'updateShortTermApplication',
 					),
 				),
 			),

			'longtermtrainingform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermtrainingform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermTrainingForm',
 					),
 				),
 			),
			'longtermapplications' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermapplications[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermApplications',
 					),
 				),
 			),
 			'editlongtermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editlongtermapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'editAppliedLongTermApplication',
 					),
 				),
 			),
 			'appliedlongtermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/appliedlongtermapplication[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'appliedLongTermApplication',
 					),
 				),
 			),
			'downloadlongtermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadlongtermapplication[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'downloadLongTermApplication',
 					),
 				),
 			),
			'downloadshorttermapplication' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadshorttermapplication[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'downloadShortTermApplication',
 					),
 				),
 			),
			'downloadtrainingdocuments' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadtrainingdocuments[/:filename][/:category]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z0-9_-]*',
					'category' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'downloadTrainingDocuments',
 					),
 				),
 			),
			//employee promotion
			'empapplypromotion' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empapplypromotion[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'applyPromotion',
 					),
 				),
 			),
			'empmeritoriouspromotion' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empmeritoriouspromotion[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'applyMeritoriousPromotion',
 					),
 				),
 			),
			'promotionapplicantstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/promotionapplicantstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'promotionApplicantStatus',
 					),
 				),
 			),
			'emppromotionapproval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emppromotionapproval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'empPromotionApproval',
 					),
 				),
 			),
			'emppromotionreject' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emppromotionreject[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'empPromotionReject',
 					),
 				),
 			),
            'promotionapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/promotionapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'promotionApprovalList',
 					),
 				),
 			),
			'viewupdatedemployeepost' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewupdatedemployeepost[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'viewUpdatedEmployeePost',
 					),
 				),
 			),
			'viewrejectedreasons' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewrejectedreasons[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'viewRejectedReasons',
 					),
 				),
 			),
			'updateemployeepost' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateemployeepost[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'updateEmployeePost',
 					),
 				),
 			),
			'viewdetailsforpromotion' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewdetailsforpromotion[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'viewDetailsForPromotion',
 					),
 				),
 			),
			'emppromotionsearch' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emppromotionsearch[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'empPromotionSearch',
 					),
 				),
 			),
			'opencompetitionlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/opencompetitionlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'openCompetitionList',
 					),
 				),
 			),
			'promotionviacompetition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/promotionviacompetition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'promotionViaCompetition',
 					),
 				),
 			),

 			'downloadpromotiondetailfile' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadpromotiondetailfile[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'downloadPromotionDetailFile',
 					),
 				),
 			),

			'downloadpromotiondocument' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadpromotiondocument[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'downloadPromotionDocument',
 					),
 				),
			 ),

			 'printempapplypromotiondetails' => array(
				'type' => 'segment',
				'options' => array(
				'route' => '/printempapplypromotiondetails[/:action][/:id][/:type][/:promotion_type]',
				'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					'id' => '[a-zA-Z0-9_-]*',
					'type' => '[a-zA-Z0-9_-]*',
					'promotion_type' => '[a-zA-Z0-9_-]*',
					),
			   'defaults' => array(
				   'controller' => 'EmpPromotion',
				   'action' => 'printEmpApplyPromotionDetails',
					),
				),
			),
			//Training
			'emptraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emptraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'emptraining',
 					),
 				),
 			),
			'trainingapprovalform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/trainingapprovalform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'trainingapprovalform',
 					),
 				),
 			),
                        'updatestudystatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatestudystatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'updateStudyStatus',
 					),
 				),
 			),

                        'longtermtraineelist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermtraineelist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longTermTraineeList',
 					),
 				),
 			),

                        'updatetrainingreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatetrainingreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'updateTrainingReport',
 					),
 				),
 			),
                        'updatestudyreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatestudyreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'updateStudyReport',
 					),
 				),
 			),
                        'requeststudyextension' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requeststudyextension[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'requestStudyExtension',
 					),
 				),
 			),
                         'shorttermtraineelist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermtraineelist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shortTermTraineeList',
 					),
 				),
 			),

			'trainingreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/trainingreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'trainingReport',
 					),
 				),
 			),
			'viewlongtermtrainingreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewlongtermtrainingreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'viewLongTermTrainingReport',
 					),
 				),
 			),
			'viewshorttermtrainingreport' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewshorttermtrainingreport[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'viewShortTermTrainingReport',
 					),
 				),
 			),
            'longtermtraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/longtermtraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'longtermtraining',
 					),
 				),
 			),
            'shorttermtraining' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/shorttermtraining[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'shorttermtraining',
 					),
 				),
 			),
            'emppromotion' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emppromotion[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpPromotion',
					'action' => 'emppromotion',
 					),
 				),
 			),
            'activateccevaluation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/activateccevaluation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'activateccevaluation',
 					),
 				),
 			), 
            'generatecc' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generatecc[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmpTraining',
					'action' => 'generatecc',
 					),
 				),
 			),
 		),
 	),
	'view_manager' => array(
		'template_path_stack' => array(
		'EmployeeLifeCycle' => __DIR__ . '/../view',
		),
	),
);