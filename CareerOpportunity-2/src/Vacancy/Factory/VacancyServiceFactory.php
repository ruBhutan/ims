<?php

namespace Vacancy\Factory;

use Vacancy\Service\VacancyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VacancyServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new VacancyService(
			$serviceLocator->get('Vacancy\Mapper\VacancyMapperInterface')
		);
	}
	
}