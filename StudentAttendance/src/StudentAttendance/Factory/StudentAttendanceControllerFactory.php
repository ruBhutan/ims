<?php

namespace StudentAttendance\Factory;

use StudentAttendance\Controller\StudentAttendanceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentAttendanceControllerFactory implements FactoryInterface
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
		$attendanceService = $realServiceLocator->get('StudentAttendance\Service\StudentAttendanceServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new StudentAttendanceController($attendanceService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}