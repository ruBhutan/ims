<?php

namespace ChequeManagement\Factory;

use ChequeManagement\Service\ChequeManagementService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChequeManagementServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ChequeManagementService(
			$serviceLocator->get('ChequeManagement\Mapper\ChequeManagementMapperInterface')
		);
	}
	
}