<?php

namespace Budgeting\Factory;

use Budgeting\Controller\CurrentBudgetController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CurrentBudgetControllerFactory implements FactoryInterface
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
		
		return new CurrentBudgetController($budgetingService);
	}
	
}