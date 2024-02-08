<?php

namespace StudentAdmission\Mapper;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\RegisterStudent;
use StudentAdmission\Model\UpdateStudent;
use StudentAdmission\Model\UpdateReportedStudentDetails;
use StudentAdmission\Model\AddNewStudent;
use StudentAdmission\Model\StudentType;
use StudentAdmission\Model\StudentHouse;
use StudentAdmission\Model\StudentCategory;
use StudentAdmission\Model\UploadStudentLists;
use StudentAdmission\Model\StudentSemesterRegistration;
use StudentAdmission\Model\UpdateStudentPersonalDetails;
use StudentAdmission\Model\UpdateStudentPermanentAddr;
use StudentAdmission\Model\UpdateStudentParentDetails;
use StudentAdmission\Model\UpdateStudentGuardianDetails;
use StudentAdmission\Model\StudentPreviousSchool;
use StudentAdmission\Model\StudentRelationDetails;
use StudentAdmission\Model\UpdateStudentPreviousSchool;
use StudentAdmission\Model\StudentChangeProgramme;
use StudentAdmission\Model\UpdateChangeProgramme;
use StudentAdmission\Model\StudentFeeDetails;
use StudentAdmission\Model\StudentFeePaymentDetails;


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
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentAdmissionMapperInterface
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
	 * @var \StudentAdmission\Model\StudentAdmissionInterface
	*/
	protected $studentAdmissionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentAdmission $studentAdmissionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->studentAdmissionPrototype = $studentAdmissionPrototype;
	}

    /*
    * Getting the id for username
    */
    
    public function getUserDetailsId($tableName, $username)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'employee_details'){
            $select->from(array('t1' => $tableName));
            $select->where(array('emp_id' =>$username));
            $select->columns(array('id'));
        }

        else if($tableName == 'student'){
            $select->from(array('t1' => $tableName));
            $select->where(array('student_id' =>$username));
            $select->columns(array('id'));
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    /*
    * Get organisation id based on the username
    */
    
    public function getOrganisationId($tableName, $username)
    {
        $tableName;
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'employee_details'){
            $select->from(array('t1' => $tableName));        
            $select->where(array('emp_id' =>$username));
            $select->columns(array('organisation_id'));
        }

        else if($tableName == 'student'){
            $select->from(array('t1' => $tableName));        
            $select->where(array('student_id' =>$username));
            $select->columns(array('organisation_id'));
        }
            
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
	* @return StudentAdmission
	* @throws \InvalidArgumentException
	*/
	public function findRegisteredStudent($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('student_registration');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
	}
	
	
	public function findAllRegisteredStudent()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
          
            $select->from(array('t1' => 'student_registration'))
            ->join(array('t2' => 'programmes'),
               't2.id = t1.programme_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                     't3.id = t1.organisation_id', array('organisation_name'))
                     ->join(array('t4' => 'student_type'), // join table with alias
                    	 't4.id = t1.student_type_id', array('student_type'));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
    
        
        public function findRegisteredStudentDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'student_registration')) //base table
                   /* ->join(array('t2' => 'student'),
                        't1.id = t2.student_registration_id', array('student_id', 'joining_date'))*/
                    ->join(array('t3' => 'student_type'),
                        't3.id = t1.student_type_id', array('student_type'))
                    ->join(array('t4' => 'programmes'),
                        't4.id = t1.programme_id', array('programme_name'))
                    ->join(array('t5' => 'organisation'),
                        't5.id = t1.organisation_id', array('organisation_name'))
                    ->join(array('t6' => 'gender'),
                        't6.id = t1.gender', array('gender'))
                    ->join(array('t7' => 'relation_type'),
                        't7.id = t1.relationship_id', array('relation'))
                    ->where(array('t1.id = ' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }
		
	
	
		public function saveRegisteredStudent(RegisterStudent $registerStudentObject, $organisation_id, $programme_id)
		{
		$registerStudentData = $this->hydrator->extract($registerStudentObject);
		unset($registerStudentData['id']);

        $registerStudentData['organisation_Id'] = $organisation_id;

        //get the id of the Programme Name
        $registerStudentData['programme_Id'] = $programme_id;

        $registerStudentData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($registerStudentData['date_Of_Birth'],0,10)));
		
		if($registerStudentObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_registration');
			$action->set($registerStudentData);
			$action->where(array('id = ?' => $registerStudentObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_registration');
			$action->values($registerStudentData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $registerStudentObject->setId($newId);
			}
			return $registerStudentObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function findUpdatedStudent($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student_registration'))
            ->join(array('t2' => 'programmes'),
               't2.id = t1.programme_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                     't3.id = t1.organisation_id', array('organisation_name'))
                     ->join(array('t4' => 'student_type'), // join table with alias
                         't4.id = t1.student_type_id', array('student_type'))

                         ->where(array('t1.id =' .$id));  //join expression; // 
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
	}


    public function findNewStudentList($stdProgramme)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_registration'))
            ->join(array('t2' => 'programmes'),
            't2.id = t1.programme_id', array('programme_name'))
            ->join(array('t3' => 'student_type'),
            't3.id = t1.student_type_id', array('student_type'))
            ->join(array('t4' => 'gender'),
            't4.id = t1.gender', array('gender'))
            ->where(array('t1.admission_year = YEAR(NOW())', 't1.student_reporting_status = ?' => 'Pending', 't1.programme_id = ' .$stdProgramme))
            ->order(array('t1.first_name ASC', 't1.middle_name ASC', 't1.last_name ASC'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result); 

    }


    // To get the new reported student list
    public function findReportedStudentList($stdProgramme)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
            't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'student_type'),
            't3.id = t1.scholarship_type', array('student_type'))
            ->join(array('t4' => 'gender'),
            't4.id = t1.gender', array('gender'))
           // ->where->like('t1.student_id', $temp.'%')
            ->where(array('t1.enrollment_year = YEAR(NOW())', 't1.programmes_id = ' .$stdProgramme, 't1.student_status_type_id' => '1'))
            
            ->order(array('first_name ASC', 'middle_name ASC', 'last_name ASC'))
            ->where->like('t1.student_id', "TEMP_%");

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getGeneratedStudentIdList($organisation_id)
    {
       // $temp = "TEMP_";
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
            't2.id = t1.programmes_id', array('programme_name'))
            ->join(array('t3' => 'student_type'),
            't3.id = t1.scholarship_type', array('student_type'))
            ->join(array('t4' => 'gender'),
            't4.id = t1.gender', array('gender'))
            //->where->notLike('t1.student_id', 'TEMP_%')
            ->where(array('t1.enrollment_year = YEAR(NOW())', 't1.organisation_id = ?' => $organisation_id))
             ->order(array('t1.student_id ASC'))
            ->where->notLike('t1.student_id', "TEMP_%");

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function crossCheckStudentNotAssignedId($stdProgramme)
    {
       $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'));
        $select->where(array('t1.programmes_id' => $stdProgramme, 't1.student_status_type_id' => '1'))
               ->where->like('t1.student_id', "TEMP_%");
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $studentID = 0;
        foreach($resultSet as $set){
            $studentID = $set['id'];
        }
        return $studentID; 
    }


    public function assignStudentId($stdProgramme, $organisation_id)
    {
        $studentData = array();
        $studentList = $this->findReportedStudentList($stdProgramme);
        $i = 0;

        foreach($studentList as $key => $value)
        {
            $studentData[$i++] = $value['id'];

        }
		

        if($studentData != NULL){

            $i = 0;

            foreach($studentData as $key => $value){

            // Generate student id to the newly added student
            $student_id = $this->generateStudentId($organisation_id);
            $studentAdmissionData['student_id'] = $student_id;

            $studentDOB = $this->getStudentDateOfBirth($tableName = 'student', $value);
            $studentAdmissionData['date_of_birth'] = $studentDOB;

            $action = new Update('student');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentData[$i]));
                
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute(); 
            $i++;

            //after generating student id, it will auto generate username and password for the particular student
           $this->addNewUser($studentAdmissionData['student_id'], $studentAdmissionData['date_of_birth'], $organisation_id);
        }
    }
            
    }


    public function getStudentDateOfBirth($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        
        //if($tableName == 'item_sub_category'){
            $select->from(array('t1' => $tableName));
            $select->columns(array('date_of_birth'));
            //$select->where->like('id = ?' => $code);
            $select->where(array('t1.id = ?' => $id));
        //}
        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       // $id = NULL;
        
        foreach($resultSet as $set)
        {
           $id = $set['date_of_birth'];
        }
        return $id;
    }


    /*
    *To get new reported student to update their information
    **/
    public function getReportedStudentList($stdName, $stdCid, $stdProgramme, $admissionYear)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'student_id', 'cid', 'gender', 'enrollment_year', 'dzongkhag'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'gender'),
                    't4.id = t1.gender', array('student_gender' => 'gender'))
               ->where(array('t1.programmes_id' => $stdProgramme, 't1.enrollment_year' => $admissionYear))
               ->where->like('t1.student_id', "TEMP_%");                          
        
        if($stdName){
            $select->where->like('first_name','%'.$stdName.'%');
            $select->where(array('t1.programmes_id' => $stdProgramme));
        }
        if($stdCid){
            $select->where(array('cid' =>$stdCid));
            $select->where(array('t1.programmes_id' => $stdProgramme));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    // To get the list of new reported student list
    public function getNewReportedStudentList($programmesId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_semester_registration'))
               ->join(array('t2' => 'student'),
                    't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'studentId' =>'student_id', 'programmes_id'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t2.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester'),
                    't4.id = t1.semester_id', array('semester'))
               ->where(array('t2.enrollment_year = YEAR(NOW())', 't1.student_section_id is NULL', 't2.programmes_id' => $programmesId));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getStudentHouseList($programmesId, $yearId, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getCurrentAcademicYear($academic_event_details);

        $academic_session = $this->getAcademicSession($organisation_id);

        $student_list = array();

        $student_lists = $this->getSemesterStudentList($programmesId, $yearId, $academic_session);

        $assigned_house_student_list = $this->getAssignedHouseStudentList($programmesId, $yearId, $academic_session);

        //Remove student list from $student_lists by $aassigned_house_student_list
        $unassigned_student_list = array_diff_key($student_lists, $assigned_house_student_list);

        foreach($unassigned_student_list as $key => $value){
            $student_list[$key] = $value;
        } 

        return $student_list;

        /*$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t1.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester'),
                    't4.id = t2.semester_id', array('semester'))
               ->join(array('t5' => 'programme_year'),
                    't5.id = t2.year_id', array('year'))
               ->where(array('t1.enrollment_year = YEAR(NOW())', 't1.programmes_id' => $programmesId, 't2.semester_id' => '1', 't2.academic_year' => $academic_year, 't1.student_status_type_id' => '1'))
			   //->where(array('t1.programmes_id' => $programmesId, 't2.academic_year' => $academic_year, 't1.student_status_type_id' => '1'))
               ->order('t1.student_id ASC')
               ->where->notLike('t1.student_id', "TEMP_%");
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);*/
    }


    public function getSemesterStudentList($programmesId, $yearId, $academic_session)
    { 
        $student_list = array();

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

         $select->from(array('t1' => 'student'))
                ->columns(array('id','first_name','middle_name','last_name','student_id'))
                ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id', 'academic_year'))
                ->join(array('t3' => 'programmes'),
                    't3.id = t1.programmes_id', array('programme_name'))
                ->join(array('t4' => 'student_semester'),
                    't4.id = t2.semester_id', array('semester'))
                ->join(array('t5' => 'programme_year'),
                    't5.id = t2.year_id', array('year'))
                ->where(array('t1.programmes_id' => $programmesId, 't2.year_id' => $yearId, 't2.academic_session_id' => $academic_session, 't1.student_status_type_id' => '1'))
                ->order('t1.student_id ASC')
                ->where->notLike('t1.student_id', "TEMP_%");
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
           $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['year'] = $set['year'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];           
        }
        return $student_list;
    }



    public function getAssignedHouseStudentList($programmesId, $yearId, $academic_session)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_house_details')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name', 'middle_name', 'last_name','student_id', 'programmes_id'))
                    ->join(array('t3' => 'programmes'),
                            't3.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t4' => 'student_semester_registration'),
                            't4.student_id = t2.id', array('semester_id', 'academic_year'))
                    ->join(array('t5' => 'student_semester'),
                            't5.id = t4.semester_id', array('semester'))
                    ->join(array('t6' => 'programme_year'),
                            't6.id = t4.year_id', array('year'));
        $select->where(array('t2.programmes_id' => $programmesId, 't4.year_id' => $yearId, 't2.student_status_type_id' => '1', 't4.academic_session_id' => $academic_session));
        $select->order('t2.student_id ASC');
        $select->where->notLike('t2.student_id', "TEMP_%");
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_list = array();
        
        foreach($resultSet as $set){
           $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['year'] = $set['year'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];
        } 
        return $student_list; 
    }


    public function getEditSectionStudentList($programmesId, $yearId, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getCurrentAcademicYear($academic_event_details);


        $current_academic_session = $this->getAcademicSession($organisation_id);


        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_semester_registration'))
               ->join(array('t2' => 'student'),
                    't2.id = t1.student_id', array('id','first_name', 'middle_name', 'last_name', 'studentId'=>'student_id'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t2.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester'),
                    't4.id = t1.semester_id', array('semester'))
               ->join(array('t5' => 'student_section'),
                    't5.id = t1.student_section_id', array('section'))
               ->join(array('t6' => 'programme_year'),
                    't6.id = t1.year_id', array('year'))
               ->where(array('t1.academic_year' => $academic_year, 't1.student_section_id is NOT NULL', 't2.programmes_id' => $programmesId, 't1.year_id' => $yearId, 't1.academic_session_id' => $current_academic_session, 't2.student_status_type_id' => '1'))
               ->order('t5.section ASC, t2.enrollment_year DESC, t2.student_id ASC')
               ->where->notLike('t2.student_id', "TEMP_%");
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getEditHouseStudentList($programmesId, $yearId, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getCurrentAcademicYear($academic_event_details);

        $current_academic_session = $this->getAcademicSession($organisation_id);

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_house_details'))
               ->join(array('t2' => 'student'),
                    't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'studentId'=>'student_id'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t2.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester_registration'),
                    't2.id = t4.student_id', array('year_id','academic_year', 'academic_session_id'))
               ->join(array('t5' => 'student_semester'),
                    't5.id = t4.semester_id', array('semester'))
               ->join(array('t6' => 'student_house'),
                    't6.id = t1.student_house_id', array('house_name'))
               ->join(array('t7' => 'programme_year'),
                    't7.id = t4.year_id', array('year'))
               ->where(array('t4.academic_year' => $academic_year, 't1.student_house_id is NOT NULL', 't2.programmes_id' => $programmesId, 't4.year_id' => $yearId, 't4.academic_session_id' => $current_academic_session, 't2.student_status_type_id' => '1'));
              // ->order('id ASC');
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $organisation_id)
    {
       $programme_duration = $this->getProgrammeDuration($programmesId);

       $fromDate = $this->getFromDate('Semester Registration');
       $toDate = $this->getToDate('Semester Registration');

       $current_academic_session = $this->getAcademicSession($organisation_id);

       if($current_academic_session == 1)
       {
        $previous_academic_session = 2;
       }
       else
       {
        $previous_academic_session = 1;
       }

       $start_academic_session_id = $this->getAcademicStartSessionId($programmesId);
       if($start_academic_session_id == 1 && $previous_academic_session == 1){
        $year = $yearId;
       }else  if($start_academic_session_id == 1 && $previous_academic_session == 2){
        $year = $yearId + 1;
       } else if($start_academic_session_id == 2 && $previous_academic_session == 1){
        $year = $yearId + 1;
       } else if($start_academic_session_id == 2 && $previous_academic_session == 2){
        $year = $yearId;
       }

        $student_lists = $this->getCurrentSemesterStudentList($programmesId, $yearId, $previous_academic_session, $studentName, $studentId, $year);

       $previous_semester = $this->getPreviousStudentSemester($programmesId, $yearId, $previous_academic_session); 

       $current_semester = $previous_semester+1;

        $student_list = array();

        //get the current back year student and remove from the list
        $current_backyear_student_list = $this->getCurrentBackyearStudentList($previous_semester, $programmesId, $studentName, $studentId, $yearId); 

        //remove this from student list
        $backyear_students_cleared = array_diff_key($student_lists, $current_backyear_student_list);  

        foreach($backyear_students_cleared as $key => $value){
            $student_list[$key] = $value;
        }
        
        $previous_backyear_student_list = $this->getPreviousBackyearStudentList($current_semester, $programmesId, $studentName, $studentId, $current_academic_session);

        // add to the list
        $backyear_students = array_diff_key($previous_backyear_student_list, $student_list); 

        foreach($backyear_students as $key => $value){
            $student_list[$key] = $value;
        }

        $registeredId = $this->getStudentRegisteredId($fromDate, $toDate, $programmesId, $current_semester, $studentName, $studentId, $current_academic_session); 

        //remove this from student list
        foreach($registeredId as $key => $value){
            unset($student_list[$key]);
        }

        return $student_list;
    }
	

    public function getCurrentSemester($year)
    {
        $month = date('m');

        if($year == 1){
            if($month >='1' && $month < '6'){
                $semesterId = 2;
            }
            else{
                $semesterId = 1;
            }
        }
        if($year == 2){
            if($month >='1' && $month < '6'){
                $semesterId = 4;
            }
            else{
                $semesterId = 3;
            }
        }
        if($year == 3){
            if($month >='1' && $month < '6'){
                $semesterId = 6;
            }
            else{
                $semesterId = 5;
            }
        }
        if($year == 4){
            if($month >='1' && $month < '6'){
                $semesterId = 8;
            }
            else{
                $semesterId = 7;
            }
        }
        if($year == 5){
            if($month >='1' && $month < '6'){
                $semesterId = 10;
            }
            else{
                $semesterId = 9;
            }
        }

        if($year == 6){
            if($month >= '1' && $month < '6'){
                $semesterId = 12;
            }
            else{
                $semesterId = 11;
            }
        }

       return $semesterId;
    }


    public function getCurrentSemesterStudentList($programmesId, $yearId, $previous_academic_session, $studentName, $studentId, $year)
    {
        $month = date('m');

        $fromDate = $this->getFromDate('Semester Registration');
        $toDate = $this->getToDate('Semester Registration');

        $academicYear = $this->getAcademicYear('Semester Registration', date('Y-m-d'));

        //$year_semester = $this->getYearSemester($yearId);

        //To get previous semester based on year for the semester upgrade
        $currentSemester = $this->getCurrentSemester($yearId);

        $previousSemester = $currentSemester-1; //echo $currentSemester; echo $previousSemester; die();

        $student_list = array();

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

         $select->from(array('t1' => 'student'))
                 ->columns(array('id','first_name','middle_name','last_name','student_id', 'enrollment_year'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id', 'student_section_id', 'academic_year'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t1.programmes_id', array('programme_name'))
               ->join(array('t4' => 'student_semester'),
                    't4.id = t2.semester_id', array('semester'))
               ->join(array('t5' => 'programme_year'),
                    't5.id = t2.year_id', array('year'))
               ->where(array('t1.programmes_id' => $programmesId, 't2.year_id' => $yearId, 't2.academic_session_id' => $previous_academic_session, 't1.student_status_type_id' => '1', 't3.programme_duration >= ?' => $year))

               ->where->notLike('t1.student_id', "TEMP_%");
               $select->order('t1.enrollment_year DESC, t1.student_id ASC'); 

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
        $resultSet->initialize($result);

        foreach($resultSet as $set){
           $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['year'] = $set['year'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];
           $student_list[$set['student_id']]['enrollment_year'] = $set['enrollment_year'];            
        }

        return $student_list;
    }


    public function getCurrentBackyearStudentList($previousSemester, $programmesId, $studentName, $studentId, $yearId)
    {
        $fromDate = $this->getFromDate('Semester Registration');
        $toDate = $this->getToDate('Semester Registration');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_backyears')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name', 'middle_name', 'last_name','student_id', 'enrollment_year', 'programmes_id'))
                    ->join(array('t3' => 'programmes'),
                            't3.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t4' => 'student_semester'),
                            't4.id = t1.backyear_semester', array('semester'))
                    ->join(array('t5' => 'student_semester_registration'),
                            't5.semester_id = t1.backyear_semester', array('semester_id', 'academic_year'));
        $select->where(array('t1.backyear_semester' => $previousSemester, 't2.programmes_id' => $programmesId, 't1.backyear_status' => 'Not Cleared', 't1.backyear_year' => $yearId, 't2.student_status_type_id' => '1'));

        $select->where->notLike('t2.student_id', "TEMP_%");
        $select->order('t2.enrollment_year DESC, t2.student_id ASC'); 

        if($studentName){
            $select->where->like('t2.first_name','%'.$studentName.'%');
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }
        if($studentId){
            $select->where(array('t2.student_id' =>$studentId));
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_list = array();
        
        foreach($resultSet as $set){
            $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];
           $student_list[$set['student_id']]['enrollment_year'] = $set['enrollment_year'];
        }

        return $student_list;    
    }


    public function getPreviousBackyearStudentList($current_semester, $programmesId, $studentName, $studentId, $current_academic_session)
    {
        $fromDate = $this->getFromDate('Semester Registration');
        $toDate = $this->getToDate('Semester Registration');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_semester_registration')) 
                    ->join(array('t2' => 'student'), 
                            't1.student_id = t2.id', array('first_name', 'middle_name', 'last_name','student_id', 'enrollment_year', 'programmes_id'))
                    ->join(array('t3' => 'programmes'),
                            't3.id = t2.programmes_id', array('programme_name'))
                    ->join(array('t4' => 'student_semester'),
                            't4.id = t1.semester_id', array('semester'))
                    ->join(array('t5' => 'student_backyears'),
                            't5.student_id = t1.student_id', array('backyear_status', 'backyear_academic_year', 'backyear_semester', 'backyear_year'))
                    ->join(array('t6' => 'programme_year'),
                            't6.id = t1.year_id', array('year'));
        $select->where(array('t1.semester_id' => $current_semester, 't2.programmes_id' => $programmesId, 't1.academic_session_id' => $current_academic_session, 't5.backyear_status' => 'Not Cleared', 't2.student_status_type_id' => '1'));
        $select->where->notLike('t2.student_id', "TEMP_%");
        $select->order('t2.enrollment_year DESC, t2.student_id ASC'); 

        if($studentName){
            $select->where->like('t2.first_name','%'.$studentName.'%');
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }
        if($studentId){
            $select->where(array('t2.student_id' =>$studentId));
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_list = array();
        
        foreach($resultSet as $set){
            $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
            $student_list[$set['student_id']]['year'] = $set['year'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];
           $student_list[$set['student_id']]['enrollment_year'] = $set['enrollment_year'];
        }
        
        return $student_list;  
    }


    public function getSemesterReportedStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
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
               ->where(array('t1.programmes_id' => $programmesId, 't2.year_id' => $yearId, 't2.academic_year' => $academicYear));
        //$select->where->notEqualTo('t1.student_status_type_id','4');
        //$select->where->notEqualTo('t1.student_status_type_id','5');
        //$select->where->notEqualTo('t1.student_status_type_id','6');
        $select->where->notEqualTo('t1.student_status_type_id','7');
		//$select->where->notEqualTo('t1.student_status_type_id','8');


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


    public function getStudentRegisteredId($fromDate, $toDate, $programmesId, $current_semester ,$studentName, $studentId, $current_academic_session)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(); 

        $select->from(array('t1' => 'student_semester_registration'))
               ->join(array('t2' => 'student'),
                    't2.id = t1.student_id', array('student_id', 'first_name', 'middle_name', 'last_name', 'programmes_id', 'enrollment_year'))
               ->join(array('t3' => 'programmes'),
                            't3.id = t2.programmes_id', array('programme_name'))
                ->join(array('t4' => 'student_semester'),
                            't4.id = t1.semester_id', array('semester'))
               ->where(array('t2.programmes_id' => $programmesId,'t1.updated_date >= ?' => $fromDate, 't1.updated_date <= ?' => $toDate, 't1.semester_id' => $current_semester, 't1.academic_session_id' => $current_academic_session));

        if($studentName){
            $select->where->like('t2.first_name','%'.$studentName.'%');
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }
        if($studentId){
            $select->where(array('t2.student_id' =>$studentId));
            $select->where(array('t2.programmes_id = ?' => $programmesId));
        }               
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_list = array();
        
        foreach($resultSet as $set){
            $student_list[$set['student_id']]['name'] = $set['first_name']. ' ' .$set['middle_name'].' '.$set['last_name'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['semester'] = $set['semester'];
           $student_list[$set['student_id']]['programme_name'] = $set['programme_name'];
           $student_list[$set['student_id']]['academic_year'] = $set['academic_year'];
           $student_list[$set['student_id']]['enrollment_year'] = $set['enrollment_year'];
        }

        //var_dump($student_list); die();
        
        return $student_list;  
    }



    public function getStudentList($programmesId, $yearId, $academicYear, $studentName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

         $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id', 'student_section_id'))
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
                ->join(array('t8' => 'programme_year'),
                    't8.id = t2.year_id', array('year'))
               ->where(array('t1.programmes_id' => $programmesId, 't2.year_id' => $yearId, 't2.academic_year' => $academicYear, 't1.student_status_type_id' => '1'))
               
               ->where->notLike('t1.student_id', "TEMP_%");

        if($studentName){
            $select->where->like('first_name','%'.$studentName.'%');
            $select->where(array('t1.programmes_id = ?' => $programmesId));
            $select->where(array('t2.year_id = ?' => $yearId));
            $select->where(array('t2.academic_year = ?' => $academicYear));
        }
        if($studentId){
            $select->where(array('t1.student_id' => $studentId));
            $select->where(array('t1.programmes_id = ?' => $programmesId));
            $select->where(array('t2.year_id = ?' => $yearId));
            $select->where(array('t2.academic_year = ?' => $academicYear));
        }
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    /*
    * List registered student to update the reported student/ view the student whether he/ she is reported to the * college or not
    */
    public function getRegisteredStudentList($stdOrganisation, $stdProgramme, $stdYear, $stdGender, $stdReportStatus)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_registration'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programme_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.student_type_id', array('student_type'))
               ->join(array('t4' => 'organisation'),
                    't4.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t5' => 'gender'),
                    't5.id = t1.gender', array('gender'))
               ->where(array('t1.organisation_id' => $stdOrganisation, 't1.programme_id' => $stdProgramme, 't1.registration_type' => '1'));

        if($stdYear){
            $select->where->like('admission_year', $stdYear.'%');
            $select->where(array('t1.organisation_id = ?' => $stdOrganisation, 't1.programme_id = ?' => $stdProgramme));
        }
        if($stdGender){
            $select->where->like('t5.gender', $stdGender.'%');
            $select->where(array('t1.organisation_id = ?' => $stdOrganisation, 't1.programme_id = ?' => $stdProgramme));
        }
        if($stdReportStatus){
            $select->where->like('student_reporting_status', $stdReportStatus.'%');
            $select->where(array('t1.organisation_id = ?' => $stdOrganisation, 't1.programme_id = ?' => $stdProgramme));
        }


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result); 
    }

    /*
    * get details of new registered student to update
    */
    public function findNewRegisteredStudentDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_registration'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programme_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                    't3.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                    't4.id = t1.student_type_id', array('student_type'))
               ->join(array('t5' => 'gender'),
                    't5.id = t1.gender', array('student_gender' => 'gender'))
               ->join(array('t6' => 'relation_type'),
                    't6.id = t1.relationship_id', array('relation'))
               ->where(array('t1.id =' .$id));  //join expression; //         

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
        }

        throw new \InvalidArgumentException("Student with given ID: ($id) not found");
    }


    public function getNewRegisteredStudentDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_registration'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programme_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                    't3.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                    't4.id = t1.student_type_id', array('student_type'))
               ->join(array('t5' => 'gender'),
                    't5.id = t1.gender', array('student_gender' => 'gender'))
               ->join(array('t6' => 'relation_type'),
                    't6.id = t1.relationship_id', array('relation'))
               ->where(array('t1.id =' .$id));  //join expression; //         

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

	public function findAllReportedStudent($organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                    't3.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                	't4.id = t1.scholarship_type', array('student_type'))  //join expression; //
               ->join(array('t5' => 'student_registration'),
                    't5.id = t1.student_registration_id', array('registration_no'));
        $select->where(array('t1.organisation_id = ?' => $organisation_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                return $resultSet->initialize($result); 
        }

        return array();
	}


        
    
        
        public function findUpdatedStudentDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'student_registration'))
            ->join(array('t2' => 'programmes'),
               't2.id = t1.programme_name_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                     't3.id = t1.organisation_name_id', array('organisation_name'))
                     ->join(array('t4' => 'student_type'), // join table with alias
                    	 't4.id = t1.student_type_id', array('student_type'));  //join expression; // 
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }


        public function getStudentPersonalDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student'))
                   ->join(array('t2' => 'programmes'),
                        't2.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t3' => 'gender'),
                        't3.id = t1.gender', array('stdgender' => 'gender'))
                   ->join(array('t4' => 'student_type'),
                        't4.id = t1.scholarship_type', array('student_type'))
                   ->where(array('t1.id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }

        public function getStudentDetails($tableName, $id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            if($tableName == 'student_relation_details'){
                $select->from(array('t1' => 'student'))
                   ->join(array('t2' => $tableName),
                        't1.id = t2.student_id', array('std_id' => 'student_id'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'gender'),
                        't4.id = t1.gender', array('stdgender' => 'gender'))
                   ->join(array('t5' => 'student_type'),
                        't5.id = t1.scholarship_type', array('student_type'))
                   ->where(array('t2.id' => $id));
            }
            else if($tableName == 'student_previous_school_details'){
                $select->from(array('t1' => 'student'))
                   ->join(array('t2' => $tableName),
                        't1.id = t2.student_id', array('std_id' => 'student_id'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'gender'),
                        't4.id = t1.gender', array('stdgender' => 'gender'))
                   ->join(array('t5' => 'student_type'),
                        't5.id = t1.scholarship_type', array('student_type'))
                   ->where(array('t2.id' => $id));
            }

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
                        't2.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t3' => 'organisation'), // join table with alias
                        't3.id = t1.organisation_id', array('organisation_name'))
                   ->join(array('t4' => 'student_type'), // join table with alias
                        't4.id = t1.scholarship_type', array('student_type'))
                   ->join(array('t5' => 'gender'),
                        't5.id = t1.gender', array('student_gender'=>'gender'))

                    ->where(array('t1.id =' .$id));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
                }

                throw new \InvalidArgumentException("Student Type with given ID: ($id) not found");
        }


        public function getStdPermanentAddrDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student'))
                   ->join(array('t2' => 'student_nationality_details'),
                        't2.student_id = t1.id', array('student_country_id', 'student_nationality_id'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'student_type'), // join table with alias
                        't4.id = t1.scholarship_type', array('student_type'))
                   ->join(array('t5' => 'gender'),
                        't5.id = t1.gender', array('student_gender'=>'gender'))
                   ->join(array('t6' => 'country'),
                        't6.id = t2.student_country_id', array('country'))
                   ->join(array('t7' => 'nationality'),
                        't7.id = t2.student_nationality_id', array('nationality'))
                   ->join(array('t8' => 'dzongkhag'),
                        't8.id = t1.dzongkhag', array('dzongkhag_name'))
                   ->join(array('t9' => 'gewog'),
                        't9.id = t1.gewog', array('gewog_name'))
                   ->join(array('t10' => 'village'),
                        't10.id = t1.village', array('village_name'))

                    ->where(array('t1.id = ?' => $id));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
                }

                throw new \InvalidArgumentException("Student Permanent Address with given ID: ($id) not found");
        }


        public function getStdParentDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student'))
                   ->join(array('t2' => 'student_parents_details'),
                        't2.student_id = t1.id', array('father_name', 'father_cid', 'father_nationality', 'father_house_no', 'father_thram_no', 'father_occupation', 'father_dzongkhag', 'father_gewog', 'father_village', 'mother_name', 'mother_cid', 'mother_nationality', 'mother_house_no', 'mother_thram_no', 'mother_occupation', 'mother_dzongkhag', 'mother_gewog', 'mother_village', 'parents_contact_no', 'parents_present_address'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'student_type'), // join table with alias
                        't4.id = t1.scholarship_type', array('student_type'))
                   ->join(array('t5' => 'gender'),
                        't5.id = t1.gender', array('student_gender'=>'gender'))
                   ->join(array('t6' => 'nationality'),
                        't6.id = t2.father_nationality', array('fnationality' => 'nationality'))
                   ->join(array('t7' => 'dzongkhag'),
                        't7.id = t2.father_dzongkhag', array('fdzongkhag_name' => 'dzongkhag_name'))
                   ->join(array('t8' => 'gewog'),
                        't8.id = t2.father_gewog', array('fgewog_name' => 'gewog_name'))
                   ->join(array('t9' => 'village'),
                        't9.id = t2.father_village', array('fvillage_name' => 'village_name'))
                   ->join(array('t10' => 'nationality'),
                        't10.id = t2.mother_nationality', array('mnationality' => 'nationality'))
                   ->join(array('t11' => 'dzongkhag'),
                        't11.id = t2.mother_dzongkhag', array('mdzongkhag_name' => 'dzongkhag_name'))
                   ->join(array('t12' => 'gewog'),
                        't12.id = t2.mother_gewog', array('mgewog_name' => 'gewog_name'))
                   ->join(array('t13' => 'village'),
                        't13.id = t2.mother_village', array('mvillage_name' => 'village_name'))

                    ->where(array('t2.student_id = ?' => $id));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
                }

                throw new \InvalidArgumentException("Student Parent Details with given ID: ($id) not found");
        }


        public function getStudentCategoryDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student_category_details'))
                   ->join(array('t2' => 'student'),
                        't2.id = t1.student_id', array('id'))
                   ->join(array('t3' => 'student_category'),
                        't3.id = t1.student_category_id', array('student_category'))
                   ->where(array('t1.student_id' => $id))
		   ->order('t1.date DESC')
                   ->limit(1);

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentContactDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student'))
                   ->where(array('t1.id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentNationalityDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student_nationality_details'))
                   ->join(array('t2' => 'country'),
                        't2.id = t1.student_country_id', array('country'))
                   ->join(array('t3' => 'nationality'),
                        't3.id = t1.student_nationality_id', array('nationality'))
                   ->where(array('t1.student_id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentPermanentAddrDetails($id, $type)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            if($type == 'ALL'){
                $select->from(array('t1' => 'student'))
                        ->join(array('t2' => 'dzongkhag'),
                            't2.id = t1.dzongkhag', array('dzongkhag_name'))
                        ->join(array('t3' => 'gewog'),
                            't3.id = t1.gewog', array('gewog_name'))
                        ->join(array('t4' => 'village'),
                            't4.id = t1.village', array('village_name'))
                    ->where(array('t1.id' => $id));
            }
            else if($type == 'NEW'){
                $select->from(array('t1' => 'student_registration'))
                        ->join(array('t2' => 'dzongkhag'),
                            't2.id = t1.dzongkhag', array('dzongkhag_name'))
                        ->join(array('t3' => 'gewog'),
                            't3.id = t1.gewog', array('gewog_name'))
                        ->join(array('t4' => 'village'),
                            't4.id = t1.village', array('village_name'))
                        ->join(array('t5' => 'country'),
                            't5.id = t1.country_id', array('country'))
                        ->where(array('t1.id' => $id));
            }

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentParentDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student_parents_details'))
                   ->join(array('t2' => 'nationality'),
                        't2.id = t1.father_nationality', array('fnationality' => 'nationality'))
                   ->join(array('t3' => 'dzongkhag'),
                        't3.id = t1.father_dzongkhag', array('fdzongkhag_name' => 'dzongkhag_name'))
                   ->join(array('t4' => 'gewog'),
                        't4.id = t1.father_gewog', array('fgewog_name' => 'gewog_name'))
                   ->join(array('t5' => 'village'),
                        't5.id = t1.father_village', array('fvillage_name' => 'village_name'))
                   ->join(array('t6' => 'nationality'),
                        't6.id = t1.mother_nationality', array('mnationality' => 'nationality'))
                   ->join(array('t7' => 'dzongkhag'),
                        't7.id = t1.mother_dzongkhag', array('mdzongkhag_name' => 'dzongkhag_name'))
                   ->join(array('t8' => 'gewog'),
                        't8.id = t1.mother_gewog', array('mgewog_name' => 'gewog_name'))
                   ->join(array('t9' => 'village'),
                        't9.id = t1.mother_village', array('mvillage_name' => 'village_name'))
                   ->where(array('t1.student_id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentGuardianDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student_guardian_details'))
                   ->join(array('t2' => 'student'),
                        't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id', 'enrollment_year'))
                   ->join(array('t3' => 'gender'),
                        't3.id = t2.gender', array('stdgender' => 'gender'))
                   ->join(array('t4' => 'student_type'),
                        't4.id = t2.scholarship_type', array('student_type'))
                   ->join(array('t5' => 'programmes'),
                        't5.id = t2.programmes_id', array('programme_name'))
                   ->where(array('t1.student_id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStudentPreviousSchool($tableName, $id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => $tableName))
                   ->join(array('t2' => 'school'),
                        't2.id = t1.previous_institution', array('school_name'))
                   ->where(array('t1.student_id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }


        public function getStdPreviousSchoolDetails($tableName, $id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName))
                    ->join(array('t2' => 'school'),
                        't2.id = t1.previous_institution', array('school_name'))
                    ->where(array('t1.id' => $id));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
                }
            throw new \InvalidArgumentException("Student Previous School Details with given ID: ($id) not found");
        }


        public function getStdGuardianDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student_guardian_details'))
                   ->join(array('t2' => 'student'),
                        't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id', 'enrollment_year'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t2.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'student_type'), // join table with alias
                        't4.id = t2.scholarship_type', array('student_type'))
                   ->join(array('t5' => 'gender'),
                        't5.id = t2.gender', array('student_gender'=>'gender'))
                   ->join(array('t6' => 'relation_type'),
                        't6.id = t1.guardian_relation', array('relation'))

                    ->where(array('t2.id = ?' => $id));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
                }

                throw new \InvalidArgumentException("Student Guardian Details with given ID: ($id) not found");
        }
		
	

   // Function to save new registered student who have reported
    public function saveNewReportedStudent(UpdateStudent $studentAdmissionObject)
        {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        $studentAdmissionDataSample = $studentAdmissionData;

        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['student_Type']);
        
        //defining an array and extracting elements of the Student Admission Model
        $registeredStudentData = array();
        $registeredStudentFields = array(
            'student_Id',
            'first_Name',
            'middle_Name',
            'last_Name',
            'cid',
            'gender',
            'date_Of_Birth',
            'scholarship_Type',
            'enrollment_Year',
            'joining_Date',
            'programmes_Id',
            'organisation_Id',
            'student_Registration_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $registeredStudentFields))
            {
                $registeredStudentData = array_merge($registeredStudentData, array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }

        $stdGuardianData = array();
        $stdGuardianFields = array(
            'guardian_Name',
            'guardian_Relation',
            'guardian_Contact_No',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $stdGuardianFields))
            {
                $stdGuardianData = array_merge($stdGuardianData, array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }

        $stdSemesterData = array();
        $stdSemesterFields = array(
            'academic_Year',
            'semester_Id',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample  as $key => $value) {
            if(in_array($key, $stdSemesterFields))
            {
                $stdSemesterData = array_merge($stdSemesterData, array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }

           // $studentAdmissionData['student_reporting_status'] = $status;
            $action = new Update('student_registration');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentAdmissionObject->getId()));
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

        // If student_reporting_status is Reported then it will get certain data from student_registration table, insert into student table and at the same time generate student_id and username and password for student to login into the system
            if($studentAdmissionData['student_Reporting_Status'] == 'Reported'){

                //generate student id and assign to it
                $registeredStudentData['student_Id']  = $this->generateStudentId($registeredStudentData['organisation_Id']);
        
                $report_action = new Insert('student');
                $report_action->values(array(
                            'student_id' => $registeredStudentData['student_Id'],
                            'first_name' => $registeredStudentData['first_Name'],
                            'middle_name' => $registeredStudentData['middle_Name'],
                            'last_name' => $registeredStudentData['last_Name'],
                            'cid' => $registeredStudentData['cid'],
                            'gender' => $registeredStudentData['gender'],
                            'date_of_birth' => $registeredStudentData['date_Of_Birth'],
                            'first_name' => $registeredStudentData['first_Name'],
                            'scholarship_type' => $registeredStudentData['scholarship_Type'],
                            'enrollment_year' => $registeredStudentData['enrollment_Year'],
                            //'joining_date' => $registeredStudentData['joining_Date'],
                            'programmes_id' => $registeredStudentData['programmes_Id'],
                            'organisation_id' => $registeredStudentData['organisation_Id'],
                            ));
                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($report_action);
                $result = $stmt->execute();

				//after generating student id, it will auto generate username and password for the particular student
                $this->addNewUser($registeredStudentData['student_Id'], $registeredStudentData['date_Of_Birth'], $registeredStudentData['organisation_Id']);
            }
        
        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            if($studentAdmissionData != NULL){

                // Take $newId from student table and add in place of student_id in student_guardian_details with other data
                $guardian_action = new Insert('student_guardian_details');
                $guardian_action->values(array(
                            'guardian_name' => $stdGuardianData['guardian_Name'],
                            'guardian_relation' => $stdGuardianData['guardian_Relation'],
                            'guardian_contact_no' => $stdGuardianData['guardian_Contact_No'],
                            'student_id' => $newId
                            ));
                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($guardian_action);
                $result = $stmt->execute();

                // Take $newId from student table and add in place of student_id in student_semester_registration with other data
                $semester_action = new Insert('student_semester_registration');
                $semester_action->values(array(
                            'academic_year'=>$stdSemesterData['academic_Year'],
                            'semester_id' => $stdSemesterData['semester_Id'],
                            'student_id'=> $newId
                        ));
                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($semester_action);
                $result = $stmt->execute();
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }


        
    public function findReportedStudentDetails($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                    't3.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                    't4.id = t1.scholarship_type', array('student_type'))
               ->join(array('t5' => 'student_guardian_details'),
                    't5.student_id = t1.id', array('guardian_name', 'guardian_contact_no', 'guardian_relation'))
               ->join(array('t6' => 'gender'),
                    't6.id = t1.gender', array('student_gender' => 'gender'))

                ->where(array('t1.id =' .$id));  //join expression; // 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student Type with given ID: ($id) not found");
	}


    //Function to delete not reported student list
    public function deleteNotReportedStudent($id)
    {
        //Get student data to change the status in student registration table
        $studentData = $this->getReportedStudentData($id);

        //Call function to change the status
        $this->updateStudentReportingStatus($studentData);

        //Delete Student
        $action = new Delete('student');
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        return (bool)$result->getAffectedRows();
    }


    public function deleteStudentRelation($id)
    {
        //Delete Student
        $action = new Delete('student_relation_details');
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        return (bool)$result->getAffectedRows();
    }


    //Get student data to change the status in student registration table
    public function getReportedStudentData($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'));
        $select->columns(array('id', 'cid', 'enrollment_year', 'programmes_id'));
        $select->where(array('t1.id = ?' => $id));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       // $id = NULL;
        $studentData = array();
        
        foreach($resultSet as $set)
        { 
            $studentData[] = $set;
        }
        return $studentData;
    }


    //Function to change the reporting status
    public function updateStudentReportingStatus($studentData)
    { 
        $cid = NULL;
        $admission_year = NULL;
        $programme = NULL;
        foreach($studentData as $set){
            $cid = $set['cid'];
            $admission_year = $set['enrollment_year'];
            $programme = $set['programmes_id'];
        }
        
        $action = new Update('student_registration');
        $action->set(array('student_reporting_status' => 'Pending'));
        $action->where(array('cid = ?' => $cid, 'admission_year = ?' => $admission_year, 'programme_id = ?' => $programme));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }



    public function saveStudentPersonalDetails(UpdateStudentPersonalDetails $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        $studentAdmissionDataSample = $studentAdmissionData;
        unset($studentAdmissionData['id']);

        unset($studentAdmissionData['programme_Name']);
        /*unset($studentAdmissionData['rank']);
        unset($studentAdmissionData['blood_Group']);
        unset($studentAdmissionData['birth_Place']);
        unset($studentAdmissionData['nationality']);
        unset($studentAdmissionData['mother_Tongue']);
        unset($studentAdmissionData['student_Type_Id']);
        unset($studentAdmissionData['student_Type']);
        unset($studentAdmissionData['student_Category']);
        unset($studentAdmissionData['joining_Date']);
        unset($studentAdmissionData['student_Registration_Id']);
        unset($studentAdmissionData['student_Gender']);*/


        //defining an array and extracting elements of the Student Admission Model
        $studentCategoryData = array();
        $studentCategoryFields = array(
            'student_Category_Id',
            'date',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentCategoryFields))
            {
                $studentCategoryData = array_merge($studentCategoryData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }
        
        //ID present, so it is an update
        $action = new Update('student');
        $action->set($studentAdmissionData);
        $action->where(array('id = ?' => $studentAdmissionObject->getId()));        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($studentAdmissionData != NULL)
        {
            $category_action = new Insert('student_category_details');
            //$action->values(array('item_Received_Purchased_Id'=>$newId));
            //$action->values($goodsReceivedData);
            $category_action->values(array(
                'student_category_id'=>$studentCategoryData['student_Category_Id'],
                'date' => $studentCategoryData['date'],
                'student_id'=>$studentAdmissionObject->getId()
            ));

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($category_action);
            $result = $stmt->execute();
        }

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }


    public function saveStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        $studentAdmissionDataSample = $studentAdmissionData;
        unset($studentAdmissionData['id']);

        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['nationality']);
        unset($studentAdmissionData['student_Type']);
        unset($studentAdmissionData['student_Category']);
        unset($studentAdmissionData['student_Gender']);

        $studentAdmissionData['dzongkhag'] = $stdDzongkhag;
        $studentAdmissionData['gewog'] = $stdGewog;
        $studentAdmissionData['village'] = $stdVillage;

        //defining an array and extracting elements of the Student Admission Model
        $studentNationalityData = array();
        $studentNationalityFields = array(
            'student_Country_Id',
            'student_Nationality_Id',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentNationalityFields))
            {
                $studentNationalityData = array_merge($studentNationalityData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }
        
        //ID present, so it is an update
        $action = new Update('student');
        $action->set($studentAdmissionData);
        $action->where(array('id = ?' => $studentAdmissionObject->getId()));        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($studentAdmissionData != NULL)
        {
            $nationality_action = new Insert('student_nationality_details');
            //$action->values(array('item_Received_Purchased_Id'=>$newId));
            //$action->values($goodsReceivedData);
            $nationality_action->values(array(
                'student_country_id'=>$studentNationalityData['student_Country_Id'],
                'student_nationality_id' => $studentNationalityData['student_Nationality_Id'],
                'student_id'=>  $studentAdmissionObject->getId()
            ));

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($nationality_action);
            $result = $stmt->execute();
        }

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }


    public function updateStudentPermanentAddr(UpdateStudentPermanentAddr $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        $studentAdmissionDataSample = $studentAdmissionData;
        unset($studentAdmissionData['id']);

        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['nationality']);
        unset($studentAdmissionData['student_Type']);
        unset($studentAdmissionData['student_Category']);
        unset($studentAdmissionData['student_Gender']);

        $studentAdmissionData['dzongkhag'] = $stdDzongkhag;
        $studentAdmissionData['gewog'] = $stdGewog;
        $studentAdmissionData['village'] = $stdVillage;


        //defining an array and extracting elements of the Student Admission Model
        $studentNationalityData = array();
        $studentNationalityFields = array(
            'student_Country_Id',
            'student_Nationality_Id',
            //'student_Id' = $studentAdmissionData['id'];
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentNationalityFields))
            {
                $studentNationalityData = array_merge($studentNationalityData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }
        
        //ID present, so it is an update
        $action = new Update('student');
        $action->set($studentAdmissionData);
        $action->where(array('id = ?' => $studentAdmissionObject->getId()));        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($studentAdmissionData != NULL)
        {

            $nationality_action = new Update('student_nationality_details');
            $nationality_action->set($studentNationalityData);
            $nationality_action->where(array('student_id = ?' => $studentAdmissionObject->getId()));
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($nationality_action);
            $result = $stmt->execute();
        }

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }


    public function updateStudentParentDetails(UpdateStudentParentDetails $studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['first_Name']); 
        unset($studentAdmissionData['middle_Name']);  
        unset($studentAdmissionData['last_Name']);
        unset($studentAdmissionData['std_Id']);     

        $studentAdmissionData['father_Dzongkhag'] = $stdFatherDzongkhag;
        $studentAdmissionData['mother_Dzongkhag'] = $stdMotherDzongkhag;

        $studentAdmissionData['father_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdFatherGewog);
        $studentAdmissionData['father_Village'] = $this->getAjaxDataId($tableName = 'village', $stdFatherVillage);

        $studentAdmissionData['mother_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdMotherGewog);
        $studentAdmissionData['mother_Village'] = $this->getAjaxDataId($tableName = 'village', $stdMotherVillage);

       // $studentAdmissionData['student_Id'] = $studentAdmissionData->getId();

       // var_dump($studentAdmissionData);
        //die();       

        // ID present, so it is an update
        $action = new Update('student_parents_details');
        $action->set($studentAdmissionData);
        $action->where(array('student_id = ?' => $studentAdmissionObject->getId()));        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        } 
        throw new \Exception("Database Error");
    }


    public function saveStudentGuardianDetails(UpdateStudentGuardianDetails $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['first_Name']);
        unset($studentAdmissionData['middle_Name']);
        unset($studentAdmissionData['last_Name']);
        unset($studentAdmissionData['student_Type']);
        
        //ID present, so it is an update
        $action = new Update('student_guardian_details');
        $action->set($studentAdmissionData);
        $action->where(array('id = ?' => $studentAdmissionObject->getId()));        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }

    public function crossCheckStudentRelation($parentCID, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_relation_details'));
        $select->where(array('t1.parent_cid' => $parentCID, 't1.student_id' => $id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $parentCID = 0;
        foreach($resultSet as $set){
            $parentCID = $set['parent_cid'];
        }
        return $parentCID;
    }

    public function findStudentRelationDetails($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => $tableName))
               ->join(array('t2' => 'relation_type'),
                    't1.relation_type = t2.id', array('relation'))
               ->join(array('t3' => 'nationality'),
                    't3.id = t1.parent_nationality', array('nationality'))
               ->join(array('t4' => 'dzongkhag'),
                    't4.id = t1.parent_dzongkhag', array('dzongkhag_name'))
                ->where('t1.student_id = ' .$id); 
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getStudentRelationDetails($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName))
               ->join(array('t2' => 'relation_type'),
                    't2.id = t1.relation_type', array('relation'))
               ->join(array('t3' => 'nationality'),
                    't3.id = t1.parent_nationality', array('nationality'))
               ->join(array('t4' => 'dzongkhag'),
                    't4.id = t1.parent_dzongkhag', array('dzongkhag_name'))
                ->where(array('t1.id = ?' => $id));  //join expression; // 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }
        throw new \InvalidArgumentException("Student Relation Details with given ID: ($id) not found");
    }


    public function getStdInitialRelationDetails($id)
    { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_relation_details'))
               ->join(array('t2' => 'relation_type'),
                    't2.id = t1.relation_type', array('relation'))
               ->join(array('t3' => 'student'),
                    't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name'))
                ->where(array('t1.student_id = ?' => $id));
               // ->where(array('t1.parent_cid' => NULL)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }
        throw new \InvalidArgumentException("Student Relation Details with given ID: ($id) not found");
    }

    public function checkStdInitialRelationDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_relation_details'))
               ->join(array('t2' => 'relation_type'),
                    't2.id = t1.relation_type', array('relation'))
               ->join(array('t3' => 'student'),
                    't3.id = t1.student_id', array('first_name', 'middle_name', 'last_name'))
                ->where(array('t3.id = ?' => $id))
                ->where(array('t1.parent_cid' => NULL));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $studentID = NULL;
        foreach($resultSet as $set){
            $studentID = $set['student_id'];
        }
        return $studentID;
    }


    public function saveStudentRelationDetails(StudentRelationDetails $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['first_Name']);
        unset($studentAdmissionData['middle_Name']);
        unset($studentAdmissionData['last_Name']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['student_Type']);

        if($studentAdmissionObject->getId()){
            // ID present, so it is an update
            $action = new Update('student_relation_details');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentAdmissionObject->getId()));
        }else {
            // ID is not preset, so it is an insert
            $action = new Insert('student_relation_details');
            $action->values($studentAdmissionData);
        }
                
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        } 
        throw new \Exception("Database Error");
    }


    public function saveStudentParentDetails(UpdateStudentParentDetails $studentAdmissionObject, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['first_Name']); 
        unset($studentAdmissionData['middle_Name']);  
        unset($studentAdmissionData['last_Name']); 
        unset($studentAdmissionData['std_Id']);    

        $studentAdmissionData['father_Dzongkhag'] = $stdFatherDzongkhag;
        $studentAdmissionData['mother_Dzongkhag'] = $stdMotherDzongkhag;

        $studentAdmissionData['father_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdFatherGewog);
        $studentAdmissionData['father_Village'] = $this->getAjaxDataId($tableName = 'village', $stdFatherVillage);

        $studentAdmissionData['mother_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdMotherGewog);
        $studentAdmissionData['mother_Village'] = $this->getAjaxDataId($tableName = 'village', $stdMotherVillage);


        if($studentAdmissionObject->getId()){
            // ID present, so it is an update
            $action = new Update('student_parents_details');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentAdmissionObject->getId()));
        }else {
            // ID is not preset, so it is an insert
            $action = new Insert('student_parents_details');
            $action->values($studentAdmissionData);
        }
                
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        } 
        throw new \Exception("Database Error");
    }


    public function saveStudentPreviousSchool(StudentPreviousSchool $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['enrollment_Year']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['first_Name']);
        unset($studentAdmissionData['middle_Name']);
        unset($studentAdmissionData['last_Name']);

        $studentAdmissionData['from_Date'] = date("Y-m-d", strtotime(substr($studentAdmissionData['from_Date'],0,10)));
        $studentAdmissionData['to_Date'] = date("Y-m-d", strtotime(substr($studentAdmissionData['to_Date'],0,10)));

        if($studentAdmissionObject->getId()){
            // ID present, so it is an update
            $action = new Update('student_previous_school_details');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentAdmissionObject->getId()));
        }else {
            // ID is not preset, so it is an insert
            $action = new Insert('student_previous_school_details');
            $action->values($studentAdmissionData);
        }
                
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        } 
        throw new \Exception("Database Error");
    }


    public function updateStudentPreviousSchool(UpdateStudentPreviousSchool $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['first_Name']);
        unset($studentAdmissionData['middle_Name']);
        unset($studentAdmissionData['last_Name']);
        unset($studentAdmissionData['student_Type']);
        unset($studentAdmissionData['student_Gender']);
        unset($studentAdmissionData['studentID']);

        // ID present, so it is an update
        $action = new Update('student_previous_school_details');
        $action->set($studentAdmissionData);
        $action->where(array('id = ?' => $studentAdmissionObject->getId()));
                
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        } 
        throw new \Exception("Database Error");
    }


	public function saveReportedStudentDetails(UpdateReportedStudentDetails $studentAdmissionObject, $stdDzongkhag, $stdGewog, $stdVillage, $stdFatherDzongkhag, $stdFatherGewog, $stdFatherVillage, $stdMotherDzongkhag, $stdMotherGewog, $stdMotherVillage)
		{
		$studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        $studentAdmissionDataSample = $studentAdmissionData;
		unset($studentAdmissionData['id']);
		unset($studentAdmissionData['registration_No']);
        unset($studentAdmissionData['programme_Name']);
        unset($studentAdmissionData['rank']);
        unset($studentAdmissionData['blood_Group']);
        unset($studentAdmissionData['birth_Place']);
        unset($studentAdmissionData['nationality']);
        unset($studentAdmissionData['mother_Tongue']);
        unset($studentAdmissionData['student_Type_Id']);
        unset($studentAdmissionData['student_Type']);
        unset($studentAdmissionData['student_Category']);
		unset($studentAdmissionData['joining_Date']);
        unset($studentAdmissionData['student_Registration_Id']);
        unset($studentAdmissionData['student_Gender']);

        $studentPreviousSchoolDetailsData = $studentAdmissionData['stdpreviousschooldetails'];
        unset($studentAdmissionData['stdpreviousschooldetails']);

        $studentAdmissionData['dzongkhag'] = $stdDzongkhag;
        $studentAdmissionData['father_Dzongkhag'] = $stdFatherDzongkhag;
        $studentAdmissionData['mother_Dzongkhag'] = $stdMotherDzongkhag;

        $studentAdmissionData['gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdGewog);
        $studentAdmissionData['village'] = $this->getAjaxDataId($tableName = 'village', $stdVillage);

        $studentAdmissionData['father_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdFatherGewog);
        $studentAdmissionData['father_Village'] = $this->getAjaxDataId($tableName = 'village', $stdFatherVillage);

        $studentAdmissionData['mother_Gewog'] = $this->getAjaxDataId($tableName = 'gewog', $stdMotherGewog);
        $studentAdmissionData['mother_Village'] = $this->getAjaxDataId($tableName = 'village', $stdMotherVillage);

        //defining an array and extracting elements of the Student Admission Model
        $studentParentsData = array();
        $studentParentsFields = array(
            'father_Name',
            'father_Cid',
            'father_Nationality',
            'father_House_No',
            'father_Thram_No',
            'father_Dzongkhag',
            'father_Gewog',
            'father_Village',
            'father_Occupation',
            'mother_Name',
            'mother_Cid',
            'mother_Nationality',
            'mother_House_No',
            'mother_Thram_No',
            'mother_Dzongkhag',
            'mother_Gewog',
            'mother_Village',
            'mother_Occupation',
            'parents_Present_Address',
            'parents_Contact_No',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentParentsFields))
            {
                $studentParentsData = array_merge($studentParentsData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }


        //defining an array and extracting elements of the Student Admission Model
        $studentGuardianData = array();
        $studentGuardianFields = array(
            'guardian_Name',
            'guardian_Occupation',
            'guardian_Relation',
            'guardian_Address',
            'guardian_Contact_No',
            'remarks',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentGuardianFields))
            {
                $studentGuardianData = array_merge($studentGuardianData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }


        //defining an array and extracting elements of the Student Admission Model
        $studentPreviousSchoolData = array();
        $studentPreviousSchoolFields = array(
            'previous_Institution',
            'aggregate_Marks_Obtained',
            'from_Date',
            'to_date',
            'previous_Education',
            'student_Id'
        );

        foreach ($studentPreviousSchoolDetailsData as $key => $value) {
            if(in_array($key, $studentPreviousSchoolFields))
            {
                $studentPreviousSchoolData = array_merge($studentPreviousSchoolData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }


        //defining an array and extracting elements of the Student Admission Model
        $studentCategoryData = array();
        $studentCategoryFields = array(
            'student_Category_Id',
            'date',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentCategoryFields))
            {
                $studentCategoryData = array_merge($studentCategoryData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }

        //defining an array and extracting elements of the Student Admission Model
        $studentNationalityData = array();
        $studentNationalityFields = array(
            'student_Country_Id',
            'student_Nationality_Id',
            'student_Id'
        );

        foreach ($studentAdmissionDataSample as $key => $value) {
            if(in_array($key, $studentNationalityFields))
            {
                $studentNationalityData = array_merge($studentNationalityData,array($key=>$value));
                unset($studentAdmissionData[$key]);
            }
        }
		
		//ID present, so it is an update
		$action = new Update('student');
		$action->set($studentAdmissionData);
		$action->where(array('id = ?' => $studentAdmissionObject->getId()));		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		//if($result instanceof ResultInterface) {
		//	if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
		//		$studentAdmissionObject->setId($newId);

                if($studentAdmissionData != NULL)
                    {


                        $parents_action = new Insert('student_parents_details');
                        //$action->values(array('item_Received_Purchased_Id'=>$newId));
                        //$action->values($goodsReceivedData);
                        $parents_action->values(array(
                            'father_name'=>$studentParentsData['father_Name'],
                            'father_cid' => $studentParentsData['father_Cid'],
                            'father_nationality' => $studentParentsData['father_Nationality'],
                            'father_house_no' => $studentParentsData['father_House_No'],
                            'father_thram_no' => $studentParentsData['father_Thram_No'],
                            'father_dzongkhag' => $studentParentsData['father_Dzongkhag'],
                            'father_gewog' => $studentParentsData['father_Gewog'],  
                            'father_village' => $studentParentsData['father_Village'],
                            'father_occupation' => $studentParentsData['father_Occupation'],
                            'mother_name' => $studentParentsData['mother_Name'],
                            'mother_cid' => $studentParentsData['mother_Cid'],
                            'mother_nationality' => $studentParentsData['mother_Nationality'],
                            'mother_house_no' => $studentParentsData['mother_House_No'],
                            'mother_thram_no' => $studentParentsData['mother_Thram_No'],
                            'mother_dzongkhag' => $studentParentsData['mother_Dzongkhag'],
                            'mother_gewog' => $studentParentsData['mother_Gewog'],
                            'mother_village' => $studentParentsData['mother_Village'],
                            'mother_occupation' => $studentParentsData['mother_Occupation'],
                            'parents_present_address' => $studentParentsData['parents_Present_Address'],
                            'parents_contact_no' => $studentParentsData['parents_Contact_No'],
                            'student_id'=>$studentAdmissionObject->getId()
                        ));

                        $sql = new Sql($this->dbAdapter);
                        $stmt = $sql->prepareStatementForSqlObject($parents_action);
                        $result = $stmt->execute();

                        $guardian_action = new Insert('student_guardian_details');
                        //$action->values(array('item_Received_Purchased_Id'=>$newId));
                        //$action->values($goodsReceivedData);
                        $guardian_action->values(array(
                            'guardian_name'=>$studentGuardianData['guardian_Name'],
                            'guardian_occupation' => $studentGuardianData['guardian_Occupation'],
                            'guardian_relation' => $studentGuardianData['guardian_Relation'],
                            'guardian_address' => $studentGuardianData['guardian_Address'], 
                            'guardian_contact_no' => $studentGuardianData['guardian_Contact_No'],
                            'remarks' => $studentGuardianData['remarks'],
                            'student_id'=>$studentAdmissionObject->getId()
                        ));

                        $sql = new Sql($this->dbAdapter);
                        $stmt = $sql->prepareStatementForSqlObject($guardian_action);
                        $result = $stmt->execute();


                        $category_action = new Insert('student_category_details');
                        //$action->values(array('item_Received_Purchased_Id'=>$newId));
                        //$action->values($goodsReceivedData);
                        $category_action->values(array(
                            'student_category_id'=>$studentCategoryData['student_Category_Id'],
                            'date' => $studentCategoryData['date'],
                            'student_id'=>$studentAdmissionObject->getId()
                        ));

                        $sql = new Sql($this->dbAdapter);
                        $stmt = $sql->prepareStatementForSqlObject($category_action);
                        $result = $stmt->execute();

                        // will insert into student_nationality_details table
                        $nationality_action = new Insert('student_nationality_details');
                        //$action->values(array('item_Received_Purchased_Id'=>$newId));
                        //$action->values($goodsReceivedData);
                        $nationality_action->values(array(
                            'student_country_id'=> $studentNationalityData['student_Country_Id'],
                            'student_nationality_id' => $studentNationalityData['student_Nationality_Id'],
                            'student_id' => $studentAdmissionObject->getId()
                        ));

                        $sql = new Sql($this->dbAdapter);
                        $stmt = $sql->prepareStatementForSqlObject($nationality_action);
                        $result = $stmt->execute();

                        //The following loop is to insert action plan
                        if($studentPreviousSchoolDetailsData != NULL)
                        {
                            foreach ($studentPreviousSchoolDetailsData as $value) {
                                $previous_school_action = new Insert('student_previous_school_details');
                                $previous_school_action->values(array(
                                    'previous_institution' => $value->getPrevious_Institution(),
                                    'aggregate_marks_obtained' => $value->getAggregate_Marks_Obtained(),
                                    'from_date' => $value->getFrom_Date(),
                                    'to_date' => $value->getTo_Date(),
                                    'previous_education' => $value->getPrevious_Education(),
                                    'student_id' => $studentAdmissionObject->getId()
                                ));

                        $sql = new Sql($this->dbAdapter);
                        $stmt = $sql->prepareStatementForSqlObject($previous_school_action);
                        $result = $stmt->execute();
                            }
                        }

                    }
                    if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
			}
			return $studentAdmissionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function findNewStudent($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('student');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
	}
	
	
	public function findAllNewStudent()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'student'))
            ->join(array('t2' => 'programmes'),
               't2.id = t1.programme_name_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                     't3.id = t1.organisation_name_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                    	 't4.id = t1.student_type_id', array('student_type'))
               ->join(array('t5' => 'student_category'), // join table with alias
                    	 't5.id = t1.student_category_id', array('student_category'));
                 //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


    public function listAllNewStudentFile($tableName, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
            
        $select->from(array('t1' => $tableName))
               ->where(array('t1.organisation_id = ?' => $organisation_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
        
    
        //To find the details of newly added student
        public function findNewStudentDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'student_type'),
                        't2.id = t1.scholarship_type', array('student_type'))
                   ->join(array('t3' => 'programmes'),
                        't3.id = t1.programmes_id', array('programme_name'))
                   ->join(array('t4' => 'organisation'),
                        't4.id = t1.organisation_id', array('organisation_name'))
                   ->join(array('t5' => 'gender'),
                        't5.id = t1.gender', array('student_gender' => 'gender'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }

        //Function to find the permanent address details of student
        public function findStudentPermanentAddressDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'dzongkhag'),
                        't2.id = t1.dzongkhag', array('dzongkhag_name'))
                   ->join(array('t3' => 'gewog'),
                        't3.id = t1.gewog', array('gewog_name'))
                   ->join(array('t4' => 'village'),
                        't4.id = t1.village', array('village_name'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }

        //Function to find the student guardian details
        public function findStudentGuardianDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'student_guardian_details'),
                        't1.id = t2.student_id', array('guardian_name', 'guardian_occupation', 'guardian_relation', 'guardian_address', 'guardian_contact_no', 'remarks'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }


        //Function to find the student parents details
		public function findStudentParentsDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'student_parents_details'),
                        't1.id = t2.student_id', array('father_name', 'father_cid', 'father_nationality', 'father_house_no', 'father_thram_no', 'father_dzongkhag', 'father_gewog', 'father_village', 'father_occupation', 'mother_name', 'mother_cid', 'mother_nationality', 'mother_house_no', 'mother_thram_no', 'mother_dzongkhag', 'mother_gewog', 'mother_village', 'mother_occupation', 'parents_present_address', 'parents_contact_no'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }


        //Function to find the student previous school details
        public function findStudentPreviousSchoolDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'student_previous_school_details'),
                        't1.id = t2.student_id', array('previous_institution', 'aggregate_marks_obtained', 'from_date', 'to_date', 'previous_education'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }


        //Function to find the student semester
        public function findStudentSemesterDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'student')) //base table
                   ->join(array('t2' => 'student_semester_registration'),
                        't1.id = t2.student_id', array('academic_year', 'semester_id'))
                   ->join(array('t3' => 'student_semester'),
                        't3.id = t2.semester_id', array('semester'))
                   ->where(array('t1.id =' .$id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }


        public function getStudentSemesterDetails($id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student')) //base table
                   ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'studentId' => 'student_id'))
                       ->join(array('t2' => 'student_semester_registration'),
                            't1.id = t2.student_id', array('academic_year', 'semester_id', 'student_section_id'))
                       ->join(array('t3' => 'student_semester'),
                            't3.id = t2.semester_id', array('semester'))
                       ->join(array('t4' => 'student_section'),
                            't4.id = t2.student_section_id', array('section'))
                       ->join(array('t5' => 'student_status_type'),
                            't5.id = t1.student_status_type_id', array('reason'))
                       ->join(array('t6' => 'programme_year'),
                            't6.id = t2.year_id', array('year'))
                       ->where(array('t1.id = ?' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
           if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($id) not found");
        }


        public function crossCheckRegisterStudent($cid, $tableName)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            if($tableName == 'student'){
                $select->from(array('t1' => $tableName));
                $select->where->like('t1.cid', $cid);
            }
            else if($tableName == 'student_registration'){
                $select->from(array('t1' => $tableName));
                $select->where->like('t1.cid', $cid);
            }

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $CID = 0;
            foreach($resultSet as $set){
                $CID = $set['id'];
            }
            return $CID;   
        }


	
	   // Function to save new student adding from college
		public function saveNewStudent(AddNewStudent $studentAdmissionObject, $programmes_id, $country_id, $dzongkhag, $gewog, $village, $year_id, $organisation_id)
		{
		$studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
		unset($studentAdmissionData['id']);

        // Generate student id to the newly added student
        //$studentAdmissionData['student_Id']  = $this->generateStudentId($studentAdmissionData['organisation_Id']);

        //$organisation_id = $studentAdmissionData['organisation_Id'];
        //$first_name = $studentAdmissionData['first_Name'];
       // $middle_name = $studentAdmissionData['middle_Name'];
        //$last_name = $studentAdmissionData['last_Name'];
        //$date_of_birth = $studentAdmissionData['date_Of_Birth'];
       // $gender = $studentAdmissionData['gender'];
       // $cid = $studentAdmissionData['cid'];
       // $student_type = $studentAdmissionData['scholarship_Type'];
       // $programme_name = $studentAdmissionData['programme_Id'];
        //$studentAdmissionData['registration_Type'] = $year_id;
        //$enrollment_year = $studentAdmissionData['enrollment_Year'];
        //$relation_type = $studentAdmissionData['relation_Type'];
        //unset($studentAdmissionData['relation_Type']);
        //$parent_contact_no = $studentAdmissionData['parent_Contact_No'];
        //unset($studentAdmissionData['parent_Contact_No']);

        //$academic_year = $studentAdmissionData['academic_Year'];
        unset($studentAdmissionData['academic_Year']);
        //$semester_id = $studentAdmissionData['semester_Id']; 
        unset($studentAdmissionData['semester_Id']); 
        unset($studentAdmissionData['year_Id']); 

        $studentAdmissionData['programme_Id'] = $programmes_id;
        $studentAdmissionData['country_Id'] = $country_id;
        $studentAdmissionData['dzongkhag'] = $dzongkhag;
        $studentAdmissionData['gewog'] = $gewog;
        $studentAdmissionData['village'] = $village;
        $studentAdmissionData['registration_Type'] = $year_id;
        $studentAdmissionData['submission_Date'] = date('Y-m-d');
        $studentAdmissionData['student_Reporting_Status'] = 'Pending';
        $studentAdmissionData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($studentAdmissionData['date_Of_Birth'],0,10)));
        //var_dump($studentAdmissionData); die();
       //Insert into student table
		$action = new Insert('student_registration');
		$action->values($studentAdmissionData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentAdmissionObject->setId($newId);
			}
            //need to insert the guardian details of new student
          //  $this->addNewStudentGuardianDetails($newId, $parent_name, $relation_type, $parent_contact_no);

            //need to insert the semester and academic year of new student
            //$this->addNewStudentSemester($newId, $year_id, $semester_id, $studentAdmissionData['enrollment_Year'], $organisation_id);

            //after generating student id, it will auto generate username and password for the particular student
            //$this->addNewUser($studentAdmissionData['student_Id'], $studentAdmissionData['date_Of_Birth'], $studentAdmissionData['organisation_Id']);

			return $studentAdmissionObject;
		}
		throw new \Exception("Database Error");
	}


    public function updateNewStudentStatus($new_student_data, $status, $organisation_id, $stdProgramme)
    {
        $i = 1;
        $studentIds = array();
        $studentList = $this->findNewStudentList($stdProgramme); 
        foreach($studentList as $value)
        {
            $studentIds[$i++] = $value['id'];
        } 

        if($new_student_data != NULL)
        {
            $i = 1;
            foreach($new_student_data as $data){ 
                $this->updateReportedStudentStatus($data, $status, $studentIds[$i], $organisation_id, $stdProgramme);
                $i++;
            }
            return;
        }
    }


    public function updateReportedStudentStatus($data, $status, $id, $organisation_id, $stdProgramme)
    { 
        if($data == '1')
        {  
            $studentAdmissionData['student_Reporting_Status'] = $status;

            $action = new Update('student_registration');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $id));

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            // To get the reported student details from student_registration table
            $studentData = array();
            $studentData = $this->getStudentData($tableName = 'student_registration', $id);
    
            $this->addNewStudentData($studentData, $organisation_id, $stdProgramme); 
        }
        return;
    }


    public function findNotReportedStudentList($organisation_id, $stdProgramme)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_registration'))
               ->where(array('t1.programme_id' => $stdProgramme, 't1.organisation_id' => $organisation_id, 't1.student_reporting_status' => 'Pending', 't1.admission_year = YEAR(NOW())'));
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getStudentData($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        
        //if($tableName == 'item_sub_category'){
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth', 'cid', 'contact_no', 'country_id', 'dzongkhag', 'gewog', 'village', 'parent_name', 'parents_contact_no', 'relationship_id', 'admission_year', 'student_type_id', 'programme_id', 'registration_type', 'organisation_id'));
            //$select->where->like('id = ?' => $code);
            $select->where(array('t1.id = ?' => $id));
        //}
        

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
            $studentData[$set['id']]['gender'] = $set['gender'];
            $studentData[$set['id']]['date_of_birth'] = $set['date_of_birth'];
            $studentData[$set['id']]['cid'] = $set['cid'];
            $studentData[$set['id']]['contact_no'] = $set['contact_no'];
            $studentData[$set['id']]['country_id'] = $set['country_id'];
            $studentData[$set['id']]['dzongkhag'] = $set['dzongkhag'];
            $studentData[$set['id']]['gewog'] = $set['gewog'];
            $studentData[$set['id']]['village'] = $set['village'];
            $studentData[$set['id']]['parent_name'] = $set['parent_name'];
            $studentData[$set['id']]['parents_contact_no'] = $set['parents_contact_no'];
            $studentData[$set['id']]['relationship_id'] = $set['relationship_id'];
            $studentData[$set['id']]['admission_year'] = $set['admission_year'];
            $studentData[$set['id']]['student_type_id'] = $set['student_type_id'];
            $studentData[$set['id']]['programme_id'] = $set['programme_id'];
            $studentData[$set['id']]['registration_type'] = $set['registration_type'];
            $studentData[$set['id']]['organisation_id'] = $set['organisation_id'];
        }
        return $studentData;
    }


    // function to add new reported student data into student table
    public function addNewStudentData($studentData, $organisation_id, $stdProgramme)
    {
        $academic_session_id = $this->getAcademicSessionId($stdProgramme);

        if($academic_session_id == 1){
            $start_year = date('Y')-1;
            $end_year = date('Y');
            $academic_year = $start_year.'-'.$end_year;
        }else if($academic_session_id == 2){
            $start_year = date('Y');
            $end_year = date('Y')+1;
            $academic_year = $start_year.'-'.$end_year;
        }

        $tempStudentId = $this->generateTempStudentId($organisation_id);
      
        foreach($studentData as $key => $value)
        {
            if($value['registration_type'] == 1){
                $semester = 1;
            } else if($value['registration_type'] == 2){
                $semester = 3;
            }else if($value['registration_type'] == 3){
                $semester = 5;
            }else if($value['registration_type'] == 4){
                $semester = 7;
            }else if($value['registration_type'] == 5){
                $semester = 9;
            }else if($value['registration_type'] == 1){
                $semester = 11;
            }

            $action = new Insert('student');
            $action->values(array(
                'first_name' => $value['first_name'],
                'middle_name' => $value['middle_name'],
                'last_name' => $value['last_name'],
                'cid' => $value['cid'],
                'gender' => $value['gender'],
                'date_of_birth' => $value['date_of_birth'],
                'contact_no' => $value['contact_no'],
                'country_id' => $value['country_id'],
                'dzongkhag' => $value['dzongkhag'],
                'gewog' => $value['gewog'],
                'village' => $value['village'],
                'scholarship_type' => $value['student_type_id'],
                'enrollment_year' => $value['admission_year'],
                'programmes_id' => $value['programme_id'],
                'organisation_id' => $value['organisation_id'],
                'student_id' => $tempStudentId
            ));
            
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            if($result instanceof ResultInterface){
                if($newId = $result->getGeneratedValue()){
                    $guardian_action = new Insert('student_relation_details');
                    $guardian_action->values(array(
                        'parent_name' => $value['parent_name'],
                        'parent_contact_no' => $value['parents_contact_no'],
                        'relation_type' => $value['relationship_id'],
                        'student_id' => $newId));

                    $sql = new Sql($this->dbAdapter);
                    $stmt = $sql->prepareStatementForSqlObject($guardian_action);
                    $result = $stmt->execute();

                    $section_action = new Insert('student_semester_registration');
                    $section_action->values(array(
                        'year_id' => $value['registration_type'],
                        'semester_id' => $semester,
                        'academic_session_id' => $academic_session_id,
                        'student_section_id' => '1',
                        'enrollment_year' => $value['admission_year'],
                        'academic_year' => $academic_year,
                        'updated_date' => date('Y-m-d'),
                        'student_id' => $newId));

                    $sql = new Sql($this->dbAdapter);
                    $stmt = $sql->prepareStatementForSqlObject($section_action);
                    $result = $stmt->execute();
                }
            }
       }
       return;  
    } 


    //Function to add section to new student
    public function updateNewStudentSection($data, $programmesId)
    {
        //get the student list
        $i=1;
        $sectionIds = array();
        $sectionData = $this->getNewReportedStudentList($programmesId);
        foreach($sectionData as $value)
        {
            $sectionIds[$i++] = $value['id'];
        }

        if($data != NULL)
        {
            $i =1;
            // Its an update
            foreach ($data as $value) {
                $studentAdmissionData['student_Section_Id'] = $value;
                //$studentAdmissionData['id'] = $studentIds[$i];

                $action = new Update('student_semester_registration');
                $action->set($studentAdmissionData);
                $action->where(array('id = ?' => $sectionIds[$i]));

                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();
                $i++;
            }
        }

    }

    public function updateEditedStudentSection($data, $programmesId, $yearId, $organisation_id)
    {
        //get the student list
        $i=1;
        $editedSectionIds = array();
        $editedSectionData = $this->getEditSectionStudentList($programmesId, $yearId, $organisation_id);
        foreach($editedSectionData as $value)
        {
            $editedSectionIds[$i++] = $value['id'];
        }
        if($data != NULL)
        {
            $i=1;
            // Its an update
            foreach ($data as $value) {
                $studentAdmissionData['student_section_id'] = $value;
                //$studentAdmissionData['id'] = $studentIds[$i];

                $action = new Update('student_semester_registration');
                $action->set($studentAdmissionData);
                $action->where(array('student_id = ?' => $editedSectionIds[$i]));

                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();
                $i++;
            }
        }
    }

    //Function to add house to new student
    public function saveNewStudentHouse($data1, $programmesId, $yearId, $organisation_id)
    { 
        $i = 1;
        $houseIds = array();
        $houseData = $this->getStudentHouseList($programmesId, $yearId, $organisation_id); 

        foreach($houseData as $key => $value)
        {
            $houseIds[$i++] = $key;
        }

        // the following loop is to insert student house id 
        if($data1 != NULL)
        {
            $i = 1;
            foreach($data1 as $value1)
            {
                $studentDetailsId = $this->getStudentDetailsIds($houseIds[$i]); 

                $action = new Insert('student_house_details');
                $action->values(array(
                    'student_house_id' => $value1,
                    'student_id' => $studentDetailsId
                ));

                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();
                $i++;
            }
            return;
        }
        throw new \Exception("Database Error"); 
    }


    public function updateEditedStudentHouse($data1, $programmesId, $yearId, $organisation_id)
    {
        //get the student list
        $i=1;
        $editedHouseIds = array();
        $editedHouseData = $this->getEditHouseStudentList($programmesId, $yearId, $organisation_id);
        foreach($editedHouseData as $value)
        {
            $editedHouseIds[$i++] = $value['id'];
        }

        if($data1 != NULL)
        {
            $i =1;
            // Its an update
            foreach ($data1 as $value) {
                $studentAdmissionData['last_Updated'] = date("Y-m-d h:i:s");
                $studentAdmissionData['student_House_Id'] = $value;
                //$studentAdmissionData['id'] = $studentIds[$i];

                $action = new Update('student_house_details');
                $action->set($studentAdmissionData);
                $action->where(array('id = ?' => $editedHouseIds[$i]));

                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();
                $i++;
            }
        }
    }


    public function crossCheckSemesterAcademicYear($registration_type, $academicYear)
    {
        $date = date('Y-m-d');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar'))
               ->columns(array('academic_year'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
        $select->where(array('t1.academic_year' => $academicYear));
        $select->where->like('t2.academic_event', $registration_type);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $semesterAcademicYear = 0;
        foreach($resultSet as $set){
            $semesterAcademicYear = $set['academic_year'];
        }
        return $semesterAcademicYear;  
    }

    // Function to insert the upgraded semester of student into the student_semester_registraion table
    public function updateStudentSemester($registration_type, $semester_data, $programmesId, $yearId, $studentName, $studentId, $organisation_id)
    {       
        $i = 1;
        $studentIds = array();
        $studentData = $this->getSemesterRegistrationStudentList($programmesId, $yearId, $studentName, $studentId, $organisation_id);

        foreach($studentData as $key=>$value)
        {
           // $studentIds[$i++] = $value['id'];
            $studentIds[$i++] = $key;
        }

        $current_academic_session = $this->getAcademicSession($organisation_id);

       if($current_academic_session == 1)
       {
        $previous_academic_session = 2;
       }
       else
       {
        $previous_academic_session = 1;
       }

       $previous_semester = $this->getPreviousStudentSemester($programmesId, $yearId, $previous_academic_session); 

       $current_semester = $previous_semester+1; 

        if($semester_data != NULL)
        {
            $i = 1;

            foreach($semester_data as $data)
            {
                $fromDate = $this->getFromDate('Semester Registration');
                $toDate = $this->getToDate('Semester Registration');

                $studentDetailsId = $this->getStudentDetailsIds($studentIds[$i]);
                $student_section = $this->getStudentCurrentSection($tableName = 'student_semester_registration', $studentDetailsId);
                $enrollment_year = $this->getStudentEnrollmentYear($tableName = 'student_semester_registration', $studentDetailsId);

                $this->updateSemester($registration_type, $data, $studentDetailsId, $student_section, $enrollment_year, $current_semester, $current_academic_session);
                $i++;
            }
            return;
        }
    }


    public function getStudentDetailsIds($student_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->columns(array('id', 'student_id'))
               ->where(array('t1.student_id' => $student_id));
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $studentDetailsId = NULL;
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
            $studentDetailsId = $set['id'];
        }

        return $studentDetailsId;
    }


    public function updateSemester($registration_type, $data, $student_id, $student_section, $enrollment_year, $current_semester, $current_academic_session)
    {
        if($current_semester == 1 || $current_semester == 2){
            $year = 1;
        }else if($current_semester == 3 || $current_semester == 4){
            $year = 2;
        }else if($current_semester == 5 || $current_semester == 6){
            $year = 3;
        }else if($current_semester == 7 || $current_semester == 8){
            $year = 4;
        } else if($current_semester == 9 || $current_semester == 10){
            $year = 5;
        }else if($current_semester == 11 || $current_semester == 12){
            $year = 6;
        }

        if($data == '1')
        {
            $date = date('Y-m-d');
            $academic_year = $this->getAcademicYear($registration_type, $date);
            
            $action = new Update('student_semester_registration');
            $action->set(array('year_id' => $year, 'semester_id' => $current_semester, 'academic_session_id' => $current_academic_session, 'student_section_id' => $student_section, 'enrollment_year' => $enrollment_year, 'academic_year' => $academic_year, 'updated_date' => $date));
                $action->where(array('student_id = ?' => $student_id));

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
    
        }
    }



    public function crossCheckSemesterUpdatedDate($registration_type, $semesterUpdatedDate)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar'))
               ->columns(array('id'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $semesterUpdatedDate, 't1.to_date >= ?' => $semesterUpdatedDate));
       // $select->where(array('t1.academic_year' => $academicYear));
        $select->where->like('t2.academic_event', $registration_type);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $semesterUpdateDate = 0;
        foreach($resultSet as $set){
            $semesterUpdateDate = $set['id'];
        }
        return $semesterUpdateDate;
    }

    public function crossCheckUpdatedStudent($student_id, $fromDate, $toDate)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_semester_registration'));
        $select->where(array('student_id' => $student_id))
               ->where(array('t1.updated_date >= ?' => $fromDate, 't1.updated_date <= ?' => $toDate));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $updatedDate = NULL;
        foreach($resultSet as $set){
            $updatedDate = $set['student_id'];
        }
        return $updatedDate;
    }


    public function crossCheckStudentSemesterRegistration($student_id, $semester, $academic_year)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_semester_registration'));
        $select->where(array('student_id' => $student_id, 'academic_year' => $academic_year))
               ->where(array('semester_id' => $semester));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $registeredStudent = NULL;
        foreach($resultSet as $set){
            $registeredStudent = $set['student_id'];
        }
        return $registeredStudent;
    }


    public function getUpdatedSemesterStudentId($programmesId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id'))
               ->where(array('t1.programmes_id' => $programmesId))
               ->where->notLike('t1.student_id', "TEMP_%");
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getFromDate($registration_type)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar'))
               ->columns(array('from_date'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => date('Y-m-d'), 't1.to_date >= ?' => date('Y-m-d')));
        $select->where->like('t2.academic_event', $registration_type);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $fromDate = 0;
        foreach($resultSet as $set){
            $fromDate = $set['from_date'];
        }
        return $fromDate;
    }


    public function getToDate($registration_type)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar'))
               ->columns(array('to_date'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => date('Y-m-d'), 't1.to_date >= ?' => date('Y-m-d')));
        $select->where->like('t2.academic_event', $registration_type);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $toDate = 0;
        foreach($resultSet as $set){
            $toDate = $set['to_date'];
        }
        return $toDate;
    }


    public function getSemesterUpdatedDate($tableName, $studentId, $academicYear)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('updated_date'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $studentId))
               ->where(array('t1.academic_year' => $academicYear));
              // ->order('id DESC')->limit(1);        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $updatedDate = NULL;
        
        foreach($resultSet as $set)
        {
           $updatedDate = $set['updated_date'];
        }
        return $updatedDate;
    }


    public function getStudentCurrentSection($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('student_section_id'));
        $select->where(array('t1.student_id = ?' => $studentId));      

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $sectionId = NULL;
        
        foreach($resultSet as $set)
        {
           $sectionId = $set['student_section_id'];
        }
        return $sectionId;
    }


    public function getstdCurrentSemester($student_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_semester_registration'));
        $select->columns(array('semester_id'));
        $select->where(array('t1.student_id = ?' => $student_id));      

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $semesterId = NULL;
        foreach($resultSet as $set)
        {
           $semesterId = $set['semester_id'];
        }
        return $semesterId;
    }


    public function getStudentEnrollmentYear($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('enrollment_year'));
        $select->where(array('t1.student_id = ?' => $studentId));      

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $enrollment_year = NULL;
        
        foreach($resultSet as $set)
        {
           $enrollment_year = $set['enrollment_year'];
        }
        return $enrollment_year;
    }


    public function updateNotReportedStudent(StudentSemesterRegistration $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        unset($studentAdmissionData['studentId']);
        unset($studentAdmissionData['first_Name']);
        unset($studentAdmissionData['middle_Name']);
        unset($studentAdmissionData['last_Name']);
        unset($studentAdmissionData['semester']);
        unset($studentAdmissionData['section']);
        unset($studentAdmissionData['student_section_id']);
        unset($studentAdmissionData['year']);
        unset($studentAdmissionData['year_Id']);

       // $student_section_id = $studentAdmissionData['student_Section_Id'];
        $student_id = $studentAdmissionData['student_Id'];
        $academic_year = $studentAdmissionData['academic_Year'];
        $semester_id = $studentAdmissionData['semester_Id'];
        $student_status_type_id = $studentAdmissionData['student_Status_Type_Id'];
        $remarks = $studentAdmissionData['remarks']; 

        //need to get the file locations and store them in database
        $file_name = $studentAdmissionData['file'];
        $studentAdmissionData['file'] = $file_name['tmp_name'];

       //Insert into student table
        $action = new Insert('student_not_reported_details');
        $action->values($studentAdmissionData);
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }

            $this->updateStudentStatus($student_id, $student_status_type_id);
            if($student_status_type_id == '1'|| $student_status_type_id == '7') {
                return $studentAdmissionObject;    
            } else {
                $this->deleteStudentCurrentMarks($student_id, $student_status_type_id, $academic_year);
            }
            return $studentAdmissionObject;
        }
        throw new \Exception("Database Error");
    }

    public function deleteStudentCurrentMarks($id, $student_status_type_id, $academic_year)
    {
        $action = new Delete('assessment_marks');
        $action->where(array('student_id = ?' => $id));
        $action->where(array('academic_year = ?' => $academic_year));


        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    public function updateStudentStatus($id, $student_status_type_id)
    {
        $action = new Update('student');
        $action->set(array('student_status_type_id' => $student_status_type_id));
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    /*
    * Adding Guardian details to new student
    */
    
    public function addNewStudentGuardianDetails($student_id, $parent_name, $relation_type, $parent_contact_no)
    {
        $action = new Insert('student_relation_details');
        $action->values(array(
            'student_id' => $student_id,
            'parent_name' => $parent_name,
            'relation_type' => $relation_type,
            'parent_contact_no' => $parent_contact_no
        ));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }

    /*
    *Add semester to new student
    */
    public function addNewStudentSemester($student_id, $year_id, $semester_id, $enrollment_year, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getCurrentAcademicYear($academic_event_details);

        $current_academic_session = $this->getAcademicSession($organisation_id);

        $action = new Insert('student_semester_registration');
        $action->values(array(
            'student_id' => $student_id,
            'year_id' => $year_id,
            'semester_id' => $semester_id,
            'academic_session_id' => $current_academic_session,
            'student_section_id' => '1',
			'enrollment_year' => $enrollment_year,
			'academic_year' => $academic_year,
            'updated_date' => date('Y-m-d')
        ));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }

/**
	* @param int/String $id
	* @return StudentAdmission
	* @throws \InvalidArgumentException
	*/
	
	public function findStudentType($id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('student_type');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student Type with given ID: ($id) not found");
	}

    public function findHouse($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('student_house');
        $select->where(array('id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student  with given ID: ($id) not found");
    }
	
	/**
	* @return array/ StudentAdmission()
	*/
	public function findAllStudentType($tableName)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
	}


    public function listAllStudentHouse($tableName, $organisation_id)
    {
         $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)); // join expression
			$select->where(array('t1.organisation_id' => $organisation_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                $resultSet = new ResultSet();
                $resultSet->initialize($result);

                $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                return $resultSet->initialize($result); 
            }

            return array();
    }
        
    /**
    * 
    * @param type $id
    * 
    * to find the StudentType for a given $id
    */
    public function findStudentTypeDetails($id) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_type'));
       
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                    return $resultSet->initialize($result); 
            }
            
            return array();
    }


    public function crossCheckStudentType($stdType)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_type'));
        $select->where->like('student_type', $stdType);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $studentType = 0;
        foreach($resultSet as $set){
            $studentType = $set['id'];
        }
        return $studentType;
    }

            
        
    /**
	 * 
	 * @param type $StudentType
	 * 
	 * to save StudentType
	 */

   public function saveStudentType(StudentType $studentAdmissionObject)
		{
		$studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
		unset($studentAdmissionData['id']);
		
		if($studentAdmissionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_type');
			$action->set($studentAdmissionData);
			$action->where(array('id = ?' => $studentAdmissionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_type');
			$action->values($studentAdmissionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentAdmissionObject->setId($newId);
			}
			return $studentAdmissionObject;
		}
		
		throw new \Exception("Database Error");
	}


    public function crossCheckHouse($house_name)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_house'));
        $select->where->like('house_name', $house_name.'%');
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $houseName = 0;
        foreach($resultSet as $set){
            $houseName = $set['id'];
        }
        return $houseName;
    }


    public function crossCheckStudentPermanentAddress($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_nationality_details'));
        $select->where->like('t1.student_id', $id);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $studentAddress = 0;
        foreach($resultSet as $set){
            $studentAddress = $set['student_id'];
        }
        return $studentAddress;
    }

    public function crossCheckStdParentDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_parents_details'));
        $select->where->like('student_id', $id);
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $parentDetails = 0;
        foreach($resultSet as $set){
            $parentDetails = $set['id'];
        }
        return $parentDetails;
    }


    //Function to save new house
    public function saveNewHouse(StudentHouse $studentAdmissionObject)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
        unset($studentAdmissionData['id']);
        
        if($studentAdmissionObject->getId()) {
            //ID present, so it is an update
            $action = new Update('student_house');
            $action->set($studentAdmissionData);
            $action->where(array('id = ?' => $studentAdmissionObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('student_house');
            $action->values($studentAdmissionData);
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        
        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $studentAdmissionObject->setId($newId);
            }
            return $studentAdmissionObject;
        }
        
        throw new \Exception("Database Error");
    }


    /**
	 * 
	 * @param type $StudentType
	 * 
	 * to Delete StudentType
	 */

	public function deleteStudentType(StudentAdmission $studentAdmissionObject)
	{

		$action = new Delete('student_type');
		$action->where(array('id = ?' => $studentAdmissionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}

	/**
	* @param int/String $id
	* @return StudentAdmission
	* @throws \InvalidArgumentException
	*/
	public function findStudentCategory($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('student_category');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("student category with given ID: ($id) not found");
	}
	
	
	public function findAllStudentCategory()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student_category')); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
    
        
        public function findStudentCategoryDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'student_category')); //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->studentAdmissionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }

        public function crossCheckStudentCategory($stdCategory)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'student_category'));
            $select->where->like('t1.student_category', $stdCategory);
                
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $studentCategory = 0;
            foreach($resultSet as $set){
                $studentCategory = $set['id'];
            }
            return $studentCategory;            
        }


        public function crossCheckStudentParent($parent_type, $id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            if($parent_type == 'Father'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id, 't1.relation_type' => '1'));
            }
            if($parent_type == 'Mother'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id, 't1.relation_type' => '2'));
            }

            if($parent_type == 'Guardian'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id));
                $select->where->notLike('t1.relation_type', '1');
                $select->where->notLike('t1.relation_type', '2');
            }
                
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $studentParent = NULL;
            foreach($resultSet as $set){
                $studentParent = $set['id'];
            }
            return $studentParent;  
        }


        public function crossCheckStudentParentCid($parent_type, $id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            if($parent_type == 'Father'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id, 't1.relation_type' => '1'));
            }

            if($parent_type == 'Mother'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id, 't1.relation_type' => '2'));
            }

            if($parent_type == 'Guardian'){
                $select->from(array('t1' => 'student_relation_details'));
                $select->where(array('t1.student_id' => $id));
                $select->where->notLike('t1.relation_type', '1');
                $select->where->notLike('t1.relation_type', '2');
            }
                
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $parentCid = NULL;
            foreach($resultSet as $set){
                $parentCid = $set['parent_cid'];
            }
            return $parentCid;  
        }
		
		public function saveStudentCategory(StudentCategory $studentAdmissionObject)
		{
		$studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
		unset($studentAdmissionData['id']);
		
		if($studentAdmissionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('student_category');
			$action->set($studentAdmissionData);
			$action->where(array('id = ?' => $studentAdmissionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('student_category');
			$action->values($studentAdmissionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $studentAdmissionObject->setId($newId);
			}
			return $studentAdmissionObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function deleteStudentCategory(StudentAdmission $studentAdmissionObject)
	{

		$action = new Delete('student_category');
		$action->where(array('id = ?' => $studentAdmissionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


    public function getFileName($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'new_student_list_file'));
        $select->columns(array('file_name'));
         
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $fileLocation;
        foreach($resultSet as $set)
        {
            $fileLocation = $set['file_name'];
        }
        
        return $fileLocation;
    }


    /*
    * Function to generate the student id for new student
    */    
    public function generateStudentId($organisation_id)
    {
        
      $sql1 = new Sql($this->dbAdapter);
      $select1 = $sql1->select();

      $select1->from(array('t1' => 'organisation'));
      $select1->columns(array('organisation_code'));
      $select1->where(array('t1.id = ?' => $organisation_id));
      $stmt1 = $sql1->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);
        
        $code = NULL;
        foreach($resultSet1 as $set1)
            $code = $set1['organisation_code'];

        
        $Year = date('Y');
        $format = $code.substr($Year, 2);
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->columns(array('student_id'));
        $select->where->like('student_id',''.$format.'%');
        $select->order('student_id DESC');
        $select->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_id = NULL;
        
        foreach($resultSet as $set)
            $student_id = $set['student_id'];
        
        //first student of the year
        if($student_id == NULL){
            $generated_id = $code.substr(date('Y'),2).'0001';
        }
        else{
            //need to get the last 4 digits and increment it by 1 and convert it back to string
            $number = substr($student_id, -4);
            $number = (int)$number+1;
            $number = strval($number);
            while (mb_strlen($number)<4)
                $number = '0'. strval($number);
            
            $generated_id = $code.substr(date('Y'),2).$number;
        }
        
        return $generated_id;
    }



    /*
    * Function to generate the student id for new student
    */    
    public function generateTempStudentId($organisation_id)
    {
        
      $sql1 = new Sql($this->dbAdapter);
      $select1 = $sql1->select();

      $select1->from(array('t1' => 'organisation'));
      $select1->columns(array('organisation_code'));
      $select1->where(array('t1.id = ?' => $organisation_id));
      $stmt1 = $sql1->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);
        
        $code = NULL;
        foreach($resultSet1 as $set1)
            $code = $set1['organisation_code'];

        
        $Year = date('Y');
        $format = 'TEMP_'.$code.substr($Year, 2);
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->columns(array('student_id'));
        $select->where->like('student_id',''.$format.'%');
        $select->order('student_id DESC');
        $select->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $student_id = NULL;
        
        foreach($resultSet as $set)
            $student_id = $set['student_id'];
        
        //first student of the year
        if($student_id == NULL){
            $generated_id = 'TEMP_'.$code.substr(date('Y'),2).'0001';
        }
        else{
            //need to get the last 4 digits and increment it by 1 and convert it back to string
            $number = substr($student_id, -4);
            $number = (int)$number+1;
            $number = strval($number);
            while (mb_strlen($number)<4)
                $number = '0'. strval($number);
            
            $generated_id = 'TEMP_'.$code.substr(date('Y'),2).$number;
        }
        
        return $generated_id;
    }


    public function getAcademicSessionId($stdProgramme)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'programmes'));
        $select->columns(array('academic_session_id'))
               ->where(array('t1.id' => $stdProgramme));
         
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academicSessionId = NULL;
        foreach($resultSet as $set)
        {
            $academicSessionId = $set['academic_session_id'];
        }
        
        return $academicSessionId;
    }
	
	
	/*
	* Add new user to the system
	* username is the student id and password is the date of birth
	*/
	
	public function addNewUser($student_id, $dob, $organisation_id)
	{
        $abbr = $this->getOrganisationAbbr($organisation_id);

		$action = new Insert('users');
		$action->values(array(
			'username' => $student_id,
			'password' => md5($student_id),
			'role' => $abbr.'_STUDENT',
			'region' => $organisation_id,
            'user_type_id' => '2',
		));
		
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

    /*
    * Return an id for the programme  given the name
    * this is done as the ajax returns a value and not the id
    */    
    public function getAjaxDataId($tableName, $code)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('id'));
        if($tableName == 'programmes'){
            $select->where(array('programme_name = ?' => $code));
        }

        if($tableName == 'gewog'){
            $select->where(array('gewog_name = ?' => $code));
        }

        if($tableName == 'village'){
            $select->where(array('village_name = ?' => $code));
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

    public function selectStudentProgramme($tableName, $columnName, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('id', $columnName))
               ->where(array('t1.organisation_id = ?' => $organisation_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['id']] = $set[$columnName];
        }
        return $selectData;
    }


    public function getStudentCurrentProgramme($student_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->where(array('t1. id = ? ' => $student_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentAdmissionPrototype);
            }

            throw new \InvalidArgumentException("Student with given ID: ($student_id) not found");
    }


	public function listSelectData($tableName, $columnName, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'student_type')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName)); 
        }
        else if ($tableName == 'academic_session') 
        {
            $select->from(array('t1' => $tableName));  
            $select->columns(array('id',$columnName));
        }

        else if($tableName == 'gender')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }
        elseif($tableName == 'organisation')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }
        elseif($tableName == 'programmes')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }
        elseif ($tableName == 'student_semester')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif ($tableName == 'programme_year')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif($tableName == 'student_section')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif($tableName == 'student_house')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
			$select->where(array('t1.organisation_id' => $organisation_id));
        }

        elseif($tableName == 'student_category')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif($tableName == 'country')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif($tableName == 'nationality')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif($tableName == 'student_status_type')
        {
            //$select->from(array('t1' => $tableName));
	    //$select->columns(array('id', $columnName));
            $select->from(array('t1' => $tableName),array('id', $columnName))
               ->where(array('t1.id != ? ' => '7'));
        }

        elseif($tableName == 'relation_type')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif ($tableName == 'dzongkhag') {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        elseif ($tableName == 'school') {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
	}


    public function listSelectData1($tableName, $columnName, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));  
        $select->columns(array('id',$columnName))
            ->where(array('t1.organisation_id = ?' => $organisation_id)); 
        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['id']] = $set[$columnName];
        }
        return $selectData;
    }


    public function listSelectAcademicYear($tableName)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));  
        $select->columns(array('academic_year'))
               ->order('academic_year DESC'); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['academic_year']] = $set['academic_year'];
        }
        return $selectData;
    }

    public function getSemesterRegistrationAnnouncement($registration_type, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar'))
                ->join(array('t2' => 'academic_calendar_events'), 
                            't1.academic_event = t2.id', array('academic_event'));
        $select->where(array('t1.from_date <= ? ' => date('Y-m-d'), 't1.to_date >= ? ' => date('Y-m-d')));
        $select->where(array('t2.academic_event' => $registration_type, 't2.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getAcademicYear($registration_type, $date)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar')) 
               ->columns(array('academic_year'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
        $select->where(array('t2.academic_event' => $registration_type));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $academicYear = NULL;
        foreach($resultSet as $set)
        {
            $academicYear = $set['academic_year'];
        }
        return $academicYear;
    }


	// Uploading students lists file in excel

	public function saveStudentListFile(UploadStudentLists $studentAdmissionObject)
		{

    		$studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
    		unset($studentAdmissionData['id']);

            //need to get the file locations and store them in database
            $file_name = $studentAdmissionData['file_Name'];
            $studentAdmissionData['file_Name'] = $file_name['tmp_name'];

            $pathinfo = pathinfo($studentAdmissionData['file_Name']);

             if($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls'){

    		
    		//ID is not present, so its an insert
    		$action = new Insert('new_student_list_file');
    		$action->values($studentAdmissionData);
    		$sql = new Sql($this->dbAdapter);
    		$stmt = $sql->prepareStatementForSqlObject($action);
    		$result = $stmt->execute();

    		
    		if($result instanceof ResultInterface) {
    			if($newId = $result->getGeneratedValue()){
    				//when a value has been generated, set it on the object
    				echo $studentAdmissionObject->setId($newId);
    			}

                $inputFileName = $studentAdmissionData['file_Name'];

                $this->importStudentExcelData($inputFileName);

    			return $studentAdmissionObject;
    		}
        }
            else {
                echo "Please Select Valid Excel File";
            }
    		
    		throw new \Exception("Database Error");
	   }


    public function importStudentExcelData($inputFileName)
    {

        $objPHPExcel = new \PHPExcel_Reader_Excel5();

        $document = $objPHPExcel->load($inputFileName);

        // Get worksheet dimension
        $sheet = $document->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        // Loop through each of row of the worksheet in turn
        $array = array();
        for($row = 2; $row <= $highestRow; $row++){
            $highestColumn = $sheet->getHighestColumn();
            for($col = 0; $col <= 21; $col++){
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $array[$row][$col] = $val;
            }
    } 

        $this->insertStudentData($array);
       }


    public function insertStudentData($studentDataArray)
    {
        foreach ($studentDataArray as $value) {

            $excel_date = $value[5];
            $unix_date = ($excel_date - 25569)*86400;
            $excel_date = 25569+($unix_date/86400);
            $unix_date = ($excel_date - 25569)*86400;
            $date_of_birth = gmdate("Y-m-d", $unix_date);

            $excel_date1 = $value[13];
            $unix_date1 = ($excel_date1 - 25569)*86400;
            $excel_date1 = 25569+($unix_date1/86400);
            $unix_date1 = ($excel_date1 - 25569)*86400;
            $submission_date = gmdate("Y-m-d", $unix_date1);

            $action = new Insert('student_registration');
            $action->values(array(                
                'rank' => $value[0],
                'first_name' => $value[1],
                'middle_name' => $value[2],
                'last_name' => $value[3],
                'gender' => $value[4],
                'date_of_birth' => $date_of_birth,
                'cid' => $value[6],
                'registration_no' => $value[7],
                'aggregate' => $value[8],
                'parent_name' => $value[9],
                'parents_contact_no' => $value[10],
                'relationship_id' => $value[11],
                'admission_year' => $value[12],
                'submission_date' => $submission_date,
                'student_reporting_status' => 'Pending',
                'student_type_id' => $value[14],
                'moe_student_code' => $value[15],
                'twelve_indexnumber' => $value[16],
                'twelve_stream' => $value[17],
                'twelve_student_type' => $value[18],
                'twelve_school' => $value[19],
                'programme_id' => $value[20],
                'organisation_id' => $value[21],
        ));
        
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
        }

        return;
    }


    // Uploading students lists file in excel
    public function saveBulkStudentFile(UploadStudentLists $studentAdmissionObject, $organisation_id)
    {
        $studentAdmissionData = $this->hydrator->extract($studentAdmissionObject);
            unset($studentAdmissionData['id']);

            //need to get the file locations and store them in database
            $file_name = $studentAdmissionData['file_Name'];
            $studentAdmissionData['file_Name'] = $file_name['tmp_name'];

            $pathinfo = pathinfo($studentAdmissionData['file_Name']);

             if($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls'){

            
            //ID is not present, so its an insert
            $action = new Insert('new_student_list_file');
            $action->values($studentAdmissionData);
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            
            if($result instanceof ResultInterface) {
                if($newId = $result->getGeneratedValue()){
                    //when a value has been generated, set it on the object
                    echo $studentAdmissionObject->setId($newId);
                }

                $inputFileName = $studentAdmissionData['file_Name'];

                $this->importBulkStudentExcelData($inputFileName, $organisation_id);

                return $studentAdmissionObject;
            }
        }
            else {
                echo "Please Select Valid Excel File";
            }
            
            throw new \Exception("Database Error");
    }


    public function importBulkStudentExcelData($inputFileName, $organisation_id)
    {

        $objPHPExcel = new \PHPExcel_Reader_Excel5();

        $document = $objPHPExcel->load($inputFileName);

        // Get worksheet dimension
        $sheet = $document->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        // Loop through each of row of the worksheet in turn
        $array = array();
        for($row = 2; $row <= $highestRow; $row++){
            $highestColumn = $sheet->getHighestColumn();
            for($col = 0; $col <= 9; $col++){
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $array[$row][$col] = $val;
            }
		}

        $this->insertBulkStudentData($array, $organisation_id);
       }


    public function insertBulkStudentData($studentDataArray, $organisation_id)
    {
        
        foreach ($studentDataArray as $value) {
            
            $excel_date = $value[4];
            $unix_date = ($excel_date - 25569)*86400;
            $excel_date = 25569+($unix_date/86400);
            $unix_date = ($excel_date - 25569)*86400;
            $date_of_birth = gmdate("Y-m-d", $unix_date);

           /* $excel_date1 = $value[13];
            $unix_date1 = ($excel_date1 - 25569)*86400;
            $excel_date1 = 25569+($unix_date1/86400);
            $unix_date1 = ($excel_date1 - 25569)*86400;
            $submission_date = gmdate("Y-m-d", $unix_date1);*/

          //  $tempStudentId = $this->generateTempStudentId($organisation_id);

            $action = new Insert('student_registration');
            $action->values(array(                
                'first_name' => $value[0],
                'middle_name' => $value[1],
                'last_name' => $value[2],
                'gender' => $value[3],
                'date_of_birth' => $date_of_birth,
                'cid' => $value[5],
                'admission_year' => $value[6],
                'submission_date' => date('Y-m-d'),
                'student_reporting_status' => 'Pending',
                'student_type_id' => $value[7],
                'programme_id' => $value[8],
                'registration_type' => $value[9],
                'organisation_id' => $organisation_id,
        ));
        
            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
        }

        return;
    }

    // Function to insert the student guardian details in bulk got from the excel by extracting
    public function insertStudentGuardianDetails($studentId, $guardianName, $guardianContactNo, $guardianRelation)
    {
        $action = new Insert('student_guardian_details');
        $action->values(array(
            'guardian_name' => $guardianName,
            'guardian_contact_no' => $guardianContactNo,
            'guardian_relation' => $guardianRelation,
            'student_id' => $studentId,
        ));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    // Function to insert the student semester registration in bulk got from the excel by extracting
    public function insertStudentSemesterDetails($studentId, $academicYear, $stdSemester)
    {
        $action = new Insert('student_semester_registration');
        $action->values(array(
            'student_section_id' => '1',
            'student_id' => $studentId,
            'academic_year' => $academicYear,
            'semester_id' => $stdSemester,
        ));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


        /*
    *To get the list of students to view their details
    **/
    public function getStudentLists($stdName, $stdId, $stdCid, $stdProgramme, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
		if($organisation_id == '1'){
			$select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'gender'),
                    't4.id = t1.gender', array('stdGender' => 'gender'))
	       ->join(array('t5' => 'student_status_type'),
		    't5.id = t1.student_status_type_id', array('reason'))
		->join(array('t6' => 'organisation'),
			't6.id = t1.organisation_id', array('abbr'))
               ->join(array('t7' => 'student_semester_registration'),
                    't7.student_id = t1.id', array('academic_year','semester_id'))
               ->join(array('t8' => 'dzongkhag'),
                    't1.dzongkhag = t8.id', array('dzongkhag_name'))
               ->where->notLike('t1.student_id', "TEMP_%");
		}else{
			$select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'gender'),
                    't4.id = t1.gender', array('stdGender' => 'gender'))
			   ->join(array('t5' => 'student_status_type'),
					't5.id = t1.student_status_type_id', array('reason'))
               ->join(array('t6' => 'student_semester_registration'),
                    't6.student_id = t1.id', array('academic_year','semester_id'))
               ->join(array('t7' => 'dzongkhag'),
                    't1.dzongkhag = t7.id', array('dzongkhag_name'))
               ->where(array('t1.organisation_id = ?' => $organisation_id))
               ->where->notLike('t1.student_id', "TEMP_%");
		}                          
        
        if($stdName){
            $select->where->like('t1.first_name','%'.$stdName.'%');
            //$select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
        if($stdId){
            $select->where(array('t1.student_id' =>$stdId));
            //$select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
        if($stdCid){
            $select->where(array('t1.cid' =>$stdCid));
           // $select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
        if($stdProgramme){
            $select->where->like('t1.student_status_type_id', $stdProgramme.'%');
            //$select->where(array('t1.organisation_id = ?' => $organisation_id));
        }


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $organisation_id)
    {
        //$academic_event_details = $this->getSemester($organisation_id);
        //$semester_session = $academic_event_details['academic_event'];
        //$academic_year = $this->getCurrentAcademicYear($academic_event_details);

       // $current_academic_session = $this->getAcademicSession($organisation_id);      

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

         $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id','academic_session_id', 'student_section_id', 'year_id','academic_year'))
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
               ->join(array('t8' => 'programme_year'),
                    't8.id = t2.year_id', array('year'))
               ->join(array('t9' => 'academic_session'),
                    't9.id = t2.academic_session_id', array('academic_session'))
               ->where(array('t1.programmes_id' => $stdProgramme, 't2.year_id' => $stdYear, 't1.student_status_type_id' => '1'))
               ->where->notLike('t1.student_id', "TEMP_%");

        if($stdName){
            $select->where->like('first_name','%'.$stdName.'%');
            $select->where(array('t1.programmes_id = ?' => $stdProgramme));
        }
        if($stdId){
            $select->where(array('t1.student_id' =>$stdId));
            $select->where(array('t1.programmes_id = ?' => $stdProgramme));
        }
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function updateStudentChangeProgramme($programme_data, $stdProgramme, $stdYear, $stdName, $stdId, $organisation_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate, $updateBy)
    {
        $i = 1;
        $studentIds = array();
        $studentData = $this->getProgrammeChangeStudentLists($stdProgramme, $stdYear, $stdName, $stdId, $organisation_id);
        foreach($studentData as $value)
        {
            $studentIds[$i++] = $value['id'];
        } 

        if($programme_data != NULL)
        {
            $i = 1;
            // Its an insert
            foreach($programme_data as $data){
                
                if($data == '1')
                {
                    $current_semester = $this->getstdCurrentSemester($studentIds[$i]);

                    $action = new Insert('student_programme_change_details');
                    $action->values(array(
                        'previous_programme' => $stdProgramme,
                        'changed_programme' => $changeProgramme,
                        'semester' => $current_semester,
                        'student_id' => $studentIds[$i],
                        'updated_by' => $updateBy,
                        'updated_date' => $updateDate,
                        'status' => $data,
                        'organisation_id' => $organisation_id
                    ));

                    $sql = new Sql($this->dbAdapter);
                    $stmt = $sql->prepareStatementForSqlObject($action);
                    $result = $stmt->execute();

                    $this->updateStudentProgramme($studentIds[$i], $changeProgramme);
                    $this->updateStudentProgrammeSemester($studentIds[$i], $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate);
                }

                $i++;

                //It will insert the id of all students in list and it will be differentiated from the status. For check value it is '1' and unchecked value is '0'. Then for status value '1' only the student programme will be updated.
            }
            
        }
    }


    // Function to change the student programme changed
    public function updateStudentProgramme($student_id, $updated_programme)
    {   
        $action = new Update('student');
        $action->set(array('programmes_id' => $updated_programme));
        $action->where(array('id = ?' => $student_id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    public function updateStudentProgrammeSemester($student_id, $changeProgramme, $changeSession, $year, $semester, $academicYear, $updateDate)
    {
        //$academic_session_id = NULL;
        $enrollment_year = $this->getStudentEnrollmentYear($tableName = 'student_semester_registration', $student_id);
        $academic_session = $this->getStudentAcademicSession($changeProgramme, $year, $semester);
        //if($academic_session == 'Spring'){
            //$academic_session_id = 1;
        //}else{
            //$academic_session_id = 2;
        //}

        $action = new Update('student_semester_registration');
        $action->set(array('year_id' => $year, 'semester_id'  => $semester, 'academic_session_id' => $changeSession, 'student_section_id' => '1', 'enrollment_year' => $enrollment_year, 'academic_year' => $academicYear, 'updated_date' => $updateDate));
        $action->where(array('student_id = ?' => $student_id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }



    public function getStudentAcademicSession($changeProgramme, $year, $semester)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'academic_modules_allocation'))
                ->columns(array('id', 'academic_session'))
               ->where(array('t1.programmes_id' => $changeProgramme, 't1.year' => $year, 't1.semester' => $semester))
               ->order(array('t1.id DESC'))
               ->limit(1);
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $academic_session = NULL;
        foreach($resultSet as $set){
            $academic_session = $set['academic_session'];
        } 
        return $academic_session;
    }



    public function getProgrammeChangedStudentId($stdProgramme, $stdYear, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        $semester_session = $academic_event_details['academic_event'];
        $academic_year = $this->getCurrentAcademicYear($academic_event_details);

        $current_academic_session = $this->getAcademicSession($organisation_id); 

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_semester_registration'),
                    't1.id = t2.student_id', array('semester_id'))
               ->where(array('t1.programmes_id' => $stdProgramme, 't2.year_id' => $stdYear, 't2.academic_session_id' => $current_academic_session, 't2.academic_year' => $academic_year ))
               ->where->notLike('t1.student_id', "TEMP_%");
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getChangedProgrammeStudentList($stdProgramme, $stdSemester, $stdYear, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_programme_change_details'))
               ->join(array('t2' => 'student'),
                    't1.student_id = t2.id', array('first_name', 'middle_name', 'last_name', 'student_id'))
               ->join(array('t3' => 'programmes'),
                    't3.id = t1.previous_programme', array('pprogramme_name'=>'programme_name'))
               ->join(array('t4' => 'programmes'),
                    't4.id = t1.changed_programme', array('cprogramme_name'=> 'programme_name'))
               ->join(array('t5' => 'gender'),
                    't5.id = t2.gender', array('stdgender' => 'gender'))
               ->join(array('t6' => 'student_semester'),
                    't6.id = t1.semester', array('semester'))
               ->where(array('t1.status' => '1', 't1.previous_programme' => $stdProgramme, 't1.semester' => $stdSemester, 't1.organisation_id' => $organisation_id));

        if($stdYear){
            $select->where->like('t1.updated_date',$stdYear.'%');
            $select->where(array('t1.previous_programme = ?' => $stdProgramme));
             $select->where(array('t1.semester = ?' => $stdSemester));
              $select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
                          
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function assignParentPortalAccess($parent_type, $id, $parent_cid)
    {
        if($parent_type == 1){
            $assignedStudent = $this->getAssignedParentStudent($tableName = 'parent_portal_access', $id);

            $studentAdmissionData['parent_cid'] = $parent_cid;
            $studentAdmissionData['student_id'] = $id;
            $studentAdmissionData['parent_type'] = $parent_type;
            
            if(!empty($assignedStudent)){
                $father_action = new Update('parent_portal_access');
                $father_action->set($studentAdmissionData);
                $father_action->where(array('student_id = ?' => $id));
            }else{
                $father_action = new Insert('parent_portal_access');
                $father_action->values($studentAdmissionData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($father_action);
            $result = $stmt->execute();

            //After assigning responsible parent to access the parent portal, it will create username and password to login to the system and access the system
            $this->addParentToUser($parent_cid);
        }

        else if($parent_type == 2){
            $assignedStudent = $this->getAssignedParentStudent($tableName = 'parent_portal_access', $id);

            $studentAdmissionData['parent_cid'] = $parent_cid;
            $studentAdmissionData['student_id'] = $id;
            $studentAdmissionData['parent_type'] = $parent_type;

            if(!empty($assignedStudent)){
                $mother_action = new Update('parent_portal_access');
                $mother_action->set($studentAdmissionData);
                $mother_action->where(array('student_id = ?' => $id));
            }else{
                $mother_action = new Insert('parent_portal_access');
                $mother_action->values($studentAdmissionData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($mother_action);
            $result = $stmt->execute();

            //After assigning responsible parent to access the parent portal, it will create username and password to login to the system and access the system
            $this->addParentToUser($parent_cid);
        }

        else if($parent_type == 'Guardian'){

            $guardian_type = $this->getStudentGuardianType($parent_cid);
            $assignedStudent = $this->getAssignedParentStudent($tableName ='parent_portal_access', $id);

            $studentAdmissionData['parent_cid'] = $parent_cid;
            $studentAdmissionData['student_id'] = $id;
            $studentAdmissionData['parent_type'] = $guardian_type;

            if(!empty($assignedStudent)){
                $guardian_action = new Update('parent_portal_access');
                $guardian_action->set($studentAdmissionData);
                $guardian_action->where(array('student_id = ?' => $id));
            }else{
                $guardian_action = new Insert('parent_portal_access');
                $guardian_action->values($studentAdmissionData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($guardian_action);
            $result = $stmt->execute(); 

            //After assigning responsible parent to access the parent portal, it will create username and password to login to the system and access the system
            $this->addParentToUser($parent_cid);           
        }

        else{
            return;
        }

        return;
    }


    public function getFatherCid($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('father_cid'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $studentId));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $fatherCid = NULL;
        
        foreach($resultSet as $set)
        {
           $fatherCid = $set['father_cid'];
        }
        return $fatherCid;
    }


    public function getMotherCid($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('mother_cid'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $studentId));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $motherCid = NULL;
        
        foreach($resultSet as $set)
        {
           $motherCid = $set['mother_cid'];
        }
        return $motherCid;
    }


    public function getGuardianCid($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('guardian_cid'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $studentId));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $guardianCid = NULL;
        
        foreach($resultSet as $set)
        {
           $guardianCid = $set['guardian_cid'];
        }
        return $guardianCid;
    }


    public function getAssignedParentStudent($tableName, $studentId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('student_id'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $studentId));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       $studentIds = NULL;
        
        foreach($resultSet as $set)
        {
           $studentIds = $set['student_id'];
        }
        return $studentIds;
    }	


    public function getStudentGuardianType($parent_cid)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_relation_details'));
        $select->columns(array('relation_type'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.parent_cid = ?' => $parent_cid));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       $parentCid = NULL;
        
        foreach($resultSet as $set)
        {
           $parentCid = $set['relation_type'];
        }
        return $parentCid;
    }


    public function addParentToUser($parent_cid)
    {
        $parentUsername = $this->getParentAssignedUsername($tableName = 'users', $parent_cid);

        $studentAdmissionData['username'] = $parent_cid;
        $studentAdmissionData['password'] = md5('admin');
        $studentAdmissionData['role'] = 'STUDENT_PARENT';
        $studentAdmissionData['region'] = '0';
        $studentAdmissionData['user_type_id'] = '3';

        if(!empty($parentUsername)){
                return;
            }
            else{
                $action = new Insert('users');
                $action->values($studentAdmissionData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
    }


    public function getParentAssignedUsername($tableName, $parent_cid)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('username'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.username = ?' => $parent_cid));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       $userName = NULL;
        
        foreach($resultSet as $set)
        {
           $userName = $set['username'];
        }
        return $userName;
    }


    public function getAssignedParentPortalAccess($access_details_type, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
       
        if($access_details_type == 'Details'){
            $select->from(array('t1' => 'parent_portal_access'))
               ->join(array('t2' => 'student_relation_details'),
                    't2.student_id = t1.student_id', array('parent_cid', 'parent_name', 'parent_nationality', 'parent_dzongkhag', 'parent_occupation', 'parent_address', 'parent_contact_no'))
               ->join(array('t3' => 'relation_type'),
                    't3.id = t1.parent_type', array('relation'));
        $select->where(array('t1.student_id' =>$id));
        }else if($access_details_type == 'Nationality'){
            $select->from(array('t1' => 'parent_portal_access'))
               ->join(array('t2' => 'student_relation_details'),
                    't2.student_id = t1.student_id', array('parent_nationality'))
               ->join(array('t3' => 'nationality'),
                    't3.id = t2.parent_nationality', array('nationality'));
        $select->where(array('t1.student_id' =>$id));
        }else if($access_details_type == 'Dzongkhag'){
            $select->from(array('t1' => 'parent_portal_access'))
               ->join(array('t2' => 'student_relation_details'),
                    't2.student_id = t1.student_id', array('parent_dzongkhag'))
               ->join(array('t3' => 'dzongkhag'),
                    't3.id = t2.parent_dzongkhag', array('dzongkhag_name'));
        $select->where(array('t1.student_id' =>$id));
        }
        
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getParentType($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('parent_type'));
        //$select->where->like('id = ?' => $code);
        $select->where(array('t1.student_id = ?' => $id));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       $parentType = NULL;
        
        foreach($resultSet as $set)
        {
           $parentType = $set['parent_type'];
        }
        return $parentType;
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
     * Get the semester from the database
     */
    
    public function getAcademicSession($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'academic_calendar'))
                    ->columns(array('academic_year'))
                ->join(array('t2' => 'academic_calendar_events'), 
                        't1.academic_event = t2.id', array('academic_event', 'academic_session_id'));
        $select->where(array('from_date <= ? ' => date('Y-m-d')));
        $select->where(array('to_date >= ? ' => date('Y-m-d')));
        $select->where('t2.organisation_id = ' .$organisation_id);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academic_session = NULL;
        
        foreach($resultSet as $set){
            if($set['academic_event'] == 'Autumn Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
            else if($set['academic_event'] == 'Spring Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
        }
        return $academic_session;
    }


     /*
     * Get the semester from the database
     */
    
    public function getProgrammeDuration($programmes_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'programmes'))
                    ->columns(array('programme_duration'));
        $select->where('t1.id = ' .$programmes_id);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $programme_duration = NULL;
        
        foreach($resultSet as $set){
                $programme_duration = $set['programme_duration'];
        }
        return $programme_duration;
    }


    public function getPreviousStudentSemester($programmesId, $yearId, $previous_academic_session)
    {
        $start_academic_session_id = $this->getAcademicStartSessionId($programmesId);

        if($yearId == 1){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 1;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 2;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 2;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 1;
            }
        }

        if($yearId ==2){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 3;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 4;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 4;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 3;
            }
        }


        if($yearId == 3){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 5;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 6;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 6;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 5;
            }
        }

        if($yearId == 4){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 7;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 8;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 8;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 7;
            }
        }

        if($yearId == 5){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 9;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 10;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 10;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 9;
            }
        }

        if($yearId == 6){
            if($start_academic_session_id == 1 && $previous_academic_session == 1){
                $previousSemesterId = 11;
            }
            else if($start_academic_session_id == 1 && $previous_academic_session == 2){
                $previousSemesterId = 12;
            }
            elseif ($start_academic_session_id == 2 && $previous_academic_session == 1) {
                $previousSemesterId = 12;
            }
             elseif ($start_academic_session_id == 2 && $previous_academic_session == 2) {
                $previousSemesterId = 11;
            }
        }

        return $previousSemesterId;
    }


    public function getAcademicStartSessionId($programmesId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'programmes'))
                    ->columns(array('academic_session_id'));
        $select->where(array('t1.id' => $programmesId));
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academic_session_id = NULL;
        
        foreach($resultSet as $set){
            $academic_session_id = $set['academic_session_id'];
            }
        return $academic_session_id;
    }

    public function getCurrentAcademicYear($academic_event_details)
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


    /*To get the list of students to view their details
    **/
    public function getSelfFinancedStudentListsToAdmin($stdName, $stdId,$organisation_id = null)
    {
        $selffinanced_type_id = '2'; // Self Financed Student Type ID
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'gender'),
                    't4.id = t1.gender', array('stdGender' => 'gender'))
               ->join(array('t5' => 'dzongkhag'),
                    't5.id = t1.dzongkhag', array('dzongkhag_name'))
               ->join(array('t6' => 'gewog'),
                    't6.id = t1.gewog', array('gewog_name'))
               ->join(array('t7' => 'village'),
                    't7.id = t1.village', array('village_name'))
               ->where(array('t1.student_status_type_id' => '1', 't1.scholarship_type = ?' => $selffinanced_type_id));
        
        if($stdName) {
            $select->where->like('t1.first_name','%'.$stdName.'%');
        }
        if($stdId) {
            $select->where(array('t1.student_id' => $stdId));
        }
        if($organisation_id) {
            $select->where(array('t1.organisation_id' => $organisation_id));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
    
    /*
    *To get the list of students to view their details
    **/
    public function getSelfFinancedStudentLists($stdName, $stdId, $organisation_id)
    {
        $selffinanced_type_id = '2'; // Self Financed Student Type ID
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
                    't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'gender'),
                    't4.id = t1.gender', array('stdGender' => 'gender'))
               ->join(array('t5' => 'dzongkhag'),
                    't5.id = t1.dzongkhag', array('dzongkhag_name'))
               ->join(array('t6' => 'gewog'),
                    't6.id = t1.gewog', array('gewog_name'))
               ->join(array('t7' => 'village'),
                    't7.id = t1.village', array('village_name'))
               ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.student_status_type_id' => '1', 't1.scholarship_type = ?' => $selffinanced_type_id));
        
        if($stdName){
            $select->where->like('t1.first_name','%'.$stdName.'%');
        }
        if($stdId){
            $select->where(array('t1.student_id' =>$stdId));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function fetchFeeCategories($organisation_id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_category'))->columns(array('id', 'organisation_id', 'fee_category'));
        $select->where(array('t1.organisation_id' => intval($organisation_id)));
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $allFeeCategories = [];
        
        foreach($resultSet as $set){
            $allFeeCategories[$set['id']] = $set['fee_category'];
        }
        
        return $allFeeCategories;
    }
    
    public function listStudentFeeList($student_id, $id = null) {
        $sql = new Sql($this->dbAdapter);
        
        $whereCondition['t1.student_id'] = intval($student_id);

        if ($id !== null) {
          $whereCondition['t1.id'] = intval($id);
        }
        
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_details'))
               ->join(array('t2' => 'student_fee_category'), 't1.student_fee_category_id = t2.id', array('fee_category'))
               ->where($whereCondition)->order(['id' => 'DESC']);
        
        // Sub query to check for pending amount
        $select->columns(array('*', 'pending_amount' => new Expression('t1.amount - IFNULL((SELECT SUM(amount) from student_fee_payment_details WHERE status = "Completed" GROUP BY student_fees_details_id having student_fees_details_id=t1.id), 0)')));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
    
    public function fetchStudentFeeStructure($student_id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_details'))
               ->join(array('t2' => 'student_fee_category'), 't1.student_fee_category_id = t2.id', array('fee_category'))
               ->where(array('t1.student_id' => intval($student_id)))->order(['id' => 'DESC']);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
    
    public function saveStudentFeeDetails(StudentFeeDetails $studentFeeDetails) {
      $studentFeeDetailsData = $this->hydrator->extract($studentFeeDetails);
      unset($studentFeeDetailsData['id']);
      
      if($studentFeeDetails->getId()) {
          // ID present, so it is an update
          $studentFeeDetailsData['updated_at'] = date('Y-m-d H:i:s');
          $action = new Update('student_fee_details');
          $action->set($studentFeeDetailsData);
          $action->where(array('id = ?' => $studentFeeDetails->getId()));
      } else {
          $studentFeeDetailsData['created_at'] = $studentFeeDetailsData['updated_at'] = date('Y-m-d H:i:s');
          // ID is not preset, so it is an insert
          $action = new Insert('student_fee_details');
          $action->values($studentFeeDetailsData);
      }

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $result = $stmt->execute();

      if($result instanceof ResultInterface) {
          if($newId = $result->getGeneratedValue()){
              //when a value has been generated, set it on the object
              $studentFeeDetails->setId($newId);
          }
          return $studentFeeDetails;
      } 
      throw new \Exception("Database Error");
    }
    
    public function fetchStudentSemesterList($semester_id = null) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_semester'));
        $select->join(array('t2' => 'programme_year'), 't1.programme_year_id = t2.id', array('year'));
        
        if ($semester_id !== null) {
          $select->where(array('t1.id' => intval($semester_id)))->limit(1);
        }
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet = $resultSet->initialize($result)->toArray();
        
        $studentSemesterList = [];
        
        foreach($resultSet as $row) {
            if ($semester_id !== null) {
                $studentSemesterList[] = $row;
            } else {
                $studentSemesterList[$row['id']] = 'Semester ' . $row['semester'] . ' - ' . $row['year'];
            }
        }
        
        return ($semester_id !== null)? $studentSemesterList[0]: $studentSemesterList;
    }
    
    public function isStudentFeesPaid($data, $id = null) { // $id is used to check the unique except the current ID that is used for update operation
      
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_details'));
        $select->where([
            't1.student_fee_category_id' => $data['student_fee_category_id'],
            't1.organisation_id' => $data['organisation_id'],
            't1.student_id' => $data['student_id'],
            't1.semester_id' => $data['semester_id'],
            't1.financial_year' => $data['financial_year'],
        ]);
        if ($id !== null) {
          $select->where('id <> '.$id);
        }
        
        $select->limit(1);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $studentFeesPaid = [];
        
        foreach($resultSet as $row) {
            $studentFeesPaid[] = $row;
        }
        
        return (count($studentFeesPaid)>0)? true: false;
    }
    
    public function fetchStudentFeeDetails($id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_details'))
                ->join(array('t2' => 'student_fee_category'), 't1.student_fee_category_id = t2.id', array('fee_category'))
                ->where(array('t1.id' => intval($id)))->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $data = $resultSet->initialize($result)->toArray();
        
        $feeDetails = [];
        
        foreach ($data as $type) {
          $feeDetails[] = $type;
        }
        
        $semester = $this->fetchStudentSemesterList($feeDetails[0]['semester_id']);
        unset($semester['id']);
        
        return (count($feeDetails)>0)? array_merge($feeDetails[0], $semester): $feeDetails;
    }
    
    public function fetchStudentFeePaymentDetails($field, $id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_payment_details'))
                ->join(array('t2' => 'student_fee_details'), 't1.student_fees_details_id = t2.id', array('student_fee_structure_id', 'student_fee_category_id', 'student_id'))
                ->join(array('t3' => 'payment_type'), 't1.payment_type = t3.id', array('type'))
                ->where(array('t1.'.$field => intval($id)));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $data = $resultSet->initialize($result)->toArray();
        
        $feePaymentDetails = [];
        
        foreach ($data as $paymentDetail) {
          $feePaymentDetails[] = $paymentDetail;
        }

        return $feePaymentDetails;
    }
    
    public function fetchPaymentTypes() {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'payment_type'))->where(array('t1.status' => 'Active'));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $data = $resultSet->initialize($result)->toArray();
        
        $paymentTypes = [];
        
        foreach ($data as $type) {
          $paymentTypes[$type['id']] = $type['type'];
        }
        
        return $paymentTypes;
    }
    
    public function fetchStudentFeeStructureDetails($id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_structure'));
        $select->where(array('t1.id' => intval($id)))->limit(1);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $feeStructures = $resultSet->initialize($result)->toArray();
        
        $fee_structure = [];
        
        foreach($feeStructures as $row){
            $fee_structure = $row;
        }
        
        return $fee_structure;
    }
    
    public function saveStudentFeePaymentDetails(StudentFeePaymentDetails $studentFeePaymentDetails) {
        $studentFeePaymentDetailsData = $this->hydrator->extract($studentFeePaymentDetails);
        unset($studentFeePaymentDetailsData['id']);

        if($studentFeePaymentDetails->getId()) {
            // ID present, so it is an update
            $studentFeePaymentDetailsData['updated_at'] = date('Y-m-d H:i:s');
            if ($studentFeePaymentDetailsData['payment_Type'] === '1') {
              $studentFeePaymentDetailsData['cheque_No'] = '';
              $studentFeePaymentDetailsData['dd_No'] = '';
            } else if ($studentFeePaymentDetailsData['payment_Type'] === '2') {
              $studentFeePaymentDetailsData['dd_No'] = '';
            } else if ($studentFeePaymentDetailsData['payment_Type'] === '3') {
              $studentFeePaymentDetailsData['cheque_No'] = '';
            }
            $action = new Update('student_fee_payment_details');
            $action->set($studentFeePaymentDetailsData);
            $action->where(array('id = ?' => $studentFeePaymentDetails->getId()));
        } else {
            $studentFeePaymentDetailsData['created_at'] = $studentFeePaymentDetailsData['updated_at'] = date('Y-m-d H:i:s');
            // ID is not preset, so it is an insert
            $action = new Insert('student_fee_payment_details');
            $action->values($studentFeePaymentDetailsData);
        }

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                $studentFeePaymentDetails->setId($newId);
            }
            return $studentFeePaymentDetails;
        } 
        throw new \Exception("Database Error");
    }
    
    public function getStudentFeeStructureDetails($organisation_id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_fee_structure'));
        $select->join(array('t2' => 'student_fee_category'), 't1.student_fee_category_id = t2.id', array('fee_category'));
        $select->where(array('t1.organisation_id' => intval($organisation_id)));
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $feeStructures = $resultSet->initialize($result)->toArray();
        
        $fee_structure = [];
        
        foreach($feeStructures as $row){
            $fee_structure[$row['id']] = $row['fee_category'] . ' - ' . $row['total_fee'] . ' (' . $row['financial_year'] . ')';
        }
        
        return $fee_structure;
    }
    
    public function generateBulkStudentFees($organisation_id, $student_fee_structure_id, $due_date) {
      $studentIds = [];
      
      $studentFeeStructure = $this->fetchStudentFeeStructureDetails($student_fee_structure_id);

      $sql = new Sql($this->dbAdapter);
      $select = $sql->select();
      $select->from(array('t1' => 'student'))->columns(array('id'))->where(array('t1.programmes_id' => $studentFeeStructure['programmes_id'], 't1.organisation_id' => $studentFeeStructure['organisation_id']));

      $stmt = $sql->prepareStatementForSqlObject($select);
      $result = $stmt->execute();

      $resultSet = new ResultSet();
      $data = $resultSet->initialize($result)->toArray();

      foreach ($data as $studentId) {
        $studentIds[] = $studentId['id'];
      }
      
      // Set the insert values with columns as key except student_id column
      $studentFeeDetailsData = [
        'student_fee_structure_id' => $studentFeeStructure['id'],
        'student_fee_category_id' => $studentFeeStructure['student_fee_category_id'],
        'organisation_id' => $organisation_id,
        'financial_year' => $studentFeeStructure['financial_year'],
        'due_date' => $due_date,
        'amount' => $studentFeeStructure['total_fee'],
        'status' => 'Pending',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ];
      
      // Loop over student and Insert bulk student fees details
      $insertedData = [];
      $action = new Insert('student_fee_details');
      foreach ($studentIds as $studentId) {
          $studentDetails = $this->findRegisteredStudentByStudentId($studentId, 'DESC');
          $studentFeeDetailsData = array_merge($studentFeeDetailsData, [
            'student_id' => $studentId,
            'semester_id' => $studentDetails['semester_id'],
            'year_id' => $studentDetails['year_id'],
          ]);
          
          $isExists = $this->isStudentFeesPaid($studentFeeDetailsData);
          if (!$isExists) { // If student fees is not exists then only add in database
            $action->values($studentFeeDetailsData);
            
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            $insertedData[] = $result->getGeneratedValue();
          }
      }

      return (count($studentIds)===count($insertedData))? true: count($insertedData);
    }
    
    public function updateStudentFeeDetailsStatus($id, $status = 'Completed') {
      $action = new Update('student_fee_details');
      $action->set(['status' => $status]);
      $action->where(array('id = ?' => $id));

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $result = $stmt->execute();

      if($result instanceof ResultInterface) {
        if($newId = $result->getGeneratedValue()){
          //when a value has been generated, set it on the object
          $studentFeePaymentDetails->setId($newId);
        }
      }
    }
    
    public function deleteStudentFeeDetails($id)
    {
      $sql = new Sql($this->dbAdapter);
      
      // Delete all payment details first
      $action = new Delete('student_fee_payment_details');
      $action->where(array('student_fees_details_id = ?' => $id));
      $stmt = $sql->prepareStatementForSqlObject($action)->execute();

      // Delete the actual fee details entry
      $action = new Delete('student_fee_details');
      $action->where(array('id = ?' => $id));
      $result = $sql->prepareStatementForSqlObject($action)->execute();

      return (bool)$result->getAffectedRows();
    }
    
    public function deleteStudentFeePaymentDetails($id)
    {
      $action = new Delete('student_fee_payment_details');
      $action->where(array('id = ?' => $id));

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $result = $stmt->execute();

      return (bool)$result->getAffectedRows();
    }

    public function checkChequeAndDraftNumExists($data,$id = null){
        $cheque_no      = $data->getcheque_no();
        $dd_no          = $data->getdd_no();
        $payment_type   = $data->getpayment_type();

        if($payment_type != '1'){
            $sql = new Sql($this->dbAdapter);

            $select = $sql->select();
            $select->from(array('sfd' => 'student_fee_details'))
                    ->join(['sfpd' => 'student_fee_payment_details'], 'sfd.id = sfpd.student_fees_details_id',[]);
            $select->columns([
                'total_occurance' => new Expression('count(sfpd.id)'),
            ]);
            $select->where(['sfd.student_id' => $id]);

            if ($payment_type == '2') {
                $select->where('sfpd.cheque_no = '.$cheque_no);
            }elseif($payment_type == '3'){
                $select->where('sfpd.dd_no = '.$dd_no);
            }

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $return_data = current($resultSet->initialize($result)->toArray());

            return ($return_data['total_occurance']>0)? false: true;
        }
        return true;
    }
}
