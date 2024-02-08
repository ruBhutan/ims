<?php

namespace CounselingService\Factory;

use CounselingService\Service\CounselingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CounselingServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new CounselingService(
			$serviceLocator->get('CounselingService\Mapper\CounselingMapperInterface')
		);
	}
	
}