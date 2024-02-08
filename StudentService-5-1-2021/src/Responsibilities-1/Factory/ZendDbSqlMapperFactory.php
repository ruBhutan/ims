<?php

namespace Responsibilities\Factory;

use Responsibilities\Mapper\ZendDbSqlMapper;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;
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
			new StudentResponsibility(),
			new ResponsibilityCategory()
		);
	}
	
	
}