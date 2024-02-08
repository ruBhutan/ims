<?php

namespace EmpTravelAuthorization\Factory;

use EmpTravelAuthorization\Mapper\ZendDbSqlMapper;
use EmpTravelAuthorization\Model\EmpTravelAuthorization;
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
			new EmpTravelAuthorization()
		);
	}
	
	
}