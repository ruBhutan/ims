<?php

namespace Discipline\Mapper;

use Discipline\Model\Discipline;
use Discipline\Model\DisciplineCategory;
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

class ZendDbSqlMapper implements DisciplineMapperInterface
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
	 * @var \Discipline\Model\DisciplineInterface
	*/
	protected $disciplinePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $disciplinePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->disciplinePrototype = $disciplinePrototype;
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
	* @return Discipline
	* @throws \InvalidArgumentException
	*/
	
	public function findStudent($id)
	{
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student')); 
		$select->where(array('id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @return array/Discipline()
	*/
	public function findAll($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('t1.organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
        
	/**
	 * 
	 * @param type $DisciplineInterface
	 * 
	 * to save  Details
	 */
	
	public function saveDetails(DisciplineCategory $disciplineObject)
	{
		$disciplineData = $this->hydrator->extract($disciplineObject);
		unset($disciplineData['id']);
		
		if($disciplineObject->getId()) {
			//ID present, so it is an update
			$action = new Update('discipline_category');
			$action->set($disciplineData);
			$action->where(array('id = ?' => $disciplineObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('discipline_category');
			$action->values($disciplineData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $disciplineObject->setId($newId);
			}
			return $disciplineObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the disciplinary record of a student
	*/
	
	public function saveDisciplinaryRecord(Discipline $disciplineObject)
	{
		$disciplineData = $this->hydrator->extract($disciplineObject);
		unset($disciplineData['id']);
                
                //need to get the file locations and store them in database
		$evidence_file_name = $disciplineData['evidence_File'];
		$disciplineData['evidence_File'] = $evidence_file_name['tmp_name'];
		$disciplineData['record_Date'] = date("Y-m-d", strtotime(substr($disciplineData['record_Date'],0,10)));
		
		if($disciplineObject->getId()) {
			//Find the evidence file link if the current upload link is null
			if($disciplineData['evidence_File'] == NULL){
				$disciplineData['evidence_File'] = $this->getUploadedEvidenceFile($disciplineObject->getId());
			}else{
				$disciplineData['evidence_File'] = $disciplineData['evidence_File'];
			}
			//ID present, so it is an update
			$action = new Update('student_disciplinary_record');
			$action->set($disciplineData);
			$action->where(array('id = ?' => $disciplineObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_disciplinary_record');
			$action->values($disciplineData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $disciplineObject->setId($newId);
			}
			return $disciplineObject;
		}
		
		throw new \Exception("Database Error");
	}

	
	public function getUploadedEvidenceFile($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_disciplinary_record'));
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result); 

		$uploaded_file = NULL;
		foreach($resultSet as $set){
			$uploaded_file = $set['evidence_file'];
		}
		return $uploaded_file;
	}
	
	/*
	* List Student to add awards etc
	*/
	
	public function getStudentList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
				->where(array('t1.student_status_type_id' => '1'));
		
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
	* Get the list of disciplinary action of students after search funcationality
	*/
	
	public function getStudentDisciplinaryList($studentName, $studentId, $programme, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_disciplinary_record')) 
                    ->columns(array('disciplinary_details','record_date','recorded_by'))
					->join(array('t4' => 'discipline_category'), 
                            't1.discipline_category_id = t4.id', array('discipline_category'))
					->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('programme_name'));
		$select->group(array('t1.student_id'));
		$select->where(array('t2.organisation_id' =>$organisation_id));
		
		if($studentName){
			$select->where->like('t2.first_name','%'.$studentName.'%');
		}
		if($studentId){
			$select->where(array('t2.student_id' =>$studentId));
		}
		if($programme){
			$select->where(array('t2.programme' =>$programme));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find Category details to edit/display
	 */
	public function getDisciplineCategoryDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'discipline_category')); 
		$select->where(array('id = ?' => $id));

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
		$select->from(array('t1' => 'student')) 
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get the disciplinary record of the students
	*/
	
	public function getDisciplinaryRecord($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student_disciplinary_record')) 
					->columns(array('disciplinary_details','record_date','recorded_by'))
					->join(array('t2' => 'discipline_category'), 
                            't1.discipline_category_id = t2.id', array('discipline_category'))
                    ->join(array('t3'=>'student'),
                            't1.student_id = t3.id', array('id','first_name','middle_name','last_name','student_id','enrollment_year'))
					->join(array('t4'=>'programmes'),
							't3.programmes_id= t4.id' , array('programme_name'))
                    ->where(array('t3.organisation_id = ' .$organisation_id))
                    ->group(array('t1.student_id'));
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the list of disciplinary records by a student
	*/
	
	public function getStudentDisciplinaryRecords($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_disciplinary_record')) 
					->join(array('t2' => 'discipline_category'), 
                            't1.discipline_category_id = t2.id', array('discipline_category'))
                    ->join(array('t3'=>'student'),
                            't1.student_id = t3.id', array('first_name','middle_name','last_name', 'std_enrol_id' => 'student_id','enrollment_year'))
					->join(array('t4'=>'programmes'),
							't3.programmes_id= t4.id' , array('programme_name'))
					->join(array('t5'=>'employee_details'),
							't1.recorded_by= t5.id' , array('staff_first_name'=>'first_name','staff_last_name'=>'last_name', 'emp_id'));
		$select->where(array('t1.student_id' =>$student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}


	public function getRecordedDisciplinaryRecordDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'student_disciplinary_record')) 
					->join(array('t2' => 'discipline_category'), 
                            't1.discipline_category_id = t2.id', array('discipline_category'))
                    ->join(array('t3'=>'student'),
                            't1.student_id = t3.id', array('first_name','middle_name','last_name', 'std_enrol_id' => 'student_id','enrollment_year'))
					->join(array('t4'=>'programmes'),
							't3.programmes_id= t4.id' , array('programme_name'))
					->join(array('t5'=>'employee_details'),
							't1.recorded_by= t5.id' , array('staff_first_name'=>'first_name','staff_last_name'=>'last_name', 'emp_id'));
		$select->where(array('t1.id' =>$id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Discipline()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 
		$select->where(array('t1.organisation_id' =>$organisation_id));

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