<?php

namespace AcademicAllocation\Factory;

use AcademicAllocation\Service\AcademicAllocationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicAllocationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AcademicAllocationService(
			$serviceLocator->get('AcademicAllocation\Mapper\AcademicAllocationMapperInterface')
		);
	}
	
}