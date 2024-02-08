<?php

namespace Examinations\Mapper;

use Examinations\Model\Examinations;
use Examinations\Model\ExamHall;
use Examinations\Model\ExaminationCode;
use Examinations\Model\ExamInvigilator;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ExaminationsMapperInterface
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
	 * @var \Examinations\Model\ExaminationsInterface
	*/
	protected $examinationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Examinations $examinationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->examinationPrototype = $examinationPrototype;
	}
	
	
	/**
	* @return array/Examinations()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'examination_timetable'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'examination_hall'), 
                            't1.examination_hall_id = t2.id', array('hall_no'))
                    ->join(array('t3'=>'programmes'),
                            't1.programmes_id = t3.id', array('programme_name'))
					->join(array('t4'=>'academic_modules'),
                            't1.academic_modules_id = t4.id', array('module_title'))
                    ->where(array('t1.organisation_id' =>$organisation_id));
		} else if($tableName == 'examination_invigilation_duties'){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'examination_timetable'), 
                            't1.examination_timetable_id = t2.id', array('start_time','end_time','exam_date'))
					->join(array('t3' => 'examination_hall'), 
                            't2.examination_hall_id = t3.id', array('hall_no'))
                    ->join(array('t4'=>'programmes'),
                            't2.programmes_id = t4.id', array('programme_name'))
					->join(array('t5'=>'academic_modules'),
                            't2.academic_modules_id = t5.id', array('module_title'))
					->join(array('t6'=>'employee_details'),
                            't1.employee_details_id = t6.id', array('first_name','middle_name','last_name','emp_id'))
                    ->where(array('t2.organisation_id' =>$organisation_id));
		} else {
			$select->from(array('t1' => $tableName));
			$select->where(array('organisation_id' =>$organisation_id));
		}
		
		/*
		if($tableName=='examination_applicant')
			$select->where(array('id' =>$applicant_id));
		else
			$select->where(array('examination_applicant_id' =>$applicant_id));
		*/
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
	* Get organisation id based on the programme_id
	*/
	
	private function getOrganisationIdByProgramme($programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'programmes'));
		$select->where(array('id' =>$programmes_id));
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['organisation_id'];
		}
	}

	/*
	*take username and return the employee first name, middle name and last name
	*/
	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' =>$username));
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
	* Save Examination Hall
	*/
	
	public function saveExaminationHall(ExamHall $examinationObject)
	{
		$examinationData = $this->hydrator->extract($examinationObject);
		unset($examinationData['id']);

		if($examinationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('examination_hall');
			$action->set($examinationData);
			$action->where(array('id = ?' => $examinationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('examination_hall');
			$action->values($examinationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $examinationObject->setId($newId);
			}
			return $examinationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Examination Timetable
	* Model is not used as we are extracting data from the form due to AJAX 
	*/
	
	public function saveExaminationTimetable($data)
	{		
		$examinationData['programmes_Id'] = $data['programmes_id'];
		//$examinationData['academic_Modules_Id'] = $this->getAjaxDataId('academic_modules', $data['academic_modules_id'], $data['programmes_id']);
		$examinationData['academic_Modules_Id'] = $data['academic_modules_id'];
		$examinationData['examination_Hall_Id'] = $data['hall_no'];
		$examinationData['start_Time'] = $data['start_time'];
		$examinationData['end_Time'] = $data['end_time'];
		$examinationData['exam_Date'] = date("Y-m-d", strtotime(substr($data['exam_date'],0,10)));
		$examinationData['organisation_Id'] = $data['organisation_id'];

		if($data['id']) {
			//ID present, so it is an update
			$action = new Update('examination_timetable');
			$action->set($examinationData);
			$action->where(array('id = ?' => $data['id']));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('examination_timetable');
			$action->values($examinationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$data['id']=$newId;
			}
			return $data;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the Hall Arrangement
	*/
	
	public function saveHallArrangement()
	{
		$examinationData = $this->hydrator->extract($examinationObject);
		unset($examinationData['id']);
		unset($examinationData['examination_Applicant_Id']);

		if($examinationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('examination_applicant');
			$action->set($examinationData);
			$action->where(array('id = ?' => $examinationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('examination_applicant');
			$action->values($examinationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $examinationObject->setId($newId);
			}
			return $examinationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Examination Invigilator
	*/
	
	public function saveExamInvigilator(ExamInvigilator $invigilatorObject)
	{
		$invigilatorData = $this->hydrator->extract($invigilatorObject);
		unset($invigilatorData['id']);
		//for the future
		unset($invigilatorData['organisation_Id']);

		if($invigilatorObject->getId()) {
			//ID present, so it is an update
			$action = new Update('examination_invigilation_duties');
			$action->set($invigilatorData);
			$action->where(array('id = ?' => $invigilatorObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('examination_invigilation_duties');
			$action->values($invigilatorData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $invigilatorObject->setId($newId);
			}
			return $invigilatorObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Delete Exam Invigilator
	*/
	
	public function deleteExamInvigilator($id)
	{
		$action = new Delete('examination_invigilation_duties');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	* Get the list of students searched for by various parameters
	*/
	
	public function getStudentToAddList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'));
		$select->where(array('t1.student_status_type_id' => '1'));
		
		if($studentName){
			$select->where->like('first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('programme' =>$programme));
		}
		if($organisation_id){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of students searched for by various parameters to add to back paper
	*/
	
	public function getStudentBackPaperList($programme, $batch)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		$semester = $this->getSemester($organisation_id);
		if($semester == 'Spring'){
			$enrollment_year = date('Y') - $batch;
		} else{
			$enrollment_year = date('Y') - $batch;
		}
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
				->columns(array('id','student_id','first_name','middle_name','last_name','enrollment_year'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'));
		$select->where(array('t1.student_status_type_id' => '1'));
		$select->where(array('t1.programmes_id' =>$programme));
		$select->where(array('t1.enrollment_year' =>$enrollment_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}
	
	/*
	* Save the List of Students with Back paper
	*/
	
	public function addStudentBackPaper($backpaper_data, $programme, $academic_modules_id, $backlog_academic_year, $backlog_semester)
	{
		$modules_data = $this->getAcademicModulesData($academic_modules_id);
		
		$backpaperData['programmes_Id'] = $programme;
		$backpaperData['module_Title'] = $modules_data['module_title'];
		$backpaperData['module_Code'] = $modules_data['module_code'];
		$backpaperData['backlog_Academic_Year'] = $backlog_academic_year;
		$backpaperData['backlog_Semester'] = $backlog_semester;
		$backpaperData['backlog_Status'] = 'Not Cleared';
		
		foreach($backpaper_data as $key => $value){
			$backpaperData['student_Id'] = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
			$backpaperData['backlog_In'] = $value;
			$action = new Insert('student_repeat_modules');
			$action->values($backpaperData);
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		
		return;
	}
	
	/*
	* Get the modules data for back papers
	*/
	
	private function getAcademicModulesData($academic_modules_id)
	{
		$module_data = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules'))
				->columns(array('module_title','module_code'));
		$select->where(array('t1.id' =>$academic_modules_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
		foreach($resultSet as $set){
			$module_data['module_title'] = $set['module_title'];
			$module_data['module_code'] = $set['module_code'];
		}
		return $module_data;
	}
	
	/*
	* Get the Academic Year List for Adding Backpapers
	*/
	
	public function getAcademicYearList($organisation_id)
	{		
		$years = $this->createYearList($organisation_id);
		$academic_years = array();
		$present_year = date('Y');
		
		for($i=count($years); $i>=1; $i--){
			$academic_years[($present_year-$i)."-".($present_year-$i+1)] = ($present_year-$i)."-".($present_year-$i+1);
		}
		
		return $academic_years;
	}
	
	/*
	* Get the Semester List List for Adding Backpapers
	*/
	
	public function getSemesterList($organisation_id)
	{
		$years = $this->createYearList($organisation_id);
		$semesters = array();
		
		for($i=1; $i<=(2*count($years)); $i++){
			$semesters[$i] = $i;
		}
		return $semesters;
	}
	
	/*
	* Get the list of students that are eligible to sit for exams
	* should look into the attendance, finance records etc.
	*
	* This function will also take care of getting the list of non-eligible students
	*/
	
	public function getEligibleStudentList($data, $organisation_id, $type)
	{
		$student_list = array();
		//first check whether the non-eligible students have been generated or not
		$eligibility_generation_status = $this->getStudentExaminationEligibilityStatus($organisation_id);
		
		if($eligibility_generation_status == NULL){
			//not generated, so generate the list of non-eligibile students
			$this->generateStudentExaminationEligibility($organisation_id);
		}
		
		$semester = $this->getSemesterNumber($data['year'], $data['programmes_id']);
		$programme_id = $data['programmes_id'];
		$academic_modules_allocation_id = $this->getAjaxDataId('academic_modules_allocation', $data['academic_modules_id'], $data['programmes_id']);
		//enrollment year
		$present_month = date('m');
		if((int)$present_month <= 6)
			$academic_year = date('Y')-((int) $data['year']);
		else 
			$academic_year = date('Y')-((int) $data['year'])-1;
		//list of non-eligible students
		$sql = new Sql ($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_examination_noneligibility'))
				->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('organisation_id'));
		$select->where(array('t2.organisation_id' =>$organisation_id));
		$select->where(array('t1.academic_modules_allocation_id' =>$academic_modules_allocation_id));
		$select->where(array('t1.status' =>'Non-Eligible'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$student_ids = array();
		foreach($resultSet as $set){
			$student_ids[] = $set['student_id'];
		}
		
		//if student_ids is null
		if(empty($student_ids)){
			return $student_list;
		} else{
			$sql2 = new Sql($this->dbAdapter);
			$select2 = $sql2->select();
			//Needs to be changed to student semester registration when complete
			if($type == 'Eligible'){
				$select2->from(array('t1' => 'student'))
						->join(array('t2' => 'programmes'), 
								't1.programmes_id = t2.id', array('programme_name'));
				$select2->where(array('t1.organisation_id' =>$organisation_id));
				$select2->where(array('t1.programmes_id' => $data['programmes_id']));
				$select2->where(array('t1.enrollment_year' => $academic_year));
				$select2->where->notIn('t1.id', $student_ids);
			} else {
				$select2->from(array('t1' => 'student'))
						->join(array('t2' => 'programmes'), 
								't1.programmes_id = t2.id', array('programme_name'));
				$select2->where(array('t1.organisation_id' =>$organisation_id));
				$select2->where(array('t1.programmes_id' => $data['programmes_id']));
				$select2->where(array('t1.enrollment_year' => $academic_year));
				$select2->where(array('t1.id ' => $student_ids));
			}
			/*
			if($type == 'Eligible'){
				$select2->from(array('t1' => 'student_semester_registration'))
						 ->join(array('t2' => 'student'), 
								't1.student_id = t2.id', array('programmes_id'))
						->join(array('t3' => 'student_semester'), 
								't1.student_semester_id = t3.id', array('semester'));
				$select2->where(array('organisation_id' =>$organisation_id, 't2.programmes_id' => $data['programme'], 't3.semester' => $semester));
				$select2->where->notIn('t1.id', $student_ids);
			} else {
				$select2->from(array('t1' => 'student_semester_registration'))
						 ->join(array('t2' => 'student'), 
								't1.student_id = t2.id', array('programmes_id'))
						->join(array('t3' => 'student_semester'), 
								't1.student_semester_id = t3.id', array('semester'));
				$select2->where(array('organisation_id' =>$organisation_id, 't2.programmes_id' => $data['programme'], 't3.semester' => $semester));
				$select2->where(array('t1.id ' => $student_ids));
			}
			*/
			$stmt2 = $sql2->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			return $resultSet2->initialize($result2);
		}
		
	}
	
	/*
	* Generate Examination Codes for Students
	*/
	
	public function generateExamCodes(ExaminationCode $examinationObject, $data)
	{
		//table name - student_examination_code
		$examinationData = $this->hydrator->extract($examinationObject);
		unset($examinationData['id']);
		//new function as the old getAjaxData has too many dependents
		$examinationData['academic_Modules_Id'] = $this->getAjaxModuleId('academic_modules_allocation', $data['academic_modules_id'], $data['programmes_id']);
		$examinationData['programmes_Id'] = $data['programmes_id'];
		$examinationData['code_Date'] = date('Y-m-d');
		$programme_code = $examinationData['programme_Code'];
		$module_code = $examinationData['academic_Module_Code'];
		unset($examinationData['programme_Code']);
		unset($examinationData['academic_Module_Code']);
		$student_list = $this->getStudentList($examinationData['academic_Modules_Id'], $examinationData['programmes_Id'], $examinationData['organisation_Id']);
                
		$random_numbers = $this->generateRandomNumbers();

		foreach($student_list as $key=>$value){
			$examinationData['student_Id'] = $key;
			$examinationData['examination_Code'] = $programme_code.$module_code.array_shift($random_numbers);
			$action = new Insert('student_examination_code');
			$action->values($examinationData);
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $examinationObject->setId($newId);
			}
			return $examinationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Generate Secret Examination Codes for Students
	*/
	
	public function generateSecretCodes($data)
	{
		// table name - student_exam_secret_code
	}
	
	/*
	* Generate Random Numbers
	*/
	
	public function generateRandomNumbers()
	{
		$random_numbers = array();
		$i=0;
		while($i<999)
		{
			$number = mt_rand(1,999);
			if(!in_array($number, $random_numbers)){
				$random_numbers[$i++] = $number;
			}
		}
		
		return $random_numbers;
	}
	
	/*
	* List Students
	*
	* This will not be used once the eligiblity table is filled.
	* Just USED FOR TESTING SO THAT WE CAN GET THE STUDENT IDS
	* CHANGE IT DURING IMPLEMENTATION
	*/
	
	public function getStudentList($academic_modules_id, $programme, $organisation_id)
	{
		$batch = $this->getBatch($academic_modules_id);
		//need to get which part of the year so that we do not mix the enrollment years
		$present_month = date('m');
		if((int)$present_month <= 6)
			$enrollment_year = date('Y')-$batch+1;
		else 
			$enrollment_year = date('Y')-$batch;
                
		$student_list = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
				->columns(array('id', 'student_id'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
				->join(array('t3' => 'academic_modules_allocation'), 
                            't1.programmes_id = t2.id', array('year'));
		$select->where(array('t1.enrollment_year' =>$enrollment_year));
		if($programme){
			$select->where(array('t1.programmes_id' =>$programme));
		}
		if($organisation_id){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
                
		foreach($resultSet as $set){
			$student_list[$set['id']] = $set['student_id'];
		}
		return $student_list;
	}
	
	/*
	* Get the batch for generating the student list
	*/
	
	public function getBatch($academic_modules_id)
	{
		$batch = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('year'));
		$select->where(array('t1.id' =>$academic_modules_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 
		foreach($resultSet as $set){
			$batch = $set['year'];
		}
		return $batch;
	}
	
	/*
	* Get the Examination code, given a programme and module name
	*/
	
	public function getExaminationCode($data)
	{
		//$modules_id = $this->getAjaxDataId('academic_modules_allocation', $data['academic_modules_id'], $data['programmes_id']);
		$modules_id = $data['academic_modules_id'];
		$programmes_id = $data['programmes_id'];
		$organisation_id = $data['organisation_id'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_examination_code'))
				 ->columns(array('examination_code'))
				 ->join(array('t2' => 'student'), 
									't1.student_id = t2.id', array('student_id'));
		$select->where(array('t1.academic_modules_id' =>$modules_id));
		$select->where(array('t1.programmes_id' =>$programmes_id));
		$select->where(array('t1.organisation_id' =>$organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Examination Dates - the start and end of the semester exams
	*/
	
	public function getExaminationDates($organisation_id)
	{
		$present_month= date('m');
		$from_date = date('Y').'-'.($present_month-2).'-01';
		$to_date = date('Y').'-'.($present_month+2).'-01';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'examination_timetable'));
		$select->where(array('t1.exam_date >= ? ' => $from_date));
		$select->where(array('t1.exam_date <= ? ' => $to_date));
		$select->where(array('t1.organisation_id' =>$organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$exam_dates['start_date'] = NULL;
		$exam_dates['end_date'] = NULl;
		foreach($resultSet as $set){
			if(strtotime($set['exam_date']) < strtotime($exam_dates['start_date']) || $exam_dates['start_date'] == NULL){
				$exam_dates['start_date'] = $set['exam_date'];
			}
			if(strtotime($set['exam_date']) > strtotime($exam_dates['end_date'])){
				$exam_dates['end_date'] = $set['exam_date'];
			}
		}
		return $exam_dates;
	}
	
	/*
	* Get the Examination Timetable for a given programme, year or employee id
	*/
	
	public function getExaminationTimetable($data, $employee_id, $organisation_id)
	{
		$programme_id = $data['programme'];
		$year = $data['year'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'examination_timetable')) 
				->join(array('t2' => 'examination_hall'), 
						't1.examination_hall_id = t2.id', array('hall_no'))
				->join(array('t3'=>'programmes'),
						't1.programmes_id = t3.id', array('programme_name'))
				->join(array('t4'=>'academic_modules'),
						't1.academic_modules_id = t4.id', array('module_title','module_code'))
				->join(array('t5'=>'academic_modules_allocation'),
						't5.academic_modules_id = t4.id', array('year'))
				->where(array('t1.organisation_id' =>$organisation_id));
		if($employee_id){
			$select->where(array('t1.employee_details_id' =>$employee_id));
		}
		if($programme_id){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		if($year){
			$select->where(array('t5.year' =>$year));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Generic function to get the details of a table given an id
	* 
	* Takes $table name and $id
	*/
	
	public function getTableDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('id' =>$id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Check whether the Student Non-Eligible List have been generated or not
	*/
	
	public function getStudentExaminationEligibilityStatus($organisation_id)
	{
		//value to be returned
		$generation_status = NULL;
		
		$academic_year = date('Y');
		$status = 'Generated';
		$present_month = date('m');
		if((int)$present_month > 6)
			$semester = 'Odd Semesters';
		else 
			$semester = 'Even Semesters';
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_examination_eligibility_generation'));
		$select->columns(array('status'));
		$select->where(array('academic_year' => $academic_year, 'semester' => $semester, 'status' => $status));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$generation_status = $set['status'];
		}
		
		return $generation_status;
	}
	
	/*
	* Generate the list of non-eligible student list
	* and insert into student_examination_noneligibility
	*
	* need to update two tables
	* student eligibility generation table and student non-eligibility list
	*/
	
	public function generateStudentExaminationEligibility($organisation_id)
	{
		$present_month = date('m');
		if((int)$present_month > 6)
			$semester = 'Odd Semesters';
		else 
			$semester = 'Even Semesters';
		
		//data for eligibility status table
		$generationData['academic_Year'] = date('Y');
		$generationData['date'] = date('Y-m-d');
		$generationData['semester'] = $semester;
		$generationData['status'] = 'Generated';
		$generationData['organisation_Id'] = $organisation_id;
			
		$action = new Insert('student_examination_eligibility_generation');
		$action->values($generationData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		//get attendance record and insert it
		$attendance_record = $this->getStudentAttendanceRecords($organisation_id);
		if($attendance_record != NULL){

			foreach($attendance_record as $student_id=>$value){
				foreach($value as $academic_module=>$attendance_percent){
					$attendaceData['academic_Year'] = date('Y');
					$attendaceData['semester'] = '';
					$attendaceData['academic_Modules_Allocation_Id'] = $academic_module;
					$attendaceData['reasons'] = 'Attendance Shortage - '.$attendance_percent;
					$attendaceData['status'] = 'Non-Eligible';
					$attendaceData['remarks'] = '';
					$attendaceData['student_Id'] = $student_id;
					
					$action2 = new Insert('student_examination_noneligibility');
					$action2->values($attendaceData);
					$sql2 = new Sql($this->dbAdapter);
					$stmt2 = $sql2->prepareStatementForSqlObject($action2);
					$result2 = $stmt2->execute();
				}
			}			
		}
		
		//insert financial data when finance module is complete
		
		return;
		
	}
	
	/*
	* Return Student Attendance Records
	*
	* Used by Eligible/Non Eligible Student List for Examinations
	*/
	
	public function getStudentAttendanceRecords($organisation_id)
	{
		$present_month = date('m');
		if((int)$present_month <= 6){
			$from_date = date('Y').'-01-01';
			$to_date = date('Y').'-06-30';
		} else {
			$from_date = date('Y').'-07-01';
			$to_date = date('Y').'-12-31';
		}
			
		//getting the student absent records
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_absentee_record'))
                    ->join(array('t2' => 'student_attendance_dates'), 
                            't1.student_attendance_dates_id = t2.id', array('attendance_date','academic_modules_allocation_id'))
                    ->where(array('t2.attendance_date >= ? ' => $from_date, 't2.attendance_date <= ? ' => $to_date));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$student_absent_records = array();
		$student_ids = array();
		$module_ids = array();
		foreach($resultSet as $set){
			$student_ids[$set['student_id']] = $set['student_id'];
			$module_ids[$set['academic_modules_allocation_id']] = $set['academic_modules_allocation_id'];
			$student_absent_records[$set['student_id']][$set['academic_modules_allocation_id']][$set['id']] = $set['id'];
		}
		
		//get the count for each lecture
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'student_attendance_dates'))
                    ->where(array('t1.attendance_date >= ? ' => $from_date, 't1.attendance_date <= ? ' => $to_date));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		$total_lectures = array();
		foreach($resultSet2 as $set){
			$count = 1;
			if(array_key_exists($set['academic_modules_allocation_id'],$total_lectures))
				$total_lectures[$set['academic_modules_allocation_id']] += $count;
			else
				$total_lectures[$set['academic_modules_allocation_id']] = $count;
		}
		
		//the array to be returned
		$student_attendance_records = array();
		foreach($student_ids as $id){
			foreach($module_ids as $mod_id){
				if(array_key_exists($mod_id, $student_absent_records[$id]))
					$attendance_percentage = number_format((float)($total_lectures[$mod_id]-count($student_absent_records[$id][$mod_id]))*100/$total_lectures[$mod_id],2,'.','');
					if($attendance_percentage < 90)
						$student_attendance_records[$id][$mod_id] = $attendance_percentage;
			}
		}
		
		return $student_attendance_records;
	}
	
	/*
	* Return Student Financial Records
	*
	* Used by Eligible/Non Eligible Student List for Examinations
	*/
	
	public function getStudentFinancialRecords()
	{
		
	}
	
	/*
	* Get the reasons for non-eligibility, given a student id
	*/
	
	public function getNonEligibilityReasons($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
                    ->columns(array('first_name', 'middle_name', 'last_name','student_id'))
					->join(array('t2' => 'student_examination_noneligibility'), 
                            't1.id = t2.student_id', array('id', 'reasons','academic_modules_allocation_id'))
					->join(array('t3' => 'academic_modules_allocation'), 
                            't2.academic_modules_allocation_id = t3.id', array('module_title'))
					->join(array('t4' => 'programmes'), 
                            't1.programmes_id = t4.id', array('programme_name'));
        $select->where(array('t2.student_id' =>$id));
		$select->where(array('t2.status' =>'Non-Eligible'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the details from the non-eligibility table
	*/
	
	public function getExaminationNonEligibilityDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
                    ->columns(array('first_name', 'middle_name', 'last_name','student_id'))
					->join(array('t2' => 'student_examination_noneligibility'), 
                            't1.id = t2.student_id', array('id', 'reasons','academic_modules_allocation_id'))
					->join(array('t3' => 'academic_modules_allocation'), 
                            't2.academic_modules_allocation_id = t3.id', array('module_title'))
					->join(array('t4' => 'programmes'), 
                            't1.programmes_id = t4.id', array('programme_name'))
                    ->where(array('t2.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Change the eligibility for the student examination 
	*/
	
	public function changeStudentEligibility($data)
	{
		//extract the data
		$examinationData['status'] = 'Eligible';
		$examinationData['remarks'] = $data['examinations']['remarks'];
		
		$action = new Update('student_examination_noneligibility');
		$action->set($examinationData);
		$action->where(array('id = ?' => $data['examinations']['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$data['examinations']['id']=$newId;
			}
			return $data;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Return the Semester given year
	*
	* Used by functions such as generate exam code, generate secret code and getting eligible/non-eligible students
	*/
	
	public function getSemesterNumber($year, $programmes_id)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		$semester_type = $this->getSemester($organisation_id);
		
		if($semester_type == 'odd' || $semester_type == 'Odd'){
			return $semester = ($year*2)-1;
		} else {
			return $semester = $year*2;
		}
	}
	
	/*
	* Return an id 
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $ajaxName, $conditional_id)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'academic_modules_allocation'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'))
				->join(array('t2' => 'academic_modules'), 
						't1.academic_modules_id = t2.id', array('module_title'));
			//$select->where->like('t2.module_title','%'.$ajaxName);
			$select->where('t2.id = ' .$ajaxName);
			$select->where('t2.programmes_id = ' .$conditional_id);
		} else if($tableName == 'academic_modules'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.module_title','%'.$ajaxName);
			$select->where('t1.programmes_id = ' .$conditional_id);
		} else if($tableName == 'academic_assessment'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.assessment','%'.$ajaxName.'%');
			$select->where('t1.assessment_component_id = ' .$conditional_id);
		} else {
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.assessment','%'.$ajaxName.'%');
			$select->where('t1.academic_modules_allocation_id = ' .$conditional_id);
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
	
	/*
	* Return an id 
	* NEW AJAX GET DATA function as the previous function has too many dependants
	*/
	
	public function getAjaxModuleId($tableName, $ajaxName, $conditional_id)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => $tableName))
				->columns(array('id'))
				->join(array('t2' => 'academic_modules'), 
						't1.academic_modules_id = t2.id', array('module_title'));
		$select->where->like('t2.module_title','%'.$ajaxName);
		$select->where('t2.programmes_id = ' .$conditional_id);
		$select->where(array('t1.academic_year' => date('Y')));
		
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
	
	/*
	* Create the Year List such as First Year, Second Year etc
	*/
	
	public function createYearList($organisation_id)
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
		$selectData = array();
		for($i=1; $i<=$max_years; $i++){
			$selectData[$i] = $i ." Year ";
		}
        return $selectData;
		
	}
	
	/*
	* To upload marks once moderation is done
	*/
	
	public function consolidateMarks($data)
	{
		$semester_dates = $this->getSemesterDates($data['organisation_id']);
		$semester = $this->getSemester($data['organisation_id']);
		$academic_year = $this->getAcademicYear($semester);
		
		
		$modules = $this->getModuleAllocationByProgramme($data['programmes_id'], $data['organisation_id'], $data['year']);
										
		foreach($modules as $module_allocation_id => $module_code){
			$student_list = $this->getStudentListForMarkEntry($programme_id, $academic_year, $programme_year);
			
			//get the backyear students and remove students who have cleared from student list
			$backyear_students_in_module = $this->getBackyearStudentForModule($module_allocation_id, $academic_year, $programme_id);
			$backyear_students_list = $this->getBackyearStudentList($module_allocation_id, $academic_year);
			
			//get backpaper students
			$backpaper_students_in_module = $this->getBackpaperStudentsForModule($module_allocation_id, $academic_year, $programme_id);
			
			//remove all backyear students from student list
			foreach($backyear_students_list as $key => $value){
				if(array_key_exists($key, $student_list)){
					unset($student_list[$key]);
				}
			}
			
			//calculate marks for students (excluding back year and backpaper)
			foreach($student_list as $id => $student_id){
				$this->calculateStudentConsolidatedMarks($module_allocation_id, $id, $student_id, $type= 'normal');
			}
			
			//get backpaper students and calculate marks
			$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
			foreach($backpaper_students as $backpaper_id => $backpaper_student_id){
				$this->calculateStudentConsolidatedMarks($module_allocation_id, $backpaper_id, $backpaper_student_id, $type = 'backpaper');
			}
			
			//calculate the backyear students marks
			foreach($backyear_students_in_module as $backyear_id => $backyear_student_id){
				$this->calculateStudentConsolidatedMarks($module_allocation_id, $backyear_id, $backyear_student_id, $type = 'backyear');
			}
			
		}
		
		//need to update moderatation table (to keep track of which programmes have been moderated)
		
		$this->updateExamModerationTable($data);
		
		return;
	}
	
	/*
	* Generate the list of backpaper students and backyear students
	*/
	
	public function generateBackpaperStudentList($data)
	{
		$semester_dates = $this->getSemesterDates($data['organisation_id']);
		$semester = $this->getSemester($data['organisation_id']);
		$academic_year = $this->getAcademicYear($semester);
		
		$modules = $this->getModuleAllocationByProgramme($data['programmes_id'], $data['organisation_id'], $data['year']);
										
		foreach($modules as $module_allocation_id => $module_code){
			//search the students with backpaper
			$this->searchBackpaperStudents($module_allocation_id);
				
			//need to get the year back students and update table students_yearback	
			$this->searchBackyearStudents($organisation_id);
			
			//need to update the backpaper registration table
			$this->updateBackpaperRegistration($academic_year);
			
			//update the status of the consolidate marks 
			//marks all backpaper students as fail
			$this->changeStatusOfBackpaper($academic_year);
		}
		
		//need to update backpaper table (to keep track of which programmes have been already done)
		$this->updateBackPaperListGenerationTable($data);
		
		return;
		
	}
	
	/*
	* Get the dates for the start and end of the semester
	*/
	
	private function getSemesterDates($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('from_date', 'to_date', 'academic_year'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('t1.from_date <= ? ' => date('Y-m-d')));
		$select->where(array('t1.to_date >= ? ' => date('Y-m-d')));
		$select->where('t2.organisation_id = ' .$organisation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = array();
		
		foreach($resultSet as $set){
			$semester['from_date'] = $set['from_date'];
			$semester['to_date'] = $set['to_date'];	
			$semester['academic_year'] = $set['academic_year'];			
		}
		return $semester;
	}
	
	/*
	* Calculate the Consolidated Marks
	* $id is the id of the student table
	* $student_id is the "student identification number"
	*/
	
	private function calculateStudentConsolidatedMarks($academic_module_allocation_id, $id, $student_id, $type)
	{
		$assessment_components = $this->getAssessmentComponents($academic_module_allocation_id);
		//$result components holds various variables that are needed for Consolidated Marksheet such as module code etc. 
		//Refer to database table for complete details
		$ca_result_components = $this->getConsolidateMarkComponents($academic_module_allocation_id, $module_code = NULL, 'Continuous Assessment');
		$se_result_components = $this->getConsolidateMarkComponents($academic_module_allocation_id, $module_code = NULL, 'Semester Exams');
		$marks = NULL;
		$assessment_type = NULL;
			
		if($type == 'normal'){
			foreach($assessment_components as $key=>$value){
				if($value['assessment'] == 'Continuous Assessment'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $ca_result_components['academic_year']);
					$assessment_type = 'CA';
					if(array_key_exists('weightage', $ca_result_components)){
						$this->enterConsolidatedMarks($marks, $assessment_type, $ca_result_components, $student_id);
					}
				} 
				if($value['assessment'] == 'Semester Exams'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $se_result_components['academic_year']);
					$assessment_type = 'SE';
					if(array_key_exists('weightage', $se_result_components)){
						$this->enterConsolidatedMarks($marks, $assessment_type, $se_result_components, $student_id);
					}
				}
			}
		} else if($type == 'backyear'){
			foreach($assessment_components as $key=>$value){
				if($value['assessment'] == 'Continuous Assessment'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $ca_result_components['academic_year']);
					$assessment_type = 'CA';
					if(array_key_exists('weightage', $ca_result_components)){
						$this->updateConsolidatedMarks($marks, $assessment_type, $ca_result_components, $student_id);
					}
				} 
				if($value['assessment'] == 'Semester Exams'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $se_result_components['academic_year']);
					$assessment_type = 'SE';
					if(array_key_exists('weightage', $se_result_components)){
						$this->updateConsolidatedMarks($marks, $assessment_type, $se_result_components, $student_id);
					}
				}
			}
		} else if($type == 'backpaper'){
			//get whether CA or SE
			$backpaper_in = $this->getBackpaperIn($ca_result_components['module_code'], $id);
			foreach($assessment_components as $key=>$value){
				if($value['assessment'] == 'Continuous Assessment' && $backpaper_in == 'CA'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $ca_result_components['academic_year']);
					$assessment_type = 'CA';
					if(array_key_exists('weightage', $ca_result_components)){
						$this->updateConsolidatedMarks($marks, $assessment_type, $ca_result_components, $student_id);
					}
				} 
				if($value['assessment'] == 'Semester Exams' && $backpaper_in == 'SE'){
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $id, $se_result_components['academic_year']);
					$assessment_type = 'SE';
					if(array_key_exists('weightage', $se_result_components)){
						$this->updateConsolidatedMarks($marks, $assessment_type, $se_result_components, $student_id);
					}
				}
			}
		}
				
		return;
	}
	
	/*
	* Calculate the Continuous Assessment and Consolidate it
	*/
	
	private function calculateAssessmentMarks($assessment_component_id, $student_id, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component'))
					->columns(array('assessment_year','weightage'))
				->join(array('t2' => 'academic_assessment'), 
						't1.id = t2.assessment_component_id', array('assessment_marks', 'assessment_weightage'))
				->join(array('t3' => 'assessment_marks'), 
						't2.id = t3.academic_assessment_id', array('marks'));
		$select->where('t1.id = ' .$assessment_component_id);
		$select->where(array('t1.assessment_year' => $academic_year));
		$select->where('t3.student_id = ' .$student_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$marks = 0;
		$weightage = 0;
		
		foreach($resultSet as $set){
			$marks += $set['marks']/$set['assessment_weightage'];	
			$weightage = $set['weightage'];
		}
		
		return $marks*$weightage;
	}
	
	/*
	* Enter the Consolidate Marks into database
	*/
	
	public function enterConsolidatedMarks($marks, $assessment_type, $se_result_components, $student_id)
	{
		$markData['assessment_Type'] = $assessment_type;
		$markData['marks'] = $marks;
		$markData['module_Code'] = $se_result_components['module_code'];
		$markData['credit'] = $se_result_components['module_credit'];
		$markData['weightage'] = $se_result_components['weightage'];
		$markData['programmes_Id'] = $se_result_components['programmes_id'];
		$markData['academic_Year'] = $se_result_components['academic_year'];
		$markData['pass_Year'] = date('Y');
		$markData['student_Id'] = $student_id;
		$markData['result_Status'] = 'Moderated';
		//default status is "Pass". Updating backstudents will change status
		$markData['status'] = 'Pass';
		
		$action = new Insert('student_consolidated_marks');
		$action->values($markData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Update the Consolidate Marks
	*/
	
	public function updateConsolidatedMarks($marks, $assessment_type, $se_result_components, $student_id)
	{
		$markData['marks'] = $marks;
		//$markData['academic_Year'] = $se_result_components['academic_year'];
		$markData['pass_Year'] = date('Y');
		
		$action = new Update('student_consolidated_marks');
		$action->set($markData);
		$action->where(array('student_id' => $student_id));
		$action->where(array('module_code' => $se_result_components['module_code']));
		$action->where(array('programmes_id' => $se_result_components['programmes_id']));
		$action->where(array('assessment_Type' => $assessment_type));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Search the Students with Backpaper
	*/
	
	private function searchBackpaperStudents($module_allocation_id)
	{
		$backpaper_students = array();
		$temp1 = array();
		$temp2 = array();
		$temp3 = array();
		
		//Refer to database table for complete details
		$ca_assessment_components = $this->getConsolidateMarkComponents($module_allocation_id, $module_code =NULL, 'Continuous Assessment');
		$se_assessment_components = $this->getConsolidateMarkComponents($module_allocation_id, $module_code =NULL, 'Semester Exams');
		
		//generic assessment_components array
		$assessment_components['academic_year'] = NULL;	
		$assessment_components['module_code'] = NULL;
		$assessment_components['module_credit'] = NULL;
		$assessment_components['programmes_id'] = NULL;
		$assessment_components['weightage'] = NULL;
			
		if(!empty($ca_assessment_components)){
			$assessment_components['academic_year'] = $ca_assessment_components['academic_year'];	
			$assessment_components['module_code'] = $ca_assessment_components['module_code'];
			$assessment_components['module_credit'] = $ca_assessment_components['module_credit'];
			$assessment_components['programmes_id'] = $ca_assessment_components['programmes_id'];
			$assessment_components['weightage'] = $ca_assessment_components['weightage'];
			
		} 
		
		if(!empty($se_assessment_components)){
			$assessment_components['academic_year'] = $se_assessment_components['academic_year'];	
			$assessment_components['module_code'] = $se_assessment_components['module_code'];
			$assessment_components['module_credit'] = $se_assessment_components['module_credit'];
			$assessment_components['programmes_id'] = $se_assessment_components['programmes_id'];
			$assessment_components['weightage'] = $se_assessment_components['weightage'];
		}
		
		//calculate whether CA is 40%
		if(array_key_exists('weightage', $ca_assessment_components)){
			$temp1 = $this->calculateStudentBackpapers($ca_assessment_components, 'CA');
		}
		
		//Calculate whether SE is 40%
		if(array_key_exists('weightage', $se_assessment_components)){
			$temp2 = $this->calculateStudentBackpapers($se_assessment_components, 'SE');
		}
		
		//Calculate whether CA and SE combined is 50%
		$temp3 = $this->calculateStudentBackpapers($assessment_components, 'Combined');
		
		$backpaper_students_temp = array_merge($temp1, $temp2, $temp3);
		
		//remove all duplicate student ids
		foreach($backpaper_students_temp as $key => $value){
			$backpaper_students[$value] = $value;
		}
		
		if(!empty($backpaper_students)){
			$this->updateBackpaperModules($backpaper_students, $ca_assessment_components);
		}
		
		return;
	}
	
	/*
	* Update Exam Moderation Table
	* Keep track of which programmes have been moderated
	*/
	
	private function updateExamModerationTable($data)
	{
		$semester = $this->getSemester($data['organisation_id']);
		$academic_year = $this->getAcademicYear($semester);
		
		$moderationData['programmes_Id'] = $data['programmes_id'];
		$moderationData['year'] = $data['year'];
		$moderationData['academic_Year'] = $academic_year;
		$moderationData['status'] = 'Generated';
		
		$action = new Insert('exam_moderation');
		$action->values($moderationData);

		$sql_action = new Sql($this->dbAdapter);
		$stmt_action = $sql_action->prepareStatementForSqlObject($action);
		$result_action = $stmt_action->execute();
		
		return;
	}
	
	/*
	* Update Back Paper List Generation Table
	* Keep track of the list of programmes that have been generated
	*/
	
	private function updateBackPaperListGenerationTable($data)
	{
		$semester = $this->getSemester($data['organisation_id']);
		$academic_year = $this->getAcademicYear($semester);
		
		$backpaperData['programmes_Id'] = $data['programmes_id'];
		$backpaperData['year'] = $data['year'];
		$backpaperData['academic_Year'] = $academic_year;
		$backpaperData['status'] = 'Generated';
		
		$action = new Insert('backpaper_list_generation');
		$action->values($backpaperData);

		$sql_action = new Sql($this->dbAdapter);
		$stmt_action = $sql_action->prepareStatementForSqlObject($action);
		$result_action = $stmt_action->execute();
		
		return;
	}
	
	
	
	private function calculateStudentBackpapers($assessment_components, $assessment_type)
	{
		//fields of the assessment components
		/*
		$assessment_components['academic_year'];	
		$assessment_components['module_code'];
		$assessment_components['module_credit'];
		$assessment_components['programmes_id'];
		$assessment_components['weightage']; */
		
		$students = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//predicate used for LessThan arguments
		$predicate = new  \Zend\Db\Sql\Where();
			
		if($assessment_type != 'Combined'){		
			//a minimum of 50% is required
			$minimum_mark = 50;	
			
			$select->from(array('t1' => 'student_consolidated_marks'))
						->columns(array('student_id','marks'=>new \Zend\Db\Sql\Expression('SUM(marks)')))
						->group('student_id');
			$select->where(array('t1.academic_year' => $assessment_components['academic_year']));
			$select->where(array('t1.pass_year' => date('Y')));
			$select->where(array('t1.module_code' => $assessment_components['module_code']));
			$select->where(array('t1.programmes_id' => $assessment_components['programmes_id']));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				if($set['marks']<50){
					$students[$set['student_id']] = $set['student_id'];	
				}
			}

			
		} else {
			//a minimum of 40% is required
			$minimum_mark = $assessment_components['weightage']*(0.4);
			
			$select->from(array('t1' => 'student_consolidated_marks'))
						->columns(array('student_id'));
			$select->where($predicate->lessThan("marks", $minimum_mark));
			$select->where(array('t1.academic_year' => $assessment_components['academic_year']));
			$select->where(array('t1.pass_year' => date('Y')));
			$select->where(array('t1.module_code' => $assessment_components['module_code']));
			$select->where(array('t1.programmes_id' => $assessment_components['programmes_id']));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$students[$set['student_id']] = $set['student_id'];	
			}
		}
				
		return $students;
	}
	
	/*
	* Updates the students with backpaper (less than 40% in either CA/SE)
	* Gets a list of students with backpaper
	*/
	
	private function updateBackpaperModules($backpaper_students, $assessment_components)
	{
		//fields of the assessment components
		/*
		$assessment_components['academic_year'];	
		$assessment_components['module_code'];
		$assessment_components['module_credit'];
		$assessment_components['programmes_id'];
		$assessment_components['weightage']; */
		
		$backpaper_student_details = array();
		$backpaperData = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_consolidated_marks'));
		$select->where(array('t1.academic_year' => $assessment_components['academic_year']));
		$select->where(array('t1.pass_year' => date('Y')));
		$select->where(array('t1.module_code' => $assessment_components['module_code']));
		$select->where(array('t1.programmes_id' => $assessment_components['programmes_id']));
		$select->where(array('t1.student_id' => $backpaper_students));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			if($set['assessment_type'] == 'CA'){
				$backpaper_student_details[$set['student_id']][$set['assessment_type']] = $set['marks'];
			} else if($set['assessment_type'] == 'SE'){
				$backpaper_student_details[$set['student_id']][$set['assessment_type']] = $set['marks'];
			}
		}
		
		//Once we retrieve the backpaper details, we have to insert in table 'student_backlog_papers'
		$ca_assessment_components = $this->getConsolidateMarkComponents(NULL, $assessment_components['module_code'], 'Continuous Assessment');
		$se_assessment_components = $this->getConsolidateMarkComponents(NULL, $assessment_components['module_code'], 'Semester Exams');
		foreach($backpaper_student_details as $key=>$value){
				if(array_key_exists('CA', $backpaper_student_details[$key])){
					$backpaperData['previous_Ca_Marks'] = $value['CA'];
				} else {
					$backpaperData['previous_Ca_Marks'] = 'NA';
				}
				
				if(array_key_exists('SE', $backpaper_student_details[$key])){
					$backpaperData['previous_Se_Marks'] = $value['SE'];
				} else {
					$backpaperData['previous_Se_Marks'] = 'NA';
				}
				$backpaperData['module_Code'] = $assessment_components['module_code'];
				$backpaperData['programmes_Id'] = $assessment_components['programmes_id'];
				$backpaperData['backlog_Semester'] = $this->getModuleSemesterTaught($assessment_components['module_code'], $assessment_components['programmes_id']);
				$backpaperData['backlog_Academic_Year'] = $assessment_components['academic_year'];
				$backpaperData['backlog_Date'] = date('Y-m-d');
				$backpaperData['attempt_No'] = 1;
				$backpaperData['backlog_Status'] = 'Not Cleared';
				$backpaperData['student_Id'] = $this->getStudentId($key);
				
				$action = new Insert('student_repeat_modules');
				$action->values($backpaperData);
		
				$sql_action = new Sql($this->dbAdapter);
				$stmt_action = $sql_action->prepareStatementForSqlObject($action);
				$result_action = $stmt_action->execute();
		}
		return;
	}
	
	/*
	* Get the semester the particulare module was taught
	*/
	
	private function getModuleSemesterTaught($module_code, $programmes_id)
	{
		$semester = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('semester', 'year'))
					->join(array('t2' => 'academic_modules'), 
							't1.academic_modules_id = t2.id', array('programmes_id'));
        $select->where->like('t2.module_code', $module_code);
		$select->where(array('t2.programmes_id' => $programmes_id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$semester = $set['semester'];
		}
		
		return $semester;
	}
	
	/*
	* Search for students who got back year
	*/
	
	private function searchBackyearStudents($organisation_id)
	{
		$students = array();
		$student_semester = array();
		$backpaper_count = array();
		
		$semester_dates = $this->getSemesterDates($organisation_id);
		$semester = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'student_repeat_modules'))
					->columns(array('student_id', 'backlog_semester' ));
		$select->where(array('t1.backlog_academic_year' => $academic_year));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$students[] = $set['student_id'];
			$student_semester[$set['student_id']] = $set['backlog_semester'];
		}
		
		$backpaper_count = array_count_values($students);
		foreach($backpaper_count as $key=>$value){
			if($value < 3){
				unset($student_semester[$key]);
			}
		}
		//the student semester stores both student id and semester value
		$this->updateBackyearStudentsTable($student_semester, $academic_year);
		
		return;
	}
	
	/*
	* Update the Backyear Student Table
	*/
	
	private function updateBackyearStudentsTable($students, $academic_year)
	{
		foreach($students as $key=>$value){
			$backyearData['backyear_Year'] = ceil($value/2);
			$backyearData['backyear_Semester'] = $value;
			$backyearData['backyear_Academic_Year'] = $academic_year;
			$backyearData['backyear_Status'] = 'Not Cleared';
			$backyearData['student_Id'] = $key;
			
			$action = new Insert('student_backyears');
			$action->values($backyearData);
	
			$sql_action = new Sql($this->dbAdapter);
			$stmt_action = $sql_action->prepareStatementForSqlObject($action);
			$result_action = $stmt_action->execute();
		}
		
		return;
	}
	
	/*
	* Get the list of the students of Mark Entry
	*/
	
	private function getStudentListForMarkEntry($programme_id, $academic_year, $programme_year)
	{
		$semester_no = $this->getSemesterNumber($programme_year, $programme_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
					->columns(array('id','student_id'))
				->join(array('t2' => 'student_semester_registration'), 
						't1.id = t2.student_id', array('academic_year'))
				->join(array('t3' => 'student_semester'), 
						't2.semester_id = t3.id', array('programme_year_id'))
				->join(array('t4' => 'programme_year'), 
						't3.programme_year_id = t4.id', array('year'));
		$select->where('t1.programmes_id = ' .$programme_id);
		$select->where(array('t2.academic_year' => $academic_year));
		$select->where(array('t4.id' => $programme_year));
		$select->where(array('t2.semester_id' => $semester_no));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$students = array();
		
		foreach($resultSet as $set){
			$students[$set['id']] = $set['student_id'];	
		}
		return $students;
	}
	
	/*
	* Get the list of back year students
	*/
	
	private function getBackyearStudentList($module_allocation_id, $academic_year)
	{
		$semester = $this->getSemesterForModule($module_allocation_id);
		
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_repeat_modules'))
					->join(array('t4' => 'student_backyears'), 
                            't1.student_id = t4.student_id', array('backyear_semester'))
					->join(array('t5' => 'student'), 
                            't4.student_id = t5.id', array('id', 'first_name', 'middle_name', 'last_name','student_id'));
		$select->where(array('t4.backyear_semester' => $semester));
		$select->where(array('t4.backyear_academic_year' => $backyear_academic_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['id']] = $set['student_id'];
		}
		
		return $student_list;
	}
	
	/*
	* Get the list of students with backpapers
	*/
	
	private function getBackpaperStudentsForModule($academic_modules_allocation_id, $academic_year, $programmesId)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		$semester = $this->getSemesterForModule($academic_modules_allocation_id);
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_title'))
					->join(array('t2' => 'academic_modules'), 
                            't2.id = t1.academic_modules_id', array('module_code'))
					->join(array('t3' => 'student_backpaper_registration'),
							't3.module_code = t2.module_code', array('backpaper_semester'))
					->join(array('t5' => 'student'), 
                            't3.student_id = t5.id', array('id', 'first_name', 'middle_name', 'last_name','student_id'));
		$select->where->like('t2.module_code', $module_code);
		$select->where(array('t5.programmes_id' => $programmesId));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t3.backpaper_academic_year' => $backyear_academic_year));
		$select->where->like('t3.registration_status', "Registered");
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['id']] = $set['student_id'];
		}
		
		return $student_list;
	}
	
	/*
	* Get the list of backyear students for a particular module
	*/
	
	private function getBackyearStudentForModule($academic_modules_allocation_id, $academic_year, $programmesId)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		$semester = $this->getSemesterForModule($academic_modules_allocation_id);
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_title'))
					->join(array('t2' => 'academic_modules'), 
                            't2.id = t1.academic_modules_id', array('module_code'))
					->join(array('t3' => 'student_repeat_modules'),
							't3.module_code = t2.module_code', array('backlog_semester'))
					->join(array('t4' => 'student_backyears'), 
                            't3.student_id = t4.student_id', array('backyear_semester'))
					->join(array('t5' => 'student'), 
                            't4.student_id = t5.id', array('id', 'first_name', 'middle_name', 'last_name','student_id'));
		$select->where->like('t2.module_code', $module_code);
		$select->where(array('t2.programmes_id' => $programmesId));
		$select->where(array('t3.backlog_semester' => $semester));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t4.backyear_academic_year' => $backyear_academic_year));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['id']] = $set['student_id'];
		}
		return $student_list;
	}
	
	/*
	* Get Backpaper Type, i.e. CA or SE, for backpaper modules
	*/
	
	private function getBackpaperIn($module_code, $student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_backpaper_registration'))
				->columns(array('backpaper_in'));
		$select->where->like('t1.module_code', $module_code);
		$select->where(array('t1.student_id' => $student_id));
		$select->where->like('t1.registration_status', "Registered");
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$backpaper_in = NULL;
		
		foreach($resultSet as $set){
			$backpaper_in = $set['backpaper_in'];
		}
		
		return $backpaper_in;
	}
	
	/*
	* Register all the students with backpaper
	* Default status is unregistered until students register
	*/
	
	private function updateBackpaperRegistration($academic_year)
	{
		
	}
	
	/*
	* Change the stauts of consolidated marks for students with backpaper
	*/
	
	private function changeStatusOfBackpaper($academic_year)
	{
		
	}
	
	/*
	* Get the semester for module allocated
	*/
	
	private function getSemesterForModule($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('semester'));
		$select->where(array('id' => $academic_modules_allocation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = NULL;
		
		foreach($resultSet as $set){
			$semester= $set['semester'];
		}
		return $semester;
	}
	
	
	/*
	* Get Module Code given Module Allocation ID
	*/
	
	private function getAllocatedAcademicModuleCode($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t3' => 'academic_modules_allocation'))
						->columns(array('id'))
				->join(array('t4' => 'academic_modules'), 
						't3.academic_modules_id = t4.id', array('module_code'));
		$select->where('t3.id = ' .$academic_modules_allocation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$module_code = NULL;
		
		foreach($resultSet as $set){
			$module_code = $set['module_code'];
		}
		return $module_code;
	}
	
	/*
	* Get the previous academic year
	*/
	
	private function getPreviousAcademicYear($academic_year)
	{
		$years = explode("-", $academic_year);
		return (($years[0]-1)."-".($years[0]));
	}
	
	/*
	* Get the list of Modules by Each Programme
	*/
	
	public function getModuleAllocationByProgramme($programme_id, $organisation_id, $programme_year)
	{
		$semester_no = $this->getSemesterNumber($programme_year, $programme_id);
		$semester = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('id', 'module_title'))
				->join(array('t2' => 'academic_modules'), 
					't2.id = t1.academic_modules_id', array('module_code'));
		$select->where(array('t1.academic_year = ? ' => $academic_year));
		$select->where('t1.programmes_id = ' .$programme_id);
		$select->where('t1.year = ' .$programme_year);
		$select->where('t1.semester = ' .$semester_no);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$modules = array();
		
		foreach($resultSet as $set){
			$modules[$set['id']] = $set['module_code'];		
		}
		return $modules;
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
			if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
			}
			else if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
		}
		return $semester;
	}
        
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($semester_type)
	{
		$academic_year = NULL;
		/*
		//Old Function - Kept for reference should anything be wrong
		if($semester_type == 'odd'){
			$academic_year = date('Y');
		} else {
			$academic_year = date('Y')-1;
		}
		*/
		if($semester_type == 'Autumn'){
			$academic_year = date('Y').'-'.(date('Y')+1);
		} else {
			$academic_year = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	/*
	* Get the programme duration
	*/
	
	private function getProgrammeDuration($programme_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array(new Expression ('MAX(programme_duration) as max_duration')));
		$select->where('t1.id = ' .$programme_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$duration = NULL;
		foreach ($resultSet as $res) {
			$tmp_number = $res['max_duration'];
						preg_match_all('!\d+!', $tmp_number, $matches);
						$duration = implode(' ', $matches[0]);
		}
		return $duration;
	}
	
	/*
	* Get the assessment components
	*/
	
	private function getAssessmentComponents($academic_module_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component'))
					->columns(array('id','assessment', 'weightage'));
		$select->where('t1.academic_modules_allocation_id = ' .$academic_module_allocation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_components = array();
		$i=0;
		foreach($resultSet as $set){
			$assessment_components[$i]['assessment_component_id'] = $set['id'];	
			$assessment_components[$i]['assessment'] = $set['assessment'];
			$assessment_components[$i]['weightage'] = $set['weightage'];
			$i++;
		}
		return $assessment_components;
	}
	
	/*
	* Get the various Components need for Consolidated Mark Sheet like module code, credit, weghtage etc.
	*/
	
	private function getConsolidateMarkComponents($academic_modules_allocation_id, $module_code, $assessment_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
							->columns(array('academic_year'))
					->join(array('t2'=>'academic_modules'),
                            't1.academic_modules_id = t2.id', array('module_code', 'module_credit','programmes_id'))
					->join(array('t3'=>'academic_modules_assessment'),
                            't2.id = t3.academic_modules_id', array('weightage'));
		if($academic_modules_allocation_id){
			$select->where('t1.id = ' .$academic_modules_allocation_id);
		}
		if($module_code){
			$select->where->like('t2.module_code',$module_code);
		}
		$select->where->like('t3.assessment',$assessment_type);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_components = array();
		foreach($resultSet as $set){
			$assessment_components['academic_year'] = $set['academic_year'];	
			$assessment_components['module_code'] = $set['module_code'];
			$assessment_components['module_credit'] = $set['module_credit'];
			$assessment_components['programmes_id'] = $set['programmes_id'];
			$assessment_components['weightage'] = $set['weightage'];
		}
		return $assessment_components;
	}
	
	/*
	* Get the 'id' of the student table given a student_identity number
	*/
	
	private function getStudentId($student_id)
	{
		$id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'));
		$select->where(array('student_id' =>$student_id));
		$select->columns(array('id'));
			
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		
		return $id;
	}
		
	/**
	* @return array/Examinations()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	
	public function listSelectData($tableName, $columnName, $condition)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'examination_timetable'){
			$select->from(array('t1' => $tableName))
                    ->join(array('t2'=>'programmes'),
                            't1.programmes_id = t2.id', array('programme_name'))
					->join(array('t3'=>'academic_modules'),
                            't1.academic_modules_id = t3.id', array('module_title'))
                    ->where(array('t1.organisation_id' =>$condition));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$selectData = array();
			foreach($resultSet as $set){
				$selectData[$set['id']] = $set['module_title'].' (' .$set['programme_name'].')';
			}
			return $selectData;
		}
		else if($tableName == 'employee_details'){
			//here we execute the mysql statement and return it
			// as first name, middle name, last name is needed
			//need to also join with employee title such as professor etc.
			// (this will be done once all employees are assigned their titles)
			$select->from(array('t1' => 'employee_details')) ;
			$select->columns(array('id','first_name', 'middle_name','last_name', 'emp_id'))
                    ->where('t1.organisation_id = ' .$condition);
					
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$selectData = array();
			foreach($resultSet as $set){
				$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' ' .$set['last_name']. ' (' .$set['emp_id'].')';
			}
			return $selectData;
		} else {
			$select->from(array('t1' => $tableName))
                    ->columns(array('id', $columnName))
					->where('t1.organisation_id = ' .$condition);
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
        
}