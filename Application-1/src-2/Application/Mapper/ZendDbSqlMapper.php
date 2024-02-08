<?php

namespace Application\Mapper;

use Application\Model\Application;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ApplicationMapperInterface
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
	 * @var \Application\Model\ApplicationInterface
	*/
	protected $applicationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Application $applicationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->applicationPrototype = $applicationPrototype;
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'parent_portal_access'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.parent_cid' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.email' => $username));
			$select->columns(array('id'));
		}

		else if($tableName == 'alumni'){
			$select->from(array('t1' => $tableName));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('id'));
		}
		
			
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

		else if($usertype == 3){
			$select->from(array('t1' => 'parent_portal_access'));
			$select->where(array('parent_cid' => $username))
				   ->join(array('t2' => 'student_relation_details'),
						't1.parent_cid = t2.parent_cid');
			$select->columns(array('first_name' => 'parent_name', 'middle_name' => NULL, 'last_name' => NULL));
		}	

		else if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('email' => $username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}	

		else if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('cid' => $username));
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

		if($usertype == 3){
			$select->from(array('t1' => 'parent_portal_access'));
			$select->where(array('t1.parent_cid' => $username));
			$select->columns(array('profile_picture'=>NULL));
		}	

		if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('t1.email' => $username));
			$select->columns(array('profile_picture'=>NULL));
		}

		if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('profile_picture'=>NULL)); 

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
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username, $usertype)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('t1.emp_id' => $username));
			$select->columns(array('organisation_id'));
		}

		if($usertype == 2){
			$select->from(array('t1' => 'student'));
			$select->where(array('t1.student_id' => $username));
			$select->columns(array('organisation_id'));
		}

		if($usertype == 5){
			$select->from(array('t1' => 'alumni'));
			$select->where(array('t1.cid' => $username));
			$select->columns(array('organisation_id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the leave notifications
	*/
	
	public function getNotifications($notification_type, $userrole, $employee_details_id)
	{
		$notifications = array(); 

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'users'))
					->columns(array('username'))
					->join(array('t2' => 'employee_details'), 
                            't1.username = t2.emp_id', array('departments_units_id'))
                    ->join(array('t3'=>'notifications'),
                            't2.departments_id = t3.submission_to_department');
		$select->where(array('t2.id' =>$employee_details_id));
		$select->where(array('t3.submission_to' =>$userrole));
		$select->where(array('t3.notification_status' => $notification_type, 't3.view_status' => 'Pending'));
		
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$notifications[] = $set;
		}
		return $notifications;
	}
	
	
	/*
	* Get the important upcoming dates
	*
	* The events will also depending on the userrole
	*/
	
	public function getUpcomingDates($userrole)
	{
		$upcoming_date = array();
		
		$hrd_dates = $this->getHRProposalDate('HRD Proposal');
		$hrm_dates = $this->getHRProposalDate('HRM Proposal');
		$research_dates = $this->getResearchDate();
		
		$upcoming_date[] = $hrd_dates;
		$upcoming_date[] = $hrm_dates;
		//$upcoming_date[] = $research_dates;
		
		return $upcoming_date;
	}


	/*
	*Get staff details based on employee details id
	**/
	public function getStaffDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'departments'),
					't2.id = t1.departments_id', array('department_name'))
			   ->join(array('t3' => 'department_units'),
					't3.id = t1.departments_units_id', array('unit_name'))
		       ->where(array('t1.id = ?' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	/*
	*Get present position title
	**/
	public function getPresentPositionTitle($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_title'))
			   ->join(array('t2' => 'position_title'),
					't2.id = t1.position_title_id', array('position_title'))
		       ->where(array('t1.employee_details_id = ?' => $employee_details_id))
		       ->order(array('t1.date DESC'))
		       ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $position_title = NULL;
        foreach($resultSet as $detail){
			 $position_title = $detail['position_title'];
		}
		return $position_title;
	}

	/*
	*Get present position level
	**/
	public function getPresentPositionLevel($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_level'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level_id', array('position_level'))
		       ->where(array('t1.employee_details_id = ?' => $employee_details_id))
		       ->order(array('t1.date DESC'))
		       ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $position_level = NULL;
        foreach($resultSet as $detail){
			 $position_level = $detail['position_level'];
		}
		return $position_level;
	}


	public function getNumberOfStudents()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->columns(array('id', 'organisation_id', 'gender'))
			   ->where(array('student_status_type_id' => '1'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$temp_student_no = array();
		$student_no = array();

		//replace both the foreach with "count"
		foreach($resultSet as $set){
			$temp_student_no[$set['organisation_id']][$set['id']] = $set['id'];
		}

		foreach($temp_student_no as $key => $value){
			$student_no[$key] = count($temp_student_no[$key]);
		}

		return $student_no;
	}


	public function getNumberOfStaff()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->columns(array('id', 'organisation_id'))
			   ->where(array('t1.emp_resignation_id' => '0'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$temp_staff_no = array();
		$staff_no = array();
		foreach($resultSet as $set){
			$temp_staff_no[$set['organisation_id']][$set['id']] = $set['id'];
		}

		foreach($temp_staff_no as $key => $value){
			$staff_no[$key] = count($temp_staff_no[$key]);
		}

		return $staff_no;
	}

	public function getStaffOnLeave($organisation_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id', 'departments_id', 'departments_units_id'))
			   ->join(array('t3' => 'departments'),
					't3.id = t2.departments_id', array('department_name'))
			   ->join(array('t4' => 'department_units'),
					't4.id = t2.departments_units_id', array('unit_name'))
			   ->join(array('t5' => 'employee_details'),
					't1.approved_by = t5.id', array('fname' => 'first_name', 'mname' => 'middle_name', 'lname' => 'last_name', 'aemp_id' => 'emp_id'))
			   ->where(array('t2.organisation_id' => $organisation_id, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date, 't1.leave_status' => 'Approved'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the last Date for HRD / HRM Proposal
	*/
	
	public function getHRProposalDate($hr_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_activation_date'))
				->join(array('t2' => 'five_year_plan'), 
                            't1.five_year_plan = t2.five_year_plan', array('five_year_plan'));
		//$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));
		$select->where(array('t1.hr_proposal_type' => $hr_type));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$proposal_date = array();
		foreach($resultSet as $detail){
			 $proposal_date['date'] = $detail['end_date'];
			 $proposal_date['remarks'] = "The Last Date for the Submission of ".$hr_type;
		}
		
		return $proposal_date;
	}
	
	/*
	* Get the last Date for CARG/AURG
	*/
	
	public function getResearchDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_grant_announcement'))
					->join(array('t2' => 'research_type'), 
                            't1.research_grant_type = t2.id', array('grant_type'));
                   // ->where('t1.employee_details_id = ' .$employee_id);
		$select->where(array('t1.start_date <= ? ' => date('Y-m-d'), 't1.end_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the last Date for Publication
	*/
	
	public function getPublicationDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'research_publication_announcement'));
		$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the last Date for APA / IWP Submission
	*/
	
	public function getPMSDate()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'apa_activation_date'));
		$select->where(array('t2.from_date <= ? ' => date('Y-m-d'), 't2.to_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}



	public function getStudentDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'student_semester_registration'),
					't1.id = t2.student_id', array('semester_id'))
			   ->join(array('t3' => 'student_semester'),
					't3.id = t2.semester_id', array('semester', 'programme_year_id'))
			   ->join(array('t4' => 'programmes'),
					't4.id = t1.programmes_id', array('programme_name'))
		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStdCurrentSemesterDetails($student_id, $organisation_id)
	{
        $academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$assessment_component = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $assessment_component = $this->getAssessmentComponentWeightage($year, $semester, $programme, $academicYear);

        return $assessment_component;
	}


	public function getAssessmentComponentWeightage($year, $semester, $programme, $academicYear)
	{ 
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;

	}


	public function getAcademicModuleLists($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$module_list = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $module_list = $this->getModuleLists($year, $semester, $programme, $academicYear);
       // /var_dump($module_list); die();
        return $module_list;
	}


	public function getModuleLists($year, $semester, $programme, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->join(array('t2' => 'academic_modules'),
					't2.id = t1.academic_modules_id', array('module_title', 'module_code'));
		$select->where(array('t1.year' => $year, 't1.semester' => $semester, 't1.academic_year' => $academic_year, 't1.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $modules = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$modules[$set['academic_modules_id']][$set['module_code']] = $set['module_title'];
        }

        return $modules;
	}


	public function getStdCurrentCADetails($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$assessment_component = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $ca_component = $this->getCAComponentWeightage($year, $semester, $programme, $academicYear);

        return $ca_component;
	}


	public function getCAComponentWeightage($year, $semester, $programme, $academicYear)
	{
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));
		$select->where->notLike('t1.assessment','%Semester Exams%');

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;
	}


	public function getStudentAcademicTimetable($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$academic_timetable = array();
		$year = NULl;
		$semester = NULL;
		$section = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'student_section_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$section = $set['student_section_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        } 

		$academic_timetable = $this->getAcademicTimetable($year, $semester, $section, $programme, $academicYear);
	
		return $academic_timetable;
	}


	public function getAcademicTimetable($year, $semester, $section, $programme, $academicYear)
	{
		$sql = new Sql($this->dbAdapter); 
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't2.academic_modules_id = t3.id', array('module_title', 'module_code', 'programmes_id'))
                            ->join(array('t4' => 'programmes'), 
                                't3.programmes_id = t4.id', array('programme_name'))
                            ->join(array('t5' => 'academic_modules_allocation'),
                        		't5.id = t1.academic_modules_allocation_id', array('semester'))
                            ->join(array('t6' => 'academic_module_tutors'),
                        		't6.academic_modules_allocation_id = t5.id', array('year', 'module_tutor'))
                            ->join(array('t7' => 'student_section'),
                        		't7.id = t6.section', array('section'))
                            ->join(array('t8' => 'employee_details'),
                        		't8.emp_id = t6.module_tutor', array('first_name', 'middle_name', 'last_name'));

		$select->where(array('t5.semester' => $semester, 't1.group' => $section, 't1.academic_year' => $academicYear, 't1.programmes_id' => $programme, 't3.module_year' => $year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


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


	public function getAbsenteeModuleRecord($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$student_attendance_data = array();
		$year = NULl;
		$semester = NULL;
		$section = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'student_section_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$section = $set['student_section_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        } 

        $module_list = $this->getModuleLists($year, $semester, $programme, $academicYear);
        foreach($module_list as $key=>$value){
        	foreach($value as $key2=>$value2){
        		$student_attendance_data[$key]['module_title'] = $value2;
        		$student_attendance_data[$key]['module_code'] = $key2;
        		$student_attendance_data[$key]['total_lectures_delivered'] = $this->getTotalLecturesDelivered($key, $year, $semester, $section, $programme, $academicYear);
        		$student_attendance_data[$key]['total_lectures_missed'] = $this->getTotalLecturesMissed($key, $year, $semester, $section, $programme, $academicYear, $student_id);
        	}
        }
        return $student_attendance_data;
	}


	public function getTotalLecturesDelivered($academic_modules_id, $year, $semester, $section, $programme, $academicYear)
	{
        $total_lectures = array();
        $sql = new Sql($this->dbAdapter);
		$select1 = $sql->select();

		$select1->from(array('t1' => 'student_attendance_dates'))
			   ->join(array('t2' => 'academic_timetable'),
					't2.id = t1.academic_timetable_id', array('academic_modules_allocation_id', 'group'))
			   ->join(array('t3' => 'academic_modules_allocation'),
					't3.id = t2.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id'));
		$select1->where(array('t3.academic_year' => $academicYear, 't3.semester' => $semester, 't3.year' => $year, 't3.programmes_id' => $programme, 't2.group' => $section, 't3.academic_modules_id' => $academic_modules_id));

		$stmt1 = $sql->prepareStatementForSqlObject($select1);		
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);
		
		foreach($resultSet1 as $set1){
			$total_lectures[$set1['id']] = $set1['id'];
		}	
		return count($total_lectures);
	}


	public function getTotalLecturesMissed($academic_modules_id, $year, $semester, $section, $programme, $academicYear, $student_id)
	{
		$missed_class = array();
		$studentId = $this->getStudentId($student_id);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_absentee_record'))
				->join(array('t2' => 'student_attendance_dates'), 
					't2.id = t1.student_attendance_dates_id', array('academic_timetable_id'))
				->join(array('t3' => 'academic_timetable'),
					't3.id = t2.academic_timetable_id', array('group', 'academic_modules_allocation_id'))
				->join(array('t4' => 'academic_modules_allocation'),
					't4.id = t3.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id'));
		$select->where(array('t1.student_id' => $studentId, 't4.academic_year' => $academicYear, 't4.semester' => $semester, 't4.year' => $year, 't4.programmes_id' => $programme, 't3.group' => $section, 't4.academic_modules_id' => $academic_modules_id));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$missed_class[$set['id']] = $set['id'];
		} 
		
		return count($missed_class);
	}


	public function getStudentId($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
					->columns(array('student_id'));
		$select->where(array('t1.id' => $student_id));

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


	public function getAcademicModuleTutor($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_year', 'semester', 'year', 'programmes_id', 'academic_modules_id'))
                            ->join(array('t3' => 'student'), 
                                't2.programmes_id = t3.programmes_id', array('id'))
                            ->join(array('t4' => 'student_semester_registration'), 
                                't3.id = t4.student_id', array('student_section_id','semester_id'))
                            ->join(array('t5' => 'programmes'), 
                                't1.programmes_id = t5.id', array('programme_name'))
                            ->join(array('t6' => 'academic_module_tutors'),
                        		't2.id = t6.academic_modules_allocation_id', array('module_tutor'))
                            ->join(array('t7' => 'academic_modules'),
                        		't7.id = t2.academic_modules_id', array('module_code'));
		$select->where(array('t4.student_id' => $student_id, 't1.semester = t4.semester_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Promotion Dates
	*/
	
	public function getPromotionDates($employee_id)
	{
		
	}
	
	/*
	* Get Leave Balance and Notify if balance exceeds 80 days
	*/
	
	public function getLeaveBalance($employee_id)
	{
		
	}

	public function getAcademicYear($organisation_id)
    {
    	$date = date('Y-m-d');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar')) 
               ->columns(array('academic_year'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date, 't2.organisation_id' => $organisation_id));

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


    /*
     * Get the semester from the database
     */
    
    public function getCurrentAcademicSession($organisation_id)
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
            if($set['academic_event'] == 'Start of Autumn Semester'){
                $academic_session = $set['academic_session_id'];
            }
            else if($set['academic_event'] == 'Start of Spring Semester'){
                $academic_session = $set['academic_session_id'];
            }
        }
        return $academic_session;
    }


    public function getStdAcademicModuleLists($student_id, $organisation_id)
    {
    	$academic_year = $this->getAcademicYear($organisation_id);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                             ->join(array('t2' => 'academic_modules_allocation'),
                        		't2.id = t1.academic_modules_allocation_id', array('academic_year', 'semester', 'year'))
                            ->join(array('t3' => 'student'), 
                                't2.programmes_id = t3.programmes_id', array('id'))
                            ->join(array('t4' => 'student_semester_registration'), 
                                't3.id = t4.student_id', array('student_section_id','semester_id'))
                            ->join(array('t5' => 'programmes'), 
                                't1.programmes_id = t5.id', array('programme_name'))
                            ->join(array('t6' => 'academic_modules'), 
                                't2.academic_modules_id = t6.id', array('module_code'))
                            ->join(array('t7' => 'academic_module_tutors'),
                        		't6.id = t7.academic_modules_allocation_id', array('module_tutor'));
		$select->where(array('t4.student_id' => $student_id, 't1.semester = t4.semester_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
    }


	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id','abbr', $columnName));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);		
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['abbr'];
		}
		return $selectData;
	}
}