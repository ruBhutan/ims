<?php

namespace StudentImage\Factory;

use StudentImage\Service\StudentImageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentImageServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentImageService(
			$serviceLocator->get('StudentImage\Mapper\StudentImageMapperInterface')
		);
	}
	
}