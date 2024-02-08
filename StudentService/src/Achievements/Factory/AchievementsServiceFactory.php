<?php

namespace Achievements\Factory;

use Achievements\Service\AchievementsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AchievementsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AchievementsService(
			$serviceLocator->get('Achievements\Mapper\AchievementsMapperInterface')
		);
	}
	
}