<?php

namespace StudentAdmission\Factory;

use StudentAdmission\Mapper\ZendDbSqlMapper;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\UpdateStudent;
use StudentAdmission\Model\UpdateReportedStudentDetails;
use StudentAdmission\Model\StudentType;
use StudentAdmission\Model\StudentCategory;
use StudentAdmission\Model\UploadStudentLists;
use StudentAdmission\Model\StudentSemesterRegistration;


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
			new StudentAdmission()
		);
	}	
}