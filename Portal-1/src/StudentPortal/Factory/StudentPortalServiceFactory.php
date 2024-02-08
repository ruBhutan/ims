<?php

namespace StudentPortal\Factory;

use StudentPortal\Service\StudentPortalService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentPortalServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentPortalService(
			$serviceLocator->get('StudentPortal\Mapper\StudentPortalMapperInterface')
		);
	}
	
}