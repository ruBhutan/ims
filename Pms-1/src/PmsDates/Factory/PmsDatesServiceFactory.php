<?php

namespace PmsDates\Factory;

use PmsDates\Service\PmsDatesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PmsDatesServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new PmsDatesService(
			$serviceLocator->get('PmsDates\Mapper\PmsDatesMapperInterface')
		);
	}
	
}