<?php

namespace Appraisal\Factory;

use Appraisal\Service\AppraisalService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AppraisalServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AppraisalService(
			$serviceLocator->get('Appraisal\Mapper\AppraisalMapperInterface')
		);
	}
	
}