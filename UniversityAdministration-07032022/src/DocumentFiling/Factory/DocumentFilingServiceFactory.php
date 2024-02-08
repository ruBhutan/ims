<?php

namespace DocumentFiling\Factory;

use DocumentFiling\Service\DocumentFilingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DocumentFilingServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new DocumentFilingService(
			$serviceLocator->get('DocumentFiling\Mapper\DocumentFilingMapperInterface')
		);
	}
	
}