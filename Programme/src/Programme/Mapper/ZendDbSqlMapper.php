<?php

namespace Programme\Mapper;

use Programme\Model\Programme;
use Programme\Model\Module;
use Programme\Model\AssessmentComponent;
use Programme\Model\EditAssessmentComponent;
use Programme\Model\AssessmentComponentType;
use Programme\Model\ContinuousAssessment;
use Programme\Model\AssignModule;
use Programme\Model\AcademicYearModule;
use Programme\Model\EditAssessmentMark;
use Programme\Model\UploadModuleTutors;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ProgrammeMapperInterface
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
	 * @var \Programme\Model\ProgrammeInterface
	*/
	protected $programmePrototype;
	
	/*
	 * @var \Programme\Model\ModuleInterface
	*/
	protected $modulePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Programme $programmePrototype,
			Module $modulePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->programmePrototype = $programmePrototype;
		$this->modulePrototype = $modulePrototype;
	}
	
	/**
	* @return array/Programme()
	*/
	
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'academic_assessment'){
			$select->from(array('t1' => $tableName))
						->join(array('t2' => 'assessment_component'), 
								't1.assessment_component_id = t2.id', array('assessment_type'=>'assessment','weightage'))
						->join(array('t3'=>'academic_modules_allocation'),
								't2.academic_modules_allocation_id = t3.id', array('academic_modules_id'))
						->join(array('t4'=>'academic_modules'),
								't3.academic_modules_id = t4.id', array('module_title','module_code'));
						//need to fix the year
						//->where('t3.year = ' .date('Y')); 
		}  else if($tableName == 'academic_calendar'){
			$academic_event_type = 'Marks Compile Duration';
			$select->from(array('t1' => $tableName))
                ->join(array('t2' => 'academic_calendar_events'), 
                            't1.academic_event = t2.id', array('academic_event'));
	        $select->where(array('t1.from_date <= ? ' => date('Y-m-d'), 't1.to_date >= ? ' => date('Y-m-d')));
	        $select->where(array('t2.academic_event' => $academic_event_type, 't2.organisation_id' => $organisation_id));
		}else
			$select->from(array('t1' => $tableName)); 
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
		$select->where(array('emp_id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @param int/String $id
	* @return Programme
	* @throws \InvalidArgumentException
	*/
	
	public function findProgramme($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('programmes');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->programmePrototype);
		}

		throw new \InvalidArgumentException("Programme Proposal with given ID: ($id) not found");
	}
	        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Modules for a given $id
	 */
	 
	public function findModule($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules'));
		$select->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->modulePrototype);
		}

		throw new \InvalidArgumentException("Module with given ID: ($id) not found");
		
	}
	
	/*
	 * Get the details of the allocated module in an academic year
	 */
	 
	public function findAllocatedModule($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
                        ->join(array('t2' => 'academic_modules'), 
                        't1.academic_modules_id = t2.id', array('module_title'));
		$select->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get details of the assessment component
	 */
	 
	public function getAssessmentComponentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component'));
		$select->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
	 /*
	 * Get details of the academic assessment component
	*/
	 
	public function getAcademicAssessmentComponentDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_assessment'));
		$select->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
    }
	
	/*
	* Get details of the assessment mark allocated
	*/
	 
	public function getAssessmentMarkDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_assessment'))
			   ->join(array('t2' => 'assessment_component'),
					't2.id = t1.assessment_component_id', array('academic_modules_allocation_id'))
			   ->join(array('t3' => 'assessment_component_types'),
					't3.id = t2.assessment_component_types_id', array('assessment_component_type'))
			   ->join(array('t4' => 'academic_modules_allocation'),
					't4.id = t2.academic_modules_allocation_id', array('programmes_id', 'academic_modules_id'));
		$select->where(array('t1.id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
		
	/**
	 * 
	 * @param type $ProgrammeInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function saveProgramme(Programme $programmeObject)
	{
		$programmeData = $this->hydrator->extract($programmeObject);
		unset($programmeData['id']);
		$programmeData['programme_Approval_Date'] = date("Y-m-d", strtotime(substr($programmeData['programme_Approval_Date'],0,10)));
		$programmeData['programme_Apmr_date'] = date("Y-m-d", strtotime(substr($programmeData['programme_Apmr_date'],0,10)));
		$programmeData['programme_Ccr_date'] = date("Y-m-d", strtotime(substr($programmeData['programme_Ccr_date'],0,10)));
		
		//need to get the file locations and store them in database
		$file_name = $programmeData['programme_Approved_Dpd'];
		$programmeData['programme_Approved_Dpd'] = $file_name['tmp_name'];
		
		if($programmeData['duration_Units'] == 'months'){
			$programme_duration = number_format((float)$programmeData['programme_Duration']/12,1,'.','');
			$programmeData['programme_Duration'] = $programme_duration;
			$programmeData['duration_Units'] = 'years';
		} 
		
		if($programmeObject->getId()) {
			if($programmeData['programme_Approved_Dpd'] == NULL){
				$programmeData['programme_Approved_Dpd'] = $this->getUploadedDpdFile($programmeObject->getId());
			}else{
				$programmeData['programme_Approved_Dpd'] = $programmeData['programme_Approved_Dpd'];
			} //var_dump($programmeData); die();
			//ID present, so it is an update
			$action = new Update('programmes');
			$action->set($programmeData);
			$action->where(array('id = ?' => $programmeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('programmes');
			$action->values($programmeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $programmeObject->setId($newId);
			}
			return $programmeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * To save the update programme
	 * Should update the Programme Table and keep a history of the changes of the programme (programme_history table)
	 */
	 
	 public function updateProgramme(Programme $programmeObject)
	 {
		//first get old programme details and insert into programme history
		$programmeData = $this->hydrator->extract($programmeObject);
		$programmeData['programmes_Id'] = $programmeData['id'];
		//var_dump($programmeData); die();
		$programmeHistory = $this->findProgramme($programmeData['programmes_Id']);
		$programmeHistoryData = $this->hydrator->extract($programmeHistory);
		$programmeHistoryData['programmes_Id'] = $programmeData['id'];
		unset($programmeHistoryData['id']);
		unset($programmeHistoryData['organisation_Id']);
		unset($programmeHistoryData['duration_Units']);
		
		$this->saveProgramme($programmeObject);

		$action = new Insert('programmes_history');
		$action->values($programmeHistoryData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$id=  $programmeObject->setId($newId);
			}
			return $programmeObject;
		}
		
		throw new \Exception("Database Error");
		
	 }


	 public function getUploadedDpdFile($id)
	 {

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$approved_programme_dpd = NULL;
		foreach($resultSet as $set){
			$approved_programme_dpd = $set['programme_approved_dpd'];
		}
		return $approved_programme_dpd;
	 }
	 
	  /*
	 * Get the history of the changes made to a programme
	 */
	 
	public function getProgrammeHistory($id)
	{
		$programme_records = array();
		
		/*$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->where(array('id = ? ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$index=0;
		foreach($resultSet as $set){
			$programme_records[$index++] = $set;
		}*/
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'programmes_history'));
		$select2->where(array('programmes_id = ? ' => $id));
		
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		//$index=0;
		foreach($resultSet2 as $set2){
			$programme_records[] = $set2;
		}
		
		return $programme_records;
	}
	
	/*
	 * Get File Location from the database
	 */
	 
	public function getFileName($id, $category)
	{
		$file = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($category == 'new'){
			$select->from(array('t1' => 'programmes'));
			$select->columns(array('programme_approved_dpd'));
			$select->where(array('id = ? ' => $id));
		}else{
			$select->from(array('t1' => 'programmes_history'));
			$select->columns(array('programme_approved_dpd'));
			$select->where(array('id = ? ' => $id));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$file = $set['programme_approved_dpd'];
		}
		return $file;
	}
	
	
	/**
	 * 
	 * @param type $ProgrammeInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveModule(Module $moduleObject, $data)
	{
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		$moduleData['module_Semester'] = $data['semester'];
		$moduleData['module_Year'] = $data['year'];
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('academic_modules');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_modules');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * To save Module Allocation for an academic year
	 */
	 
	 public function saveModuleAllocation($moduleData)
	 {		 
		$moduleIds = $moduleData['academic_modules_id']; 
		//$data['module_Title'] = $moduleData['module_title'];
		//$data['academic_Year'] = $moduleData['academic_year'];
		//$data['semester'] = $moduleData['semester'];
		//$data['year'] = $moduleData['year'];
		$data['module_Type'] = $moduleData['module_type'];
		$data['academic_Session'] = $moduleData['academic_session'];
		$data['programmes_Id'] = $moduleData['programmes_id'];
		$data['module_credit'] = $moduleData['module_credit'];
		$data['academic_Modules_Id'] = $moduleData['academic_modules_id'];
		
		if($moduleData['id']) {
			//ID present, so it is an update
			$action = new Update('academic_modules_allocation');
			$action->set($data);
			$action->where(array('id = ?' => $moduleData['id']));
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		} else {
			//ID is not present, so its an insert
			foreach($moduleIds as $key => $value){
				$action = new Insert('academic_modules_allocation');
				$action->values(array(
					'module_title' => $value,
					'academic_year'=> $moduleData['academic_year'],
					'semester' => $moduleData['semester'],
					'year' => $moduleData['year'],
					'module_type' => $moduleData['module_type'],
					'programmes_id' => $moduleData['programmes_id'],
					'academic_modules_id'=> $this->getAjaxDataId('academic_modules', $value, $moduleData['programmes_id']),
				));
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
				
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleData['id'] = $newId;
			}
			return $moduleData;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * This function extracts all module definitions from "Module Description"
	 * and assigns it to an academic year.
	 *
	 * Different from saveModuleAllocation where modules are save one at a time
	 */
	 
	 public function saveAllModuleAllocation($organisation_id)
	 {
		$module_allocation_present = $this->getModuleAllocationPresent($organisation_id);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		//Empty, so no allocation is done
		if($module_allocation_present == NULL){
			$modules = $this->listModules($organisation_id);
			$modules_data = array();
			$index = 0;
			foreach($modules as $key=>$value){
				$modules_data[$index++] = $value;
			}
			
			foreach($modules_data as $key => $value){
				$action = new Insert('academic_modules_allocation');
				$action->values(array(
					'module_title' => $value['module_title'],
					'module_code' => $value['module_code'],
					'module_credit' => $value['module_credit'],
					'module_type' => $value['module_type'],
					'academic_session' => $this->getAcademicSessionForAllocation($value['module_code'], $value['programmes_id']),
					'academic_year'=> $academic_year,
					'semester' => $value['module_semester'],
					'year' => $value['module_year'],
					'programmes_id' => $value['programmes_id'],
					'academic_modules_id'=> $value['id'],
				));
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			//once module allocation has been done, assign assessment component allocation
			$this->saveAllAcademicAssessment($organisation_id);
		}
		return;
	 }
	 
	/*
	* Save Missing Modules Allocation
	*/
	
	public function saveMissingModuleAllocation($organisation_id)
	{
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$modules_allocated = $this->getModulesAllocated($organisation_id, $academic_year);
		$total_modules = $this->getAllModules($organisation_id, $academic_year, $semester);
		
		$missing_modules = array_diff($total_modules, $modules_allocated);
		
		$missing_modules_detail = array();
		if($missing_modules){
			$missing_modules_detail = $this->getMissingModuleDetails($missing_modules);
		}
		
		foreach($missing_modules_detail as $key => $value){
			$action = new Insert('academic_modules_allocation');
			$action->values(array(
				'module_title' => $value['module_title'],
				'module_code' => $value['module_code'],
				'module_credit' => $value['module_credit'],
				'module_type' => $value['module_type'],
				'academic_session' => $semester,
				'academic_year'=> $academic_year,
				'semester' => $value['module_semester'],
				'year' => $value['module_year'],
				'contact_hours' => $value['contact_hours'],
				'programmes_id' => $value['programmes_id'],
				'academic_modules_id'=> $value['id'],
			));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		//once module allocation has been done, assign assessment component allocation
		$this->saveMissingAcademicAssessment($organisation_id);
		
		return;
	}
	
	/*
	* Save Mark Allocation as per DPD
	*/
	
	public function allocateDpdMarks($organisation_id)
	{
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$assessment_marks = $this->getDpdAssessmentList($organisation_id);
		
		foreach($assessment_marks as $marks){
			$action = new Insert('academic_assessment');
			$action->values(array(
				'assessment' => $marks['assessment'],
				'date_submission' => date('Y-m-d'),
				'assessment_marks' => $marks['assessment_marks'],
				'assessment_weightage' => $marks['assessment_weightage'],
				'assessment_component_id' => $this->getAssessmentComponentId($marks['programmes_id'], $marks['academic_modules_id'], $marks['assessment_component_types_id']),
			));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		
		return;
	}
	
	/*
	* Get the Assessment Component Id for DPD Marks allocation
	*/
	
	private function getAssessmentComponentId($programmes_id, $academic_modules_id, $assessment_component_types_id)
	{
		$academic_modules_allocation_id = $this->getAcademicModulesAllocationId($programmes_id, $academic_modules_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'));
        $select->where(array('t1.academic_modules_allocation_id = ? ' => $academic_modules_allocation_id));
		$select->where(array('t1.assessment_component_types_id' => $assessment_component_types_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_component_id = NULL;
		foreach($resultSet as $set){
			$assessment_component_id = $set['id'];
		}
		return $assessment_component_id;
	}
	
	
	/*
	* Get all the modules allocated for the academic year
	*/
	
	private function getModulesAllocated($organisation_id, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('id', 'academic_modules_id'))
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('programmes_id'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('organisation_id'));
        $select->where(array('t3.organisation_id = ? ' => $organisation_id));
		$select->where(array('t1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_modules_id = array();
		foreach($resultSet as $set){
			$academic_modules_id[$set['id']] = $set['academic_modules_id'];
		}
		return $academic_modules_id;
	}
	
	/*
	* Get ALL Modules for All Programes by Organisation for the current semester
	*/
	
	private function getAllModules($organisation_id, $academic_year, $semester)
	{
		$semester_session = $semester." Semester";
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t2' => 'academic_modules'))
                    ->columns(array('id', 'programmes_id'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('academic_session_id'))
					->join(array('t4' => 'academic_session'), 
                            't3.academic_session_id = t4.id', array('academic_session'));
        $select->where(array('t3.organisation_id = ? ' => $organisation_id));
		//$select->where(array('t4.academic_session' => $semester_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_modules_id = array();
		foreach($resultSet as $set){
			$academic_modules_id[$set['id']] = $set['id'];
		}
		return $academic_modules_id;
	}
	
	/*
	* Get the details of the Missing Modules
	*/
	
	private function getMissingModuleDetails($missing_modules)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules'));
        $select->where(array('t1.id' => $missing_modules));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_modules = array();
		foreach($resultSet as $set){
			$academic_modules[$set['id']] = $set;
		}
		return $academic_modules;
	}
	
	/*
	* Get all the assessments components that have been allocated (to find the unallocated assessment components)
	*/
	
	private function getAllocatedAssessmentComponents($organisation_id, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
					->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('programmes_id'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'));;
        $select->where(array('t2.academic_year' => $academic_year));
		$select->where(array('t3.organisation_id' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$modules_assessments = array();
		foreach($resultSet as $set){
			$modules_assessments[$set['academic_modules_allocation_id']] = $set['academic_modules_allocation_id'];
		}
		return $modules_assessments;
	}
         
	 public function saveAllAcademicAssessment($organisation_id)
	 {
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		 
		 $academic_module_assessments = $this->getAcademicModuleAssessment($organisation_id);
		 
		 foreach($academic_module_assessments as $modules){
			$action = new Insert('assessment_component');
			$action->values(array(
					'assessment' => $modules['assessment'],
					'weightage'=> $modules['weightage'],
					'assessment_year' => date('Y'),
					'academic_modules_allocation_id' => $this->getModuleAllocationId($modules['academic_modules_id'], $academic_year),
					'assessment_component_types_id' => $modules['assessment_component_types_id']
			));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		 }
		 
		 return;
	 }
	 
	 /*
	 * Save the Missing Assessment Components (both for missing modules and others)
	 */
	 
	 public function saveMissingAcademicAssessment($organisation_id)
	 {
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		 
		 //get all the module allocation for academic year
		 $modules_allocated = $this->getModulesAllocated($organisation_id, $academic_year);
		 
		 //get all allocated assessment components
		 $allocated_assessment_components = $this->getAllocatedAssessmentComponents($organisation_id, $academic_year);
		 
		 $missing_components = array_diff_key($modules_allocated, $allocated_assessment_components);
		 if($missing_components){
			 //get the different types of assessments for organisation
		 	$academic_module_assessments = $this->getMissingAcademicModuleAssessment($missing_components);
		 } else {
			//initialise it as empty
		 	$academic_module_assessments = array();
		 }
		 foreach($academic_module_assessments as $modules){
			$action = new Insert('assessment_component');
			$action->values(array(
					'assessment' => $modules['assessment'],
					'weightage'=> $modules['weightage'],
					'assessment_year' => date('Y'),
					'academic_modules_allocation_id' => $this->getModuleAllocationId($modules['academic_modules_id'], $academic_year),
					'assessment_component_types_id' => $modules['assessment_component_types_id']
			));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		 }
		 return;
	 }
	 
	 /*
	 * Save Edited Assessment Mark
	 */
	 
	 public function saveEditedAssessmentMark($markData)
	 {
		$markData['marks'] = $markData['marks'];
				
		$action = new Update('assessment_marks');
		$action->set($markData);
		$action->where(array('id = ?' => $markData['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$markData['id']= $newId;
			}
			return $markData;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	/*
	* Save Edited Compiled Mark
	*/
	
	public function saveEditedCompiledMark($marks_data)
	{
		$markData['marks'] = $marks_data['marks'];
		$markData['status'] = $marks_data['status'];

		$action = new Update('student_consolidated_marks');
		$action->set($markData);
		$action->where(array('id = ?' => $marks_data['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $marks_data['id'] = $newId;
			}
			return $marks_data;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	* Updating the marks after the Student Edit Assessment Mark List is provided
	* Done for ONE student only
	*/
	
	public function editStudentAssessmentMark($id)
	{
		$marks_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_marks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id'))
					->join(array('t3' => 'academic_assessment'), 
                            't1.academic_assessment_id = t3.id', array('assessment_marks'))
                    ->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$marks_details = $set;
		}
		return $marks_details;
	}
	
	 /*
	* Updating the marks after the marks are compiled
	* Done for ONE student only
	*/
	
	public function editStudentCompiledMark($id)
	{
		$marks_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_consolidated_marks'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.student_id', array('first_name','middle_name','last_name','student_id'))
                    ->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$marks_details = $set;
		}
		
		return $marks_details;
	}
        
	/*
	 * check if tutor is assigned to section and/or whether assignment has already been marked
	 */
	
	public function crossCheckAsssignment($academic_modules_allocation_id, $assessment, $section, $username)
	{
		$assignment_status = NULL;
		
		$sql = new Sql($this->dbAdapter);
		//check whether section is assigned to tutor
		$select = $sql->select();
		$select->from(array('t1' => 'academic_module_tutors'))
						->join(array('t2' => 'student_section'), 
											't1.section = t2.id');
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t2.id' => $section));
		$select->where->like('t1.module_tutor','%'.$username.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$assigned_module = $set['id'];
			if($assigned_module != NULL){
				$assignment_status = 'Assigned';
			}
		}
		
		//check whether the marks have already been given for assignment
		$select2 = $sql->select();
		$select2->from(array('t1' => 'academic_assessment_status'))
					->join(array('t2' => 'academic_assessment'), 
					't1.academic_assessment_id = t2.id', array('assessment'))
					->join(array('t3' => 'assessment_component'), 
					't2.assessment_component_id = t3.id', array('academic_modules_allocation_id'));
		$select2->where(array('t1.section' => $section));
		$select2->where(array('t3.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select2->where(array('t2.id' => $assessment));

		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set2){
			$marked_module = $set2['id'];
			if($marked_module != NULL){
				$assignment_status = 'Assigned and Marked';
			}
		}
		return $assignment_status;
	}
	
	/*
	 * check if a module has been compiled or not
	 */
	
	public function checkCompiledMarks($academic_modules_allocation_id, $section, $assessment_type)
	{
		$compilation_status = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'compiled_marks_status'));
		$select->where(array('t1.section' => $section));
		$select->where(array('t1.type like ?' => $assessment_type.'%'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$marked_module = $set['id'];
			if($marked_module != NULL){
				$compilation_status = 'Compiled';
			}
		}
		return $compilation_status;
	}

	public function crossCheckCompiled($batch, $section, $continuous_assessment_id)
	{
		$compilation_status = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'compiled_marks_status'))
			->join(array('t2' => 'assessment_component'), 
				't1.academic_modules_allocation_id = t2.academic_modules_allocation_id')
			->join(array('t3' => 'academic_assessment'), 
				't2.id = t3.assessment_component_id');
		$select->where(array('t1.section = ?' => $section));
		$select->where(array('t3.id = ?' => $continuous_assessment_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$marked_module = $set['id'];
			if($marked_module != NULL){
				$compilation_status = 'Compiled';
			}
		}
		return $compilation_status;
	}
	 
	 /*
	 * To check whether the module allocation has been done or not.
	 */
	 
	 public function getModuleAllocationPresent($organisation_id)
	 {
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);
		
		$academic_event_details = $this->getSemester($organisation_id);
        

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('programmes_id'))
                    ->join(array('t3'=>'programmes'),
                            't2.programmes_id = t3.id', array('organisation_id'));
		$select->where(array('t3.organisation_id' =>$organisation_id));
		$select->where(array('t1.academic_year' => $academic_year));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$return_data = NULL;
		foreach($resultSet as $set){
			$return_data = $set['academic_year'];
		}
		return $return_data;
	 }
	 
	 /*
	 * To save Programme Leader and Tutors to Modules for an academic year
	 */
	 
	 public function saveModuleTutors($tutorData)
	 {		
		$tutorNames = NULL;
		$sections = array();

		$module_coordinator = $this->getEmployeeId($tutorData['module_coordinator']);
		$tutorNames = $module_coordinator;
		if($tutorData['module_tutor'] != 0){
			$module_tutor = $this->getEmployeeId($tutorData['module_tutor']);
			$tutorNames = $tutorNames.'/'.$module_tutor;
		}
		if($tutorData['module_tutor_2'] != 0){
			$module_tutor_2 = $this->getEmployeeId($tutorData['module_tutor_2']);
			$tutorNames = $tutorNames.'/'.$module_tutor_2;
		}
		if($tutorData['module_tutor_3'] != 0){
			$module_tutor_3 = $this->getEmployeeId($tutorData['module_tutor_3']);
			$tutorNames = $tutorNames.'/'.$module_tutor_3;
		}

		foreach($tutorData as $key => $value){
			if(preg_match("/section/", $key) && $value == 1){
				preg_match_all('!\d+!', $key, $matches);
						$sections[] = implode(' ', $matches[0]);
			}
		}
								
		if($tutorData['id']) {
			//ID present, so it is an update
			$action = new Update('academic_module_tutors');
			$action->set($tutorData);
			$action->where(array('id = ?' => $tutorData['id']));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		} else {
			//ID is not present, so its an insert
			foreach($sections as $key => $value){
				$action = new Insert('academic_module_tutors');
				$action->values(array(
					'year'=> date('Y'),
					'section' => $value,
					'module_coordinator' => $module_coordinator,
					'module_tutor' => $tutorNames,
					'academic_modules_allocation_id'=> $tutorData['academic_modules_allocation_id'],
				));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $tutorData['id'] = $newId;
			}
			return $tutorData;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * To save the default Module Tutors to Academic Modules
	*/
	 
	public function saveModuleTutorsAssignment($tutorData)
	{
		$tutorNames = array();
		$academic_module_id = $tutorData['academic_modules_id'];
		//$academic_module_id = $this->getAjaxDataId('academic_modules', $tutorData['academic_modules_id'], $tutorData['programmes_id']);
		$academic_module_code = $this->getAcademicModuleCode($academic_module_id);
		
		if($tutorData['module_tutor'] != 0){
			$module_tutor = $tutorData['module_tutor'];
			$assignment = $this->crossCheckAcademicModuleTutorAssignment($module_tutor, $academic_module_id);
			if(!$assignment){
				array_push($tutorNames, $module_tutor);
			}
		}
		if($tutorData['module_tutor_2'] != 0){
			$module_tutor_2 = $tutorData['module_tutor_2'];
			$assignment = $this->crossCheckAcademicModuleTutorAssignment($module_tutor_2, $academic_module_id);
			if(!$assignment){
				array_push($tutorNames, $module_tutor_2);
			}			
		}
		if($tutorData['module_tutor_3'] != 0){
			$module_tutor_3 = $tutorData['module_tutor_3'];
			$assignment = $this->crossCheckAcademicModuleTutorAssignment($module_tutor_3, $academic_module_id);
			if(!$assignment){
				array_push($tutorNames, $module_tutor_3);
			}			
		}
		if($tutorData['module_tutor_4'] != 0){
			$module_tutor_4 = $tutorData['module_tutor_4'];
			$assignment = $this->crossCheckAcademicModuleTutorAssignment($module_tutor_4, $academic_module_id);
			if(!$assignment){
				array_push($tutorNames, $module_tutor_4);
			}			
		}
		
		foreach($tutorNames as $key=>$value){
			if($tutorData['id']) {
				//ID present, so it is an update
				$action = new Update('modules_tutors_assignment');
				$action->set($tutorData);
				$action->where(array('id = ?' => $tutorData['id']));
	
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			} else {
				//ID is not present, so its an insert
				
				$action = new Insert('modules_tutors_assignment');
				$action->values(array(
					'module_code' => $academic_module_code,
					'academic_modules_id' => $academic_module_id,
					'programmes_id' => $tutorData['programmes_id'],
					'employee_details_id' => $value
				));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			
		}
		return;
		
	}
	
	/*
	* Allocating Modules to Module Tutors in Bulk
	*/
	
	public function saveAcademicModuleToTutorAssignment($data)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($data['programmes_id']);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sections = array();
		$tutorData = array();
		$tutorData_temp = array();
		
		foreach($data as $key => $value){
			if(preg_match("/section/", $key)){
				$sections[] = explode('_', $key);
			}
		}
		
		//sorting the data
		foreach($sections as $section_data){
			$tutorData[$section_data['3']][$section_data['1']] = $section_data['2'];
			$tutorData_temp[$section_data['3']][$section_data['1']] = $section_data['2'];
		}
		
		//sort out the tutor data
		// if already assigned, remove from array.
		// if previously assigned but removed during reassignment , then remove from array
		foreach($tutorData_temp as $key => $value){
			//$key2 is employee_details_id, $value2 is academic_modules_id
			foreach($value as $key2 => $value2){
				$academic_modules_allocation_id = $this->getAcademicModulesAllocationId($data['programmes_id'], $value2);
				$module_tutor = $this->getEmployeeId($key2);
				//here $key is section
				$crosscheck = $this->crossCheckModuleAssignment($academic_year, $key, $module_tutor, $academic_modules_allocation_id);
				if($crosscheck){
					unset($tutorData[$key][$key2]);
				}
				$assigned_sections[] = $this->getModuleAssignmentAcademicYear($academic_year, $module_tutor, $academic_modules_allocation_id);
			}
		}
		
		$to_delete_data = $assigned_sections;
		foreach($assigned_sections as $index => $assigned_data){
			foreach($assigned_data as $k => $v){
				foreach($v as $k2 => $v2){
					if(array_key_exists($k, $tutorData_temp)){
						if(array_key_exists($k2, $tutorData_temp[$k])){
							unset($to_delete_data[$index][$k]);
						}
					}
				}
			}
		}
		foreach($to_delete_data as $delete_data){
			foreach($delete_data as $sec => $emp_id){
				foreach($emp_id as $key => $academic_module_tutors_id){
					$this->deleteModuleAssignmentAcademicYear($academic_module_tutors_id);
				}
			}
		}
		
		foreach($tutorData as $key=>$value){
			foreach($value as $key2 => $value2){
				$academic_modules_allocation_id = $this->getAcademicModulesAllocationId($data['programmes_id'], $value2);
				$module_tutor = $this->getEmployeeId($key2);
				//here $key is section
				//$crosscheck = $this->crossCheckModuleAssignment($academic_year, $key, $module_tutor, $academic_modules_allocation_id);
				
				$action = new Insert('academic_module_tutors');
				$action->values(array(
					'year' => $academic_year,
					'section' => $key,
					'module_tutor' => $module_tutor,
					'academic_modules_allocation_id' => $academic_modules_allocation_id
				));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
		}
	}
	 
	 /*
	 * To save Programme Leader/Cooridnator to Modules for an academic year
	*/
	 
	public function saveModuleCoordinator($coordinatorData)
	{
		$module_coordinator = $this->getEmployeeId($coordinatorData['module_coordinator']);
		$academic_modules_allocation_id = $coordinatorData['academic_modules_id'];
		//To get the module code based on academic_modules_allocation_id
		$academic_module_code = $this->getAcademicModuleCode($academic_modules_allocation_id);
						
		if($coordinatorData['id']) {
			//ID present, so it is an update
			$action = new Update('academic_module_coordinators');
			$action->set($coordinatorData);
			$action->where(array('id = ?' => $coordinatorData['id']));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_module_coordinators');
			$action->values(array(
				'module_coordinator' => $module_coordinator,
				'module_code' => $academic_module_code,
				'academic_modules_id'=> $academic_modules_allocation_id,
			));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $coordinatorData['id'] = $newId;
				$this->saveModuleCoordinatorToTutor($coordinatorData);
			}
			return $coordinatorData;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Cross check whether a module has been assigned to a module coordinator or not
	*/
	
	public function checkModuleCoordinatorAssignment($coordinatorData)
	{
		$assignment = 'Not Assigned';
		
		$module_coordinator = $this->getEmployeeId($coordinatorData['module_coordinator']);
		$academic_modules_id = $coordinatorData['academic_modules_id'];
		//To get the module code based on academic_modules_allocation_id
		$academic_module_code = $this->getAcademicModuleCode($academic_modules_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_coordinators'));
		$select->where(array('t1.academic_modules_id' => $academic_modules_id));
		//$select->where(array('t1.module_coordinator' => $module_coordinator));
		$select->where(array('t1.module_code' => $academic_module_code));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_assessment = NULL;
		foreach($resultSet as $set){
			$assignment = 'Assigned';
		}
		return $assignment;
		
	}


	//Function to save module coordinator as tutor to from assign module coordinator
	public function saveModuleCoordinatorToTutor($tutorData)
	{
		$academic_module_id = $tutorData['academic_modules_id'];

		$academic_module_code = $this->getAcademicModuleCode($academic_module_id);
				
		$action = new Insert('modules_tutors_assignment');
		$action->values(array(
			'module_code' => $academic_module_code,
			'academic_modules_id' => $academic_module_id,
			'programmes_id' => $tutorData['programmes_id'],
			'employee_details_id' => $tutorData['module_coordinator']
		));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $tutorData['id'] = $newId;
			}
			return $tutorData;
		}
		
		throw new \Exception("Database Error");
	}

         
	/*
	 * Upload file with Module Tutors for Academic Year
	 */
	
	/*public function saveModuleTutorFile(UploadModuleTutors $uploadModel, $organisation_id)
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

		// Loop through each of row of the worksheet in turn
		$module_tutors_array = array();
		for($row = 2; $row <= $highestRow; $row++){
				$highestColumn = $sheet->getHighestColumn();
				for($col = 0; $col <=4; $col++){
					$cell = $sheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					$module_tutors_array[$row][$col] = $val;
				}
		}
		
		foreach($module_tutors_array as $tutors){
			//Load the values into $timetableData
			$tutorData['year'] = date('Y');
			$tutorData['section'] = $tutors[0];
			$tutorData['module_Coordinator'] = $tutors[1];
			$tutorData['module_Tutor'] = $tutors[2];
			$programmes_id = $this->getProgrammeId($tutors[4], $organisation_id);
			$tutorData['academic_Modules_Allocation_Id'] = $this->getUploadModuleAllocationId($tutors[3], $programmes_id);
			
			$action = new Insert('academic_module_tutors');
			$action->values($tutorData);
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		
		return;
		
	}*/
	
	/*
	 * To Save the Assessment Components for each module
	 * Separate function is used when editing
	 */
	 
	 public function saveAssessmentComponent($data)
	 {
		$programmes_id = $data['programmes_id'];
        $assessment_component_types_id = $data['assessment'];
		$academic_module_id = $data['academic_modules_id'];
		$assessment_type = $this->getAssessmentName($assessment_component_types_id);
		
		$assessmentData['assessment'] = $assessment_type;
		$assessmentData['weightage'] = $data['weightage'];
		$assessmentData['academic_Modules_Id'] = $academic_module_id;
		$assessmentData['assessment_Component_Types_Id'] = $assessment_component_types_id;
		
		$action = new Insert('academic_modules_assessment');
		$action->values($assessmentData);

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	 }
	 
	  /*
	 * to save edited assessment component
	 */
	 
	 public function saveEditedAssessmentComponent(EditAssessmentComponent $assessmentObject)
	 {
		$assessmentData = $this->hydrator->extract($assessmentObject);
		unset($assessmentData['id']);
		
		$assessmentData['assessment'] = $this->getAssessmentName($assessmentData['assessment_Component_Types_Id']);
		//old table name = assessment_component
		$action = new Update('academic_modules_assessment');
		$action->set($assessmentData);
		$action->where(array('id = ?' => $assessmentObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $assessmentObject->setId($newId);
			}
			return $assessmentObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * Save the assessment component types
	 */
	 
	 public function saveComponentType(AssessmentComponentType $moduleObject)
	 {
		$moduleData = $this->hydrator->extract($moduleObject);
		unset($moduleData['id']);
		
		if($moduleObject->getId()) {
			//ID present, so it is an update
			$action = new Update('assessment_component_types');
			$action->set($moduleData);
			$action->where(array('id = ?' => $moduleObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('assessment_component_types');
			$action->values($moduleData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $moduleObject->setId($newId);
			}
			return $moduleObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * To save the mark allocation
	 */
	 
	 public function saveMarkAllocation($assessmentData)
	 {
		$data['assessment'] = $assessmentData['assessment'];
		//$data['date_Submission'] = $assessmentData['date_submission'];
		$data['assessment_Marks'] = $assessmentData['assessment_marks'];
		$data['assessment_Weightage'] = $assessmentData['assessment_weightage'];
		$data['remarks'] = $assessmentData['remarks'];
		$data['assessment_Component_Id'] = $assessmentData['assessment_component_id'];
		//$data['assessment_Component_Id'] = $this->getAjaxDataId('assessment_component',$assessmentData['assessment_component_id'],$assessmentData['academic_modules_allocation_id']);
		$data['date_Submission'] = date("Y-m-d", strtotime(substr($assessmentData['date_submission'],0,10)));

		if($assessmentData['id']) {
			//ID present, so it is an update
			$action = new Update('academic_assessment');
			$action->set($data);
			$action->where(array('id = ?' => $assessmentData['id']));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_assessment');
			$action->values($data);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$assessmentData['id']=$newId;
			}
			return $assessmentData;
		}
		
		throw new \Exception("Database Error");
	 }

	 public function deleteAssessmentMarkAllocation($id)
	 {
	 	$action = new Delete('academic_assessment');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	 }



	 public function crossCheckAcademicAssessmentMarks($id)
	 {
	 	$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_marks'));
		$select->where(array('t1.academic_assessment_id = ?' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_assessment = NULL;
		foreach($resultSet as $set){
			$academic_assessment = $set['id'];
		}
		return $academic_assessment;
	 }
	 
	 /*
	* To save the mark allocation as per DPD
	*/
	 
	public function saveDpdMarkAllocation($assessmentData)
	{
		$data['assessment'] = $assessmentData['assessment'];
		$data['assessment_Marks'] = $assessmentData['assessment_marks'];
		$data['assessment_Weightage'] = $assessmentData['assessment_weightage'];
		$data['assessment_Component_Types_Id'] = $assessmentData['assessment_component_types_id'];
		$data['programmes_Id'] = $assessmentData['programmes_id'];
		$data['academic_Modules_Id'] = $assessmentData['academic_modules_id'];

		if($assessmentData['id']) {
			//ID present, so it is an update
			$action = new Update('academic_assessment_defaults');
			$action->set($data);
			$action->where(array('id = ?' => $assessmentData['id']));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('academic_assessment_defaults');
			$action->values($data);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$assessmentData['id']=$newId;
			}
			return $assessmentData;
		}
		
		throw new \Exception("Database Error");
	}
	 
	 /*
	 * To save the continuous assessment marks
	 */
	 
	 public function saveAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	 {
		//get academic year
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		//get the student list
		$i=1;
		$academic_modules_allocation_id = $this->getAcademicAllocationModuleId($continuous_assessment_id);
		$studentIds = array();
		$marks_entered_student_list = $this->getMarksEnteredStudentList($section, $continuous_assessment_id);
		
		if($assessment_type == 'Semester Exams'){
			if ($marks_entered_student_list) {
				$studentData = $this->getMissingStudentList($continuous_assessment_id,$studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'continuous_assessment',$status = NULL);
			} else {
				$studentData = $this->getStudentList($studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'semester_exams',$status = NULL);
			}
		} else {
			if ($marks_entered_student_list) {
				$studentData = $this->getMissingStudentList($continuous_assessment_id,$studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'continuous_assessment',$status = NULL);
			} else {
				$studentData = $this->getStudentList($studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'continuous_assessment',$status = NULL);
			}
		}
		//$studentData = $this->getStudentList($studentName=NULL, $section, $programmesId, $batch);
		foreach($studentData as $key=>$value){
			$studentIds[$i++] = $key;
		}
		
		$academic_module_code = $this->getAssignmentAcademicModuleCode($continuous_assessment_id);
		//the following loop is to insert marks
		if($data != NULL)
		{
			$i = 1;
			foreach($data as $value)
			{
				$action = new Insert('assessment_marks');
				$action->values(array(
					'assessment_type' => $assessment_type,
					'marks' => $value,
					'programmes_id'=> $programmesId,
                    'section' => $section,
					'academic_assessment_id' => $continuous_assessment_id,
					'module_code' => $academic_module_code,
					'entry_date' => date('Y-m-d'),
					'student_id' => $this->getStudentId($studentIds[$i]),
					'academic_year' => $academic_year
				));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				$i++;
			}
			$this->changeAcademicAssessmentStatus($continuous_assessment_id, $section);
			return;
		}
		
		throw new \Exception("Database Error"); 
	 }
	 
	 /*
	* To save the edited continuous assessment marks
	*/
	 
	public function updateAssessmentMarks($data, $programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	{
		//get academic year
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		//get the student list
		$i=1;
		$academic_modules_allocation_id = $this->getAcademicAllocationModuleId($continuous_assessment_id);
		$studentIds = array();
		if($assessment_type == 'Semester Exams'){
			$studentData = $this->getStudentList($studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'semester_exams',$status = NULL);
		} else {
			$studentData = $this->getStudentList($studentName=NULL, $section, $academic_modules_allocation_id, $programmesId, $batch, $marks = 'continuous_assessment',$status = NULL);
		}
		//$studentData = $this->getStudentList($studentName=NULL, $section, $programmesId, $batch);
		foreach($studentData as $key=>$value){
			$studentIds[$i++] = $key;
		}
		
		$academic_module_code = $this->getAssignmentAcademicModuleCode($continuous_assessment_id);
		//the following loop is to insert marks
		if($data != NULL)
		{
			foreach($data as $key => $value)
			{				
				$action = new Update('assessment_marks');
				$action->set(array('marks' => $value));
				$action->where(array('academic_assessment_id = ?' => $continuous_assessment_id));
				$action->where(array('student_id' => $this->getStudentId($key)));
				$action->where(array('section = ?' => $section));
			
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
			$this->changeAcademicAssessmentStatus($continuous_assessment_id, $section);
			return;
		}
		
		throw new \Exception("Database Error"); 
	}

	public function deleteAssessmentMarks($programmesId, $batch, $section, $continuous_assessment_id, $assessment_type)
	 {
	 	$action = new Delete('assessment_marks');
		$action->where(array('programmes_id = ?' => $programmesId));
		$action->where(array('section = ?' => $section));
		$action->where(array('academic_assessment_id = ?' => $continuous_assessment_id));
		$action->where(array('assessment_type = ?' => $assessment_type));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$action1 = new Delete('academic_assessment_status');
		$action1->where(array('academic_assessment_id = ?' => $continuous_assessment_id));

		$sql1 = new Sql($this->dbAdapter);
		$stmt1 = $sql1->prepareStatementForSqlObject($action1);
		$result = $stmt1->execute();

		return (bool)$result->getAffectedRows();
	 }
	 
	 /*
	 * Crosscheck to ensure that there is no duplicate entry for a module in a programme
	 */
	 
	public function crosscheckProgrammeModule($module_code, $programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules'));
		$select->where->like('module_code','%'.$module_code);
		$select->where(array('programmes_id = ?' =>$programmes_id));
		$select->where(array('status = ?' => 'Active'));	
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$programme = 0;
		foreach($resultSet as $set){
			$programme = $set['id'];
		}
		return $programme;
	}
	
	/*
	* Crosscheck to while editing assessment mark
	*/
	
	public function assessmentTimeCheck($assessment, $section)
	{
		$present_date = date('Y-m-d');
		$two_week_date = date('Y-m-d', strtotime("-2 week"));
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_assessment_status'))
					->join(array('t2' => 'academic_assessment'), 
					't1.academic_assessment_id = t2.id', array('assessment'));
		$select->where(array('t1.section = ?' =>$section));
		$select->where->like('t2.assessment','%'.$assessment);
		$select->where->between('t1.date', $two_week_date, $present_date);
		/*
		$select->where(array('t1.date >= ? ' => $two_week_date));
		$select->where(array('t1.date <= ? ' => $present_date));
		*/
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$status = 0;
		foreach($resultSet as $set){
			$status = $set['status'];
		}
		
		return $status;
	}
	
	/*
	 * Check whether the semester marks have been entered or not
	 */
	 
	public function checkSemesterMarkEntry($academic_modules_allocation_id, $assessment, $section, $organisation_id, $username)
	{		
		//var_dump($assessment); die();
		//$assessment = 'Semester Exams';
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
        //$academic_year = $academic_event_details['academic_year'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_assessment_status'))
					->join(array('t2' => 'academic_assessment'), 
					't1.academic_assessment_id = t2.id', array('assessment'))
					->join(array('t3' => 'assessment_component'), 
					't2.assessment_component_id = t3.id', array('academic_modules_allocation_id'));
		$select->where(array('t1.section' => $section));
		$select->where(array('t1.academic_assessment_id' => $assessment));
		$select->where(array('t3.academic_modules_allocation_id' => $academic_modules_allocation_id));
		$select->where(array('t3.assessment like ? ' => 'Semester Exam%'));
		
		//$select->where->like('t3.assessment','%'.$assessment.'%');

		/*
		$select->from(array('t1' => 'assessment_component'))
				->join(array('t2' => 'academic_assessment'), 
                            't1.id = t2.assessment_component_id', array('assessment_component_id'))
                    ->join(array('t3'=>'assessment_marks'),
                            't2.id = t3.academic_assessment_id', array('assessment_type'));
		$select->where->like('t1.assessment','%'.$assessment);
		$select->where(array('t1.academic_modules_allocation_id = ?' =>$academic_modules_allocation_id));
		*/
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_component = NULL;
		foreach($resultSet as $set){
			$assessment_component = $set['id'];
		}
		return $assessment_component;
	}
	
	/*
	* Save Student and Elective Modules
	*/
	
	public function saveStudentElectiveModules($data, $academic_modules_allocation_id, $organisation_id)
	{	
		unset($data['id']);
		unset($data['csrf']);
		unset($data['submit']);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		$academic_module_code = $this->getAcademicModuleCodeByAllocation($academic_modules_allocation_id);
		//the following loop is to insert marks
		if($data != NULL)
		{
			foreach($data as $key => $value)
			{
				if((int)$value == '1'){
					$action = new Insert('student_elective_modules');
					//$action = new Insert('academic_module_elective');
					$action->values(array(
						'module_code' => $academic_module_code,
						'academic_year' => $academic_year,
						'academic_session' => $semester,
						'academic_modules_allocation_id' => $academic_modules_allocation_id,
						'student_id' => $this->getstudentid($key),

					));
				
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
			}
			return;
		}
		
		throw new \Exception("Database Error"); 
	}
	
	/*
	* Compile the assessment marks
	*/
	
	public function compileMarks($academic_modules_allocation_id, $section, $assessment_for, $organisation_id)
	{
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$assessment_components = $this->getAssessmentComponents($academic_modules_allocation_id, $assessment_for);
		$result_components = $this->getConsolidateMarkComponents($academic_modules_allocation_id, $assessment_for);
		
		foreach($assessment_components as $key=>$value){ 
			foreach($result_components as $key1 => $value1){
				if(array_key_exists('weightage', $value1) &&(int)$value['weightage'] != 0 && $value1['assessment'] == $value['assessment']){ 
					$marks = $this->calculateAssessmentMarks($value['assessment_component_id'], $section);
					
					if($value['assessment'] == 'Continuous Assessment'){
						$assessment_type = 'CA';
					} elseif($value['assessment'] == 'Semester Exams'){
						$assessment_type = 'SE';
					} elseif($value['assessment'] == 'Continuous Assessment (Practical)'){
						$assessment_type = 'CA (P)';
					} elseif($value['assessment'] =='Continuous Assessment (Theory)') {
						$assessment_type = 'CA (T)';
					} elseif($value['assessment'] =='Semester Exams (Theory)') {
						$assessment_type = 'SE (T)';
					} else {
						$assessment_type = 'SE (P)';
					}
					$this->enterConsolidatedMarks($academic_modules_allocation_id, $marks, $assessment_type, $value1);
					//update the compiled table to ensure that module is not compiled again
					$this->updateCompiledTable($academic_modules_allocation_id, $section, $assessment_for,$assessment_type);
				}	
			}
		}
		return;
	}
	
	/*
	* Get the assessment components
	*/
	
	private function getAssessmentComponents($academic_module_allocation_id, $assessment_for)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component'))
					->columns(array('id','assessment', 'weightage'));
		$select->where('t1.academic_modules_allocation_id = ' .$academic_module_allocation_id);
		$select->where->like('t1.assessment','%'.$assessment_for.'%');
                
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
	
	private function getConsolidateMarkComponents($academic_modules_allocation_id, $assessment_type)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
							->columns(array('academic_year'))
					->join(array('t2'=>'academic_modules'),
                            't1.academic_modules_id = t2.id', array('module_title','module_code', 'module_credit','programmes_id'))
					->join(array('t4'=>'programmes'),
						't2.programmes_id = t4.id', array('programme_name'))
					->join(array('t3'=>'assessment_component'),
							't1.id = t3.academic_modules_allocation_id', array('weightage', 'assessment'));
				//	->join(array('t3'=>'academic_modules_assessment'),
                           // 't2.id = t3.academic_modules_id', array('weightage'));
		if($academic_modules_allocation_id){
			$select->where('t1.id =' .$academic_modules_allocation_id);
		}
		$select->where->like('t3.assessment',$assessment_type.'%');
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_components = array();
		$i = 0;
		foreach($resultSet as $set){
			$assessment_components[$i]['academic_year'] = $set['academic_year'];
			$assessment_components[$i]['programme_name'] = $set['programme_name'];
			$assessment_components[$i]['module_title'] = $set['module_title'];	
			$assessment_components[$i]['module_code'] = $set['module_code'];
			$assessment_components[$i]['module_credit'] = $set['module_credit'];
			$assessment_components[$i]['programmes_id'] = $set['programmes_id'];
			$assessment_components[$i]['assessment'] = $set['assessment'];
			$assessment_components[$i]['weightage'] = $set['weightage'];
			$i++;
		}
		return $assessment_components;
	}
	
	/*
	* Calculate the Continuous Assessment and Consolidate it
	*/
	
	private function calculateAssessmentMarks($assessment_component_id, $section)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component'))
					->columns(array('assessment_year','weightage'))
				->join(array('t2' => 'academic_assessment'), 
						't1.id = t2.assessment_component_id', array('assessment_marks', 'assessment_weightage'))
				->join(array('t3' => 'assessment_marks'), 
						't2.id = t3.academic_assessment_id', array('marks', 'student_id'));
		$select->where('t1.id = ' .$assessment_component_id);
		$select->where('t3.section = ' .$section);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$marks = array();
		$weightage = 0;
		$assessment_weightage = 0;
		
		foreach($resultSet as $set){
			if(array_key_exists($set['student_id'], $marks)){
				$marks[$set['student_id']] += (number_format((float)($set['marks']/$set['assessment_marks'])*$set['assessment_weightage'],2,'.',''));
				$assessment_weightage += $set['assessment_weightage'];
			} else {
				$marks[$set['student_id']] = ($set['marks']/$set['assessment_marks'])*$set['assessment_weightage'];
				$assessment_weightage += $set['assessment_weightage'];
			}
			$weightage = $set['weightage'];
			
		}
		$assessment_weightage = $assessment_weightage/count($marks);
		foreach($marks as $key => $value){
			$marks[$key] = (number_format((float)$value,2,'.',''))*((number_format((float)$weightage,0,'.',''))/(number_format((float)$assessment_weightage,0,'.','')));
		}
		
		return $marks;
	}
	
	/*
	* Enter the Consolidate Marks into database
	*/
	
	public function enterConsolidatedMarks($academic_modules_allocation_id, $marks, $assessment_type, $result_components)
	{
		if(count($marks) != 0){
			foreach($marks as $key => $value){
				$markData['assessment_Type'] = $assessment_type;
				$markData['marks'] = number_format((float)$value,2,'.','');
				$markData['programme_Name'] = $result_components['programme_name'];
				$markData['academic_modules_allocation_id'] = $academic_modules_allocation_id;
				$markData['module_Title'] = $result_components['module_title'];
				$markData['module_Code'] = $result_components['module_code'];
				$markData['credit'] = $result_components['module_credit'];
				$markData['weightage'] = $result_components['weightage'];
				$markData['programmes_Id'] = $result_components['programmes_id'];
				$markData['semester'] = $this->getSemesterForConsolidatedMarks($result_components['module_code'], $result_components['programmes_id']);
				$markData['academic_Year'] = $result_components['academic_year'];
				$markData['pass_Year'] = date('Y');
				$markData['level'] = 'Regular';
				$markData['student_Id'] = $this->getStdId($key);
				$markData['temp_Student_Id'] = $this->getStdId($key);
				$markData['result_Status'] = 'Moderated';
				$percentage = (number_format((float)($markData['marks']),2,'.','')/number_format((float)($markData['weightage']),2,'.',''));
				if($percentage >= 0.4){
					$markData['status'] = 'Pass';
				} else {
					$markData['status'] = 'Re-assessment';
				}
				
				$action = new Insert('student_consolidated_marks');
				$action->values($markData);
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
			
		return;
	}
	
	/*
	* Get the marks for the CA/SE for mass editing
	*/
	
	public function getStudentMarks($assessment, $section)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_marks'))
				->columns(array('marks'))
				->join(array('t2' => 'student'), 
						't1.student_id = t2.id', array('student_id'));
		$select->where('t1.academic_assessment_id = ' .$assessment);
		$select->where('t1.section = ' .$section);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$studentMarks = array();
		
		foreach($resultSet as $set){
			$studentMarks[$set['student_id']] = $set['marks'];
		}
		
		return $studentMarks;
	}
	 
	 /*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('organisation_id'));
		}else{
			$select->from(array('t1' => 'student'));
			$select->where(array('student_id' =>$username));
			$select->columns(array('organisation_id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the Academic Modules Allocation Id
	*/
	
	private function getAcademicModulesAllocationId($programmes_id, $academic_module_id)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_code'));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t2.id' => $academic_module_id));
		$select->where(array('t2.programmes_id' => $programmes_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$academic_modules_allocation_id = NULL;
		foreach($resultSet as $set){
			$academic_modules_allocation_id = $set['id'];
		}
		
		return $academic_modules_allocation_id;
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
	* Get module_allocation_module type based on the academic_module_allocation
	*/
	
	public function getModuleAllocationDetails($academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->where(array('id' =>$academic_modules_allocation_id));
		$select->columns(array('module_type'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			return $set['module_type'];
		}
	}

	public function getMarksEnteredStudentList($section, $continuous_assessment_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1'=>'assessment_marks'));
		$select->columns(array('student_id'));
		$select->where(array('t1.academic_assessment_id' => $continuous_assessment_id));
		$select->where(array('t1.section' => $section));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['student_id']] = $set['student_id'];
		}
		//var_dump($selectData); die();
		return $selectData;
		
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
		$select->columns(array('id', 'organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
	* Get the details for the student id from the scheduled counseling appointments
	*/
	
	public function findStudentId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('scheduled_counseling_appointments');
		$select->where(array('id = ? ' => $id));
		$select->columns(array('student_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Student details so that their names are displayed
	 */
	public function getStudentDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student')) // base table
			   ->join(array('t2' => 'programmes'),
			   		't2.id = t1.programmes_id', array('programme_name'))
			   ->join(array('t3' => 'organisation'),
			   		't3.id = t1.organisation_id', array('organisation_name','organisation_dzongkha_name','address'))
				->where('t1.id = ' .$id); // join expression
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get the employee details
	*/
	
	public function getEmployeeDetails($empId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->where('t1.id = ' .$empId);
		$select->columns(array('id','first_name','middle_name','last_name'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the batch which the module is for
	*  Returns "year"
	*/
	
	public function getBatch($academic_modules_allocation_id, $assessment, $assessment_for)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($assessment_for == 'continuous_assessment')
		{
			$select->from(array('t1' => 'academic_assessment')) 
                    ->columns(array('academic_assessment_id'=>'id','assessment_weightage','assessment_marks'))
					->join(array('t2' => 'assessment_component'), 
                            't2.id = t1.assessment_component_id', array('academic_modules_allocation_id'))
					->join(array('t3' => 'academic_modules_allocation'), 
                            't2.academic_modules_allocation_id = t3.id', array('year', 'programmes_id'))
                    ->where(array('t2.academic_modules_allocation_id = ' .$academic_modules_allocation_id))
					->where(array('t1.id = ' .$assessment));
           //$select->where->like('t1.assessment','%'.$assessment.'%');
		} else if($assessment_for == 'semester_exams'){
			$select->from(array('t1' => 'academic_assessment')) 
                    ->columns(array('academic_assessment_id'=>'id','assessment_weightage','assessment_marks'))
					->join(array('t2' => 'assessment_component'), 
                            't2.id = t1.assessment_component_id', array('academic_modules_allocation_id'))
					->join(array('t3' => 'academic_modules_allocation'), 
                            't2.academic_modules_allocation_id = t3.id', array('year', 'programmes_id'))
                    ->where(array('t2.academic_modules_allocation_id = ' .$academic_modules_allocation_id))
					->where(array('t1.id = ' .$assessment));
		} else if($assessment_for == 'view'){
			$select->from(array('t1' => 'academic_assessment')) 
                    ->columns(array('assessment_weightage','assessment_marks'))
					->join(array('t2' => 'assessment_marks'), 
                            't1.id = t2.academic_assessment_id', array('marks'))
					->join(array('t3' => 'student'), 
                            't2.student_id = t3.id', array('first_name','middle_name','last_name','student_id'))
                    ->where(array('t1.assessment_component_id = ' .$academic_modules_allocation_id));
		}
		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* get the list of modules for each tutor
	*/
	
	public function getModuleTutorList($employee_id)
	{
		$organisation_id = NULL;
		$organisation = $this->getUserDetailsId($employee_id, 'employee_details');
		foreach($organisation as $set){
			$organisation_id = $set['organisation_id'];
		}
		
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('id','module_title'))
				->join(array('t2' => 'academic_module_tutors'), 
                            't1.id = t2.academic_modules_allocation_id', array('module_tutor'));
		$select->where->like('t2.module_tutor', $employee_id);
		$select->where(array('t1.academic_year' => $academic_year));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$moduleData = array();
		foreach($resultSet as $set)
		{
			$moduleData[$set['id']] = $set['module_title'];
		}
		return $moduleData;
	}
	
	/*
	* get the list of modules for each coordinator
	*/
	
	public function getModuleCoordinatorList($employee_id)
	{
		$organisation_id = NULL;
		$organisation = $this->getUserDetailsId($employee_id, 'employee_details');
		foreach($organisation as $set){
			$organisation_id = $set['organisation_id'];
		}
		
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('id','module_title', 'module_code'))
				->join(array('t2' => 'academic_module_coordinators'), 
                            't1.academic_modules_id = t2.academic_modules_id', array('module_coordinator'));
		$select->where->like('t2.module_coordinator', $employee_id);
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t1.academic_session' => $semester));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$moduleData = array();
		foreach($resultSet as $set)
		{
			$moduleData[$set['id']] = $set['module_title']." (".$set['module_code'].")";
		}
		
		return $moduleData;
	}
	
	/*
	* Get the list of modules assessments for a given tutor
	*/
	
	public function getTutorAssessmentList($employee_id)
	{
		$organisation_id = NULL;
		$organisation = $this->getUserDetailsId($employee_id, 'employee_details');
		foreach($organisation as $set){
			$organisation_id = $set['organisation_id'];
		}
		
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_assessment'))
					->join(array('t2' => 'assessment_component'), 
							't1.assessment_component_id = t2.id', array('assessment_type'=>'assessment','weightage'))
					->join(array('t3'=>'academic_modules_allocation'),
							't2.academic_modules_allocation_id = t3.id', array('academic_modules_id'))
					->join(array('t4'=>'academic_modules'),
							't3.academic_modules_id = t4.id', array('module_title','module_code'))
					->join(array('t5'=>'academic_module_coordinators'),
							't3.academic_modules_id = t5.academic_modules_id', array('module_coordinator'))
					->join(array('t6' => 'programmes'),
							't6.id = t4.programmes_id', array('programme_name'))
                                        ->where->like('t5.module_coordinator', $employee_id);
					//->where(array('t5.module_coordinator' => $employee_id));
						//need to fix the year
						//->where('t3.year = ' .date('Y')); 
		$select->where(array('t3.academic_year' => $academic_year));
		$select->where(array('t3.academic_session' => $semester));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of modules assessments for an organisation
	*/
	
	public function getDpdAssessmentList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_assessment_defaults'))
					->join(array('t2'=>'academic_modules'),
							't1.academic_modules_id = t2.id', array('module_title','module_code'))
					->join(array('t3' => 'programmes'),
							't3.id = t2.programmes_id', array('programme_name'))
					->join(array('t4' => 'assessment_component_types'),
							't4.id = t1.assessment_component_types_id', array('assessment_type'=>'assessment_component_type'));
		$select->where(array('t3.organisation_id' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of Programmes for an organisation
	*/
	
	public function listProgrammes($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'programmes'))
				->join(array('t2' => 'employee_details'), 
                            't1.programme_leader = t2.id', array('first_name','middle_name','last_name'));
		$select->where(array('t1.organisation_id' =>$organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of Modules for Programme based on organisation
	*/
	
	public function listModules($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules')) 
                    ->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('organisation_id'))
                    ->where('t2.organisation_id = ' .$organisation_id);
		//get only active modules
		$select->where(array('t1.status != ?' => 'In Active'));
		//$select->where(array('t1.status' => 'Phasing Out'), Where::OP_OR);
		$select->order(array('t2.programme_name ASC', 't1.module_year ASC', 't1.module_semester ASC'));	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
        /*
	* Get the list of Modules based on Programme Id
	*/
	
	public function getModuleListByProgramme($programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules')) 
                    ->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->where('t1.programmes_id = ' .$programmes_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
    }
	
	
	public function getAllocatedModules($organisation_id)
	{
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->columns(array('id','module_title','module_code','module_type','academic_session','module_credit','year','semester','academic_year'))
					 ->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id',array('status'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'));
		$select->where('t3.organisation_id = ' .$organisation_id);
		$select->where(array('t1.academic_year' => $academic_year));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
        /*
	* Get the list of unallocated modules
	*/
	
	public function getUnallocatedModule($organisation_id)
	{
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		if($semester == "Spring"){
			$semesters = array(2,4,6,8);
		}
		else{
			$semesters = array(1,3,5,7);
       }
            
		//get the list of modules that have been assigned to module tutors
		$allocated_modules = $this->getAllocatedModuleWithTutors($organisation_id);
		$allocated_modules_id = array();
		foreach($allocated_modules as $modules){
			$allocated_modules_id[$modules['id']] = $modules['id'];
		}
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
					->join(array('t2' => 'academic_modules'), 
						't1.academic_modules_id = t2.id', array('module_title','module_credit','module_code'))
		->join(array('t3' => 'programmes'), 
						't2.programmes_id = t3.id', array('programme_name'));
		$select ->where->notIn('t1.id', $allocated_modules_id);
		$select->where(array('t1.semester' => $semesters));
		$select->where(array('t1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
    }
	
	/*
	* Get allocated modules with the tutor list
	*/
	
	public function getAllocatedModuleWithTutors($organisation_id)
	{
		//need to get which part of the year so that we do not mix the enrollment years
		//$semester = $this->getSemester($organisation_id);
        //$academic_year = $this->getAcademicYear($semester);

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation')) 
                     ->columns(array('academic_year','academic_modules_id'))
					 ->join(array('t4' => 'academic_module_tutors'), 
                            't4.academic_modules_allocation_id = t1.id', array('id','module_tutor'))
					 ->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title','module_credit','module_code'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t5' => 'employee_details'), 
                            't4.module_tutor = t5.emp_id', array('first_name','middle_name','last_name'))
					->join(array('t6' => 'student_section'), 
                            't4.section = t6.id', array('section'));
        $select->where('t3.organisation_id = ' .$organisation_id);
		$select->where(array('t1.academic_year' => $academic_year));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of modules allocated with the tutors
	*/
	
	public function getAllocatedModuleToTutors($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'modules_tutors_assignment')) 
					 ->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title','module_code'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t5' => 'employee_details'), 
                            't1.employee_details_id = t5.emp_id', array('first_name','middle_name','last_name'));
        $select->where('t5.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of the Default Modules assigned to Module Tutors
	*/
	
	public function getAssignedAcademicModules($organisation_id)
	{			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'modules_tutors_assignment')) 
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title','module_code', 'module_semester','module_type','status'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t5' => 'employee_details'), 
                            't1.employee_details_id = t5.id', array('first_name','middle_name','last_name','emp_id'));
        $select->where('t5.organisation_id = ' .$organisation_id);
		$select->where('t3.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get the list of the Default Modules assigned to Module Tutors By Semester
	*/
	
	public function getAssignedAcademicModulesBySemester($programmes_id, $academic_modules_id)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		//Get the semester numbers depending on the start of academic session
		// 1 :- Spring Session
		// 2 :- Autumn Session 
		$semesters = $this->getSemesterArray($semester, $programmes_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'modules_tutors_assignment')) 
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title', 'module_code'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
					->join(array('t4' => 'academic_modules_allocation'), 
                            't4.academic_modules_id = t2.id', array('semester'))
					->join(array('t5' => 'employee_details'), 
                            't1.employee_details_id = t5.id', array('first_name','middle_name','last_name','emp_id'));
        $select->where('t1.programmes_id = ' .$programmes_id);
		$select->where('t2.id = ' .$academic_modules_id);
		//$select->where(array('t4.semester' => $semesters));
		$select->where(array('t4.academic_year' => $academic_year));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of Module Coordinators for Organisation
	*/
	
	public function getAllocatedModuleWithCoordinators($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_coordinators'))
					->join(array('t3' => 'academic_modules'), 
                            't1.academic_modules_id = t3.id', array('module_title', 'module_code', 'module_semester','module_type','status'))
					->join(array('t4' => 'programmes'), 
                            't3.programmes_id = t4.id', array('programme_name'))
					->join(array('t5' => 'employee_details'), 
                            't1.module_coordinator = t5.emp_id', array('first_name','middle_name','last_name','emp_id'));
        $select->where('t5.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
    /*
	* Get the assessment components for academic modules
	*/
	
	public function getAcademicModuleAssessment($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_assessment'))
					->join(array('t3' => 'academic_modules'), 
                            't3.id = t1.academic_modules_id', array('module_title', 'module_code' ,'programmes_id'))
					->join(array('t4' => 'programmes'), 
                            't4.id = t3.programmes_id', array('programme_name','organisation_id'));
        $select->where('t4.organisation_id = ' .$organisation_id);
		//get only active modules
		$select->where(array('t3.status != ?' => 'In Active'));
		//$select->where(array('t3.status' => 'Phasing Out'), Where::OP_OR);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
    }
	
	/*
	* Get the assessment components for the missing academic modules
	*/
	
	private function getMissingAcademicModuleAssessment($missing_modules)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_assessment'))
					->join(array('t3' => 'academic_modules'), 
                            't3.id = t1.academic_modules_id', array('module_title', 'module_code' ,'programmes_id'))
					->join(array('t4' => 'programmes'), 
                            't4.id = t3.programmes_id', array('programme_name','organisation_id'));
		$select->where(array('t3.id' => $missing_modules));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
    }
	
	/*
	* Get the assessment components
	*/
	
	public function getAssessmentComponent($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component')) 
					 ->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('programmes_id'))
					->join(array('t3' => 'academic_modules'), 
                            't3.id = t2.academic_modules_id', array('module_title', 'module_code' ,'programmes_id'))
					->join(array('t4' => 'programmes'), 
                            't4.id = t3.programmes_id', array('programme_name','organisation_id'))
                    ->where('t4.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the assessment components
	*/
	
	public function getAssessmentComponentType($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component_types')) 
                    ->where('t1.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of module tutors
	*/
	
	public function getModuleTutors($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_module_tutors'))
					->columns(array('module_tutor'))
					 ->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('programmes_id'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'))
                    ->where('t3.organisation_id = ' .$organisation_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get th details of the tutor details
	*/
	
	public function getTutorDetail($tutorIds)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('emp_id ' => $tutorIds));
		$select->columns(array('emp_id','first_name','middle_name','last_name'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
			
		$employeeData = array();
		foreach($resultSet as $set)
		{
			$employeeData[$set['emp_id']] = $set['first_name'] . ' '. $set['middle_name'] .' '. $set['last_name'];
		}
		return $employeeData;
	}
	
	/*
	* Get the modules that are being taught for each academic year by programme
	*/
	
	public function getAcademicYearModule($programmes_id, $semester)
	{
        $organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		//$semester_session = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_session);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
					->join(array('t3' => 'academic_modules'), 
                            't1.academic_modules_id = t3.id', array('module_title','module_code','module_credit'))
					->join(array('t2' => 'programmes'), 
                            't2.id = t3.programmes_id', array('programme_name'))
					->join(array('t4' => 'assessment_component'), 
                            't1.id = t4.academic_modules_allocation_id', array('assessment', 'weightage'));
		$select->where(array('t1.programmes_id = ' .$programmes_id));
		$select->where(array('t1.semester = ' .$semester));
        $select->where(array('t1.academic_year' => $academic_year));
								
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$index = 1;
		$module = array();
		foreach($resultSet as $set){
			if(array_key_exists($set['module_code'], $module)){
				$module[$set['module_code']][$set['assessment']] = $set['weightage'];
			} else {
				$module[$set['module_code']]['module_title'] = $set['module_title'];
				$module[$set['module_code']]['module_credit'] = $set['module_credit'];
				$module[$set['module_code']][$set['assessment']] = $set['weightage'];
			}
		}
		return $module;
	}
	
	/*
	* Get the marks for the student for a particular assessment
	*/
	
	public function getStudentAssessment($programmesId, $batch, $assessment)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_marks'))
					 ->join(array('t3' => 'academic_assessment'), 
                            't1.academic_assessment_id = t3.id', array('assessment_marks','assessment_weightage'))
					 ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id'))
                    ->where(array('t1.programmes_id = ' .$programmesId, 't2.enrollment_year = ' .$batch, 't1.assessment_type'=>$assessment));
								
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the marks for the student for a particular assessment
	*/
	
	public function getStudentAssessmentMarks($academic_modules_allocation_id, $section, $type)
	{
		$assessment_marks = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($type == 'consolidated'){
			$select->from(array('t1' => 'academic_assessment'))
					 ->columns(array('assessment','id'))
					 ->join(array('t2' => 'assessment_component'), 
                            't1.assessment_component_id = t2.id', array('academic_modules_allocation_id'))
                    ->where(array('t2.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
					//->where(array('t1.section = ' .$section));
		} elseif($type == 'semester_assessment'){
			$select->from(array('t1' => 'academic_assessment'))
					 ->columns(array('assessment','id'))
					 ->join(array('t2' => 'assessment_component'), 
                            't1.assessment_component_id = t2.id', array('academic_modules_allocation_id'))
                    ->where(array('t2.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
			$select->where->like('t2.assessment','%Semester Exam%');
		}
		else {
			$select->from(array('t1' => 'academic_assessment'))
					 ->columns(array('assessment','id'))
					 ->join(array('t2' => 'assessment_component'), 
                            't1.assessment_component_id = t2.id', array('academic_modules_allocation_id'))
                    ->where(array('t2.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
			$select->where->notLike('t2.assessment','%Semester Exam%');
		}
		
		/*
		$select->from(array('t1' => 'assessment_marks'))
					 ->join(array('t3' => 'academic_assessment'), 
                            't1.academic_assessment_id = t3.id', array('assessment_marks','assessment_weightage'))
					->join(array('t4' => 'assessment_component'), 
                            't3.assessment_component_id = t4.id', array('academic_modules_allocation_id'))
					 ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id'))
                    ->where(array('t4.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
		*/
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assessment_components = array();
		foreach($resultSet as $set){
			$assessment_components[$set['id']] = $set['assessment'];
		}
		
		foreach($assessment_components as $key => $value){
			$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_marks'))
					 ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('student_id'))
					 ->join(array('t3' => 'academic_assessment'), 
                            't1.academic_assessment_id = t3.id', array('assessment_marks','assessment_weightage'))
					->join(array('t4' => 'assessment_component'), 
                            't3.assessment_component_id = t4.id', array('academic_modules_allocation_id'));
			$select2->where(array('t4.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
			$select2->where(array('t1.section = ' .$section));
			$select2->where->like('t3.assessment', $value);
			$select2->order(array('t2.student_id'));
			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				//$assessment_marks[$value][$set2['student_id']] = number_format((float)(($set2['marks'])*$set2['assessment_weightage']/$set2['assessment_marks']),2,'.','');
				$assessment_marks[$value][$set2['assessment_weightage']][$set2['assessment_marks']][$set2['student_id']]['id'] = $set2['id'];
				$assessment_marks[$value][$set2['assessment_weightage']][$set2['assessment_marks']][$set2['student_id']]['marks']= $set2['marks'];
			}
		} 
		return $assessment_marks;
		
	}
	
	/*
	* Get the compiled marks for the student for a particular assessment
	*/
	
	public function getCompiledMarks($academic_modules_allocation_id, $section, $type)
	{
		$assessment_marks = array();
		$studentNameList = $this->getStudentNameList($academic_modules_allocation_id, $section);
		$studentIds = array();
		foreach($studentNameList as $key => $value){
			$studentIds[] = $key;
		}
		
		if($type == 'continuous_assessment'){
			$assessment_type = 'CA';
		} else {
			$assessment_type = 'SE';
		}
		
		//get the module code
		$module_code = $this->getModuleCode($academic_modules_allocation_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_consolidated_marks'));
        $select->where->like('t1.module_code', $module_code);
		$select->where(array('student_id ' => $studentIds));
		$select->where(array('t1.result_status != ?' => 'Declared'));
		$select->where->like('t1.assessment_type', $assessment_type.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$assessment_marks[$set['assessment_type']][$set['weightage']][$set['student_id']]['id'] = $set['id'];
			$assessment_marks[$set['assessment_type']][$set['weightage']][$set['student_id']]['marks']= $set['marks'];
		} 
		return $assessment_marks;
	}
        
	/*
	 * Get the Consolidated Marks for All Students By Programme
	 */
	
	public function getStudentConsolidatedMarks($programme, $academic_year, $semester)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		//$semester_type = $this->getSemester($organisation_id);
		//here academic year is the academic year the consolidated marks is requested for
		//current academic year stores present academic year
		//$current_academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);
		
		$assessment_marks = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
					->columns(array('id','student_id','first_name','middle_name','last_name'))
					->join(array('t2' => 'student_consolidated_marks'), 
						't1.student_id = t2.student_id', array('assessment_type', 'marks', 'module_code', 'weightage'))
					->where(array('t2.academic_year ' => $academic_year))
					->where(array('t1.programmes_id = ' .$programme))
					->where(array('t2.semester' =>$semester));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
                
		foreach($resultSet as $set){
			$assessment_marks[$set['id']][$set['module_code']][$set['assessment_type']][$set['weightage']] = $set['marks'];
			//$assessment_marks[$set['id']][$set['module_code']][$set['assessment_type']] = $set['weightage'];
		} //var_dump($assessment_marks); die();
		return $assessment_marks;
	}


	public function getModuleCreditList($programme, $academic_year, $semester)
	{ 
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		//$semester_type = $this->getSemester($organisation_id);
		//here academic year is the academic year the consolidated marks is requested for
		//current academic year stores present academic year
		//$current_academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);
		
		$module_credit_list = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->join(array('t2' => 'academic_modules'),
			   		't2.id = t1.academic_modules_id', array('module_code', 'module_credit'))
					->where(array('t1.academic_year' => $academic_year))
					->where(array('t1.programmes_id' => $programme))
					->where(array('t1.semester' => $semester));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
                
		foreach($resultSet as $set){
			$module_credit_list[$set['module_code']][$set['module_credit']] = $set['module_credit'];
			//$assessment_marks[$set['id']][$set['module_code']][$set['assessment_type']] = $set['weightage'];
		} 
		return $module_credit_list;
	}
        
	/*
	 * Get the Consolidated Marks for A Student
	 */
	
	public function getConsolidatedMarkByStudentId($id)
	{
		$assessment_marks = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
						->columns(array('id','student_id','first_name','middle_name','last_name'))
						->join(array('t2' => 'student_consolidated_marks'), 
							't1.student_id = t2.student_id')
						->where(array('t1.id ' => $id));
		$select->where(array('t2.result_status' => 'Declared'));
		$select->where->notLike('t2.result_status','Cancelled');
		$select->order('t2.semester ASC');
		$select->order('t2.academic_year ASC');
		$select->order('t2.declared_date ASC');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();		
		$resultSet->buffer();
		return $resultSet->initialize($result); 
		/*
		foreach($resultSet as $set){
			$assessment_marks[$set['academic_year']][$set['module_code']][$set['assessment_type']] = $set['marks'];
		}
		return $assessment_marks; */
	}

	public function getStudentBlockByStudentId($id)
	{
		$assessment_marks = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'block_result'))
			->columns(array('id','student_details_id','student_id','programmes_id','academic_year','status'))
			->where(array('t1.student_details_id ' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();		
		$resultSet->buffer();
		$resultSet->initialize($result); 

		$block_result = NULL;
		foreach($resultSet as $set){
			$block_result['id'] = $set['id'];
		}
		return $block_result;
	}
	
	/*
	* Get the names for the students for a particular assessment
	* Used for getStudentAssessmentMarks
	*/
	
	public function getStudentNameList($academic_modules_allocation_id, $section)
	{
		$student_names = array();
		$year;
		$semester;
		$programmes_id;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
			->columns(array('id', 'year', 'semester', 'programmes_id'))
			->where(array('t1.id = ' .$academic_modules_allocation_id));



		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$assessment_components = array();
		foreach($resultSet as $set){
			$year = $set['year'];
			$semester = $set['semester'];
			$programmes_id = $set['programmes_id'];
		}
		
		//get the organisation by programme
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		
		//need to work on this
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
				
		$student_names = $this->getStudentList(NULL, $section, $academic_modules_allocation_id, $programmes_id, $academic_year, $marks = 'continuous_assessment',$status = NULL);
		
		
		/* Old
		foreach($student_name_list as $set2){
				$student_names[$set2['student_id']] = $set2['first_name'].' '.$set2['middle_name'].' '.$set2['last_name'];
		}*/
		
		return $student_names;
	}
	
	/*
	* Get All marks for a particular assessment for a specific module for editing
	*/
	
	public function getStudentAssessmentEditing($academic_modules_allocation_id, $assessment, $section)
	{
		$assessment_marks = array();
		$sql = new Sql($this->dbAdapter);
		$select2 = $sql->select();
		$select2->from(array('t1' => 'assessment_marks'))
				 ->join(array('t2' => 'student'), 
						't1.student_id = t2.id', array('first_name','middle_name','last_name','student_id'))
				 ->join(array('t3' => 'academic_assessment'), 
						't1.academic_assessment_id = t3.id', array('assessment_marks','assessment_weightage'))
				->join(array('t4' => 'assessment_component'), 
						't3.assessment_component_id = t4.id', array('academic_modules_allocation_id'));
		$select2->where(array('t4.academic_modules_allocation_id = ' .$academic_modules_allocation_id));
		$select2->where->like('t3.assessment', $assessment);
		$select2->where(array('t1.section = ' .$section));
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set2){
			$assessment_marks[$set2['id']]['id'] = $set2['id'];
			$assessment_marks[$set2['id']]['student_id'] = $set2['student_id'];
			$assessment_marks[$set2['id']]['name'] = $set2['first_name'].' '.$set2['middle_name'].' '.$set2['last_name'];
			$assessment_marks[$set2['id']]['assessment_marks'] = $set2['assessment_marks'];
			$assessment_marks[$set2['id']]['assessment_weightage'] = $set2['assessment_weightage'];
			$assessment_marks[$set2['id']]['marks'] = $set2['marks'];
		}
		
		return $assessment_marks;
	}
	
	/*
	* List Student to add the marks
	*/
	
	public function getStudentList($studentName, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for,$status)
	{ 
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		//$semester_type = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$semester = $this->getSemesterForModule($academic_modules_allocation_id);
		$student_list = array();
				
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if ($status == 'elective_allocation'){
			
			$select->from(array('t1' => 'student'))
				->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
				->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('student_section_id'))
				->join(array('t3' => 'student_section'), 
					't2.student_section_id = t3.id', array('section'))
				->join(array('t4' => 'programmes'), 
					't1.programmes_id = t4.id', array('programme_name'));
			$select->where(array('t2.semester_id' => $semester));
			$select->where(array('t1.current_status' => 'Regular'));
			$select->where(array('t1.student_status_type_id' => 1));
			$select->where(array('t2.academic_year' => $academic_year));
			$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
			if($programmesId){
				$select->where(array('programmes_id' =>$programmesId));
			}
			
	        if($section){
				$select->where(array('t3.id' =>$section));
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
			}

			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
			foreach($backyear_students_list as $key => $value){
				$student_list[$key] = $value;
			}
			/*
			//get the backyear students and remove students who have cleared from student list
			$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
					
			//get backpaper students
			$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
			
			//remove this from student list
			$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
			
			foreach($backyear_students_module_cleared as $key => $value){
				unset($student_list[$key]);
			}
			
			//add this to student list
			$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
			foreach($backpaper_students as $key => $value){
				$student_list[$key] = $value;
			}*/
		} 
		else 
		{
			$modules_type = $this->getModuleAllocationDetails($academic_modules_allocation_id);
			
			if ($modules_type =='Compulsory'){
				$select->from(array('t1' => 'student'))
					->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
					->join(array('t2' => 'student_semester_registration'),
						't1.id = t2.student_id', array('student_section_id'))
					->join(array('t3' => 'student_section'), 
						't2.student_section_id = t3.id', array('section'))
					->join(array('t4' => 'programmes'), 
						't1.programmes_id = t4.id', array('programme_name'));
				$select->where(array('t2.semester_id' => $semester));
				$select->where(array('t1.current_status' => 'Regular'));
				$select->where(array('t1.student_status_type_id' => 1));
				$select->where(array('t2.academic_year' => $academic_year));
				$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
				if($programmesId){
					$select->where(array('programmes_id' =>$programmesId));
				}
				/*
				//no longer needed as we are using semester and academic year
				if($batch){
					$select->where(array('enrollment_year' =>$batch));
				} */
		        if($section){
					$select->where(array('t3.id' =>$section));
				}

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				
				foreach($resultSet as $set){
					$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
				}

				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
				foreach($backyear_students_list as $key => $value){
					$student_list[$key] = $value;
				}
				/*
				//get the backyear students and remove students who have cleared from student list
				$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
						
				//get backpaper students
				$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
				
				//remove this from student list
				$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
				
				foreach($backyear_students_module_cleared as $key => $value){
					unset($student_list[$key]);
				}
				
				//add this to student list
				$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
				foreach($backpaper_students as $key => $value){
					$student_list[$key] = $value;
				}*/
				
			} else{
				$select->from(array('t1' => 'student'))
					->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
					->join(array('t2' => 'student_semester_registration'),
						't1.id = t2.student_id', array('student_section_id'))
					->join(array('t3' => 'student_section'), 
						't2.student_section_id = t3.id', array('section'))
					->join(array('t4' => 'programmes'), 
						't1.programmes_id = t4.id', array('programme_name'))
					->join(array('t5' => 'student_elective_modules'),
						't5.student_id = t1.id',  array('academic_modules_allocation_id'));
				$select->where(array('t2.semester_id' => $semester));
				$select->where(array('t1.current_status' => 'Regular'));
				$select->where(array('t1.student_status_type_id' => 1));
				$select->where(array('t2.academic_year' => $academic_year));
				$select->where(array('t5.academic_modules_allocation_id' => $academic_modules_allocation_id));
				$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
				if($programmesId){
					$select->where(array('programmes_id' =>$programmesId));
				}
				/*
				//no longer needed as we are using semester and academic year
				if($batch){
					$select->where(array('enrollment_year' =>$batch));
				} */
		        if($section){
					$select->where(array('t3.id' =>$section));
				}

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				
				foreach($resultSet as $set){
					$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
				}

				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
				foreach($backyear_students_list as $key => $value){
					$student_list[$key] = $value;
				}
				/*
				//get the backyear students and remove students who have cleared from student list
				$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
						
				//get backpaper students
				$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
				
				//remove this from student list
				$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
				
				foreach($backyear_students_module_cleared as $key => $value){
					unset($student_list[$key]);
				}
				
				//add this to student list
				$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
				foreach($backpaper_students as $key => $value){
					$student_list[$key] = $value;
				}*/
				
			}
		}
		
		return $student_list;
	}

	public function getMissingStudentList($continuous_assessment_id, $studentName, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for,$status)
	{ 
		$student_list = NULL;
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		
		$academic_event_details = $this->getSemester($organisation_id);
        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		$semester = $this->getSemesterForModule($academic_modules_allocation_id);

		$marks_entered_student_list = $this->getMarksEnteredStudentList($section, $continuous_assessment_id);
				
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if ($status == 'elective_allocation'){
			
			$select->from(array('t1' => 'student'))
				->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
				->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('student_section_id'))
				->join(array('t3' => 'student_section'), 
					't2.student_section_id = t3.id', array('section'))
				->join(array('t4' => 'programmes'), 
					't1.programmes_id = t4.id', array('programme_name'));
				$select->where->notIn('t1.id', $marks_entered_student_list); 
			$select->where(array('t2.semester_id' => $semester));
			$select->where(array('t1.current_status' => 'Regular'));
			$select->where(array('t1.student_status_type_id' => 1));
			$select->where(array('t2.academic_year' => $academic_year));
			$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
			if($programmesId){
				$select->where(array('programmes_id' =>$programmesId));
			}
			
	        if($section){
				$select->where(array('t3.id' =>$section));
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
			}
			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
			foreach($backyear_students_list as $key => $value){
				$student_list[$key] = $value;
			}
			/*
			
			//get the backyear students and remove students who have cleared from student list
			$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
			$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
					
			//get backpaper students
			$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
			
			//remove this from student list
			$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
			
			foreach($backyear_students_module_cleared as $key => $value){
				unset($student_list[$key]);
			}
			
			//add this to student list
			$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
			foreach($backpaper_students as $key => $value){
				$student_list[$key] = $value;
			}*/
		} 
		else 
		{
			$modules_type = $this->getModuleAllocationDetails($academic_modules_allocation_id);
			
			if ($modules_type =='Compulsory'){
				$select->from(array('t1' => 'student'))
					->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
					->join(array('t2' => 'student_semester_registration'),
						't1.id = t2.student_id', array('student_section_id'))
					->join(array('t3' => 'student_section'), 
						't2.student_section_id = t3.id', array('section'))
					->join(array('t4' => 'programmes'), 
						't1.programmes_id = t4.id', array('programme_name'));
				$select->where->notIn('t1.id', $marks_entered_student_list); 
				$select->where(array('t2.semester_id' => $semester));
				$select->where(array('t1.current_status' => 'Regular'));
				$select->where(array('t1.student_status_type_id' => 1));
				$select->where(array('t2.academic_year' => $academic_year));
				
				$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
				if($programmesId){
					$select->where(array('programmes_id' =>$programmesId));
				}
				/*
				//no longer needed as we are using semester and academic year
				if($batch){
					$select->where(array('enrollment_year' =>$batch));
				} */
		        if($section){
					$select->where(array('t3.id' =>$section));
				}

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				
				foreach($resultSet as $set){
					$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
				}
				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
				foreach($backyear_students_list as $key => $value){
					$student_list[$key] = $value;
				}
				/*
				//get the backyear students and remove students who have cleared from student list
				$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
						
				//get backpaper students
				$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
				
				//remove this from student list
				$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
				
				foreach($backyear_students_module_cleared as $key => $value){
					unset($student_list[$key]);
				}
				
				//add this to student list
				$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
				foreach($backpaper_students as $key => $value){
					$student_list[$key] = $value;
				}*/
				
			} else{
				$select->from(array('t1' => 'student'))
					->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
					->join(array('t2' => 'student_semester_registration'),
						't1.id = t2.student_id', array('student_section_id'))
					->join(array('t3' => 'student_section'), 
						't2.student_section_id = t3.id', array('section'))
					->join(array('t4' => 'programmes'), 
						't1.programmes_id = t4.id', array('programme_name'))
					->join(array('t5' => 'student_elective_modules'),
						't5.student_id = t1.id',  array('academic_modules_allocation_id'));
				$select->where->notIn('t1.id', $marks_entered_student_list); 
				$select->where(array('t2.semester_id' => $semester));
				$select->where(array('t1.current_status' => 'Regular'));
				$select->where(array('t1.student_status_type_id' => 1));
				$select->where(array('t2.academic_year' => $academic_year));
				$select->where(array('t5.academic_modules_allocation_id' => $academic_modules_allocation_id));
				$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
				if($programmesId){
					$select->where(array('programmes_id' =>$programmesId));
				}
				/*
				//no longer needed as we are using semester and academic year
				if($batch){
					$select->where(array('enrollment_year' =>$batch));
				} */
		        if($section){
					$select->where(array('t3.id' =>$section));
				}

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);
				
				foreach($resultSet as $set){
					$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
				}

				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year, $academic_modules_allocation_id, $programmesId, $section);
				foreach($backyear_students_list as $key => $value){
					$student_list[$key] = $value;
				}
				/*
				//get the backyear students and remove students who have cleared from student list
				$backyear_students_in_module = $this->getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId);
				$backyear_students_list = $this->getBackyearStudentList($semester, $academic_year);
						
				//get backpaper students
				$backpaper_students_in_module = $this->getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for);
				
				//remove this from student list
				$backyear_students_module_cleared = array_diff_key($backyear_students_list, $backyear_students_in_module);
				
				foreach($backyear_students_module_cleared as $key => $value){
					unset($student_list[$key]);
				}
				
				//add this to student list
				$backpaper_students = array_diff_key($backpaper_students_in_module, $backyear_students_list);
				foreach($backpaper_students as $key => $value){
					$student_list[$key] = $value;
				}*/
				
			}
		}
		
		return $student_list;
	}
	
	/*
	* To List the Students to add Examination Marks
	* Different from getStudentList - need to check if there is an examination code or not.
	*/
	
	public function getStudentExaminationList($academic_modules_allocation_id, $section, $programmesId, $batch)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		//$semester_type = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$semester = $this->getSemesterForModule($academic_modules_allocation_id);
		$student_list = array();
         
		//first check if there is a secret code generated
		//not getting programme id. need to check why
		$secret_code = $this->checkExamCodeGeneration($academic_modules_allocation_id, $programmesId);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($secret_code){
			$select->from(array('t1' => 'student_examination_code'));
			$select->columns(array('id', 'student_id' =>'examination_code'));
			if($programmesId){
				$select->where(array('programmes_id' =>$programmesId));
			}
			if($academic_modules_allocation_id){
				$select->where(array('academic_modules_id' =>$academic_modules_allocation_id));
			}
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);	 
		} else{
			$student_list = $this->getStudentList($studentName=NULL, $section, $academic_modules_allocation_id,$programmesId, $batch, $marks_for = 'semester_exams',$status = NULL);
			return $student_list;
		}
		
	}
	
	/*
	* Get the academic session given module id and programme id
	*/
	
	private function getAcademicSessionForAllocation($module_code, $programmes_id)
	{
		$academic_session_id = NULL;
		$academic_session = NULL;
		$module_semester = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'programmes'))
                            ->columns(array('academic_session_id'))
                        ->join(array('t2' => 'academic_session'), 
                            't1.academic_session_id = t2.id', array('academic_session'));
		$select->where(array('t1.id' =>$programmes_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$academic_session_id = $set['academic_session'];
		}
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		
		$select2->from(array('t1' => 'academic_modules'))
                            ->columns(array('module_semester'));
		$select2->where(array('t1.programmes_id' =>$programmes_id));
		$select2->where->like('t1.module_code','%'.$module_code.'%');

		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set){
			$module_semester = $set['module_semester'];
		}
		
		if((($module_semester%2) != 0) && $academic_session_id == 'Autumn Semester'){
			return $academic_session = 'Autumn';
		} else if ((($module_semester%2) == 0) && $academic_session_id == 'Autumn Semester') {
			return $academic_session = 'Spring';
		} else if ((($module_semester%2) != 0) && $academic_session_id == 'Spring Semester') {
			return $academic_session = 'Spring';
		} else if ((($module_semester%2) == 0) && $academic_session_id == 'Spring Semester') {
			return $academic_session = 'Autumn';
		}
	}
	
	/*
	* List of students for display
	*/
	
	public function getBasicStudentNameList($programme, $academic_year, $semester, $section)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programme);
		//$semester_type = $this->getSemester($organisation_id);
		//here academic year is the academic year the consolidated marks is requested for
		//current academic year stores present academic year
		//$current_academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);

		$student_list = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($section =='All'){
			$select->from(array('t1' => 'student'))
						->columns(array('id','student_id','first_name','middle_name','last_name'))
					->join(array('t2' => 'student_consolidated_marks'), 
						't1.student_id = t2.student_id', array('module_code'))
					->join(array('t3' => 'student_semester_registration'), 
                                    't1.id = t3.student_id', array('student_section_id'))
					->join(array('t4' => 'student_section'), 
						't3.student_section_id = t4.id', array('section'))
					->where(array('t2.academic_year ' => $academic_year))
					->where(array('t1.programmes_id = ' .$programme))
					->where(array('t2.semester' =>$semester));
		} else {
			$select->from(array('t1' => 'student'))
						->columns(array('id','student_id','first_name','middle_name','last_name'))
					->join(array('t2' => 'student_consolidated_marks'), 
						't1.student_id = t2.student_id', array('module_code'))
					->join(array('t3' => 'student_semester_registration'), 
                                    't1.id = t3.student_id', array('student_section_id'))
					->join(array('t4' => 'student_section'), 
						't3.student_section_id = t4.id', array('section'))
					->where(array('t2.academic_year ' => $academic_year))
					->where(array('t1.programmes_id = ' .$programme))
					->where(array('t4.id = ' .$section))
					->where(array('t2.semester' =>$semester));

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
	
	/*
	* List of students by programme
	*/
	
	public function getStudentListByYear($student_name, $student_id, $programme)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
                            ->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
                        ->join(array('t4' => 'programmes'), 
                            't1.programmes_id = t4.id', array('programme_name'));
        if($student_name){
			$select->where(array('t1.first_name' =>$student_name));
		}            
		if($programme){
			$select->where(array('programmes_id' =>$programme));
		}
		if($student_id){
			$select->where(array('t1.student_id' =>$student_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/*
	* To view Examination Marks
	*/
	
	public function getExaminationMarks($academic_modules_allocation_id, $programmesId, $batch, $section)
	{
		$assessment_type = 'Semester Exams';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
                                     ->columns(array('first_name','middle_name','last_name', 'student_id'))
                                ->join(array('t2' => 'student_semester_registration'), 
                                    't1.id = t2.student_id', array('student_section_id'))
                                ->join(array('t3' => 'student_section'), 
                                    't2.student_section_id = t3.id', array('section'))
								->join(array('t4' => 'assessment_marks'), 
                            		't1.id = t4.student_id', array('marks'))
								->join(array('t5' => 'academic_assessment'), 
                            		't4.academic_assessment_id = t5.id', array('assessment_component_id'))
								->join(array('t6' => 'assessment_component'), 
                            		't5.assessment_component_id = t6.id', array('academic_modules_allocation_id'));
        $select->where(array('t3.id' =>$section));
		$select->where->like('t4.assessment_type','%'.$assessment_type.'%');
		$select->where(array('t6.academic_modules_allocation_id' =>$academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);		 
	}
	
	/*
	* To get the list of students in order to "ADD" Marks
	* Different from getStudentExamination List as previous function is to only get list of students
	* This is while adding the marks
	* not able to use the previous function as examination_code is mapped as student_id
	*/
	
	public function getExaminationMarkEntryList($continuous_assessment_id, $batch, $programmesId, $section)
	{	
		$organisation_id = $this->getOrganisationIdByProgramme($programmesId);
		//$semester_type = $this->getSemester($organisation_id);
		
		//Get the semester numbers depending on the start of academic session
		// 1 :- Spring Session
		// 2 :- Autumn Session 
		
		$semester = $this->getSemesterArray($semester, $programmes_id);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        //$academic_year = $this->getAcademicYear($academic_event_details);

			
		//get the academic_modules_allocation_id;
		$academic_modules_allocation_id = $this->getAcademicAllocationModuleId($continuous_assessment_id);
		//check if there is a secret code generated
		//not getting programme id. need to check why
		$secret_code = $this->checkExamCodeGeneration($academic_modules_allocation_id, $programmesId);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($secret_code){
			$select->from(array('t1' => 'student_examination_code'));
			$select->columns(array('id','student_id','examination_code'));
			if($programmesId){
				$select->where(array('programmes_id' =>$programmesId));
			}
			if($academic_modules_allocation_id){
				$select->where(array('academic_modules_id' =>$academic_modules_allocation_id));
			}
		} else{
			$select->from(array('t1' => 'student'))
                                     ->columns(array('id','student_id'))
                                ->join(array('t2' => 'student_semester_registration'), 
                                    't1.id = t2.student_id', array('student_section_id'))
                                ->join(array('t3' => 'student_section'), 
                                    't2.student_section_id = t3.id', array('section'));
			$select->where(array('t2.semester_id' => $semester));
			if($programmesId){
				$select->where(array('t1.programmes_id' =>$programmesId));
			}
			if($batch){
				$select->where(array('t1.enrollment_year' =>$batch));
			}
            if($section){
                    $select->where(array('t3.id' =>$section));
            }
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Check to see whether an assessment component has been assigned for an academic year
	*
	* Gets an array
	*/
	
	public function checkAssessmentComponent($data)
	{
		$programmes_id = $data['programmes_id'];
        $assessment_component_types_id = $data['assessment_component_types_id'];
        $academic_modules_id = $data['academic_modules_id'];
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_assessment')) ;
		$select->where('t1.academic_modules_id = ' .$academic_modules_id);
		$select->where('t1.assessment_component_types_id = ' .$assessment_component_types_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$component_check = 0;
		foreach($resultSet as $set){
			$component_check = $set['id'];
		}
		return $component_check;
	}
	
	/*
	* Check if Examination Code has been generated for a module
	*/
	
	public function checkExamCodeGeneration($academic_modules_id, $programmes_id)
	{
		$present_month = date('m');
		$present_month = 12;

		if ($present_month == 1) {
			$from_date = (date('Y')-1).'-11-01';
		} else if($present_month == 2) {
			$from_date = (date('Y')-1).'-12-01';
		} else {
			$from_date = date('Y').'-'.($present_month-2).'-01';
		}

		if ($present_month == 12) {
			$to_date = (date('Y')+1).'-01-30';
		} else {
			$to_date = date('Y').'-'.($present_month+1).'-30';
		}
		

		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_examination_code')) ;
		//$select->where('t1.programmes_id = ' .$programmes_id);
		$select->where('t1.academic_modules_id = ' .$academic_modules_id);
		$select->where(array('code_date >= ? ' => $from_date));
		$select->where(array('code_date <= ? ' => $to_date));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$component_check = 0;
		foreach($resultSet as $set){
			$component_check = $set['id'];
		}
		return $component_check;
	}
	
	/*
	* Get the academic module allocation id, given continuous assessment id
	* used by when entering the semester marks 
	* (as academic module id is not passed, we will not be able to get the student list for exam mark entry)
	*/
	
	public function getAcademicAllocationModuleId($continuous_assessment_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_assessment')) 
                    ->join(array('t2' => 'assessment_component'), 
                            't1.assessment_component_id = t2.id', array('academic_modules_allocation_id'));
		$select->where('t1.id = ' .$continuous_assessment_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_module_allocation_id = 0;
		foreach($resultSet as $set){
			$academic_module_allocation_id = $set['academic_modules_allocation_id'];
		}
		return $academic_module_allocation_id;
	}
	
	/*
	* Get Academic Module Allocation Id By Academic Module ID
	*/
	
	public function getAcademicAllocationModuleIdByModule($module_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'));
		$select->where('t1.academic_modules_id = ' .$module_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_module_allocation_id = 0;
		foreach($resultSet as $set){
			$academic_module_allocation_id = $set['id'];
		}
		return $academic_module_allocation_id;
	}
	
	/*
	* Get Array of Semester Numbers given a programme and academic session
	* 1- Jan Session 2- July Session
	*/
	
	private function getSemesterArray($academic_session, $programmes_id)
	{
		$semesters = array();
		$academic_session_start = NULL;
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
				
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_session'))
				->join(array('t4' => 'programmes'), 
						't4.academic_session_id = t1.id', array('academic_session_id'));
		$select->where('t4.id = ' .$programmes_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$academic_session_start = $set['id'];
		}
		
		if($academic_session == 'Spring' && $academic_session_start== 1){
			$semesters = array(1,3,5,7,9);
		} else if($academic_session == 'Spring' && $academic_session_start== 2){
			$semesters = array(2,4,6,8,10);
		} else if($academic_session == 'Autumn' && $academic_session_start== 1){
			$semesters = array(2,4,6,8,10);
		} else {
			$semesters = array(1,3,5,7,9);
		}
		return $semesters;
	}
	
	/*
	* Get the list of semesters given an organisation id
	*/
	
	public function getSemesterList($organisation_id)
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
		$semesters = array();
		$index = 1;
		foreach ($resultSet as $res) {
			$tmp_number = $res['max_duration'];
						preg_match_all('!\d+!', $tmp_number, $matches);
						$max_years = implode(' ', $matches[0]);
		}
		//var_dump($max_years); die();

		for($i=1; $i<=($max_years*2); $i++){
				$semesters[$i] = $i ." Semester ";
		}
		return $semesters;
        
	}
        
	/*
	 * Get Academic Modules Allocation ID when allocating module for academic year
	 * (i.e. we need academic_modules_allocation_id for "assessment_component" table
	 * have academic_modules_id
	 */
	
	public function getModuleAllocationId($module_id, $academic_year)
	{
		$academic_module_id = 0;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
                                ->columns(array('id', 'academic_modules_id'))
                            ->join(array('t2' => 'academic_modules'), 
                                't1.academic_modules_id = t2.id', array('module_code'));
		$select->where(array('t2.id' =>$module_id));
		$select->where(array('t1.academic_year' =>$academic_year));

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
	* Get the module code given academic_modules_allocation_id
	*/
	
	private function getModuleCode($academic_modules_allocation_id)
	{
		$academic_code = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
                                ->columns(array('module_code'));
		$select->where(array('t1.id' =>$academic_modules_allocation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$academic_code = $set['module_code'];
		}
		
		return $academic_code;
	}
        
	/*
	 * Get Academic Modules Allocation ID when Uploading Module Tutors
	 */
	
	public function getUploadModuleAllocationId($module_code, $programmes_id)
	{
        $academic_module_id = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
                                ->columns(array('id', 'academic_modules_id'))
                            ->join(array('t2' => 'academic_modules'), 
                                't1.academic_modules_id = t2.id', array('module_code'));
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
	 * Get the Programmes ID when inserting into module tutors
	 */
	
	public function getProgrammeId($tableName, $organisation_id, $username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id','programme_leader'))
			->join(array('t2'=>'employee_details'),
						't1.programme_leader = t2.id');
        $select->where('t1.organisation_id = ' .$organisation_id);
        $select->where->like('t2.emp_id', $username);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		$programmes_id = NULL;

		foreach($resultSet as $set){
			$programmes_id['id'] = $set['id'];
			$programmes_id['programme_leader'] = $set['programme_leader'];
		}
		return $programmes_id;
		
	}
	
	private function getBackyearStudentList($semester, $academic_year,$academic_modules_allocation_id, $programmesId, $section)
	{
		$backyear_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
				->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
				->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('student_section_id'))
				->join(array('t3' => 'student_section'), 
					't2.student_section_id = t3.id', array('section'))
				->join(array('t4' => 'programmes'), 
					't1.programmes_id = t4.id', array('programme_name'))
				->join(array('t5' => 'student_repeat_semester_module'),
					't5.student_id = t1.id',  array('academic_module_id'))
				->join(array('t6' => 'academic_modules_allocation'),
					't6.academic_modules_id = t5.academic_module_id',  array('module_code'));
		$select->where(array('t2.semester_id' => $semester));
		$select->where(array('t1.current_status' => 'Repeat Semester'));
		$select->where(array('t1.student_status_type_id' => 1));
		$select->where(array('t2.academic_year' => $academic_year));
		$select->where(array('t6.id' => $academic_modules_allocation_id));
		$select->order('t1.enrollment_year DESC, t1.student_id ASC'); 
		if($programmesId){
			$select->where(array('t1.programmes_id' =>$programmesId));
		}
		
        if($section){
			$select->where(array('t3.id' =>$section));
		}
		
		/*$select->from(array('t1' => 'student_repeat_modules'))
					->join(array('t4' => 'student_backyears'), 
                            't1.student_id = t4.student_id', array('backyear_semester'))
					->join(array('t5' => 'student'), 
                            't4.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
		$select->where(array('t4.backyear_semester' => $semester));
		$select->where(array('t4.backyear_academic_year' => $backyear_academic_year));
		*/
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		
		return $student_list;
	}
	
	/*
	* Get the list of backyear students for a particular module
	*/
	
	private function getBackyearStudentForModule($academic_modules_allocation_id, $semester, $academic_year, $programmesId)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		
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
                            't4.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
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
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		return $student_list;
	}
	
	/*
	* Get the list of students with backpapers
	*/
	
	private function getBackpaperStudentsForModule($academic_modules_allocation_id, $section, $semester, $academic_year, $programmesId, $marks_for)
	{
		$module_code = $this->getAllocatedAcademicModuleCode($academic_modules_allocation_id);
		$backpaper_in = NULL;
		if($marks_for == 'continuous_assessment'){
			$backpaper_in = 'CA';
		} else {
			$backpaper_in = 'SE';
		}
		
		
		$backpaper_academic_year = $this->getPreviousAcademicYear($academic_year);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_title'))
					->join(array('t2' => 'academic_modules'), 
                            't2.id = t1.academic_modules_id', array('module_code'))
					->join(array('t3' => 'student_backpaper_registration'),
							't3.module_code = t2.module_code', array('backpaper_semester'))
					->join(array('t4' => 'student_section'), 
                            't3.section_id = t4.id', array('section'))
					->join(array('t5' => 'student'), 
                            't3.student_id = t5.id', array('first_name', 'middle_name', 'last_name','student_id'));
		$select->where->like('t2.module_code', $module_code);
		$select->where(array('t3.programmes_id' => $programmesId));
		$select->where(array('t1.academic_year' => $academic_year));
		$select->where(array('t3.backpaper_academic_year' => $backpaper_academic_year));
		$select->where(array('t4.id' => $section));
		$select->where(array('t3.backpaper_in' => $backpaper_in));
		$select->where->like('t3.registration_status', "Registered");
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_list = array();
		
		foreach($resultSet as $set){
			$student_list[$set['student_id']] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
		}
		
		return $student_list;
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
	* Get the semester according to academic module allocation
	*/
	
	private function getSemesterForConsolidatedMarks($module_code, $programmes_id)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programmes_id);
		//$semester_session = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_session);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('semester'));
		$select->where(array('module_code' => $module_code));
		$select->where(array('programmes_id' => $programmes_id));
		$select->where(array('academic_session' => $semester_session));
		$select->where(array('academic_year' => $academic_year));
		
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
	* Get the previous academic year
	*/
	
	private function getPreviousAcademicYear($academic_year)
	{
		$years = explode("-", $academic_year);
		return (($years[0]-1)."-".($years[0]));
	}
	
	/*
	* Delete Assigned Module Tutor
	*/
	
	public function deleteModuleTutor($id)
	{
		$action = new Delete('modules_tutors_assignment');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	* Delete Module Coordinator
	*/
	
	public function deleteModuleCoordinator($id)
	{
		$action = new Delete('academic_module_coordinators');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	* Delete Module Tutor Assigned for Academic Year
	*/
	
	public function deleteAcademicYearModuleTutor($id)
	{
		$action = new Delete('academic_module_tutors');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/**
	* @return array/Programme()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id, $username)
	{
        //need to get which part of the year so that we do not mix the enrollment years
		//$semester_type = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_type);

		$academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'programmes' && $organisation_id != NULL)
		{
			$programmes_detail = $this->getProgrammeId($tableName, $organisation_id, $username);
			if ($programmes_detail) {
				$select->from(array('t1' => 'employee_details'))
					->join(array('t2'=>'programmes'),
						't2.programme_leader = t1.id', array('id',$columnName));
        	        $select->where->like('t1.emp_id', $username);
	                $select->where('t2.organisation_id = ' .$organisation_id);
			$select->order(array('t2.programme_name ASC'));
			} else {
				$select->from(array('t1' => 'programmes')) ;
				$select->columns(array('id',$columnName))
		                    ->where('t1.organisation_id = ' .$organisation_id);
				$select->order(array('t1.programme_name ASC'));
			}
		}
		else if($tableName == 'programme_year' && $organisation_id == NULL)
		{
			$select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
		}
		else if($tableName == 'assessment_component' && $organisation_id != NULL)
		{
			$select->from(array('t1' => 'assessment_component')) ;
			$select->columns(array('id',$columnName))
					->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
					->join(array('t3' => 'academic_modules'), 
                            't2.academic_modules_id = t3.id', array('programmes_id'))
					->join(array('t4' => 'programmes'), 
                            't3.programmes_id = t4.id', array('organisation_id'))
                    ->where('t4.organisation_id = ' .$organisation_id);
		}
		else if($tableName == 'academic_modules_allocation' && $organisation_id != NULL)
		{
			$select->from(array('t1' => 'academic_modules_allocation')) ;
			$select->columns(array('id'))
					->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('organisation_id'))
                    ->where(array('t1.academic_year' => $academic_year))
					->where('t3.organisation_id = ' .$organisation_id);
		}
		else if($tableName == 'academic_modules' && $organisation_id != NULL)
		{
			$select->from(array('t1' => $tableName)) 
                    ->columns(array('id', $columnName))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('organisation_id'))
                    ->where('t2.organisation_id = ' .$organisation_id);
		}
		else if($tableName == 'employee_details' && $organisation_id != NULL)
		{
			//here we execute the mysql statement and return it
			// as first name, middle name, last name is needed
			//need to also join with employee title such as professor etc.
			// (this will be done once all employees are assigned their titles)
			$select->from(array('t1' => 'employee_details')) ;
			$select->columns(array('id','first_name', 'middle_name','last_name', 'emp_id'))
                    ->where('t1.organisation_id = ' .$organisation_id)
                    ->where('t1.emp_resignation_id = 0');
			$select->order(array('t1.first_name ASC'));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$selectData = array();
			foreach($resultSet as $set)
			{
				$selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' ' .$set['last_name'].' ('.$set['emp_id'].')';
			}
			return $selectData;
		}
		 else if($tableName =='student_section'){
			$select->from(array('t1' => $tableName))
                                ->columns(array('id', $columnName));
		} else if($tableName =='academic_session'){
			$select->from(array('t1' => $tableName))
                                ->columns(array('id', $columnName));
        }  else if($tableName == 'academic_calendar'){
        	if ($columnName == 'CA'){
        		$academic_event_type = 'CA Marks Compile Duration';
				$select->from(array('t1' => $tableName))
	                ->join(array('t2' => 'academic_calendar_events'), 
	                            't1.academic_event = t2.id', array('academic_event'));
		        $select->where(array('t1.from_date <= ? ' => date('Y-m-d'), 't1.to_date >= ? ' => date('Y-m-d')));
		        $select->where(array('t2.academic_event' => $academic_event_type, 't2.organisation_id' => $organisation_id));

        	} else if ($columnName == 'SE') {
        		$academic_event_type = 'SE Marks Compile Duration';
				$select->from(array('t1' => $tableName))
	                ->join(array('t2' => 'academic_calendar_events'), 
	                            't1.academic_event = t2.id', array('academic_event'));
		        $select->where(array('t1.from_date <= ? ' => date('Y-m-d'), 't1.to_date >= ? ' => date('Y-m-d')));
		        $select->where(array('t2.academic_event' => $academic_event_type, 't2.organisation_id' => $organisation_id));
        	}
        	$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		} else {
				$select->from(array('t1' => $tableName))
							->columns(array('id', $columnName))
									->where('t1.organisation_id = ' .$organisation_id);
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



	public function listSelectData1($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $selectData = array();

        $select->from(array('t1' => $tableName));  
        $select->columns(array('id',$columnName));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        foreach($resultSet as $set)
        {
        	if($tableName == 'student_section'){
        		$selectData['All'] = 'All';
        		$selectData[$set['id']] = $set[$columnName];
        	} else {
        		//echo $tableName; die();
        		$selectData[$set['id']] = $set[$columnName];
        	}
        }
        return $selectData;
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
			$select->where->like('t2.module_title', $ajaxName);
			$select->where('t2.programmes_id = ' .$conditional_id);
		} else if($tableName == 'academic_modules'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.module_title','%'.$ajaxName);
			$select->where('t1.programmes_id = ' .$conditional_id);
		} else if($tableName == 'academic_assessment'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.assessment','%'.$ajaxName);
			$select->where('t1.assessment_component_id = ' .$conditional_id);
		} else {
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.assessment','%'.$ajaxName);
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
    
	public function getModuleTitle($tableName, $ajaxName, $conditional_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName))
				->columns(array('module_title'));
		$select->where->like('t1.module_title','%'.$conditional_id.'%');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$title = NULL;
		
		foreach($resultSet as $set){
			$id = $set['module_title'];
		}
		return $title;
	}
	
	public function getAcademicModules($programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
				->columns(array('id'));
		$select->where('t1.programmes_id = ' .$programmes_id);
		$select->where('t1.academic_year = ' .date('Y'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$modules = array();
		
		foreach($resultSet as $set){
			$modules[$set['id']] = $set['id'];
		}
		return $modules;
	}
	
	public function getAssessmentName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'assessment_component_types'))
				->columns(array('assessment_component_type'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$title = NULL;
		
		foreach($resultSet as $set){
			$title = $set['assessment_component_type'];
		}
		return $title;
	}
	
	public function changeAcademicAssessmentStatus($academic_assessment_id, $section)
	{
		$data['date'] = date('Y-m-d');
		$data['section'] = $section;
		$data['status'] = 'Completed';
		$data['academic_assessment_id'] = $academic_assessment_id;
		
		$action = new Insert('academic_assessment_status');
		$action->values($data);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	public function getEmployeeId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->columns(array('emp_id'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$emp_id = NULL;
		
		foreach($resultSet as $set){
			$emp_id = $set['emp_id'];
		}
		return $emp_id;
	}
	
	/*
	* Returns the id from student given a student identity number
	*/
	
	public function getStudentId($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id'));
		$select->where('t1.student_id = ' .$student_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_id = NULL;
		
		foreach($resultSet as $set){
			$student_id = $set['id'];
		}
		return $student_id;
	}
	
	/*
	* Returns the student_id from student given a id
	*/
	
	public function getStdId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('student_id'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$student_id = NULL;
		
		foreach($resultSet as $set){
			$student_id = $set['student_id'];
		}
		return $student_id;
	}
	
	//Get Module Code Given academic_module_id
	
	public function getAcademicModuleCode($academic_modules_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules'))
					->columns(array('module_code'));
		$select->where('t1.id = ' .$academic_modules_id);
                
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
	
	//Get Module Code Given academic_module_allocation_id
	
	public function getAcademicModuleCodeByAllocation($academic_module_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_modules_allocation'))
					->columns(array('module_code'));
		$select->where('t1.id = ' .$academic_module_allocation_id);
                
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
	
	
	public function getAssignmentAcademicModuleCode($continuous_assessment_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_assessment'))
					->columns(array('assessment_component_id'))
				->join(array('t2' => 'assessment_component'), 
						't1.assessment_component_id = t2.id', array('academic_modules_allocation_id'))
				->join(array('t3' => 'academic_modules_allocation'), 
						't2.academic_modules_allocation_id = t3.id', array('academic_modules_id'))
				->join(array('t4' => 'academic_modules'), 
						't3.academic_modules_id = t4.id', array('module_code'));
		$select->where('t1.id = ' .$continuous_assessment_id);
                
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
		
		/*foreach($resultSet as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester = 'Autumn';
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester = 'Spring';
			}
		}*/
		foreach($result as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
                $semester['academic_year'] = $set['academic_year'];
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
                $semester['academic_year'] = $set['academic_year'];
			}
		}
		//var_dump($semester); die();
		return $semester;
	}
        
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($academic_event_details)
	{
		//$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		//$academic_year = NULL;
		
		if($semester == 'Autumn'){
			$academic_year; // = (date('Y')).'-'.(date('Y')+1);
		} else {
			$academic_year; // = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	/*
	* Get the number of assessment components
	*/
	
	public function getAssessmentComponentNumber($programme_id, $semester)
	{
		$organisation_id = $this->getOrganisationIdByProgramme($programme_id);
		//$semester_session = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester_session);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//$select->from(array('t1' => 'academic_modules_allocation'))
		//			->join(array('t3' => 'academic_modules'), 
                  //          't1.academic_modules_id = t3.id', array('module_title','module_code','module_credit'))
		//			->join(array('t2' => 'programmes'), 
                  //          't2.id = t3.programmes_id', array('programme_name'))
		//			->join(array('t4' => 'academic_modules_assessment'), 
                  //          't3.id = t4.academic_modules_id', array('assessment', 'weightage'));
		//$select->where(array('t1.programmes_id = ' .$programme_id));
		//$select->where(array('t1.semester = ' .$semester));
		//$select->where(array('t1.academic_year' => $academic_year));
		$select->from(array('t1' => 'academic_modules_allocation'),array('module_title','module_code'))
					->join(array('t3' => 'academic_modules'), 
                            't1.academic_modules_id = t3.id', array('module_credit'))
					->join(array('t2' => 'programmes'), 
                            't2.id = t3.programmes_id', array('programme_name'))
					->join(array('t4' => 'assessment_component'), 
                            't1.id = t4.academic_modules_allocation_id', array('assessment', 'weightage'));
		$select->where(array('t1.programmes_id = ' .$programme_id));
		$select->where(array('t1.semester = ' .$semester));
		$select->where(array('t1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$components = array();
		
		foreach($resultSet as $set){
			$components[$set['assessment']] = $set['assessment'];
		}
		
		return $components;
	}
	
	/*
	* Crosscheck whether module has been assigned to tutor
	*/
	
	private function crossCheckAcademicModuleTutorAssignment($module_tutor, $academic_modules_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'modules_tutors_assignment'));
		$select->where(array('t1.employee_details_id' => $module_tutor));
		$select->where(array('t1.academic_modules_id' => $academic_modules_id));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assignment = 0;
		
		foreach($resultSet as $set){
			$assignment = $set['id'];
		}
		
		return $assignment;
	}
	
	/*
	* Crosscheck whether a module has been assigned to a tutor for academic year
	* 
	* Here the $key value is section
	*/
	
	private function crossCheckModuleAssignment($academic_year, $key, $module_tutor, $academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_module_tutors'));
		$select->where(array('t1.year' => $academic_year));
		$select->where(array('t1.section' => $key));
		$select->where(array('t1.module_tutor' => $module_tutor));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assignment = 0;
		
		foreach($resultSet as $set){
			$assignment = $set['id'];
		}
		
		return $assignment;
	}
	
	/*
	* Get the modules assigned to module tutor for academic year
	*/
	
	private function getModuleAssignmentAcademicYear($academic_year, $module_tutor, $academic_modules_allocation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_module_tutors'))
					->columns(array('academic_module_tutors_id' => 'id', 'section'))
					->join(array('t2' => 'employee_details'),
								't1.module_tutor = t2.emp_id', array('id'));
		$select->where(array('t1.year' => $academic_year));
		$select->where(array('t1.module_tutor' => $module_tutor));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id));
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$assignment = array();
		
		foreach($resultSet as $set){
			$assignment[$set['section']][$set['id']] = $set['academic_module_tutors_id'];
		}
		
		return $assignment;
	}
	
	/*
	* delete the modules assigned to module tutor for academic year
	*/
	
	private function deleteModuleAssignmentAcademicYear($academic_module_tutors_id)
	{
		$action = new Delete('academic_module_tutors');
		$action->where(array('id = ?' => $academic_module_tutors_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	* Update the Compiled Table to ensure that modules are not compiled again
	*/
	
	private function updateCompiledTable($academic_modules_allocation_id, $section, $assessment_for,$assessment_type)
	{
		//var_dump($assessment_type); die();
		//var_dump($academic_modules_allocation_id); die();
		//$assessment_type = NULL;
		//if($assessment_for == 'Continuous Assessment'){
			//$assessment_type = 'CA';
		//} else {
			//$assessment_type = 'SE';
		//}
		//$data['type'] = substr($assessment_type, 0,2);
		$data['type'] = $assessment_type;
		$data['entry_date'] = date('Y-m-d');
		$data['section'] = $section;
		$data['status'] = 'Compiled';
		$data['academic_modules_allocation_id'] = $academic_modules_allocation_id;
		
		$action = new Insert('compiled_marks_status');
		$action->values($data);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$data['id'] = $newId;
			}
			return;
		}
		throw new \Exception("Database Error");
	}


	public function getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

         $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id', 'student_section_id', 'academic_year'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t1.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester'),
                    't4.id = t2.semester_id', array('semester'))
               ->join(array('t5' => 'student_section'),
                    't5.id = t2.student_section_id', array('section'))
               ->join(array('t6' => 'gender'),
                    't6.id = t1.gender', array('stdgender' => 'gender'))
               ->join(array('t7' => 'student_type'),
                    't7.id = t1.scholarship_type', array('student_type'))
               ->join(array('t8' => 'student_status_type'),
                    't8.id = t1.student_status_type_id', array('reason'))
               ->join(array('t9' => 'programme_year'),
                    't9.id = t2.year_id', array('year'))
               ->where(array('t1.programmes_id' => $programmesId, 't2.year_id' => $yearId, 't2.academic_year' => $academicYear, 't1.student_status_type_id' => '1'));
	$select->order('t5.section ASC','t1.first_name ASC', 't1.student_id ASC');
               
               //->where->notLike('t1.student_id', "TEMP_%");

        if($studentName){
            $select->where->like('first_name','%'.$studentName.'%');
            $select->where(array('t1.programmes_id = ?' => $programmesId));
        }
        if($studentId){
            $select->where(array('t1.student_id' =>$studentId));
            $select->where(array('t1.programmes_id = ?' => $programmesId));
        }
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result); 
	}


	public function updateGraduatedStudent($student_data, $programmesId, $yearId, $academicYear, $studentName, $studentId, $organisation_id)
	{ 
		$i = 1;
        $studentIds = array();
        $studentData = $this->getGraduatingStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId);

        foreach($studentData as $value)
        {
           // $studentIds[$i++] = $value['id'];
            $studentIds[$i++] = $value['id'];
        } 

        if($student_data != NULL)
        {
            $i = 1;

            foreach($student_data as $data)
            {
                $this->updateStudentStatus($data, $studentIds[$i]);
                $i++;
            }
            return;
        }
	}


	public function updateStudentStatus($data, $id)
    {
        if($data == '1')
        {  
           $student_status_type_id = '7';

            $student_data = array();
            $studentDetails = $this->getStudentData($tableName = 'student', $id);
            foreach($studentDetails as $key => $value){
            	$student_data = $value;
            }

            $action = new Update('student');
            $action->set(array('cid' => $student_data['student_id'], 'cid_graduate' => $student_data['cid'],'student_status_type_id' => $student_status_type_id));
            $action->where(array('id = ?' => $id));

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            // To get the graduated student details from student table 
            $studentData = array();
            $studentData = $this->getStudentData($tableName = 'student', $id);

            $this->addNewAlumniData($studentData);
        }
        return;
    }


    public function getStudentData($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('id', 'first_name', 'middle_name', 'last_name', 'student_id', 'gender', 'date_of_birth', 'cid', 'contact_no', 'email', 'programmes_id', 'organisation_id'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.id = ?' => $id));
        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       // $id = NULL;
        $studentData = array();
        
        foreach($resultSet as $set)
        {
           // $id = $set['first_name'];
          //   $id = $set['middle_name'];
            $studentData[$set['id']]['id'] = $set['id'];
            $studentData[$set['id']]['first_name'] = $set['first_name'];
            $studentData[$set['id']]['middle_name'] = $set['middle_name'];
            $studentData[$set['id']]['last_name'] = $set['last_name'];
            $studentData[$set['id']]['student_id'] = $set['student_id'];
            $studentData[$set['id']]['gender'] = $set['gender'];
            $studentData[$set['id']]['date_of_birth'] = $set['date_of_birth'];
            $studentData[$set['id']]['cid'] = $set['cid'];
            $studentData[$set['id']]['contact_no'] = $set['contact_no'];
            $studentData[$set['id']]['email'] = $set['email'];
            $studentData[$set['id']]['programmes_id'] = $set['programmes_id'];
            $studentData[$set['id']]['organisation_id'] = $set['organisation_id'];
        }
        return $studentData;
    }


    public function addNewAlumniData($studentData)
    { 
    	foreach ($studentData as $key => $value) {
    		$action = new Insert('alumni');
	        $action->values(array(
	            'first_name' => $value['first_name'],
	            'middle_name' => $value['middle_name'],
	            'last_name' => $value['last_name'],
	            'cid' => $value['cid'],
	            'student_id' => $value['student_id'],
	            'gender' => $value['gender'],
	            'date_of_birth' => $value['date_of_birth'],
	            'contact_no' => $value['contact_no'],
	            'email_address' => $value['email'],
	            'graduation_year' => date('Y'),
	            'alumni_status' => 'Active',
	            'alumni_Type' => 'Present',
	            'alumni_programmes_id' => $value['programmes_id'],
	            'organisation_id' => $value['organisation_id'],
	            
	        ));
	        
	        $sql = new Sql($this->dbAdapter);
	        $stmt = $sql->prepareStatementForSqlObject($action);
	        $result = $stmt->execute();

	        $this->updateAlumniUser($value['student_id'], $value['cid'], $value['date_of_birth'], $value['organisation_id']);
    	}

  		return;
    }


    public function updateAlumniUser($student_id, $student_cid, $student_dob, $organisation_id)
    { 
    	$abbr = $this->getOrganisationAbbr($organisation_id);

        $action = new Update('users');
        $action->set(array(
            'username' => $student_cid,
            'password' => md5($student_dob),
            'role' => $abbr.'_ALUMNI',
            'region' => $organisation_id,
            'user_type_id' => '5',
        )); 
        $action->where(array('username = ?' => $student_id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
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

    public function getOrganisationDocument($tableName, $document_type, $organisation_id)
	{	
		$sql = new Sql($this->dbAdapter);

		if($tableName == 'organisation_document'){
			$img_location = NULL;

			$select = $sql->select();

			$select->from(array('t1' => $tableName))
		       ->where(array('t1.document_type' => $document_type, 't1.organisation_id' => $organisation_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$img_location = $set['documents'];
			}
			return $img_location;
		} 
		else if($tableName == 'organisation'){ 
			$select = $sql->select();

			$select->from(array('t1' => $tableName))
		       ->where(array('t1.id' => $organisation_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}
}
