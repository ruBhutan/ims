<?php

namespace Voucher\Factory;

use Voucher\Controller\VoucherController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VoucherControllerFactory implements FactoryInterface
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
		$voucherService = $realServiceLocator->get('Voucher\Service\VoucherServiceInterface');
		
		return new VoucherController($voucherService);
	}
	
}