<?php

namespace HrSettings\Mapper;

use HrSettings\Model\Job;
use HrSettings\Model\EmploymentStatus;
use HrSettings\Model\MajorOccupationalGroup;
use HrSettings\Model\PayScale;
use HrSettings\Model\PositionCategory;
use HrSettings\Model\PositionLevel;
use HrSettings\Model\PositionTitle;
use HrSettings\Model\RentAllowance;
use HrSettings\Model\UniversityAllowance;
use HrSettings\Model\TeachingAllowance;
use HrSettings\Model\FundingCategory;
use HrSettings\Model\StudyLevelCategory;
use HrSettings\Model\ResearchCategory;
use HrSettings\Model\EmpAwardCategory;
use HrSettings\Model\EmpCommunityServiceCategory;
use HrSettings\Model\EmpContributionCategory;
use HrSettings\Model\EmpResponsibilityCategory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements HrSettingsMapperInterface
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
	 * @var \HrSettings\Model\JobInterface
	*/
	protected $jobPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Job $jobPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->jobPrototype = $jobPrototype;
	}

	/*
	* take username and returns organisation id
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
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$name = $set['first_name']." ".$set['middle_name']." ".$set['last_name'];
		}
		
		return $name;
	}

	public function getUserImage($username, $usertype)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('profile_picture', 'first_name', 'middle_name', 'last_name'));
		}		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$img_location = $set['profile_picture'];
		}
		
		return $img_location;
	}
	
	/**
	* @param int/String $id
	* @return HrSettings
	* @throws \InvalidArgumentException
	*/
	
	public function find($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select($tableName);
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->jobPrototype);
		}

		throw new \InvalidArgumentException("HrSettings with given ID: ($id) not found");
	}
	
	/**
	* @return array/HrSettings()
	*/
	public function findAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_title')) // base table
				->join(array('t2' => 'occupational_subgroup'), // join table with alias
						't2.id = t1.occupational_subgroup_id', array('occupational_subgroup')); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the HrSettings for a given $id
	 */
	public function findDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'position_title'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				$resultSet->buffer();
				return $resultSet->initialize($result); 
		}
		
		return array();
	}
		
	/**
	 * 
	 * @param type $JobInterface
	 * 
	 * to save job
	 */
	
	public function saveDetails(HrSettings $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($empWorkForcePropsalData['id']);
		
		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_title');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_title');
			$action->values($jobData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * Should return a set of position titles
	 */
	 
	 public function findPositionTitles()
	 {
		 $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_title')) // base table
				->join(array('t2' => 'position_category'), // join table with alias
						't2.id = t1.position_category_id', array('category')); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /**
	 * Should return a set of position levels
	 */
	 
	 public function findPositionLevels()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_level'))
				->join(array('t2' => 'major_occupational_group'), // join table with alias
						't2.id = t1.major_occupational_group_id', array('major_occupational_group')); // join expression

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /**
	 * Should return a set of teachng allowances
	 */
	 
	 public function findTeachingAllowances()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'teaching_allowance'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level', array('position_level'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
			return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /**
	 * Should return a set of Pay Scale
	 */
	 
	 public function findPayScales()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pay_scale'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level', array('position_level'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
			return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /**
	 * Should return a set of Employment Status
	 */
	 
	 public function findEmploymentStatus()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pay_scale'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
			return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /*
	 * Get list of study level
	 */
	 
	 public function findStudyLevel()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'study_level'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	 
	 /*
	 * get list of funding categories
	 */
	 
	 public function findFundingCategory()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'funding_category'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	 
	 /*
	 * get list of research types
	 */
	 
	 public function findResearchType()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_category'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	 
	 /*
	 * Should return a set of Position Category
	 */
	 
	 public function findPositionCategory()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_category'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	 }
	 
	 /**
	 * Should return a set of rent allowances
	 */
	 
	 public function findRentAllowances()
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'housing_allowance'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level', array('position_level'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	 }


	 public function findUniversityAllowances()
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'professional_allowance'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level', array('position_level'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->jobPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	 }


	  public function findHrSettings($id, $tableName)
	  {
	  	$sql = new Sql($this->dbAdapter);
	 	$select = $sql->select();

	 	if($tableName == 'position_title'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'position_category'),
	 					't2.id = t1.position_category_id', array('category'));
			$select->where(array('t1.id = ? ' => $id));
	 	}
	 	if($tableName == 'position_level'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'major_occupational_group'),
	 					't2.id = t1.major_occupational_group_id', array('major_occupational_group'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	if($tableName == 'teaching_allowance'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'position_level'),
	 					't2.id = t1.position_level', array('pposition_level' => 'position_level'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	if($tableName == 'housing_allowance'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'position_level'),
	 					't2.id = t1.position_level', array('pposition_level' => 'position_level'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	if($tableName == 'professional_allowance'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'position_level'),
	 					't2.id = t1.position_level', array('pposition_level' => 'position_level'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	if($tableName == 'pay_scale'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'position_level'),
	 					't2.id = t1.position_level', array('pposition_level' => 'position_level'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	if($tableName == 'position_category'){
	 		$select->from(array('t1' => $tableName))
	 			   ->join(array('t2' => 'major_occupational_group'),
	 					't2.id = t1.major_occupational_group_id', array('major_occupational_group'));
			$select->where(array('t1.id = ? ' => $id));
	 	}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->jobPrototype);
		}

		throw new \InvalidArgumentException("HR with given ID: ($id) not found");
	  }


	 public function findAwardCategory($organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_award_category'))
				->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }



	 public function findCommunityServiceCategory($organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_community_service_category'))
				->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }

	 public function findContributionCategory($organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_contribution_category'))
				->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }

	 public function findResponsibilityCategory($organisation_id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_responsibility_category'))
				->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }


	 public function findHrOtherSetting($id, $tableName)
	 {
	 	$sql = new Sql($this->dbAdapter);
	 	$select = $sql->select();

	 	if($tableName == 'emp_award_category'){
	 		$select->from(array('t1' => $tableName));
			$select->where(array('t1.id = ? ' => $id));
	 	}
	 	else if($tableName == 'emp_community_service_category'){
	 		$select->from(array('t1' => $tableName));
			$select->where(array('t1.id = ? ' => $id));
	 	}
	 	else if($tableName == 'emp_contribution_category'){
	 		$select->from(array('t1' => $tableName));
			$select->where(array('t1.id = ? ' => $id));
	 	}

	 	else if ($tableName == 'emp_responsibility_category') {
	 		$select->from(array('t1' => $tableName));
			$select->where(array('t1.id = ? ' => $id));
	 	}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->jobPrototype);
		}

		throw new \InvalidArgumentException("Category with given ID: ($id) not found");
	 }
	 
	 /*
	 * save employment status
	 */
	  
	 public function saveEmploymentStatus(EmploymentStatus $employmentObject)
	 {
		$employmentData = $this->hydrator->extract($employmentObject);
		unset($employmentData['id']);
		
		if($employmentObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_title');
			$action->set($employmentData);
			$action->where(array('id = ?' => $employmentObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_title');
			$action->values($employmentData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employmentObject->setId($newId);
			}
			return $employmentObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Occupational Group
	 */
	 
	 public function saveOccupationalGroup(MajorOccupationalGroup $occupationalObject)
	 {
		$occupationalData = $this->hydrator->extract($occupationalObject);
		unset($occupationalData['id']);
		
		if($occupationalObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_title');
			$action->set($occupationalData);
			$action->where(array('id = ?' => $occupationalObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_title');
			$action->values($occupationalData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$occupationalObject->setId($newId);
			}
			return $occupationalObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Pay Scale
	 */
	 
	 public function savePayScale(PayScale $payObject)
	 {
		$payData = $this->hydrator->extract($payObject);
		unset($payData['id']);
		
		if($payObject->getId()) {
			//ID present, so it is an update
			$action = new Update('pay_scale');
			$action->set($payData);
			$action->where(array('id = ?' => $payObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('pay_scale');
			$action->values($payData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$payObject->setId($newId);
			}
			return $payObject;
		}
		
		throw new \Exception("Database Error"); 
	 }
	 
	 /*
	 * save Position Category
	 */
	 
	 public function savePositionCategory(PositionCategory $categoryObject)
	 {
		$categoryData = $this->hydrator->extract($categoryObject);
		unset($categoryData['id']);
		
		if($categoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_category');
			$action->set($categoryData);
			$action->where(array('id = ?' => $categoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_category');
			$action->values($categoryData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$categoryObject->setId($newId);
			}
			return $categoryObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Position Level
	 */
	 
	 public function savePositionLevel(PositionLevel $positionObject)
	 {
		$positionData = $this->hydrator->extract($positionObject);
		unset($positionData['id']);
		
		if($positionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_level');
			$action->set($positionData);
			$action->where(array('id = ?' => $positionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_level');
			$action->values($positionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$positionObject->setId($newId);
			}
			return $positionObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Position Title
	 */
	 
	 public function savePositionTitle(PositionTitle $positionObject)
	 {
		$positionData = $this->hydrator->extract($positionObject);
		unset($positionData['id']);
		
		if($positionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('position_title');
			$action->set($positionData);
			$action->where(array('id = ?' => $positionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('position_title');
			$action->values($positionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$positionObject->setId($newId);
			}
			return $positionObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Rent Allowance
	 */
	 
	 public function saveRentAllowance(RentAllowance $allowanceObject)
	 {
		$allowanceData = $this->hydrator->extract($allowanceObject);
		unset($allowanceData['id']);
		
		if($allowanceObject->getId()) {
			//ID present, so it is an update
			$action = new Update('housing_allowance');
			$action->set($allowanceData);
			$action->where(array('id = ?' => $allowanceObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('housing_allowance');
			$action->values($allowanceData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$allowanceObject->setId($newId);
			}
			return $allowanceObject;
		}
		
		throw new \Exception("Database Error");
	 }


	 public function saveUniversityAllowance(UniversityAllowance $allowanceObject)
	 {
	 	$allowanceData = $this->hydrator->extract($allowanceObject);
		unset($allowanceData['id']);
		
		if($allowanceObject->getId()) {
			//ID present, so it is an update
			$action = new Update('professional_allowance');
			$action->set($allowanceData);
			$action->where(array('id = ?' => $allowanceObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('professional_allowance');
			$action->values($allowanceData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$allowanceObject->setId($newId);
			}
			return $allowanceObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * save Teaching Allowance
	 */
	 
	 public function saveTeachingAllowance(TeachingAllowance $allowanceObject)
	 {
		$allowanceData = $this->hydrator->extract($allowanceObject);
		unset($allowanceData['id']);
		
		if($allowanceObject->getId()) {
			//ID present, so it is an update
			$action = new Update('teaching_allowance');
			$action->set($allowanceData);
			$action->where(array('id = ?' => $allowanceObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('teaching_allowance');
			$action->values($allowanceData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$allowanceObject->setId($newId);
			}
			return $allowanceObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 public function saveFunding(FundingCategory $fundingObject)
	 {
		$fundingData = $this->hydrator->extract($fundingObject);
		unset($fundingData['id']);
		
		if($fundingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('funding_category');
			$action->set($fundingData);
			$action->where(array('id = ?' => $fundingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('funding_category');
			$action->values($fundingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$fundingObject->setId($newId);
			}
			return $fundingObject;
		}
		throw new \Exception("Database Error");
	 }
	 
	 public function saveResearchCategory(ResearchCategory $categoryObject)
	 {
		 $researchData = $this->hydrator->extract($categoryObject);
		unset($researchData['id']);
		
		if($categoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_category');
			$action->set($researchData);
			$action->where(array('id = ?' => $categoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_category');
			$action->values($researchData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$categoryObject->setId($newId);
			}
			return $categoryObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 public function saveStudyLevel(StudyLevelCategory $studyObject)
	 {
		 $studyData = $this->hydrator->extract($studyObject);
		unset($studyData['id']);
		
		if($studyObject->getId()) {
			//ID present, so it is an update
			$action = new Update('study_level');
			$action->set($studyData);
			$action->where(array('id = ?' => $studyObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('study_level');
			$action->values($studyData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$studyObject->setId($newId);
			}
			return $studyObject;
		}
		throw new \Exception("Database Error");
	 }

	 public function saveAwardCategory(EmpAwardCategory $otherCategoryObject)
	 {
	 	$awardCategoryData = $this->hydrator->extract($otherCategoryObject);
		unset($awardCategoryData['id']);
		
		if($otherCategoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_award_category');
			$action->set($awardCategoryData);
			$action->where(array('id = ?' => $otherCategoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_award_category');
			$action->values($awardCategoryData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$otherCategoryObject->setId($newId);
			}
			return $otherCategoryObject;
		}
		throw new \Exception("Database Error");
	 }


	 public function saveCommunityServiceCategory(EmpCommunityServiceCategory $otherCategoryObject)
	 {
	 	$csCategoryData = $this->hydrator->extract($otherCategoryObject);
		unset($csCategoryData['id']);
		
		if($otherCategoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_community_service_category');
			$action->set($csCategoryData);
			$action->where(array('id = ?' => $otherCategoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_community_service_category');
			$action->values($csCategoryData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$otherCategoryObject->setId($newId);
			}
			return $otherCategoryObject;
		}
		throw new \Exception("Database Error");
	 }


	 public function saveContributionCategory(EmpContributionCategory $otherCategoryObject)
	 {
	 	$contributionCategoryData = $this->hydrator->extract($otherCategoryObject);
		unset($contributionCategoryData['id']);
		
		if($otherCategoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_contribution_category');
			$action->set($contributionCategoryData);
			$action->where(array('id = ?' => $otherCategoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_contribution_category');
			$action->values($contributionCategoryData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$otherCategoryObject->setId($newId);
			}
			return $otherCategoryObject;
		}
		throw new \Exception("Database Error");
	 }


	 public function saveResponsibilityCategory(EmpResponsibilityCategory $otherCategoryObject)
	 {
	 	$responsibilityCategoryData = $this->hydrator->extract($otherCategoryObject);
		unset($responsibilityCategoryData['id']);
		
		if($otherCategoryObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_responsibility_category');
			$action->set($responsibilityCategoryData);
			$action->where(array('id = ?' => $otherCategoryObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_responsibility_category');
			$action->values($responsibilityCategoryData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$otherCategoryObject->setId($newId);
			}
			return $otherCategoryObject;
		}
		throw new \Exception("Database Error");
	 }
	 
	 /**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//this is only for Position Level
		// do not need to match id with value
		// need 'Poistion Level 1' => 'Position Level 1'
		if($tableName == 'position_level')
		{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id', $columnName)); 
	
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$selectData = array();
			foreach($resultSet as $set)
			{
				$selectData[$set['id']] = $set[$columnName];
			}
			return $selectData;
		}

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 

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