<?php

namespace LeaveCategory\Factory;

use LeaveCategory\Service\LeaveCategoryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeaveCategoryServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new LeaveCategoryService(
			$serviceLocator->get('LeaveCategory\Mapper\LeaveCategoryMapperInterface')
		);
	}
	
}