<?php

namespace EmpResignation\Mapper;

use EmpResignation\Model\EmpResignation;
use EmpResignation\Model\Dues;
use EmpResignation\Model\Separation;
use EmpResignation\Model\SeparationRecord;
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

class ZendDbSqlMapper implements EmpResignationMapperInterface
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
	 * @var \EmpResignation\Model\EmpResignationInterface
	*/
	protected $resignationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmpResignation $resignationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->resignationPrototype = $resignationPrototype;
	}
	
	
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		
		$select->where(array('emp_id' =>$emp_id));
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
	* @return array/EmpResignation()
	*/
	public function findAll($userrole, $tableName, $organisation_id, $status)
	{
		$auth_type = 'Staff Resignation';
		$department_staff = array();
		$empty = array();

		if($userrole != NULL && $status == NULL)
		{
			$sql = new Sql($this->dbAdapter);
	        $select1 = $sql->select();

	        //first get the department, organisation and authtype for the user role
	        $select1->from(array('t1' => 'user_workflow'))
					->columns(array('role_department', 'department','type'));
			$select1->join(array('t2' => 'users'), 
						't1.role = t2.role', array('username'))
	                ->join(array('t3' => 'employee_details'), 
	                    't2.username = t3.emp_id', array('id', 'departments_units_id'));
			$select1->where(array('t1.type = ?' => $auth_type));
		    $select1->where(array('t1.auth = ?' => $userrole));
		    $select1->where(array('t1.organisation = ?' => $organisation_id));
					
	        $stmt1 = $sql->prepareStatementForSqlObject($select1);
	        $result1 = $stmt1->execute();
	        $resultSet1 = new ResultSet();
	        $resultSet1->initialize($result1);
	        $departments_staff = array();
	        foreach($resultSet1 as $set1){
	                $departments_staff[$set1['id']] = $set1['id'];
	        }

	        if(!empty($departments_staff)){
	        	$select = $sql->select();
	        	$select->from(array('t1' => $tableName)) //base table
		               ->join(array('t2' => 'employee_details'), // join table with alias
		                    't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
		               ->join(array('t3' => 'departments'),
		               	    't3.id = t2.departments_id', array('department_name'))
		               ->join(array('t4' => 'department_units'),
		               	     't4.id = t2.departments_units_id', array('unit_name'))
		               ->join(array('t5' => 'resignation_type'),
		           			  't5.id = t1.resignation_type', array('resignation_type'))
		               ->where(array('t1.employee_details_id' => $departments_staff, 't2.organisation_id = '.$organisation_id));

		        $stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				return $resultSet->initialize($result); 
	        }
	        return $empty;
		}

		else if($userrole == NULL){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			if($status == 'Approved'){
				$select->from(array('t1' => $tableName)) 
	                    ->join(array('t2' => 'employee_details'), 
	                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
	                    ->join(array('t3' => 'resignation_type'),
	                			't3.id = t1.resignation_type', array('resignation_type'))
	                    ->where(array('t2.organisation_id = ' .$organisation_id, 't1.resignation_status' => $status));
			}
			else{
				$select->from(array('t1' => $tableName)) 
	                    ->join(array('t2' => 'employee_details'), 
	                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
	                    ->join(array('t3' => 'resignation_type'),
	                			't3.id = t1.resignation_type', array('resignation_type'))
	                    ->where('t2.organisation_id = ' .$organisation_id);
			}
			

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Objectives for a given $id
	 */
	 
	public function getResignationDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','date_of_birth'))
                    ->join(array('t3' => 'resignation_type'),
                			't3.id = t1.resignation_type', array('resignationType' => 'resignation_type'))
                    ->where(array('t1.id' =>$id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
				->where(array('t1.organisation_id = ' .$organisation_id))
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

		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
			$select->where(array('t1.organisation_id' => $organisation_id));
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
			$select->where(array('t1.organisation_id' => $organisation_id));
		}
		if($department){
			$select->where(array('t2.department_name' =>$department));
			$select->where(array('t1.organisation_id' => $organisation_id));
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


	public function getResignedEmpDetails($id)
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
				->where(array('t1.id' => $id));
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getResignationApplicationDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'emp_resignation'))
        	   ->join(array('t2' => 'resignation_type'),
        			't2.id = t1.resignation_type', array('type' => 'resignation_type'))
               ->where(array('t1.id = ? ' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                return $this->hydrator->hydrate($result->current(), $this->resignationPrototype);
        }

        throw new \InvalidArgumentException("Resignation Application with given ID: ($id) not found");
	}
	
	/*
	* Get the details of the employee resigning to edit, delete etc.
	*/
	 
	public function getEmployeeResigningDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id','date_of_birth'))
                    ->join(array('t3' => 'resignation_type'),
                			't3.id = t1.resignation_type', array('resignation_type'))
                    ->where(array('t1.employee_details_id' => $employee_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSeparationRecordDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
	       	   ->join(array('t2' => 'emp_resignation'),
	   			't2.id = t1.emp_resignation_id', array('resignation_type', 'date_of_application', 'reason_for_resignation', 'employee_details_id')) 
                ->join(array('t3' => 'resignation_type'), 
                        't3.id = t2.resignation_type', array('resignationType'=>'resignation_type'))
                ->join(array('t4' => 'employee_details'),
            			't4.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'date_of_birth'))
                ->where('t1.id = ' .$id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getSeparationRecordFile($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_separation_record'))
			  ->columns(array('relieving_order_file'))
			   ->where(array('t1.id = ?' => $id));
		//$select->columns(array('supporting_documents'));
		 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$fileLocation = NULL;
		foreach($resultSet as $set)
		{
			$fileLocation = $set['relieving_order_file'];
		}
		
		return $fileLocation;
	}
	
	/*
	* get the list of goods issued to an employee
	* $id is the id of the resignation details
	*/
	
	public function getEmpGoods($id, $categoryType)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($categoryType == NULL){
			$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
					->join(array('t3'=>'emp_goods'),
                            't2.id = t3.employee_details_id', array('date_of_issue','emp_quantity'))
					->join(array('t4'=>'goods_received'),
                            't3.goods_received_id = t4.id', array('item_name_id'))
					->join(array('t5'=>'item_name'),
                            't4.item_name_id = t5.id', array('item_name'))
					->join(array('t6' => 'item_sub_category'),
							't6.id = t5.item_sub_category_id', array('item_category_id'))
					->join(array('t7' => 'item_category'),
							't7.id = t6.item_category_id', array('category_type'))
                    ->where(array('t1.id' =>$id, 't7.id !=' => '5', 't3.emp_quantity >= 1'));
		}
		else{
			$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
					->join(array('t3'=>'emp_goods'),
                            't2.id = t3.employee_details_id', array('date_of_issue','emp_quantity'))
					->join(array('t4'=>'goods_received'),
                            't3.goods_received_id = t4.id', array('item_name_id'))
					->join(array('t5'=>'item_name'),
                            't4.item_name_id = t5.id', array('item_name'))
					->join(array('t6' => 'item_sub_category'),
							't6.id = t5.item_sub_category_id', array('item_category_id'))
					->join(array('t7' => 'item_category'),
							't7.id = t6.item_category_id', array('category_type'))
                    ->where(array('t1.id' =>$id, 't7.id' => $categoryType, 't3.emp_quantity >= 1'));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function crossCheckResignationApplication($employee_details_id, $status)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($status == NULL){
			$select->from(array('t1' => 'emp_resignation'))
				->columns(array('employee_details_id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.resignation_status' => $status));
		}
		else if($status == 'Approved'){
			$select->from(array('t1' => 'emp_resignation'))
				->columns(array('employee_details_id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.resignation_status' => $status));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$resignationApplication = NULL;
		foreach($resultSet as $set){
				$resignationApplication= $set['employee_details_id'];
		}
		return $resignationApplication;
	}
		
	/**
	 * 
	 * @param type $EmpResignationInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function save(EmpResignation $resignationObject)
	{
		$resignationData = $this->hydrator->extract($resignationObject);
		unset($resignationData['id']);
		unset($resignationData['date_Of_Issue']);

		$resignationData['date_Of_Application'] = date("Y-m-d", strtotime(substr($resignationData['date_Of_Application'],0,10)));

		if($resignationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_resignation');
			$action->set($resignationData);
			$action->where(array('id = ?' => $resignationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_resignation');
			$action->values($resignationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $resignationObject->setId($newId);
			}
			return $resignationObject;
		}
		
		throw new \Exception("Database Error");
	}


	//Function to delete resignation application
	public function deleteEmployeeResignation($id)
	{

		$action = new Delete('emp_resignation');
		$action->where(array('id = ?' => $id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	
	/*
	 * Save Separation Record of Resigning Employee
	 */
	 
	public function saveSeparationRecord(SeparationRecord $resignationObject)
	{
		$resignationData = $this->hydrator->extract($resignationObject);
		unset($resignationData['id']);

		$file_name = $resignationData['relieving_Order_File'];
		$resignationData['relieving_Order_File'] = $file_name['tmp_name'];

		$resignationData['separation_Order_Date'] = date("Y-m-d", strtotime(substr($resignationData['separation_Order_Date'],0,10)));

		$employeeId = $this->getEmployeeId($resignationData['employee_Details_Id']);
		
		if($resignationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_separation_record');
			$action->set($resignationData);
			$action->where(array('id = ?' => $resignationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_separation_record');
			$action->values($resignationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $resignationObject->setId($newId);
				//update the status of the employee resignation table
				$this->updateResignationStatus($resignationData['emp_Resignation_Id'], 'Issued');
				$this->removeEmployeeFromUser($employeeId);
				$this->updateEmpResignationId($resignationData['employee_Details_Id'], $resignationData['separation_Type']);
			}
			return $resignationObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function getEmployeeId($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details')) 
                    ->columns(array('emp_id'))
					->where(array('t1.id' =>$employee_details_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$empId = NULL;		
		foreach($resultSet as $set){
			$empId = $set['emp_id'];
		}
		return $empId;
	}


	public function removeEmployeeFromUser($emp_id)
	{
		$action = new Delete('users');
		$action->where(array('username = ?' => $emp_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}


	public function updateEmpResignationId($employee_details_id, $resignationId)
	{
		$previousResignationId = 0;

		$resignationData['emp_resignation_id'] = $resignationId;

		$action = new Update('employee_details');
		$action->set($resignationData);
		$action->where(array('emp_resignation_id = ?' => $previousResignationId));
		$action->where(array('id = ?' => $employee_details_id));
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	
	/*
	* Save the Dues Clearance Record
	*/
	 
	public function saveDueClearance(Dues $resignationObject)
	{
		$resignationData = $this->hydrator->extract($resignationObject);
		unset($resignationData['id']);

		$resignationData['date_Of_Issue'] = date('Y-m-d', strtotime(substr($resignationData['date_Of_Issue'],0,10)));

		if($resignationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('dues_clearance');
			$action->set($resignationData);
			$action->where(array('id = ?' => $resignationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('dues_clearance');
			$action->values($resignationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $resignationObject->setId($newId);
			}
			return $resignationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Update the status of the resignation table
	*/
	
	public function updateResignationStatus($id, $status)
	{
		$resignationData['resignation_Status'] = $status;
		$action = new Update('emp_resignation');
		$action->set($resignationData);
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	 
	
	/*
	* Get Employee Details of those Resigning Employees
	*/
	
	public function getResigningEmployee($organisation_id)
	{
		//the employee array stores that array data of the nominees
		$employeeArray = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('organisation_id'))
                    ->where('t2.organisation_id = ' .$organisation_id);

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
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql->select();
		$select2->from(array('t1' => 'employee_details'));
		$select2->where(array('id' =>$employeeArray));
		
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		
		return $resultSet2->initialize($result2); 
		
	}
	
	/*
	* Get the status of the dues clearance of the resiging employee
	*/
	
	public function getDueClearance($organisation_id)
	{
		//the employee array stores that array data of the nominees
		$employeeArray = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		$select->from(array('t1' => 'emp_resignation')) 
                    ->join(array('t2' => 'employee_details'), 
                            't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
					->join(array('t3' => 'dues_clearance'), 
                            't1.id = t3.emp_resignation_id', array('remarks','date_of_issue','issuing_authority'))
					->join(array('t4' => 'resignation_type'), 
                            't4.id = t1.resignation_type', array('resignation_type'))
                    ->where('t2.organisation_id = ' .$organisation_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the Authorizing Role for various "no due certificate"
	*/
	 
	public function getAuthorisingRole($type, $organisation_id)
	{
		$user_work_flow = 'No '.$type.' Dues';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'user_workflow')) 
                    ->columns(array('auth'))
					->where(array('t1.type' =>$user_work_flow, 'organisation' => $organisation_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$authoriser = NULL;
		
		foreach($resultSet as $set){
			$authoriser = $set['auth'];
		}
		return $authoriser;
	}


	public function getSeparationRecordList($tableName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $tableName)) 
                    ->join(array('t2' => 'emp_resignation'), 
                            't1.emp_resignation_id = t2.id', array('date_of_application','reason_for_resignation','employee_details_id', 'resignation_status'))
                    ->join(array('t3' => 'resignation_type'),
                			't3.id = t1.separation_type', array('resignation_type'))
                    ->join(array('t4' => 'employee_details'),
                			't4.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'organisation_id'))
                    ->where(array('t4.organisation_id' => $organisation_id, 't2.resignation_status' => 'Issued'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
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
			$select->where->like('t1.rolename','%ADMINISTRATIVE_SECTION_HEAD');
			
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
			
	/**
	* @return array/EmpResignation()
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