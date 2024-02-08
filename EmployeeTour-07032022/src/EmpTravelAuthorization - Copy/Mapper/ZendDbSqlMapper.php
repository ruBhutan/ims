<?php

namespace EmpTravelAuthorization\Mapper;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
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


		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->empTravelAuthorizationPrototype);
		}

		throw new \InvalidArgumentException("Travel with given ID: ($id) not found");
	}
	
	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function findAll()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization')); 
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$resultSet = new HydratingResultSet($this->hydrator, $this->empTravelAuthorizationPrototype);
				return $resultSet->initialize($result); 
		}

		return array();
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
                    ->where('t1.id = ' .$id);
		
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
	
	public function saveDetails(EmpTravelAuthorization $empTravelAuthorizationObject)
	{
		$empTravelAuthorizationData = $this->hydrator->extract($empTravelAuthorizationObject);
		unset($empTravelAuthorizationData['id']);
		
		$empTravelDetailsData = $empTravelAuthorizationData['emptraveldetails'];
		unset($empTravelAuthorizationData['emptraveldetails']);

		
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
				//the following loop is to insert action plan
				if($empTravelDetailsData != NULL)
				{
					foreach($empTravelDetailsData as $value)
					{
						$action = new Insert('travel_details');
						$action->values(array(
							'from_station'=> $value->getFrom_Station(),
							'from_date' => $value->getFrom_Date(),
							'to_station' => $value->getTo_Station(),
							'to_date'=> $value->getTo_Date(),
							'mode_of_travel'=> $value->getMode_Of_Travel(),
							'halt_at' => $value->getHalt_At(),
							'purpose_of_tour' => $value->getPurpose_Of_Tour(),
							'travel_authorization_id' => $newId,
						));
						
						$sql = new Sql($this->dbAdapter);
						$stmt = $sql->prepareStatementForSqlObject($action);
						$result = $stmt->execute();
					}
				}
			}
			return $empTravelAuthorizationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	* @return array/EmployeeLeave()
	*/
	public function listTravelEmployee($date)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'travel_authorization'));
		$select->where(array('travel_auth_date >= ? ' => $date));
		$select->columns(array('employee_details_id'));
		$select->group('employee_details_id');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

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

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else {
			$select->where(array('student_id' =>$username));
		}
		$select->columns(array('id'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
        
}