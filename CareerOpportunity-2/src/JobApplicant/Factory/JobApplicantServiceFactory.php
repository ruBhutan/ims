<?php

namespace JobApplicant\Factory;

use JobApplicant\Service\JobApplicantService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobApplicantServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new JobApplicantService(
			$serviceLocator->get('JobApplicant\Mapper\JobApplicantMapperInterface')
		);
	}
	
}