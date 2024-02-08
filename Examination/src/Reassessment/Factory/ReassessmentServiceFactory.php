<?php

namespace Reassessment\Factory;

use Reassessment\Service\ReassessmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReassessmentServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ReassessmentService(
			$serviceLocator->get('Reassessment\Mapper\ReassessmentMapperInterface')
		);
	}
	
}