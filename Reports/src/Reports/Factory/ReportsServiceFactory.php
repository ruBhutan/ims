<?php

namespace Reports\Factory;

use Reports\Service\ReportsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReportsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ReportsService(
			$serviceLocator->get('Reports\Mapper\ReportsMapperInterface')
		);
	}
	
}