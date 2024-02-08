<?php

namespace EmpTransfer\Mapper;

use EmpTransfer\Model\EmpTransfer;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements EmpTransferMapperInterface
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
	 * @var \EmpTransfer\Model\EmpTransferInterface
	*/
	protected $transferPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmpTransfer $transferPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->transferPrototype = $transferPrototype;
	}
	
	
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function getEmployeeDetailsId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('emp_id = ? ' => $id));
		$select->columns(array('id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
	* take username and returns Name and any other detail required
	*/
	
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
		else if($tableName = 'job_applicant'){
			$select->from(array('t1' => $tableName));
			$select->where(array('email' =>$username));
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
	
	/*
	* Get Employee Details of those Transfer Employees
	*/
	
	public function getTransferEmployee()
	{
		//the employee array stores that array data of the nominees
		$employeeArray = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'emp_transfer_application'));
		$select->columns(array('employee_details_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $tempData){
			array_push($employeeArray,$tempData['employee_details_id']);
		}
		
		if(empty($employeeArray)){
			return $employeeArray;
		}
		
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'));
		$select->where(array('id' =>$employeeArray));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result); 
		
	}
	
	/**
	* @return array/EmpTransfer()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Transfer Details for a given $id
	 */
	 
	public function findTransferDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.id = ? ' => $id));
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
		
	/**
	 * 
	 * @param type $EmpTransferInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function save(EmpTransfer $transferObject)
	{
		$transferData = $this->hydrator->extract($transferObject);
		unset($transferData['id']);
		
		//need to get the file locations and store them in database
		$file_name = $transferData['document_Proof'];
		$transferData['document_Proof'] = $file_name['tmp_name'];
		
		if($transferObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_transfer_application');
			$action->set($transferData);
			$action->where(array('id = ?' => $transferObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_transfer_application');
			$action->values($transferData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $transferObject->setId($newId);
			}
			return $transferObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Transfer Applicant Details
	 * These are the details after the transfer has been approved
	 */
	 
	public function saveTransferApplicantDetails($data)
	{
		$transferData['id'] = $data['id'];
		$transferData['transfer_Order_No'] = $data['transfer_order_no'];
		$transferData['transfer_Order_Date'] = $data['transfer_order_date'];
		$transferData['joining_Date'] = $data['joining_date'];
		$transferData['new_Working_Agency'] = $data['new_working_agency'];
		$transferData['new_Position_Category'] = $this->getAjaxData('position_category', $data['new_position_category'], NULL);
		$transferData['new_Position_Title'] = $data['new_position_title'];
		$transferData['new_Position_Level'] = $data['new_position_level'];
		$transferData['new_Pay_Scale'] = $data['new_pay_scale'];
		//need to work on the file
		//$transferData['transfer_order_file'] = $data['transfer_order_file'];
		//need to get the file locations and store them in database
		$file_name = $data['transfer_order_file'];
		$transferData['transfer_Order_File'] = $file_name['tmp_name'];
		//keep a record of position title and position level, if they are different
		if($transferData['new_Position_Title'] != $data['previous_position_title']){
			$position_title = $this->getAjaxData('position_title', NULL, $data['new_position_title']);
			$this->recordPositionTitle($data['employee_details_id'], $position_title, $data['joining_date']);
		}
		if($transferData['new_Position_Level'] != $data['previous_position_level']){
			$position_level = $this->getAjaxData('position_level', $data['new_position_level'],$data['new_position_title']);
			$this->recordPositionLevel($data['employee_details_id'], $position_level, $data['joining_date']);
			
		}
		if($transferData['new_Working_Agency'] != $data['previous_working_agency']){
			//$this->updateOrganisation($data['employee_details_id']);
		}
		//update the employment record
		$this->updateEmploymentRecord($transferData['new_Working_Agency'], $data['occupational_group'], $transferData['new_Position_Category'], $transferData['new_Position_Title'], $transferData['new_Position_Level'], $transferData['transfer_Order_Date'], $data['employee_details_id'] );
		
		$transferData['new_Position_Title'] = $this->getAjaxData('position_title', NULL, $data['new_position_title']);
		$transferData['new_Position_Level'] = $this->getAjaxData('position_level', $data['new_position_level'],$data['new_position_title']);
		unset($transferData['id']);
		
		$action = new Update('emp_transfer_application');
		$action->set($transferData);
		$action->where(array('id = ?' => $data['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}
			return $data;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * to get the transfer TO/FROM based on organisations
	 * for e.g. HRO should only see applicants that have applied TO/FROM respective organisations
	 */
	 
	public function getTransferList($type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($type == 'transfer_to'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.transfer_request_to = ? ' => $organisation_id))
					->where(array('t1.from_org_transfer_status = ? ' => 'pending'))
					->where(array('t1.to_org_transfer_status = ? ' => 'pending'));
		}
		elseif ($type == 'transfer_from'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t2.organisation_id = ? ' => $organisation_id))
					->where(array('t1.from_org_transfer_status = ? ' => 'pending'))
					->where(array('t1.to_org_transfer_status = ? ' => 'pending'));
		}
		elseif($type == 'approved'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'approved'))
					->where(array('t1.to_org_transfer_status = ? ' => 'approved'));
		}
		else{
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'pending'))
					->where(array('t1.to_org_transfer_status = ? ' => 'pending'));
		}
		
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Personal Details
	*/
	
	public function getPersonalDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('t1.id' =>$employee_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Employment Details such as Position Title, Position Level etc. of the employee
	*/
	
	public function getEmploymentDetails($employee_id)
	{
		$employment_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'position_title'))
					->columns(array('position_title'))
					->join(array('t2' => 'emp_position_title'), 
							't1.id = t2.position_title_id', array('employee_details_id'))
					->join(array('t3'=>'position_category'),
                            't1.position_category_id = t3.id', array('category'))
					->join(array('t4'=>'major_occupational_group'),
                            't3.major_occupational_group_id = t4.id', array('major_occupational_group'))
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $data){
			$employment_details['position_title'] = $data['position_title'];
			$employment_details['category'] = $data['category'];
			$employment_details['major_occupational_group'] = $data['major_occupational_group'];
		}
		
		$select2 = $sql->select();
		$select2->from(array('t1' => 'position_level'))
					->columns(array('position_level'))
					->join(array('t2' => 'emp_position_level'), 
							't1.id = t2.position_level_id', array('employee_details_id'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $data){
			$employment_details['position_level'] = $data['position_level'];
		}

		return $employment_details;
	}
	
	/*
	* Get the details of the applicant for transfer
	* Used when updating the details of the applicant
	* Takes the id of the transfer details
	*/
	
	public function getTransferApplicantDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_transfer_application'))
				->join(array('t2' => 'employee_details'), 
							't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$applicant_details = array();
		foreach($resultSet as $set){
			$applicant_details['employee_details_id'] = $set['employee_details_id'];
		}
		return $applicant_details;
	}
	
	/*
	* Update the status of the transfer FROM/ TO organisation
	*/
	
	public function updateTransferStatus($id, $status, $type)
	{
		$data['id'] = $id;
		$transferData[$type] = $status;
		$action = new Update('emp_transfer_application');
		$action->set($transferData);
		$action->where(array('id = ?' => $data['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}
			return $data;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Keep a record of the position title (if there is a change) during transfer
	*/
	
	public function recordPositionTitle($employee_id, $position_title, $date)
	{
		$transferData['position_title_id'] = $position_title;
		$transferData['date'] = $date;
		$transferData['employee_details_id'] = $employee_id;
		
		$action = new Insert('emp_position_title');
		$action->values($transferData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			
			return ;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Keep a record of the position title (if there is a change) during transfer
	*/
	
	public function recordPositionLevel($employee_id, $position_level, $date)
	{
		$transferData['position_level_id'] = $position_level;
		$transferData['date'] = $date;
		$transferData['employee_details_id'] = $employee_id;
		
		$action = new Insert('emp_position_level');
		$action->values($transferData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			
			return;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Update the Organisation ID during transfer
	*/
	
	public function updateOrganisation($employee_id)
	{
		
	}
	
	/*
	* Update the Employment Record After Promotion
	*/
	
	public function updateEmploymentRecord($working_agency, $occupational_group, $position_category, $position_title_id, $position_level_id, $promotion_date, $employee_details_id)
	{
		//store the data in the an array and update the employment record
		$transferData['working_Agency'] = $this->getWorkingAgency($working_agency);
		$transferData['occupational_Group'] = $this->getOccupationalGroup($occupationalGroup);
		$transferData['position_Category'] = $this->getPositionCategory($position_category);
		$transferData['position_Title'] = $position_title_id;
		$transferData['position_Level'] = $position_level_id;
		$transferData['start_period'] = $promotion_date;
		$transferData['remarks'] = 'Transfer';
		$transferData['employee_Details_Id'] = $employee_details_id;
		
		$action = new Insert('emp_employment_record');
		$action->values($transferData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
        
        /*
         * Get the working agency given the employee id
         */
        
        public function getWorkingAgency($working_agency)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'organisation'))
				->columns(array('organisation_name'));
		$select->where('t1.id = ' .$working_agency);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$organisation = NULL;
		
		foreach($resultSet as $set){
			$organisation = $set['organisation_name'];
		}
		return $organisation;
        }
        
        /*
         * Get the Occupational Group given the id
         */
        
        public function getOccupationalGroup($id)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'major_occupational_group'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$occupational_group = NULL;
		
		foreach($resultSet as $set){
			$occupational_group = $set['major_occupational_group'];
		}
		return $occupational_group;
        }
        
        /*
         * Get the Postion Category given the id
         */
        
        public function getPositionCategory($id)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'position_category'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$position_category = NULL;
		
		foreach($resultSet as $set){
			$position_category = $set['category'];
		}
		return $position_category;
        }
		
	/*
	* Get the notification details, i.e. submission to and submission to department
	*/
	
	public function getNotificationDetails($organisation_id)
	{
		$submission_to = NULL;
		if($organisation_id == 1){
			$submission_to = "OVC_HRO";
			return $submission_to;
		} else {
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			
			$select->from(array('t1' => 'user_role'))
					->columns(array('rolename'));
			$select->where('t1.organisation_id = ' .$organisation_id);
			$select->where->like('t1.rolename','%PRESIDENT');
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$occupational_group = NULL;
			
			foreach($resultSet as $set){
					$submission_to = $set['rolename'];
				}
			return $submission_to;
		}
		
	}
	
	/*
	*
	*/
	
	public function getAjaxData($table_name, $position_level, $position_title)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($table_name == 'position_category'){
			//here the position_level is actually position category
			$select->from(array('t1' => $table_name))
				->columns(array('id'));
			$select->where(array('t1.category' =>$position_level));
		} else if($table_name == 'position_title'){
			$select->from(array('t1' => $table_name))
				->columns(array('id'));
			$select->where(array('t1.position_title' =>$position_title));
		} else {
			$select->from(array('t1' => $table_name))
				->columns(array('id'));
			$select->where->like('t1.description','%'.$position_title.'%');
			$select->where(array('t1.position_level' =>$position_level));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$id = NULL;
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		return $id;
	}
	 
	/**
	* @return array/EmpTransfer()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName)); 

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