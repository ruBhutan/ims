<?php

namespace MedicalRecord\Factory;

use MedicalRecord\Service\MedicalRecordService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MedicalRecordServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new MedicalRecordService(
			$serviceLocator->get('MedicalRecord\Mapper\MedicalRecordMapperInterface')
		);
	}
	
}