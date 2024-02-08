<?php

namespace StudentContribution\Factory;

use StudentContribution\Service\StudentContributionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentContributionServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentContributionService(
			$serviceLocator->get('StudentContribution\Mapper\StudentContributionMapperInterface')
		);
	}
	
}