<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'GoodsTransaction\Mapper\GoodsTransactionMapperInterface' => 'GoodsTransaction\Factory\ZendDbSqlMapperFactory',
			'GoodsTransaction\Service\GoodsTransactionServiceInterface'=> 'GoodsTransaction\Factory\TransactionServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

			'GoodsRequisition\Mapper\GoodsRequisitionMapperInterface' => 'GoodsRequisition\Factory\ZendDbSqlMapperFactory',
			'GoodsRequisition\Service\GoodsRequisitionServiceInterface'=> 'GoodsRequisition\Factory\RequisitionServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',

			'GoodsDepreciation\Mapper\GoodsDepreciationMapperInterface' => 'GoodsDepreciation\Factory\ZendDbSqlMapperFactory',
			'GoodsDepreciation\Service\GoodsDepreciationServiceInterface'=> 'GoodsDepreciation\Factory\DepreciationServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'GoodsTransaction' => 'GoodsTransaction\Factory\GoodsTransactionControllerFactory',
			'GoodsRequisition' => 'GoodsRequisition\Factory\GoodsRequisitionControllerFactory',
			'GoodsDepreciation' => 'GoodsDepreciation\Factory\GoodsDepreciationControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
 			'add-item-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemCategory',
 					),
 				),
 			),

 			'view-item-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-item-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewItemDetails',
 					),
 				),
 			), 

 			'edit-item-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemCategory',
 					),
 				),
 			), 

 			'delete-item-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemCategory',
 					),
 				),
 			),

 			'add-item-sub-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-sub-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemSubCategory',
 					),
 				),
 			),

 			'edit-item-sub-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-sub-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemSubCategory',
 					),
 				),
 			),

 			'black-list-item-supplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/black-list-item-supplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'blackListItemSupplier',
 					),
 				),
 			),


 			'activateblacklistedsupplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/activateblacklistedsupplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'activateBlackListedSupplier',
 					),
 				),
 			),


 			'downloadblacklistedsupplierdocuments' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadblacklistedsupplierdocuments[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'downloadBlackListedSupplierDocuments',
 					),
 				),
 			),


 			'blacklistedsupplierdetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/blacklistedsupplierdetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'blackListedSupplierDetail',
 					),
 				),
 			),

 			

 			'delete-item-sub-category' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-sub-category[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemSubCategory',
 					),
 				),
 			),

 			'add-item-quantity-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-quantity-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemQuantityType',
 					),
 				),
 			),


 			'edit-item-quantity-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-quantity-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemQuantityType',
 					),
 				),
 			),

 			'add-college-item-quantity-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-college-item-quantity-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addCollegeItemQuantityType',
 					),
 				),
 			),

 			'delete-item-quantity-type' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-quantity-type[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemQuantityType',
 					),
 				),
 			),

 			'add-item-name' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-name[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemName',
 					),
 				),
 			),


 			'edit-item-name' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-name[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemName',
 					),
 				),
 			),

 			'delete-item-name' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-name[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemName',
 					),
 				),
 			),

 			'add-item-supplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-supplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemSupplier',
 					),
 				),
 			),

 			'view-item-supplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-item-supplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewItemSupplier',
 					),
 				),
 			),

 			'edit-item-supplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-supplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemSupplier',
 					),
 				),
 			),

 			'delete-item-supplier' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-supplier[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemSupplier',
 					),
 				),
 			),


 			'add-item-donor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-item-donor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addItemDonor',
 					),
 				),
 			),

 			'edit-item-donor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-item-donor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editItemDonor',
 					),
 				),
 			),


 			'view-item-donor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-item-donor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewItemDonor',
 					),
 				),
 			),

 			'delete-item-donor' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-item-donor[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteItemDonor',
 					),
 				),
 			),

 			'add-goods-received' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-goods-received[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addGoodsReceived',
 					),
 				),
 			),

 			'addgoodsreceivedpurchased' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addgoodsreceivedpurchased[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addGoodsReceivedPurchased',
 					),
 				),
 			),

 			'add-goods-supplied' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-goods-supplied[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addGoodsSupplied',
 					),
 				),
 			),

 			'updateaddgoodssupplied' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateaddgoodssupplied[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateAddGoodsSupplied',
 					),
 				),
 			),

 			'generatevoucher' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generatevoucher[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'generateVoucher',
 					),
 				),
 			),

 			'edit-add-goods-supplied' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-add-goods-supplied[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editAddGoodsSupplied',
 					),
 				),
 			),

 			'delete-add-goods-supplied' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-add-goods-supplied[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteAddGoodsSupplied',
 					),
 				),
 			),


 			'add-goods-received-donation' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-goods-received-donation[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addGoodsReceivedDonation',
 					),
 				),
 			),

 			'supplied-goods-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/supplied-goods-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'suppliedGoodsList',
 					),
 				),
 			),

 			'supplier-goods-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/supplier-goods-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'supplierGoodsDetails',
 					),
 				),
 			),

 			'supplier-goods-list-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/supplier-goods-list-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'supplierGoodsListDetails',
 					),
 				),
 			),


 			'generategoodsreceiptvoucher' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/generategoodsreceiptvoucher[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'generateGoodsReceiptVoucher',
 					),
 				),
 			),


 			'goods-receipt-voucher' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/goods-receipt-voucher[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'goodsReceiptVoucher',
 					),
 				),
 			),

 			'view-goods-in-stock' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-goods-in-stock[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewGoodsInStock',
 					),
 				),
 			),


 			'view-goods-in-stock-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-goods-in-stock-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewGoodsInStockDetails',
 					),
 				),
 			),

 			'view-donated-goods-in-stock-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-donated-goods-in-stock-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewDonatedGoodsInStockDetails',
 					),
 				),
 			),

 			'viewtransferedgoodsinstockdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewtransferedgoodsinstockdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewTransferedGoodsInStockDetails',
 					),
 				),
 			),

 			'dept-all-goods-in-stock' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dept-all-goods-in-stock[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deptAllGoodsInStock',
 					),
 				),
 			),

 			'adhoc-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/adhoc-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'adhocIssueGoods',
 					),
 				),
 			),

 			'updateadhocgoodsissue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateadhocgoodsissue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateAdhocGoodsIssue',
 					),
 				),
 			),

 			'add-adhoc-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-adhoc-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addAdhocGoodsIssue',
 					),
 				),
 			),

 			'edit-adhoc-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-adhoc-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editAdhocGoodsIssue',
 					),
 				),
 			),

 			'delete-adhoc-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-adhoc-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteAdhocGoodsIssue',
 					),
 				),
 			),

 			'sub-store-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/sub-store-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'subStoreIssueGoods',
 					),
 				),
 			),

 			'updatesubstoreissuegoods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatesubstoreissuegoods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateSubStoreIssueGoods',
 					),
 				),
 			),

 			'delete-sub-store-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-sub-store-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteSubStoreGoodsIssue',
 					),
 				),
 			),

 			'delete-sub-store-to-ind-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-sub-store-to-ind-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteSubStoreToIndIssueGoods',
 					),
 				),
 			),

 			'requisition-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'requisitionGoodsIssue',
 					),
 				),
			),
			// View only requisition li
			'requisition-goods-issue-list-only' => array(
                                'type' => 'segment',
                                'options' => array(
                                'route' => '/requisition-goods-issue-list-only[/:action][/:id]',
                                'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[a-zA-Z0-9_-]*',
                                        ),
                                'defaults' => array(
                                        'controller' => 'GoodsTransaction',
					'action' => 'requisitionGoodsIssueList',
                                        ),
                                ),
                        ),


 			'delete-requisition-goods-issue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-requisition-goods-issue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deleteRequisitionGoodsIssue',
 					),
 				),
 			),


 			'updaterequisitiongoodsissue' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updaterequisitiongoodsissue[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateRequisitionGoodsIssue',
 					),
 				),
 			),
            

            'nominate-sub-store-responsible' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/nominateSubStore[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'nominateSubStoreResponsible',
 					),
 				),
 			),


 			'add-sub-store-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-sub-store-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addSubStoreIssueGoods',
 					),
 				),
 			),

 			'add-sub-store-to-ind-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/add-sub-store-to-ind-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'addSubStoreToIndIssueGoods',
 					),
 				),
 			),

 			'updatesubstoretoindissuegoods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatesubstoretoindissuegoods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateSubStoreToIndIssueGoods',
 					),
 				),
 			),

 			'updatedeptissuegoods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updatedeptissuegoods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateDeptIssueGoods',
 					),
 				),
 			),

 			'view-emp-issued-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-emp-issued-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewEmpIssuedGoods',
 					),
 				),
 			),

 			'emp-goods-list-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emp-goods-list-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empGoodsListDetails',
 					),
 				),
 			),

 			'emp-goods-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emp-goods-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empGoodsList',
 					),
 				),
 			),


 			'apply-goods-surrender' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-goods-surrender[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'applyGoodsSurrender',
 					),
 				),
 			),

 			'applied-goods-surrender-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/applied-goods-surrender-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'appliedGoodsSurrenderList',
 					),
 				),
 			),

 			'goods-surrender-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/goods-surrender-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'goodsSurrenderList',
 					),
 				),
 			),

 			'emp-goods-surrender-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emp-goods-surrender-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empGoodsSurrenderList',
 					),
 				),
 			),

 			'empSurrenderGoodsList' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empSurrenderGoodsList[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empGoodsSurrenderListDetails',
 					),
 				),
 			),

 			'emp-sub-store-goods-surrender-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/emp-sub-store-goods-surrender-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empSubStoreGoodsSurrenderList',
 					),
 				),
 			),


 			'empsubstoregoodssurrenderlistdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empsubstoregoodssurrenderlistdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'empSubStoreGoodsSurrenderListDetails',
 					),
 				),
 			),


 			'approveempgoodssurrender' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approveempgoodssurrender[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'approveEmpGoodsSurrender',
 					),
 				),
 			),

 			'approveempsubstoresurrender' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approveempsubstoresurrender[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'approveEmpSubStoreSurrender',
 					),
 				),
 			),

 			'consumeemmpconsumablegoods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/consumeemmpconsumablegoods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'consumeEmpConsumableGoods',
 					),
 				),
 			),


 			'updateempgoodssurrender' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateempgoodssurrender[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateEmpGoodsSurrender',
 					),
 				),
 			),


 			'view-goods-surrender-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-goods-surrender-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewGoodsSurrenderDetails',
 					),
 				),
 			),


 			'viewsubstoregoodssurrenderdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewsubstoregoodssurrenderdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewSubStoreGoodsSurrenderDetails',
 					),
 				),
 			),


 			'sub-store-surrender-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/sub-store-surrender-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'subStoreSurrenderList',
 					),
 				),
 			),


 			'sub-store-surrender-goods-lists' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/sub-store-surrender-goods-lists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'subStoreSurrenderGoodsLists',
 					),
 				),
 			),


 			'sub-store-surrender-goods-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/sub-store-surrender-goods-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'subStoreSurrenderGoodsDetails',
 					),
 				),
 			),


 			'dept-goods-transfer-from-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dept-goods-transfer-from-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deptGoodsTransferFromList',
 					),
 				),
 			),


 			'dept-goods-transfer-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dept-goods-transfer-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deptGoodsTransferList',
 					),
 				),
 			),

 			'view-dept-goods-transfer-from-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-dept-goods-transfer-from-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewDeptGoodsTransferFromDetails',
 					),
 				),
 			),


 			'dept-goods-transfer-from-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dept-goods-transfer-from-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deptGoodsTransferFromDetails',
 					),
 				),
 			),


 			'dept-goods-transfer-to-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dept-goods-transfer-to-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'deptGoodsTransferToDetails',
 					),
 				),
 			),


 			'apply-dept-goods-surrender' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-dept-goods-surrender[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'applyDeptGoodsSurrender',
 					),
 				),
 			),


 			'edit-emp-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-emp-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editEmpGoods',
 					),
 				),
 			),

 			'edit-issue-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-issue-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'editIssueGoods',
 					),
 				),
 			),

 			'apply-goods-requisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-goods-requisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'applyGoodsRequisition',
 					),
 				),
 			),


 			 'updateindgoodsrequisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/updateindgoodsrequisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'updateIndGoodsRequisition',
 					),
 				),
 			),

 			'view-requisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-requisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'viewRequisition',
 					),
 				),
 			 ),

 			 'requisition-approval-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-approval-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionApprovalList',
 					),
 				),
 			 ),

 			 'requisition-approved-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-approved-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionApprovedList',
 					),
 				),
 			 ), 


 			 'all-goods-requisition-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/all-goods-requisition-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'allGoodsRequisitionList',
 					),
 				),
			 ),
//Test
			 'list-all-requisition'  => array(
                                'type' => 'segment',
                                'options' => array(
                                'route' => '/list-all-requisition[/:action][/:id]',
                                'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[a-zA-Z0-9_-]*',
                                        ),
                                'defaults' => array(
                                        'controller' => 'GoodsRequisition',
                                        'action' => 'listAllRequisition',
                                        ),
                                ),
			 ),
			 // List indovidual list
			'all-individual-list'  => array(
                                'type' => 'segment',
                                'options' => array(
                                'route' => '/all-individual-list[/:action][/:id]',
                                'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[a-zA-Z0-9_-]*',
                                        ),
                                'defaults' => array(
                                        'controller' => 'GoodsRequisition',
                                        'action' => 'allIndividualList',
                                        ),
                                ),
                         ),

 			 'requisition-approval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-approval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionApproval',
 					),
 				),
 			 ),


 			 'approvegoodsrequisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvegoodsrequisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'approveGoodsRequisition',
 					),
 				),
 			 ),


 			 'empGoodsRequisitionLists' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empGoodsRequisitionLists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'empGoodsRequisitionListDetails',
 					),
 				),
 			 ),
			 
			 'edit-goods-requisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/edit-goods-requisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'editGoodsRequisition',
 					),
 				),
 			 ),
			 
			 'delete-goods-requisition' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/delete-goods-requisition[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'deleteGoodsRequisition',
 					),
 				),
 			 ),

 			 'requisition-forward-approval-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisitionForwardLists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionForwardApprovalList',
 					),
 				),
 			 ),

 			 'emp-requisition-forward-list-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empRequisitionForwardLists[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'empRequisitionForwardListDetails',
 					),
 				),
 			 ),

 			 'requisition-forward-approval' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-forward-approval[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionForwardApproval',
 					),
 				),
 			 ),

 			 'approved-requisition-forwarded-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvedReqForwardedList[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'approvedRequisitionForwardedList',
 					),
 				),
 			 ),

 			 'update-approved-req-forwarded' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/update-approved-req-forwarded[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'updateApprovedReqForwarded',
 					),
 				),
 			 ),

 			 'indv-req-pending-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/indv-req-pending-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'indvReqPendingDetails',
 					),
 				),
 			 ),

 			 'indv-req-approved-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/indv-req-approved-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'indvReqApprovedDetails',
 					),
 				),
 			 ),

 			 'indv-req-rejected-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/indv-req-rejected-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'indvReqRejectedDetails',
 					),
 				),
 			 ),

 			 'indv-req-forwarded-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/indv-req-forwarded-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'indvReqForwardedDetails',
 					),
 				),
 			 ),

 			 'requisition-pending-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-pending-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionPendingDetails',
 					),
 				),
 			 ),

 			 'requisition-approved-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-approved-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionApprovedDetails',
 					),
 				),
 			 ),

 			 'requisition-rejected-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-rejected-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionRejectedDetails',
 					),
 				),
 			 ),

 			 'requisition-forwarded-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/requisition-forwarded-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsRequisition',
					'action' => 'requisitionForwardedDetails',
 					),
 				),
 			 ),

 			 'apply-dept-goods-transfer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-dept-goods-transfer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'applyDeptGoodsTransfer',
 					),
 				),
 			),

 			 'apply-transfer-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-transfer-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'applyTransferGoods',
 					),
 				),
 			),

 			'view-transfer-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-transfer-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewTransferGoods'
 				  ),
 			    ), 
 		     ),	
 			
 		     'view-dept-goods-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-dept-goods-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'viewDeptGoodsList'
 				  ),
 			    ), 
 		     ),

 		     'goods-transfer-approval-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/goods-transfer-approval-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'goodsTransferApprovalList'
 				  ),
 			    ), 
 		     ),
 		     'update-goods-transfer-status' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/update-goods-transfer-status[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'updateGoodsTransferStatus'
 				  ),
 			    ), 
 		     ),

 		     'apply-org-goods-transfer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/apply-org-goods-transfer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'applyOrgGoodsTransfer',
 					),
 				),
 			),

 		     'dispose-goods' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/dispose-goods[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'disposeGoods',
 					),
 				),
 			),


 		     'org-goods-transfer-approval-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/org-goods-transfer-approval-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'orgGoodsTransferApprovalList',
 					),
 				),
 			),


 		     'org-goods-transfer-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/org-goods-transfer-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'orgGoodsTransferDetails',
 					),
 				),
 			),


 		     'org-goods-transfer-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/org-goods-transfer-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'orgGoodsTransferList',
 					),
 				),
 			),

 		     'org-goods-transfer-to-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/org-goods-transfer-to-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'orgGoodsTransferToDetails',
 					),
 				),
 			),


 		     'org-goods-transfer-from-details' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/org-goods-transfer-from-details[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'orgGoodsTransferFromDetails',
 					),
 				),
 			),

 		     'rejectorgfromgoodstransfer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectorgfromgoodstransfer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsTransaction',
					'action' => 'rejectOrgFromGoodsTransfer',
 					),
 				),
 			),


 		     'view-fixed-asset-list' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-fixed-asset-list[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsDepreciation',
					'action' => 'viewFixedAssetList'
 				  ),
 			    ), 
 		     ),

 		     'enter-fixed-asset-depr-val' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/enter-fixed-asset-depr-val[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsDepreciation',
					'action' => 'enterFixedAssetDeprVal'
 				  ),
 			    ), 
 		     ),


 		     'view-depreciation-value' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/view-depreciation-value[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'GoodsDepreciation',
					'action' => 'viewDepreciationValue'
 				  ),
 			    ), 
 		     ),			  

 		),
 	),
   
	'view_manager' => array(
		'template_path_stack' => array(
			'Inventory' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);
