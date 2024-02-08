<?php

namespace Notification\Factory;

use Notification\Service\NotificationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NotificationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new NotificationService(
			$serviceLocator->get('Notification\Mapper\NotificationMapperInterface'),
			$serviceLocator->get('Zend\Authentication\AuthenticationService')
		);
	}
	
}