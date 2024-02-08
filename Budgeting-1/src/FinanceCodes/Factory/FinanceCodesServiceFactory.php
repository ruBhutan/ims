<?php

namespace FinanceCodes\Factory;

use FinanceCodes\Service\FinanceCodesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FinanceCodesServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new FinanceCodesService(
			$serviceLocator->get('FinanceCodes\Mapper\FinanceCodesMapperInterface')
		);
	}
	
}