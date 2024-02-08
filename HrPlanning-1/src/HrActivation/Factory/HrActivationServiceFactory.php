<?php

namespace HrActivation\Factory;

use HrActivation\Service\HrActivationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrActivationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new HrActivationService(
			$serviceLocator->get('HrActivation\Mapper\HrActivationMapperInterface')
		);
	}
	
}