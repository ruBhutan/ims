<?php

namespace EmpTransfer\Mapper;

use EmpTransfer\Model\EmpTransfer;
use EmpTransfer\Model\OvcTransferApproval;
use EmpTransfer\Model\JoiningReport;
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
		$select->columns(array('id', 'departments_units_id', 'departments_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the User Name
	*/
	
	public function getEmployeeUserName($id)
	{
		$emp_id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('id = ? ' => $id));
		$select->columns(array('emp_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$emp_id = $set['emp_id'];
		}
		
		return $emp_id;
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


	public function crossCheckEmpTransfer($employee_details_id, $fromStatus, $toStatus)
	{		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($fromStatus == 'pending' && $toStatus == 'pending'){
			$select->from(array('t1' => 'emp_transfer_application'))
				->columns(array('employee_details_id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.from_org_transfer_status' => $fromStatus, 't1.to_org_transfer_status' => $toStatus));
		}
		else if($fromStatus == 'pending' && $toStatus == 'Approved'){
			$select->from(array('t1' => 'emp_transfer_application'))
				->columns(array('employee_details_id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.from_org_transfer_status' => $fromStatus, 't1.to_org_transfer_status' => $toStatus));
		}
		else if($fromStatus == 'Approved' && $toStatus == 'pending'){
			$select->from(array('t1' => 'emp_transfer_application'))
				->columns(array('employee_details_id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.from_org_transfer_status' => $fromStatus, 't1.to_org_transfer_status' => $toStatus));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$transferStatus = NULL;
		foreach($resultSet as $set){
				$transferStatus= $set['employee_details_id'];
		}
		return $transferStatus;
	}


	public function getEmployeeSpouseDetails($relationType, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_relation_details'))
			   ->where(array('t1.employee_details_id' => $employee_details_id, 't1.relation_type' => $relationType));
			   

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getTransferedEmpSpouseDetails($relationType, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_transfer_application'))
		       ->join(array('t2' => 'emp_relation_details'),
		   				't2.employee_details_id = t1.employee_details_id', array('name', 'occupation'))
			   ->where(array('t1.id' => $id, 't2.relation_type' => $relationType));
			   

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
                    ->join(array('t3' => 'organisation'),
                			't3.id = t2.organisation_id', array('organisation_name'))
                    ->where(array('t1.id = ? ' => $id));
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}


	public function getEmpTransferFileName($application_id, $column_name)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'emp_transfer_application')) 
                ->columns(array($column_name))
				->where('t1.id = ' .$application_id);
		
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
		$transferData['employee_Details_Id'] = $data['employee_details_id'];
		$transferData['transfer_Order_No'] = $data['transfer_order_no'];
		$transferData['transfer_Order_Date'] = $data['transfer_order_date'];
		$transferData['joining_Date'] = $data['joining_date'];
		$transferData['new_Working_Agency'] = $data['new_working_agency'];
		$transferData['new_Position_Category'] = $data['new_position_category'];
		$transferData['new_Position_Title'] = $data['new_position_title'];
		$transferData['new_Position_Level'] = $data['new_position_level'];
		$transferData['new_Pay_Scale'] = $data['new_pay_scale'];
		$transferData['previous_Working_Agency'] = $data['previous_working_agency'];
		$transferData['previous_Position_Category'] = $data['previous_position_category'];
		$transferData['previous_Position_Title'] = $data['previous_position_title'];
		$transferData['previous_Position_Level'] = $data['previous_position_level'];
		$transferData['previous_Pay_Scale'] = $data['previous_pay_scale'];
		$transferData['occupational_Group'] = $data['occupational_group'];
		$transferData['new_Departments_Id'] = $data['new_departments_id'];
		$transferData['new_Departments_Units_Id'] = $data['new_departments_units_id'];
		$transferData['reasons'] = $data['reasons'];
		//$transferData['previous_Working_Agency'] = $this->getAjaxData('organisation', $data['previous_working_agency'], NULL);
		//$transferData['ovc_Transfer_Status'] = "Pending";
		
		$file_name = $data['transfer_order_file'];
		$transferData['transfer_Order_File'] = $file_name['tmp_name'];
		
		$transferData['new_Position_Title'] = $data['new_position_title'];
		$transferData['new_Position_Level'] = $data['new_position_level'];
		$occupational_group = $transferData['occupational_Group'];
		$departments_id = $transferData['new_Departments_Id'];
		$departments_units_id = $transferData['new_Departments_Units_Id']; 
		unset($transferData['id']);
		unset($transferData['occupational_Group']);
		unset($transferData['new_Departments_Id']);
		unset($transferData['new_Departments_Units_Id']); 

		$transferData['transfer_Order_Date'] = date("Y-m-d", strtotime(substr($transferData['transfer_Order_Date'],0,10)));
		$transferData['joining_Date'] = date("Y-m-d", strtotime(substr($transferData['joining_Date'],0,10)));

		$recruitment_date = $this->getEmpRecruitmentDate($transferData['employee_Details_Id']); 
		
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

			$this->updateEmployeeDetails($transferData['joining_Date'], $transferData['new_Working_Agency'], $departments_id, $departments_units_id, $transferData['employee_Details_Id']);
			$this->addEmpPositionLevel($transferData['joining_Date'], $transferData['new_Position_Level'], $transferData['employee_Details_Id']);
			$this->addEmpPositionTitle($transferData['joining_Date'], $transferData['new_Position_Title'], $transferData['employee_Details_Id']);
			$this->addEmpEmploymentRecord($transferData['previous_Working_Agency'], $occupational_group, $transferData['previous_Position_Category'], $transferData['previous_Position_Title'], $transferData['previous_Position_Level'], $recruitment_date, $transferData['transfer_Order_Date'], $transferData['transfer_Order_No'], $transferData['reasons'], $transferData['employee_Details_Id']);

			$this->updateTransferedUser($transferData['new_Working_Agency'], $occupational_group, $transferData['employee_Details_Id']);

			return $data;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveTransferedStaffDetails($data)
	{
		$transferData['id'] = $data['id'];
		$transferData['employee_Details_Id'] = $data['employee_details_id'];
		$transferData['transfer_Order_No'] = $data['transfer_order_no'];
		$transferData['transfer_Order_Date'] = $data['transfer_order_date'];
		$transferData['joining_Date'] = $data['joining_date'];
		$transferData['new_Working_Agency'] = $data['new_working_agency'];
		$transferData['new_Position_Category'] = $data['new_position_category'];
		$transferData['new_Position_Title'] = $data['new_position_title'];
		$transferData['new_Position_Level'] = $data['new_position_level'];
		$transferData['new_Pay_Scale'] = $data['new_pay_scale'];
		$transferData['previous_Working_Agency'] = $data['previous_working_agency'];
		$transferData['previous_Position_Category'] = $data['previous_position_category'];
		$transferData['previous_Position_Title'] = $data['previous_position_title'];
		$transferData['previous_Position_Level'] = $data['previous_position_level'];
		$transferData['previous_Pay_Scale'] = $data['previous_pay_scale'];
		$transferData['occupational_Group'] = $data['occupational_group'];
		$transferData['new_Departments_Id'] = $data['new_departments_id'];
		$transferData['new_Departments_Units_Id'] = $data['new_departments_units_id'];
		$transferData['reasons'] = $data['reasons'];
		//$transferData['previous_Working_Agency'] = $this->getAjaxData('organisation', $data['previous_working_agency'], NULL);
		//$transferData['ovc_Transfer_Status'] = "Pending";
		
		$file_name = $data['transfer_order_file'];
		$transferData['transfer_Order_File'] = $file_name['tmp_name'];
		
		$transferData['new_Position_Title'] = $data['new_position_title'];
		$transferData['new_Position_Level'] = $data['new_position_level'];
		$occupational_group = $transferData['occupational_Group'];
		$departments_id = $transferData['new_Departments_Id'];
		$departments_units_id = $transferData['new_Departments_Units_Id']; 
		unset($transferData['id']);
		unset($transferData['occupational_Group']);
		unset($transferData['new_Departments_Id']);
		unset($transferData['new_Departments_Units_Id']); 

		$transferData['transfer_Order_Date'] = date("Y-m-d", strtotime(substr($transferData['transfer_Order_Date'],0,10)));
		$transferData['joining_Date'] = date("Y-m-d", strtotime(substr($transferData['joining_Date'],0,10)));

		$recruitment_date = $this->getEmpRecruitmentDate($transferData['employee_Details_Id']); 
		
		$action = new Insert('emp_transfer_application');
		$action->values(array(
			'transfer_request_to' => $transferData['new_Working_Agency'],
			'reason_for_transfer' => 'Request Transfer',
			'date_of_request' => $transferData['transfer_Order_Date'],
			'from_org_transfer_status' => 'Approved',
			'to_org_transfer_status' => 'Approved',
			'from_org_transfer_remarks' => 'Approved',
			'to_org_transfer_remarks' => 'Approved',
			'transfer_order_no' => $transferData['transfer_Order_No'],
			'transfer_order_date' => $transferData['transfer_Order_Date'],
			'joining_date' => $transferData['joining_Date'],
			'previous_working_agency' => $transferData['previous_Working_Agency'],
			'new_working_agency' => $transferData['new_Working_Agency'],
			'previous_position_title' => $transferData['previous_Position_Title'],
			'new_position_title' => $transferData['new_Position_Title'],
			'previous_position_level' => $transferData['previous_Position_Level'],
			'new_position_level' => $transferData['new_Position_Level'],
			'previous_position_category' => $transferData['previous_Position_Category'],
			'new_position_category' => $transferData['new_Position_Category'],
			'previous_pay_scale' => $transferData['previous_Pay_Scale'],
			'new_pay_scale' => $transferData['new_Pay_Scale'],
			'transfer_order_file' => $transferData['transfer_Order_File'],
			'employee_details_id' => $transferData['employee_Details_Id'],
			'reasons' => $transferData['reasons']

		));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}

			$this->updateEmployeeDetails($transferData['joining_Date'], $transferData['new_Working_Agency'], $departments_id, $departments_units_id, $transferData['employee_Details_Id']);
			$this->addEmpPositionLevel($transferData['joining_Date'], $transferData['new_Position_Level'], $transferData['employee_Details_Id']);
			$this->addEmpPositionTitle($transferData['joining_Date'], $transferData['new_Position_Title'], $transferData['employee_Details_Id']);
			$this->addEmpEmploymentRecord($transferData['previous_Working_Agency'], $occupational_group, $transferData['previous_Position_Category'], $transferData['previous_Position_Title'], $transferData['previous_Position_Level'], $recruitment_date, $transferData['transfer_Order_Date'], $transferData['transfer_Order_No'], $transferData['reasons'], $transferData['employee_Details_Id']);

			$this->updateTransferedUser($transferData['new_Working_Agency'], $occupational_group, $transferData['employee_Details_Id']);

			return $data;
		}
		
		throw new \Exception("Database Error");
	}



	public function updateEmployeeDetails($joining_date, $organisation_id, $departments_id, $departments_units_id, $employee_details_id)
	{
		$transferData['recruitment_Date'] = $joining_date;
		$transferData['organisation_Id'] = $organisation_id;
		$transferData['departments_Id'] = $departments_id;
		$transferData['departments_Units_Id'] = $departments_units_id;
		unset($transferData['id']);

		$action = new Update('employee_details');
		$action->set($transferData);
		$action->where(array('id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function addEmpPositionLevel($joining_date, $new_position_level, $employee_details_id)
	{
		$positionLevelData['date'] = $joining_date;
		$positionLevelData['position_Level_Id'] = $new_position_level;

		$action = new Update('emp_position_level');
		$action->set($positionLevelData);
		$action->where(array('employee_details_id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function addEmpPositionTitle($joining_date, $new_position_title, $employee_details_id)
	{
		$positionTitleData['date'] = $joining_date;
		$positionTitleData['position_Title_Id'] = $new_position_title;

		$action = new Update('emp_position_title');
		$action->set($positionTitleData);
		$action->where(array('employee_details_id = ?' => $employee_details_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function addEmpEmploymentRecord($previous_working_agency, $occupational_group, $previous_position_category, $previous_position_title, $previous_position_level, $recruitment_date, $transfer_order_date, $transfer_order_no, $reasons, $employee_details_id)
	{
		$action = new Insert('emp_employment_record');
		$action->values(array(
			'working_agency' => $previous_working_agency,
			'occupational_group' => $occupational_group,
			'position_category' => $previous_position_category,
			'position_title' => $previous_position_title,
			'position_level' => $previous_position_level,
			'start_period' => $recruitment_date,
			'end_period' => $transfer_order_date,
			'office_order_no' => $transfer_order_no,
			'office_order_date' => $transfer_order_date,
			'remarks' => $reasons,
			'employee_details_id' => $employee_details_id,
			'working_agency_type' => 'RUB'
		));
		$sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
	}


	/*
	** Function to update the user table of the approved transfer staff
	*/
	public function updateTransferedUser($new_working_agency, $occupational_group, $employee_details_id)
	{
		$abbr = $this->getOrganisationAbbr($new_working_agency);
		if($occupational_group == 1){
			$role = $abbr.'_ACADEMIC_STAFF';
		}
		else if($occupational_group == 2){
			$role = $abbr.'_ADMINISTRATIVE_STAFF';
		}

		$empId = $this->getTransferedEmpId($employee_details_id);

		$action = new Update('users');
		$action->set(array('role' => $role, 'region' => $new_working_agency));
		$action->where(array('username = ?' => $empId));

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


    public function getTransferedEmpId($employee_details_id)
    {
    	$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'employee_details'));  
        $select->columns(array('emp_id'))
               ->where(array('t1.id' => $employee_details_id)); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        $empId = NULL;
        foreach($resultSet as $set)
        {
            $empId = $set['emp_id'];
        }
        return $empId;
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
		elseif($type == 'Approved'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'Approved', 't1.to_org_transfer_status = ? ' => 'Approved', 't1.transfer_order_no' => NULL));
		} elseif($type == 'ovc_approval'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'approved'))
					->where(array('t1.to_org_transfer_status = ? ' => 'approved'))
					->where(array('t1.ovc_transfer_status = ? ' => 'Pending'));
		} elseif($type == 'ovc_approved'){
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'approved'))
					->where(array('t1.to_org_transfer_status = ? ' => 'approved'))
					->where(array('t1.ovc_transfer_status = ? ' => 'OVC Approved'));
		}
		elseif($type == NULL) {
			$select->from(array('t1' => 'emp_transfer_application')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','organisation_id'))
                    ->where(array('t1.from_org_transfer_status = ? ' => 'Approved'))
					->where(array('t2.organisation_id = ? ' => $organisation_id));
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


	public function getTransferApprovalList($type, $organisation_id, $userrole)
	{
		if ($type == 'transfer_from'){

			$auth_type = 'Staff Transfer';
			$organisation_staff = array();
			$empty = array();

			$sql = new Sql($this->dbAdapter);
			$select1 = $sql->select();

			//first get the organisationa and auth type for user role;
			$select1->from(array('t1' => 'user_workflow'))
			        ->columns(array('organisation', 'type', 'auth'))
			        ->join(array('t2' => 'users'),
			    		't2.role = t1.role', array('username'))
			        ->join(array('t3' => 'employee_details'),
			    			't3.emp_id = t2.username', array('id', 'organisation_id'));
			$select1->where(array('t1.type = ?' => $auth_type));
			$select1->where(array('t1.auth = ?' => $userrole));
			$select1->where(array('t1.organisation = ?' => $organisation_id));

        	$stmt1 = $sql->prepareStatementForSqlObject($select1);
        	$result1 = $stmt1->execute();
       		$resultSet1 = new ResultSet();
        	$resultSet1->initialize($result1);

        	$organisation_staff = array();
        	foreach($resultSet1 as $set1){
                $organisation_staff[$set1['id']] = $set1['id'];
        	}

	        if(!empty($organisation_staff)){
	        	$select = $sql->select();
	        	$select->from(array('t1' => 'emp_transfer_application'))
	        		   ->join(array('t2' => 'employee_details'),
	        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
	        		   ->join(array('t3' => 'organisation'),
	        				't3.id = t1.transfer_request_to', array('organisation_name'))
	        		   ->where(array('t1.employee_details_id' => $organisation_staff));
	        		   //->where(array('t1.from_org_transfer_status = ? ' => 'pending'))
					//	->where(array('t1.to_org_transfer_status = ? ' => 'pending'));
				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				return $resultSet->initialize($result); 
	        }
	        return $empty;
		}

		elseif ($type == 'transfer_to') {
			$auth_type = 'Staff Transfer';
			//$organisation_staff = array();
			$empty = array();

			$sql = new Sql($this->dbAdapter);
			$select1 = $sql->select();

			//first get the organisationa and auth type for user role;
			$select1->from(array('t1' => 'user_workflow'))
			        ->columns(array('organisation', 'type', 'auth'))
			        ->join(array('t2' => 'users'),
			    		't2.role = t1.role', array('username'))
			        ->join(array('t3' => 'employee_details'),
			    			't3.emp_id = t2.username', array('id', 'organisation_id'));
			$select1->where(array('t1.type = ?' => $auth_type));
			$select1->where(array('t1.auth = ?' => $userrole));

        	$stmt1 = $sql->prepareStatementForSqlObject($select1);
        	$result1 = $stmt1->execute();
       		$resultSet1 = new ResultSet();
        	$resultSet1->initialize($result1);

        	$transferType = array();
        	foreach($resultSet1 as $set1){
                $transferType[$set1['type']] = $set1['type'];
        	}

	        if(!empty($transferType)){
	        	$select = $sql->select();
	        	$select->from(array('t1' => 'emp_transfer_application'))
	        		   ->join(array('t2' => 'employee_details'),
	        				't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
	        		   ->join(array('t3' => 'organisation'),
	        				't3.id = t2.organisation_id', array('organisation_name'))
	        		   ->where(array('t1.from_org_transfer_status = ? ' => 'Approved'))
					   ->where(array('t1.transfer_request_to = ? ' => $organisation_id));
				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				return $resultSet->initialize($result); 
	        }
	        return $empty;
		}
	}
	
	/*
	* Get Personal Details
	*/
	
	public function getPersonalDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.organisation_id', array('organisation_name'))
			   ->join(array('t3' => 'departments'),
					't3.id = t1.departments_id', array('department_name'))
			   ->join(array('t4' => 'department_units'),
					't4.id = t1.departments_units_id', array('unit_name'));
		$select->where(array('t1.id' => $employee_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		$email = array();
		$supervisor_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
			   ->columns(array('auth', 'department', 'organisation'));
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => 'Staff Transfer'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$supervisor_details[$set['auth']][$set['department']][$set['organisation']] = $set['organisation'];
        }

        
        if(!empty($supervisor_details)){
        	foreach($supervisor_details as $auth => $value){ 
	        	foreach($value as $department => $value1){
	        		foreach($value1 as $organisation){
	        			$sql1 = new Sql($this->dbAdapter);
						$select1 = $sql1->select();

						$select1->from(array('t1' => 'users'))
							   ->join(array('t2' => 'employee_details'),
									't2.emp_id = t1.username', array('email'));
						$select1->where(array('t1.role' =>$auth, 't2.departments_id' => $department, 't2.organisation_id' => $organisation));
							
						$stmt1 = $sql1->prepareStatementForSqlObject($select1);
						$result1 = $stmt1->execute();
						
						$resultSet1 = new ResultSet();
						$resultSet1->initialize($result1);

						//$email = array();
						foreach($resultSet1 as $set1){
							$email[] = $set1['email'];
						} 					
	        		}
	        	}
	        }
        }
        return $email;
	}


	public function getAuthorizeeEmailId($to_organisation)
	{
		$abbr = $this->getOrganisationAbbr($to_organisation);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'users'),
					't2.username = t1.emp_id', array('role'))
		       ->where->like('t2.role', $abbr.'_ADMINISTRATIVE_SECTION_HEAD');
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$email = array();
		foreach($resultSet as $set)
		{
			$email[] = $set['email'];
		}
		return $email;
	}


	public function getOrganisation($organisation_id)
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
	
	/*
	* Get Employment Details such as Position Title, Position Level etc. of the employee
	*/
	
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
                            't1.position_category_id = t3.id', array('category'))
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
			$employment_details['position_title_id'] = $data['position_title_id'];
			$employment_details['position_category_id'] = $data['position_category_id'];
		}
		
		$select2 = $sql->select();
		$select2->from(array('t1' => 'position_level'))
					->columns(array('position_level'))
					->join(array('t2' => 'emp_position_level'), 
							't1.id = t2.position_level_id', array('employee_details_id', 'position_level_id'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $data){
			$employment_details['position_level'] = $data['position_level'];
			$employment_details['position_level_id'] = $data['position_level_id'];
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

		$select4 = $sql->select();
		$select4->from(array('t1' => 'emp_transfer_application'))
					->columns(array('transfer_request_to'))
					->join(array('t2' => 'organisation'), 
							't2.id = t1.transfer_request_to', array('organisation_name'))
                    ->where('t1.employee_details_id = ' .$employee_id);
			
		$stmt4 = $sql->prepareStatementForSqlObject($select4);
		$result4 = $stmt4->execute();
		$resultSet4 = new ResultSet();
		$resultSet4->initialize($result4);
		foreach($resultSet4 as $data){
			$employment_details['organisation_name'] = $data['organisation_name'];
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


	public function listAllEmployees($organisation_id)
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
				->where('t1.organisation_id = ' .$organisation_id)
				->limit(20);
				

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getEmployeeList($empName, $empId, $department, $organisation_id)
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
						't3.position_title_id = t4.id', array('position_title'));
		$select->order('t3.date ASC');
		if($organisation_id != '1'){
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


	public function getTransferRequestAgency($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_transfer_application'))
		       ->columns(array('transfer_request_to'))
		       ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$agency = NULL;
		foreach($resultSet as $set){
				$agency = $set['transfer_request_to'];
		}
		return $agency;
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


	public function getEmpRecruitmentDate($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->columns(array('recruitment_date'));
		$select->where('t1.id = ' .$employee_details_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$recruitment_date = NULL;
		
		foreach($resultSet as $set){
			$recruitment_date = $set['recruitment_date'];
		}
		return $recruitment_date;
	}
	
	/*
	 * Get the Occupational Group given the id
	 */
	
	public function getOccupationalGroup($position_title_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'major_occupational_group'))
					->join(array('t2' => 'position_category'), 
                            't1.id = t2.major_occupational_group_id')
					->join(array('t3' => 'position_title'), 
                            't2.id = t3.position_category_id')
                    ->where(array('t3.id = ? ' => $position_title_id));
		
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
	* Get the User role so that we can update the user
	*/
	
	public function getUserRole($role, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'user_role'));
		$select->where->like('t1.rolename','%'.$role);
        $select->where(array('t1.organisation_id = ? ' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$user_role = NULL;
		
		foreach($resultSet as $set){
			$user_role = $set['rolename'];
		}
		return $user_role;
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
	* Transfer Approval from OVC
	*/
	
	public function saveOvcTransferApproval(OvcTransferApproval $transferObject)
	{
		$transferData = $this->hydrator->extract($transferObject);
		unset($transferData['id']);
		
		//need to get the file locations and store them in database
		$file_name = $transferData['rejection_Order'];
		$transferData['rejection_Order'] = $file_name['tmp_name'];
		
		$action = new Update('emp_transfer_application');
		$action->set($transferData);
		$action->where(array('id = ?' => $transferObject->getId()));
		
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
	* Save the joining report after the transfer has been approved by OVC
	*/
	
	public function saveJoiningReport(JoiningReport $reportObject)
	{
		$reportData = $this->hydrator->extract($reportObject);
		unset($reportData['id']);
		
		//need to get the file locations and store them in database
		$file_name = $reportData['joining_Report'];
		$reportData['joining_Report'] = $file_name['tmp_name'];
		
		$action = new Update('emp_transfer_application');
		$action->set($reportData);
		$action->where(array('id = ?' => $reportObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			//need to update all employment records
			$this->recordPositionLevel($reportObject->getId());
			$this->recordPositionTitle($reportObject->getId());
			$this->updateEmploymentRecord($reportObject->getId());
			//need to update employee details
			$this->updateEmployeeRecord($reportObject->getId());
			//need to update user role
			$this->updateUserRole($reportObject->getId());
			return $reportObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Keep a record of the position title (if there is a change) during transfer
	*/
	
	public function recordPositionTitle($transfer_id)
	{
		$transfer_details = $this->findTransferDetails($transfer_id);
		$transferData = array();
		foreach($transfer_details as $transfer){
			$transferData['position_Title_Id'] = $transfer['new_position_title'];
			$transferData['date'] = $transfer['joining_date'];
			$transferData['employee_Details_Id'] = $transfer['employee_details_id'];
		}
		
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
	
	public function recordPositionLevel($transfer_id)
	{
		$transfer_details = $this->findTransferDetails($transfer_id);
		$transferData = array();
		foreach($transfer_details as $transfer){
			$transferData['position_Litle_Id'] = $transfer['new_position_level'];
			$transferData['date'] = $transfer['joining_date'];
			$transferData['employee_Details_Id'] = $transfer['employee_details_id'];
		}
		
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
	* Update the Employee Record during transfer
	*/
	
	public function updateEmployeeRecord($transfer_id)
	{
		$transfer_details = $this->findTransferDetails($transfer_id);
		$transferData = array();
		$employee_details_id = NULL;
		foreach($transfer_details as $transfer){
			$transferData['working_Agency'] = $transfer['new_working_agency'];
			$employee_details_id = $transfer['employee_details_id'];
		}
		
		$action = new Update('employee_details');
		$action->set($transferData);
		$action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/*
	* Update the Employment Record After Promotion/Transfer
	*/
	
	public function updateEmploymentRecord($transfer_id)
	{
		$transfer_details = $this->findTransferDetails($transfer_id);
		$transferData = array();
		foreach($transfer_details as $transfer){
			$transferData['working_Agency'] = $transfer['new_working_agency'];
			$transferData['occupational_Group'] = $transfer[''];
			$transferData['position_Category'] = $transfer['new_position_category'];
			$transferData['position_Title'] = $transfer['new_position_title'];
			$transferData['position_Level'] =$transfer['new_position_level'];
			$transferData['start_period'] = $transfer['joining_date'];
			$transferData['remarks'] = 'Transfer';
			$transferData['employee_Details_Id'] = $transfer['employee_details_id'];
		}
		
		$action = new Insert('emp_employment_record');
		$action->values($transferData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/*
	* Update the User Role on Transfer of Employee
	*/
	
	public function updateUserRole($transfer_id)
	{
		$transfer_details = $this->findTransferDetails($transfer_id);
		$transfer_data = array();
		$role = NULL;
		
		foreach($transfer_details as $transfer){
			$transfer_data['working_agency'] = $transfer['new_working_agency'];
			$transfer_data['position_title'] = $transfer['new_position_title'];
			$transfer_data['employee_details_id'] = $transfer['employee_details_id'];
		}
		
		//get the username
		$username = $this->getEmployeeUserName($transfer_data['employee_details_id']);
		
		//get the occupational group
		$occupational_group = $this->getOccupationalGroup($transfer_data['position_title']);
		if($occupational_group == "Academics"){
			$role = "ACADEMIC_STAFF";
		} else {
			$role = "ADMINISTRATIVE_STAFF";
		}
		
		//get the new user role
		$user_role = $this->getUserRole($role, $transfer_data['working_agency']);
		$userData['role'] = $user_role;
		
		$action = new Update('users');
		$action->set($userData);
		$action->where(array('username' =>$username));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
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
		} else if($table_name = 'position_level'){
			$select->from(array('t1' => $table_name))
				->columns(array('id'));
			$select->where(array('t1.position_level' =>$position_level));
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