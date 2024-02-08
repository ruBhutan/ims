<?php

namespace EmpResignation\Factory;

use EmpResignation\Service\EmpResignationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpResignationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpResignationService(
			$serviceLocator->get('EmpResignation\Mapper\EmpResignationMapperInterface')
		);
	}
	
}