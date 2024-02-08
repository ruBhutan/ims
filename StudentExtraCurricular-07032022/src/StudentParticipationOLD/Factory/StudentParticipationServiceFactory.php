<?php

namespace StudentParticipation\Factory;

use StudentParticipation\Service\StudentParticipationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentParticipationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentParticipationService(
			$serviceLocator->get('StudentParticipation\Mapper\StudentParticipationMapperInterface')
		);
	}
	
}