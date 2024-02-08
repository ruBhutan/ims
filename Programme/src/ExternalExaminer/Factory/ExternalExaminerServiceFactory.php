<?php

namespace ExternalExaminer\Factory;

use ExternalExaminer\Service\ExternalExaminerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExternalExaminerServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ExternalExaminerService(
			$serviceLocator->get('ExternalExaminer\Mapper\ExternalExaminerMapperInterface')
		);
	}
	
}