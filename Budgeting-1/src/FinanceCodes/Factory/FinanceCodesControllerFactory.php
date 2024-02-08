<?php

namespace FinanceCodes\Factory;

use FinanceCodes\Controller\FinanceCodesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FinanceCodesControllerFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$codesService = $realServiceLocator->get('FinanceCodes\Service\FinanceCodesServiceInterface');
		
		return new FinanceCodesController($codesService);
	}
	
}