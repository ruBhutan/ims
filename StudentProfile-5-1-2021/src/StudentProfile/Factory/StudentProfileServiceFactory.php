<?php

namespace StudentProfile\Factory;

use StudentProfile\Service\StudentProfileService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentProfileServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentProfileService(
			$serviceLocator->get('StudentProfile\Mapper\StudentProfileMapperInterface')
		);
	}
	
}