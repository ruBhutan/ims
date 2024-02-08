<?php

namespace Review\Factory;

use Review\Service\ReviewService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReviewServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ReviewService(
			$serviceLocator->get('Review\Mapper\ReviewMapperInterface')
		);
	}
	
}