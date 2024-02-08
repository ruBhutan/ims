<?php

namespace Programme\Factory;

use Programme\Service\ProgrammeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProgrammeServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ProgrammeService(
			$serviceLocator->get('Programme\Mapper\ProgrammeMapperInterface')
		);
	}
	
}