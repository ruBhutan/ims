<?php

namespace GoodsRequisition\Factory;

use GoodsRequisition\Service\GoodsRequisitionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RequisitionServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new GoodsRequisitionService(
			$serviceLocator->get('GoodsRequisition\Mapper\GoodsRequisitionMapperInterface')
		);
	}
	
}