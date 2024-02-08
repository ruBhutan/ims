<?php

namespace StudentReports\Factory;

use StudentReports\Controller\StudentReportsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentReportsControllerFactory implements FactoryInterface
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
		$studentreportService = $realServiceLocator->get('StudentReports\Service\StudentReportsServiceInterface');
		
		return new StudentReportsController($studentreportService, $realServiceLocator);
	}
	
}