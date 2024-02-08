<?php

namespace StudentAdmission\Factory;

use StudentAdmission\Service\StudentAdmissionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentAdmissionServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentAdmissionService(
			$serviceLocator->get('StudentAdmission\Mapper\StudentAdmissionMapperInterface')
		);
	}
	
}