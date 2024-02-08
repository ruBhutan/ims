<?php

namespace CharacterCertificate\Mapper;

use CharacterCertificate\Model\CharacterCertificate;
use CharacterCertificate\Model\CharacterEvaluationCriteria;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements CharacterCertificateMapperInterface
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
	 * @var \CharacterCertificate\Model\CharacterCertificateInterface
	*/
	protected $certificatePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			CharacterCertificate $certificatePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->certificatePrototype = $certificatePrototype;
	}
	
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$emp_id));
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
	* @return array/CharacterCertificate()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('t1.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/**
	* Find all employees in an organisation
	*/
	public function findAllEmployees($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id'))
				->where('t1.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to saveCharacter Certificate Details
	 */
	
	public function saveCharacterEvaluation($data, $programmesId, $batch, $studentName, $username, $academic_module_tutors_id)
	{ 
		//get the student list
		$i=1;
		$studentIds = array();
		$studentData = $this->getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id);
		foreach($studentData as $value)
		{
			$studentIds[$i++] = $value['id'];
		}

		$empData = $this->getUserDetailsId($username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$employee_details_id = $emp['id'];
		}

		//get the organisation id
		$organisationID = $this->getOrganisationId($username);
		foreach($organisationID as $organisation){
			$organisation_id = $organisation['organisation_id'];
		} 

		$j = 1;
		$evaluationCriteriaIds = array();
		$evaluationCriteriaData = $this->getCriteriaList($organisation_id);
		foreach($evaluationCriteriaData as $value1)
		{
			$evaluationCriteriaIds[$j++] = $value1['id'];
		} 


		//var_dump($evaluationCriteriaIds); die();  
										
		//the following loop is to insert action plan
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{ 
				$j=1;
				foreach($value as $value2)
				{ 
					$action = new Insert('character_evaluation');
					$action->values(array(
						'evaluation_date'=> date('Y-m-d'),
						'employee_details_id' => $employee_details_id,
						'character_evaluation_criteria_id' => $evaluationCriteriaIds[$j],
						'student_id'=> $studentIds[$i],
						'evaluation' => $value2,
						'academic_module_tutors_id' => $academic_module_tutors_id,
					));
				
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
					$j++;
				}
				$i++;
			}
			return;
		}
		
		throw new \Exception("Database Error");
		
	}


	public function updateCharacterEvaluation($data, $id, $academic_module_tutors_id, $employee_details_id, $organisation_id)
	{ 
		//get the student list
		$i=1;
		$evaluationIds = array();
		$evaluationDatas = $this->getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id);
		foreach($evaluationDatas as $value)
		{
			$evaluationIds[$i++] = $value['id'];
		}  
										
		//the following loop is to insert action plan
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{  
				$evaluationData['evaluation'] = $value;
				$action = new Update('character_evaluation');
				$action->set($evaluationData);
            	$action->where(array('id = ?' => $evaluationIds[$i]));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			return;
		}

		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to save Criteria Details
	 */
	
	public function saveCriteria(CharacterEvaluationCriteria $certificateObject)
	{
		$certificateData = $this->hydrator->extract($certificateObject);
		unset($certificateData['id']);
		
		if($certificateObject->getId()) {
			//ID present, so it is an update
			$action = new Update('character_evaluation_criteria');
			$action->set($certificateData);
			$action->where(array('id = ?' => $certificateObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('character_evaluation_criteria');
			$action->values($certificateData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $certificateObject->setId($newId);
			}
			return $certificateObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $CharacterCertificateInterface
	 * 
	 * to save Evaluator Details
	 */
	
	public function saveEvaluator(CharacterCertificate $certificateObject)
	{
		$certificateData = $this->hydrator->extract($certificateObject);
		unset($certificateData['id']);
		unset($certificateData['evaluation_Criteria']);
		unset($certificateData['evaluation_Date']);
		unset($certificateData['character_Evaluator_Id']);
		unset($certificateData['character_Evaluation_Criteria_Id']);
		unset($certificateData['remarks']);
		
		if($certificateObject->getId()) {
			//ID present, so it is an update
			$action = new Update('character_evaluator');
			$action->set($certificateData);
			$action->where(array('id = ?' => $certificateObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('character_evaluator');
			$action->values($certificateData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $certificateObject->setId($newId);
			}
			return $certificateObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $programmesId, $username, $academic_module_tutors_id)
	{
		$semester_details = $this->getSemester($organisation_id);
		$academic_year = $semester_details['academic_year'];

		$academic_modules_allocation_details = $this->getAcademicModuleAllocationDetails($academic_module_tutors_id);
		$module_tutor_allocation = array();
		foreach($academic_modules_allocation_details as $details){
			$module_tutor_allocation = $details;
		}

		$programme_duration = $this->getProgrammeDuration($username, $module_tutor_allocation['academic_modules_allocation_id']); 
		$section = $module_tutor_allocation['section'];

		$module_year = $this->getModuleYear($module_tutor_allocation['academic_modules_allocation_id']);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('year_id', 'student_section_id', 'academic_year'))
			   ->join(array('t3' => 'student_section'),
					't3.id = t2.student_section_id', array('section'))
			   ->join(array('t4' => 'programmes'),
					't4.id = t1.programmes_id', array('programme_duration'))
			   ->where(array('t2.student_section_id' => $section, 't2.academic_year' => $academic_year));
	
		if($studentName){
			$select->where->like('first_name','%'.$studentName.'%');
		}
		if($programmesId){
			$select->where(array('programmes_id' =>$programmesId));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getAcademicModuleTutorSection($username, $academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_module_tutors'))
			   ->where(array('t1.module_tutor' => $username, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$section = array();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$section[] = $set['section'];
		} 

		return $section;
	}


	public function getProgrammeDuration($username, $academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_module_tutors'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('academic_modules_id', 'programmes_id'))
			   ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_duration'))
			   ->where(array('t1.module_tutor' => $username, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$programme_duration = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$programme_duration = $set['programme_duration'];
		} 

		return $programme_duration;
	}


	public function getModuleYear($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't1.id = t2.academic_modules_id', array('academic_year', 'programmes_id'))
			   ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_duration'))
			   ->where(array('t2.id' => $academic_modules_allocation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$module_year = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$module_year = $set['module_year'];
		} 

		return $module_year;
	}


	public function getEvaluatedCharacterStudentList($studentName, $programmesId, $username, $academic_module_tutors_id)
	{
		$semester_details = $this->getSemester($organisation_id);
		$academic_year = $semester_details['academic_year'];

		$academic_modules_allocation_details = $this->getAcademicModuleAllocationDetails($academic_module_tutors_id);
		$module_tutor_allocation = array();
		foreach($academic_modules_allocation_details as $details){
			$module_tutor_allocation = $details;
		}

		$academic_modules_allocation_id = $module_tutor_allocation['academic_modules_allocation_id'];

		$programme_duration = $this->getProgrammeDuration($username, $academic_modules_allocation_id); 
		$section = $module_tutor_allocation['section'];

		$module_year = $this->getModuleYear($module_tutor_allocation['academic_modules_allocation_id']);

		$empData = $this->getUserDetailsId($username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$employee_details_id = $emp['id'];
		} 

		//get the organisation id
		$organisationID = $this->getOrganisationId($username);
		foreach($organisationID as $organisation){
			$organisation_id = $organisation['organisation_id'];
		}  

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'character_evaluation'))
			   ->join(array('t2' => 'character_evaluation_criteria'), 
                        't1.character_evaluation_criteria_id = t2.id', array('evaluation_criteria'))
               ->join(array('t3'=>'student'),
                    	't1.student_id = t3.id', array('first_name','middle_name','last_name','stdId' => 'student_id'))
               ->join(array('t4' => 'student_semester_registration'),
           				't3.id = t4.student_id', array('student_section_id'))
               ->join(array('t5' => 'programmes'),
					't5.id = t3.programmes_id', array('programme_duration'))
			   ->where(array('t4.student_section_id' => $section, 't1.academic_module_tutors_id' => $academic_module_tutors_id, 't1.employee_details_id' => $employee_details_id, 't4.academic_year' => $academic_year));
			  // ->group(array('t1.student_id'));
	
		if($studentName){
			$select->where->like('t3.first_name','%'.$studentName.'%');
		}
		if($programmesId){
			$select->where(array('t3.programmes_id' =>$programmesId));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentCharacterEvaluation($studentName, $programmesId, $batch, $employee_id, $organisation_id)
	{ 
		$semester_details = $this->getSemester($organisation_id);
		$academic_year = $semester_details['academic_year']; 
		//$evaluation_criteria = $this->getCharacterEvaluationCriteria($organisation_id);

		$evaluation = array();
		$evaluationData = array();

		//foreach($evaluation_criteria as $key => $value){ 
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'student'))
				   ->columns(array('id', 'student_id','first_name','middle_name','last_name'))
				   ->join(array('t2' => 'character_evaluation'),
				   		't2.student_id = t1.id', array('evaluation'))
				   ->join(array('t3' => 'character_evaluation_criteria'), 
                            't2.character_evaluation_criteria_id = t3.id', array('evaluation_criteria'))
		   			->join(array('t4' => 'student_semester_registration'),
				   		't1.id = t4.student_id', array('student_section_id'))
		   			->join(array('t5' => 'student_section'),
						   't5.id = t4.student_section_id', array('section'))
					->where(array('t1.programmes_id' =>$programmesId))
					->where(array('t4.academic_year' => $academic_year))
					->where(array('t4.year_id' => $batch));
			$select->order(array('t1.first_name ASC', 't1.middle_name ASC', 't1.last_name ASC'));

			if($studentName){
				$select->where->like('t1.first_name','%'.$studentName.'%');
			}
					
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				//if(array_key_exists($set['student_id'], $evaluation)){
				$evaluation[$set['id']][$set['evaluation_criteria']] += $set['evaluation'];
			}  
			foreach($evaluation as $key1 => $value1){   
				foreach($value1 as $key2 => $value2){ 
					$number_of_evaluation = $this->getEvaluationNumber($key1, $key2);
					$evaluationData[$key1][$key2] = number_format((float)$value2/$number_of_evaluation, '2','.','');
				} 
			} 
			return $evaluationData;
	}


	public function getCharacterEvaluationCriteria($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'character_evaluation_criteria'))
					->columns(array('id', 'evaluation_criteria'));
		$select->where(array('t1.organisation_id' => $organisation_id));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$evaluation_criteria = array();
		$i=0;
		foreach($resultSet as $set){
			$evaluation_criteria[$i]['character_evaluation_criteria_id'] = $set['id'];
			$evaluation_criteria[$i]['evaluation_criteria'] = $set['evaluation_criteria'];	
			$i++;
		} 
		return $evaluation_criteria;
	}


	public function getEvaluationNumber($student_id, $character_evaluation_criteria)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'character_evaluation'))
				->columns(array('id'))
				->join(array('t2' => 'character_evaluation_criteria'),
					't2.id = t1.character_evaluation_criteria_id', array('evaluation_criteria'));
		$select->where(array('t1.student_id' => $student_id, 't2.evaluation_criteria' => $character_evaluation_criteria));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//$evaluation_number = 0;
		foreach($resultSet as $set){
			$evaluation_number[] = $set['id'];
		}  
		$evaluation_number = count($evaluation_number);
		
		return $evaluation_number;
	}


	public function getEvaluatedStudentList($studentName, $programmesId, $batch, $employee_id, $organisation_id)
	{ 
		$semester_details = $this->getSemester($organisation_id);
		$semester = $semester_details['academic_event'];
		$academic_year = $semester_details['academic_year'];

		$student_list = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		
		$select->from(array('t1' => 'student'))
				->columns(array('id','student_id','first_name','middle_name','last_name'))
				->join(array('t2' => 'character_evaluation'), 
					't1.id = t2.student_id', array('evaluation'))
				->join(array('t3' => 'student_semester_registration'), 
								't1.id = t3.student_id', array('student_section_id'))
				->join(array('t4' => 'student_section'), 
					't3.student_section_id = t4.id', array('section'))
				->where(array('t3.academic_year ' => $academic_year))
				->where(array('t1.programmes_id' => $programmesId))
				//->where(array('t3.semester_id' => $semester))
				->where(array('t3.year_id' => $batch));
        $select->order(array('t1.first_name ASC', 't1.middle_name ASC', 't1.last_name ASC', 't1.id ASC'));
		
		if($studentName){
			$select->where->like('t1.first_name','%'.$studentName.'%');
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$student_list[$set['id']]['name'] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'];
			$student_list[$set['id']]['student_id'] = $set['student_id'];
			$student_list[$set['id']]['section'] = $set['section'];
		} 
			
		return $student_list;
	}


	public function getStudentEvaluatedRating($id, $academic_module_tutors_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'character_evaluation'))
					->join(array('t2' => 'character_evaluation_criteria'), 
                            't1.character_evaluation_criteria_id = t2.id', array('evaluation_criteria'))
                    ->where(array('t1.student_id' => $id, 't1.academic_module_tutors_id' => $academic_module_tutors_id, 't1.employee_details_id' => $employee_details_id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getCharacterEvaluation($id)
	{
		$evaluation = array();
		$evaluationData = array();

		//foreach($evaluation_criteria as $key => $value){ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
			   ->columns(array('id'))
			   ->join(array('t2' => 'character_evaluation'),
			   			't1.id = t2.student_id', array('evaluation'))
			   ->join(array('t3' => 'character_evaluation_criteria'), 
						't2.character_evaluation_criteria_id = t3.id', array('evaluation_criteria'));
		$select->where(array('t1.id' => $id));

				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$evaluation[$set['id']][$set['evaluation_criteria']] += $set['evaluation'];
		}  
		foreach($evaluation as $key1 => $value1){   
			foreach($value1 as $key2 => $value2){ 
				$number_of_evaluation = $this->getEvaluationNumber($key1, $key2);
				$evaluationData[$key2] = number_format((float)$value2/$number_of_evaluation, '2','.','');
			} 
		}
		return $evaluationData;
	}


	public function getStudentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->join(array('t3'=>'student_semester_registration'),
                            't1.id = t3.student_id', array('student_section_id'))
                    ->join(array('t4'=>'student_section'),
                            't4.id = t3.student_section_id', array('section'))
                    ->where(array('t1.id' => $id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStdPersonalDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->join(array('t3'=>'dzongkhag'),
                            't1.dzongkhag = t3.id', array('dzongkhag_name'))
                    ->join(array('t4'=>'gewog'),
							't4.id = t1.gewog', array('gewog_name'))
					->join(array('t5'=>'village'),
							't5.id = t1.village', array('village_name'))
					->join(array('t6' => 'organisation'),
							't6.id = t1.organisation_id', array('organisation_name', 'organisation_dzongkha_name', 'address'))
                    ->where(array('t1.id' => $id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStudentRelationDetails($type, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_relation_details'))
                    ->where(array('t1.student_id' => $id, 't1.relation_type' => $type));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getOrganisationLogo($type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($type == 'Logo'){
			if($organisation_id != NULL){
				$select->from(array('t1' => 'organisation_document'))
						->where(array('t1.organisation_id' => $organisation_id, 't1.document_type' => $type));
			}else{
				$select->from(array('t1' => 'organisation_document'))
						->where(array('t1.organisation_id' => '1', 't1.document_type' => $type));
			}
		}else if($type == 'Banner'){
			if($organisation_id != NULL){
				$select->from(array('t1' => 'organisation_document'))
						->where(array('t1.organisation_id' => $organisation_id, 't1.document_type' => $type));
			}else{
				$select->from(array('t1' => 'organisation_document'))
						->where(array('t1.organisation_id' => '1', 't1.document_type' => $type));
			}
		}		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$logo_location = NULL;
		foreach($resultSet as $set){
			$logo_location = $set['documents'];
		}
		return $logo_location;
	}
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else {
			$select->where(array('student_id' =>$username));
		}
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/*
	 * Get Criteria List
	*/
	
	public function getCriteriaList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'character_evaluation_criteria'));
		$select->columns(array('id', 'evaluation_criteria', 'remarks'));
		$select->where(array('t1.organisation_id' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	 * 
	 * to get the list of criteria based on id
	 */
	public function findCharacterCriteria($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'character_evaluation_criteria')) // base table
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get list of staff for evaluator list
	*/
	
	public function getStaffList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name'))
				->where('t1.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' '.$set['last_name'];
		}
		return $selectData;
	}
	
	/*
	* get list of programmes given the organisation_id
	*/
	
	public function getProgrammeList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'programmes'));
		$select->columns(array('id','programme_name'))
				->where('t1.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['programme_name'];
		}
		return $selectData;
	}

	
	
	/*
	* get list of programmes an evaluator given the employee details id
	*/
	
	public function getEvaluatorProgrammeList($employee_details_id)
	{ 
		$semester_details = $this->getSemester($organisation_id);
		$academic_year = $semester_details['academic_year'];

		//First- Get the list of programme for the evaluator
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_tutors'))
					->columns(array('id', 'academic_modules_allocation_id'))
					->join(array('t2'=>'academic_modules_allocation'),
                            't1.academic_modules_allocation_id = t2.id', array('module_title','academic_year','year','semester'))
					->join(array('t3'=>'academic_modules'),
                            't2.academic_modules_id = t3.id', array('programmes_id', 'module_year'))
					->join(array('t4'=>'programmes'),
                            't3.programmes_id = t4.id', array('programme_name', 'programme_duration'))
					->join(array('t5'=>'student_section'),
                            't5.id = t1.section', array('stdSection' => 'section'))
					->where(array('t2.academic_year' => $academic_year))
					->where->like('t1.module_tutor', $employee_details_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['module_title'].' ('.$set['programme_name'].' - '.$set['stdSection'].')';
		}
		return $selectData;
	}
	
	/*
	* Get the list of the batch the evaluator has to evaluate
	*/
	
	public function getBatchList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'programmes'));
		$select->where(array('organisation_id' =>$organisation_id));
		$select->columns(array('programme_duration' => new \Zend\Db\Sql\Expression('MAX(programme_duration)')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach ($result as $res) {
            $tmp_number = $res['programme_duration'];
			preg_match_all('!\d+!', $tmp_number, $matches);
			$max_years = implode(' ', $matches[0]);
        }
		
		for($i=1; $i<=$max_years; $i++){
			$selectData[$i] = $i ." Year ";
		}
        return $selectData;
		
		/*
		
		//Old function - with selection of character evaluator
		
		$select->from(array('t1' => 'character_evaluator'));
		$select->columns(array('batch'))
				->where('t1.employee_details_id = ' .$employee_details_id);
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set){
			$selectData[$set['batch']] = $set['batch'];
		}
		return $selectData;
		*/
	}


	public function getAcademicModuleAllocationDetails($academic_module_tutors_id)
	{
		//First- Get the list of programme for the evaluator
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1'=>'academic_module_tutors'))
					->where(array('t1.id = ' .$academic_module_tutors_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the details of the programme for a module tutor given the academic modules allocation id
	*/
	
	public function getBatchDetails($academic_modules_allocation_id, $type)
	{ 
		//First- Get the list of programme for the evaluator
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t2'=>'academic_modules_allocation'))
                    ->columns(array('id','module_title','academic_year','year','semester'))
					->join(array('t3'=>'academic_modules'),
                            't2.academic_modules_id = t3.id', array('programmes_id'))
					->join(array('t4'=>'programmes'),
                            't3.programmes_id = t4.id', array('programme_name'))
					->where('t2.id = ' .$academic_modules_allocation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$batchDetails = NULL;
		if($type == 'programmes_id'){
			foreach($resultSet as $set){
			$batchDetails = $set['programmes_id'];
			}
		} else{
			foreach($resultSet as $set){
				$semester = $set['semester'];
				$year = $set['year']; 
			}
			if((int)$semester%2 == 0)
					$batchDetails = date('Y')-$year-1; 
			else
				$batchDetails = date('Y')-$year;
		}
		
		return $batchDetails;
	}
	
	/*
	* Get list of evaluators
	*/
	
	public function getEvaluatorList($organisation_id)
	{
		//need to get list of employees in organisation and store it in an array
		$i= 0;
		$employee_ids = array();
		$employeeData = $this->findAllEmployees($organisation_id);
		foreach($employeeData as $data)
		{
			$employee_ids[$i++] = $data['id'];
		}
				
		//get list of evaluators in organisation
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'character_evaluator'));
		$select->where(array('employee_details_id ' => $employee_ids));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* get details of the evaluators
	*/
	
	public function getEvaluatorDetails($organisation_id)
	{
		//get the list of evaluators
		$i= 0;
		$employee_ids = array();
		$evaluatorList = $this->getEvaluatorList($organisation_id);
		foreach($evaluatorList as $data)
		{
			$employee_ids[$i++] = $data['employee_details_id'];
		}
		
		//get the details of evaluators in organisation
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'));
		$select->columns(array('id','first_name','middle_name','last_name'))
				->where(array('id ' => $employee_ids));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckCharacterEvaluation($academic_module_tutors_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'character_evaluation'));
        $select->where(array('t1.employee_details_id' => $employee_details_id, 't1.academic_module_tutors_id' => $academic_module_tutors_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	/*
	 * Get the semester from the database
	 */
	
	public function getSemester($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('academic_year'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		$select->where('t2.organisation_id = ' .$organisation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = NULL;
		
		foreach($resultSet as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
				$semester['academic_year'] = $set['academic_year'];

			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
				$semester['academic_year'] = $set['academic_year'];
			}
		}
		return $semester;
	}
}