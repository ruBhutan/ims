<?php

namespace ExtraCurricularAttendance\Factory;

use ExtraCurricularAttendance\Service\ExtraCurricularAttendanceService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtraCurricularAttendanceServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ExtraCurricularAttendanceService(
			$serviceLocator->get('ExtraCurricularAttendance\Mapper\ExtraCurricularAttendanceMapperInterface')
		);
	}
	
}