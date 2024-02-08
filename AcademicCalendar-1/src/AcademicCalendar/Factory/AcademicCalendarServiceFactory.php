<?php

namespace AcademicCalendar\Factory;

use AcademicCalendar\Service\AcademicCalendarService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicCalendarServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AcademicCalendarService(
			$serviceLocator->get('AcademicCalendar\Mapper\AcademicCalendarMapperInterface')
		);
	}
	
}