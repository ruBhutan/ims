<?php

namespace Budgeting\Factory;

use Budgeting\Service\BudgetingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BudgetingServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new BudgetingService(
			$serviceLocator->get('Budgeting\Mapper\BudgetingMapperInterface')
		);
	}
	
}