<?php

namespace CollegeResearch\Factory;

use CollegeResearch\Service\CollegeResearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CollegeResearchServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new CollegeResearchService(
			$serviceLocator->get('CollegeResearch\Mapper\CollegeResearchMapperInterface')
		);
	}
	
}