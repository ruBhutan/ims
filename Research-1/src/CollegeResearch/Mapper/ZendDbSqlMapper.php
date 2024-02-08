<?php

namespace CollegeResearch\Mapper;

use CollegeResearch\Model\CollegeResearch;
use CollegeResearch\Model\CargGrant;
use CollegeResearch\Model\CargResearch;
use CollegeResearch\Model\CargActionPlan;
use CollegeResearch\Model\CargAction;
use CollegeResearch\Model\ResearchRecommendation;
use CollegeResearch\Model\UpdateCargGrant;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements CollegeResearchMapperInterface
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
	 * @var \CollegeResearch\Model\CollegeResearchInterface
	*/
	protected $collegeResearchPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			CollegeResearch $collegeResearchPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->collegeResearchPrototype = $collegeResearchPrototype;
	}
	
	/**
	* @param int/String $id
	* @return CollegeResearch
	* @throws \InvalidArgumentException
	*/
	
	public function find($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('carg_grant');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->collegeResearchPrototype);
            }

            throw new \InvalidArgumentException("College Grant with given ID: ($id) not found");
	}
	
	/**
	* @return array/CollegeResearch()
	*/
	public function findAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'carg_grant')); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->collegeResearchPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the CollegeResearch Proposal for a given $id
	 */
	public function findDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'carg_grant'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->collegeResearchPrototype);
				$resultSet->buffer();
				return $resultSet->initialize($result); 
		}
		
		return array();
	}
		
	/**
	 * 
	 * @param type $CollegeResearchInterface
	 * 
	 * to save CollegeResearch proposals
	 */
	
	public function saveDetails(CollegeResearch $collegeResearchObject)
	{
		$collegeResearchData = $this->hydrator->extract($collegeResearchObject);
		unset($collegeResearchData['id']);
		
		if($collegeResearchObject->getId()) {
			//ID present, so it is an update
			$action = new Update('carg_grant');
			$action->set($collegeResearchData);
			$action->where(array('id = ?' => $collegeResearchObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('carg_grant');
			$action->values($collegeResearchData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$collegeResearchObject->setId($newId);
			}
			return $collegeResearchObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $CargGrant
	 * 
	 * to save Carg Grant proposals
	 */
	
	public function saveCargGrant(CargGrant $cargGrantObject)
	{
		$cargGrantData = $this->hydrator->extract($cargGrantObject);
		unset($cargGrantData['id']);

		//Need to extract coresearchers and insert into different table
		$data = $cargGrantData['coresearchers'];
		unset($cargGrantData['coresearchers']);
		
		
		if($cargGrantObject->getId()) {
			//ID present, so it is an update
			$action = new Update('carg_grant');
			$action->set($cargGrantData);
			$action->where(array('id = ?' => $cargGrantObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('carg_grant');
			$action->values($cargGrantData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$cargGrantObject->setId($newId);
				//the following loop is to insert coresearchers
				if($data != NULL)
				{
					foreach($data as $value)
					{
						$action = new Insert('carg_coresearchers');
						$action->values(array(
							'name'=> $value->getName(),
							'researcher_type' => $value->getResearcher_Type(),
							'qualification' => $value->getQualification(),
							'position_level' => $value->getPosition_Level(),
							'appointment_date' => $value->getAppointment_Date(),
							'email' => $value->getEmail(),
							'contact_no' => $value->getContact_No(),
							'researcher_category' => $value->getResearcher_Category(),
							'carg_grant_id' => $newId,
						));
						
						$sql = new Sql($this->dbAdapter);
						$stmt = $sql->prepareStatementForSqlObject($action);
						$result = $stmt->execute();
					}
				}
			}
			return $cargGrantObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $CollegeResearchInterface
	 * 
	 * to save CollegeResearch proposals
	 */
	
	public function saveCargProject(CargResearch $cargProjectObject)
	{
		$cargProjectData = $this->hydrator->extract($cargProjectObject);
		$id = $cargProjectData['id'];
		unset($cargProjectData['id']);
		
		$data = $cargProjectData['actionplan'];
		unset($cargProjectData['actionplan']);

		if($cargProjectObject->getId()) 
		{
			//ID present, so it is an update
			$action = new Update('carg_grant');
			$action->set($cargProjectData);
			$action->where(array('id = ?' => $cargProjectObject->getId()));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		} 
		
		//the following loop is to insert action plan
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('carg_action_plan');
				$action->values(array(
					'activity_name'=> $value->getActivity_Name(),
					'time_frame' => $value->getTime_Frame(),
					'remarks' => $value->getRemarks(),
					'carg_grant_id' => $id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$cargProjectObject->setId($newId);
			}
			return $cargProjectObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $CollegeResearchInterface
	 * 
	 * to save CollegeResearch proposals
	 */
	
	public function saveCargActionPlan(CargAction $cargActionPlanObject)
	{
		$cargActionPlanData = $this->hydrator->extract($cargActionPlanObject);
		$id = $cargActionPlanData['id'];
		unset($cargActionPlanData['id']);
		
		$data = $cargActionPlanData['budgetplan'];
		unset($cargActionPlanData['budgetplan']);

		//need to get the file locations and store them in database
		$researcher_file_name = $cargActionPlanData['signed_Certification_Researchers'];
		$cargActionPlanData['signed_Certification_Researchers'] = $researcher_file_name['tmp_name'];
		
		$crc_file_name = $cargActionPlanData['signed_Certification_Crc'];
		$cargActionPlanData['signed_Certification_Crc'] = $crc_file_name['tmp_name'];
		
		$document_file_name = $cargActionPlanData['research_Proposal'];
		$cargActionPlanData['research_Proposal'] = $document_file_name['tmp_name'];
				
		if($cargActionPlanObject->getId()) 
		{
			//ID present, so it is an update
			$action = new Update('carg_grant');
			$action->set($cargActionPlanData);
			$action->where(array('id = ?' => $cargActionPlanObject->getId()));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		} 

		//the following loop is to insert budget plan
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('carg_budget_plan');
				$action->values(array(
					'purpose'=> $value->getPurpose(),
					'amount' => $value->getAmount(),
					'remarks' => $value->getRemarks(),
					'carg_grant_id' => $id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$cargActionPlanObject->setId($newId);
			}
			return $cargActionPlanObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateCargGrant(UpdateCargGrant $cargActionPlanObject)
	{
		$cargStatusData = $this->hydrator->extract($cargActionPlanObject);
		unset($cargStatusData['id']); 

		$carg_evidence_file = $cargStatusData['carg_Evidence_File'];
		$cargStatusData['carg_Evidence_File'] = $carg_evidence_file['tmp_name'];
		
		if($cargActionPlanObject->getId()) {
			//ID present, so it is an update
			$action = new Update('carg_grant_application_status');
			$action->set($cargStatusData);
			$action->where(array('id = ?' => $cargActionPlanObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('carg_grant_application_status');
			$action->values($cargStatusData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $cargActionPlanObject->setId($newId);
			}
			return $cargActionPlanObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save the Recommendation from the Reviewers
	 */
	 
	public function saveRecommendation(ResearchRecommendation $recommendationObject)
	{
		$recommendationData = $this->hydrator->extract($recommendationObject);
		unset($recommendationData['id']);
		
		$action = new Update('carg_grant');
		$action->set($recommendationData);
		$action->where(array('id = ?' => $recommendationObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$recommendationObject->setId($newId);
			}
			return $recommendationObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveResearchApproval(ResearchRecommendation $collegeResearchObject)
	{
		$collegeResearchData = $this->hydrator->extract($collegeResearchObject);
		unset($collegeResearchData['id']);
		
		//unset($collegeResearchData['application_Status']);
		if($collegeResearchData['application_Status'] == 'Approved'){
			$collegeResearchData['application_Status'] = 'Approved by CRC';
		} else {
			$collegeResearchData['application_Status'] = 'Rejected by CRC';
		}
		
		$action = new Update('carg_grant');
		$action->set($collegeResearchData);
		$action->where(array('id = ?' => $collegeResearchObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$collegeResearchObject->setId($newId);
			}
			return $collegeResearchObject;
		}
		throw new \Exception("Database Error");
	}
        
   /*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('id'));
		}		
			
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

	/*
	* Get organisation id based on the username
	*/
	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('organisation_id'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('organisation_id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get employee details
	*/
	
	public function getEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t3' => 'employee_details'))
					->columns(array('first_name','middle_name','last_name','emp_id','recruitment_date', 'email', 'phone_no'))
					->join(array('t4' => 'emp_position_level'), 
						't3.id = t4.employee_details_id', array('position_level_id'))
					->join(array('t5' => 'position_level'), 
						't4.position_level_id = t5.id', array('position_level'))
					->join(array('t6' => 'organisation'), 
						't3.organisation_id = t6.id', array('organisation_name'))
					->where('t3.id = ' .$id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Research Grant List
	*/
	
	public function getResearchGrantList()
	{
		$category = 'College Grant';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_grant_announcement'))
				->join(array('t2' => 'research_type'), 
                            't1.research_grant_type = t2.id', array('id', 'grant_type'));
		$select->where(array('t1.end_date >= ? ' => date('Y-m-d'), 't1.start_date <= ?' => date('Y-m-d')));
		$select->where(array('t2.grant_category = ?' => $category));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$selectData = array();
		foreach($resultSet as $set){
			$selectData[$set['id']] = $set['grant_type'];
		}
		return $selectData;
	}
	
	/*
	* Get Research Grant Announcement
	*/
	 
	public function getResearchGrantAnnouncement($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_grant_announcement'))
				->join(array('t2' => 'research_type'), 
                            't1.research_grant_type = t2.id', array('grant_type'));
		if($id != NULL){
			if($id == 'College Grant' || $id == 'University Grant'){
				$select->where(array('t2.grant_category = ?' => $id));
				$select->order(array('t1.end_date DESC'));
				$select->limit(1);
			}
			else
				$select->where(array('id = ?' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of CARG - from the search form
	*/
	
	public function getCargList($researcher_name, $research_title, $grant_type, $status, $organisation_id)
	{
		//$research_list = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select(); 

		$select->from(array('t1' => 'carg_grant')) 
				->columns(array('id','research_title','grant_applied_for','research_year','application_status'))
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id', 'email', 'phone_no', 'recruitment_date'))
				->join(array('t3' => 'organisation'), 
						't2.organisation_id = t3.id', array('organisation_name'))
				->join(array('t4' => 'research_type'), 
						't1.grant_type = t4.id', array('grant_category'));
		$select->where(array('t2.organisation_id' => $organisation_id, 't1.application_step_status' => 'Complete', 't1.application_status' => $status));

		if($researcher_name){
			$select->where->like('t2.first_name','%'.$researcher_name.'%');
		}
		if($research_title){
			if($status != NULL)
				$select->where(array('t1.research_title' =>$research_title, 't1.application_status' => $status));
			else
				$select->where(array('t1.research_title' =>$research_title));
		}
		if($grant_type){
			if($status != NULL){
				$select->where(array('t1.grant_applied_for' =>$grant_type, 't1.application_status' => $status));
			}
			else {
				$select->where(array('t1.grant_applied_for' =>$grant_type));
			}
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	 * Get the list of AURG grant for updating
	 */
	 
	public function getAurgList($researcher_name, $research_title, $grant_type, $status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'aurg_grant')) 
				->columns(array('id','research_title','grant_applied_for','research_year','application_status'))
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
				->join(array('t3' => 'organisation'), 
						't2.organisation_id = t3.id', array('organisation_name'))
				->join(array('t4' => 'research_type'), 
						't1.grant_type = t4.id', array('grant_category'));
		$select->where(array('t2.organisation_id' =>$organisation_id));
		if($researcher_name){
			$select->where->like('t2.first_name','%'.$researcher_name.'%');
		}
		if($research_title){
			if($status != NULL)
				$select->where(array('t1.research_title' =>$research_title, 't1.application_status' => $status));
			else
				$select->where(array('t1.research_title' =>$research_title));
		}
		if($grant_type){
			if($status != NULL){
				$select->where(array('t1.grant_applied_for' =>$grant_type, 't1.application_status' => $status));
			}
			else {
				$select->where(array('t1.grant_applied_for' =>$grant_type));
			}
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the UniversityResearch Proposal for a given $id
	 */
	public function findResearchDetails($id, $tableName) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'carg_coresearchers'){
			$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.carg_grant_id', array('name', 'researcher_type', 'qualification', 'position_level', 'appointment_date', 'email', 'contact_no', 'researcher_category'))
					->where('t1.id = ' .$id);
		}
		else if($tableName == 'carg_action_plan'){
			$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.carg_grant_id', array('activity_name', 'time_frame', 'remarks'))
					->where('t1.id = ' .$id);
		}
		else if($tableName == 'carg_budget_plan'){
			$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.carg_grant_id', array('purpose', 'amount', 'remarks'))
					->where('t1.id = ' .$id);
		}

		else if($tableName == 'employee_details'){
			$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','recruitment_date', 'email', 'phone_no'))
					->join(array('t3' => 'emp_position_level'), 
						't2.id = t3.employee_details_id', array('position_level_id'))
					->join(array('t4' => 'position_level'), 
						't3.position_level_id = t4.id', array('position_level'))
					->where('t1.id = ' .$id);
		}
		
		else if($tableName == 'carg_grant'){
			$select->from(array('t1' => 'carg_grant'))
					->where('t1.id = ' .$id);
		}
		/*$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.carg_grant_id')
					->join(array('t3' => 'employee_details'), 
						't1.employee_details_id = t3.id', array('first_name','middle_name','last_name','emp_id','recruitment_date', 'email', 'phone_no'))
					->join(array('t4' => 'emp_position_level'), 
						't3.id = t4.employee_details_id', array('position_level_id'))
					->join(array('t5' => 'position_level'), 
						't4.position_level_id = t5.id', array('position_level'))
					->where('t1.id = ' .$id);*/
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return	$resultSet->initialize($result);

	}
	
	/*
	 * Get the location of the file name 
	 */
	 
	public function getFileName($training_id, $column_name, $research_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		if($research_type == 'carg'){
			$select->from(array('t1' => 'carg_grant')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$training_id);
		}
		else{
			$select->from(array('t1' => 'aurg_grant')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$training_id);

		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
}
