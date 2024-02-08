<?php

namespace PlanningReports\Factory;

use PlanningReports\Service\PlanningReportsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlanningReportsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new PlanningReportsService(
			$serviceLocator->get('PlanningReports\Mapper\PlanningReportsMapperInterface')
		);
	}
	
}