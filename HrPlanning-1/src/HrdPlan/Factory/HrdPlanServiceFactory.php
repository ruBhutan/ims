<?php

namespace HrdPlan\Factory;

use HrdPlan\Service\HrdPlanService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrdPlanServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new HrdPlanService(
			$serviceLocator->get('HrdPlan\Mapper\HrdPlanMapperInterface')
		);
	}
	
}