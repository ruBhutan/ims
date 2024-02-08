<?php

namespace Masters\Factory;

use Masters\Controller\MastersController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MastersControllerFactory implements FactoryInterface
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
		$mastersService = $realServiceLocator->get('Masters\Service\MastersServiceInterface');
		
		return new MastersController($mastersService);
	}
	
}