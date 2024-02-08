<?php

namespace UniversityResearch\Factory;

use UniversityResearch\Service\UniversityResearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UniversityResearchServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new UniversityResearchService(
			$serviceLocator->get('UniversityResearch\Mapper\UniversityResearchMapperInterface')
		);
	}
	
}