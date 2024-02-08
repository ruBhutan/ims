<?php

namespace ExternalExaminer\Mapper;

use ExternalExaminer\Model\ExternalExaminer;
use ExternalExaminer\Model\ExternalExaminerApproval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ExternalExaminerMapperInterface
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
	protected $externalExaminerPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			ExternalExaminer $externalExaminerPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->externalExaminerPrototype = $externalExaminerPrototype;
	}
	
	/**
	* @param int/String $id
	* @return EmpWorkForceProposal
	* @throws \InvalidArgumentException
	*/
	
	public function find($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'external_examiners'))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->join(array('t3'=>'organisation'),
                            't2.organisation_id = t3.id', array('organisation_id'=>'id','organisation_name'));
		$select->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function findAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'external_examiners'))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->join(array('t3'=>'organisation'),
                            't2.organisation_id = t3.id', array('organisation_name'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        		
	/**
	 * 
	 * @param type $EmpWorkForceProposalInterface
	 * 
	 * to save work force proposals
	 */
	
	public function saveDetails(ExternalExaminer $externalExaminerObject, $form_data)
	{
		$externalExaminerData = $this->hydrator->extract($externalExaminerObject);
		unset($externalExaminerData['id']);
		unset($externalExaminerData['organisation_Id']);
		$externalExaminerData['programmes_Id'] = $form_data;

		$externalExaminerData['from_Date'] = date("Y-m-d", strtotime(substr($externalExaminerData['from_Date'],0,10)));
		$externalExaminerData['to_Date'] = date("Y-m-d", strtotime(substr($externalExaminerData['to_Date'],0,10)));
		//need to work on file upload
		//need to get the file locations and store them in database
		$evidence_file = $externalExaminerData['evidence_File'];
		$externalExaminerData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($externalExaminerObject->getId()) {
			//ID present, so it is an update
			$action = new Update('external_examiners');
			$action->set($externalExaminerData);
			$action->where(array('id = ?' => $externalExaminerObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('external_examiners');
			$action->values($externalExaminerData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $externalExaminerObject->setId($newId);
			}
			return $externalExaminerObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function updateExternalExaminer(ExternalExaminer $externalExaminerObject, $form_data)
	{
		$externalExaminerData = $this->hydrator->extract($externalExaminerObject);
		//unset($externalExaminerData['id']);
		unset($externalExaminerData['organisation_Id']);
		$externalExaminerData['programmes_Id'] = $form_data;

		$externalExaminerData['from_Date'] = date("Y-m-d", strtotime(substr($externalExaminerData['from_Date'],0,10)));
		$externalExaminerData['to_Date'] = date("Y-m-d", strtotime(substr($externalExaminerData['to_Date'],0,10)));
		//need to work on file upload
		//need to get the file locations and store them in database
		$evidence_file = $externalExaminerData['evidence_File'];
		$externalExaminerData['evidence_File'] = $evidence_file['tmp_name'];
		
		if($externalExaminerData['evidence_File'] == NULL){
			$externalExaminerData['evidence_File'] = $this->getEvidenceFile($externalExaminerData['id']);
		}else{
			$externalExaminerData['evidence_File'] = $externalExaminerData['evidence_File'];
		}

		//ID present, so it is an update
		$action = new Update('external_examiners');
		$action->set($externalExaminerData);
		$action->where(array('id = ?' => $externalExaminerObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function getEvidenceFile($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'external_examiners'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$evidence_file = NULL;
		foreach ($resultSet as $set) {
			$evidence_file = $set['evidence_file'];
		}
		return $evidence_file;
	}
	
	/*
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->where(array('emp_id' =>$username));
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


	public function getUserDetails($username, $usertype)
	{
		$name = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($usertype == 1){
			$select->from(array('t1' => 'employee_details'));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('first_name', 'middle_name', 'last_name'));
		}

		else if($usertype == 2){
			$select->from(array('t1' => 'student'));
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
	* Get the list of External Examiner after search
	*/
	
	public function getExternalExaminersList($data)
	{
		$programmes_id = $data['programmes_id']; 
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'external_examiners'))
					->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'))
                    ->join(array('t3'=>'organisation'),
                            't2.organisation_id = t3.id', array('organisation_name'))
					->where('t1.programmes_id = ' .$programmes_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	    
	/**
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

	public function getFileName($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'external_examiners'));
		$select->where(array('t1.id' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$file = NULL;
		foreach($resultSet as $set)
		{
			$file = $set['evidence_file'];
		}
		return $file;
	}
	
	/*
	* Return an id 
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($programme_name, $organisation_id)
	{
		//note: $department_name takes various parameters. Check calling function 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'programmes'))
			->columns(array('id'));
		$select->where->like('t1.programme_name','%'.$programme_name.'%');
		$select->where('t1.organisation_id = ' .$organisation_id);
		
		
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
        
}