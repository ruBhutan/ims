<?php

namespace EmpPromotion\Factory;

use EmpPromotion\Service\EmpPromotionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpPromotionServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpPromotionService(
			$serviceLocator->get('EmpPromotion\Mapper\EmpPromotionMapperInterface')
		);
	}
	
}