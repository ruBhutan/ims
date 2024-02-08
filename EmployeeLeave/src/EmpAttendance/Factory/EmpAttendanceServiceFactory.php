<?php

namespace EmpAttendance\Factory;

use EmpAttendance\Service\EmpAttendanceService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpAttendanceServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpAttendanceService(
			$serviceLocator->get('EmpAttendance\Mapper\EmpAttendanceMapperInterface')
		);
	}
	
}