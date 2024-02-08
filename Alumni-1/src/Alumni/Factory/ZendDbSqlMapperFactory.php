<?php

namespace Alumni\Factory;

use Alumni\Mapper\ZendDbSqlMapper;
use Alumni\Model\Alumni;
use Alumni\Model\AlumniStudent;
use Alumni\Model\CreateAlumniEvent;
use Alumni\Model\AlumniResource;
use Alumni\Model\AlumniProfile;
use Alumni\Model\AlumniEnquiry;
use Alumni\Model\AlumniFaqs;
use Alumni\Model\AlumniContribution;
use Alumni\Model\AlumniSubscriptionDetails;
use Alumni\Model\UpdateAlumniSubscriberDetails;
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
			//new \stdClass(),
			new Alumni()
		);
	}	
}