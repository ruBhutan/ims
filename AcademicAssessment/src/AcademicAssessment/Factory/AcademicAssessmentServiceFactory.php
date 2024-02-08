<?php

namespace AcademicAssessment\Factory;

use AcademicAssessment\Service\AcademicAssessmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicAssessmentServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AcademicAssessmentService(
			$serviceLocator->get('AcademicAssessment\Mapper\AcademicAssessmentMapperInterface')
		);
	}
	
}