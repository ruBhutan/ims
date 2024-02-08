<?php

namespace UniversityResearch\Mapper;

use UniversityResearch\Model\AurgGrant;
use UniversityResearch\Model\AurgTitle;
use UniversityResearch\Model\AurgProjectDescription;
use UniversityResearch\Model\AurgActionPlan;
use UniversityResearch\Model\ResearchGrantAnnouncement;
use UniversityResearch\Model\ResearchRecommendation;
use UniversityResearch\Model\UpdateAurgGrant;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Group;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements UniversityResearchMapperInterface
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
	 * @var \UniversityResearch\Model\UniversityResearchInterface
	*/
	protected $aurgGrantPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			AurgGrant $aurgGrantPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->aurgGrantPrototype = $aurgGrantPrototype;
	}
	
	/**
	* @param int/String $id
	* @return UniversityResearch
	* @throws \InvalidArgumentException
	*/
	
	public function find($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('aurg_grant');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->aurgGrantPrototype);
		}

		throw new \InvalidArgumentException("AURG with given ID: ($id) not found");
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
	
	/**
	* @return array/UniversityResearch()
	*/
	public function findAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'aurg_grant')); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->aurgGrantPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
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
		if($tableName == 'employee_details'){
			$select->from(array('t1' => 'aurg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','organisation_id', 'phone_no', 'email', 'recruitment_date'))
					->join(array('t3' => 'emp_position_level'),
							't3.employee_details_id = t2.id', array('employee_details_id'))
					->join(array('t4' => 'position_level'), 
						't3.position_level_id = t4.id', array('position_level'))
					->join(array('t5' => 'organisation'), 
						't2.organisation_id = t5.id', array('organisation_name'))
					->where('t1.id = ' .$id);
		} elseif($tableName == 'carg_grant'){
			$select->from(array('t1' => $tableName))
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','organisation_id', 'phone_no', 'email', 'recruitment_date'))
					->join(array('t3' => 'emp_position_level'),
							't3.employee_details_id = t2.id', array('employee_details_id'))
					->join(array('t4' => 'position_level'), 
						't3.position_level_id = t4.id', array('position_level'))
					->join(array('t5' => 'organisation'), 
						't2.organisation_id = t5.id', array('organisation_name'))
					->where('t1.id = ' .$id);
		}
		else {
			$select->from(array('t1' => 'aurg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.aurg_grant_id')
					->where('t1.id = ' .$id);
		}
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return	$resultSet->initialize($result);

	}
	
	/*
	 * To get the employee details for CARG research
	 * common display of all grants
	 */
	
	public function findCargResearchDetails($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'carg_grant'))
                    ->join(array('t2' => $tableName), 
                            't1.id = t2.carg_grant_id')
					->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return	$resultSet->initialize($result);
	}
		
	/**
	 * 
	 * @param type $UniversityResearchInterface
	 * 
	 * to save UniversityResearch proposals
	 */
	
	public function saveAurgTitle(AurgTitle $aurgTitleObject)
	{
		$aurgTitleData = $this->hydrator->extract($aurgTitleObject); 
		unset($aurgTitleData['id']); 
				
		/*
		* Need to extract aurg co-researchers
		* and insert them into different table
		*/

		$data = $aurgTitleData['aurgresearchers'];
		unset($aurgTitleData['aurgresearchers']);

		$data2 = $aurgTitleData['actionplan']; 
		
		if($aurgTitleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('aurg_grant');
			$action->set($aurgTitleData);
			$action->where(array('id = ?' => $aurgTitleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('aurg_grant');
			$action->values($aurgTitleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$aurgTitleObject->setId($newId);
				//the following loop is to insert coresearchers
				if($data != NULL)
				{
					foreach($data as $value)
					{
						$action = new Insert('aurg_researchers');
						$action->values(array(
							'coresearcher_name'=> $value->getCoresearcher_Name(),
							'working_agency' => $value->getWorking_Agency(),
							'position_level' => $value->getPosition_Level(),
							'sex' => $value->getSex(),
							'email' => $value->getEmail(),
							'contact_no' => $value->getContact_No(),
							'aurg_grant_id' => $newId,
						));
						
						$sql = new Sql($this->dbAdapter);
						$stmt = $sql->prepareStatementForSqlObject($action);
						$result = $stmt->execute();
					}
				}
			}
			return $aurgTitleObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $UniversityResearchInterface
	 * 
	 * to save UniversityResearch proposals
	 */
	
	public function saveAurgProjectDescription(AurgProjectDescription $aurgProjectObject)
	{
		$aurgProjectData = $this->hydrator->extract($aurgProjectObject);
		
		if($aurgProjectObject->getId()) {
			//ID present, so it is an update
			$action = new Update('aurg_grant');
			$action->set($aurgProjectData);
			$action->where(array('id = ?' => $aurgProjectObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('aurg_grant');
			$action->values($aurgProjectData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$aurgProjectObject->setId($newId);
			}
			return $aurgProjectObject;
		}
		
		throw new \Exception("Database Error");
	}
     
	/**
	 * 
	 * @param type $UniversityResearchInterface
	 * 
	 * to save UniversityResearch proposals
	 */
	
	public function saveAurgActionPlan(AurgActionPlan $aurgPlanObject)
	{
		$aurgPlanData = $this->hydrator->extract($aurgPlanObject);
		$id = $aurgPlanData['id'];
		unset($aurgPlanData['id']);
		
		/*
		* Need to extract aurg action plan
		* and insert them into different table
		*/
		$related_documents = $aurgPlanData['related_Documents'];
		$aurgPlanData['related_Documents'] = $related_documents['tmp_name'];

		$data = $aurgPlanData['actionplan'];
		unset($aurgPlanData['actionplan']);
		//var_dump($aurgPlanData); die();
		if($aurgPlanObject->getId()) {
			//ID present, so it is an update
			$action = new Update('aurg_grant');
			$action->set($aurgPlanData);
			$action->where(array('id = ?' => $aurgPlanObject->getId()));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		//the following loop is to insert coresearchers
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('aurg_action_plan_budget');
				$action->values(array(
					'particulars'=> $value->getParticulars(),
					'start_date' => $value->getStart_Date(),
					'end_date' => $value->getEnd_Date(),
					'budget' => $value->getBudget(),
					'aurg_grant_id' => $id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		if($result instanceof ResultInterface) {
			return $aurgPlanObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateAurgStatus(UpdateAurgGrant $aurgPlanObject)
	{
		$aurgStatusData = $this->hydrator->extract($aurgPlanObject);
		unset($aurgStatusData['id']); 

		$aurg_evidence_file = $aurgStatusData['aurg_Evidence_File'];
		$aurgStatusData['aurg_Evidence_File'] = $aurg_evidence_file['tmp_name'];
                
		if($aurgPlanObject->getId()) {
			//ID present, so it is an update
			$action = new Update('aurg_grant_application_status');
			$action->set($aurgStatusData);
			$action->where(array('id = ?' => $aurgPlanObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('aurg_grant_application_status');
			$action->values($aurgStatusData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $aurgPlanObject->setId($newId);
			}
			return $aurgPlanObject;
		}
		
		throw new \Exception("Database Error");
	}

	/*
	* To get the employee details 
	*/
	
	public function getResearcherDetails($id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($type=='emp id'){
			$select->from(array('t1' => 'employee_details')); 
			$select->where(array('id = ?' => $id));
		} elseif($type=='aurg id'){
			//need to join with position level
			//presently no data exists
			$select->from(array('t1' => 'aurg_grant'))
					->columns(array('employee_details_id')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
                    ->where(array('t1.id = ?' => $id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getResearchGrantDetail($type, $research_grant_type)
	{
		if($type == 'Grant Type'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'research_type'))
					->columns(array('grant_type')) 
	                ->where(array('t1.id = ?' => $research_grant_type));	
	                		
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			$grant_type = NULL;
			foreach($resultSet as $set){
				$grant_type = $set['grant_type'];
			}
			return $grant_type;
		}
		else if($type == 'organisation'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'organisation'))
					->columns(array('organisation_name')) 
	                ->where(array('t1.id = ?' => $research_grant_type));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			$organisation_name = NULL;
			foreach($resultSet as $set){
				$organisation_name = $set['organisation_name'];
			}
			return $organisation_name;
		}
	}
	
	/*
	* Get All the research types
	*/
	
	public function getAllResearchTypes($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_type')); 
		$select->where(array('organisation_id' => $organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['grant_type'];
		}
		return $selectData;
	}


	public function getFileName($application_id, $column_name, $research_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		if($research_type == 'carg'){
			$select->from(array('t1' => 'carg_grant')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$application_id);
		}
		else{
			$select->from(array('t1' => 'aurg_grant')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$application_id);

		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * TO save Research Grant Announcement
	 */
	 
	public function saveResearchGrantAnnouncement(ResearchGrantAnnouncement $announcementObject)
	{
		$announcementData = $this->hydrator->extract($announcementObject);
		unset($announcementData['id']);

		$announcementData['start_Date'] = date("Y-m-d", strtotime(substr($announcementData['start_Date'],0,10)));
		$announcementData['end_Date'] = date("Y-m-d",strtotime(substr($announcementData['end_Date'],0,10)));
		
		if($announcementObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_grant_announcement');
			$action->set($announcementData);
			$action->where(array('id = ?' => $announcementObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_grant_announcement');
			$action->values($announcementData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$announcementObject->setId($newId);
			}
			return $announcementObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save the Recommendation from the Reviewers
	*/
	 
	public function saveRecommendation(ResearchRecommendation $recommendationObject)
	{
		
	}
	
	 /*
	 * Save Research Recommendation by DRER or DRIL
	 */
	 
	public function saveResearchRecommendation(ResearchRecommendation $recommendationObject, $approving_authority)
	{
		$recommendationData = $this->hydrator->extract($recommendationObject);
		unset($recommendationData['id']);
		
		if($approving_authority == 'dril'){
			//unset($recommendationData['application_Status']);
			//unset($recommendationData['remarks']);
			if($recommendationData['application_Status'] == 'Approved'){
				$recommendationData['application_Status'] = 'Approved by CRC';
			}else{
				$recommendationData['application_Status'] = 'Rejected by CRC';
			}
		}
		if($approving_authority == 'drer'){
			//unset($recommendationData['application_Status']); 
			//unset($recommendationData['remarks']);
			if($recommendationData['application_Status'] == 'Approved'){
				$recommendationData['application_Status'] = 'Approved by DRER';
			}else{
				$recommendationData['application_Status'] = 'Rejected by DRER';
			}
		}
		
		$action = new Update('aurg_grant');
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
	 
	/*
	* Get Research Grant Announcement
	*/
	 
	public function getResearchGrantAnnouncement($id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_grant_announcement'))
				->join(array('t2' => 'research_type'), 
                            't1.research_grant_type = t2.id', array('grant_type'));
		if($id == NULL){
			$select->where(array('t2.organisation_id' => $organisation_id));
		}
		else if($id != NULL){
			if($id == 'College Grant' || $id == 'University Grant'){
				$select->where(array('t2.grant_category = ?' => $id));
				$select->order(array('t1.end_date DESC'));
				$select->limit(1);
			}
			else
				$select->where(array('t1.id = ?' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Previus Research
	*/
	 
	public function getPreviousResearch($id)
	{
		//array to store the research
		$previous_research = array();
		$index=0;
		$employee_details_id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//get the employee details id and get the previous research for the employee id
		$select->from(array('t1' => 'aurg_grant'));
		$select->where(array('id = ?' => $id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_details_id = $set['employee_details_id'];
		}
		//only enter if not null
		if($employee_details_id){
			//get aurg grant
			$select2 = $sql->select();
			$select2->from(array('t1' => 'aurg_grant'));
			$select2->columns(array('research_year','amount_approved'));
			$select2->where(array('employee_details_id = ?' => $employee_details_id));
			$select2->order(array('research_year DESC'));
			$select2->limit(2);
			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$previous_research[$index]['research_type'] = 'aurg';
				$previous_research[$index]['research_year'] = $set2['research_year'];
				$previous_research[$index++]['amount'] = $set2['amount_approved'];
			}
			
			//get carg grant
			$select3 = $sql->select();
			$select3->from(array('t1' => 'carg_grant'));
			$select3->columns(array('research_year','crc_amount_granted'));
			$select3->where(array('employee_details_id = ?' => $employee_details_id));
			$select3->order(array('research_year DESC'));
			$select3->limit(2);
			$stmt3 = $sql->prepareStatementForSqlObject($select3);
			$result3 = $stmt3->execute();
			
			$resultSet3 = new ResultSet();
			$resultSet3->initialize($result3);
			foreach($resultSet3 as $set3){
				$previous_research[$index]['research_type'] = 'carg';
				$previous_research[$index]['research_year'] = $set3['research_year'];
				$previous_research[$index++]['amount'] = $set3['crc_amount_granted'];
			}
		}
		
		return $previous_research;
	}
	
	/*
	 * Get the list of AURG grant for updating
	 */
	 
	public function getAurgList($researcher_name, $research_title, $grant_type, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'aurg_grant')) 
				->columns(array('id','research_title','grant_applied_for','research_year','application_status'))
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
				->join(array('t3' => 'organisation'), 
						't2.organisation_id = t3.id', array('organisation_name'));
		$select->where(array('t1.application_step_status' => 'Complete'));
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
	 * Get Research list - for both AURG and CARG
	 */
	 
	 public function getResearchList($employee_id)
	 { 
		$research_list = array();
		$index = 0;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'aurg_grant')) 
				->columns(array('id','research_title','grant_applied_for','research_year','application_status', 'application_step_status'))
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
				->join(array('t3' => 'organisation'), 
						't2.organisation_id = t3.id', array('organisation_name'))
				->join(array('t4' => 'research_type'), 
						't1.grant_type = t4.id', array('grant_category'));
		$select->where(array('t1.employee_details_id' =>$employee_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$research_list[$index++] = $set;
			
		} 
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'carg_grant')) 
				->columns(array('id','research_title','grant_applied_for','research_year','application_status', 'application_step_status'))
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id', 'email', 'recruitment_date', 'phone_no'))
				->join(array('t3' => 'organisation'), 
						't2.organisation_id = t3.id', array('organisation_name'))
				->join(array('t4' => 'research_type'), 
						't1.grant_type = t4.id', array('grant_category'));
		$select2->where(array('t1.employee_details_id' =>$employee_id));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		//$resultSet2->initialize($result2);
		foreach($resultSet2 as $set2){
			$research_list[$index++] = $set2;
		}//var_dump($research_list); die();
		
		return $research_list;
	 }


	 public function deleteResearchGrantApplication($id, $type)
	 { 
		if($type == 'University_Grant'){
			$this->deleteAurgCoResearcher($id);
			$this->deleteAurgActionPlan($id);

			$action = new Delete('aurg_grant');
			$action->where(array('id = ?' => $id));
		}
		else if($type == 'College_Grant'){
			$this->deleteCargCoResearcher($id);
			$this->deleteCargActionPlan($id);
			$this->deleteCargBudgetPlan($id);

			$action = new Delete('carg_grant');
			$action->where(array('id = ?' => $id));
		}

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		//return (bool)$result->getAffectedRows();*/
	 }


	 public function deleteAurgCoResearcher($id)
	 {
		$aurg_action_plan = $this->getAurgApplicationList($id, 'aurg_coresearcher');

		//var_dump($aurg_action_plan); die();
		if(!empty($aurg_action_plan)){
			foreach($aurg_action_plan as $value){  //var_dump($value); die();

				$action = new Delete('aurg_researchers');
				$action->where(array('id = ?' => $value));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}else{
			return;
		}	
	 }


	 public function deleteAurgActionPlan($id)
	 {
		$aurg_action_plan = $this->getAurgApplicationList($id, 'aurg_action_plan');
		//var_dump($aurg_action_plan); die();
		if(!empty($aurg_action_plan)){
			foreach($aurg_action_plan as $value){  //var_dump($value); die();

				$action = new Delete('aurg_action_plan_budget');
				$action->where(array('id = ?' => $value));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}else{
			return;
		}	
	 }


	public function deleteCargCoResearcher($id)
	{
		$aurg_action_plan = $this->getAurgApplicationList($id, 'carg_coresearcher');
		//var_dump($aurg_action_plan); die();
		if(!empty($aurg_action_plan)){
			foreach($aurg_action_plan as $value){  //var_dump($value); die();

				$action = new Delete('carg_coresearchers');
				$action->where(array('id = ?' => $value));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}else{
			return;
		}
	}


	public function deleteCargActionPlan($id)
	{
		$aurg_action_plan = $this->getAurgApplicationList($id, 'carg_action_plan');
		//var_dump($aurg_action_plan); die();
		if(!empty($aurg_action_plan)){
			foreach($aurg_action_plan as $value){  //var_dump($value); die();

				$action = new Delete('carg_action_plan');
				$action->where(array('id = ?' => $value));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}else{
			return;
		}
	}


	public function deleteCargBudgetPlan($id)
	{
		$aurg_action_plan = $this->getAurgApplicationList($id, 'carg_budget_plan');
		//var_dump($aurg_action_plan); die();
		if(!empty($aurg_action_plan)){
			foreach($aurg_action_plan as $value){  //var_dump($value); die();

				$action = new Delete('carg_budget_plan');
				$action->where(array('id = ?' => $value));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}else{
			return;
		}
	}


	 public function getAurgApplicationList($id, $type)
	 {
		 $research_details = array();
		if($type == 'aurg_action_plan'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'aurg_action_plan_budget'));
			$select->where(array('t1.aurg_grant_id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 
			foreach($resultSet as $set){
				$research_details[] = $set['id']; 
			}
			return $research_details;
		}
		else if($type == 'aurg_coresearcher'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'aurg_researchers'));
			$select->where(array('t1.aurg_grant_id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 
			foreach($resultSet as $set){
				$research_details[] = $set['id']; 
			}
			return $research_details;
		}
		else if($type == 'carg_coresearcher'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'carg_coresearchers'));
			$select->where(array('t1.carg_grant_id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 
			foreach($resultSet as $set){
				$research_details[] = $set['id']; 
			}
			return $research_details;
		}
		else if($type == 'carg_action_plan'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'carg_action_plan'));
			$select->where(array('t1.carg_grant_id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 
			foreach($resultSet as $set){
				$research_details[] = $set['id']; 
			}
			return $research_details;
		}
		else if($type == 'carg_budget_plan'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'carg_budget_plan'));
			$select->where(array('t1.carg_grant_id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result); 
			foreach($resultSet as $set){
				$research_details[] = $set['id']; 
			}
			return $research_details;
		}
	 }

	
	/*
	* Get Research Grant List
	*/
	
	public function getResearchGrantList()
	{
		$category = 'University Grant';
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
			$research_list[$index]['grant_category'] = 'College Grant';
			$selectData[$set['id']] = $set['grant_type'];
		} 
		return $selectData;
	}
	
	/**
	* @return array/ResearchPublication()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
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
		return $selectData;
			
	}
        
}