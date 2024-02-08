<?php

namespace GoodsTransaction\Factory;

use GoodsTransaction\Controller\GoodsTransactionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoodsTransactionControllerFactory implements FactoryInterface
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
		$goodsTransactionService = $realServiceLocator->get('GoodsTransaction\Service\GoodsTransactionServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new GoodsTransactionController($goodsTransactionService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}