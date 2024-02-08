<?php

namespace GoodsDepreciation\Factory;

use GoodsDepreciation\Service\GoodsDepreciationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DepreciationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new GoodsDepreciationService(
			$serviceLocator->get('GoodsDepreciation\Mapper\GoodsDepreciationMapperInterface')
		);
	}
	
}