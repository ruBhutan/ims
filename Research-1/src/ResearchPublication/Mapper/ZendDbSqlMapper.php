<?php

namespace ResearchPublication\Mapper;

use ResearchPublication\Model\ResearchPublication;
use ResearchPublication\Model\PublicationType;
use ResearchPublication\Model\ResearchAnnouncement;
use ResearchPublication\Model\ResearchRecommendation;
use ResearchPublication\Model\ResearchType;
use ResearchPublication\Model\SeminarAnnouncement;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ResearchPublicationMapperInterface
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
	 * @var \ResearchPublication\Model\ResearchPublicationInterface
	*/
	protected $publicationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			ResearchPublication $publicationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->publicationPrototype = $publicationPrototype;
	}
	
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
					->join(array('t2' => 'organisation'), 
                            't1.organisation_id = t2.id', array('organisation_name'))
                    ->join(array('t3'=>'emp_position_level'),
                            't1.id = t3.employee_details_id', array('position_level_id'))
                    ->join(array('t4'=>'position_level'),
                            't3.position_level_id = t4.id', array('position_level'));
		$select->where(array('emp_id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
	
	/**
	* @param int/String $id
	* @return ResearchPublication
	* @throws \InvalidArgumentException
	*/
	
	public function findPublicationType($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('research_publication_types');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->publicationPrototype);
		}

		throw new \InvalidArgumentException("ResearchPublication Proposal with given ID: ($id) not found");
	}


	public function getResearchPublicationDetail($type, $research_publication_type)
	{
		if($type == 'Publication Type'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'research_publication_types'))
					->columns(array('publication_name')) 
	                ->where(array('t1.id = ?' => $research_publication_type));	
	                		
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			$publication_type = NULL;
			foreach($resultSet as $set){
				$publication_type = $set['publication_name'];
			}
			return $publication_type;
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
	
	/**
	* @param int/String $id
	* @return ResearchPublication
	* @throws \InvalidArgumentException
	*/
	
	public function findPublication($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select(); 
		$select->from(array('t1' => 'research_publication')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id', 'email'))
					->join(array('t3' => 'organisation'), 
                            't2.organisation_id = t3.id', array('organisation_name'))
                    ->join(array('t4'=>'research_publication_types'),
                            't1.publication_title = t4.id', array('publication_name'))
                    ->join(array('t5' => 'gender'),
                			't5.id = t2.gender', array('gender'))
                    ->join(array('t6' => 'emp_position_level'),
                			't6.employee_details_id = t2.id', array('position_level_id'))
                    ->join(array('t7' => 'position_level'),
                			't7.id = t6.position_level_id', array('position_level'))
                    ->where(array('t1.id = ? ' => $id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getResearchPublicationList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'research_publication')) 
                    ->join(array('t2' => 'research_publication_types'), 
                            't1.publication_title = t2.id', array('publication_name','publication_type'))
                    ->where(array('t1.employee_details_id = ? ' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getSeminarAnnouncementDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'research_seminar_details')) 
                    ->join(array('t2' => 'country'), 
							't1.seminar_country = t2.id', array('country'))
					->join(array('t3' => 'funding_category'),
							't1.funding_agency = t3.id', array('funding_type'))
                    ->where(array('t1.id' => $id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSemiarAnnouncementList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'research_seminar_details')) 
                    ->join(array('t2' => 'country'), 
							't1.seminar_country = t2.id', array('country'))
					->join(array('t3' => 'funding_category'),
							't1.funding_agency = t3.id', array('funding_type'))
                    ->where(array('t1.organisation_id' => $organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	
	/**
	* @return array/ResearchPublication()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        		
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveDetails(ResearchPublication $publicationObject)
	{
		$publicationData = $this->hydrator->extract($publicationObject);
		unset($publicationData['id']); 
		
		$file_name = $publicationData['publication_File'];
		$publicationData['publication_File'] = $file_name['tmp_name'];

		$publicationData['submission_Date'] = date("Y-m-d", strtotime(substr($publicationData['submission_Date'],0,10)));
		
		if($publicationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_publication');
			$action->set($publicationData);
			$action->where(array('id = ?' => $publicationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_publication');
			$action->values($publicationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $publicationObject->setId($newId);
			}
			return $publicationObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateResearchPublication(ResearchRecommendation $publicationObject)
	{
		$publicationData = $this->hydrator->extract($publicationObject);
		unset($publicationData['id']);
	
		//ID present, so it is an update
		$action = new Update('research_publication');
		$action->set($publicationData);
		$action->where(array('id = ?' => $publicationObject->getId()));
		
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $publicationObject->setId($newId);
			}
			return $publicationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveResearchAnnouncement(ResearchAnnouncement $publicationObject)
	{
		$publicationData = $this->hydrator->extract($publicationObject);
		unset($publicationData['id']);

		$publicationData['start_Date'] = date("Y-m-d", strtotime(substr($publicationData['start_Date'],0,10)));
		$publicationData['end_Date'] = date("Y-m-d",strtotime(substr($publicationData['end_Date'],0,10)));
		
		if($publicationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_publication_announcement');
			$action->set($publicationData);
			$action->where(array('id = ?' => $publicationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_publication_announcement');
			$action->values($publicationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $publicationObject->setId($newId);
			}
			return $publicationObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveSeminarAnnouncement(SeminarAnnouncement $publicationObject)
	{
		$publicationData = $this->hydrator->extract($publicationObject);
		unset($publicationData['id']);

		$publicationData['seminar_Start_Date'] = date("Y-m-d", strtotime(substr($publicationData['seminar_Start_Date'],0,10)));
		$publicationData['seminar_End_Date'] = date("Y-m-d", strtotime(substr($publicationData['seminar_End_Date'],0,10)));

		if($publicationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_seminar_details');
			$action->set($publicationData);
			$action->where(array('id = ?' => $publicationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_seminar_details');
			$action->values($publicationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $publicationObject->setId($newId);
			}
			return $publicationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $ResearchPublicationInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function savePublicationType(PublicationType $publicationObject)
	{
		$publicationData = $this->hydrator->extract($publicationObject);
		unset($publicationData['id']);
		
		if($publicationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_publication_types');
			$action->set($publicationData);
			$action->where(array('id = ?' => $publicationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_publication_types');
			$action->values($publicationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $publicationObject->setId($newId);
			}
			return $publicationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the Research Type for each organisation
	*/
	
	public function saveResearchType(ResearchType $researchObject)
	{
		$researchData = $this->hydrator->extract($researchObject);
		unset($researchData['id']);
		
		if($researchObject->getId()) {
			//ID present, so it is an update
			$action = new Update('research_type');
			$action->set($researchData);
			$action->where(array('id = ?' => $researchObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('research_type');
			$action->values($researchData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $researchObject->setId($newId);
			}
			return $researchObject;
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
		return $resultSet->initialize($result);
	}
	
	
	
	/*
	 * Get the list of publications based on type of publication, i.e. College or University
	 */
	 
	 public function getPublicationList($type)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_publication')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name'))
					->join(array('t3' => 'organisation'), 
                            't2.organisation_id = t3.id', array('organisation_name'))
                    ->join(array('t4' => 'research_publication_types'),
                            't1.publication_title = t4.id', array('publication_name', 'publication_type'))
                    ->where(array('t4.publication_type' => $type));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	 }
	
	/*
	* Get the file name given the $id so that user can download
	*/
	 
	public function getFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_publication'));
		$select->columns(array('publication_file'));
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$fileLocation;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['publication_file'];
		}
		
		return $fileLocation;
	}
	
	/*
	* Generic function to get the details given an id and table name
	*/
	
	public function getDetails($id, $table_name)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $table_name)); 
		$select->where(array('id' => $id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getResearchPublicationAnnouncement($id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_publication_announcement'))
				->join(array('t2' => 'research_publication_types'), 
                            't1.research_publication_type = t2.id', array('publication_name', 'publication_type', 'remarks', 'organisation_id'));
		if($id == NULL){
			$select->where(array('t2.organisation_id' => $organisation_id));
		}
		else if($id != NULL){
				$select->where(array('t1.id = ?' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/ResearchPublication()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $date, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($date !=NULL){
			$select->from(array('t1' => $tableName)) 
					->columns(array('id',$columnName))
                    ->join(array('t2' => 'research_publication_announcement'), 
                            't1.id = t2.research_publication_type');
            $select->where('t2.end_date >= ' .$date);
			$select->where(array('t1.organisation_id' =>$organisation_id));
		} else if($organisation_id){
			$select->from(array('t1' => $tableName)) 
					->columns(array('id',$columnName));
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		else{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName));
		}
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set){
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}
        
}