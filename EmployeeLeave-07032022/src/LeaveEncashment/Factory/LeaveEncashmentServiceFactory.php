<?php

namespace LeaveEncashment\Factory;

use LeaveEncashment\Service\LeaveEncashmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeaveEncashmentServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new LeaveEncashmentService(
			$serviceLocator->get('LeaveEncashment\Mapper\LeaveEncashmentMapperInterface')
		);
	}
	
}