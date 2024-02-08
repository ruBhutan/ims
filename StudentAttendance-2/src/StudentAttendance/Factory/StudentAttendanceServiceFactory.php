<?php

namespace StudentAttendance\Factory;

use StudentAttendance\Service\StudentAttendanceService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentAttendanceServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentAttendanceService(
			$serviceLocator->get('StudentAttendance\Mapper\StudentAttendanceMapperInterface')
		);
	}
	
}