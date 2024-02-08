<?php

namespace GoodsRequisition\Factory;

use GoodsRequisition\Controller\GoodsRequisitionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoodsRequisitionControllerFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$goodsRequisitionService = $realServiceLocator->get('GoodsRequisition\Service\GoodsRequisitionServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new GoodsRequisitionController($goodsRequisitionService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}