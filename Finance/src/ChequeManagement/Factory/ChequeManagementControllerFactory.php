<?php

namespace ChequeManagement\Factory;

use ChequeManagement\Controller\ChequeManagementController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChequeManagementControllerFactory implements FactoryInterface
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
		$chequeService = $realServiceLocator->get('ChequeManagement\Service\ChequeManagementServiceInterface');
		
		return new ChequeManagementController($chequeService);
	}
	
}