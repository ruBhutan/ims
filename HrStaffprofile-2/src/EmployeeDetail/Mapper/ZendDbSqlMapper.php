<?php

namespace EmployeeDetail\Mapper;

use EmployeeDetail\Model\EmployeeDetail;
use EmployeeDetail\Model\NewEmployeeDetail;
use EmployeeDetail\Model\NewEmployee;
use EmployeeDetail\Model\NewEmployeeDocuments;
use EmployeeDetail\Model\EmployeeAward;
use EmployeeDetail\Model\EmployeeContribution;
use EmployeeDetail\Model\EmployeeResponsibilities;
use EmployeeDetail\Model\EmployeeCommunityService;
use EmployeeDetail\Model\EmployeeRelationDetail;
use EmployeeDetail\Model\EmployeeEducation;
use EmployeeDetail\Model\EmployeePublications;
use EmployeeDetail\Model\EmployeeTrainings;
use EmployeeDetail\Model\EmployeeWorkExperience;
use EmployeeDetail\Model\EmployeeLevel;
use EmployeeDetail\Model\EmployeeTitle;
use EmployeeDetail\Model\EmployeeProfilePicture;
use EmployeeDetail\Model\EmployeePersonalDetails;
use EmployeeDetail\Model\EmployeePermanentAddress;
use EmployeeDetail\Model\EmployeeDisciplineRecord;
use EmployeeDetail\Model\EmployeeOnProbation;
use EmployeeDetail\Model\EmployeePayDetails;
use EmployeeDetail\Model\UpdateNewEmpDoc;
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

class ZendDbSqlMapper implements EmployeeMapperInterface
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
	 * @var \Blog\Model\PostInterface
	*/
	protected $employeeDetailPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmployeeDetail $employeeDetailPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->employeeDetailPrototype = $employeeDetailPrototype;
	}
	
	/**
	* @param int/String $id
	* @return EmployeeDetail
	* @throws \InvalidArgumentException
	*/
	
	public function find($id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'Personal Details'){
			$select->from(array('t1' => 'employee_details'))
			       ->join(array('t2' => 'maritial_status'),
			   			't2.id = t1.marital_status', array('maritial_status'))
			       ->join(array('t3' => 'gender'),
			   			't3.id = t1.gender', array('emp_gender' => 'gender'))
			       ->join(array('t4' => 'country'),
			   			't4.id = t1.country', array('emp_country' => 'country'))
			       ->join(array('t5' => 'nationality'),
			   			't5.id = t1.nationality', array('emp_nationality' => 'nationality'))
			       ->join(array('t6' => 'blood_group'),
			   			't6.id = t1.blood_group', array('emp_blood_group' => 'blood_group'))
			       ->join(array('t7' => 'religion'),
			   			't7.id = t1.religion', array('emp_religion' => 'religion'))
			       ->where(array('t1.id = ?' => $id));

		}

		else if($type == 'Permanent Address'){
			$select->from(array('t1' => 'employee_details'))
				   ->where(array('t1.id = ?' => $id));
		}

		else if($type == 'Employment Details'){
			$select->from(array('t1' => 'employee_details'))
				   ->join(array('t2' => 'organisation'),
						't2.id = t1.organisation_id', array('organisation_name'))
				   ->join(array('t3' => 'departments'),
						't3.id = t1.departments_id', array('department_name'))
				   ->join(array('t4' => 'department_units'),
						't4.id = t1.departments_units_id', array('unit_name'))
				   ->join(array('t5' => 'employee_type'),
						't5.id = t1.emp_type', array('employee_type'))
				   ->where(array('t1.id = ?' => $id));
		}

		else if($type == 'Position Title Details'){
			$select->from(array('t1' => 'employee_details'))
				   ->join(array('t2' => 'emp_position_title'),
						't2.employee_details_id = t1.id', array('position_title_id'))
				   ->join(array('t3' => 'position_title'),
						't3.id = t2.position_title_id', array('position_title'))
				   ->where(array('t1.id = ?' => $id));
		}
		else if($type == 'Position Level Details'){
			$select->from(array('t1' => 'employee_details'))
			->join(array('t2' => 'emp_position_level'),
						't2.employee_details_id = t1.id', array('position_level_id'))
				   ->join(array('t3' => 'position_level'),
						't3.id = t2.position_level_id', array('position_level'))
				   ->where(array('t1.id = ?' => $id));
		}

		else if($type == NULL){
			$select->from(array('t1' => 'employee_details'))
				   ->where(array('t1.id = ?' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->employeeDetailPrototype);
		}
		throw new \InvalidArgumentException("Employee with given ID: ($id) not found");
	}
	
	/**
	* @return array/EmployeeDetail()
	*/
	public function findAll($organisation_id, $staff_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id == '1'){
			$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id', 'cid','date_of_birth', 'phone_no','email','recruitment_date'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'))
				->join(array('t5' => 'department_units'),
						't5.id = t1.departments_units_id', array('unit_name'))
				->join(array('t6' => 'emp_position_level'), 
						't1.id = t6.employee_details_id', array('position_level_id'))
				->join(array('t7' => 'position_level'), 
						't6.position_level_id = t7.id', array('position_level'))
				->join(array('t8' => 'organisation'),
						't8.id = t1.organisation_id', array('abbr'))
				->join(array('t9' => 'major_occupational_group'),
						't9.id = t7.major_occupational_group_id', array('major_occupational_group'))
				->join(array('t10' => 'gender'),
						't10.id = t1.gender', array('gender'))
				->join(array('t11' => 'country'),
						't11.id = t1.country', array('country'))
				->join(array('t12' => 'employee_type'),
						't12.id = t1.emp_type', array('employee_type'))
				->where(array('t1.emp_resignation_id' => '0'))
				->where(array('t1.id' => $staff_id))
				->order('t1.first_name ASC')
				->limit(20);
		}
		else {
			$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id', 'cid','date_of_birth', 'phone_no','email','recruitment_date'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'))
				->join(array('t5' => 'department_units'),
						't5.id = t1.departments_units_id', array('unit_name'))
				->join(array('t6' => 'emp_position_level'), 
						't1.id = t6.employee_details_id', array('position_level_id'))
				->join(array('t7' => 'position_level'), 
						't6.position_level_id = t7.id', array('position_level'))
				->join(array('t8' => 'organisation'),
						't8.id = t1.organisation_id', array('abbr'))
				->join(array('t9' => 'major_occupational_group'),
						't9.id = t7.major_occupational_group_id', array('major_occupational_group'))
				->join(array('t10' => 'gender'),
						't10.id = t1.gender', array('gender'))
				->join(array('t11' => 'country'),
						't11.id = t1.country', array('country'))
				->join(array('t12' => 'employee_type'),
						't12.id = t1.emp_type', array('employee_type'))
				->where(array('t1.organisation_id = ' .$organisation_id, 't1.emp_resignation_id' => '0'))
				->where(array('t1.id' => $staff_id))
				->order('t1.first_name ASC')
				->limit(20);
		}
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getEmpPermanentAddress($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'dzongkhag'), 
						't1.emp_dzongkhag = t2.id', array('dzongkhag_name'))
				->join(array('t3' => 'gewog'), 
						't1.emp_gewog = t3.id', array('gewog_name'))
				->join(array('t4' => 'village'), 
						't1.emp_village = t4.id', array('village_name'))
				->where(array('t1.id = ' .$id));				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listAllNewEmployees($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id == 1){
			$select->from(array('t1' => 'new_employee_details')) 
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'position_title'), 
						't1.position_title_id = t3.id', array('position_title'))
				->join(array('t4' => 'position_level'),
						't1.position_level_id = t4.id', array('position_level'))
				->join(array('t5' => 'organisation'),
						't1.organisation_id = t5.id', array('organisation_name'));
		}
		else{
			$select->from(array('t1' => 'new_employee_details')) 
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'position_title'), 
						't1.position_title_id = t3.id', array('position_title'))
				->join(array('t4' => 'position_level'),
						't1.position_level_id = t4.id', array('position_level'))
				->join(array('t5' => 'organisation'),
						't1.organisation_id = t5.id', array('organisation_name'))
				->where(array('t1.organisation_id = ' .$organisation_id));
		}
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/**
	* @return array/EmployeeDetail() who are in probation period
	*/
	public function listAllEmployeesOnProbation($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'))
				->where(array('t1.organisation_id = ' .$organisation_id, 't1.emp_type' => '1'))
				->order('t3.date ASC')
				->limit(20);
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getNewEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'new_employee_details')) 
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'position_title'), 
						't1.position_title_id = t3.id', array('position_title', 'position_category_id'))
				->join(array('t4' => 'position_level'),
						't1.position_level_id = t4.id', array('position_level', 'major_occupational_group_id'))
				->join(array('t5' => 'nationality'),
					't5.id = t1.nationality', array('enationality' => 'nationality'))
				->join(array('t6' => 'maritial_status'),
					't6.id = t1.marital_status', array('maritial_status'))
				->join(array('t7' => 'dzongkhag'),
					't7.id = t1.emp_dzongkhag', array('dzongkhag_name'))
				->join(array('t8' => 'gewog'),
					't8.id = t1.emp_gewog', array('gewog_name'))
				->join(array('t9' => 'village'),
					't9.id = t1.emp_gewog', array('village_name'))
				->join(array('t10' => 'employee_type'),
					't10.id = t1.emp_type', array('employee_type'))
				->join(array('t11' => 'gender'),
					't11.id = t1.gender', array('egender' => 'gender'))
				->join(array('t12' => 'blood_group'),
					't12.id = t1.blood_group', array('eblood_group' => 'blood_group'))
				->join(array('t13' => 'religion'),
					't13.id = t1.religion', array('ereligion' => 'religion'))
				->join(array('t14' => 'departments'),
					't14.id = t1.departments_id', array('department_name'))
				->join(array('t15' => 'department_units'),
					't15.id = t1.departments_units_id', array('unit_name'))
				->join(array('t16' => 'position_category'),
					't16.id = t3.position_category_id', array('category'))
				->join(array('t17' => 'major_occupational_group'),
					't17.id = t4.major_occupational_group_id', array('major_occupational_group'))
				->join(array('t18' => 'country'),
					't18.id = t1.country', array('ecountry' => 'country'))
				->where(array('t1.id' => $id));
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getNewEmployeeGeneratedId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'new_employee_details')) 
				->join(array('t2' => 'employee_details'), 
						't1.cid = t2.cid', array('emp_id'))
				->where(array('t1.id' => $id));
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getFileName($new_employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'new_employee_details')) 
				->columns(array('evidence_file'))
				->where('t1.id = ' .$new_employee_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getOVCHroEmailId($role)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'users'))
			   ->join(array('t2' => 'employee_details'),
					't2.emp_id = t1.username', array('email'));
		$select->where(array('t1.role' =>$role));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$email = array();
		foreach($resultSet as $set){
			$email[] = $set['email'];
		} 
		return $email;
	}


	public function getOrganisationDetails($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'organisation'));
		$select->where(array('t1.id' => $organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getNewEmpFileName($new_employee_doc_id, $document_type)
	{
		$column_name = $document_type.'_doc';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'new_employee_documents')) 
				->columns(array($column_name))
				->where('t1.new_employee_details_id = ' .$new_employee_doc_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$file_location = $set[$column_name];
		}
		return $file_location;
	}


	public function getNewEmpFileUploaded($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'new_employee_documents')) 
				->where('t1.new_employee_details_id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getUploadedFileLink($tableName, $columnName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName))
			   ->columns(array($columnName)) 
			   ->where(array('t1.id' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$document = NULL;
		foreach($resultSet as $set){
			$document = $set[$columnName];
		}
		return $document;
	}


	public function getEmployeeDetailsId($tableName, $id)
	{
		$employee_details_id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_relation_details'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_employment_record'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_education_details'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_previous_trainings'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_previous_research'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_responsibilities'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_contributions'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_awards'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}

		if($tableName == 'emp_community_services'){
			$select->from(array('t1' => $tableName))
                    ->where('t1.id = ' .$id);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_details_id = $set['employee_details_id'];
		}
		
		return $employee_details_id;
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find education details related to the employee
	 */
	public function findEducationDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'emp_education_details'), 
						't1.id = t2.employee_details_id')
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->employeeDetailPrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
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
			$select->columns(array('id', 'departments_id', 'departments_units_id'));
		} else {
			$select->where(array('student_id' =>$username));
			$select->columns(array('id'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getEmployeeProfilePicture($id)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
		       ->where(array('t1.id = ?' => $id));

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
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find parent details related to the employee
	 */
	
	public function findParentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'emp_parents_details'), 
						't1.id = t2.employee_details_id')
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->employeeDetailPrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find spouse details related to the employee
	 */
	
	public function findSpouseDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'emp_education'), 
						't1.id = t2.employee_details_id')
				->join(array('t3'=>'emp_education_details'),
						't2.emp_education_details_id = t3.id')
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->employeeDetailPrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find award details related to the employee
	 */
	
	public function findAwardDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'emp_education'), 
						't1.id = t2.employee_details_id')
				->join(array('t3'=>'emp_education_details'),
						't2.emp_education_details_id = t3.id')
				->where('t1.id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->employeeDetailPrototype);
				$resultSet->buffer();
				return $resultSet->initialize($result); 
		}
		
		return array();
	}
	
	public function getExtraCurricularDetails($tableName, $organisation_id, $self_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_awards'){
			$select->from(array('t1' => $tableName))
				->join(array('t2' => 'employee_details'), 
									't1.employee_details_id = t2.id', array('emp_id','first_name','middle_name','last_name'))
				->join(array('t3' => 'emp_award_category'),
					't3.id = t1.award_category_id', array('award_category'))
				->where('t2.organisation_id = ' .$organisation_id)
				->where('t2.id = ' .$self_id);
		}
		else if($tableName == 'emp_community_services'){
			$select->from(array('t1' => $tableName))
				->join(array('t2' => 'employee_details'), 
									't1.employee_details_id = t2.id', array('emp_id','first_name','middle_name','last_name'))
				->join(array('t3' => 'emp_community_service_category'),
					't3.id = t1.community_service_category_id', array('community_service_category'))
				->where('t2.organisation_id = ' .$organisation_id)
				->where('t2.id = ' .$self_id);
		}
		else if($tableName == 'emp_contributions'){
			$select->from(array('t1' => $tableName))
				->join(array('t2' => 'employee_details'), 
									't1.employee_details_id = t2.id', array('emp_id','first_name','middle_name','last_name'))
				->join(array('t3' => 'emp_contribution_category'),
					't3.id = t1.contribution_category_id', array('contribution_category'))
				->where('t2.organisation_id = ' .$organisation_id)
				->where('t2.id = ' .$self_id);
		}
		else if($tableName == 'emp_responsibilities'){
			$select->from(array('t1' => $tableName))
				->join(array('t2' => 'employee_details'), 
									't1.employee_details_id = t2.id', array('emp_id','first_name','middle_name','last_name'))
				->join(array('t3' => 'emp_responsibility_category'),
					't3.id = t1.responsibility_category_id', array('responsibility_category'))
				->where('t2.organisation_id = ' .$organisation_id)
				->where('t2.id = ' .$self_id);
		}
		else{
			$select->from(array('t1' => $tableName))
				->join(array('t2' => 'employee_details'), 
									't1.employee_details_id = t2.id', array('emp_id','first_name','middle_name','last_name'))
				->where('t2.organisation_id = ' .$organisation_id);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getEmployeeExtraDetail($id, $tableName, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

        if($tableName == 'emp_relation_details' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_employment_record' && $type == 'NON-RUB'){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id, 't1.working_agency_type' => $type));
        }

        if($tableName == 'emp_employment_record' && $type == 'RUB'){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id, 't1.working_agency_type' => $type));
        }

        if($tableName == 'emp_education_details' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_previous_trainings' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_previous_research' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

         if($tableName == 'emp_responsibilities' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_contributions' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_awards' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        if($tableName == 'emp_community_services' && $type == NULL){
        	$select->from(array('t1' => $tableName))
        		   ->join(array('t2' => 'employee_details'),
        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
        		   ->where(array('t1.id = ? ' => $id));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->employeeDetailPrototype);
		}
		throw new \InvalidArgumentException("Employee Extra Detail with given ID: ($id) not found");
	}

	
	public function saveNewEmployee(NewEmployeeDetail $employeeObject, $update_by_employee_id)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$pay_scale_id = $this->getPayScaleId($employeeData['emp_Position_Level']);
		$basic_pay = $this->getBasicPay($employeeData['emp_Position_Level']);
		$increment = $this->getPayIncrement($employeeData['emp_Position_Level']);
		//$university_allowance = $this->getUniversityAllowance($employeeData['emp_Position_Level']);
		$rent_allowance = $this->getHousingAllowance($employeeData['emp_Position_Level']);
		$teaching_allowance = $this->getTeachingAllowance($employeeData['emp_Position_Level'], $employeeData['occupational_Group']);

		if($employeeData['emp_Type'] == 3){
			//$basic_pay = str_replace(',', '', $basic_pay);
			$fixed_term_allowance = ($basic_pay*30)/100;
			//$fixed_term_allowance = number_format($fixed_term_allowance, 2, '.', ',');
		}else{
			$fixed_term_allowance = 0;
		}

		$university_allowance = ($basic_pay*15)/100;
		
		//generate emp id and assign it
		//$employeeData['emp_Id'] = $this->generateEmployeeId();	
		$organisation_id = $employeeData['organisation_Id'];
		$department_name = $employeeData['departments_Id'];
		$unit_name = $employeeData['departments_Units_Id'];
		//$emp_type = $employeeData['emp_Type'];
		$occupational_group = $employeeData['occupational_Group'];
		$emp_Category = $employeeData['emp_Category'];
		unset($employeeData['emp_Category']);
		$emp_position_title = $employeeData['emp_Position_Title'];
		unset($employeeData['emp_Position_Title']);
		$emp_position_level = $employeeData['emp_Position_Level'];
		unset($employeeData['emp_Position_Level']);
		unset($employeeData['occupational_Group']);

		//get the id of the employee gewog and village
		$employeeData['emp_Gewog'] = $employeeData['emp_Gewog'];
		$employeeData['emp_Village'] = $employeeData['emp_Village'];

		//get the id of the departments and units
		$employeeData['departments_Id'] = $employeeData['departments_Id'];
		$employeeData['departments_Units_Id'] = $employeeData['departments_Units_Id'];
		//$emp_position_title = $this->getAjaxDataId($tableName = 'position_title', $emp_position_title, NULL);
		//$emp_position_level = $this->getAjaxDataId($tableName = 'position_level', $emp_position_level, NULL);

		$employeeData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($employeeData['date_Of_Birth'],0,10)));
		$employeeData['recruitment_Date'] = date("Y-m-d", strtotime(substr($employeeData['recruitment_Date'],0,10)));

		//To insert into the employee_details table
		$action = new Insert('employee_details');
		$action->values($employeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			//need to insert the employee position level and title
			$this->addNewEmployeePositionTitle($newId, $emp_position_title, $employeeData['recruitment_Date']);
			$this->addNewEmployeePositionLevel($newId, $emp_position_level, $employeeData['recruitment_Date']);
			$this->addNewEmployeeLeaveBalance($newId, $update_by_employee_id);
			$this->addNewEmployeePayDetails($newId, $pay_scale_id, $basic_pay, $increment, $university_allowance, $rent_allowance, $teaching_allowance, $fixed_term_allowance);
			//add employee to system user
			$this->addNewUser($employeeData['emp_Id'], $employeeData['cid'], $employeeData['organisation_Id'], $occupational_group);

			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function saveNewEmployeeDetails(NewEmployee $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		
		//generate emp id and assign it
		//$employeeData['emp_Id'] = $this->generateEmployeeId();	
		$organisation_id = $employeeData['organisation_Id'];
		$department_name = $employeeData['departments_Id'];
		$unit_name = $employeeData['departments_Units_Id'];
		//$emp_type = $employeeData['emp_Type'];
		$occupational_group = $employeeData['occupational_Group'];
		$emp_Category = $employeeData['emp_Category'];
		unset($employeeData['emp_Category']);
		unset($employeeData['occupational_Group']);
		unset($employeeData['emp_Id']); 

		//get the id of the employee gewog and village
		$employeeData['emp_Gewog'] = $employeeData['emp_Gewog'];
		$employeeData['emp_Village'] = $employeeData['emp_Village'];

		//get the id of the departments and units
		$employeeData['departments_Id'] = $employeeData['departments_Id'];
		$employeeData['departments_Units_Id'] = $employeeData['departments_Units_Id'];
		//$emp_position_title = $this->getAjaxDataId($tableName = 'position_title', $emp_position_title, NULL);
		//$emp_position_level = $this->getAjaxDataId($tableName = 'position_level', $emp_position_level, NULL);

		$employeeData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($employeeData['date_Of_Birth'],0,10)));
		$employeeData['recruitment_Date'] = date("Y-m-d", strtotime(substr($employeeData['recruitment_Date'],0,10)));

		$newEmpDocData = array();
		$announcement_doc = $employeeData['announcement_Doc'];
		$newEmpDocData['announcement_Doc'] = $announcement_doc['tmp_name'];

		$shortlist_doc = $employeeData['shortlist_Doc'];
		$newEmpDocData['shortlist_Doc'] = $shortlist_doc['tmp_name'];

		$selection_doc = $employeeData['selection_Doc'];
		$newEmpDocData['selection_Doc'] = $selection_doc['tmp_name'];

		$minutes_doc = $employeeData['minutes_Doc'];
		$newEmpDocData['minutes_Doc'] = $minutes_doc['tmp_name'];

		$emp_application_form_doc = $employeeData['emp_Application_Form_Doc'];
		$newEmpDocData['emp_Application_Form_Doc'] = $emp_application_form_doc['tmp_name'];

		$emp_academic_transcript_doc = $employeeData['emp_Academic_Transcript_Doc'];
		$newEmpDocData['emp_Academic_Transcript_Doc'] = $emp_academic_transcript_doc['tmp_name'];

		$emp_training_doc = $employeeData['emp_Training_Doc'];
		$newEmpDocData['emp_Training_Doc'] = $emp_training_doc['tmp_name'];

		$emp_cid_wp_doc = $employeeData['emp_Cid_Wp_Doc'];
		$newEmpDocData['emp_Cid_Wp_Doc'] = $emp_cid_wp_doc['tmp_name'];

		$emp_security_cl_doc = $employeeData['emp_Security_Cl_Doc'];
		$newEmpDocData['emp_Security_Cl_Doc'] = $emp_security_cl_doc['tmp_name'];

		$emp_medical_doc = $employeeData['emp_Medical_Doc'];
		$newEmpDocData['emp_Medical_Doc'] = $emp_medical_doc['tmp_name'];

		$emp_no_objec_doc = $employeeData['emp_No_Objec_Doc'];
		$newEmpDocData['emp_No_Objec_Doc'] = $emp_no_objec_doc['tmp_name'];

		$appointment_order_doc = $employeeData['appointment_Order_Doc'];
		$newEmpDocData['appointment_Order_Doc'] = $appointment_order_doc['tmp_name'];

		$others_doc = $employeeData['others_Doc'];
		$newEmpDocData['others_Doc'] = $others_doc['tmp_name'];

		unset($employeeData['announcement_Doc']);
		unset($employeeData['shortlist_Doc']);
		unset($employeeData['selection_Doc']);
		unset($employeeData['minutes_Doc']);
		unset($employeeData['emp_Application_Form_Doc']);
		unset($employeeData['emp_Academic_Transcript_Doc']);
		unset($employeeData['emp_Training_Doc']);
		unset($employeeData['emp_Cid_Wp_Doc']);
		unset($employeeData['emp_Security_Cl_Doc']);
		unset($employeeData['emp_Medical_Doc']);
		unset($employeeData['emp_No_Objec_Doc']);
		unset($employeeData['appointment_Order_Doc']);
		unset($employeeData['others_Doc']);
		unset($employeeData['new_Employee_Details_Id']);

		//To insert into the employee_details table
		$action = new Insert('new_employee_details');
		$action->values($employeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}

			$this->addNewEmpDocument($newId, $newEmpDocData);

			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}



	//Function to save the new employee documents
	public function addNewEmpDocument($new_employee_details_id, $newEmpDocData)
	{
		$newEmployeeData = array();
		$newEmployeeData['announcement_Doc'] = $newEmpDocData['announcement_Doc'];
		$newEmployeeData['shortlist_Doc'] = $newEmpDocData['shortlist_Doc'];
		$newEmployeeData['selection_Doc'] = $newEmpDocData['selection_Doc'];
		$newEmployeeData['minutes_Doc'] = $newEmpDocData['minutes_Doc'];
		$newEmployeeData['emp_Application_Form_Doc'] = $newEmpDocData['emp_Application_Form_Doc'];
		$newEmployeeData['emp_Academic_Transcript_Doc'] = $newEmpDocData['emp_Academic_Transcript_Doc'];
		$newEmployeeData['emp_Training_Doc'] = $newEmpDocData['emp_Training_Doc'];
		$newEmployeeData['emp_Cid_Wp_Doc'] = $newEmpDocData['emp_Cid_Wp_Doc'];
		$newEmployeeData['emp_Security_Cl_Doc'] = $newEmpDocData['emp_Security_Cl_Doc'];
		$newEmployeeData['emp_Medical_Doc'] = $newEmpDocData['emp_Medical_Doc'];
		$newEmployeeData['emp_No_Objec_Doc'] = $newEmpDocData['emp_No_Objec_Doc'];
		$newEmployeeData['appointment_Order_Doc'] = $newEmpDocData['appointment_Order_Doc'];
		$newEmployeeData['others_Doc'] = $newEmpDocData['others_Doc'];
		$newEmployeeData['new_Employee_Details_Id'] = $new_employee_details_id;

		//To insert into the employee_details table
		$action = new Insert('new_employee_documents');
		$action->values($newEmployeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
			}

			return;
		}
	}


	public function updateNewEmpDoc(UpdateNewEmpDoc $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		//var_dump($employeeData); die();
		$newEmpDocData = array();
		if($employeeData['announcement_Doc'] == NULL){
			$newEmpDocData['announcement_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'announcement_doc', $employeeData['id']);
		}else{
			$announcement_doc = $employeeData['announcement_Doc'];
		    $newEmpDocData['announcement_Doc'] = $announcement_doc['tmp_name'];
		}

		if($employeeData['shortlist_Doc'] == NULL){
			$newEmpDocData['shortlist_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'shortlist_doc', $employeeData['id']);
		}else{
			$shortlist_doc = $employeeData['shortlist_Doc'];
			$newEmpDocData['shortlist_Doc'] = $shortlist_doc['tmp_name'];
		}
		
		if($employeeData['selection_Doc'] == NULL){
			$newEmpDocData['selection_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'selection_doc', $employeeData['id']);
		}else{
			$selection_doc = $employeeData['selection_Doc'];
			$newEmpDocData['selection_Doc'] = $selection_doc['tmp_name'];
		}

		if($employeeData['minutes_Doc'] == NULL){
			$newEmpDocData['minutes_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'minutes_doc', $employeeData['id']);
		}else{
			$minutes_doc = $employeeData['minutes_Doc'];
			$newEmpDocData['minutes_Doc'] = $minutes_doc['tmp_name'];
		}

		if($employeeData['emp_Application_Form_Doc'] == NULL){
			$newEmpDocData['emp_Application_Form_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_application_form_doc', $employeeData['id']);
		}else{
			$emp_application_form_doc = $employeeData['emp_Application_Form_Doc'];
			$newEmpDocData['emp_Application_Form_Doc'] = $emp_application_form_doc['tmp_name'];
		}

		if($employeeData['emp_Academic_Transcript_Doc'] == NULL){
			$newEmpDocData['emp_Academic_Transcript_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_academic_transcript_doc', $employeeData['id']);
		}else{
			$emp_academic_transcript_doc = $employeeData['emp_Academic_Transcript_Doc'];
			$newEmpDocData['emp_Academic_Transcript_Doc'] = $emp_academic_transcript_doc['tmp_name'];
		}

		if($employeeData['emp_Training_Doc'] == NULL){
			$newEmpDocData['emp_Training_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_training_doc', $employeeData['id']);
		}else{
			$emp_training_doc = $employeeData['emp_Training_Doc'];
			$newEmpDocData['emp_Training_Doc'] = $emp_training_doc['tmp_name'];
		}

		if($employeeData['emp_Cid_Wp_Doc'] == NULL){
			$newEmpDocData['emp_Cid_Wp_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_cid_wp_doc', $employeeData['id']);
		}else{
			$emp_cid_wp_doc = $employeeData['emp_Cid_Wp_Doc'];
			$newEmpDocData['emp_Cid_Wp_Doc'] = $emp_cid_wp_doc['tmp_name'];
		}

		if($employeeData['emp_Security_Cl_Doc'] == NULL){
			$newEmpDocData['emp_Security_Cl_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_security_cl_doc', $employeeData['id']);
		}else{
			$emp_security_cl_doc = $employeeData['emp_Security_Cl_Doc'];
			$newEmpDocData['emp_Security_Cl_Doc'] = $emp_security_cl_doc['tmp_name'];
		}

		if($employeeData['emp_Medical_Doc'] == NULL){
			$newEmpDocData['emp_Medical_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_medical_doc', $employeeData['id']);
		}else{
			$emp_medical_doc = $employeeData['emp_Medical_Doc'];
			$newEmpDocData['emp_Medical_Doc'] = $emp_medical_doc['tmp_name'];
		}

		if($employeeData['emp_No_Objec_Doc'] == NULL){
			$newEmpDocData['emp_No_Objec_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'emp_no_objec_doc', $employeeData['id']);
		}else{
			$emp_no_objec_doc = $employeeData['emp_No_Objec_Doc'];
			$newEmpDocData['emp_No_Objec_Doc'] = $emp_no_objec_doc['tmp_name'];
		}

		if($employeeData['appointment_Order_Doc'] == NULL){
			$newEmpDocData['appointment_Order_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'appointment_order_doc', $employeeData['id']);
		}else{
			$appointment_order_doc = $employeeData['appointment_Order_Doc'];
			$newEmpDocData['appointment_Order_Doc'] = $appointment_order_doc['tmp_name'];
		}

		$newEmpDocData['new_Employee_Details_Id'] = $employeeData['new_Employee_Details_Id'];


		if($employeeData['others_Doc'] == NULL){
			$newEmpDocData['others_Doc'] = $this->getUploadedFileLink($tableName = 'new_employee_documents', $columnName = 'others_doc', $employeeData['id']);
		}else{
			$others_doc = $employeeData['others_Doc'];
			$newEmpDocData['others_Doc'] = $others_doc['tmp_name'];
		}

		//To Update into the new employee documents table
		$action = new Update('new_employee_documents');
		$action->set($newEmpDocData);
		$action->where(array('id = ?' => $employeeData['id']));
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}

	public function uploadNewEmployeeOrder(NewEmployee $employeeObject, $update_by_employee_id){
		$employeeData = $this->hydrator->extract($employeeObject);
		//unset($employeeData['id']);
		
		//generate emp id and assign it
		//$employeeData['emp_Id'] = $this->generateEmployeeId();	
		//$organisation_id = $employeeData['organisation_Id'];
		//$department_name = $employeeData['departments_Id'];
		//$unit_name = $employeeData['departments_Units_Id'];
		//$emp_type = $employeeData['emp_Type'];
		//$occupational_group = $employeeData['occupational_Group'];
		//$emp_Category = $employeeData['emp_Category'];
		unset($employeeData['emp_Category']);
		unset($employeeData['occupational_Group']);
		//unset($employeeData['emp_Id']);

		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		//var_dump($employeeData['evidence_File']); die;

		 $employeeData['office_Order_Date'] = date("Y-m-d", strtotime(substr($employeeData['office_Order_Date'],0,10))); 
		
		//To insert into the employee_details table
		$action = new Update('new_employee_details');
		$action->set(array('office_order_no' => $employeeData['office_Order_No'], 'office_order_date' => $employeeData['office_Order_Date'], 'evidence_file' => $employeeData['evidence_File']));
		$action->where(array('id = ?' => $employeeData['id']));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function updateNewEmployee(NewEmployee $employeeObject, $update_by_employee_id)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		//unset($employeeData['id']);
		
		//generate emp id and assign it
		//$employeeData['emp_Id'] = $this->generateEmployeeId();	
		$organisation_id = $employeeData['organisation_Id'];
		$department_name = $employeeData['departments_Id'];
		$unit_name = $employeeData['departments_Units_Id'];
		//$emp_type = $employeeData['emp_Type'];
		$occupational_group = $employeeData['occupational_Group'];
		$emp_Category = $employeeData['emp_Category'];
		unset($employeeData['emp_Category']);
		unset($employeeData['occupational_Group']);
		//unset($employeeData['emp_Id']);

		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];

		 $employeeData['office_Order_Date'] = date("Y-m-d", strtotime(substr($employeeData['office_Order_Date'],0,10))); 
		
		//To insert into the employee_details table
		$action = new Update('new_employee_details');
		$action->set(array('office_order_no' => $employeeData['office_Order_No'], 'office_order_date' => $employeeData['office_Order_Date'], 'evidence_file' => $employeeData['evidence_File'], 'status' => $employeeData['status']));
		$action->where(array('id = ?' => $employeeData['id']));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$newEmployeeData = array();
		$newEmployeeData['emp_Id'] = $employeeData['emp_Id'];
		$newEmployeeData['first_Name'] = $employeeData['first_Name'];
		$newEmployeeData['middle_Name'] = $employeeData['middle_Name'];
		$newEmployeeData['last_Name'] = $employeeData['last_Name'];
		$newEmployeeData['cid'] = $employeeData['cid'];
		$newEmployeeData['nationality'] = $employeeData['nationality'];
		$newEmployeeData['date_Of_Birth'] = $employeeData['date_Of_Birth'];
		$newEmployeeData['emp_House_No'] = $employeeData['emp_House_No'];
		$newEmployeeData['emp_Thram_No'] = $employeeData['emp_Thram_No'];
		$newEmployeeData['emp_Dzongkhag'] = $employeeData['emp_Dzongkhag'];
		$newEmployeeData['emp_Gewog'] = $employeeData['emp_Gewog'];
		$newEmployeeData['emp_Village'] = $employeeData['emp_Village'];
		$newEmployeeData['country'] = $employeeData['country'];
		$newEmployeeData['recruitment_Date'] = $employeeData['recruitment_Date'];
		$newEmployeeData['emp_Type'] = $employeeData['emp_Type'];
		$newEmployeeData['gender'] = $employeeData['gender'];
		$newEmployeeData['marital_Status'] = $employeeData['marital_Status'];
		$newEmployeeData['phone_No'] = $employeeData['phone_No'];
		$newEmployeeData['email'] = $employeeData['email'];
		$newEmployeeData['blood_Group'] = $employeeData['blood_Group'];
		$newEmployeeData['religion'] = $employeeData['religion'];
		$newEmployeeData['organisation_Id'] = $employeeData['organisation_Id'];
		$newEmployeeData['departments_Id'] = $employeeData['departments_Id'];
		$newEmployeeData['departments_Units_Id'] = $employeeData['departments_Units_Id'];
		$newEmployeeData['position_Title_Id'] = $employeeData['position_Title_Id'];
		$newEmployeeData['position_Level_Id'] = $employeeData['position_Level_Id'];
		$newEmployeeData['emp_Resignation_Id'] = $employeeData['emp_Resignation_Id'];

		$this->updateNewEmployeeDetails($newEmployeeData, $update_by_employee_id);
	}



	/*
	*Function to insert employee data into employee_details table
	**/
	public function updateNewEmployeeDetails($newEmployeeData, $update_by_employee_id)
	{ 
		$pay_scale_id = $this->getPayScaleId($newEmployeeData['position_Level_Id']);
		$basic_pay = $this->getBasicPay($newEmployeeData['position_Level_Id']);
		$increment = $this->getPayIncrement($newEmployeeData['position_Level_Id']);
		//$university_allowance = $this->getUniversityAllowance($employeeData['emp_Position_Level']);
		$rent_allowance = $this->getHousingAllowance($newEmployeeData['position_Level_Id']);
		$occupational_group = $this->getOccupationalGroup($newEmployeeData['position_Level_Id']);
		$teaching_allowance = $this->getTeachingAllowance($newEmployeeData['position_Level_Id'], $occupational_group);

		if($newEmployeeData['emp_Type'] == 3){
			//$basic_pay = str_replace(',', '', $basic_pay);
			$fixed_term_allowance = ($basic_pay*30)/100;
			//$fixed_term_allowance = number_format($fixed_term_allowance, 2, '.', ',');
		}else{
			$fixed_term_allowance = 0;
		}

		$university_allowance = ($basic_pay*15)/100;
		
		$emp_position_title = $newEmployeeData['position_Title_Id'];
		unset($newEmployeeData['position_Title_Id']);
		$emp_position_level = $newEmployeeData['position_Level_Id'];
		unset($newEmployeeData['position_Level_Id']);

		$employeeData = array();
		$employeeData['emp_Id'] = $newEmployeeData['emp_Id'];
		$employeeData['first_Name'] = $newEmployeeData['first_Name'];
		$employeeData['middle_Name'] = $newEmployeeData['middle_Name'];
		$employeeData['last_Name'] = $newEmployeeData['last_Name'];
		$employeeData['cid'] = $newEmployeeData['cid'];
		$employeeData['nationality'] = $newEmployeeData['nationality'];
		$employeeData['date_Of_Birth'] = $newEmployeeData['date_Of_Birth'];
		$employeeData['emp_House_No'] = $newEmployeeData['emp_House_No'];
		$employeeData['emp_Thram_No'] = $newEmployeeData['emp_Thram_No'];
		$employeeData['emp_Dzongkhag'] = $newEmployeeData['emp_Dzongkhag'];
		$employeeData['emp_Gewog'] = $newEmployeeData['emp_Gewog'];
		$employeeData['emp_Village'] = $newEmployeeData['emp_Village'];
		$employeeData['country'] = $newEmployeeData['country'];
		$employeeData['recruitment_Date'] = $newEmployeeData['recruitment_Date'];
		$employeeData['emp_Type'] = $newEmployeeData['emp_Type'];
		$employeeData['gender'] = $newEmployeeData['gender'];
		$employeeData['marital_Status'] = $newEmployeeData['marital_Status'];
		$employeeData['phone_No'] = $newEmployeeData['phone_No'];
		$employeeData['email'] = $newEmployeeData['email'];
		$employeeData['blood_Group'] = $newEmployeeData['blood_Group'];
		$employeeData['religion'] = $newEmployeeData['religion'];
		$employeeData['organisation_Id'] = $newEmployeeData['organisation_Id'];
		$employeeData['departments_Id'] = $newEmployeeData['departments_Id'];
		$employeeData['departments_Units_Id'] = $newEmployeeData['departments_Units_Id'];
		$employeeData['emp_Resignation_Id'] = $newEmployeeData['emp_Resignation_Id'];

		//To insert into the employee_details table
		$action = new Insert('employee_details');
		$action->values($employeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
			}
			//need to insert the employee position level and title
			$this->addNewEmployeePositionTitle($newId, $emp_position_title, $employeeData['recruitment_Date']);
			$this->addNewEmployeePositionLevel($newId, $emp_position_level, $employeeData['recruitment_Date']);
			$this->addNewEmployeeLeaveBalance($newId, $update_by_employee_id);
			$this->addNewEmployeePayDetails($newId, $pay_scale_id, $basic_pay, $increment, $university_allowance, $rent_allowance, $teaching_allowance, $fixed_term_allowance);
			//add employee to system user
			$this->addNewUser($employeeData['emp_Id'], $employeeData['cid'], $employeeData['organisation_Id'], $occupational_group);

			return;
		}
	}


	public function getPayScaleId($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pay_scale')) 
			   ->where(array('t1.position_level' => $position_level));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$payScaleId = NULL;
		foreach($resultSet as $set){
			$payScaleId = $set['id'];
		}
		
		return $payScaleId;
	}


	public function getBasicPay($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pay_scale')) 
			   ->where(array('t1.position_level' => $position_level));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$basicPay = NULL;
		foreach($resultSet as $set){
			$basicPay = $set['minimum_pay_scale'];
		}
		
		return $basicPay;
	}


	public function getPayIncrement($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'pay_scale')) 
			   ->where(array('t1.position_level' => $position_level));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$increment = NULL;
		foreach($resultSet as $set){
			$increment = $set['increment'];
		}
		
		return $increment;
	}


	public function getUniversityAllowance($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'university_allowance')) 
			   ->where(array('t1.position_level' => $position_level));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$university_allowance = NULL;
		foreach($resultSet as $set){
			$university_allowance = $set['university_allowance'];
		}
		
		return $university_allowance;
	}


	public function getHousingAllowance($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'housing_allowance')) 
			   ->where(array('t1.position_level' => $position_level));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$rent_allowance = NULL;
		foreach($resultSet as $set){
			$rent_allowance = $set['rent_allowance'];
		}
		
		return $rent_allowance;
	}


	public function getTeachingAllowance($position_level, $occupational_group)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'teaching_allowance')) 
			   ->where(array('t1.position_level' => $position_level))
			   ->order(array('t1.id ASC'))
			   ->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$teaching_allowance = NULL;

		if($occupational_group == 1){
			foreach($resultSet as $set){
				$teaching_allowance = $set['teaching_allowance'];
			}
		}else{
			$teaching_allowance = 0;
		}
		
		return $teaching_allowance;
	}

	public function updateEmpPayDetails(EmployeePayDetails $employeeObject)
	{	
        $empPayData = $this->hydrator->extract($employeeObject);
		$employee_details_id = $empPayData['employee_Details_Id'];
        unset($empPayData['id']);

        $action = new Update('emp_pay_details');
        $action->set($empPayData);
        $action->where(array('employee_details_id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmployeeDetails(EmployeeDetail $employeeObject, $dzongkhag, $gewog, $village)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		unset($employeeData['maritial_Status']);
		unset($employeeData['college_Name']);
		unset($employeeData['college_Location']);
		unset($employeeData['college_Country']);
		unset($employeeData['field_Study']);
		unset($employeeData['subject_Studied']);
		unset($employeeData['completion_Year']);
		unset($employeeData['result_Obtained']);
		unset($employeeData['certificate_Obtained']);
		unset($employeeData['position_Title_Id']);
		unset($employeeData['position_Level_Id']);
		unset($employeeData['evidence_File']);
		unset($employeeData['relation_Type']);
		unset($employeeData['name']);
		unset($employeeData['occupation']);
		unset($employeeData['remarks']);
		unset($employeeData['employee_Details_Id']);
		unset($employeeData['working_Agency']);
		unset($employeeData['occupational_Group']);
		unset($employeeData['position_Level']);
		unset($employeeData['position_Title']);
		unset($employeeData['position_Category']);
		unset($employeeData['start_Period']);
		unset($employeeData['end_Period']);
		unset($employeeData['date_Range']);
		unset($employeeData['working_Agency_Type']);
		unset($employeeData['office_Order_No']);
		unset($employeeData['office_Order_Date']);
		unset($employeeData['study_Mode']);
		unset($employeeData['study_Level']);
		unset($employeeData['start_Date']);
		unset($employeeData['end_Date']);
		unset($employeeData['funding']);
		unset($employeeData['marks_Obtained']);
		unset($employeeData['course_Title']);
		unset($employeeData['institute_Name']);
		unset($employeeData['institute_Address']);
		unset($employeeData['from_Date']);
		unset($employeeData['to_Date']);
		unset($employeeData['publication_Year']);
		unset($employeeData['publication_Name']);
		unset($employeeData['research_Type']);
		unset($employeeData['publisher']);
		unset($employeeData['publication_Url']);
		unset($employeeData['publication_No']);
		unset($employeeData['author_Level']);
		unset($employeeData['responsibility_Category_Id']);
		unset($employeeData['responsibility_Name']);
		unset($employeeData['contribution_Category_Id']);
		unset($employeeData['contribution_Date']);
		unset($employeeData['contribution_Type']);
		unset($employeeData['award_Category_Id']);
		unset($employeeData['award_Name']);
		unset($employeeData['award_Date']);
		unset($employeeData['award_Reasons']);
		unset($employeeData['award_Given_by']);
		unset($employeeData['community_Service_Category_Id']);
		unset($employeeData['service_Name']);
		unset($employeeData['service_Date']);



		//get the id of the employee gewog and village
		$employeeData['emp_Dzongkhag'] = $dzongkhag;
		$employeeData['emp_Gewog'] = $gewog;
		$employeeData['emp_Village'] = $village;

        if($employeeObject->getId()){
        	//ID present, so it is an update
            $action = new Update('employee_details');
            $action->set($employeeData);
            $action->where(array('id = ?' => $employeeObject->getId()));
        }else{
        	 //ID is not present, so its an insert
            $action = new Insert('employee_details');
            $action->values($employeeData);
        }

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}

			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmployeePersonalDetails(EmployeeDetail $employeeObject, $previous_emp_id)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		unset($employeeData['maritial_Status']);
		unset($employeeData['college_Name']);
		unset($employeeData['college_Location']);
		unset($employeeData['college_Country']);
		unset($employeeData['field_Study']);
		unset($employeeData['subject_Studied']);
		unset($employeeData['completion_Year']);
		unset($employeeData['result_Obtained']);
		unset($employeeData['certificate_Obtained']);
		unset($employeeData['position_Title_Id']);
		unset($employeeData['position_Level_Id']);
		unset($employeeData['evidence_File']);
		unset($employeeData['relation_Type']);
		unset($employeeData['name']);
		unset($employeeData['occupation']);
		unset($employeeData['remarks']);
		unset($employeeData['employee_Details_Id']);
		unset($employeeData['working_Agency']);
		unset($employeeData['occupational_Group']);
		unset($employeeData['position_Level']);
		unset($employeeData['position_Title']);
		unset($employeeData['position_Category']);
		unset($employeeData['start_Period']);
		unset($employeeData['end_Period']);
		unset($employeeData['date_Range']);
		unset($employeeData['working_Agency_Type']);
		unset($employeeData['office_Order_No']);
		unset($employeeData['office_Order_Date']);
		unset($employeeData['study_Mode']);
		unset($employeeData['study_Level']);
		unset($employeeData['start_Date']);
		unset($employeeData['end_Date']);
		unset($employeeData['funding']);
		unset($employeeData['marks_Obtained']);
		unset($employeeData['course_Title']);
		unset($employeeData['institute_Name']);
		unset($employeeData['institute_Address']);
		unset($employeeData['from_Date']);
		unset($employeeData['to_Date']);
		unset($employeeData['publication_Year']);
		unset($employeeData['publication_Name']);
		unset($employeeData['research_Type']);
		unset($employeeData['publisher']);
		unset($employeeData['publication_Url']);
		unset($employeeData['publication_No']);
		unset($employeeData['author_Level']);
		unset($employeeData['responsibility_Category_Id']);
		unset($employeeData['responsibility_Name']);
		unset($employeeData['contribution_Category_Id']);
		unset($employeeData['contribution_Date']);
		unset($employeeData['contribution_Type']);
		unset($employeeData['award_Category_Id']);
		unset($employeeData['award_Name']);
		unset($employeeData['award_Date']);
		unset($employeeData['award_Reasons']);
		unset($employeeData['award_Given_by']);
		unset($employeeData['community_Service_Category_Id']);
		unset($employeeData['service_Name']);
		unset($employeeData['service_Date']);

		$employeeData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($employeeData['date_Of_Birth'],0,10)));
		$employeeData['recruitment_Date'] = date("Y-m-d", strtotime(substr($employeeData['recruitment_Date'],0,10)));
		//echo $previous_emp_id;
		//var_dump($employeeData); die();

        if($employeeObject->getId()){
        	//ID present, so it is an update
            $action = new Update('employee_details');
            $action->set($employeeData);
            $action->where(array('id = ?' => $employeeObject->getId()));
        }else{
        	 //ID is not present, so its an insert
            $action = new Insert('employee_details');
            $action->values($employeeData);
        }

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}

			$this->updateUsername($employeeData['emp_Id'], $previous_emp_id);

			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateUsername($new_user_id, $old_user_id)
	{
		$action = new Update('users');
		$action->set(array('username' => $new_user_id));
		$action->where(array('username = ?' => $old_user_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}

	
	public function saveNewEmployeeRelation($employee_details_id, $data)
	{		
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('emp_relation_details');
				$action->values(array(
					'relation_type'=> $value['relation_type'],
					'name' => $value['relation_name'],
					'cid' => $value['relation_cid'],
					'nationality' => $value['relation_nationality'],
					'house_no' => $value['relation_house_no'],
					'thram_no' => $value['relation_thram_no'],
					'village' => $value['relation_village'],
					'gewog' => $value['relation_gewog'],
					'dzongkhag' => $value['relation_dzongkhag'],
					'occupation' => $value['relation_occupation'],
					'employee_details_id' => $employee_details_id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
	
	public function saveNewEmployeeEducation($employee_details_id, $data)
	{
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('emp_education_details');
				$action->values(array(
					'college_name'=> $value['relation_type'],
					'college_location' => $value['relation_name'],
					'college_country' => $value['relation_cid'],
					'field_study' => $value['relation_nationality'],
					'subject_studied' => $value['relation_house_no'],
					'completion_year' => $value['relation_thram_no'],
					'result_obtained' => $value['relation_village'],
					'certificate_obtained' => $value['relation_gewog'],
					'employee_details_id' => $employee_details_id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
	
	public function saveNewEmployeeTraining($employee_details_id, $data)
	{
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('emp_previous_trainings');
				$action->values(array(
					'course_title' => $value['course_title'],
					'institute_name' => $value['institute_name'],
					'institute_address' => $value['institute_location'],
					'date' => $value['training_start_date'],
					'course_level' => $value['course_level'],
					'employee_details_id' => $employee_details_id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
	
	public function saveNewEmployeeEmployment($employee_details_id, $data)
	{
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('emp_employment_record');
				$action->values(array(
					'employer' => $value['employer'],
					'start_date' => $value['start_period'],
					'end_date' => $value['end_period'],
					'remarks' => $value['remarks'],
					'employee_details_id' => $employee_details_id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
	
	public function saveNewEmployeeResearch($employee_details_id, $data)
	{
		if($data != NULL)
		{
			foreach($data as $value)
			{
				$action = new Insert('emp_previous_research');
				$action->values(array(
					'publication_name' => $value['publication_name'],
					'research_type' => $value['research_type'],
					'submission_date' => $value['submission_date'],
					'remarks' => $value['publication_remarks'],
					'employee_details_id' => $employee_details_id,
				));
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
	
	public function saveNewEmployeeDocuments(NewEmployeeDocuments $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employee_details_id = $employeeData['employee_Details_Id'];
		$emp = $this->getEmployeeDetailsToAdd($employee_details_id);
		
		foreach($emp as $temp_data){
			$emp_id = $temp_data['emp_id'];
			$emp_dob = $temp_data['date_of_birth'];
		}
		//$this->addNewUser($emp_id, $emp_dob);
				
		$photo = $employeeData['passport_Photo'];
		$employeeData['passport_Photo'] = $photo['tmp_name'];
		
		$identity = $employeeData['identity_Proof'];
		$employeeData['identity_Proof'] = $identity['tmp_name'];
		
		$security = $employeeData['security_Clearance_File'];
		$employeeData['security_Clearance_File'] = $security['tmp_name'];
		
		$medical = $employeeData['medical_Clearance_File'];
		$employeeData['medical_Clearance_File'] = $medical['tmp_name'];
		
		$other = $employeeData['other_Certificate_File'];
		$employeeData['other_Certificate_File'] = $other['tmp_name'];

		$action = new Insert('emp_documents');
		$action->values($employeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function saveEmployeeTitle(EmployeeTitle $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_position_title');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_position_title');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function saveEmployeeProfilePicture(EmployeeProfilePicture $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		$employee_details_id = $employeeData['employee_Details_Id'];
                unset($employeeData['id']);
                unset($employeeData['employee_Details_Id']);
					
		$photo = $employeeData['profile_Picture'];
		$employeeData['profile_Picture'] = $photo['tmp_name'];
                
                $action = new Update('employee_details');
                $action->set($employeeData);
                $action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
        public function saveEmployeePersonalDetails(EmployeePersonalDetails $employeeModel)
        {
                $employeeData = $this->hydrator->extract($employeeObject);
		$employee_details_id = $employeeData['employee_Details_Id'];
                unset($employeeData['id']);
                unset($employeeData['employee_Details_Id']);
                
                $action = new Update('employee_details');
                $action->set($employeeData);
                $action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
            
        }
        
        public function saveEmployeePermanentAddress(EmployeePermanentAddress $employeeModel)
        {
                $employeeData = $this->hydrator->extract($employeeObject);
		$employee_details_id = $employeeData['employee_Details_Id'];
                unset($employeeData['id']);
                unset($employeeData['employee_Details_Id']);
                
                $action = new Update('employee_details');
                $action->set($employeeData);
                $action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
            
        }
        
	public function findEmployeeTitleDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_title'))
				->join(array('t2' => 'position_title'), 
						't1.position_title_id = t2.id', array('description'))
				->where('t1.employee_details_id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function saveEmployeeLevel(EmployeeLevel $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		$position_level_id = $this->getPositionLevelOccupationalGroup($employeeData['employee_Details_Id'], $employeeData['position_Level_Id']);
		
		//reset the position level id
		$employeeData['position_Level_Id'] = $position_level_id;
				
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_position_level');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_position_level');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function findEmployeeLevelDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_level'))
				->join(array('t2' => 'position_level'), 
						't1.position_level_id = t2.id', array('position_level'))
				->where('t1.employee_details_id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function saveEmployeeRelation(EmployeeRelationDetail $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_relation_details');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_relation_details');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveEmpJobProfile($data)
	{  
		//If id present then update
		if($data['id']){ 
			$action = new Update('job_profile');
			$action->set(array(
				'employee_details' => $data['employee_details'],
				'emp_type_id' => $data['emp_type_id'],
				'departments_id' => $data['departments_id'],
				'departments_units_id' => $data['departments_units_id'],
				'major_occupational_group_id' => $data['major_occupational_group_id'],
				'organisation_id' => $data['organisation_id'],
				'emp_category_id' => $data['emp_category_id'],
				'position_title_id' => $data['position_title_id'],
				'position_level_id' => $data['position_level_id'],
				'increment_type_id' => $data['increment_type_id'],
				'pay_scale' => $data['pay_scale'],
				'status' => $data['status'],
				'reason' => $data['reason'],
				'author' => $data['author'],
				'created' => $data['created'],
				'modified' => date('Y-m-d H:i:s')
			));
			$action->where(array('id = ?' => $data['id']));
		}
		else{ 
			$action = new Insert('job_profile');
			$action->values(array(
				'employee_details' => $data['employee_details'],
				'emp_type_id' => $data['emp_type_id'],
				'departments_id' => $data['departments_id'],
				'departments_units_id' => $data['departments_units_id'],
				'major_occupational_group_id' => $data['major_occupational_group_id'],
				'organisation_id' => $data['organisation_id'],
				'emp_category_id' => $data['emp_category_id'],
				'position_title_id' => $data['position_title_id'],
				'position_level_id' => $data['position_level_id'],
				'increment_type_id' => $data['increment_type_id'],
				'pay_scale' => $data['pay_scale'],
				'status' => $data['status'],
				'reason' => $data['reason'],
				'author' => $data['author'],
				'created' => date('Y-m-d H:i:s'),
				'modified' => date('Y-m-d H:i:s')
			));
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	


	public function deleteEmployeeRelationDetail($id)
	{
		$action = new Delete('emp_relation_details');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	// Roll back the Employee ID provided
	public function rollBackEmployeeId($id)
	{
		$cid = null;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from ('new_employee_details');

		$select->columns(['cid']);
		$select->where('id = '.$id);
		$stmt = $sql->prepareStatementForSqlObject($select);
                $resource  = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($resource);

		foreach ($resultSet as $row)
		{
			$cid = $row->cid;
			if ($cid != null) 
			{
				$action = new Delete('new_employee_details');
                		$action->where(array('id = ?' => $id));

                		$sqlDelete = new Sql($this->dbAdapter);
                		$stmtDelete = $sqlDelete->prepareStatementForSqlObject($action);
				$resultDelete = $stmtDelete->execute();

			}
		}	

		return $cid;   
	}
	public function deleteEmployeeWorkExperience($id)
	{
		$action = new Delete('emp_employment_record');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeeEducation($id)
	{
		$action = new Delete('emp_education_details');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeeTrainingDetail($id)
	{
		$action = new Delete('emp_previous_trainings');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeePublication($id)
	{
		$action = new Delete('emp_previous_research');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeeResponsibility($id)
	{
		$action = new Delete('emp_responsibilities');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}

	public function deleteEmployeeContribution($id)
	{
		$action = new Delete('emp_contributions');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeeAward($id)
	{
		$action = new Delete('emp_awards');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}


	public function deleteEmployeeCommunityService($id)
	{
		$action = new Delete('emp_community_services');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}

	
	public function saveEmployeeWorkExperience(EmployeeWorkExperience $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];

		$employeeData['office_Order_Date'] = date("Y-m-d", strtotime(substr($employeeData['office_Order_Date'],0,10)));

		//get the start and end dates
		$employeeData['start_Period'] = date("Y-m-d", strtotime(substr($employeeData['date_Range'],0,10)));
		$employeeData['end_Period'] = date("Y-m-d", strtotime(substr($employeeData['date_Range'],13,10)));
		unset($employeeData['date_Range']);
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_employment_record');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_employment_record');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}

			/*if($employeeData['working_Agency_Type'] == 'RUB'){
				$this->addNewEmployeePositionTitle($employeeData['employee_Details_Id'], $employeeData['position_Title'], $employeeData['start_Period']);
				$this->addNewEmployeePositionLevel($employeeData['employee_Details_Id'], $employeeData['position_Level'], $employeeData['start_Period']);
			}
			else{
				return;
			}*/
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmployeeWorkExperience(EmployeeWorkExperience $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];

		//get the start and end dates
		$employeeData['start_Period'] = date("Y-m-d", strtotime(substr($employeeData['start_Period'],0,10)));
		$employeeData['end_Period'] = date("Y-m-d", strtotime(substr($employeeData['end_Period'],0,10)));
		unset($employeeData['date_Range']);
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_employment_record');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_employment_record');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveRubEmployeeWorkExperience(EmployeeWorkExperience $employeeObject, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];

		$employeeData['office_Order_Date'] = date("Y-m-d", strtotime(substr($employeeData['office_Order_Date'],0,10)));

		//get the start and end dates
		$employeeData['start_Period'] = date("Y-m-d", strtotime(substr($employeeData['start_Period'],0,10)));
		$employeeData['end_Period'] = date("Y-m-d", strtotime(substr($employeeData['end_Period'],0,10)));
		unset($employeeData['date_Range']);

		$employeeData['occupational_Group'] = $occupationalGroup;
		$employeeData['position_Title'] = $positionTitle;
		$employeeData['position_Level'] = $positionLevel;
		$employeeData['position_Category'] = $positionCategory;
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_employment_record');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_employment_record');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}

			/*if($employeeData['working_Agency_Type'] == 'RUB'){
				$this->addNewEmployeePositionTitle($employeeData['employee_Details_Id'], $employeeData['position_Title'], $employeeData['start_Period']);
				$this->addNewEmployeePositionLevel($employeeData['employee_Details_Id'], $employeeData['position_Level'], $employeeData['start_Period']);
			}
			else{
				return;
			}*/
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}

		
	public function saveEmployeeEducation(EmployeeEducation $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['start_Date'] = date("Y-m-d", strtotime(substr($employeeData['start_Date'],0,10)));
		$employeeData['end_Date'] = date("Y-m-d", strtotime(substr($employeeData['end_Date'],0,10))); 

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_education_details');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_education_details');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
		
	public function saveEmployeeTraining(EmployeeTrainings $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['from_Date'] = date("Y-m-d", strtotime(substr($employeeData['from_Date'], 0,10)));
		$employeeData['to_Date'] = date("Y-m-d", strtotime(substr($employeeData['to_Date'], 0,10)));

		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_previous_trainings');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_previous_trainings');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function findEmployeeTrainingDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_title'))
				->join(array('t2' => 'position_title'), 
						't1.position_title_id = t2.id', array('description'))
				->where('t1.employee_details_id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function saveEmployeePublication(EmployeePublications $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_previous_research');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_previous_research');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function findEmployeePublicationDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_title'))
				->join(array('t2' => 'position_title'), 
						't1.position_title_id = t2.id', array('description'))
				->where('t1.employee_details_id = ' .$id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function saveEmployeeAward(EmployeeAward $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['award_Date'] = date("Y-m-d", strtotime(substr($employeeData['award_Date'],0,10)));

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name']; 
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_awards');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_awards');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	public function saveEmployeeContribution(EmployeeContribution $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['contribution_Date'] = date("Y-m-d", strtotime(substr($employeeData['contribution_Date'], 0,10)));

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_contributions');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_contributions');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function saveEmployeeResponsibility(EmployeeResponsibilities $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['start_Date'] = date("Y-m-d", strtotime(substr($employeeData['start_Date'], 0,10)));
		$employeeData['end_Date'] = date("Y-m-d", strtotime(substr($employeeData['end_Date'], 0,10)));

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_responsibilities');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_responsibilities');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function saveEmployeeCommunityService(EmployeeCommunityService $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$employeeData['service_Date'] = date("Y-m-d", strtotime(substr($employeeData['service_Date'],0,10)));

		//need to get the location of file and store in the database
		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_community_services');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_community_services');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmployeeDepartment($data)
	{
		
		$work_history['working_Agency'] = $data['organisation_id'];
        //$work_history['occupational_Group'] = $this->getOccupationalGroup($work_history['occupational_Group']);

        $empPositionDetails = $this->getEmploymentDetails($data['employee_details_id']);

        $work_history['occupational_Group'] = $empPositionDetails['major_occupational_group_id'];
        $work_history['position_Category'] = $empPositionDetails['position_category_id'];
        $work_history['position_Title'] = $empPositionDetails['position_title_id'];
        $work_history['position_Level'] = $empPositionDetails['position_level_id'];
        $work_history['start_Period'] = $empPositionDetails['date'];
        $work_history['end_Period'] = date("Y-m-d", strtotime(substr($data['order_date'],0,10)));
        $work_history['office_Order_Date'] = $empPositionDetails['date'];
        $work_history['remarks'] = 'Department Changed';
        $work_history['employee_Details_Id'] = $data['employee_details_id'];
        $work_history['working_Agency_Type'] = 'RUB';


		//keep a record of previous position level and position title
		$position_title_id = $data['recommended_position_title']; 
		$position_level_id = $data['recommended_position_level'];
		$employee_details_id = $work_history['employee_Details_Id'];
		$departments_id = $data['new_departments_id'];
		$departments_units_id = $data['new_departments_units_id'];
		$date = $work_history['end_Period'];

			
		$action = new Insert('emp_employment_record');
		$action->values($work_history);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}
			$this->updatePositionDetails('emp_position_level', $date, $position_level_id, $employee_details_id);
			$this->updatePositionDetails('emp_position_title', $date, $position_title_id, $employee_details_id);
			$this->updateEmpDetails($work_history['working_Agency'], $departments_id, $departments_units_id, $employee_details_id);

			return $data;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEditEmpDepartment($data)
	{
		$organisation_id = $data['organisation_id'];
		$departments_id = $data['departments_id'];
		$departments_units_id = $data['departments_units_id'];
		$employee_details_id = $data['employee_details_id']; 

		$action = new Update('employee_details');
		$action->set(array('organisation_id' => $organisation_id, 'departments_id' => $departments_id, 'departments_units_id' => $departments_units_id));
		$action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	}


	public function updateEditedPositionTitleLevel($data)
	{
		$employee_details_id = $data['employee_details_id'];
		$position_title_id = $data['emp_position_title'];
		$position_level_id = $data['emp_position_level'];
		unset($data['occupational_group']);
		unset($data['emp_category']);

		$action = new Update('emp_position_title');
		$action->set(array('position_title_id' => $position_title_id));
		$action->where(array('employee_details_id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->updateEditedEmpPositionLevel($employee_details_id, $position_level_id);
	}


	public function updateEditedEmpPositionLevel($employee_details_id, $position_level_id)
	{
		$action = new Update('emp_position_level');
		$action->set(array('position_level_id' => $position_level_id));
		$action->where(array('employee_details_id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	}



	/*
	* Update the Position Level/Title History of the Employee After Promotion
	*/
	public function updatePositionDetails($table_name, $date, $position_detail, $employee_details_id)
	{ 
		$promotionData['date'] = $date;
		$promotionData['employee_Details_Id'] = $employee_details_id;
		if($table_name== 'emp_position_title')
			$promotionData['position_Title_Id'] = $position_detail;
		else
			$promotionData['position_Level_Id'] = $position_detail;
			
		$action = new Update($table_name);
		$action->set($promotionData);
		$action->where(array('employee_details_id = ?' => $promotionData['employee_Details_Id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}


	public function updateEmpDetails($organisation_id, $departments_id, $departments_units_id, $employee_details_id)
	{
		$action = new Update('employee_details');
		$action->set(array('organisation_id' => $organisation_id, 'departments_id' => $departments_id, 'departments_units_id' => $departments_units_id));
		$action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	}


	
        public function saveEmployeeDiscipline(EmployeeDisciplineRecord $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);
		
		if($employeeObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_disciplinary_record');
			$action->set($employeeData);
			$action->where(array('id = ?' => $employeeObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_disciplinary_record');
			$action->values($employeeData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$employeeObject->setId($newId);
			}
			return $employeeObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateEmployeeOnProbation(EmployeeOnProbation $employeeObject)
	{
		$employeeData = $this->hydrator->extract($employeeObject);
		unset($employeeData['id']);

		$position_title_id = $employeeData['position_Title_Id'];
		unset($employeeData['position_Title_Id']);
		$position_level_id = $employeeData['position_Level_Id'];
		unset($employeeData['position_Level_Id']);
		$date = $employeeData['date'];
		unset($employeeData['date']);
		$position_category_id = $employeeData['position_Category_Id'];
		unset($employeeData['position_Category_Id']);
		$major_occupational_group_id = $employeeData['major_Occupational_Group_Id'];
		unset($employeeData['major_Occupational_Group_Id']);
		$organisation_id = $employeeData['organisation_Id'];
		//unset($employeeData['organisation_Id']);
		$office_order_no = $employeeData['office_Order_No'];
		unset($employeeData['office_Order_No']);

		$employeeData['office_Order_Date'] = date("Y-m-d", strtotime(substr($employeeData['office_Order_Date'], 0,10)));
		$office_order_date = $employeeData['office_Order_Date'];
		unset($employeeData['office_Order_Date']);

		$evidence_file = $employeeData['evidence_File'];
		$employeeData['evidence_File'] = $evidence_file['tmp_name'];
		$evidenceFile = $employeeData['evidence_File'];
		unset($employeeData['evidence_File']);
		$remarks = $employeeData['remarks'];
		unset($employeeData['remarks']);
		$working_agency_type = $employeeData['working_Agency_Type'];
	    unset($employeeData['working_Agency_Type']);
		$employee_details_id = $employeeData['employee_Details_Id'];
		unset($employeeData['employee_Details_Id']);

    	//ID present, so it is an update
        $action = new Update('employee_details');
        $action->set($employeeData);
        $action->where(array('id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->addEmpOnProbationEmploymentRecord($organisation_id, $major_occupational_group_id, $position_category_id, $position_title_id, $position_level_id, $date, $office_order_date, $office_order_no, $evidenceFile, $remarks, $employee_details_id, $working_agency_type);
		$this->updateNewEmployeePositionTitle($employee_details_id, $position_title_id, $office_order_date);
		$this->updateNewEmployeePositionLevel($employee_details_id, $position_level_id, $office_order_date);
	}
        
	/*
	* Common function for other details such as contributions, awards etc
	*/
	
	public function findEmployeeExtraDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_awards'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'emp_award_category'),
			   			't2.id = t1.award_category_id', array('award_category'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_community_services'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'emp_community_service_category'),
			   			't2.id = t1.community_service_category_id', array('community_service_category'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_contributions'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'emp_contribution_category'),
			   			't2.id = t1.contribution_category_id', array('contribution_category'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_responsibilities'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'emp_responsibility_category'),
			   			't2.id = t1.responsibility_category_id', array('responsibility_category'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_education_details'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'study_level'),
			   			't2.id = t1.study_level', array('study_level'))
			       ->join(array('t3' => 'country'),
			   			't3.id = t1.college_Country', array('country'))
			       ->join(array('t4' => 'funding_category'),
			   			't4.id = t1.funding', array('funding_type'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_relation_details'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'gender'),
			   			't2.id = t1.gender', array('gender'))
			       ->join(array('t3' => 'relation_type'),
			   			't3.id = t1.relation_type', array('relation'))
			       ->join(array('t4' => 'nationality'),
			   			't4.id = t1.nationality', array('nationality'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_previous_trainings'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'country'),
			   			't2.id = t1.country', array('country'))
			       ->join(array('t3' => 'funding_category'),
			   			't3.id = t1.funding', array('funding_type'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'emp_previous_research'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'research_category'),
			   			't2.id = t1.research_type', array('research_category'))
				->where('t1.employee_details_id = ' .$id); 
		}
		else if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName))
			       ->join(array('t2' => 'employee_type'),
			   			't2.id = t1.emp_type', array('employee_type'))
			       ->join(array('t3' => 'emp_position_title'),
			   			't1.id = t3.employee_details_id', array('date', 'position_title_id'))
			       ->join(array('t4' => 'position_title'),
			   			't4.id = t3.position_title_id', array('position_category_id'))
			       ->join(array('t5' => 'emp_position_level'),
			   			't1.id = t5.employee_details_id', array('position_level_id'))
			       ->join(array('t6' => 'position_level'),
			   			't6.id = t5.position_level_id', array('major_occupational_group_id'))
				->where('t1.id = ' .$id); 
			$select->order('t3.date ASC');
		}
		else {
			$select->from(array('t1' => $tableName))
				->where('t1.employee_details_id = ' .$id); 
		}
			
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findEmployeeRUBExtraDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.working_agency', array('organisation_name'))
			   ->join(array('t3' => 'major_occupational_group'),
					't3.id = t1.occupational_group', array('occupational_group' => 'major_occupational_group'))
			   ->join(array('t4' => 'position_category'),
					't4.id = t1.position_category', array('category'))
			   ->join(array('t5' => 'position_title'),
					't5.id = t1.position_title', array('position_title_name' => 'position_title'))
			   ->join(array('t6' => 'position_level'),
					't6.id = t1.position_level', array('position_level_name' => 'position_level'))
			   ->where(array('t1.employee_details_id = ' .$id, 't1.working_agency_type' => 'RUB'));
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function findEmployeeNonRUBExtraDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->where(array('t1.employee_details_id = ' .$id, 't1.working_agency_type' => 'NON-RUB')); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Employee Details such as Emp Id and DOB to add new user
	*/
	
	public function getEmployeeDetailsToAdd($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
				->columns(array('emp_id','date_of_birth'))
				->where('t1.id = ' .$employee_details_id); 
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* List Employees to add awards etc
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id, $self_id, $role)
	{
		
		$employee_list = array();

		$abbr = $this->getOrganisationAbbr($organisation_id);

		//echo "$abbr"; die();
		$requiredrole = NULL;
		if($organisation_id == 1){			
			if($role == 'ADMIN'){
				$requiredrole = 'ADMIN';
			}
			if($role == $abbr.'_HR_DIVISION_HEAD'){
				$requiredrole = $abbr.'_HR_DIVISION_HEAD';
			}
			if($role == $abbr.'_HRO'){
				$requiredrole = $abbr.'_HRO';
			}
			if($role == $abbr.'_HR_ASSISTANT'){
				$requiredrole = $abbr.'_HR_ASSISTANT';
			}
			

			//echo "$role"; die();
		}
		else{
			if($role == $abbr.'_ICT_SECTION_HEAD'){
				$requiredrole = $abbr.'_ICT_SECTION_HEAD';
			}
			if($role == $abbr.'_ICT_SECTION_ADMIN'){
				$requiredrole = $abbr.'_ICT_SECTION_ADMIN';
			}
			if($role == $abbr.'_ADMINISTRATIVE_SECTION_HEAD'){
				$requiredrole = $abbr.'_ADMINISTRATIVE_SECTION_HEAD';
			}
			if($role == $abbr.'_ADM_ASSISTANT'){
				$requiredrole = $abbr.'_ADM_ASSISTANT';
			}
			if($role == $abbr.'_PRESIDENT'){
				$requiredrole = $abbr.'_PRESIDENT';
			}

			//echo "$role"; die();
		}

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id', 'cid','date_of_birth', 'phone_no','email', 'recruitment_date'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'))
				->join(array('t5' => 'department_units'),
						't5.id = t1.departments_units_id', array('unit_name'))
				->join(array('t6' => 'emp_position_level'),
						't1.id = t6.employee_details_id', array('position_level_id'))
				->join(array('t7' => 'position_level'),
						't7.id = t6.position_level_id', array('position_level'))
				->join(array('t8' => 'organisation'),
						't8.id = t1.organisation_id', array('abbr'))
				->join(array('t9' => 'major_occupational_group'),
						't9.id = t7.major_occupational_group_id', array('major_occupational_group'))
				->join(array('t10' => 'gender'),
						't10.id = t1.gender', array('gender'))
				->join(array('t11' => 'country'),
						't11.id = t1.country', array('country'))
				->join(array('t12' => 'employee_type'),
						't12.id = t1.emp_type', array('employee_type'));
		$select->where(array('t1.emp_resignation_id' => '0'));
		if($organisation_id != 1){	
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		if($requiredrole != $role){
			$select->where(array('t1.emp_id' => $self_id));
		}
		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
		}
		if($department){
			$select->where(array('t2.department_name' =>$department));
		}
		
		$select->order('t1.first_name ASC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_list[$set['id']] = $set;
		}
		
		return $employee_list;
	}



	public function getEmployeeDetails($employee_details_id)
	{
		$employee_details = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'department_units'),
						't3.id = t1.departments_units_id', array('unit_name'))
			    ->join(array('t4' => 'employee_type'),
						't4.id = t1.emp_type', array('employee_type'))
				->join(array('t5' => 'gender'),
						't5.id = t1.gender', array('gender'))
				->join(array('t6' => 'maritial_status'),
						't6.id = t1.marital_status', array('maritial_status'))
				->join(array('t7' => 'blood_group'),
						't7.id = t1.blood_group', array('blood_group'))
				->join(array('t8' => 'religion'),
						't8.id = t1.religion', array('religion'))
				->join(array('t9' => 'organisation'),
						't9.id = t1.organisation_id', array('organisation_name'));
		$select->where(array('t1.id' => $employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_details[$set['id']] = $set;
		}
		return $employee_details;
	}


	public function getEmpPositionTitleDetail($employee_details_id)
	{
		$position_title = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_title'))
				->join(array('t2' => 'position_title'), 
						't1.position_title_id = t2.id', array('position_title'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->order('t1.id DESC');
		$select->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$position_title[$set['id']] = $set;
		}
		return $position_title;
	}


	public function getEmpPositionLevelDetail($employee_details_id)
	{
		$position_level = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_level'))
				->join(array('t2' => 'position_level'),
						't2.id = t1.position_level_id', array('position_level'))
				->join(array('t3' => 'pay_scale'),
						't2.id = t3.position_level', array('minimum_pay_scale', 'increment', 'maximum_pay_scale'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->order('t1.id DESC');
		$select->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$position_level[$set['id']] = $set;
		}
		return $position_level;
	}


	public function getEmpJobProfileDetails($id)
	{
		$emp_job_profile = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_profile'));
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$emp_job_profile[$set['id']] = $set;
		}
		return $emp_job_profile;
	}


	public function getEmpJobProfile($employee_details_id)
	{
		$emp_job_profile = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_profile'))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.organisation_id', array('organisation_name'))
			   ->join(array('t3' => 'departments'),
					't3.id = t1.departments_id', array('department_name'))
			    ->join(array('t4' => 'department_units'),
					't4.id = t1.departments_units_id', array('unit_name'))
				->join(array('t5' => 'major_occupational_group'),
					't5.id = t1.major_occupational_group_id', array('major_occupational_group'))
				->join(array('t6' => 'position_category'),
					't6.id = t1.emp_category_id', array('category'))
				->join(array('t7' => 'position_title'),
					't7.id = t1.position_title_id', array('position_title'))
				->join(array('t8' => 'position_level'),
					't8.id = t1.position_level_id', array('position_level'))
				->join(array('t9' => 'increment_type'),
					't9.id = t1.increment_type_id', array('increment_type'))
				->join(array('t10' => 'employee_type'),
					't10.id = t1.emp_type_id', array('employee_type'));
		$select->where(array('t1.employee_details' => $employee_details_id));
		$select->order('t1.id DESC');
		$select->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$emp_job_profile[$set['id']] = $set;
		}
		return $emp_job_profile;
	}


	public function getDepartmentEmployeeList($empName, $empId, $department, $organisation_id)
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
						't3.position_title_id = t4.id', array('position_title'))
				->join(array('t5' => 'department_units'),
						't5.id = t1.departments_units_id', array('unit_name'))
				->join(array('t6' => 'emp_position_level'),
						't6.employee_details_id = t1.id', array('position_level_id'))
				->join(array('t7' => 'position_level'),
						't7.id = t6.position_level_id', array('position_level'));
		$select->where(array('t1.organisation_id' =>$organisation_id, 't1.emp_resignation_id' => '0'));
		$select->order('t3.date ASC');

		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
		}
		if($department){
			$select->where(array('t2.department_name' =>$department));
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


	public function getPersonalDetails($employee_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'Department'){
			$select->from(array('t1' => 'employee_details'))
				->join(array('t2' => 'dzongkhag'), 
                        't1.emp_dzongkhag = t2.id', array('dzongkhag_name'))
				->join(array('t3'=>'gewog'),
					't1.emp_gewog = t3.id', array('gewog_name'))
				->join(array('t4'=>'village'),
					't1.emp_village = t4.id', array('village_name'))
				->join(array('t5'=>'nationality'),
					't1.nationality = t5.id', array('nationality'))
		       ->where(array('t1.id' => $employee_id));
		}
		elseif($type == 'Position'){
			$select->from(array('t1' => 'employee_details'))
		          ->where(array('t1.id' => $employee_id));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getEmploymentDetails($employee_id)
	{
		$employment_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'position_title'))
					->columns(array('position_title', 'position_category_id'))
					->join(array('t2' => 'emp_position_title'), 
							't1.id = t2.position_title_id', array('employee_details_id', 'position_title_id'))
					->join(array('t3'=>'position_category'),
                            't1.position_category_id = t3.id', array('category', 'major_occupational_group_id'))
					->join(array('t4'=>'major_occupational_group'),
                            't3.major_occupational_group_id = t4.id', array('major_occupational_group'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $data){
			$employment_details['position_title'] = $data['position_title'];
			$employment_details['category'] = $data['category'];
			$employment_details['major_occupational_group'] = $data['major_occupational_group'];
			$employment_details['major_occupational_group_id'] = $data['major_occupational_group_id'];
			$employment_details['position_title_id'] = $data['position_title_id'];
			$employment_details['position_category_id'] = $data['position_category_id'];
		}
		
		$select2 = $sql->select();
		$select2->from(array('t1' => 'position_level'))
					->columns(array('position_level'))
					->join(array('t2' => 'emp_position_level'), 
							't1.id = t2.position_level_id', array('employee_details_id', 'position_level_id', 'date'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		foreach($resultSet2 as $data){
			$employment_details['position_level'] = $data['position_level'];
			$employment_details['position_level_id'] = $data['position_level_id'];
			$employment_details['date'] = $data['date'];
		}

		$select3 = $sql->select();
		$select3->from(array('t1' => 'pay_scale'))
					->columns(array('minimum_pay_scale', 'maximum_pay_scale'))
					->join(array('t2' => 'emp_position_level'), 
							't1.position_level = t2.position_level_id', array('employee_details_id'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt3 = $sql->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		foreach($resultSet3 as $data){
			$employment_details['minimum_pay_scale'] = $data['minimum_pay_scale'];
			$employment_details['maximum_pay_scale'] = $data['maximum_pay_scale'];
		}
		
		return $employment_details;
	}


	public function getDepartmentDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'employee_details'))
    		   ->join(array('t2' => 'departments'),
    				't2.id = t1.departments_id', array('department_name'))
    		   ->join(array('t3' => 'department_units'),
    				't3.id = t1.departments_units_id', array('unit_name'));
    	$select->where(array('t1.id' => $employee_id));           

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	}


	public function getStaffPositionDetails($employee_id)
	{ 
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'employee_details'))
    		   ->columns(array('id'))
    		   ->join(array('t2' => 'emp_position_title'),
    				't2.employee_details_id = t1.id', array('position_title_id'))
    		   ->join(array('t3' => 'emp_position_level'),
    				't3.employee_details_id = t1.id', array('position_level_id'))
    		   ->join(array('t4' => 'position_title'),
    				't4.id = t2.position_title_id', array('position_category_id'))
    		   ->join(array('t5' => 'position_category'),
    				't5.id = t4.position_category_id', array('major_occupational_group_id'));
    	$select->where(array('t1.id' => $employee_id));           

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	}


	public function getEmployeeOnProbationList($empName, $empId, $department, $organisation_id)
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
						't3.position_title_id = t4.id', array('position_title'))
				->where(array('t1.emp_type' => '1'));
		//$select->order('t3.date ASC');
		
		if($organisation_id != 1){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
		}
		if($department){
			$select->where(array('t2.department_name' =>$department));
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


	public function getEmployeePayDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

    	$select->from(array('t1' => 'emp_pay_details'))
    		   ->join(array('t2' => 'employee_details'),
    				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'emp_type'))
    		   ->join(array('t3' => 'employee_type'),
    				't3.id = t2.emp_type', array('employee_type'))
    		   ->join(array('t4' => 'emp_position_level'),
    				't4.employee_details_id = t2.id', array('date', 'position_level_id'))
    		   ->join(array('t5' => 'position_level'),
    				't5.id = t4.position_level_id', array('position_level', 'major_occupational_group_id'))
    		   ->join(array('t6' => 'major_occupational_group'),
    				't6.id = t5.major_occupational_group_id', array('major_occupational_group'))
    		   ->order(array('t4.date DESC'))
    		   ->limit(1);
    	$select->where(array('t1.employee_details_id' => $id));           

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
	}
        
        /*
	* List Employees to add awards etc
	*/
	
	public function getEmployeeListByLevel($empName, $empId, $department, $organisation_id)
	{
		$employee_list = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		

		$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_level'), 
						't1.id = t3.employee_details_id', array('position_level_id'))
				->join(array('t4' => 'position_level'), 
						't3.position_level_id = t4.id', array('position_level'))
                                ->join(array('t5' => 'emp_position_title'), 
						't1.id = t5.employee_details_id', array('position_title_id'))
				->join(array('t6' => 'position_title'), 
						't5.position_title_id = t6.id', array('position_title'));
		$select->order('t3.date ASC');
                $select->order('t5.date ASC');
		
		if($organisation_id != 1){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
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
	
	/*
	* Function to generate the employee id for new employee
	*/
	
	public function generateEmployeeId()
	{
		//format for employee id
		$Year = date('Y');
		//$format = 'RUB'.substr($Year, 2).date('m');
		$format = 'RUB'.substr($Year, 2);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->columns(array('emp_id'));
		$select->where->like('emp_id','%'.$format.'%');
		$select->order('emp_id DESC');
		$select->limit(1);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$emp_id = NULL;
		
		foreach($resultSet as $set)
			$emp_id = $set['emp_id']; 
		
		//first employee of the year
		if($emp_id == NULL){
			$generated_id = 'RUB'.substr(date('Y'),2).date('m').'001';
		} 
		else{
			//need to get the last 3 digits and increment it by 1 and convert it back to string
			$number = substr($emp_id, -3);
			$number = (int)$number+1;
			$number = strval($number);
			while (mb_strlen($number)<3)
				$number = '0'. strval($number);
			
			$generated_id = 'RUB'.substr(date('Y'),2).date('m').$number;
		}
		
		return $generated_id;
	}


	public function getEvidenceFileName($id, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'emp_education_details'){
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_previous_trainings')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_previous_research')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_responsibilities')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_contributions')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_awards')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_community_services')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		if($tableName == 'emp_employment_record')
		{
			$select->from(array('t1' => $tableName))
				   ->columns(array('id', 'evidence_file'))
			       ->where(array('t1.id = ?' => $id));
		}

		//$select->columns(array('supporting_documents'));
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$fileLocation;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['evidence_file'];
		}
		
		return $fileLocation;
	}
	
	/*
	* Add new user to the system
	* username is the emp id and password is the date of birth
	*/
	
	public function addNewUser($emp_id, $cid, $organisation_id, $occupational_group)
	{
		$abbr = $this->getOrganisationAbbr($organisation_id);

		if($occupational_group == 1)
			$role = $abbr.'_ACADEMIC_STAFF';
		else
			$role = $abbr.'_ADMINISTRATIVE_STAFF';
		$action = new Insert('users');
		$action->values(array(
			'username' => $emp_id,
			'password' => md5($cid),
			'role' => $role,
			'region' => $organisation_id
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
	* Adding Position Title for new employee
	*/
	
	public function addNewEmployeePositionTitle($employee_details_id, $position_title_id, $recruitment_date)
	{
		$action = new Insert('emp_position_title');
		$action->values(array(
			'date' => $recruitment_date,
			'employee_details_id' => $employee_details_id,
			'position_title_id' => $position_title_id
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Adding Position Level for new employee
	*/
	
	public function addNewEmployeePositionLevel($employee_details_id, $position_level_id, $recruitment_date)
	{
		$action = new Insert('emp_position_level');
		$action->values(array(
			'date' => $recruitment_date,
			'position_level_id' => $position_level_id,
			'employee_details_id' => $employee_details_id
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateNewEmployeePositionTitle($employee_details_id, $position_title_id, $office_order_date)
	{
		$positionTitleData['date'] = $office_order_date;
		$positionTitleData['position_Title_Id'] = $position_title_id;

		$action = new Update('emp_position_title');
		$action->set($positionTitleData);
		$action->where(array('employee_details_id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function updateNewEmployeePositionLevel($employee_details_id, $position_level_id, $office_order_date)
	{
		$positionLevelData['date'] = $office_order_date;
		$positionLevelData['position_Level_Id'] = $position_level_id;

		$action = new Update('emp_position_level');
		$action->set($positionLevelData);
		$action->where(array('employee_details_id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function addEmpOnProbationEmploymentRecord($organisation_id,$major_occupational_group_id, $position_category_id, $position_title_id, $position_level_id, $date, $office_order_date, $office_order_no, $evidence_file, $remarks, $employee_details_id, $working_agency_type)
	{

		$action = new Insert('emp_employment_record');
		$action->values(array(
			'working_agency' => $organisation_id,
			'occupational_group' => $major_occupational_group_id,
			'position_category' => $position_category_id,
			'position_title' => $position_title_id,
			'position_level' => $position_level_id,
			'start_period' => $date,
			'end_period' => $office_order_date,
			'office_order_no' => $office_order_no,
			'office_order_date' => $office_order_date,
			'evidence_file' => $evidence_file,
			'remarks' => $remarks,
			'employee_details_id' => $employee_details_id,
			'working_agency_type' => $working_agency_type,
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}


	public function addNewEmployeeLeaveBalance($employee_details_id, $update_by_employee_id)
	{
		$action = new Insert('emp_leave_balance');
		$action->values(array(
			'casual_leave' => '10.00',
			//'eol' => '180.00',
			'earned_leave' => '0.00',
			//'leave_balance' => '0.00',
			'employee_details_id' => $employee_details_id,
			'update_by_employee_id' => $update_by_employee_id,
			'updated_date' => date('Y-m-d')
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}


	public function addNewEmployeePayDetails($employee_details_id, $pay_scale_id, $basic_pay, $increment, $university_allowance, $rent_allowance, $teaching_allowance, $fixed_term_allowance)
	{
		/*$basic_pay = str_replace(',', '', $basic_pay);
		$university_allowance = str_replace(',', '', $university_allowance);
		$rent_allowance = str_replace(',', '', $rent_allowance);
		$teaching_allowance = str_replace(',', '', $teaching_allowance);*/

		$action = new Insert('emp_pay_details');
		$action->values(array(
			'pay_scale_id' => $pay_scale_id,
			'basic_pay' => $basic_pay,
			'increment' => $increment,
			'university_allowance' => $university_allowance,
			'professional_allowance' => '0',
			'house_rent_allowance' => $rent_allowance,
			'communication_allowance' => '0',
			'kabney_allowance' => '0',
			'teaching_allowance' => $teaching_allowance,
			'fixed_term_allowance' => $fixed_term_allowance,
			'vice_chancellor_allowance' => '0',
			'dean_allowance' => '0',
			'patang_allowance' => '0',
			'employee_details_id' => $employee_details_id
		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Return an id for the departments and units given the name
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $department_name, $organisation_id)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'position_category'){
			$select->from(array('t1' => $tableName))
					->columns(array('id'));
            $select->where->like('t1.category','%'.$department_name.'%');
		}
		else if($tableName == 'departments'){
			$select->from(array('t1' => $tableName))
					->columns(array('id'));
            $select->where->like('department_name','%'.$department_name.'%');
			$select->where('t1.organisation_id = ' .$organisation_id);
		}
		else if($tableName == 'department_units'){
			$select->from(array('t1' => $tableName))
					->columns(array('id'))
					->join(array('t2' => 'departments'), 
                            't1.departments_id = t2.id', array('department_name'));
            $select->where->like('t2.department_name','%'.$department_name.'%');
			$select->where('t2.organisation_id = ' .$organisation_id);
		}
		else if($organisation_id == NULL ){
			$select->from(array('t1' => $tableName))
					->columns(array('id'));
            if($tableName=='position_title'){
				$select->where->like('t1.position_title','%'.$department_name.'%');
            }
			else if($tableName == 'position_level'){
				$select->where->like('t1.position_level','%'.$department_name.'%');
			}
			else if($tableName == 'gewog'){
				$select->where(array('t1.gewog_name = ?' => $department_name));
			}
			else if($tableName == 'village'){
				$select->where(array('t1.village_name = ?' => $department_name));
			}
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
	* Get the position level id of an employee based on his/her position title
	* function created as position level does not have a 1=>1 mapping
	*/
	
	public function getPositionLevelOccupationalGroup($employee_id, $position_level)
	{
		$position_level_id;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_position_title'))
				->columns(array('position_title_id'))
				->join(array('t2' => 'position_title'), 
                            't1.position_title_id = t2.id', array('position_category_id'))
                    ->join(array('t3'=>'position_category'),
                            't2.position_category_id = t3.id', array('major_occupational_group_id'))
					 ->join(array('t4'=>'major_occupational_group'),
                            't3.major_occupational_group_id = t4.id')
                    ->where('t1.employee_details_id = ' .$employee_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$major_occupational_group_id;
		foreach($resultSet as $set){
			$major_occupational_group_id = $set['id'];
		}

		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'position_level'))
				->columns(array('id'))
				->join(array('t4'=>'major_occupational_group'),
                            't1.major_occupational_group_id = t4.id',array('major_occupational_group'));
        	$select2->where->like('t1.position_level','%'.$position_level);
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		foreach($resultSet2 as $set2){
			$position_level_id = $set2['id'];
		}
		return $position_level_id;	
	}


	public function getOccupationalGroup($position_level_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_level'));
		$select->columns(array('id', 'major_occupational_group_id'))
			   ->where(array('t1.id' => $position_level_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = NULL;
		foreach($resultSet as $set)
		{
			$selectData = $set['major_occupational_group_id'];
		}
		return $selectData;
	}
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getHrReport($report_type)
	{
		$hr_reports = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($report_type['report_type'] == 'employee_record'){
			$select->from(array('t1' => 'employee_details'))
					->join(array('t2'=>'nationality'),
                            't1.nationality = t2.id',array('nationality'))
					->join(array('t3'=>'organisation'),
                            't1.organisation_id = t3.id',array('organisation_name'));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			if($report_type['report_format'] == 'list'){
				$hr_reports = $resultSet;
			} else {
				foreach($resultSet as $set){
					$hr_reports[$set['organisation_id']][$set['gender']][$set['id']] = $set['id'];
				}				
			}
			
		} else if($report_type['report_type'] == 'training_record'){
			//long term training details
			$select->from(array('t1' => 'training_details'))
					->columns(array('id','training_category'))
					->join(array('t2'=>'emp_training_details'),
                            't1.id = t2.training_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('emp_id','first_name','middle_name','last_name','organisation_id'))
					->join(array('t4'=>'organisation'),
                            't3.organisation_id = t4.id',array('organisation_name'));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			if($report_type['report_format'] == 'list'){
				$hr_reports[] = $resultSet;
			} else {
				foreach($resultSet as $set){
					$hr_reports[$set['organisation_id']]['longterm'][$set['id']] = $set['id'];
				}				
			}
			
			//short term training details
			$sql2 = new Sql($this->dbAdapter);
			$select2 = $sql2->select();
			$select2->from(array('t1' => 'workshop_details'))
					->columns(array('id','type'))
					->join(array('t2'=>'emp_workshop_details'),
                            't1.id = t2.workshop_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('emp_id','first_name','middle_name','last_name','organisation_id'))
					->join(array('t4'=>'organisation'),
                            't3.organisation_id = t4.id',array('organisation_name'));
			$stmt2 = $sql2->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			
			if($report_type['report_format'] == 'list'){
				$hr_reports[] = $resultSet2;
			} else {
				foreach($resultSet2 as $set2){
					$hr_reports[$set2['organisation_id']]['shortterm'][$set2['id']] = $set2['id'];
				}				
			}
			
		} else if($report_type['report_type'] == 'promotion_record'){
			
		} else if($report_type['report_type'] == 'transfer_record'){
			
		}
		
		
		return $hr_reports;
	}


	public function listSelectCategoryData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id', $columnName))
			   ->where(array('t1.organisation_id' => $organisation_id));

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
	
	/**
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($tableName == 'position_level' && $columnName == NULL){
			$select->from($tableName);
			$select->columns(array(new Expression('DISTINCT position_level as position_level'),'id')); 
		} else {
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName)); 
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		if($tableName == 'position_level' && $columnName == NULL){
			foreach($resultSet as $set){
				$selectData[$set['position_level']] = $set['position_level'];
			}
		} else if($tableName == 'resignation_type'){
			foreach($resultSet as $set){
				$selectData['0'] = 'Active';
				$selectData[$set['id']] = $set[$columnName];
			}
		}
		else {
			foreach($resultSet as $set){
				$selectData[$set['id']] = $set[$columnName];
			}
			//this if for Activities for AWPA Activities
			if($tableName == 'awpa_objectives_activity'){
				$selectData[0] = 'Others';
			}
		}
		
		return $selectData;
	}
}
