<?php

namespace EmpAttendance\Factory;

use EmpAttendance\Controller\EmpAttendanceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpAttendanceControllerFactory implements FactoryInterface
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
		$attendanceService = $realServiceLocator->get('EmpAttendance\Service\EmpAttendanceServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmpAttendanceController($attendanceService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}