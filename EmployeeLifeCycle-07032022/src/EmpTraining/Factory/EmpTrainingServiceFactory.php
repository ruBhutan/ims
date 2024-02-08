<?php

namespace EmpTraining\Factory;

use EmpTraining\Service\EmpTrainingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpTrainingServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpTrainingService(
			$serviceLocator->get('EmpTraining\Mapper\EmpTrainingMapperInterface')
		);
	}
	
}