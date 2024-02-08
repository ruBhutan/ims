<?php

namespace Examinations\Factory;

use Examinations\Service\ExaminationsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExaminationsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ExaminationsService(
			$serviceLocator->get('Examinations\Mapper\ExaminationsMapperInterface')
		);
	}
	
}