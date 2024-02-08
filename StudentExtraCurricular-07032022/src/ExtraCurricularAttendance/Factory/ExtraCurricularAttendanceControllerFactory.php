<?php

namespace ExtraCurricularAttendance\Factory;

use ExtraCurricularAttendance\Controller\ExtraCurricularAttendanceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtraCurricularAttendanceControllerFactory implements FactoryInterface
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
		$attendanceService = $realServiceLocator->get('ExtraCurricularAttendance\Service\ExtraCurricularAttendanceServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new ExtraCurricularAttendanceController($attendanceService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}