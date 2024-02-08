<?php

namespace Reports\Factory;

use Reports\Controller\ReportsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReportsControllerFactory implements FactoryInterface
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
		$reportService = $realServiceLocator->get('Reports\Service\ReportsServiceInterface');
		
		return new ReportsController($reportService, $realServiceLocator);
	}
	
}