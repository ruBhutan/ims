<?php

namespace GoodsTransaction\Factory;

use GoodsTransaction\Service\GoodsTransactionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new GoodsTransactionService(
			$serviceLocator->get('GoodsTransaction\Mapper\GoodsTransactionMapperInterface')
		);
	}
	
}