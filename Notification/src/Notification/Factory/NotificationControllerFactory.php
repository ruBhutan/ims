<?php

namespace Notification\Factory;

use Notification\Controller\NotificationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NotificationControllerFactory implements FactoryInterface
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
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		
		return new NotificationController($notificationService);
	}
	
}