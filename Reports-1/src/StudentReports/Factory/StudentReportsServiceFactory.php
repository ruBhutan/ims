<?php

namespace StudentReports\Factory;

use StudentReports\Service\StudentReportsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentReportsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentReportsService(
			$serviceLocator->get('StudentReports\Mapper\StudentReportsMapperInterface')
		);
	}
	
}