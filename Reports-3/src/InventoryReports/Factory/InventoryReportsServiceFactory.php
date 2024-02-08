<?php

namespace InventoryReports\Factory;

use InventoryReports\Service\InventoryReportsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InventoryReportsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new InventoryReportsService(
			$serviceLocator->get('InventoryReports\Mapper\InventoryReportsMapperInterface')
		);
	}
	
}