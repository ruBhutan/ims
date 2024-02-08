<?php

namespace Timetable\Factory;

use Timetable\Service\TimetableService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TimetableServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new TimetableService(
			$serviceLocator->get('Timetable\Mapper\TimetableMapperInterface')
		);
	}
	
}