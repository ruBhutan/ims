<?php

namespace CharacterCertificate\Factory;

use CharacterCertificate\Controller\CharacterCertificateController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CharacterCertificateControllerFactory implements FactoryInterface
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
		$certificateService = $realServiceLocator->get('CharacterCertificate\Service\CharacterCertificateServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new CharacterCertificateController($certificateService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}