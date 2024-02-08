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
use JobPortal\Model\ApplicantMarks;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
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

	public function getUserDetailsId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id'));
		}
		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' =>$username));
			$select->columns(array('id'));
		}
		
		if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('email' =>$username));
			$select->columns(array('id', 'first_name', 'middle_name', 'last_name'));
		}
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getUserImage($username, $usertype)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('profile_picture', 'middle_name', 'last_name'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('profile_picture', 'middle_name', 'last_name'));
		}
		
		if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('t1.email' => $username));
			$select->columns(array('profile_picture'=>NULL)); 

		}

		if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('profile_picture'=>NULL)); 

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
	* @return array/JobPortal()
	*/
	public function findAll($tableName, $applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName)); 
			$select->where(array('id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_education'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'country'),
						't2.id = t1.college_country', array('country'))
				   ->join(array('t3' => 'study_level'),
						't3.id = t1.study_level', array('study_level'))
				   ->join(array('t4' => 'funding_category'),
						't4.id = t1.funding', array('funding_type'));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_training_details'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'country'),
						't2.id = t1.country', array('country'))
				   ->join(array('t3' => 'funding_category'),
						't3.id = t1.funding', array('funding_type'));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_employment_record'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_community_service'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_memberships'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}
		else if($tableName == 'job_applicant_awards'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}

		else if($tableName == 'job_applicant_languages'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}

		else if($tableName == 'job_applicant_research_details'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'research_category'),
						't2.id = t1.research_type', array('research_category'));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}

		else if($tableName == 'job_applicant_references'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}

		else if($tableName == 'job_applicant_marks'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}


	public function listApplicantStudyLevel($tableName, $job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'job_applicant_education'){
			$select->from(array('t1' => $tableName)); 
			$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$educationDetails = array();
		foreach($resultSet as $set){
			$educationDetails[$set['study_level']] = $set['study_level'];
		}
		return $educationDetails;
	}


	public function getApplicantAddressDetails($job_applicant_details_id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant'))
			   ->join(array('t2' => 'nationality'),
					't2.id = t1.nationality', array('nationality'))
			   ->join(array('t3' => 'country'),
					't3.id = t1.country', array('country'))
			   ->join(array('t4' => 'maritial_status'),
					't4.id = t1.maritial_status', array('maritial_status'))
			   ->join(array('t5' => 'gender'),
					't5.id = t1.gender', array('gender'))
			   ->join(array('t6' => 'dzongkhag'),
					't6.id = t1.dzongkhag', array('dzongkhag_name'))
			   ->join(array('t7' => 'gewog'),
					't7.id = t1.gewog', array('gewog_name'))
			   ->join(array('t8' => 'village'),
					't8.id = t1.village', array('village_name'));
		$select->where(array('t1.id' => $job_applicant_details_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getRegistrantOtherDetails($tableName, $id)
	{
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'job_applicant_education'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'country'),
						't2.id = t1.college_country', array('country'))
				   ->join(array('t3' => 'study_level'),
						't3.id = t1.study_level', array('study_level'))
				   ->join(array('t4' => 'funding_category'),
						't4.id = t1.funding', array('funding_type'));
			$select->where(array('t1.id' =>$id));
		}
		else if($tableName == 'job_applicant_training_details'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'country'),
						't2.id = t1.country', array('country'))
				   ->join(array('t3' => 'funding_category'),
						't3.id = t1.funding', array('funding_type'));
			$select->where(array('t1.id' =>$id));
		}

		else if($tableName == 'job_applicant_employment_record'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}
		else if($tableName == 'job_applicant_community_service'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}
		else if($tableName == 'job_applicant_memberships'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}
		else if($tableName == 'job_applicant_awards'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}

		else if($tableName == 'job_applicant_languages'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}

		else if($tableName == 'job_applicant_research_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}

		else if($tableName == 'job_applicant_references'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' =>$id));
		}
			
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


	public function getUploadedFileLink($tableName, $type, $id)
	{
		$file_location = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'cid'){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('id' =>$id));
			$select->columns(array('cid_copy'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['cid_copy'];
			}
		}
		else if($type == 'profile_picture'){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('id' =>$id));
			$select->columns(array('profile_picture'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['profile_picture'];
			}
		}
		else if($type == 'academic_transcript'){
			$select->from(array('t1' => 'job_applicant_education'));
			$select->where(array('id' =>$id));
			$select->columns(array('academic_transcript'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['academic_transcript'];
			}
		}
		else if($type == 'pass_certificate'){
			$select->from(array('t1' => 'job_applicant_education'));
			$select->where(array('id' =>$id));
			$select->columns(array('pass_certificate'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['pass_certificate'];
			}
		}
		else if($type == 'training_certificate'){
			$select->from(array('t1' => 'job_applicant_training_details'));
			$select->where(array('id' =>$id));
			$select->columns(array('training_certificate'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['training_certificate'];
			}
		}
		else if($type == 'employment_record_file'){
			$select->from(array('t1' => 'job_applicant_employment_record'));
			$select->where(array('id' =>$id));
			$select->columns(array('employment_record_file'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['employment_record_file'];
			}
		}
		else if($type == 'community_service'){
			$select->from(array('t1' => 'job_applicant_community_service'));
			$select->where(array('id' =>$id));
			$select->columns(array('supporting_file'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['supporting_file'];
			}
		}
		else if($type == 'membership'){
			$select->from(array('t1' => 'job_applicant_memberships'));
			$select->where(array('id' =>$id));
			$select->columns(array('supporting_file'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['supporting_file'];
			}
		}
		else if($type == 'award'){
			$select->from(array('t1' => 'job_applicant_awards'));
			$select->where(array('id' =>$id));
			$select->columns(array('supporting_file'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['supporting_file'];
			}
		}							
		return $file_location;
	}
	
	/*
	* Save Personal Details of Job Applicant
	*/
	
	public function savePersonalDetails(PersonalDetails $jobObject, $country, $dzongkhag, $gewog, $village)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']);
		unset($jobData['job_Applicant_Id']); 

		$jobData['country'] = $country;
		$jobData['dzongkhag'] = $dzongkhag;
		$jobData['gewog'] = $gewog;
		$jobData['village'] = $village;
		$cid_copy = $jobData['cid_Copy'];
		$jobData['cid_Copy'] = $cid_copy['tmp_name'];

		$profile_picture = $jobData['profile_Picture'];
		$jobData['profile_Picture'] = $profile_picture['tmp_name'];
		
		if($jobData['cid_Copy'] == NULL){
			$jobData['cid_Copy'] = $this->getUploadedFileLink($tableName = 'job_applicant', $type='cid', $jobData['id']);
		}

		if($jobData['profile_Picture'] == NULL){
			$jobData['profile_Picture'] = $this->getUploadedFileLink($tableName = 'job_applicant', $type='profile_picture', $jobData['id']);
		}
		//var_dump($jobData['cid_Copy']); die();

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

		$jobData['start_Date'] = date("Y-m-d", strtotime(substr($jobData['start_Date'],0,10)));
		$jobData['end_Date'] = date("Y-m-d", strtotime(substr($jobData['end_Date'],0,10))); 

		$academic_transcript = $jobData['academic_Transcript'];
		$jobData['academic_Transcript'] = $academic_transcript['tmp_name'];

		$pass_certificate = $jobData['pass_Certificate'];
		$jobData['pass_Certificate'] = $pass_certificate['tmp_name'];


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


	public function updateEducationDetails(EducationDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']);

		$jobData['start_Date'] = date("Y-m-d", strtotime(substr($jobData['start_Date'],0,10)));
		$jobData['end_Date'] = date("Y-m-d", strtotime(substr($jobData['end_Date'],0,10))); 

		$academic_transcript = $jobData['academic_Transcript'];
		$jobData['academic_Transcript'] = $academic_transcript['tmp_name'];

		$pass_certificate = $jobData['pass_Certificate'];
		$jobData['pass_Certificate'] = $pass_certificate['tmp_name']; 

		if($jobData['academic_Transcript'] == NULL){
			$jobData['academic_Transcript'] = $this->getUploadedFileLink($tableName = 'job_applicant_education', $type='academic_transcript', $jobData['id']);
		}

		//var_dump($jobData['academic_Transcript']); die();

		if($jobData['pass_Certificate'] == NULL){
			$jobData['pass_Certificate'] = $this->getUploadedFileLink($tableName = 'job_applicant_education', $type='pass_certificate', $jobData['id']);
		}

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

	public function deleteEducationDetails($id){
		//var_dump($id); die();
		$action = new Delete('job_applicant_education');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();

	}
	
	/*
	* Save Training Details of Job Applicant
	*/
	
	public function saveTrainingDetails(TrainingDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		$jobData['from_Date'] = date("Y-m-d", strtotime(substr($jobData['from_Date'],0,10)));
		$jobData['to_Date'] = date("Y-m-d", strtotime(substr($jobData['to_Date'],0,10))); 

		$training_certificate = $jobData['training_Certificate'];
		$jobData['training_Certificate'] = $training_certificate['tmp_name'];
		
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


	public function updateTrainingDetails(TrainingDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']);

		$jobData['from_Date'] = date("Y-m-d", strtotime(substr($jobData['from_Date'],0,10)));
		$jobData['to_Date'] = date("Y-m-d", strtotime(substr($jobData['to_Date'],0,10))); 

		$training_certificate = $jobData['training_Certificate'];
		$jobData['training_Certificate'] = $training_certificate['tmp_name'];

		if($jobData['training_Certificate'] == NULL){
			$jobData['training_Certificate'] = $this->getUploadedFileLink($tableName = 'job_applicant_training_details', $type='training_certificate', $jobData['id']);
		}
		
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

		$jobData['start_Period'] = date("Y-m-d", strtotime(substr($jobData['start_Period'],0,10)));
		$jobData['end_Period'] = date("Y-m-d", strtotime(substr($jobData['end_Period'],0,10))); 

		$employment_record_file = $jobData['employment_Record_File'];
		$jobData['employment_Record_File'] = $employment_record_file['tmp_name'];

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


	public function updateEmploymentRecord(EmploymentDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);

		$jobData['start_Period'] = date("Y-m-d", strtotime(substr($jobData['start_Period'],0,10)));
		$jobData['end_Period'] = date("Y-m-d", strtotime(substr($jobData['end_Period'],0,10))); 

		$employment_record_file = $jobData['employment_Record_File'];
		$jobData['employment_Record_File'] = $employment_record_file['tmp_name'];

		if($jobData['employment_Record_File'] == NULL){
			$jobData['employment_Record_File'] = $this->getUploadedFileLink($tableName = 'job_applicant_employment_record', $type='employment_record_file', $jobData['id']);
		}

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

		$jobData['start_Period'] = date("Y-m-d", strtotime(substr($jobData['start_Period'],0,10)));
		$jobData['end_Period'] = date("Y-m-d", strtotime(substr($jobData['end_Period'],0,10))); 

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

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


	public function updateMembership(MembershipDetails $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']); 

		$jobData['start_Period'] = date("Y-m-d", strtotime(substr($jobData['start_Period'],0,10)));
		$jobData['end_Period'] = date("Y-m-d", strtotime(substr($jobData['end_Period'],0,10))); 

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

		if($jobData['supporting_File'] == NULL){
			$jobData['supporting_File'] = $this->getUploadedFileLink($tableName = 'job_applicant_memberships', $type='membership', $jobData['id']);
		}

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

		$jobData['service_Date'] = date("Y-m-d", strtotime(substr($jobData['service_Date'],0,10)));

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_community_service');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_community_service');
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


	public function updateCommunityService(CommunityService $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']);

		$jobData['service_Date'] = date("Y-m-d", strtotime(substr($jobData['service_Date'],0,10)));

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

		if($jobData['supporting_File'] == NULL){
			$jobData['supporting_File'] = $this->getUploadedFileLink($tableName = 'job_applicant_community_service', $type='community_service', $jobData['id']);
		}

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_community_service');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_community_service');
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
			$action = new Update('job_applicant_research_details');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_research_details');
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

		$jobData['award_Date'] = date("Y-m-d", strtotime(substr($jobData['award_Date'],0,10)));

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

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


	public function updateAwards(Awards $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//unset($jobData['id']);

		$jobData['award_Date'] = date("Y-m-d", strtotime(substr($jobData['award_Date'],0,10)));

		$supporting_file = $jobData['supporting_File'];
		$jobData['supporting_File'] = $supporting_file['tmp_name'];

		if($jobData['supporting_File'] == NULL){
			$jobData['supporting_File'] = $this->getUploadedFileLink($tableName = 'job_applicant_awards', $type='award', $jobData['id']);
		}

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


	public function saveJobApplicantMarks(ApplicantMarks $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']); 
		
		$x_aggregate = NULL;
		$xii_aggregate = NULL;
		if($jobData['x_English'] != NULL){
			$x_aggregate = ($jobData['x_English']+$jobData['x_Sub1_Mark']+$jobData['x_Sub2_Mark']+$jobData['x_Sub3_Mark']+$jobData['x_Sub4_Mark'])/5;
		}else{
			$x_aggregate = 0.00;
		}

		if($jobData['xll_English'] != NULL){
			$xii_aggregate = ($jobData['xll_English']+$jobData['xll_Sub1_Mark']+$jobData['xll_Sub2_Mark']+$jobData['xll_Sub3_Mark'])/4;
		}else{
			$xii_aggregate = 0.00;
		}

		if($jobData['x_English'] != NULL)
		{
			$this->updateJobApplicantMarks($jobData['job_Applicant_Id'], $x_aggregate, 'ten');
		}
		if($jobData['xll_English'] != NULL){
			$this->updateJobApplicantMarks($jobData['job_Applicant_Id'], $xii_aggregate, 'twelve');
		}
		
		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant_marks');
			$action->set($jobData);
			$action->where(array('job_applicant_id = ?' => $jobData['job_Applicant_Id']));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('job_applicant_marks');
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


	// FUnction to update the class 10 and 12 aggregate
	public function updateJobApplicantMarks($job_applicant_id, $aggregate, $type)
	{
		if($type == 'ten'){
			$action = new Update('job_applicant_education');
			$action->set(array('marks_obtained' => $aggregate));
			$action->where(array('job_applicant_id = ?' => $job_applicant_id, 'study_level' => '4'));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		else if($type == 'twelve'){
			$action = new Update('job_applicant_education');
			$action->set(array('marks_obtained' => $aggregate));
			$action->where(array('job_applicant_id = ?' => $job_applicant_id, 'study_level' => '5'));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}	
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


	public function getFileName($file_id, $column_name, $type)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($type == 'cid'){
			$select->from(array('t1' => 'job_applicant')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		else if($type == 'education'){
			$select->from(array('t1' => 'job_applicant_education')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		else if($type == 'training'){
			$select->from(array('t1' => 'job_applicant_training_details')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}

		else if($type == 'employment'){
			$select->from(array('t1' => 'job_applicant_employment_record')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}

		else if($type == 'community_service'){ 
			$select->from(array('t1' => 'job_applicant_community_service')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}

		else if($type == 'membership'){ 
			$select->from(array('t1' => 'job_applicant_memberships')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}

		else if($type == 'award'){ 
			$select->from(array('t1' => 'job_applicant_awards')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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