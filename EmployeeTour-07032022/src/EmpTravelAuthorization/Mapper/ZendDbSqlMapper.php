<?php

namespace EmpTravelAuthorization\Mapper;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;
use EmpTravelAuthorization\Model\TravelPaymentDetails;
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

class ZendDbSqlMapper implements EmpTravelAuthorizationMapperInterface
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
	 * @var \EmpWorkForceProposal\Model\EmpWorkForceProposalInterface
	*/
	protected $empTravelAuthorizationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmpTravelAuthorization $empTravelAuthorizationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->empTravelAuthorizationPrototype = $empTravelAuthorizationPrototype;
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
	* @return EmpWorkForceProposal
	* @throws \InvalidArgumentException
	*/
	
	public function find($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('travel_authorization');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}
	
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the HRD Proposal for a given $id
	 */
	public function findDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_authorization'))
                    ->join(array('t2' => 'travel_details'), 
                            't1.id = t2.travel_authorization_id')
                    ->where(array('t1.id = ' .$id, 't1.tour_status' => 'Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);

	}


	public function findTravelOfficiating($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_authorization'))
                    ->join(array('t2' => 'employee_details'), 
                            't2.id = t1.officiating_staff', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where(array('t1.id = ' .$id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function crossCheckAppliedTravelAuthorization($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->columns(array('id', 'travel_auth_date'));
		$select->where(array('t1.employee_details_id' => $employee_details_id));
		$select->where(array('t1.tour_status' => 'Submitted'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$travelAuth = NULL;
		foreach($resultSet as $set){
				$travelAuth= $set['travel_auth_date'];
		}
		return $travelAuth;
	}
		
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(EmpTravelAuthorization $empTravelAuthorizationObject)
	{
		$empTravelAuthorizationData = $this->hydrator->extract($empTravelAuthorizationObject);
		unset($empTravelAuthorizationData['id']);
		
		$empTravelDetailsData = $empTravelAuthorizationData['emptraveldetails'];
		unset($empTravelAuthorizationData['emptraveldetails']);

		$empTravelAuthorizationData['travel_Auth_Date'] = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['travel_Auth_Date'],0,10)));
		$empTravelAuthorizationData['start_Date'] = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['start_Date'],0,10)));
		$empTravelAuthorizationData['end_Date'] = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['end_Date'],0,10)));

		$related_documents = $empTravelAuthorizationData['related_Document_File'];
		$empTravelAuthorizationData['related_Document_File'] = $related_documents['tmp_name'];
		
		if($empTravelAuthorizationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('travel_authorization');
			$action->set($empTravelAuthorizationData);
			$action->where(array('id = ?' => $empTravelAuthorizationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('travel_authorization');
			$action->values($empTravelAuthorizationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$empTravelAuthorizationObject->setId($newId);				
			}
			return $empTravelAuthorizationObject;
		}
		
		throw new \Exception("Database Error");
	}


	// Update the travel authorization details
	public function updateTravelAuthorization(EmpTravelAuthorization $empTravelAuthorizationObject)
	{   
		$empTravelAuthorizationData = $this->hydrator->extract($empTravelAuthorizationObject);
		//unset($empTravelAuthorizationData['id']); 
		//var_dump($empTravelAuthorizationData); die();
		$travel_auth_date = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['travel_Auth_Date'],0,10)));
		//$start_date = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['start_Date'],0,10)));
		//$end_date = date("Y-m-d", strtotime(substr($empTravelAuthorizationData['end_Date'],0,10)));
        //var_dump($empTravelAuthorizationData); die();
		$action = new Update('travel_authorization');
		$action->set(array('travel_auth_date' => $travel_auth_date, 'no_of_days' => $empTravelAuthorizationData['no_Of_Days'], 'estimated_expenses' => $empTravelAuthorizationData['estimated_Expenses'], 'advance_required' => $empTravelAuthorizationData['advance_Required'], 'tour_status' => $empTravelAuthorizationData['tour_Status'], 'purpose_of_tour' => $empTravelAuthorizationData['purpose_Of_Tour'], 'organisation_id' => $empTravelAuthorizationData['organisation_Id'], 'employee_details_id' => $empTravelAuthorizationData['employee_Details_Id'], 'officiating_staff' => $empTravelAuthorizationData['officiating_Staff'], 'applied_by_id' => $empTravelAuthorizationData['applied_By_Id']));
		$action->where(array('id = ?' => $empTravelAuthorizationData['id']));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
	
	/*
	 * Function to save the Travel Details
	 */
	 
	public function saveTravelDetails(EmpTravelDetails $empTravelDetailsObject)
	{
		$empTravelDetailsData = $this->hydrator->extract($empTravelDetailsObject);
		unset($empTravelDetailsData['id']);
		
		$empTravelDetailsData['from_Date'] = date("Y-m-d", strtotime(substr($empTravelDetailsData['from_Date'],0,10)));
		$empTravelDetailsData['to_Date'] = date("Y-m-d", strtotime(substr($empTravelDetailsData['to_Date'],0,10)));
		
		if($empTravelDetailsObject->getId()) {
			//ID present, so it is an update
			$action = new Update('travel_details');
			$action->set($empTravelDetailsData);
			$action->where(array('id = ?' => $empTravelDetailsObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('travel_details');
			$action->values($empTravelDetailsData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$empTravelDetailsObject->setId($newId);
			}
			return $empTravelDetailsObject;
		}
		throw new \Exception("Database Error");
	}


	public function updateEmpTravelDetailStatus($status, $previousStatus, $id)
	{
		//need to get the organisaiton id
		//$organisation_id = 1;
		$empTravelDetailsData['tour_status'] = $status;
		$action = new Update('travel_authorization');
		$action->set($empTravelDetailsData);

		if($previousStatus != NULL){

		$action->where(array('tour_status = ?' => $previousStatus));
		$action->where(array('id = ?' => $id));
	}
	/*elseif($id != NULL) {
		$action->where(array('id = ?' => $id));
	}*/
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function updateEmpTravelDetail($remarks, $status, $id, $organisation_id, $employee_details_id)
	{
		$empTravelDetailsData['tour_Status'] = $status;
		$empTravelDetailsData['remarks'] = $remarks;
		$empTravelDetailsData['authorizing_Officer'] = $employee_details_id;

		if($empTravelDetailsData['tour_Status'] == 'Approved'){
			$travel_auth_no = $this->generateTravelAuthNo($organisation_id);
		}else{
			$travel_auth_no = 'Rejected';
		}

		$empTravelDetailsData['travel_Auth_No'] = $travel_auth_no;
		//$empTravelDetailsData['id'] = $id;
		
        $action = new Update('travel_authorization');
        $action->set($empTravelDetailsData);
        $action->where(array('id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($empTravelDetailsData['tour_Status'] == 'Approved'){
        	$this->updateStaffOfficiating($id);
        }else{
        	return;
        }

        //return;
	}



	public function updateStaffOfficiating($id)
	{
		$travel_details = $this->getStaffTravelDetails($id);

		if(!empty($travel_details['officiating_staff'])){
			$role = $this->getStaffRole($travel_details['emp_id']);

			$action = new Insert('user_workflow_officiating');
			$action->values(array(
				'officiating_supervisor' => $travel_details['officiating_staff'],
				'from_date' => $travel_details['start_date'],
				'to_date' => $travel_details['end_date'],
				'supervisor' => $role,
				'supervisor_id' => $travel_details['employee_details_id'],
				'department' => $travel_details['departments_id'],
				'remarks' => 'Officiated from travel authorization'

			));
			
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		}else{
			return;
		}

	}



	public function getStaffTravelDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'travel_authorization')) 
                ->columns(array('start_date', 'end_date', 'officiating_staff', 'employee_details_id'))
                ->join(array('t2' => 'employee_details'),
            			't2.id = t1.employee_details_id', array('emp_id', 'departments_id'))
				->where(array('t1.id = ' .$id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$travel_details = array();
		foreach($resultSet as $set){
			$travel_details = $set;
		}	
		return $travel_details;
	}



	public function getStaffRole($emp_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'users')) 
                ->columns(array('role'))
				->where(array('t1.username = ' .$emp_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$role = NULL;
		foreach($resultSet as $set){
			$role = $set['role'];
		}	
		return $role;
	}



	public function updateEmpTravelOrder($data, $id)
	{ 
		$travel_order_no = $data['order_no'];

		$travel_order_date = date("Y-m-d", strtotime(substr($data['order_date'],0,10)));

		$order_file = $data['order_file'];
		$travel_order_file = $order_file['tmp_name'];

        $action = new Update('travel_authorization');
        $action->set(array('order_no' => $travel_order_no, 'order_date' => $travel_order_date, 'order_file' => $travel_order_file));
        $action->where(array('id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
	}


	public function getFileName($travel_authorization_id, $column_name)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'travel_authorization')) 
                ->columns(array($column_name))
				->where('t1.id = ' .$travel_authorization_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get Travel Details, given Travel Authorization $id
	 */
	 
	public function getTravelDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_authorization'))
                    ->join(array('t2' => 'travel_details'), 
                            't1.id = t2.travel_authorization_id')
                    ->join(array('t3' => 'employee_details'),
                			't3.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where(array('t1.id = ' .$id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function listEmpApprovedTravels($order_no, $organisation_id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($order_no == "NULL"){
			$select->from(array('t1' => 'travel_authorization'))
                    		->join(array('t2' => 'employee_details'),
                			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    		->where(array('t1.organisation_id' => $organisation_id, 't1.order_no is NULL', 't1.tour_status' => 'Approved'));
		}else if($order_no == "NOT NULL"){
			$select->from(array('t1' => 'travel_authorization'))
                    		->join(array('t2' => 'employee_details'),
                			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    		->where(array('t1.organisation_id' => $organisation_id, 't1.order_no is NOT NULL', 't1.tour_status' => 'Approved'));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        	return $resultSet->initialize($result);
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		$supervisor_email = array();
		$supervisor_role = NULL;
		$supervisor_dept = NULL;
		$supervisor_org = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
			   ->columns(array('auth', 'department', 'organisation'));
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => 'Tour'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

        $authorizee_role = array();
        foreach($resultSet as $set){
        	$authorizee_role[$set['auth']][$set['department']][$set['organisation']] = $set['organisation'];
        }

        if(!empty($authorizee_role)){
        	foreach($authorizee_role as $auth_role => $value){
        		foreach($value as $auth_department => $value2){
        			foreach($value2 as $organisation){
						//Get supervisor id
						$supervisor_ids = $this->getSupervisorIdByRole($auth_role, $auth_department);
						foreach($supervisor_ids as $supervisorId){
							//Get the officiating supervisor
							$officiating = $this->getSupervisorOfficiating($supervisorId, $auth_role, $auth_department);
							// Check whether the officiating supervisor array is empty or not
							if(!empty($officiating)){
								foreach($officiating as $officiating_supervisor_id){
									$sql2 = new Sql($this->dbAdapter);
									$select2 = $sql2->select();

									$select2->from(array('t1' => 'employee_details'));
									$select2->where(array('t1.id' =>$officiating_supervisor_id));
										
									$stmt2 = $sql2->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);

									//$email = array();
									foreach($resultSet2 as $set2){
										$supervisor_email[] = $set2['email'];
									}
								}
							}else{
								$select1 = $sql->select();
								$select1->from(array('t1' => 'employee_details'));
								$select1->where(array('t1.id' => $supervisorId));
									
								$stmt1 = $sql->prepareStatementForSqlObject($select1);
								$result1 = $stmt1->execute();
								
								$resultSet1 = new ResultSet();
								$resultSet1->initialize($result1);

								foreach($resultSet1 as $set1){
									$supervisor_email[] = $set1['email'];
								} 
							}
						}
        			}
        		}
        	}
        }
        return $supervisor_email;
	}


	public function getSupervisorIdByRole($supervisor_role, $supervisor_dept)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'users'),
					't1.emp_id = t2.username', array('username', 'role', 'user_status_id'));
		$select->where(array('t2.role' =>$supervisor_role, 't1.departments_id' => $supervisor_dept, 't2.user_status_id' => '1'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$supervisor_ids = array();
		foreach($resultSet as $set){
			$supervisor_ids[] = $set['id'];
		} 
		return $supervisor_ids;
	}

	public function getSupervisorOfficiating($supervisorId, $supervisor_role, $supervisor_dept)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.supervisor_id' => $supervisorId, 't1.supervisor' =>$supervisor_role, 't1.department' => $supervisor_dept, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$officiating_supervisor_id = array();
		foreach($resultSet as $set){
			$officiating_supervisor_id[] = $set['officiating_supervisor'];
		} 
		return $officiating_supervisor_id;
	}


	public function getSupervisorEmail($supervisor_role, $supervisor_dept, $supervisor_org)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'users'))
			   ->join(array('t2' => 'employee_details'),
					't2.emp_id = t1.username', array('email'));
		$select->where(array('t1.role' =>$supervisor_role, 't2.departments_id' => $supervisor_dept, 't2.organisation_id' => $supervisor_org));
			
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


	public function getTravelAuthNo($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->columns(array('travel_auth_no'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$auth_no = NULL;
		foreach($resultSet as $set){
			$auth_no = $set['travel_auth_no'];
		} 
		return $auth_no;
	}

	public function getApprovedTravelApplicantDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
		       ->join(array('t2' => 'employee_details'),
					   't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'email'))
			   ->join(array('t3' => 'employee_details'),
						't3.id = t1.authorizing_officer', array('afirst_name' => 'first_name', 'amiddle_name' => 'middle_name', 'alast_name' => 'last_name'));
		$select->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}



	public function getAdmEmailId($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);

		//get the organisation id of staff
		$select3 = $sql->select();
		$select3->from(array('t1' => 'employee_details'))
				->columns(array('organisation_id'));
		$select3->where(array('t1.id = ? ' => $employee_details_id));
		
		$stmt3 = $sql->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);

		$organisation_id = NULL;
		foreach($resultSet3 as $set3){
			$organisation_id = $set3['organisation_id'];
		} 
		
		$abbr = $this->getOrganisationAbbr($organisation_id);
		if($organisation_id == '1'){
			$adm_role = $abbr.'_ADMINISTRATIVE_DIVISION_HEAD';
		}else{
			$adm_role = $abbr.'_ADMINISTRATIVE_SECTION_HEAD';
		}
		
		$select = $sql->select();
		$select->from(array('t1' => 'users'));
		$select->where(array('t1.role' =>$adm_role, 't1.region' => $organisation_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$username = array();
		foreach($resultSet as $set){
			$username[] = $set['username'];
		} 

		$adm_email = array();

		if(!empty($username)){
			foreach ($username as $key => $value){
				//get the list of employees
				$select2 = $sql->select();
				$select2->from(array('t1' => 'employee_details'))
						->columns(array('email'));
				$select2->where(array('t1.emp_id = ? ' => $value));
				
				$stmt2 = $sql->prepareStatementForSqlObject($select2);
				$result2 = $stmt2->execute();
				
				$resultSet2 = new ResultSet();
				$resultSet2->initialize($result2);
				foreach($resultSet2 as $set1){
					$adm_email[] = $set1;
				} 
			}
		} 

		return $adm_email;
	}

	public function getRegistrarEmailId($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);

		//get the organisation id of staff
		$select3 = $sql->select();
		$select3->from(array('t1' => 'employee_details'))
				->columns(array('organisation_id'));
		$select3->where(array('t1.id = ? ' => $employee_details_id));
		
		$stmt3 = $sql->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);

		$organisation_id = NULL;
		foreach($resultSet3 as $set3){
			$organisation_id = $set3['organisation_id'];
		} 
		
		//$abbr = $this->getOrganisationAbbr($organisation_id);
		if($organisation_id == '1'){
			$adm_role = 'REGISTRAR';
		}else{
			$adm_role = 'REGISTRAR';
		}
		
		$select = $sql->select();
		$select->from(array('t1' => 'users'));
		$select->where(array('t1.role' =>$adm_role));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$username = array();
		foreach($resultSet as $set){
			$username[] = $set['username'];
		} 

		$reg_email = array();

		if(!empty($username)){
			foreach ($username as $key => $value){
				//get the list of employees
				$select2 = $sql->select();
				$select2->from(array('t1' => 'employee_details'))
						->columns(array('email'));
				$select2->where(array('t1.emp_id = ? ' => $value));
				
				$stmt2 = $sql->prepareStatementForSqlObject($select2);
				$result2 = $stmt2->execute();
				
				$resultSet2 = new ResultSet();
				$resultSet2->initialize($result2);
				foreach($resultSet2 as $set1){
					$reg_email[] = $set1;
				} 
			}
		} 

		return $reg_email;
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



	public function getTourApplicant($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('t1.id' => $employee_details_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getTravelApplicant($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name'));
		$select->where(array('t1.id' => $id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getEmployeeDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_authorization'))
                    ->join(array('t2' => 'employee_details'),
                			't2.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                    ->where(array('t1.id = ' .$id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}
	
	/*
	 * Delete Travel Details (given an id)
	 */
	 
	public function deleteTravelDetails($id)
	{
		$action = new Delete('travel_details');
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		return (bool)$result->getAffectedRows();
	}
	
	/*
	 * Get Travel Authorization id
	 */
	 
	public function getTravelAuthorizationId($id)
	{
		$travel_authorization_id = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_details'))
                    ->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$travel_authorization_id = $set['travel_authorization_id'];
		}
		
		return $travel_authorization_id;
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
	
	/**
	* @return array/EmployeeLeave()
	*/
	public function listTravelEmployee($date, $organisation_id, $userrole, $employee_details_id)
	{
		$auth_type = 'Tour';
		$empty = array(); 

		//Get whether the particular user have assigned his/ her officiating
		$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

		if($check_assigned_officiating){
			return;
		}
		else
		{
			$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id); 

			if(!empty($check_authorized_role)){
				//stores values of the departments that user can authorise
				$role_departments = array();
			
				//store values of the user authoriser's department
				$authoriser_department = array(); 

				//authorizee roles
				$authorizee_role = array();
				
				//authorizee emp ids
				$authorizee_emp_ids = array();

				$sql = new Sql($this->dbAdapter);

					//First get the department, organisation and auth type of user.
					$select1 = $sql->select();
					$select1->from(array('t1' => 'user_workflow'))
							->columns(array('role_department', 'department', 'organisation', 'role'));

					$select1->where(array('t1.type = ?' => 'Tour'));
				    $select1->where(array('t1.auth = ?' => $userrole));
				    $select1->where(array('t1.organisation = ?' => $organisation_id));
							
			        $stmt1 = $sql->prepareStatementForSqlObject($select1);
			        $result1 = $stmt1->execute();
			        $resultSet1 = new ResultSet();
			        $resultSet1->initialize($result1);
			       // $tour_staff = array();
			        foreach($resultSet1 as $tmp_data){
			               // $tour_staff[$set1['id']] = $set1['id'];
			                $role_departments[$tmp_data['role_department']] = $tmp_data['role_department']; 
							$authoriser_department[$tmp_data['department']] = $tmp_data['department'];
							$authorizee_role[$tmp_data['role']]  = $tmp_data['role'];
							

			        } 



				$officiating_role = $this->getOfficiatingRole($employee_details_id); 

				if($officiating_role){
							$select3 = $sql->select();
							$select3->from(array('t1' => 'user_workflow'))
									->columns(array('role_department', 'department','type', 'role'));

							$select3->where(array('t1.type = ?' => $auth_type));
						    $select3->where(array('t1.auth = ?' => $officiating_role));
						    $select3->where(array('t1.organisation = ?' => $organisation_id)); 
									
					        $stmt3 = $sql->prepareStatementForSqlObject($select3);
					        $result3 = $stmt3->execute(); 
					        $resultSet3 = new ResultSet();
					        $resultSet3->initialize($result3);
					       // $tour_staff = array();
					        foreach($resultSet3 as $tmp_data){
					                $role_departments[$tmp_data['role_department']] = $tmp_data['role_department']; 
									$authoriser_department[$tmp_data['department']] = $tmp_data['department'];
									$authorizee_role[$tmp_data['role']]  = $tmp_data['role'];
					        }			
						
				//	var_dump($role_departments); echo "<br>";
				//	var_dump($authoriser_department); echo "<br>";
					
		        }
					
		        $authorizee_emp_ids = $this->getEmployeeIdByRoles($authorizee_role, $role_departments);				

			        if(!empty($role_departments)){
			        	$select = $sql->select();
			        	$select->from(array('t1' => 'travel_authorization'))
			        		   ->join(array('t2' => 'employee_details'),
			        				't2.id = t1.employee_details_id', array('departments_id', 'departments_units_id'))
			        		   ->join(array('t3' => 'departments'),
			        				't3.id = t2.departments_id', array('department_name'))
			        		   ->join(array('t4' => 'department_units'),
			        				't4.id = t2.departments_units_id', array('unit_name'));
						$select->where(array('t1.travel_auth_date >= ? ' => $date));
						$select->where(array('t2.departments_units_id' => $role_departments));
						
						if($authorizee_emp_ids){
							$select->where(array('t1.employee_details_id ' => $authorizee_emp_ids));
						}

						$stmt = $sql->prepareStatementForSqlObject($select);
						$result = $stmt->execute();
						
						$resultSet = new ResultSet();
						return $resultSet->initialize($result);
			        }
		        	return $empty;
				}
			}
	}

	/*
	* To check whether the particular logged in role is in user_workflow table or not 
	*/

	private function checkAuthorizedRole($userrole, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow'));
		$select->where(array('t1.auth' => $userrole));
		$select->where(array('t1.organisation' => $organisation_id));
		$select->where->like('type','%Leave');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$role = array();
		foreach($resultSet as $set){
            $role[$set['id']] = $set['id'];
        }
        return $role;

	}


	public function getOfficiatingRole($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('supervisor'));
		$select->where(array('t1.officiating_supervisor = ? ' => $employee_details_id));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//officiating role
		$officiating_role = NULL;

		
		foreach($resultSet as $tmp_data){
			$officiating_role = $tmp_data['supervisor'];
		}
		return $officiating_role;
	}


	/*
	* Get employee ids given roles
	*/

	private function getEmployeeIdByRoles($role, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'users'))
						->join(array('t2' => 'employee_details'), 
								't1.username = t2.emp_id', array('id'));
		$select->where(array('t1.role ' => $role));
		$select->where(array('t2.departments_units_id ' => $departments_units_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['id']] = $set['id'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;

	}


	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function findAll($status, $organisation_id, $userrole, $employee_details_id, $departments_id)
	{
			$auth_type = 'Tour';

			//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);
			if($check_assigned_officiating){
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);
				$employee_tours = array();

				if(!empty($check_authorized_role)){
					$sql = new Sql($this->dbAdapter);
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where(array('t1.type' => $auth_type));
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 
				}
				//get details of departments if username is officiating
				$officiating_role = $this->getOfficiatingRole($employee_details_id);
					
				//Need to redo previous sql statements for officiating role
				if($officiating_role){
					$sql = new Sql($this->dbAdapter);
					$select3 = $sql->select();
					$select3->from(array('t1' => 'user_workflow'))
					->columns(array('role_department','department','type', 'role'));
					$select3->where(array('t1.auth = ? ' => $officiating_role));
					$select3->where(array('t1.organisation = ? ' => $organisation_id));
					$select3->where(array('t1.type' => $auth_type));
					$stmt3 = $sql->prepareStatementForSqlObject($select3);
					
					$result3 = $stmt3->execute();
					$resultSet3 = new ResultSet();
					$resultSet3->initialize($result3);
					foreach($resultSet3 as $tmp_data3){
						$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
					}
				} //var_dump($type_authorisation); die();

				if(!empty($type_authorisation)){
					foreach ($type_authorisation as $type => $value) {
						$applied_type = $type;
						foreach ($value as $role_department => $value2) {
							foreach($value2 as $role){
								$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
								foreach($authorizee_emp_ids as $value3){
								//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'travel_authorization'))
									->join(array('t2' => 'employee_details'), 
										't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.tour_status = ? ' => $status));
									$select2->where(array('t1.travel_auth_date >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_tours[] = $set;
									} 
								}
							}
						}
					}
				} 
				return $employee_tours;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);
				$employee_tours = array();

				if(!empty($check_authorized_role)){
					$sql = new Sql($this->dbAdapter);
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where(array('t1.type' => $auth_type));
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 
				}
				//get details of departments if username is officiating
				$officiating_role = $this->getOfficiatingRole($employee_details_id);
					
					//Need to redo previous sql statements for officiating role
				if($officiating_role){
					$sql = new Sql($this->dbAdapter);
					$select3 = $sql->select();
					$select3->from(array('t1' => 'user_workflow'))
					->columns(array('role_department','department','type', 'role'));
					$select3->where(array('t1.auth = ? ' => $officiating_role));
					$select3->where(array('t1.organisation = ? ' => $organisation_id));
					$select3->where(array('t1.type' => $auth_type));
					$stmt3 = $sql->prepareStatementForSqlObject($select3);
					
					$result3 = $stmt3->execute();
					$resultSet3 = new ResultSet();
					$resultSet3->initialize($result3);
					foreach($resultSet3 as $tmp_data3){
						$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
					}
				} //var_dump($type_authorisation); die();

				if(!empty($type_authorisation)){
					foreach ($type_authorisation as $type => $value) {
						$applied_type = $type;
						foreach ($value as $role_department => $value2) {
							foreach($value2 as $role){
								$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
								foreach($authorizee_emp_ids as $value3){
								//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'travel_authorization'))
									->join(array('t2' => 'employee_details'), 
										't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.tour_status = ? ' => $status));
									$select2->where(array('t1.travel_auth_date >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_tours[] = $set;
									} 
								}
							}
						}
					}
				} 
				return $employee_tours;
			}	        
	}


	public function getSupervisorDepartmentId($department_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'department_units'))
					->columns(array('departments_id'))
                    ->where(array('t1.id' =>$department_units_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$departments_id = NULL;
		foreach($resultSet as $set)
		{
			$departments_id = $set['departments_id'];
		}
		return $departments_id;
	}



	public function getEmployeeIdBySupervisorRole($role, $departments_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
		$select->where(array('t1.supervisor' => $role));
		$select->where(array('t1.department' => $departments_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['officiating_supervisor']] = $set['officiating_supervisor'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;
	}




	// Function to check officiating
	public function checkStaffOfficiating($employee_details_id)
	{
		$date=date('Y-m-d'); 


		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('id', 'officiating_supervisor','from_date','to_date','supervisor', 'supervisor_id', 'department'))
                    ->where(array('t1.officiating_supervisor' => $employee_details_id, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		//$officiating = array();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

		/*foreach($resultSet as $set){
	                $officiating[$set['id']] = $set['id'];
	                 $officiating[$set['officiating_supervisor']] = $set['officiating_supervisor'];
	        }*/
	}

	// Function to check whether the user have assigned his/ her own officiating
	public function checkOwnAssignedOfficiating($userrole)
	{
		$date = date('Y-m-d');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('id', 'officiating_supervisor','from_date','to_date','supervisor', 'supervisor_id', 'department'))
                    ->where(array('t1.supervisor' => $userrole, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$officiated = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$officiated = $set['officiating_supervisor'];
		}

		return $officiated;
	}

	
	/**
	* Find details of employees that have applied for leave
	*/
	
	public function findEmployeeDetails($empIds)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('id ' => $empIds));
		$select->columns(array('id','first_name','middle_name','last_name','emp_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
			
		$employeeData = array();
		foreach($resultSet as $set)
		{
			$employeeData[$set['id']] = $set['first_name'] . ' '. $set['middle_name'] .' '. $set['last_name'];
			$employeeData['emp_id' . $set['id']] = $set['emp_id'];
		}
		return $employeeData;

	}


	public function getEmployeeList($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','first_name','middle_name','last_name','emp_id'))
					->join(array('t2' => 'emp_position_level'), 
                            't1.id = t2.employee_details_id', array('position_level_id'))
					->join(array('t3' => 'position_level'), 
                            't2.position_level_id = t3.id', array('position_level'))
                    ->where(array('t1.organisation_id' =>$organisation_id));
					//->where(array('t3.position' =>$employee_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$staffList = array();
		foreach($resultSet as $set)
		{
			$staffList[$set['id']] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['emp_id'].')';
		}
		return $staffList;
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
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id', 'departments_units_id', 'departments_id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	//Function to generate travel auth no
	public function generateTravelAuthNo($organisation_id)
	{
		$sql1 = new Sql($this->dbAdapter);
      $select1 = $sql1->select();

      $select1->from(array('t1' => 'organisation'));
      $select1->columns(array('organisation_code', 'abbr'));
      $select1->where(array('t1.id = ?' => $organisation_id));
      $stmt1 = $sql1->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);
        
        $code = NULL;
        foreach($resultSet1 as $set1)
            $code = $set1['abbr'];

        
        $Year = date('Y');
        $format = $code.substr($Year, 2).date('m');
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'travel_authorization'))
               ->columns(array('travel_auth_no'));
        $select->where->like('travel_auth_no',''.$format.'%');
        $select->order('travel_auth_no DESC');
        $select->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $travel_auth_no = NULL;
        
        foreach($resultSet as $set)
            $travel_auth_no = $set['travel_auth_no'];
        
        //first voucher of the year
        if($travel_auth_no == NULL){
            $generated_id = $code.substr(date('Y'),2).date('m').'0001';
        }
        else{
            //need to get the last 4 digits and increment it by 1 and convert it back to string
            $number = substr($travel_auth_no, -4);
            $number = (int)$number+1;
            $number = strval($number);
            while (mb_strlen($number)<4)
                $number = '0'. strval($number);
            
            $generated_id = $code.substr(date('Y'),2).date('m').$number;
        }
        
        return $generated_id;
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
    
    public function saveTravelPaymentDetails(TravelPaymentDetails $travelPaymentDetails) {
      $travelPaymentDetailsData = $this->hydrator->extract($travelPaymentDetails);
      unset($travelPaymentDetailsData['id']);
      
      if($travelPaymentDetails->getId()) {
          // ID present, so it is an update
          $travelPaymentDetailsData['updated_at'] = date('Y-m-d H:i:s');
          if ($travelPaymentDetailsData['payment_Type'] === '1') {
            $travelPaymentDetailsData['cheque_No'] = '';
            $travelPaymentDetailsData['dd_No'] = '';
          } else if ($travelPaymentDetailsData['payment_Type'] === '2') {
            $travelPaymentDetailsData['dd_No'] = '';
          } else if ($travelPaymentDetailsData['payment_Type'] === '3') {
            $travelPaymentDetailsData['cheque_No'] = '';
          }

          $action = new Update('travel_payment_details');
          $action->set($travelPaymentDetailsData);
          $action->where(array('id = ?' => $travelPaymentDetails->getId()));
      } else {
          $travelPaymentDetailsData['created_at'] = $travelPaymentDetailsData['updated_at'] = date('Y-m-d H:i:s');
          // ID is not preset, so it is an insert
          $action = new Insert('travel_payment_details');
          $action->values($travelPaymentDetailsData);
      }

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $result = $stmt->execute();

      if($result instanceof ResultInterface) {
          if($newId = $result->getGeneratedValue()){
              //when a value has been generated, set it on the object
              $travelPaymentDetails->setId($newId);
          }
          return $travelPaymentDetails;
      } 
      throw new \Exception("Database Error");
    }
    
    public function fetchTravelPaymentList($field, $id) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'travel_payment_details'))
                ->join(array('t2' => 'travel_authorization'), 't1.travel_authorization_id = t2.id', array('purpose_of_tour', 'travel_auth_no', 'travel_auth_date', 'no_of_days', 'estimated_expenses', 'advance_required', 'tour_status'))
                ->where(array('t1.'.$field => $id));
        $select->columns(array('*', 'pending_amount' => new Expression('t2.estimated_expenses - IFNULL((SELECT SUM(amount) from travel_payment_details GROUP BY travel_authorization_id having travel_authorization_id=t2.id), 0)')));
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $data = $resultSet->initialize($result)->toArray();
        
        $travelPayment = [];
        
        foreach ($data as $row) {
          $travelPayment[] = $row;
        }
        
        return $travelPayment;
    }
    
    public function updateTravelAuthorizationStatus($id) {
      $payments = $this->fetchTravelPaymentList('travel_authorization_id', $id);
      $allStatus = array_unique(array_column($payments, 'status'));
      $pendingAmount = array_unique(array_column($payments, 'pending_amount'));
      
      $action = new Update('travel_authorization');
      if (intval($pendingAmount[0]) === 0 && count($allStatus) === 1 && $allStatus[0] === 'Completed') {
        $action->set(['tour_status' => 'Completed']);
      } else {
        $action->set(['tour_status' => 'Approved']);
      }
      $action->where(array('id' => intval($id)));

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $stmt->execute();
    }
    
    public function deletePaymentDetails($id)
    {
      $action = new Delete('travel_payment_details');
      $action->where(array('id = ?' => $id));

      $sql = new Sql($this->dbAdapter);
      $stmt = $sql->prepareStatementForSqlObject($action);
      $result = $stmt->execute();

      return (bool)$result->getAffectedRows();
    }
        
        
}
