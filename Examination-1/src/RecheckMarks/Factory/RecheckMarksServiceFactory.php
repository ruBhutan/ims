<?php

namespace RecheckMarks\Factory;

use RecheckMarks\Service\RecheckMarksService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RecheckMarksServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new RecheckMarksService(
			$serviceLocator->get('RecheckMarks\Mapper\RecheckMarksMapperInterface')
		);
	}
	
}