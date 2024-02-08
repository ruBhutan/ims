<?php

namespace Voucher\Factory;

use Voucher\Service\VoucherService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VoucherServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new VoucherService(
			$serviceLocator->get('Voucher\Mapper\VoucherMapperInterface')
		);
	}
	
}