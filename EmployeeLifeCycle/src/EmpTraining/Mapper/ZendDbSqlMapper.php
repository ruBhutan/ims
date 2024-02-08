<?php

namespace EmpTraining\Mapper;

use EmpTraining\Model\TrainingDetails;
use EmpTraining\Model\HrdTrainingPlan;
use EmpTraining\Model\WorkshopDetails;
use EmpTraining\Model\TrainingNomination;
use EmpTraining\Model\ShortTermApplication;
use EmpTraining\Model\LongTermApplication;
use EmpTraining\Model\TrainingReport;
use EmpTraining\Model\StudyReport;
use EmpTraining\Model\StudyExtension;
use EmpTraining\Model\HrLongTermApplication;
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

class ZendDbSqlMapper implements EmpTrainingMapperInterface
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
	 * @var \EmpTraining\Model\EmpTrainingInterface
	*/
	protected $trainingPrototype;
	
	/*
	 * @var \EmpTraining\Model\hrdTrainingPlan
	*/
	protected $hrdPlanPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			TrainingDetails $trainingPrototype,
			HrdTrainingPlan $hrdPlanPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->trainingPrototype = $trainingPrototype;
		$this->hrdPlanPrototype = $hrdPlanPrototype;
	}
	
	
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the Organisation Id
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
	* @return EmpTraining
	* @throws \InvalidArgumentException
	*/
	
	public function findPlanDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('hr_development');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/EmpTraining()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* List all training details
	*/
	
	public function listTrainingDetails($table_name, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $table_name));
		$select->where(array('t1.proposing_agency = ? ' => $organisation_id));
		if($table_name == 'training_details'){
			$select->where(array('t1.training_start_date >= ? ' => date('Y-m-d')));
		} else {
			$select->where(array('t1.workshop_start_date >= ? ' => date('Y-m-d')));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/HrdPlan()
	*/
	public function listHrdPlan($type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_development'))
					->join(array('t2' => 'training_types'), 
                            't1.training_type = t2.id', array('training_type'))
					->join(array('t3' => 'funding_category'),
							't3.id = t1.source_of_funding', array('funding_type'));
		$select->where(array('t2.training_type = ? ' => $type, 't1.approval_status' => 'Approved'));
                if($organisation_id != 1){
                    $select->where(array('t1.working_agency = ? ' => $organisation_id));
                }

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->hrdPlanPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
	}


	public function listAdhocTrainingList($type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'Long Term'){
			$select->from(array('t1' => 'training_details'));
			$select->where(array('t1.proposing_agency = ? ' => $organisation_id, 't1.hrd_type' => 'adhoc training'));
		}
		else if($type == 'Short Term'){
			$select->from(array('t1' => 'workshop_details'));
			$select->where(array('t1.proposing_agency = ? ' => $organisation_id, 't1.hrd_type' => 'Adhoc Training'));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getAdhocTrainingDetails($type, $id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'Long Term'){
			$select->from(array('t1' => 'training_details'))
				   ->join(array('t2' => 'training_types'),
						't2.id = t1.training_category', array('training_type'))
				   ->join(array('t3' => 'training_type_details'),
						't3.id = t1.training_type', array('training_type_detail'))
				   ->join(array('t4' => 'country'),
						't4.id = t1.institute_country', array('country'))
				   ->join(array('t5' => 'study_level'),
						't5.id = t1.course_level', array('study_level'))
				   ->join(array('t6' => 'funding_category'),
						't6.id = t1.source_of_funding', array('funding_type'));
			$select->where(array('t1.id' => $id));
		}
		else if($type == 'Short Term'){
			$select->from(array('t1' => 'workshop_details'))
				   ->join(array('t2' => 'training_type_details'),
						't2.id = t1.type', array('training_type_detail'))
				   ->join(array('t3' => 'country'),
						't3.id = t1.institute_country', array('country'))
				   ->join(array('t4' => 'funding_category'),
						't4.id = t1.source_of_funding', array('funding_type'));
			$select->where(array('t1.id' => $id));
		}

		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	public function getAdhocTrainingNomination($id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'Long Term'){
			$select->from(array('t1' => 'training_nominations'));
			$select->where(array('t1.training_details_id' => $id));
		}
		else if($type == 'Short Term'){
			$select->from(array('t1' => 'training_nominations'));
		    $select->where(array('t1.workshop_details_id' => $id));
		}

		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$nomination = array();
		foreach($resultSet as $set){
			$nomination[] = $set;
		}//var_dump($nomination); die();
		return $nomination;
	}
	
	
	public function deleteAdhocTraining($id, $type)
	{
		if($type == 'Long Term'){
			$action = new Delete('training_details');
			$action->where(array('id = ?' => $id));
		}
		else if($type == 'Short Term'){
			$action = new Delete('workshop_details');
			$action->where(array('id = ?' => $id));
		}

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}

        		
	/**
	 * 
	 * @param type $EmpTrainingInterface
	 * 
	 * to save Long Term Training Details
	 */
	
	public function save(TrainingDetails $trainingObject, $category, $type)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);

		$start_date = substr($trainingData['training_Start_Date'],0,10);
		$end_date = substr($trainingData['training_Start_Date'],13,10);
		$trainingData['training_Start_Date'] = date("Y-m-d", strtotime(substr($start_date,0,10)));
		$trainingData['training_End_Date'] = date("Y-m-d", strtotime(substr($end_date,0,10)));
		$trainingData['order_Date'] = date("Y-m-d", strtotime($trainingData['order_Date']));

		$trainingData['training_Category'] = $category;
		$trainingData['training_Type'] = $type;
	
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('training_details');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('training_details');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $EmpTrainingInterface
	 * 
	 * to save Short Term Training Details
	 */
	
	public function saveShortTermTraining(WorkshopDetails $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);

		$start_date = substr($trainingData['workshop_Start_Date'],0,10);
		$end_date = substr($trainingData['workshop_Start_Date'],13,10);
		$trainingData['workshop_Start_Date'] = date("Y-m-d", strtotime(substr($start_date,0,10)));
		$trainingData['workshop_End_Date'] = date("Y-m-d", strtotime(substr($end_date,0,10)));
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('workshop_details');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('workshop_details');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save Training Nominations
	 */
	
	public function saveTrainingNomination(TrainingNomination $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
		
		//need to get the file locations and store them in database
		$nomination_evidence_file_name = $trainingData['nomination_Evidence_File'];
		$trainingData['nomination_Evidence_File'] = $nomination_evidence_file_name['tmp_name'];
		
		//need to get the id of either training details/workshop details
		$training_name = $trainingData['training_Detail'];
		$training_id = $this->getTrainingDetailId('training_details','course_title', $training_name);
		$trainingData['training_Details_Id'] = $training_id;
		
		//if training_id is null, then we have to checck in workshop details
		if($training_id == NULL){
			$training_id = $this->getTrainingDetailId('workshop_details','title', $training_name);
			$trainingData['workshop_Details_Id'] = $training_id;
		}
		//need to unset training detail as it is not part of database
		unset($trainingData['training_Detail']);
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('training_nominations');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('training_nominations');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Long Term application form submitted by staff for training
	*/
	 
	public function saveLongTermApplication(LongTermApplication $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
		
		//need to get the file locations and store them in database
		$audit_file_name = $trainingData['audit_Clearance'];
		$trainingData['audit_Clearance'] = $audit_file_name['tmp_name'];
		
		$security_file_name = $trainingData['security_Clearance'];
		$trainingData['security_Clearance'] = $security_file_name['tmp_name'];
		
		$medical_file_name = $trainingData['medical_Certificate'];
		$trainingData['medical_Certificate'] = $audit_file_name['tmp_name'];
		
		$course_file_name = $trainingData['course_Content_Schedule'];
		$trainingData['course_Content_Schedule'] = $course_file_name['tmp_name'];
		
		$acceptance_file_name = $trainingData['acceptance_Letter'];
		$trainingData['acceptance_Letter'] = $acceptance_file_name['tmp_name'];
		
		$award_file_name = $trainingData['award_Letter'];
		$trainingData['award_Letter'] = $award_file_name['tmp_name'];
		
		$understanding_file_name = $trainingData['understanding_Letter'];
		$trainingData['understanding_Letter'] = $understanding_file_name['tmp_name'];
		
		$departure_file_name = $trainingData['departure_Intimidation_Form'];
		$trainingData['departure_Intimidation_Form'] = $departure_file_name['tmp_name'];
		
		$predeparture_file_name = $trainingData['predeparture_Briefing_Form'];
		$trainingData['predeparture_Briefing_Form'] = $predeparture_file_name['tmp_name'];
		
		$secondment_file_name = $trainingData['understanding_Secondment'];
		$trainingData['understanding_Secondment'] = $secondment_file_name['tmp_name'];
		
		$secondment_file_name = $trainingData['professional_Development_Report'];
		$trainingData['professional_Development_Report'] = $secondment_file_name['tmp_name'];
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_training_details');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_training_details');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEditedLongTermApplication(LongTermApplication $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject); 
		$id = $trainingData['id']; 
		unset($trainingData['award_Letter']); 
		unset($trainingData['understanding_Letter']); 
		unset($trainingData['departure_Intimidation_Form']); 
		unset($trainingData['predeparture_Briefing_Form']); 
		unset($trainingData['understanding_Secondment']); 

		
		//need to get the file locations and store them in database
		$audit_file_name = $trainingData['audit_Clearance'];
		$trainingData['audit_Clearance'] = $audit_file_name['tmp_name'];
		if(!empty($trainingData['audit_Clearance'])){
			$audit_clearance = $trainingData['audit_Clearance'];
		}else{
			$audit_clearance = $this->getLongTermFileName($id, 'audit_clearance');
		} //echo $audit_clearance;
		
		$security_file_name = $trainingData['security_Clearance'];
		$trainingData['security_Clearance'] = $security_file_name['tmp_name'];
		if(!empty($trainingData['security_Clearance'])){
			$security_clearance = $trainingData['security_Clearance'];	
		}else{
			$security_clearance = $this->getLongTermFileName($id, 'security_clearance');
		} //echo $security_clearance; 
		
		$medical_file_name = $trainingData['medical_Certificate'];
		$trainingData['medical_Certificate'] = $medical_file_name['tmp_name'];
		if(!empty($trainingData['medical_Certificate'])){
			$medical_certificate = $trainingData['medical_Certificate'];
		}else{
			$medical_certificate = $this->getLongTermFileName($id, 'medical_certificate');
		}
		
		$course_file_name = $trainingData['course_Content_Schedule'];
		$trainingData['course_Content_Schedule'] = $course_file_name['tmp_name'];
		if(!empty($trainingData['course_Content_Schedule'])){
			$course_content_schedule = $trainingData['course_Content_Schedule'];
		}else{
			$course_content_schedule = $this->getLongTermFileName($id, 'course_content_schedule');
		}
		
		$acceptance_file_name = $trainingData['acceptance_Letter'];
		$trainingData['acceptance_Letter'] = $acceptance_file_name['tmp_name'];
		if($trainingData['acceptance_Letter']){
			$acceptance_letter = $trainingData['acceptance_Letter'];
		}else{
			$acceptance_letter = $this->getLongTermFileName($id, 'acceptance_letter');
		}
		
		$secondment_file_name = $trainingData['professional_Development_Report'];
		$trainingData['professional_Development_Report'] = $secondment_file_name['tmp_name'];
		if($trainingData['professional_Development_Report']){
			$professional_development_report = $trainingData['professional_Development_Report'];
		}else{
			$professional_development_report = $this->getLongTermFileName($id, 'professional_development_report');
		}
		
		//ID present, so it is an update
		$action = new Update('emp_training_details');
		$action->set(array('audit_clearance' => $audit_clearance, 'security_clearance' => $security_clearance, 'medical_certificate' => $medical_certificate, 'course_content_schedule' => $course_content_schedule, 'acceptance_letter' => $acceptance_letter, 'professional_development_report' => $professional_development_report));
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function getLongTermFileName($id, $file_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_training_details'));
		$select->where(array('t1.id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$file_name = NULL;
		foreach($resultSet as $set){
			if($file_type == 'audit_clearance'){
				$file_name = $set['audit_clearance'];
			}
			else if($file_type == 'security_clearance'){
				$file_name = $set['security_clearance'];
			}
			else if($file_type == 'medical_certificate'){
				$file_name = $set['medical_certificate'];
			}
			else if($file_type == 'course_content_schedule'){
				$file_name = $set['course_content_schedule'];
			}
			else if($file_type == 'acceptance_letter'){
				$file_name = $set['acceptance_letter'];
			}else if($file_type == 'professional_development_report'){
				$file_name = $set['professional_development_report'];
			}
		}	
		return $file_name;
	}



	public function updateEditedShortTermApplication(ShortTermApplication $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject); 
		$id = $trainingData['id']; 
		unset($trainingData['course_Content_Schedule']); 
		unset($trainingData['acceptance_Letter']); 
		unset($trainingData['award_Letter']); 
		unset($trainingData['understanding_Letter']); 
		unset($trainingData['departure_Intimidation_Form']); 
		unset($trainingData['predeparture_Briefing_Form']); 
		unset($trainingData['understanding_Secondment']); 

		
		//need to get the file locations and store them in database
		$audit_file_name = $trainingData['audit_Clearance'];
		$trainingData['audit_Clearance'] = $audit_file_name['tmp_name'];
		if(!empty($trainingData['audit_Clearance'])){
			$audit_clearance = $trainingData['audit_Clearance'];
		}else{
			$audit_clearance = $this->getShortTermFileName($id, 'audit_clearance');
		} //echo $audit_clearance;
		
		$security_file_name = $trainingData['security_Clearance'];
		$trainingData['security_Clearance'] = $security_file_name['tmp_name'];
		if(!empty($trainingData['security_Clearance'])){
			$security_clearance = $trainingData['security_Clearance'];	
		}else{
			$security_clearance = $this->getShortTermFileName($id, 'security_clearance');
		} //echo $security_clearance; 
		
		$medical_file_name = $trainingData['medical_Certificate'];
		$trainingData['medical_Certificate'] = $medical_file_name['tmp_name'];
		if(!empty($trainingData['medical_Certificate'])){
			$medical_certificate = $trainingData['medical_Certificate'];
		}else{
			$medical_certificate = $this->getShortTermFileName($id, 'medical_certificate');
		}
		
		$pd_file_name = $trainingData['pd_Form'];
		$trainingData['pd_Form'] = $pd_file_name['tmp_name'];
		if(!empty($trainingData['pd_Form'])){
			$pd_form = $trainingData['pd_Form'];
		}else{
			$pd_form = $this->getShortTermFileName($id, 'pd_form');
		}
		
		
		//ID present, so it is an update
		$action = new Update('emp_workshop_details');
		$action->set(array('audit_clearance' => $audit_clearance, 'security_clearance' => $security_clearance, 'medical_certificate' => $medical_certificate, 'pd_form' => $pd_form));
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function getShortTermFileName($id, $file_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_workshop_details'));
		$select->where(array('t1.id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$file_name = NULL;
		foreach($resultSet as $set){
			if($file_type == 'audit_clearance'){
				$file_name = $set['audit_clearance'];
			}
			else if($file_type == 'security_clearance'){
				$file_name = $set['security_clearance'];
			}
			else if($file_type == 'medical_certificate'){
				$file_name = $set['medical_certificate'];
			}
			else if($file_type == 'pd_form'){
				$file_name = $set['pd_form'];
			}
		}	
		return $file_name;
	}


	public function updateLongTermApplication(HrLongTermApplication $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		//unset($trainingData['id']); 
		
		//need to get the file locations and store them in database
		$award_file_name = $trainingData['award_Letter'];
		$trainingData['award_Letter'] = $award_file_name['tmp_name'];
		
		$understanding_file_name = $trainingData['understanding_Letter'];
		$trainingData['understanding_Letter'] = $understanding_file_name['tmp_name'];
		
		$departure_file_name = $trainingData['departure_Intimidation_Form'];
		$trainingData['departure_Intimidation_Form'] = $departure_file_name['tmp_name'];
		
		$predeparture_file_name = $trainingData['predeparture_Briefing_Form'];
		$trainingData['predeparture_Briefing_Form'] = $predeparture_file_name['tmp_name'];
		
		$secondment_file_name = $trainingData['understanding_Secondment'];
		$trainingData['understanding_Secondment'] = $secondment_file_name['tmp_name'];
		
		//ID present, so it is an update
		$action = new Update('emp_training_details');
		$action->set($trainingData);
		$action->where(array('id = ?' => $trainingData['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$employee_details_id = $this->getEmployeeDetailsId($trainingData['id'], $tableName = 'emp_training_details');

		$this->updateEmployeeLeaveStatus($employee_details_id, 'relieve', NULL, NULL);
	}
	 
	/*
	* Save Short Term application form submitted by staff for training
	*/
	 
	public function saveShortTermApplication(ShortTermApplication $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
				
		//need to get the file locations and store them in database
		$audit_file_name = $trainingData['audit_Clearance'];
		$trainingData['audit_Clearance'] = $audit_file_name['tmp_name'];
		
		$security_file_name = $trainingData['security_Clearance'];
		$trainingData['security_Clearance'] = $security_file_name['tmp_name'];
		
		$medical_file_name = $trainingData['medical_Certificate'];
		$trainingData['medical_Certificate'] = $medical_file_name['tmp_name'];

		$pd_form_name = $trainingData['pd_Form'];
		$trainingData['pd_Form'] = $pd_form_name['tmp_name'];
		
		$course_file_name = $trainingData['course_Content_Schedule'];
		$trainingData['course_Content_Schedule'] = $course_file_name['tmp_name'];
		
		$acceptance_file_name = $trainingData['acceptance_Letter'];
		$trainingData['acceptance_Letter'] = $acceptance_file_name['tmp_name'];
		
		$award_file_name = $trainingData['award_Letter'];
		$trainingData['award_Letter'] = $award_file_name['tmp_name'];
		
		$understanding_file_name = $trainingData['understanding_Letter'];
		$trainingData['understanding_Letter'] = $understanding_file_name['tmp_name'];
		
		$departure_file_name = $trainingData['departure_Intimidation_Form'];
		$trainingData['departure_Intimidation_Form'] = $departure_file_name['tmp_name'];
		
		$predeparture_file_name = $trainingData['predeparture_Briefing_Form'];
		$trainingData['predeparture_Briefing_Form'] = $predeparture_file_name['tmp_name'];
		
		$secondment_file_name = $trainingData['understanding_Secondment'];
		$trainingData['understanding_Secondment'] = $secondment_file_name['tmp_name'];
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_workshop_details');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_workshop_details');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}
        
        /*
	 * Save Short Term application form submitted by HR Officer
	 */
	 
	public function updateShortTermApplication(ShortTermApplication $trainingObject, $data_to_check)
    {  
            
        $trainingData = $this->hydrator->extract($trainingObject);

    	unset($trainingData['id']);
        unset($trainingData['audit_Clearance']);
        unset($trainingData['security_Clearance']);
        unset($trainingData['medical_Certificate']);
        unset($trainingData['understanding_Letter']);
        unset($trainingData['departure_Intimidation_Form']);
        unset($trainingData['predeparture_Briefing_Form']);
        unset($trainingData['understanding_Secondment']);
        unset($trainingData['employee_Details_Id']);
        unset($trainingData['workshop_Details_Id']); 


        $course_file_name = $trainingData['course_Content_Schedule'];
		$trainingData['course_Content_Schedule'] = $course_file_name['tmp_name'];

		$acceptance_file_name = $trainingData['acceptance_Letter'];
		$trainingData['acceptance_Letter'] = $acceptance_file_name['tmp_name'];

		$award_file_name = $trainingData['award_Letter'];
		$trainingData['award_Letter'] = $award_file_name['tmp_name']; 

		foreach($data_to_check as $key=>$value){ 
				
			if($value == 1){
				$action = new Update('emp_workshop_details');
				$action->set($trainingData);
				$action->where(array('id = ?' => $key));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();	

				$employee_details_id = $this->getEmployeeDetailsId($key, $tableName = 'emp_workshop_details');

				$workshop_details_id = $this->getWorkshopDetailsId($key, $tableName = 'emp_workshop_details');
				$workshop_from_to_date = $this->getWorkshopFromToDate($workshop_details_id, $tableName = 'workshop_details');

				$workshopDetails = array();
				foreach($workshop_from_to_date as $details){
					$workshopDetails['workshop_start_date'] = $details['workshop_start_date'];
					$workshopDetails['workshop_end_date'] = $details['workshop_end_date'];
				} 

				$diff = abs(strtotime($workshopDetails['workshop_end_date']) - strtotime($workshopDetails['workshop_start_date']));
				//echo $diff; 
				//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
				$days = ($diff/86400)+1;

				$this->updateEmployeeLeaveStatus($employee_details_id, 'relieve', $days, NULL);
					//ID present, so it is an update
			}
		}
    }


    /*
    *Function to get the employee details id based on the table emp_workshop_details table
    **/
    public function getEmployeeDetailsId($id, $tableName)
    {
    	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_workshop_details'){
			$select->from(array('t1' => $tableName)) 
				   ->columns(array('employee_details_id'));
			$select->where(array('t1.id' =>$id));
		}
		if($tableName == 'emp_training_details'){
			$select->from(array('t1' => $tableName)) 
				   ->columns(array('employee_details_id'));
			$select->where(array('t1.id' =>$id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$employee_details_id = NULL;
		foreach($resultSet as $set){
			$employee_details_id = $set['employee_details_id'];
		}	
		return $employee_details_id;
    }


    public function getWorkshopDetailsId($id, $tableName)
    {
    	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_workshop_details'){
			$select->from(array('t1' => $tableName)) 
				   ->columns(array('workshop_details_id'));
			$select->where(array('t1.id' =>$id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$workshop_details_id = NULL;
		foreach($resultSet as $set){
			$workshop_details_id = $set['workshop_details_id'];
		}	
		return $workshop_details_id;
    }


    public function getWorkshopFromToDate($workshop_details_id, $tableName)
    {
    	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 
		$select->where(array('t1.id' => $workshop_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
        $resultSet = new ResultSet();
		return $resultSet->initialize($result);
    }


    public function updateEmployeeLeaveStatus($employee_details_id, $status, $days, $reported_date)
    {
    	$leave_balance = $this->getEmployeeLeaveBalance($employee_details_id, 'earned_leave');
    	$casual_leave_balance = $this->getEmployeeLeaveBalance($employee_details_id, 'casual_leave');
    	$month = date("F",strtotime($reported_date));
    	$earned_leave = 0;
    	$casual_leave = 0;

    	if($status == 'relieve' && $days == NULL && $reported_date == NULL){
    		$leave_status = 1;
    		$earned_leave = $leave_balance;
    		$casual_leave = $casual_leave_balance;
    	}
    	else if($status == 'longterm_report' && $days == NULL && $reported_date != NULL){
    		$leave_status = 0;
    		$earned_leave = $leave_balance;
    		if($casual_leave_balance == 0){
    			if($month == 'July'){
    				$casual_leave = round((10/12)*12);
    			}else if($month == 'August'){
    				$casual_leave = round((10/12)*11);
    			}else if($month == 'September'){
    				$casual_leave = round((10/12)*10);
    			}else if($month == 'October'){
    				$casual_leave = round((10/12)*9);
    			}else if($month == 'November'){
    				$casual_leave = round((10/12)*8);
    			}else if($month == 'December'){
    				$casual_leave = round((10/12)*7);
    			}else if($month == 'January'){
    				$casual_leave = round((10/12)*6);
    			}else if($month == 'February'){
    				$casual_leave = round((10/12)*5);
    			}else if($month == 'March'){
    				$casual_leave = round((10/12)*4);
    			}else if($month == 'April'){
    				$casual_leave = round((10/12)*3);
    			}else if($month == 'May'){
    				$casual_leave = round((10/12)*2);
    			}else if($month == 'June'){
    				$casual_leave = round((10/12)*1);
    			}
    		}else{
    			$casual_leave = $casual_leave_balance;
    		}
    	}
    	else if($status == 'shortterm_report' && $days == NULL && $reported_date != NULL){ 
    		$leave_status = 0;
    		$earned_leave = $leave_balance;
    		if($casual_leave_balance == 0){
    			if($month == 'July'){
    				$casual_leave = round((10/12)*12);
    			}else if($month == 'August'){
    				$casual_leave = round((10/12)*11);
    			}else if($month == 'September'){
    				$casual_leave = round((10/12)*10);
    			}else if($month == 'October'){
    				$casual_leave = round((10/12)*9);
    			}else if($month == 'November'){
    				$casual_leave = round((10/12)*8);
    			}else if($month == 'December'){
    				$casual_leave = round((10/12)*7);
    			}else if($month == 'January'){
    				$casual_leave = round((10/12)*6);
    			}else if($month == 'February'){
    				$casual_leave = round((10/12)*5);
    			}else if($month == 'March'){
    				$casual_leave = round((10/12)*4);
    			}else if($month == 'April'){
    				$casual_leave = round((10/12)*3);
    			}else if($month == 'May'){
    				$casual_leave = round((10/12)*2);
    			}else if($month == 'June'){
    				$casual_leave = round((10/12)*1);
    			}

    		}else{
    			$casual_leave = $casual_leave_balance;
    		}
    	}
    	else if($status == 'relieve' && $days != NULL && $reported_date == NULL){ 
    		if($days >= 30){
    			$leave_status = 1;
    		}else if($days < 30){
    			$leave_status = 0;
    		}
    		$earned_leave = $leave_balance;
    		$casual_leave = $casual_leave_balance;    		
    	}
    	
    	$action = new Update('emp_leave_balance');
        $action->set(array('emp_leave_status' => $leave_status, 'earned_leave' => $earned_leave, 'casual_leave' => $casual_leave));
        $action->where(array('employee_details_id = ?' => $employee_details_id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    public function getEmployeeLeaveBalance($employee_details_id, $leave_type)
    {
    	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_balance')) 
				->columns(array('earned_leave', 'casual_leave'));
		$select->where(array('t1.employee_details_id' =>$employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
                
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$leave_balance = NULL;
		foreach($resultSet as $set){
			if($leave_type == 'earned_leave'){
				$leave_balance = $set['earned_leave'];
			}else{
				$leave_balance = $set['casual_leave'];
			}
		}	
		return $leave_balance;
    }
	
	 /*
	 * Save Training Report
	 */
	 
	public function saveTrainingReport(TrainingReport $trainingObject)
	{
		$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
				
		//need to get the file locations and store them in database
		$joining_report_file_name = $trainingData['joining_Report'];
		$trainingData['joining_Report'] = $joining_report_file_name['tmp_name'];
		
		$feedback_file_name = $trainingData['feedback_Form'];
		$trainingData['feedback_Form'] = $feedback_file_name['tmp_name'];

		$trainingData['reported_Date'] = date("Y-m-d", strtotime(substr($trainingData['reported_Date'],0,10))); 
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('shortterm_training_report');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('shortterm_training_report');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
                                //Insert Training Details in Employee Training Details
                $this->updateEmployeeTraining($trainingData['workshop_Details_Id'], $trainingData['employee_Details_Id']);

                $this->updateEmployeeLeaveStatus($trainingData['employee_Details_Id'], 'shortterm_report', NULL, $trainingData['reported_Date']);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
	}
        
         /*
	 * Save Long Term Training/Study Report
	 */
	 
	public function saveStudyReport(StudyReport $trainingObject)
    {
    	$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
				
		//need to get the file locations and store them in database
		$joining_report_file_name = $trainingData['joining_Report'];
		$trainingData['joining_Report'] = $joining_report_file_name['tmp_name'];
		
		$feedback_file_name = $trainingData['feedback_Form'];
		$trainingData['feedback_Form'] = $feedback_file_name['tmp_name'];
		
		$certificates_file_name = $trainingData['certificates'];
		$trainingData['certificates'] = $certificates_file_name['tmp_name'];
		
		$marksheets_file_name = $trainingData['marksheets'];
		$trainingData['marksheets'] = $marksheets_file_name['tmp_name'];

		$trainingData['reported_Date'] = date("Y-m-d", strtotime(substr($trainingData['reported_Date'],0,10))); 
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('longterm_training_report');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('longterm_training_report');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
                                //Insert Study Report in Employee Education
                $this->updateEmployeeEducation($trainingData['training_Details_Id'], $trainingData['marks_Obtained'], $trainingData['employee_Details_Id']);
                $this->updateEmployeeLeaveStatus($trainingData['employee_Details_Id'], 'longterm_report', NULL, $trainingData['reported_Date']);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
        }
         
         /*
	 * Save Study Extension Request
	 */
	 
	public function saveStudyExtensionRequest(StudyExtension $trainingObject)
        {
            	$trainingData = $this->hydrator->extract($trainingObject);
		unset($trainingData['id']);
				
		//need to get the file locations and store them in database
		$approval_file_name = $trainingData['committee_Approved_Evidence'];
		$trainingData['committee_Approved_Evidence'] = $approval_file_name['tmp_name'];

		$trainingData['extension_Date'] = date("Y-m-d", strtotime(substr($trainingData['extension_Date'],0,10)));
		
		if($trainingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('longterm_study_extension');
			$action->set($trainingData);
			$action->where(array('id = ?' => $trainingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('longterm_study_extension');
			$action->values($trainingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $trainingObject->setId($newId);
			}
			return $trainingObject;
		}
		
		throw new \Exception("Database Error");
        }
        
        /*
         * Update Employee Education Details after updating Study Report
         */
        
        public function updateEmployeeEducation($training_details_id, $marks_obtained, $employee_details_id)
        {
                //get the employee details
            $sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			
			$select->from(array('t1' => 'training_details')) 
		                ->join(array('t2' => 'training_nominations'), 
		                        't1.id = t2.training_details_id', array('employee_details_id'))
		                ->where(array('t2.training_details_id' => $training_details_id, 't2.employee_details_id' => $employee_details_id));
			 
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
                
            foreach($resultSet as $set){
                $trainingData['employee_Details_Id'] = $employee_details_id;
                $trainingData['college_Name'] = $set['institute_name'];
                $trainingData['college_Location'] = $set['institute_location'];
                $trainingData['college_Country'] = $set['institute_country'];
                $trainingData['field_Study'] = $set['course_title'];
                $trainingData['study_Level'] = $set['course_level'];
                $trainingData['start_Date'] = $set['training_start_date'];
                $trainingData['end_Date'] = $set['training_end_date'];
                $trainingData['funding'] = $set['source_of_funding'];
                $trainingData['marks_Obtained'] = $marks_obtained;
            }
            
            $action = new Insert('emp_education_details');
            $action->values($trainingData);

            $sql2 = new Sql($this->dbAdapter);
            $stmt2 = $sql2->prepareStatementForSqlObject($action);
            $result2 = $stmt2->execute();                      
        }
        
        /*
         * Update Employee Training Details after updating Training Report
         */
        
        public function updateEmployeeTraining($workshop_details_id, $employee_details_id)
        {
            $trainingData = array();
            
            $sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'workshop_details')) 
                    ->join(array('t2' => 'training_nominations'), 
                            't1.id = t2.workshop_details_id', array('employee_details_id'))
                    ->where(array('t2.workshop_details_id' => $workshop_details_id, 't2.employee_details_id' => $employee_details_id));
		
		
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
                
        	foreach($resultSet as $set){
	            $trainingData['employee_Details_Id'] = $employee_details_id;
	            $trainingData['course_Title'] = $set['title'];
	            $trainingData['institute_Name'] = $set['institute_name'];
	            $trainingData['institute_Address'] = $set['institute_location'];
	            $trainingData['country'] = $set['institute_country'];
	            $trainingData['from_Date'] = $set['workshop_start_date'];
	            $trainingData['to_Date'] = $set['workshop_end_date'];
	            $trainingData['funding'] = $set['source_of_funding'];
        	} 
        
        //update the employee training
            $action = new Insert('emp_previous_trainings');
            $action->values($trainingData);

            $sql2 = new Sql($this->dbAdapter);
            $stmt2 = $sql2->prepareStatementForSqlObject($action);
            $result2 = $stmt2->execute();

                   /* $diff = abs(strtotime($trainingData['to_Date']) - strtotime($trainingData['from_Date']));
					$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;*/
        }
	
	/*
	* List Employees to be nominated
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
                $employee_list = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'));
		$select->order('t3.date ASC');
                if($organisation_id != 1){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
		}
		if($department){
			$select->where(array('t1.departments_id' =>$department));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
                $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_list[$set['id']] = $set;
		}
		
		return $employee_list;

	}
	
	/*
	 * Get the list of trainings that an employee is nominated for
	 * should only see training list that he/she has been nominated for
	 */
	 
	public function getNominatedTrainingList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName=='training_details'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'training_nominations'), 
                            't1.id = t2.training_details_id', array('employee_details_id', 'nomination_evidence_file'))
                    ->where(array('t2.employee_details_id = ' .$employee_details_id, 't1.training_start_date > ?' => date('Y-m-d')));
		}
		else{
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'training_nominations'), 
                            't1.id = t2.workshop_details_id', array('employee_details_id', 'nomination_evidence_file'))
                    ->where(array('t2.employee_details_id = ' .$employee_details_id, 't1.workshop_start_date > ?' => date('Y-m-d')));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getAppliedTrainingDetails($id, $training_type, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($training_type == 'long_term'){
			$select->from(array('t1' => 'emp_training_details')) 
                    ->join(array('t2' => 'training_details'), 
                            't2.id = t1.training_details_id', array('course_title'))
                    ->where(array('t1.training_details_id' => $id, 't1.employee_details_id' => $employee_details_id));
		}
		else if($training_type == 'short_term'){
			$select->from(array('t1' => 'emp_workshop_details')) 
                    ->join(array('t2' => 'workshop_details'), 
                            't2.id = t1.workshop_details_id', array('title'))
                    ->where(array('t1.workshop_details_id' => $id, 't1.employee_details_id = ' .$employee_details_id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getAppliedTrainingList($type, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type=='longterm'){
			$select->from(array('t1' => 'emp_training_details'))
				   ->columns(array('id', 'training_details_id')) 
                    ->where(array('t1.employee_details_id = ' .$employee_details_id));
		}
		else if($type=='shortterm'){
			$select->from(array('t1' => 'emp_workshop_details'))
				   ->columns(array('id', 'workshop_details_id')) 
                    ->where(array('t1.employee_details_id = ' .$employee_details_id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the list of employees that have gone for training
	 */
	 
	public function getTrainingList($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName=='training_details'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'emp_training_details'), 
                            't1.id = t2.training_details_id', array('employee_details_id'))
					->join(array('t3' => 'employee_details'), 
                            't2.employee_details_id = t3.id', array('organisation_id'))
                    ->where(array('t3.organisation_id = ' .$organisation_id))
                    ->group(array('t2.training_details_id'));
		}
		else{
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'emp_workshop_details'), 
                            't1.id = t2.workshop_details_id', array('employee_details_id', 'course_content_schedule'))
					->join(array('t3' => 'employee_details'), 
                            't2.employee_details_id = t3.id', array('organisation_id'))
                    ->where(array('t3.organisation_id = ' .$organisation_id, 't2.course_content_schedule is NOT NULL'))
                    ->group(array('t2.workshop_details_id'));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getTraineeList($id, $tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName=='emp_training_details'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'training_details'), 
                            't1.training_details_id = t2.id', array('course_title', 'institute_name', 'institute_location', 'institute_country', 'field_study', 'training_start_date', 'training_end_date'))
					->join(array('t3' => 'employee_details'), 
                            't1.employee_details_id = t3.id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id', 'departments_id', 'departments_units_id'))
					->join(array('t4' => 'departments'),
							't4.id = t3.departments_id', array('department_name'))
                    ->where(array('t2.id' => $id, 't3.organisation_id = ' .$organisation_id, 't1.award_letter is NOT NULL'));
		}

		if($tableName == 'emp_workshop_details'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'workshop_details'), 
                            't1.workshop_details_id = t2.id', array('title', 'institute_name', 'institute_location', 'institute_country', 'workshop_start_date', 'workshop_end_date'))
					->join(array('t3' => 'employee_details'), 
                            't1.employee_details_id = t3.id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id', 'departments_id', 'departments_units_id'))
					->join(array('t4' => 'departments'),
							't4.id = t3.departments_id', array('department_name'))
                    ->where(array('t2.id' => $id, 't3.organisation_id = ' .$organisation_id, 't1.course_content_schedule is NOT NULL'));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getUpdatedStudyReportList($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName=='longterm_training_report'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'training_details'), 
                            't1.training_details_id = t2.id', array('course_title', 'institute_name', 'institute_location', 'institute_country', 'field_study', 'training_start_date', 'training_end_date'))
                    ->where(array('t2.id' => $id));
		}

		if($tableName=='shortterm_training_report'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'workshop_details'), 
                            't1.workshop_details_id = t2.id', array('title', 'institute_name', 'institute_location', 'institute_country', 'workshop_start_date', 'workshop_end_date'))
                    ->where(array('t2.id' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the list of nominations for a training or workshop
	 * takes the training id as its argument
	 */
	 
	public function getTrainingNominations($id, $training_type, $training_status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($training_status == NULL){
			$select->from(array('t1'=>'training_nominations'));
			$select->columns(array('id', 'employee_details_id', 'nomination_evidence_file'));
			$select->join(array('t2'=>'employee_details'),
	                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
			if($training_type=='long_term'){
				$select->where(array('t1.training_details_id = ? ' => $id));
			} else{
				$select->where(array('t1.workshop_details_id = ? ' => $id));
			}
		}
		else if($training_status == 'report' && $training_type == 'long_term'){
			$select->from(array('t1'=>'emp_training_details'));
			$select->columns(array('employee_details_id'));
			$select->join(array('t2'=>'employee_details'),
	                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
			$select->where(array('t1.training_details_id = ? ' => $id, 't1.award_letter is NOT NULL'));
		}
		else if($training_status == 'report' && $training_type == 'short_term'){
			$select->from(array('t1'=>'emp_workshop_details'));
			$select->columns(array('employee_details_id'));
			$select->join(array('t2'=>'employee_details'),
	                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
			$select->where(array('t1.workshop_details_id = ? ' => $id, 't1.course_content_schedule is NOT NULL'));
		}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckTrainingReport($id, $training_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($training_type == 'short_term'){
			$select->from(array('t1'=>'shortterm_training_report'))
			       ->where(array('t1.workshop_details_id' => $id));
	
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
					
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$training_report = array();
			foreach($resultSet as $set){
				$training_report[] = $set;
			}

			return $training_report;
		}
	}
	
	/*
	 * Get the details for the training for a given ID
	 * Used when displaying the documents submitted for training
	 */
	 
	public function getTrainingDetails($id, $training_type, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($training_type == 'long_term' && $status == NULL){
			$select->from(array('t1' => 'training_details'))
					->join(array('t2' => 'training_nominations'), 
                            't1.id = t2.training_details_id', array('training_details_id'))
                    ->join(array('t3' => 'emp_training_details'), 
                            't1.id = t3.training_details_id')
                    ->join(array('t4'=>'employee_details'),
                            't3.employee_details_id = t4.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t5' => 'country'),
                			't5.id = t1.institute_country', array('country'))
                    ->where(array('t1.id = ' .$id));
		}

		if($training_type == 'long_term' && $status == 'report'){
			$select->from(array('t1' => 'training_details'))
                    ->join(array('t2' => 'emp_training_details'), 
                            't1.id = t2.training_details_id')
                    ->join(array('t3'=>'employee_details'),
                            't2.employee_details_id = t3.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t4' => 'country'),
                			't4.id = t1.institute_country', array('country'))
                    ->where(array('t2.id = ' .$id));
		}

		if($training_type == 'long_term' && $status == 'time_extension'){
			$select->from(array('t1' => 'training_details'))
                    ->join(array('t2' => 'emp_training_details'), 
                            't1.id = t2.training_details_id')
                    ->join(array('t3'=>'employee_details'),
                            't2.employee_details_id = t3.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t4' => 'country'),
                			't4.id = t1.institute_country', array('country'))
                    ->where(array('t2.id = ' .$id));
		}

		if($training_type == 'short_term' && $status == 'report'){
			$select->from(array('t1' => 'workshop_details')) 
					->join(array('t2' => 'emp_workshop_details'), 
                            't1.id = t2.workshop_details_id')
                    ->join(array('t3'=>'employee_details'),
                            't2.employee_details_id = t3.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t4' => 'country'),
                		't4.id = t1.institute_country', array('country'))
                    ->where(array('t2.id = ' .$id));
		}


		else if($training_type == 'short_term' && $status == NULL){
			$select->from(array('t1' => 'workshop_details')) 
                    ->join(array('t2' => 'training_nominations'), 
                            't1.id = t2.workshop_details_id', array('workshop_details_id'))
					->join(array('t3' => 'emp_workshop_details'), 
                            't1.id = t3.workshop_details_id')
                    ->join(array('t4'=>'employee_details'),
                            't3.employee_details_id = t4.id', array('first_name','middle_name','last_name','emp_id'))
                    ->join(array('t5' => 'country'),
                		't5.id = t1.institute_country', array('country'))
                    ->where(array('t1.id = ' .$id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the details for the training report for a given ID
	 */
	 
	public function getTrainingReportDetails($id, $training_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'emp_training_report'))
				->join(array('t4'=>'employee_details'),
						't1.employee_details_id = t4.id', array('first_name','middle_name','last_name','emp_id'));
		if($training_type=='long_term'){
			$select->where(array('training_details_id = ? ' => $id));
		} else{
			$select->where(array('workshop_details_id = ? ' => $id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getNomineeDetail($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
			   ->columns(array('first_name', 'middle_name', 'last_name', 'email'))
				->where(array('t1.id' => $employee_details_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getAuthorityEmail($organisation_id)
	{
		$abbr = $this->getOrganisationAbbr($organisation_id);

		//get an array of role
		$role = array();
		$email = array();

		if($organisation_id == 1){
			$role = array(
				0 => $abbr.'_HRO',
				1 => $abbr.'_HR_ASSISTANT'
			);
		}else{
			$role = array(
				0 => $abbr.'_ADMINISTRATIVE_SECTION_HEAD',
				1 => $abbr.'_HR_ASSISTANT'
			);
		}
		
		if(!empty($role)){
			foreach($role as $key => $value){
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();

				$select->from(array('t1' => 'users'))
					   ->join(array('t2' => 'employee_details'),
							't2.emp_id = t1.username', array('email'));
				$select->where(array('t1.role' =>$value, 't1.region' => $organisation_id));
					
				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				foreach($resultSet as $set){
					$email[] = $set['email'];
				} 	
			}
		}
		return $email;			
	}


	public function getTrainingNominationDetails($training_details_id, $type, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($type == 'longterm'){
			$select->from(array('t1' => 'training_nominations'))
				   ->join(array('t2' => 'training_details'),
						't2.id = t1.training_details_id', array('course_title'))
				   ->join(array('t3' => 'employee_details'),
						't3.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name'))
				->where(array('t1.id' => $id, 't1.training_details_id' => $training_details_id));
		}
		else if($type == 'shortterm'){
			$select->from(array('t1' => 'training_nominations'))
				   ->join(array('t2' => 'workshop_details'),
						't2.id = t1.workshop_details_id', array('title'))
				   ->join(array('t3' => 'employee_details'),
						't3.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name'))
				->where(array('t1.id' => $id, 't1.workshop_details_id' => $training_details_id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getOrganisationAbbr($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'organisation'));  
        $select->columns(array('abbr'))
               ->where(array('t1.id' => $organisation_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        $ABBR = NULL;
        foreach($resultSet as $set)
        {
            $ABBR = $set['abbr'];
        }
        return $ABBR;
    }
	
	/*
	 * Cross Check whether Employee has already applied
	 */
	 
	public function crossCheckTrainingApplication($employee_id, $training_id, $training_type)
	{
		$application = NULL;
				
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($training_type == 'longterm'){
			$select->from(array('t1' => 'training_details'))
						->join(array('t2' => 'emp_training_details'), 
                            't1.id = t2.training_details_id')
                    	->where('t1.id = ' .$training_id)
						->where('t2.employee_details_id = ' .$employee_id);
		}
		else if($training_type == 'shortterm'){
			$select->from(array('t1' => 'workshop_details')) 
					->join(array('t2' => 'emp_workshop_details'), 
                            't1.id = t2.workshop_details_id')
                    ->where('t1.id = ' .$training_id)
					->where('t2.employee_details_id = ' .$employee_id);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$application = "Applied";
		}
		
		return $application;
	}


	public function getLongTermApplicantDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'emp_training_details')) 
				->join(array('t2' => 'employee_details'), 
                        't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'departments_id', 'departments_units_id', 'organisation_id'))
				->join(array('t3' => 'organisation'),
						't3.id = t2.organisation_id', array('organisation_name'))
				->join(array('t4' => 'departments'),
						't4.id = t2.departments_id', array('department_name'))
				->join(array('t5' => 'department_units'),
						't5.id = t2.departments_units_id', array('unit_name'))
				->join(array('t6' => 'training_details'),
						't6.id = t1.training_details_id', array('order_date', 'hrd_type', 'course_title', 'institute_name', 'institute_location', 'institute_country'))
				->join(array('t7' => 'country'),
						't7.id = t6.institute_country', array('country'))
				->where(array('t1.id' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	public function getTrainingNominationId($training_id, $category, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($category == 'training_nomination'){
			$select->from(array('t1' => 'training_nominations')) 						
				->where(array('t1.training_details_id' => $training_id, 't1.employee_details_id' => $employee_details_id));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$id = NULL;
			foreach($resultSet as $set){
				$id = $set['id'];
			}
			return $id;
		}
		else if($category == 'workshop_nomination'){
			$select->from(array('t1' => 'training_nominations')) 						
				->where(array('t1.workshop_details_id' => $training_id, 't1.employee_details_id' => $employee_details_id));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$id = NULL;
			foreach($resultSet as $set){
				$id = $set['id'];
			}
			return $id;
		}
	}
	
	
	
	/*
	 * Get the location of the file name 
	 */
	 
	public function getFileName($training_id, $column_name, $training_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($training_type == 'long_term'){
			$select->from(array('t1' => 'emp_training_details')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$training_id);
		}
		else if($training_type == 'short_term'){
			$select->from(array('t1' => 'emp_workshop_details')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$training_id);

		}
		else if($training_type == 'nomination'){
			$select->from(array('t1' => 'training_nominations')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$training_id);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* For a given training title, get the id of the training/workshop
	* Used for inserting nominations for training
	*/
	
	public function getTrainingDetailId($tableName, $columnName, $title)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array($columnName.' = ? ' => $title));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$training_id=NULL;
		if($resultSet != NULL){
			foreach($resultSet as $set){
				$training_id = $set['id'];
			}
		}
		return $training_id;
	}
	
	/**
	* @return array/EmpTraining()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//to get training list, we need get from both short term and long term trainings
		if($tableName == 'training_list'){
			$select->from(array('t1' => 'training_details'));
			$select->columns(array('course_title'));
			$select->where(array('t1.training_start_date > ? ' => date('Y-m-d')));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			//short term training
            $select2 = $sql->select();
			$select2->from(array('t1' => 'workshop_details'));
			$select2->columns(array('title', 'workshop_start_date'));
            $select2->where(array('t1.workshop_start_date > ? ' => date('Y-m-d')));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			
			$selectData = array();
			foreach($resultSet as $set){
				$selectData[$set['course_title']] = $set['course_title'];
			}
			foreach($resultSet2 as $set2){
				$selectData[$set2['title']] = $set2['title'];
			}
		}
		else if($tableName == 'training_type_details'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id', 'training_types_id', $columnName));
			$select->where(array('t1.training_types_id' => '1'));

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
		}
		else{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName)); 

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
		}
		return $selectData;	
	}

	// Function to get id 
	public function getAjaxDataId($tableName, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'training_type_details'){
			$select->from(array('t1' => $tableName))
					->columns(array('id'));
            $select->where(array('t1.training_type_detail' => $type));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}
        
}