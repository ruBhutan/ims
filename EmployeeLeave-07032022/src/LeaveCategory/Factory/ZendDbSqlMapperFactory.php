<?php

namespace LeaveCategory\Factory;

use LeaveCategory\Mapper\ZendDbSqlMapper;
use LeaveCategory\Model\LeaveCategory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZendDbSqlMapperFactory implements FactoryInterface
{
	/*
	* Create Service
	* @ param ServiceLocatorInterface $serviceLocator
	* @ return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ZendDbSqlMapper(
			$serviceLocator->get('Zend\Db\Adapter\Adapter'),
			new ClassMethods(false),
			new LeaveCategory()
		);
	}
	
	
}