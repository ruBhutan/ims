<?php

namespace AcademicCalendar\Factory;

use AcademicCalendar\Controller\AcademicCalendarController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicCalendarControllerFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$calendarService = $realServiceLocator->get('AcademicCalendar\Service\AcademicCalendarServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new AcademicCalendarController($calendarService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}