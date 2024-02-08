<?php

namespace StaffPortal\Mapper;

use StaffPortal\Model\StaffPortal;
use StaffPortal\Model\StaffDetail;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StaffPortalMapperInterface
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
	 * @var \StaffPortal\Model\StaffPortalInterface
	*/
	protected $Prototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StaffPortal $staffPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->staffPrototype = $staffPrototype;
	}
	
	
	/**
	* @return array/JobPortal()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression

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

		if($tableName = 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id'));
		}

		else if($tableName = 'student'){
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


	public function getDeptUnitId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('departments_units_id'));
			
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



	public function getStaffPersonalDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'departments'),
					't2.id = t1.departments_id', array('department_name'))
			   ->join(array('t3' => 'department_units'),
					't3.id = t1.departments_units_id', array('unit_name'))
			   ->join(array('t4' => 'nationality'),
					't4.id = t1.nationality', array('snationality' => 'nationality'))
			   ->join(array('t5' => 'gender'),
					't5.id = t1.gender', array('sgender' => 'gender'))
			   ->join(array('t6' => 'maritial_status'),
					't6.id = t1.marital_status', array('maritial_status'))
		       ->where(array('t1.id = ?' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffPermanentAddress($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'dzongkhag'),
					't2.id = t1.emp_dzongkhag', array('dzongkhag_name'))
			   ->join(array('t3' => 'gewog'),
					't3.id = t1.emp_gewog', array('gewog_name'))
			   ->join(array('t4' => 'village'),
					't4.id = t1.emp_village', array('village_name'))
			   ->join(array('t5' => 'country'),
					't5.id = t1.country', array('scountry' => 'country'))
		       ->where(array('t1.id = ?' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


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


	public function getEmpLastLeaveDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't2.id = t1.emp_leave_category_id', array('leave_category'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpRelationDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_relation_details'))
			   ->join(array('t2' => 'nationality'),
					't2.id = t1.nationality', array('srnationality' => 'nationality'))
			   ->join(array('t3' => 'gender'),
					't3.id = t1.gender', array('srgender'=>'gender'))
			   ->join(array('t4' => 'relation_type'),
					't4.id = t1.relation_type', array('relation'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getEmpType($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'employee_type'),
					't2.id = t1.emp_type', array('employee_type'));
		$select->where(array('t1.id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpCurrentPosition($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_title'))
			   ->join(array('t2' => 'position_title'),
					't2.id = t1.position_title_id', array('position_title'))
			   ->join(array('t3' => 'position_category'),
					't3.id = t2.position_category_id', array('category'))
			   ->join(array('t4' => 'major_occupational_group'),
					't4.id = t3.major_occupational_group_id', array('major_occupational_group'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->order('id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpPositionLevel($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_level'))
			   ->join(array('t2' => 'position_level'),
					't2.id = t1.position_level_id', array('position_level'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->order('id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpDeptUnit($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'department_units'),
					't2.id = t1.departments_units_id', array('unit_name'))
			   ->join(array('t3' => 'departments'),
					't3.id = t1.departments_id', array('department_name'))
			   ->join(array('t4' => 'organisation'),
					't4.id = t1.organisation_id', array('sorganisation_name' => 'organisation_name'));
		$select->where(array('t1.id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpPublication($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'emp_previous_research'))
			   ->join(array('t2' => 'research_category'),
					't1.research_type = t2.id', array('research_category'))
			   ->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpWorkExperience($employee_details_id, $working_agency_type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($working_agency_type == 'RUB'){
			$select->from(array('t1' => 'emp_employment_record'))
			       ->join(array('t2' => 'organisation'),
				    	't2.id = t1.working_agency', array('organisation_name'))
			      ->join(array('t3' => 'position_level'),
						't3.id = t1.position_level', array('position_level'))
			      ->join(array('t4' => 'position_title'),
			  			't4.id = t1.position_title', array('position_title'))
			      ->join(array('t5' => 'position_category'),
			  			't5.id = t1.position_category', array('category'))
			      ->join(array('t6' => 'major_occupational_group'),
			  			't6.id = t1.occupational_group', array('major_occupational_group'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.working_agency_type' => $working_agency_type));
		}
		else if($working_agency_type == 'NON-RUB'){
			$select->from(array('t1' => 'emp_employment_record'));
		    $select->where(array('t1.employee_details_id' => $employee_details_id, 't1.working_agency_type' => $working_agency_type));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpEducationDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_education_details'))
			   ->join(array('t2' => 'study_level'),
					't2.id = t1.study_level', array('study_level'))
			   ->join(array('t3' => 'country'),
					't3.id = t1.college_country', array('country'))
			   ->join(array('t4' => 'funding_category'),
					't4.id = t1.funding', array('funding_type'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpAwardDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_awards'))
			   ->join(array('t2' => 'emp_award_category'),
					't2.id = t1.award_category_id', array('award_category'));
			  // ->join(array('t3' => 'department_units'),
				//	't3.id = t1.departments_units_id', array('unit_name'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getEmpCommunityServiceDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_community_services'))
			   ->join(array('t2' => 'emp_community_service_category'),
					't2.id = t1.community_service_category_id', array('community_service_category'));
			  // ->join(array('t3' => 'department_units'),
				//	't3.id = t1.departments_units_id', array('unit_name'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getEmpContributionDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_contributions'))
			   ->join(array('t2' => 'emp_contribution_category'),
					't2.id = t1.contribution_category_id', array('contribution_category'));
			  // ->join(array('t3' => 'department_units'),
				//	't3.id = t1.departments_units_id', array('unit_name'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getEmpResponsibilityDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_responsibilities'))
			   ->join(array('t2' => 'emp_responsibility_category'),
					't2.id = t1.responsibility_category_id', array('responsibility_category'));
			  // ->join(array('t3' => 'department_units'),
				//	't3.id = t1.departments_units_id', array('unit_name'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getEmpTrainingDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_previous_trainings'))
			   ->join(array('t2' => 'country'),
			   			't2.id = t1.country', array('country'))
			       ->join(array('t3' => 'funding_category'),
			   			't3.id = t1.funding', array('funding_type'))
				->where(('t1.employee_details_id = ' .$employee_details_id)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getPendingLeaveList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t1.substitution', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.leave_status' => 'Pending'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getApprovedLeaveList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t1.substitution', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.leave_status' => 'Approved'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getRejectedLeaveList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t1.substitution', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.leave_status' => 'Rejected'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffRejectedLeaveStatus($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
			   ->join(array('t2' => 'emp_leave_category'),
					't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'))
			   ->join(array('t3' => 'employee_details'),
					't3.id = t1.substitution', array('first_name', 'middle_name', 'last_name', 'emp_id'));
		$select->where(array('t1.id = ?' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffTourList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'));
			 //  ->join(array('t2' => 'emp_leave_category'),
				//	't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getApprovedTourList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'));
			 //  ->join(array('t2' => 'emp_leave_category'),
				//	't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.tour_status' => 'Approved'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getRejectedTourList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'));
			 //  ->join(array('t2' => 'emp_leave_category'),
				//	't2.id = t1.emp_leave_category_id', array('leave_category', 'total_days'));
		$select->where(array('t1.employee_details_id' => $employee_details_id, 't1.tour_status' => 'Rejected'));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffTourAuthDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
		       ->join(array('t2' => 'employee_details'),
		   			't2.id = t1.employee_details_id', array('sfirst_name' => 'first_name', 'smiddle_name' => 'middle_name', 'slast_name' => 'last_name', 'semp_id' => 'emp_id'))
		       ->join(array('t3' => 'emp_position_title'),
		   			't3.employee_details_id = t2.id', array('position_title_id', 'date'))
		       ->join(array('t4' => 'position_title'),
		   			't4.id = t3.position_title_id', array('sposition_title' => 'position_title'))
		       ->join(array('t5' => 'emp_position_level'),
		   			't5.employee_details_id = t2.id', array('position_level_id'))
		       ->join(array('t6' => 'position_level'),
		   			't6.id = t5.position_level_id', array('position_level'))
		       ->order(array('t3.date DESC', 't5.date DESC'))
		       ->limit(1);
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function deleteStaffPendingTour($id)
	{
		$action = new Delete('travel_authorization');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->deleteStaffTravelDetails($id);

		return (bool)$result->getAffectedRows();
	}


	public function deleteStaffTravelDetails($id)
	{
		$action = new Delete('travel_details');
		$action->where(array('travel_authorization_id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	public function getTourApprovingAuthority($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.authorizing_officer', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->join(array('t3' => 'emp_position_title'),
					't2.id = t3.employee_details_id', array('position_title_id', 'date'))
			   ->join(array('t4' => 'position_title'),
					't4.id = t3.position_title_id', array('position_title'))
			   ->order(array('t3.date DESC'))
			   ->limit(1);
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}
	
	
	public function findFromTravelDate($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_details'))
                ->where(array('t1.travel_authorization_id = ' .$id))
                ->order('t1.from_date ASC')
                ->limit(1);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
		
		$fromDate = NULL;
		foreach($resultSet as $set){
			$fromDate = $set['from_date'];
		}
		
		return $fromDate;
	}
	
	public function findToTravelDate($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_details'))
               ->where(array('t1.travel_authorization_id = ' .$id))
               ->order('t1.to_date DESC')
               ->limit(1);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
		
		$toDate = NULL;
		foreach($resultSet as $set){
			$toDate = $set['to_date'];
		}
		
		return $toDate;
	}


	public function getStaffTourDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->join(array('t2' => 'travel_details'),
					't1.id = t2.travel_authorization_id', array('from_station', 'from_date', 'to_station', 'to_date', 'mode_of_travel', 'halt_at', 'purpose_of_tour'));
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffJobApplicataionList($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_job_applications'))
			   ->join(array('t2' => 'vacancy_announcements'),
					't2.id = t1.vacancy_announcements_id', array('working_agency', 'employee_type', 'position_title', 'position_category', 'position_level'))
			   ->join(array('t3' => 'organisation'),
					't3.id = t2.working_agency', array('organisation_name'))
			   ->join(array('t4' => 'employee_type'),
					't4.id = t2.employee_type', array('employee_type'))
			   ->join(array('t5' => 'position_title'),
					't5.id = t2.position_title', array('position_title'))
			   ->join(array('t6' => 'position_category'),
					't6.id = t2.position_category', array('category'))
			   ->join(array('t7' => 'position_level'),
					't7.id = t2.position_level', array('position_level'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStaffPromotionDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_promotion'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffResignationDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_resignation'))
			   ->join(array('t2' => 'resignation_type'),
					't2.id = t1.resignation_type', array('resignation_type'))
		      ->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStaffTransferDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_transfer_application'))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.transfer_request_to', array('organisation_name'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStaffAttendance($from_date, $to_date, $employee_details_id)
	{
		$attendanceData = array();
		$leaveData = array();
		$tourData = array();
		$index = 1;

		$leaveData = $this->getLeaveData($from_date, $to_date, $employee_details_id);
		$tourData = $this->getTourData($from_date, $to_date, $employee_details_id);

				
		foreach($leaveData as $key=>$value){
			foreach($value as $key1 => $value1){
				$attendanceData[$key][$key1]= $value1;
			}
			$index++;
		}
		
		foreach($tourData as $key=>$value){
			foreach($value as $key1 => $value1){
				$attendanceData[$key][$key1]= $value1;
			}
			$index++;
		}		
		return $attendanceData;
	}


	public function getLeaveData($from_date, $to_date, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'));
		$select->columns(array('id','from_date','to_date','employee_details_id','emp_leave_category_id'));
		$select->where(array('employee_details_id' => $employee_details_id));
		$select->where(array('from_date >= ? ' => $from_date));
		$select->where(array('to_date <= ? ' => $to_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result); 
		$leaveData = array();
		foreach($resultSet as $data){
			$leaveData[$data['employee_details_id']] = $data;
		}
		return $leaveData;
	}


	public function getTourData($from_date, $to_date, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
				->columns(array('id','employee_details_id'))
				->join(array('t2' => 'travel_details'), 
                            't1.id = t2.travel_authorization_id', array('from_date','to_date'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->where(array('t2.from_date >= ? ' => $from_date));
		$select->where(array('t2.to_date <= ? ' => $to_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		$resultSet->initialize($result);
		$tourData = array();
		foreach($resultSet as $data){
			if(!array_key_exists($data['employee_details_id'], $tourData)){
				$tourData[$data['employee_details_id']] = $data;
			} else{
				foreach($data as $key=>$value){
					if($key != 'from_date'){
						$tourData[$data['employee_details_id']][$key] = $value;
					}
				}
			}
		}
		return $tourData;
	}


	public function getAttendanceRecordDates($from_date, $to_date, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		//get the "from" month
		//The $to_date argument is no longer required. Was used in old function that did not work
		 $from_month = substr($from_date,5,2);
		 $from_year = substr($from_date, 0,4);
		//get number of dats in the selected month
		$days_in_month = cal_days_in_month(CAL_GREGORIAN,substr($from_date,5,2),date('Y'));
		$start_date = $from_year.'-'.substr($from_date,5,2).'-'.'01';
		$end_date = $from_year.'-'.substr($from_date,5,2).'-'.$days_in_month;

		$select->from(array('t1' => 'emp_attendance_record_dates'));
		$select->columns(array('from_date','to_date'));
		$select->where(array('departments_units_id' => $departments_units_id));
		$select->where(array('from_date >= ? ' => $start_date));
		$select->where(array('to_date <= ? ' => $end_date));

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getAbsenteeList($from_date, $to_date, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_attendance'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->where->between('t1.absent_date', $from_date, $to_date);

		$stmt = $sql->prepareStatementForSqlObject($select);		
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
	}

	public function getWeekends($from_date, $to_date)
	{
		$weekends = array();
		$type = CAL_GREGORIAN;
		$month = substr($from_date,5,2); // Month ID, 1 through to 12.
		$year = substr($from_date,0,4); // Year in 4 digit 2009 format.
		$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
		
		//loop through all days
		for ($i = 1; $i <= $day_count; $i++) {
		
				$date = $year.'/'.$month.'/'.$i; //format date
				$get_name = date('l', strtotime($date)); //get week day
				$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
		
				//if not a weekend add day to array
				if($day_name == 'Sun' || $day_name == 'Sat'){
					$weekends[] = $i;
					
				}
		}
		return $weekends;
	}


	public function getStaffLeaveEncashmentDetails($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave_encashment'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
			   //->order('t1.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getAcademicAssessmentDetails($assessment_component_id, $employee_details_id, $organisation_id) {
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_assessment'))
			   ->join(array('t2' => 'assessment_component'),
					't2.id = t1.assessment_component_id', array('assessment_component' => 'assessment'));
		$select->where(array('t1.assessment_component_id' => $assessment_component_id));
			   //->order('t1.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	//Function to return unassigned sub menu list
	public function getNotAssignedSubMenuList($parent_id)
	{ echo $parent_id; die();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_menu'))
			   ->where(array('t1.user_menu_id' => $parent_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
	        $result = $stmt->execute();
	        
	        $resultSet = new ResultSet();
	        return $resultSet->initialize($result);

			
	}


	public function getUnassignedRouteList($category_id)
	{
		$routes = array();

		$route_list = $this->getAllList($category_id, 'route');
		$assigned_route_list = $this->getAssignedList($category_id, 'route');

		$unassigned_route_list = array_diff_key($route_list, $assigned_route_list);
		foreach($unassigned_route_list as $key => $value){
			$routes[$key] = $value;
		}
		//var_dump($menu_list); die();
		return $routes;
	}


	//Function to return array of sub menu list of particular parent menu
	public function getAllList($parent_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'sub_menu'){
			$sub_menu_list = array();

			$select->from(array('t1' => 'user_menu'));
			$select->where(array('t1.user_menu_id' => $parent_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$sub_menu_list[$set['id']] = $set;
			}

			return $sub_menu_list;
		}
		else{
			$route_list = array();

			$select->from(array('t1' => 'route_list'));
			$select->where(array('t1.id' => $parent_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$route_list[$set['id']] = $set;
			}

			return $route_list;
		}
		

	}


	//Function to return array of assigned sub menu list of particular parent menu
	public function getAssignedList($parent_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'sub_menu'){
			$sub_menu_list = array();

			$select->from(array('t1' => 'user_routes'));
			$select->where(array('t1.parent_menu_id' => $parent_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$sub_menu_list[$set['user_sub_menu_id']] = $set;
			}

			return $sub_menu_list;
		}
		else {
			
			$route_list = array();

			$select->from(array('t1' => 'user_routes'));
			$select->where(array('t1.route_category' => $parent_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$route_list[$set['id']] = $set;
			}

			return $route_list;
		}

    }
}