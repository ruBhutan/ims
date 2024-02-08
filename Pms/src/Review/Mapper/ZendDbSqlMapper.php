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
	public function listAdministrativeAppraisal($tableName, $employee_id, $status, $appraisal_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName)) 
                    ->where('t1.employee_details_id = ' .$employee_id)
					->where(array('t1.status = ?' => $status))
					->where(array('t1.appraisal_period = ?' => $appraisal_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Appraisal()
	*/
	public function listEmployeeAppraisal($tableName, $employee_id, $status, $appraisal_year)
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
					->where(array('t1.appraisal_period = ?' => $appraisal_year))
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
		var_dump($data);
		die();
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
					$evaluationData['supervisor_Evaluation'] = $value;
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
	
	public function saveFeedbackEvaluation($feedback_for, $nomination_id, $data, $employee_id, $appraisal_period, $employee_details_id)
	{
		$tableName = 'feedback_ratings_'.$feedback_for;
		if(!empty($data)){
			foreach($data as $key=>$value){
				$evaluationData['appraisal_Period'] = $appraisal_period;
				$evaluationData['ratings'] = $value;
				$evaluationData['employee_Details_Id'] = $employee_id;
				$evaluationData['evaluation_By'] = $employee_details_id;
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
         * Save Student Feedback
         * 
         * the academic module is actually the id from the table "academic module tutor"
         * can be used to extract the academic modules allocation id and hence module code
         */
        
        public function saveStudentFeedback($rating_data, $academic_module, $module_tutor, $appraisal_period, $student_id)
        {
            //first get the module allocation id and the module code
            $module_data = $this->getAcademicModuleAllocationDetails($academic_module);
            
            //get the employee details id of module tutor. Will use the academic module to get the employee details id
            $employee_details = $this->getEmployeeIdByName($academic_module, $module_tutor);
            echo $employee_details;
            var_dump($module_data);
            die();
            
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

	public function getIwpDeadline($iwp_type)
	{
		$deadline = NULL;
		$appraisal_period = $this->getAppraisalPeriod();
		 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pms_activation_dates'))
							->columns(array('start_date', 'end_date'));
		$select->where(array('pms_year' =>$appraisal_period));
		$select->where(array('date_for' =>$iwp_type));

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
		$empty = array();
		$sql = new Sql($this->dbAdapter);
		
		
		if(preg_match('/SECTION_HEAD/', $role)|| preg_match('/HOD/', $role)|| preg_match('/PROGRAMME_LEADER/', $role)){
			//need to get the supervisor Department
			$supervisor_department = $this->getSupervisorDepartment($employee_details_id);
			$action = $sql->select();
			$action->from(array('t1' => 'user_workflow')) 
					->columns(array('role','role_department','type'))
					->join(array('t2' => 'users'), 
                            't1.role = t2.role', array('username'))
					->join(array('t3' => 'employee_details'), 
                            't2.username = t3.emp_id', array('id', 'departments_units_id'));
			//$action->where('t1.role_department = ' .$supervisor_department);
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
		} else if(preg_match('/DSA/', $role)|| preg_match('/PRESIDENT/', $role)|| preg_match('/DAA/', $role) || preg_match('/DIRECTOR/', $role)){
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
		return $empty;
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
		$appraisal_period = $this->getAppraisalPeriod();
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'pms_academic_weight')) 
				->columns(array('category', 'weight', 'maximum_api'))
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
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $appraisal_period));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_api = array();
		foreach($resultSet as $set){
			$academic_api[] = $set;
		}
		
		//loop through each activity and add the scores according to the activity
		$academic_score = 0;
		$temp_score = array();
		foreach($academic_weight as $key=>$value){
			foreach($academic_api as $key1=>$value1){
				if($value1['pms_nature_activity_id'] == $value['id']){
					if(!array_key_exists($value1['pms_nature_activity_id'], $temp_score))
						$temp_score[$value1['pms_nature_activity_id']] = (int) $value1['performance_rating'];
					else
						$temp_score[$value1['pms_nature_activity_id']] += (int) $value1['performance_rating'];
				}
			}
		}
		
		//loop through the scores according to activity and ensure that the score does not exceed the maximum activity score
		$temp_score_2 = array();
		$maximum_api = array();
		$api_percentage = array();
		foreach($academic_weight as $key=>$value){
			if(array_key_exists($value['id'], $temp_score)){
				if($temp_score[$value['id']] >= $value['maximum_score'])
					$temp_score[$value['id']] = $value['maximum_score'];
				
				if(!array_key_exists($value['category'], $temp_score_2))
					$temp_score_2[$value['category']] = (int) $temp_score[$value['id']];
				else
					$temp_score_2[$value['category']] += (int) $temp_score[$value['id']];
			}
			$maximum_api[$value['category']] = $value['maximum_api'];
			$api_percentage[$value['category']] = $value['weight'];
		}
		
		//ensure that the score does not exceed the mamimum api based on the themes
		// then take the score according to the percentage weightage of the score and return the academic score
		foreach($maximum_api as $key => $value){
			if(array_key_exists($key, $temp_score_2)){
				if($temp_score_2[$key] >= $value)
					$temp_score_2[$key] = $value;
				
				$academic_score += $temp_score_2[$key]*($api_percentage[$key]/100);
			}
		}
		return $academic_score;
	}
	
	public function getAdministrativePerformanceScore($employee_details_id)
	{
		$appraisal_period = $this->getAppraisalPeriod();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		
		$select->from(array('t1' => 'iwp_subactivities')) 
				->columns(array('supervisor_evaluation'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $appraisal_period));
		
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
		$appraisal_period = $this->getAppraisalPeriod();
		
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('beneficiary_feedback_questions');
		$beneficiary_score = 0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_beneficiary')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' =>$appraisal_period));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		if($question_count != 0){
			if(count($result)/$question_count >= 10){
				foreach($resultSet as $set){
					$beneficiary_score += (int) $set['ratings'];
				}
				$beneficiary_score = $beneficiary_score/count($result);
			}
		}
		
		return $beneficiary_score;
	}
	
	public function getPeerFeedbackScore($employee_details_id)
	{
		$appraisal_period = $this->getAppraisalPeriod();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_peer')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $appraisal_period));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('peer_feedback_questions');
		$peer_score = 0;
		if($question_count != 0){
			if(count($result)/$question_count >= 10){
				foreach($resultSet as $set){
					$peer_score += (int) $set['ratings'];
				}
				$peer_score = $peer_score/count($result);
			}
		}
		return $peer_score;
	}
	
	public function getSubordinateFeedbackScore($employee_details_id)
	{
		$appraisal_period = $this->getAppraisalPeriod();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_subordinate')) 
				->columns(array('ratings'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $appraisal_period));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		//need to get number of feedback questions
		$question_count = $this->getFeedbackQuestionCount('subordinate_feedback_questions');
		$subordinate_score = 0;
		if($question_count != 0){
			if(count($result)/$question_count >= 10){
				foreach($resultSet as $set){
					$subordinate_score += (int) $set['ratings'];
				}
				$subordinate_score = $subordinate_score/count($result);
			}
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
	
	//to Get the Appraisal Period
	private function getAppraisalPeriod()
	{
		$appraisal_period = NULL;
		if(date('m') < 6){
			$appraisal_period = (date('Y')-1)."-".(date('Y'));
		 } else {
			 $appraisal_period = (date('Y'))."-".(date('Y')+1);
		 }
		 return $appraisal_period;
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
		$select->where(array('status' =>'Approved'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* To get the department of the various roles such as HOD, PROGRAMME LEADER etc for PMS
	*/
	
	public function getSupervisorDepartment($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->columns(array('departments_id'))
				->where(array('t1.id' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$supervisor_unit = NULL;
		foreach($resultSet as $set){
				$supervisor_unit = $set['departments_id'];
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
        
	/*
	 * Get the module allocation id and the module code given the "Academic Module"
	 * which, in this case, because of ajax, it is the id of the table "academic module tutors"
	 */
	
	public function getAcademicModuleAllocationDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_module_tutors'))
				->join(array('t2' => 'academic_modules_allocation'), 
						't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
				->join(array('t3'=>'academic_modules'),
						't2.academic_modules_id = t3.id', array('module_code'))
				->where('t1.id = ' .$id); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$module_details = array();
		foreach($resultSet as $set){
			$module_details[$set['academic_modules_allocation_id']] = $set['academic_modules_allocation_id'];
			$module_details[$set['module_code']] = $set['module_code'];
		}
		
		return $module_details;
	}
	
	/*
	 * Get the employee details id given the module given the "Academic Module"
	 * which, in this case, because of ajax, it is the id of the table "academic module tutors"
	 * 
	 * It also take the Name of the module tutor in case there is multiple module tutors
	 */
	
	public function getEmployeeIdByName($id, $module_tutor_name)
	{
		//setting the defaults
		$employee_id = NULL;
		$employee_name =  explode(' ', $module_tutor_name);
		$first_name = $employee_name[0];
		$middle_name = $employee_name[1];
		$last_name = $employee_name[2];
		
		//given the $id for academic_module_tutors, get the tutor's id details
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_module_tutors'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$module_tutors_list = $set['module_tutor'];
		}
		$tutors_list = explode('/', $module_tutors_list);
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql->select();
		$select2->from(array('t1' => 'employee_details'));
		$select2->where->like('first_name','%'.$first_name.'%');
		$select2->where->like('last_name','%'.$last_name.'%');
		
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set2){
			$temp_name = $set2['first_name'].' '.$set2['middle_name'].' '.$set2['last_name'];
			$temp_emp_id = $set2['emp_id'];
			if(in_array($temp_emp_id, $tutors_list)){
				if($temp_name === $module_tutor_name)
					$employee_id = $set2['id'];
			}
		}
		
		return $employee_id;
	}
		
	/*
	* List Employees 
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
		$select->where(array('t1.organisation_id' =>$organisation_id));
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
		
		foreach($resultSet as $set){
			$employee_list[$set['id']] = $set;
		}
		
		return $employee_list;
	}
}