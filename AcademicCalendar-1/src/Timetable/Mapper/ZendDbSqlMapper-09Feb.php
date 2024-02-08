<?php

namespace Timetable\Mapper;

use Timetable\Model\Timetable;
use Timetable\Model\UploadTimetable;
use Timetable\Model\TimetableTiming;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements TimetableMapperInterface
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
	 * @var \Timetable\Model\TimetableInterface
	*/
	protected $timetablePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Timetable $timetablePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->timetablePrototype = $timetablePrototype;
	}
	
	
	/**
	* @return array/Timetable()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)) 
					->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                    ->join(array('t3' => 'academic_modules'), 
                            't2.academic_modules_id = t3.id', array('module_code'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Timetable()
	*/
	public function getTimetable($programme, $section, $year, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$semester_type = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester_type);
		
		if($semester_type == 'Spring'){
			$semester = $year*2;
		} else {
			$semester = $year*2-1;
		}
		
		$select->from(array('t1' => 'academic_timetable')) 
                        ->join(array('t4' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t4.id', array('academic_modules_id'))
						->join(array('t2' => 'academic_modules'), 
                            't4.academic_modules_id = t2.id', array('module_code'))
                        ->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'));
		$select->where('t3.organisation_id = ' .$organisation_id);
		if($programme != NULL){
			$select->where('t1.programmes_id = ' .$programme);
		}
        if($section != NULL){
			$select->where->like('t1.group',$section);
		}
		
		$select->where->like('t1.academic_year','%'.$academic_year.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function checkAllocatedModuleTutor($employee_details_id)
	{	
		$allocated_module = array();		
		//get the organisation id
		$organisationID = $this->getOrganisationId($employee_details_id);
		foreach($organisationID as $organisation){
			$organisation_id = $organisation['organisation_id'];
		}
		
		$semester_type = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester_type);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		
		$select->from(array('t1' => 'academic_module_tutors')) 
                            ->columns(array('academic_modules_allocation_id', 'section'))
							->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_year'));
		$select->where(array('t2.academic_year' => $academic_year));
		$select->where->like('t1.module_tutor','%'.$employee_details_id.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$allocated_module[] = $set;
		}

		 return $allocated_module; 
	}
	
	/*
	* Get timetable for module tutor
	*/
	
	public function getTutorTimetable($employee_details_id)
	{
		$sections = array();
		$academic_modules_allocation_id = array();
		
		//get the organisation id
		$organisationID = $this->getOrganisationId($employee_details_id);
		foreach($organisationID as $organisation){
			$organisation_id = $organisation['organisation_id'];
		}
		
		$semester_type = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester_type);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_tutors')) 
                            ->columns(array('academic_modules_allocation_id', 'section'))
							->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_year'));
		$select->where(array('t2.academic_year' => $academic_year));
		$select->where(array('t2.academic_session' => $semester_type));
		$select->where->like('t1.module_tutor','%'.$employee_details_id.'%');
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$academic_modules_allocation_id[] = $set['academic_modules_allocation_id'];
			$sections[] = $set['section'];
		}
		
		$sql1 = new Sql($this->dbAdapter);
		$select1 = $sql1->select();

		$select1->from(array('t1' => 'academic_timetable'))
							->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't2.academic_modules_id = t3.id', array('module_code'))
							->join(array('t4' => 'programmes'), 
                                't3.programmes_id = t4.id', array('programme_name'))
							 ->join(array('t6' => 'student_section'), 
                                't1.group = t6.id', array('section'));
		$select1->where(array('t1.group' => $sections));
		$select1->where(array('t1.academic_year' => $academic_year));
		$select1->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));

		$stmt1 = $sql1->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->buffer();
		return $resultSet1->initialize($result1);
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


	public function getUserDetails($username, $tableName)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName = 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($tableName = 'student'){
			$select->from(array('t1' => $tableName));
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
        
	/**
	 * 
	 * @param type $TimetableInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveTimetable(Timetable $timetableObject)
	{
		$timetableData = $this->hydrator->extract($timetableObject);
		unset($timetableData['id']);
		
		$organisation_id = $this->getOrganisationIdByProgramme($timetableData['programmes_Id']);
		$semester_type = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester_type);
		
		//semester is not used
		$semester = -1;
		
		$timetableData['academic_Year'] = $academic_year;
		$timetableData['semester'] = $semester;
				
		//get the id of the module name
		//$timetableData['academic_Modules_Id'] = $this->getAjaxDataId($tableName='academic_modules', $columnName = $timetableData['academic_Modules_Id'], $programmes_id=$timetableData['programmes_Id']);
		
		if($timetableObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_timetable');
			$action->set($timetableData);
			$action->where(array('id = ?' => $timetableObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_timetable');
			$action->values($timetableData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $timetableObject->setId($newId);
			}
			return $timetableObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Timetable
	*/
	
	public function saveTimings(TimetableTiming $timetableObject)
	{
		$timetableData = $this->hydrator->extract($timetableObject);
		unset($timetableData['id']);
				
		if($timetableObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_timetable_timing');
			$action->set($timetableData);
			$action->where(array('id = ?' => $timetableObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_timetable_timing');
			$action->values($timetableData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $timetableObject->setId($newId);
			}
			return $timetableObject;
		}
		
		throw new \Exception("Database Error");
	}
        
	/*
	 * Upload timetable file for Academic Year
	 */
	
	public function saveTimetableFile(UploadTimetable $uploadModel, $organisation_id)
	{
		$uploadData = $this->hydrator->extract($uploadModel);
		unset($uploadData['id']);

		//need to get the file locations and store them in database
		$file_name = $uploadData['file_Name'];
		$uploadData['file_Name'] = $file_name['tmp_name'];
		
		$objPHPExcel = new \PHPExcel_Reader_Excel5();

		$document = $objPHPExcel->load($uploadData['file_Name']);

		// Get worksheet dimension
		$sheet = $document->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		
		//The size of the column is equal to the academic timings times days of week
		$academic_timings = $this->getTimingsList($organisation_id);
		$academic_timing_data = array();
		$timing_index = 1;
		foreach($academic_timings as $time){
			$academic_timing_data[$timing_index]['from_time'] = $time['from_time'];
			$academic_timing_data[$timing_index]['to_time'] = $time['to_time'];
			$timing_index++;
		}
		
		//days of week
		$days_week = array(
			'1' => 'Monday',
			'2' => 'Tuesday',
			'3' => 'Wednesday',
			'4' => 'Thursday',
			'5' => 'Friday'
		);
		$no_of_columns = count($academic_timings) * 5;

		// Loop through each of row of the worksheet in turn
		$timetable_temp = array();
		for($row = 1; $row <= $highestRow; $row++){
				$highestColumn = $sheet->getHighestColumn();
				for($col = 0; $col <= $no_of_columns-1; $col++){
					$cell = $sheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					$timetable_temp[$row][$col] = $val;
				}
		}

		//clean up array (i.e. remove all empty cells)
		$timetable = array();
		foreach($timetable_temp as $key => $value){
			foreach($value as $key2 => $value2){
				if($value2 != NULL)
					$timetable[$key][$key2]=$value2;
			}
		}
		
		foreach($timetable as $key => $value){
			foreach($value as $key2=>$value2){
				//$key2 is the data for the day of the week and the timetable timings
				//day of the week is given by the quotient after division
				$timetable_time_key = (int)($key2%(count($academic_timings)))+1;
				$day_of_week_key = (int) $key2/count($academic_timings)+1;
				
				$timetable_values = explode("/", $value2);
				
				//Load the values into $timetableData
				$timetableData['day'] = $days_week[$day_of_week_key];
				$timetableData['from_Time'] = $academic_timing_data[$timetable_time_key]['from_time'];
				$timetableData['to_Time'] = $academic_timing_data[$timetable_time_key]['to_time'];
				$programme_id = $this->getProgrammeId($timetable_values[1], $organisation_id);
				$academic_modules_id = $this->getAcademicModuleId($timetable_values[0], $programme_id);
				$timetableData['semester'] = $this->getModuleSemester($academic_modules_id);
				$timetableData['group'] =$timetable_values[2];
				$timetableData['classroom'] =$timetable_values[3];
				$timetableData['academic_Year'] = date('Y');
				$timetableData['programmes_Id'] = $programme_id;
				$timetableData['academic_Modules_Id'] = $academic_modules_id;
				
				$action = new Insert('academic_timetable');
				$action->values($timetableData);
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
		
	}
	
	/*
	* Get the timetable timings given an organisation
	*/
	
	public function getTimingsList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'));
		$select->where(array('organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/*
	* Get the timetable timings given an id
	*/
	
	public function getTimingDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Timetable Details for Editing
	*/
	
	public function getTimetableDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable'));
		$select->where(array('id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/*
	* Get the timetable timings given an organisation e.g. 09:00-10:00 etc
	* Used when to view timetable
	*/
	
	public function getTimetableTiming($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'));
		$select->where(array('organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$timetable_timing = array();
		foreach($resultSet as $set){
			$timetable_timing[] = $set['from_time'].'-'.$set['to_time'];
		}
		
		return $timetable_timing;
	}
	
	/*
	* When Adding the timetable, we need the year the module is taught in so as to calculate the semester
	*/
	
	public function getAcademicModuleYear($academic_module_id, $programmes_id)
	{
		$module_year = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->columns(array('year'));
		$select->where->like('t1.module_title','%'.$academic_module_id.'%');
		$select->where(array('programmes_id' =>$programmes_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$module_year = $set['year'];
		}
		
		return $module_year;
	}
        
	/*
	 * Get the Programmes ID when inserting into timetable
	 */
	
	public function getProgrammeId($programme_name, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'programmes'));
		$select->columns(array('id'));
		$select->where->like('t1.programme_name', $programme_name);
		$select->where(array('organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		$programmes_id = NULL;
		foreach($resultSet as $set){
			$programmes_id = $set['id'];
		}
		
		return $programmes_id;
	}
	
	/*
	 * Get the Academic Module ID when inserting into timetable
	 */
	
	public function getAcademicModuleId($module_code, $programmes_id)
	{
		$academic_module_id = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
                                ->columns(array('academic_modules_id'))
                            ->join(array('t2' => 'academic_modules'), 
                                't1.academic_modules_id = t2.id', array('id', 'module_code'));
		$select->where->like('t2.module_code','%'.$module_code.'%');
		$select->where(array('t2.programmes_id' =>$programmes_id));
                $select->where(array('t1.academic_year' => date('Y')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$academic_module_id = $set['id'];
		}
		
		return $academic_module_id;
	}
	
	/*
	 * Get Module Semester when inserting into timetable
	 */

	public function getModuleSemester($academic_module_id)
	{
		$semester = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->columns(array('semester'));
		$select->where->like('t1.academic_modules_id','%'.$academic_module_id.'%');
		$select->where(array('t1.academic_year' => date('Y')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$semester = $set['semester'];
		}
		
		return $semester;
        }
	
	/*
	* Return an id for the Module given the module code
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $columnName, $programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id'));
		$select->where(array('module_title = ?' => $columnName, $programmes_id.' = ?' => $programmes_id ));
		
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
			if($set['academic_event'] == 'Start of Autumn Semester'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Start of Spring Semester'){
				$semester = 'Spring';
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
	* Get the max. duration of Programmes for Organisation
	*/
	
	public function getMaxProgrammeDuration($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array(new Expression ('MAX(programme_duration) as max_duration')));
		$select->where('t1.organisation_id = ' .$organisation_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$years = array();
		$index = 1;
		foreach ($resultSet as $res) {
			$tmp_number = $res['max_duration'];
			preg_match_all('!\d+!', $tmp_number, $matches);
			$max_years = implode(' ', $matches[0]);
		}
		
		for($i=1; $i<=($max_years); $i++){
				$years[$i] = $i ." Year";
		}
		
		return $years;
	}
	
	/*
	* Crosscheck whether attendance for timetable has been entered
	*/
	
	public function checkTimetableAttendance($id)
	{
		$attendance_check = NULL;
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'student_attendance_dates'))
				->columns(array('academic_timetable_id', 'period'));
		$select2->where(array('academic_timetable_id = ?' => $id));

		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set){
			$attendance_check = $set['academic_timetable_id'];
		}
		return $attendance_check;
	}
	
	/*
	* Crosscheck whether timetable has been entered
	*/
	
	public function crosscheckTimetable($timetableObject)
	{
		$timetableData = $this->hydrator->extract($timetableObject);
		unset($timetableData['id']);
		
		$organisation_id = $this->getOrganisationIdByProgramme($timetableData['programmes_Id']);
		$semester_type = $this->getSemester($organisation_id);
		$academic_year = $this->getAcademicYear($semester_type);
		
		$timetableData['academic_Year'] = $academic_year;
		
		$timetablecheck = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_timetable'))
				->columns(array('id'));
		$select->where(array('day ' => $timetableData['day']));
		$select->where(array('from_time' => $timetableData['from_Time']));
		$select->where(array('to_time' => $timetableData['to_Time']));
		$select->where(array('group' => $timetableData['group']));
		$select->where(array('academic_year' => $timetableData['academic_Year']));
		$select->where(array('academic_modules_allocation_id' => $timetableData['academic_Modules_Allocation_Id']));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$timetablecheck = $set['id'];
		}
		return $timetablecheck;
		
	}
	
	/*
	* Delete Timetable
	*/
	
	public function deleteTimetable($id)
	{
		$action = new Delete('academic_timetable');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/**
	* @return array/Timetable()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		if($organisation_id != NULL)
		{
			$select->where(array('organisation_id = ?' => $organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		if($tableName == 'academic_timetable_timing'){
			foreach($resultSet as $set){
				$selectData[$set[$columnName]] = $set[$columnName];
			}
		} else{
			foreach($resultSet as $set){
				$selectData[$set['id']] = $set[$columnName];
			}
		}
		
		return $selectData;
			
	}
        
}