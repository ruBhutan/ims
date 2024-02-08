<?php

namespace StudentProfile\Factory;

use StudentProfile\Controller\StudentProfileController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentProfileControllerFactory implements FactoryInterface
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
		$studentProfileService = $realServiceLocator->get('StudentProfile\Service\StudentProfileServiceInterface');
		
		return new StudentProfileController($studentProfileService);
	}
	
}