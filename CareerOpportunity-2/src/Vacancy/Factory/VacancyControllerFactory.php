<?php

namespace Vacancy\Factory;

use Vacancy\Controller\VacancyController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VacancyControllerFactory implements FactoryInterface
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
		$vacancyService = $realServiceLocator->get('Vacancy\Service\VacancyServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new VacancyController($vacancyService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}