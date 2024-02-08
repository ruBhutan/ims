<?php

namespace HrmPlan\Factory;

use HrmPlan\Service\HrmPlanService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrmPlanServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new HrmPlanService(
			$serviceLocator->get('HrmPlan\Mapper\HrmPlanMapperInterface')
		);
	}
	
}