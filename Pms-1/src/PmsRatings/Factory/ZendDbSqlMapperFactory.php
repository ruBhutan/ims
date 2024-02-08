<?php

namespace PmsRatings\Factory;

use PmsRatings\Mapper\ZendDbSqlMapper;
use PmsRatings\Model\Subordinate;
use PmsRatings\Model\Student;
use PmsRatings\Model\Peer;
use PmsRatings\Model\FeedbackQuestions;
use PmsRatings\Model\Beneficiary;
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
			new FeedbackQuestions()
		);
	}
	
	
}