<?php

namespace Review\Mapper;

use Review\Model\Review;
use Review\Model\AcademicReview;
use Review\Model\AcademicWeight;
use Review\Model\IwpObjectives;
use Review\Model\NatureActivity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ReviewMapperInterface
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
	 * @var \Review\Model\ReviewInterface
	*/
	protected $reviewPrototype;
	
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Review $reviewPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->reviewPrototype = $reviewPrototype;
	}
	
	/**
	* @param int/String $id
	* @return Review
	* @throws \InvalidArgumentException
	*/
	
	public function findEmployeeId($emp_id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')); 
		$select->where(array('emp_id = ?' => $emp_id));
		$select->columns(array('id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $this->empId($resultSet->initialize($result));

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
	
	/**
	* @return array/Review()
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
	
	/**
	* @return array/Review()
	*/
	public function findEmployeeReview($tableName, $employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
                    ->where('t1.employee_details_id = ' .$employee_id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Review()
	*/
	public function findActivityDetail($tableName, $columnName, $activity_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array($columnName.' = ?' => $activity_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Appraisal()
	*/
	public function listAdministrativeAppraisal($tableName, $employee_id, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName)) 
                    ->where('t1.employee_details_id = ' .$employee_id)
					->where(array('t1.status = ?' => $status));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Appraisal()
	*/
	public function listEmployeeAppraisal($tableName, $employee_id, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
					->join(array('t4'=>'awpa_objectives_activity'),
                            't1.awpa_objectives_activity_id = t4.id', array('activity_name'))
                    ->where('t1.employee_details_id = ' .$employee_id)
					->where(array('t1.status = ?' => $status))
					->order('t3.id ASC')
					->order('t4.activity_name ASC');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	 * 
	 * @param type $ReviewInterface
	 * 
	 * to save Academic Review Details
	 */
	
	public function saveAcademicReview(AcademicReview $reviewObject)
	{
		$reviewData = $this->hydrator->extract($reviewObject);
		unset($reviewData['id']);
		
		if($reviewObject->getId()) {
			//ID present, so it is an update
			$action = new Update('pms_academic_api');
			$action->set($reviewData);
			$action->where(array('id = ?' => $reviewObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('pms_academic_api');
			$action->values($reviewData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$reviewObject->setId($newId);
			}
			return $reviewObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Self Evaluation - Gets results in an array
	*/
	
	public function saveSelfEvaluation($data, $review_data, $evaluation_type, $employee_details_id)
	{
		if(!empty($data)){
			if($evaluation_type == "academic"){
				foreach($data as $key=>$value){
					$evaluationData['self_Rating'] = $value;
					$evaluationData['evaluation_Remarks'] = $review_data[$key];
					$action = new Update('pms_academic_api');
					$action->set($evaluationData);
					$action->where(array('id = ?' => $key));
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
					
				}
				
			} else{
				foreach($data as $key=>$value){
					$evaluationData['self_Evaluation'] = $value;
					$evaluationData['evaluation_Remarks'] = $review_data[$key];
					$action = new Update('iwp_subactivities');
					$action->set($evaluationData);
					$action->where(array('id = ?' => $key));
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
					
				}
			}
		}
		
		return;
	}
	
	/*
	* Save Supervisor Evaluation - Gets results in an array
	*/
	
	public function saveSupervisorEvaluation($rating_data, $evaluation_type, $employee_details_id)
	{
		if(!empty($rating_data)){
			if($evaluation_type == "academic"){
				foreach($rating_data as $key=>$value){
					$evaluationData['performance_Rating'] = $value;
					$evaluationData['status'] = 'Evaluation Complete';
					$action = new Update('pms_academic_api');
					$action->set($evaluationData);
					$action->where(array('id = ?' => $key));
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
					
				}
				
			} else{
				foreach($rating_data as $key=>$value){
					$evaluationData['performance_Rating'] = $value;
					$evaluationData['status'] = 'Evaluation Complete';
					$action = new Update('iwp_subactivities');
					$action->set($evaluationData);
					$action->where(array('id = ?' => $key));
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
					
				}
			}
		}
		
		return;
	}
	
	/*
	* Save Feedback evaluations
	*/
	
	public function saveFeedbackEvaluation($feedback_for, $nomination_id, $data, $employee_details_id, $appraisal_period)
	{
		$tableName = 'feedback_ratings_'.$feedback_for;
		if(!empty($data)){
			foreach($data as $key=>$value){
				$evaluationData['appraisal_Period'] = $appraisal_period;
				$evaluationData['ratings'] = $value;
				$evaluationData['employee_Details_Id'] = $employee_details_id;
				$action = new Insert($tableName);
				$action->values($evaluationData);
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			
			//need to update the status of the evaluator as "complete"
			$tableName2 = $feedback_for.'_nomination';
			$feedback['status'] = 'Evaluation Submitted';
			$action2 = new Update($tableName2);
			$action2->set($feedback);
			$action2->where(array('id = ?' => $nomination_id));
			$sql2 = new Sql($this->dbAdapter);
			$stmt2 = $sql2->prepareStatementForSqlObject($action2);
			$result2 = $stmt2->execute();
		}
		
		return;	
	}
	
	/*
	* Get the details of the employee details
	*/
	
	public function getEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the reviewee details given a nomination $id and nomination for
	*/
	
	public function getRevieweeDetails($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)) 
				->join(array('t2' => 'employee_details'), 
						't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
				->join(array('t3' => 'departments'), 
						't2.departments_id = t3.id', array('department_name'))
				->join(array('t4' => 'emp_position_title'), 
						't2.id = t4.employee_details_id', array('position_title_id'))
				->join(array('t5' => 'position_title'), 
						't4.position_title_id = t5.id', array('position_title'))
				->where(array('t1.id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/**
	* @return array/Appraisal()
	*/
	public function listAppraisalForEmployee($tableName, $employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'pms_academic_api'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'pms_nature_activity'), 
                            't1.pms_nature_activity_id = t2.id', array('nature_of_activity'))
                    ->join(array('t3'=>'pms_academic_weight'),
                            't2.pms_academic_weight_id = t3.id', array('category'))
                    ->where('t1.employee_details_id = ' .$employee_id); 
		} else{
			$select->from(array('t1' => $tableName)) 
                    ->where('t1.employee_details_id = ' .$employee_id); 
		}
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	* Get the appraisal list
	*/
        
	public function getAppraisalList($type, $employee_details_id, $role, $organisation_id)
	{
		$auth_type = "PMS";
		$departments_staff = array();
		$sql = new Sql($this->dbAdapter);
		
		
		if($role == "SECTION_HEAD" || $role == "HOD" || $role == "PROGRAMME_LEADER"){
			//need to get the supervisor UNIT
			$supervisor_department = $this->getSupervisorUnit($employee_details_id);
			$action = $sql->select();
			$action->from(array('t1' => 'user_workflow')) 
					->columns(array('role','role_department','type'))
					->join(array('t2' => 'users'), 
                            't1.role = t2.role', array('username'))
					->join(array('t3' => 'employee_details'), 
                            't2.username = t3.emp_id', array('id', 'departments_units_id'));
			$action->where('t1.role_department = ' .$supervisor_department);
			$action->where->like('t1.type','%'.$auth_type.'%');
			$action->where->notEqualTo('t2.role',$role);
			$action->where('t3.departments_units_id = ' .$supervisor_department);
					
			$stmt2 = $sql->prepareStatementForSqlObject($action);
			$result2 = $stmt2->execute();
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			$departments_staff = array();
			foreach($resultSet2 as $set2){
				$departments_staff[$set2['id']] = $set2['id'];
			}
		} else if($role == "DSA" || $role == "PRESIDENT" || $role == "DAA"){
			//no need to get the supervisor department
			$action = $sql->select();
			$action->from(array('t1' => 'user_workflow')) 
					->columns(array('role','role_department'))
					->join(array('t2' => 'users'), 
                            't1.role = t2.role', array('username'))
					->join(array('t3' => 'employee_details'), 
                            't2.username = t3.emp_id', array('id', 'departments_units_id'));
			$action->where->like('t1.type','%'.$auth_type.'%');
			$action->where->like('t1.auth','%'.$role.'%');
			$action->where('t3.departments_units_id = t1.role_department');
			$action->where('t3.organisation_id = ' .$organisation_id);
			
			$stmt2 = $sql->prepareStatementForSqlObject($action);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			$departments_staff = array();
			
			foreach($resultSet2 as $set2){
				$departments_staff[$set2['id']] = $set2['id'];
			}
		}
										
		//old function to get the list of employee for a supervisor
		if(!empty($departments_staff)){
			$select = $sql->select();
			if($type == 'academic'){
				$select->from(array('t1' => 'pms_academic_api')) 
						->columns(array(new Expression('DISTINCT (employee_details_id) as employee_details_id')))
						->join(array('t2' => 'employee_details'), 
								't1.employee_details_id = t2.id', array('id','first_name','middle_name','last_name','emp_id'))
						->join(array('t3' => 'departments'), 
								't2.departments_id = t3.id', array('department_name'))
						->where(array('t1.employee_details_id ' => $departments_staff));
			} else{
				$select->from(array('t1' => 'iwp_subactivities')) 
						->columns(array(new Expression('DISTINCT (employee_details_id) as employee_details_id')))
						->join(array('t2' => 'employee_details'), 
								't1.employee_details_id = t2.id', array('id','first_name','middle_name','last_name','emp_id'))
						->join(array('t3' => 'departments'), 
								't2.departments_id = t3.id', array('department_name'))
						->where(array('t1.employee_details_id ' => $departments_staff));
			}
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
		return;
	}
	
	/*
	* Get the Performance Score
	*/
	
	public function getPerformanceScore($evaluation_type, $employee_details_id)
	{		
		if($evaluation_type == 'academic')
			$performance_score = $this->getAcademicPerformanceScore($employee_details_id);
		else
			$performance_score = $this->getAdministrativePerformanceScore($employee_details_id);
		
		return $performance_score;
	}
	
	/*
	* Get the Feedback Score
	*/
	
	public function getFeedbackScore($evaluation_type, $employee_details_id)
	{		
		$total_feedback_score =0;
		if($evaluation_type == 'academic'){
			
		} else {
			$total_feedback_score += $this->getBeneficiaryFeedbackScore($employee_details_id);
			$total_feedback_score += $this->getPeerFeedbackScore($employee_details_id);
			$total_feedback_score += $this->getSubordinateFeedbackScore($employee_details_id);
		}
		return $total_feedback_score;
	}
	
	public function getAcademicPerformanceScore($employee_details_id)
	{
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'pms_academic_weight')) 
				->columns(array('category', 'weight'))
				->join(array('t2' => 'pms_nature_activity'), 
                            't1.id = t2.pms_academic_weight_id', array('id','maximum_score'));		
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$academic_weight = array();
		foreach($resultSet2 as $set2){
			$academic_weight[] = $set2;
		}
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'pms_academic_api')) 
				->columns(array('performance_rating', 'pms_nature_activity_id'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => date('Y')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_api = array();
		foreach($resultSet as $set){
			$academic_api[] = $set;
		}
		
		$academic_score = 0;
		
		foreach($academic_weight as $key=>$value){
			$temp_score = 0;
			foreach($academic_api as $key1=>$value1){
				if($value1['pms_nature_activity_id'] == $value['id']){
					$temp_score = (int) $value1['performance_rating'];
					$weight = $value['weight'];
					$temp_score = $temp_score*$weight/100;
					$academic_score += $temp_score;
				}
			}
		}
		return $academic_score;
	}
	
	public function getAdministrativePerformanceScore($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		
		$select->from(array('t1' => 'iwp_subactivities')) 
				->columns(array('supervisor_evaluation'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => date('Y')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$administrative_score = 0;
		$activities_count = count($result);
		foreach($resultSet as $set){
			$administrative_score += (int) $set['supervisor_evaluation'];
		}
		return $administrative_score/$activities_count;
	}
	
	public function getStudentFeedbackScore($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$student_score = 0;
		return $student_score;
	}
	
	public function getBeneficiaryFeedbackScore($employee_details_id)
	{
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('beneficiary_feedback_questions');
		$beneficiary_score = 0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_beneficiary')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => date('Y')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		if(count($result)/$question_count >= 10){
			foreach($resultSet as $set){
				$beneficiary_score += (int) $set['ratings'];
			}
			$beneficiary_score = $beneficiary_score/count($result);
		}
		return $beneficiary_score;
	}
	
	public function getPeerFeedbackScore($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_peer')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => date('Y')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('peer_feedback_questions');
		$peer_score = 0;
		if(count($result)/$question_count >= 10){
			foreach($resultSet as $set){
				$peer_score += (int) $set['ratings'];
			}
			$peer_score = $peer_score/count($result);
		}
		return $peer_score;
	}
	
	public function getSubordinateFeedbackScore($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_subordinate')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => date('Y')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('subordinate_feedback_questions');
		$subordinate_score = 0;
		if(count($result)/$question_count >= 10){
			foreach($resultSet as $set){
				$subordinate_score += (int) $set['ratings'];
			}
			$subordinate_score = $subordinate_score/count($result);
		}
		return $subordinate_score;
	}
	
	public function getFeedbackQuestionCount($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName)) 
				->columns(array('questions'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$question_count = count($result);
		return $question_count;
	}
        
	
	/**
	* @return array/Review()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $empIds)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName));
		if($empIds != NULL)
		{
			$select->where(array('employee_details_id' => $empIds));
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
		return $selectData;
			
	}
	
	public function empId($employee_id)
	{
		foreach($employee_id as $emp_id)
		{
			$empId = $emp_id['id'];	
		}
		return $empId;
	}
	
	/*
	* Get List of Employees that Nominations
	*/
	
	public function getNominationList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('nominee' =>$employee_details_id));
		$select->where(array('status' =>'Pending'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* To get the department of the various roles such as HOD, PROGRAMME LEADER etc for PMS
	*/
	
	public function getSupervisorUnit($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->columns(array('departments_units_id'))
				->where(array('t1.id' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$supervisor_unit = NULL;
		foreach($resultSet as $set){
				$supervisor_unit = $set['departments_units_id'];
		}
		
		return $supervisor_unit;
	}
	
	/*
	* Get Employee Details of those Nominated Employees
	*/
	
	public function getNominatedEmployee($employee_details_id)
	{
		//the employee array stores that array data of the nominees
		$employeeArray = array();
		$tableNames = array(1 => 'peer_nomination', 2 => 'subordinate_nomination', 3 => 'beneficiary_nomination');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		for($i = 1; $i<=3; $i++){
			$select->from(array('t1' => $tableNames[$i]));
			$select->where(array('nominee' =>$employee_details_id));
			$select->columns(array('employee_details_id'));
	
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $tempData){
				array_push($employeeArray,$tempData['employee_details_id']);
			}
		}
		
		if(empty($employeeArray)){
			return $employeeArray;
		}
		
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'));
		$select->where(array('id' =>$employeeArray));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
		
	}
        
}