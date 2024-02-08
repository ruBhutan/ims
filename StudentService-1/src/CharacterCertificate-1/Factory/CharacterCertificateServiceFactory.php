<?php

namespace CharacterCertificate\Factory;

use CharacterCertificate\Service\CharacterCertificateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CharacterCertificateServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new CharacterCertificateService(
			$serviceLocator->get('CharacterCertificate\Mapper\CharacterCertificateMapperInterface')
		);
	}
	
}