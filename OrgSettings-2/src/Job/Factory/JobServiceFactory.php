<?php

namespace Job\Factory;

use Job\Service\JobService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new JobService(
			$serviceLocator->get('Job\Mapper\JobMapperInterface')
		);
	}
	
}