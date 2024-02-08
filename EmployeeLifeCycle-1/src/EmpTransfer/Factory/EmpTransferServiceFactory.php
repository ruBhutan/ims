<?php

namespace EmpTransfer\Factory;

use EmpTransfer\Service\EmpTransferService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpTransferServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpTransferService(
			$serviceLocator->get('EmpTransfer\Mapper\EmpTransferMapperInterface')
		);
	}
	
}