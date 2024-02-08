<?php

namespace BudgetTransactions\Factory;

use BudgetTransactions\Service\BudgetTransactionsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BudgetTransactionsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new BudgetTransactionsService(
			$serviceLocator->get('BudgetTransactions\Mapper\BudgetTransactionsMapperInterface')
		);
	}
	
}