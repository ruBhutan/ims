<?php

namespace EmpPromotion\Factory;

use EmpPromotion\Controller\EmpPromotionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpPromotionControllerFactory implements FactoryInterface
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
		$promotionService = $realServiceLocator->get('EmpPromotion\Service\EmpPromotionServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmpPromotionController($promotionService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}