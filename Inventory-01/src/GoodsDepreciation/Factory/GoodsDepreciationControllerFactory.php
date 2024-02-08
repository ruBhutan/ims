<?php

namespace GoodsDepreciation\Factory;

use GoodsDepreciation\Controller\GoodsDepreciationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoodsDepreciationControllerFactory implements FactoryInterface
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
		$goodsDepreciationService = $realServiceLocator->get('GoodsDepreciation\Service\GoodsDepreciationServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new GoodsDepreciationController($goodsDepreciationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}