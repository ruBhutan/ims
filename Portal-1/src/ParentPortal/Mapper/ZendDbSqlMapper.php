<?php

namespace JobPortal\Mapper;

use JobPortal\Model\Awards;
use JobPortal\Model\PersonalDetails;
use JobPortal\Model\CommunityService;
use JobPortal\Model\Documents;
use JobPortal\Model\EducationDetails;
use JobPortal\Model\EmploymentDetails;
use JobPortal\Model\JobPortal;
use JobPortal\Model\LanguageSkills;
use JobPortal\Model\MembershipDetails;
use JobPortal\Model\PublicationDetails;
use JobPortal\Model\References;
use JobPortal\Model\TrainingDetails;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements JobPortalMapperInterface
{
	/**
	* @var \Zend\Db\Adapter\AdapterInterface
	*
	*/
	
	protected $dbAdapter;
	
	/*
	 * @var \Zend\Stdlib\Hydrator\HydratorInterface
	*/
	protected $hydrator;
	
	/*
	 * @var \JobPortal\Model\JobPortalInterface
	*/
	protected $jobPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			JobPortal $jobPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->jobPrototype = $jobPrototype;
	}
	
	
	/**
	* @return array/JobPortal()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Save Personal Details of Job Applicant
	*/
	
	public function savePersonalDetails(PersonalDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Education Details of Job Applicant
	*/
	
	public function saveEducationDetails(EducationDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_education');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_education');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_training_details');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_training_details');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save employment history of Job Applicant
	*/
	
	public function saveEmploymentRecord(EmploymentDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_employment_record');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_employment_record');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save membership such as board membership etc. of Job Applicant
	*/
	
	public function saveMembership(MembershipDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_memberships');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_memberships');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Community Service of the Job Applicant
	*/
	
	public function saveCommunityService(CommunityService $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_community');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_community');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Language skills of the Job Applicant
	*/
	
	public function saveLanguageSkills(LanguageSkills $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_languages');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_languages');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Publications
	*/
	
	public function savePublications(PublicationDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_research');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_research');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Awards
	*/
	
	public function saveAwards(Awards $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_awards');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_awards');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save References
	*/
	
	public function saveReferences(References $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_references');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_references');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Documents
	*/
	
	public function saveDocuments(Documents $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_documents');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_documents');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/JobPortal()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $condition)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		if($condition != NULL)
		{
			$select->where(array('organisation_id = ?' => $condition));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		//this if for Activities for AWPA Activities
		if($tableName == 'awpa_objectives_activity')
		{
			$selectData[0] = 'Others';
		}
		return $selectData;
			
	}
        
}