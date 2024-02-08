<?php

namespace Budgeting\Factory;

use Budgeting\Controller\CapitalBudgetController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CapitalBudgetControllerFactory implements FactoryInterface
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
		$budgetingService = $realServiceLocator->get('Budgeting\Service\BudgetingServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new CapitalBudgetController($budgetingService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}