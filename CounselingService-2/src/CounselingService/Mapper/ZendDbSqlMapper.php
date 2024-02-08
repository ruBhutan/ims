<?php

namespace CounselingService\Mapper;

use CounselingService\Model\Counselor;
use CounselingService\Model\CounselingAppointment;
use CounselingService\Model\CounselingNotes;
use CounselingService\Model\CounselingSuggest;
use CounselingService\Model\ScheduledAppointment;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements CounselingMapperInterface
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
	 * @var \Counseling\Model\CounselingInterface
	*/
	protected $appointmentPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			CounselingAppointment $appointmentPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->appointmentPrototype = $appointmentPrototype;
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
			$select->where(array('student_id' => $username));
		}
		$select->columns(array('id','organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else {
			$select->where(array('student_id' =>$username));
		}
		$select->columns(array('organisation_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getUserDetails($username, $tableName)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($tableName == 'student'){
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


	public function getAppointmentApplicantType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'counseling_appointment'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('applicant_type'))
    	   	   ->where(array('t1.id' => $id)); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$applicantType = NULL;
		foreach($resultSet as $set)
		{
			$applicantType = $set['applicant_type'];
		}
		return $applicantType;
	}


	public function getAppointmentApplicantDetails($applicantType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($applicantType == 1){
			$select->from(array('t1' => 'counseling_appointment'))
			       ->join(array('t2' => 'employee_details'),
			   			't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			       ->where(array('t1.id = ? ' => $id));
		}

		else if($applicantType == 2){
			$select->from(array('t1' => 'counseling_appointment'))
				   ->join(array('t2' => 'student'),
						't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
			       ->where(array('t1.id = ? ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getRecommendedType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'counseling_suggest'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('suggested_type'))
    	   	   ->where(array('t1.id' => $id)); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$recommendedType = NULL;
		foreach($resultSet as $set)
		{
			$recommendedType = $set['suggested_type'];
		}
		return $recommendedType;
	}
	
	/**
	* @param int/String $id
	* @return Counseling
	* @throws \InvalidArgumentException
	*/
	
	public function findCounseling($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'counseling_appointment'){
			$select->from(array('t1' => $tableName))
			       ->where(array('t1.id = ? ' => $id));
		}

		else if($tableName == 'counseling_suggest'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'employee_details'),
						't2.id = t1.suggested_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			       ->where(array('t1.id = ? ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Counseling()
	*/
	public function getStaffRecommendCounselingList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'counselor'),
					't2.id = t1.counselor_id', array('employee_details_id'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->join(array('t4' => 'employee_details'),
					't4.id = t1.suggested_id', array('f_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'empId' => 'emp_id'))
			   ->join(array('t5' => 'department_units'),
					't5.id = t4.departments_units_id', array('unit_name'))
			   ->where(array('t1.suggested_type' => '1', 't1.suggested_by' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStdRecommendCounselingList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'counselor'),
					't2.id = t1.counselor_id', array('employee_details_id'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->join(array('t4' => 'student'),
					't4.id = t1.suggested_id', array('f_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'student_id'))
			   ->join(array('t5' => 'programmes'),
					't5.id = t4.programmes_id', array('programme_name'))
			   ->where(array('t1.suggested_type' => '2', 't1.suggested_by' => $employee_details_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSuggestedType($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_suggest'))
    		   ->columns(array('suggested_type'))
    	   	   ->where(array('t1.id' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestType = NULL;
		foreach($resultSet as $set)
		{
			$suggestType = $set['suggested_type'];
		}
		return $suggestType;
	}


	public function getSuggestedDetails($id, $suggestType)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($suggestType == 1){
			$select->from(array('t1' => 'counseling_suggest'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.suggested_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->join(array('t3' => 'counselor'),
					't3.id = t1.counselor_id', array('employee_details_id'))
			   ->join(array('t4' => 'employee_details'),
					't4.id = t3.employee_details_id', array('cf_name' => 'first_name', 'cm_name' => 'middle_name', 'cl_name' => 'last_name', 'cemp_id' => 'emp_id'))
			   ->where(array('t1.id' => $id));
		}
		else if($suggestType == 2){
			$select->from(array('t1' => 'counseling_suggest'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.suggested_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
			   ->join(array('t3' => 'counselor'),
					't3.id = t1.counselor_id', array('employee_details_id'))
			   ->join(array('t4' => 'employee_details'),
					't4.id = t3.employee_details_id', array('cf_name' => 'first_name', 'cm_name' => 'middle_name', 'cl_name' => 'last_name', 'emp_id'))
			   ->where(array('t1.id' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getRecommendCounselingDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
        $select->from(array('t1' => 'counseling_suggest')) //base table
               ->where(array('t1.id = ?' => $id, 't1.suggested_status' => 'Pending'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();      

        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
        return $this->hydrator->hydrate($result->current(), $this->appointmentPrototype);
        }

        throw new \InvalidArgumentException("Appointment with given ID: ($id) not found");
	}


	public function findRecommendCounselingDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
        $select->from(array('t1' => 'counseling_suggest')) //base table
        	   ->join(array('t2' => 'counseling_scheduled_appointments'),
        			't1.id = t2.counseling_appointment_id', array('scheduled_time', 'scheduled_date', 'venue', 'counselor_remarks', 'counseling_type', 'scheduled_status'))
               ->where(array('t1.id' => $id, 't2.counseling_type' => 'Recommended'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}



	public function getStaffRecommendedList($status, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_suggest'))
		   ->join(array('t2' => 'counselor'),
				't2.id = t1.counselor_id', array('employee_details_id'))
		   ->join(array('t3' => 'employee_details'),
				't3.id = t1.suggested_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
		   ->join(array('t4' => 'employee_details'),
				't4.id = t1.suggested_id', array('f_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'empId' => 'emp_id'));
		$select->where(array('t1.suggested_status' => $status, 't2.employee_details_id' => $employee_details_id, 't1.suggested_type' => '1'));
	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getStdRecommendedList($status, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_suggest'))
		   ->join(array('t2' => 'counselor'),
				't2.id = t1.counselor_id', array('employee_details_id'))
		   ->join(array('t3' => 'employee_details'),
				't3.id = t1.suggested_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
		   ->join(array('t4' => 'student'),
				't4.id = t1.suggested_id', array('f_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'student_id'));
		$select->where(array('t1.suggested_status' => $status, 't2.employee_details_id' => $employee_details_id, 't1.suggested_type' => '2'));
	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/

	public function listSelectData($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'employee_details')
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', 'first_name', 'middle_name', 'last_name', 'emp_id'))
            	   ->where(array('t1.organisation_id' => $organisation_id)); 
        }
        else if($tableName == 'counselor')
        {
        	$select->from(array('t1' => $tableName))
        		   ->columns(array('id'))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
            	   ->where(array('t1.organisation_id' => $organisation_id, 't1.status' => 'Active'));
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
			$selectData[$set['id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
		}
		return $selectData;
	}

	public function getCounselorList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'counselor'))
				->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
				->where(array('t1.organisation_id' => $organisation_id));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckCounselor($counselorId, $organisation_id, $status)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($status == 'Active'){
        	$select->from(array('t1' => 'counselor'))
        	   ->where(array('t1.employee_details_id' => $counselorId, 't1.status' => $status, 't1.organisation_id' => $organisation_id)); 

	        $stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();
				
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
        }
        else if($status == 'Inactive'){
        	$select->from(array('t1' => 'counselor'))
        	   ->where(array('t1.employee_details_id' => $counselorId, 't1.status' => $status, 't1.organisation_id' => $organisation_id)); 

	        $stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();
				
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
        }
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$counselorID = NULL;
		foreach($resultSet as $set)
		{
			$counselorID = $set['employee_details_id'];
		}
		return $counselorID;
	}


	public function getCounselorId($counselorId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counselor'))
    		   ->columns(array('employee_details_id'))
    	   	   ->where(array('t1.id' => $counselorId)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counselorID = NULL;
		foreach($resultSet as $set)
		{
			$counselorID = $set['employee_details_id'];
		}
		return $counselorID;
	}


	public function crossCheckCounselingAppointment($subject, $applicant, $applicantType, $counselorId, $appointmentTime, $appointmentDate)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_appointment'))
    		   ->columns(array('id'))
    	   	   ->where(array('t1.subject' => $subject, 't1.applicant_id' => $applicant, 't1.applicant_type' => $applicantType, 't1.appointment_status' => 'Pending', 't1.counselor_id' => $counselorId, 't1.appointment_time' => $appointmentTime, 't1.appointment_date' => $appointmentDate)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counseling = NULL;
		foreach($resultSet as $set)
		{
			$counseling = $set['id'];
		}
		return $counseling;
	}


	public function crossCheckCounselingAppointmentDetails($subject, $applicant, $applicantType, $id, $counselorId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_appointment'))
    		   ->columns(array('id', 'subject'))
    	   	   ->where(array('t1.subject' => $subject, 't1.applicant_id' => $applicant, 't1.applicant_type' => $applicantType, 't1.appointment_status' => 'Pending', 't1.id != ?' => $id, 't1.counselor_id' => $counselorId)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counseling = NULL;
		foreach($resultSet as $set)
		{
			$counseling = $set['subject'];
		}
		return $counseling;
	}


	public function getCounselorEmail($counselor)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->columns(array('email'));
		$select->where(array('t1.id' =>$counselor));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$email = NULL;
		foreach($resultSet as $set){
			$email = $set['email'];
		} 
		return $email;
	}


	public function getCounselingApplicant($tableName, $applicant)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' => $applicant));
		}
		
		else if($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.id' => $applicant));
		}	
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getIndCounselingApplicationList($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'counseling_appointment'))
				->join(array('t2' => 'employee_details'), 
                            't1.applicant_id = t2.id', array('emp_id'))
				->join(array('t3' => 'counselor'),
					't3.id = t1.counselor_id', array('employee_details_id'))
				->join(array('t4' => 'employee_details'),
					't4.id = t3.employee_details_id', array('first_name', 'middle_name', 'last_name'))
				->where(array('t2.emp_id' => $username));
		}
		else if($usertype == 2){
			$select->from(array('t1' => 'counseling_appointment'))
				->join(array('t2' => 'student'), 
                            't1.applicant_id = t2.id', array('student_id'))
				->join(array('t3' => 'counselor'),
					't3.id = t1.counselor_id', array('employee_details_id'))
				->join(array('t4' => 'employee_details'),
					't4.id = t3.employee_details_id', array('first_name', 'middle_name', 'last_name'))
				->where(array('t2.student_id' => $username));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getIndCounselingAppointmentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'counseling_appointment')) //base table
                   ->join(array('t2' => 'counselor'),
                        't2.id = t1.counselor_id', array('employee_details_id'))
                   ->join(array('t3' => 'employee_details'),
               			't3.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->where(array('t1.id = ?' => $id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->appointmentPrototype);
            }

            throw new \InvalidArgumentException("Appointment with given ID: ($id) not found");
	}


	public function findIndCounselingAppointmentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'counseling_appointment')) //base table
               ->join(array('t2' => 'counselor'),
                    't2.id = t1.counselor_id', array('employee_details_id'))
               ->join(array('t3' => 'employee_details'),
           			't3.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
               ->join(array('t4' => 'counseling_scheduled_appointments'),
           			't1.id = t4.counseling_appointment_id', array('scheduled_time', 'scheduled_date', 'venue', 'counselor_remarks', 'counseling_type', 'scheduled_status'))
               ->where(array('t1.id = ' .$id, 't4.counseling_type' => 'Appointment'));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getCurrentCounselorStatus($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counselor'))
    	   	   ->where(array('t1.id' => $id)); 

       	$stmt = $sql->prepareStatementForSqlObject($select);
	    $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counselorStatus = NULL;
		foreach($resultSet as $set)
		{
			$counselorStatus = $set['status'];
		}
		return $counselorStatus;
	}

	//Function to save appointed counselor
	public function saveCounselor(Counselor $counselingObject)
	{
		$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);
		
		if($counselingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('counselor');
			$action->set($counselingData);
			$action->where(array('id = ?' => $counselingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('counselor');
			$action->values($counselingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $counselingObject->setId($newId);
			}
			return $counselingObject;
		}
		
		throw new \Exception("Database Error");
	}


	//Function to change the status of counselor
	public function updateCounselorStatus($status, $previousStatus, $id)
	{
		$counselingData['status'] = $status;

		$action = new Update('counselor');
		$action->set($counselingData);
		if($previousStatus  != NULL){
			$action->where(array('status = ?' => $previousStatus));
		} elseif($id != NULL){
			$action->where(array('id = ?' => $id));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return;
	}
        

	/**
	 * 
	 * @param type $CounselingInterface
	 * 
	 * to save Counseling Details
	 */
	
	public function saveDetails(Counseling $counselingObject)
	{
		$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);
		
		if($counselingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('rub_vision_mission');
			$action->set($counselingData);
			$action->where(array('id = ?' => $counselingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('rub_vision_mission');
			$action->values($counselingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $counselingObject->setId($newId);
			}
			return $counselingObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function crossCheckSuggestedCounseling($subject, $suggestedId, $suggestedType, $counselorId)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_suggest'))
    		   ->columns(array('id'))
    	   	   ->where(array('t1.subject' => $subject, 't1.suggested_id' => $suggestedId, 't1.suggested_type' => $suggestedType, 't1.suggested_status' => 'Pending', 't1.counselor_id' => $counselorId)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestedCounseling = NULL;
		foreach($resultSet as $set)
		{
			$suggestedCounseling = $set['subject'];
		}
		return $suggestedCounseling;
	}


	public function crossCheckSuggestedCounselingDetails($subject, $suggestedId, $suggestedType, $counselorId, $suggestedBy, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_suggest'))
    		   ->columns(array('id', 'subject'))
    	   	   ->where(array('t1.subject' => $subject, 't1.suggested_id' => $suggestedId, 't1.suggested_type' => $suggestedType, 't1.suggested_status' => 'Pending', 't1.counselor_id' => $counselorId, 't1.suggested_by' => $suggestedBy, 't1.id != ?' => $id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$suggestedCounseling = NULL;
		foreach($resultSet as $set)
		{
			$suggestedCounseling = $set['subject'];
		}
		return $suggestedCounseling;
	}
	
	/*
	 * Save details of student recommended for counseling
	 */
	 
	 public function saveCounselingRecommendation(CounselingSuggest $counselingObject)
	 {
		$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);
		
		if($counselingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('counseling_suggest');
			$action->set($counselingData);
			$action->where(array('id = ?' => $counselingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('counseling_suggest');
			$action->values($counselingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $counselingObject->setId($newId);
			}
			return $counselingObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /*
	 * Save details of student counseling record
	 */
	 
	 public function saveCounselingRecord(CounselingNotes $counselingObject, $notes, $scheduledId)
	 {
		$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);
		
		$counselingData['date'] = date("Y-m-d", strtotime(substr($counselingData['date'],0,10)));
		//var_dump($counselingData); die();
		//need to get the location of file and store in the database
		if($counselingData['documents'] == NULL){
			$counselingData['documents'] = $this->getUploadedFile($scheduledId);
		}else{
			$documents = $counselingData['documents'];
			$counselingData['documents'] = $documents['tmp_name'];
		}
		//var_dump($counselingData['documents']); die();
		$counselingData['notes'] = $notes;
		
		if($counselingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('counseling_record');
			$action->set($counselingData);
			$action->where(array('id = ?' => $counselingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('counseling_record');
			$action->values($counselingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->updateScheduledCounselingAppointment($scheduledId);
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $counselingObject->setId($newId);
			}
			return $counselingObject;
		}
		
		throw new \Exception("Database Error");
	 }


	 //Function to get the uploaded file link
	 public function getUploadedFile($scheduledId)
	 {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_record'))
			   ->columns(array('documents'));
		$select->where(array('t1.scheduled_counseling_id' => $scheduledId));
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$documents = NULL;
		foreach($resultSet as $set){
			if(empty($set)){
				$documents = NULL;
			}else{
				$documents = $set['documents'];
			}
		}
		return $documents;
	 }


	 //Function to update the scheduled couseling appointment table field appointment_status to recorded.
	 public function updateScheduledCounselingAppointment($appointmentId)
	 {
	 	$counselingData['scheduled_status'] = "Recorded";

		$action = new Update('counseling_scheduled_appointments');
		$action->set($counselingData);
		$action->where(array('id = ?' => $appointmentId));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	 }
	
	/**
	 * @param CounselingInterface $counselingObject
	 *
	 * @param CounselingInterface $counselingObject
	 * @return CounselingInterface
	 * @throws \Exception
	 */
	 
	 public function saveAppointment(CounselingAppointment $counselingObject)
	 {
		$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);
		unset($counselingData['reason']);
		unset($counselingData['suggested_By']);
		unset($counselingData['suggested_Id']);
		unset($counselingData['suggested_Type']);
		unset($counselingData['suggested_Date']);
		unset($counselingData['suggested_Status']);
		unset($counselingData['date']);
		unset($counselingData['notes']);
		unset($counselingData['documents']);
		unset($counselingData['counselor']);
		unset($counselingData['scheduled_Counseling_Id']);

		$counselingData['appointment_Date'] = date("Y-m-d", strtotime(substr($counselingData['appointment_Date'],0,10)));
		//var_dump($counselingData); die();
		if($counselingObject->getId()) {
			//ID present, so it is an update
			$action = new Update('counseling_appointment');
			$action->set($counselingData);
			$action->where(array('id = ?' => $counselingObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('counseling_appointment');
			$action->values($counselingData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $counselingObject->setId($newId);
			}
			return $counselingObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /**
	 * @param CounselingInterface $counselingObject
	 *
	 * @param CounselingInterface $counselingObject
	 * @return CounselingInterface
	 * @throws \Exception
	 */
	 
	 public function grantAppointment(ScheduledAppointment $counselingObject, $appointmentId, $counselingType)
	 {
	 	$counselingData = $this->hydrator->extract($counselingObject);
		unset($counselingData['id']);

		$counselingData['scheduled_Date'] = date("Y-m-d", strtotime(substr($counselingData['scheduled_Date'],0,10)));

	 	if($counselingType == 'Appointment'){
			
			if($counselingObject->getId()) {
				//ID present, so it is an update
				$action = new Update('counseling_scheduled_appointments');
				$action->set($counselingData);
				$action->where(array('id = ?' => $counselingObject->getId()));
			} else {
				//ID is not present, so its an insert
				$action = new Insert('counseling_scheduled_appointments');
				$action->values($counselingData);
			}
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

			$this->updateCounselingAppointment($appointmentId);
	 	}
	 	else if($counselingType == 'Recommended'){			
			if($counselingObject->getId()) {
				//ID present, so it is an update
				$action = new Update('counseling_scheduled_appointments');
				$action->set($counselingData);
				$action->where(array('id = ?' => $counselingObject->getId()));
			} else {
				//ID is not present, so its an insert
				$action = new Insert('counseling_scheduled_appointments');
				$action->values($counselingData);
			}
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

			$this->updateRecommendedCounseling($appointmentId);
	 	}
	 }


	 //Function to update the couseling appointment table field appointment_status to granted.
	 public function updateCounselingAppointment($appointmentId)
	 {
	 	$counselingData['appointment_status'] = "Granted";
	 	$counselingData['granted_date'] = date('Y-m-d');

		$action = new Update('counseling_appointment');
		$action->set($counselingData);
		$action->where(array('id = ?' => $appointmentId));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	 }


	 //Function to update the couseling appointment table field appointment_status to granted.
	 public function updateRecommendedCounseling($appointmentId)
	 {
	 	$counselingData['suggested_status'] = "Accepted";

		$action = new Update('counseling_suggest');
		$action->set($counselingData);
		$action->where(array('id = ?' => $appointmentId));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	 }


	
	/*
	* List Student to add awards etc
	*/
	
	public function getSuggestionList($suggestionType, $name, $suggestionId, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($suggestionType == 1){
			$select->from(array('t1' => 'employee_details'))
				->join(array('t2' => 'department_units'), 
                            't1.departments_units_id = t2.id', array('unit_name'));
		
			if($name){
				$select->where->like('t1.first_name','%'.$name.'%');
			}
			if($suggestionId){
				$select->where(array('t1.emp_id' =>$suggestionId));
			}
			if($organisation_id){
				$select->where(array('t1.organisation_id' =>$organisation_id));
			}
		}
		else if($suggestionType == 2){
			$select->from(array('t1' => 'student'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'));
		
			if($name){
				$select->where->like('t1.first_name','%'.$name.'%');
			}
			if($suggestionId){
				$select->where(array('t1.student_id' =>$suggestionId));
			}
			if($organisation_id){
				$select->where(array('t1.organisation_id' =>$organisation_id));
			}
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 

	}

	/*
	* Get Appointment List depending on the status
	*/
	
	public function getStaffAppointmentList($status, $organisation_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_appointment'))
		   ->join(array('t2' => 'counselor'),
				't2.id = t1.counselor_id', array('employee_details_id'))
		   ->join(array('t3' => 'employee_details'),
				't3.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.appointment_status' => $status, 't1.organisation_id' => $organisation_id, 't2.employee_details_id' => $employee_details_id, 't1.applicant_type' => '1'));
	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}


	public function getStdAppointmentList($status, $organisation_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_appointment'))
		   ->join(array('t2' => 'counselor'),
				't2.id = t1.counselor_id', array('employee_details_id'))
		   ->join(array('t3' => 'student'),
				't3.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
		$select->where(array('t1.appointment_status' => $status, 't1.organisation_id' => $organisation_id, 't2.employee_details_id' => $employee_details_id, 't1.applicant_type' => '2'));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStaffScheduledAppointmentList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'employee_details'),
				't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '1'));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStdScheduledAppointmentList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'student'),
				't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '2'));
		   	

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findStdScheduledAppointmentList($tableName, $status, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'student'),
				't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '2', 't1.scheduled_status' => $status));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findStaffScheduledAppointmentList($tableName, $status, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'employee_details'),
				't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '1', 't1.scheduled_status' => $status));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findStdCounselingRecordList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'counseling_scheduled_appointments'),
				't2.id = t1.scheduled_counseling_id', array('counseling_type', 'counseling_appointment_id', 'scheduled_time', 'scheduled_date', 'venue'))
		   ->join(array('t3' => 'counseling_appointment'),
				't3.id = t2.counseling_appointment_id', array('subject', 'description', 'appointment_time', 'appointment_date', 'remarks'))
		   ->join(array('t4' => 'student'),
				't4.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '2'));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findStaffCounselingRecordList($tableName, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
		   ->join(array('t2' => 'counseling_scheduled_appointments'),
				't2.id = t1.scheduled_counseling_id', array('counseling_type', 'counseling_appointment_id', 'scheduled_time', 'scheduled_date', 'venue'))
		   ->join(array('t3' => 'counseling_appointment'),
				't3.id = t2.counseling_appointment_id',array('subject', 'description', 'appointment_time', 'appointment_date', 'remarks'))
		   ->join(array('t4' => 'employee_details'),
				't4.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.counselor' => $employee_details_id, 't1.applicant_type' => '1'));
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the details for the scheduled counseling appointments
	*/
	
	public function findScheduledCounseling($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'counseling_appointment'){
			$select->from(array('t1' => 'counseling_scheduled_appointments')) 
                    ->join(array('t2' => $tableName), 
                            't1.counseling_appointment_id = t2.id', array('applicant_id', 'applicant_type', 'subject', 'description', 'remarks', 'appointment_time', 'appointment_date'))
                    ->where('t1.id = ' .$id);
		}
		else if($tableName = 'counseling_suggest'){
			$select->from(array('t1' => 'counseling_scheduled_appointments')) 
                    ->join(array('t2' => $tableName), 
                            't1.counseling_appointment_id = t2.id', array('suggested_id', 'suggested_type', 'subject', 'reason'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getCounselingType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'counseling_scheduled_appointments'){
        	$select->from(array('t1' => 'counseling_scheduled_appointments'))
    		   ->columns(array('counseling_type'))
    	   	   ->where(array('t1.id' => $id)); 
        }
        else if($tableName = 'counseling_record'){
        	$select->from(array('t1' => 'counseling_scheduled_appointments'))
    		   ->columns(array('counseling_type'))
    		   ->join(array('t2' => $tableName),
    				't1.id = t2.scheduled_counseling_id')
    	   	   ->where(array('t2.id' => $id)); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counselingType = NULL;
		foreach($resultSet as $set)
		{
			$counselingType = $set['counseling_type'];
		}
		return $counselingType;
	}


	public function findCounselingRecordDetails($counselingType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($counselingType == 'Appointment'){
			$select->from(array('t1' => 'counseling_record'))
		       ->join(array('t2' => 'counseling_scheduled_appointments'),
		   			't2.id = t1.scheduled_counseling_id', array('scheduled_time', 'scheduled_date', 'venue', 'counseling_appointment_id', 'counselor_remarks')) 
                    ->join(array('t3' => 'counseling_appointment'), 
                            't2.counseling_appointment_id = t3.id', array('applicant_id', 'applicant_type', 'subject', 'appointment_time', 'appointment_date', 'description', 'remarks'))
                    ->where('t1.id = ' .$id);
		}
		else if($counselingType == 'Recommended'){
			$select->from(array('t1' => 'counseling_record'))
		       ->join(array('t2' => 'counseling_scheduled_appointments'),
		   			't2.id = t1.scheduled_counseling_id', array('scheduled_time', 'scheduled_date', 'venue', 'counseling_appointment_id', 'counselor_remarks')) 
                    ->join(array('t3' => 'counseling_suggest'), 
                            't2.counseling_appointment_id = t3.id', array('suggested_id', 'suggested_type', 'subject', 'reason'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getCounselingRecordDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'counseling_record')) //base table
                   ->join(array('t2' => 'counseling_scheduled_appointments'),
                        't2.id = t1.scheduled_counseling_id', array('scheduled_time', 'scheduled_date', 'venue', 'counseling_appointment_id'))
                   ->where(array('t1.id' => $id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();      

            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->appointmentPrototype);
            }

            throw new \InvalidArgumentException("Counseling Record with given ID: ($id) not found");
	}


	public function getCounselingRecordFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_record'))
			   ->where(array('t1.id = ?' => $id));
		//$select->columns(array('supporting_documents'));
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$fileLocation = NULL;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['documents'];
		}
		
		return $fileLocation;
	}


	public function crossCheckCounselorScheduled($scheduledTime, $scheduledDate, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'counseling_scheduled_appointments'))
    		   ->columns(array('id'))
    	   	   ->where(array('t1.scheduled_time' => $scheduledTime, 't1.scheduled_date' => $scheduledDate, 't1.scheduled_status' => 'Pending', 't1.counselor' => $employee_details_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$counselorScheduled = NULL;
		foreach($resultSet as $set)
		{
			$counselorScheduled = $set['id'];
		}
		return $counselorScheduled;
	}

	public function findCounselingApplicantType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'counseling_appointment'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('applicant_type'))
    		   ->join(array('t2' => 'counseling_scheduled_appointments'),
    				't1.id = t2.counseling_appointment_id', array('id'))
    	   	   ->where(array('t2.id' => $id)); 
        }
        else if($tableName == 'counseling_record'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('applicant_type'))
    	   	   ->where(array('t1.id' => $id)); 
        }
        else if($tableName == 'counseling_suggest'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('applicant_type' => 'suggested_type'))
    		   ->join(array('t2' => 'counseling_scheduled_appointments'),
    				't1.id = t2.counseling_appointment_id')
    	   	   ->where(array('t2.id' => $id)); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$applicantType = NULL;
		foreach($resultSet as $set)
		{
			$applicantType = $set['applicant_type'];
		}
		return $applicantType;
	}


	public function findRecommendedCounselingType($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'counseling_suggest'){
        	$select->from(array('t1' => $tableName))
    		   ->columns(array('suggested_type'))
    		   ->join(array('t2' => 'counseling_scheduled_appointments'),
    				't1.id = t2.counseling_appointment_id', array('id'))
    	   	   ->where(array('t2.id' => $id)); 
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		$applicantType = NULL;
		foreach($resultSet as $set)
		{
			$applicantType = $set['suggested_type'];
		}
		return $applicantType;
	}


	public function findApplicantDetails($id, $applicantType)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($applicantType == 1){
			$select->from(array('t1' => 'counseling_scheduled_appointments')) 
                    ->join(array('t2' => 'counseling_appointment'), 
                            't1.counseling_appointment_id = t2.id', array('applicant_id'))
                    ->join(array('t3' => 'employee_details'),
                			't3.id = t2.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where('t1.id = ' .$id);
		}
		else if($applicantType == 2){
			$select->from(array('t1' => 'counseling_scheduled_appointments')) 
                    ->join(array('t2' => 'counseling_appointment'), 
                            't1.counseling_appointment_id = t2.id', array('applicant_id'))
                    ->join(array('t3' => 'student'),
                			't3.id = t2.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findRecommendedDetails($tableName, $id, $applicantType)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if(($applicantType == 1)&&($tableName == 'counseling_scheduled_appointments')){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'counseling_suggest'), 
                            't1.counseling_appointment_id = t2.id', array('suggested_id'))
                    ->join(array('t3' => 'employee_details'),
                			't3.id = t2.suggested_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where('t1.id = ' .$id);
		}
		else if(($applicantType == 2)&&($tableName == 'counseling_scheduled_appointments')){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'counseling_suggest'), 
                            't1.counseling_appointment_id = t2.id', array('suggested_id'))
                    ->join(array('t3' => 'student'),
                			't3.id = t2.suggested_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
                    ->where('t1.id = ' .$id);
		}

		else if(($applicantType == 1)&&($tableName == 'counseling_suggest')){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'employee_details'),
                			't2.id = t1.suggested_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where('t1.id = ' .$id);
		}
		else if(($applicantType == 2)&&($tableName == 'counseling_suggest')){
			$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'student'),
                			't2.id = t1.suggested_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getApplicantDetails($id, $applicantType)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($applicantType == 1){
			$select->from(array('t1' => 'counseling_record')) 
                    ->join(array('t2' => 'employee_details'),
                			't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where('t1.id = ' .$id);
		}
		else if($applicantType == 2){
			$select->from(array('t1' => 'counseling_record')) 
                    ->join(array('t2' => 'student'),
                			't2.id = t1.applicant_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
                    ->where('t1.id = ' .$id);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the details for the student id from the scheduled counseling appointments
	*/
	
	public function findStudentId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('counseling_scheduled_appointments');
		$select->where(array('id = ? ' => $id));
		$select->columns(array('student_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getStaffDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) // base table
				->where('t1.id = ' .$id); // join expression
		
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
	* Get the counseling notes for a particular student
	*/
	
	public function getCounselingNotes($studentId)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'counseling_record'))
				->where('t1.student_id = ' .$studentId);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function encryptIt($q, $key) 
	{
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $key ), $q, MCRYPT_MODE_CBC, md5( md5( $key ) ) ) );
    return( $qEncoded );
	}


	/**
	* Simple string encryption/decryption function.
	* CHANGE $secret_key and $secret_iv !!!
	**/
	
	public function stringEncryption($action, $string){
	 $output = false;
	 
	 $encrypt_method = 'AES-256-CBC';                // Default
	 $secret_key = 'Some#Random_Key!';               // Change the key!
	 $secret_iv = '!IV@_$2';  // Change the init vector!
	 
	  // hash
	 $key = hash('sha256', $secret_key);
	 
	  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	 $iv = substr(hash('sha256', $secret_iv), 0, 16);
	 
	  if( $action == 'encrypt' ) {
	      $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	      $output = base64_encode($output);
	  }
	 else if( $action == 'decrypt' ){
	      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	  }
	 
	  return $output;
	}
}