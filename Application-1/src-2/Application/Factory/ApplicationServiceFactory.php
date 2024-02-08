<?php

namespace Application\Factory;

use Application\Service\ApplicationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ApplicationService(
			$serviceLocator->get('Application\Mapper\ApplicationMapperInterface')
		);
	}
	
}