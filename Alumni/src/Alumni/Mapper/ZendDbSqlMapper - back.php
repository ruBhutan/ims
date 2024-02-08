<?php

namespace AlumniMember\Mapper;

use AlumniMember\Model\AlumniMember;
use AlumniMember\Model\AlumniStudent;
use AlumniMember\Model\Alumni;
use AlumniMember\Model\AlumniRegistration;
use AlumniMember\Model\AlumniEvent;
use AlumniMember\Model\AlumniResource;
use AlumniMember\Model\AlumniProfile;
use AlumniMember\Model\UpdateAlumni;
use AlumniMember\Model\AlumniEnquiry;
use AlumniMember\Model\AlumniFaqs;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AlumniMemberMapperInterface
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
	 * @var \GoodsTransaction\Model\GoodsTransactionInterface
	*/
	protected $alumniMemberPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			//\stdClass $goodsTransactionPrototype,
			AlumniMember $alumniMemberPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->alumniMemberPrototype = $alumniMemberPrototype;
	}
	
	/**
	* @param int/String $id
	* @return GoodsTransaction
	* @throws \InvalidArgumentException
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
	
	public function findAlumniNewRegistered($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('alumni');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniMemberPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	
	
	public function findAllAlumniNewRegistered($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni'))
             ->join(array('t2' => 'alumni_programmes'),
				't2.id = t1.alumni_programmes_id', array('programme_name'))
			 ->where(array('t1.organisation_id = ?' => $organisation_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
    
        
        public function findAlumniNewRegisteredDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'alumni')); //base table


            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }
		
		public function saveAlumniNewRegistered(AlumniRegistration $alumniRegistrationObject)
		{
		$alumniRegistrationData = $this->hydrator->extract($alumniRegistrationObject);
		unset($alumniRegistrationData['id']);
        unset($alumniRegistrationData['programme_Name']);


		
		if($alumniRegistrationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni');
			$action->set($alumniRegistrationData);
			$action->where(array('id = ?' => $alumniRegistrationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni');
			$action->values($alumniRegistrationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniRegistrationObject->setId($newId);
			}
			return $alumniRegistrationObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
    * Search for registered alumni members
    */
    public function getRegisteredMemberList($memProgramme, $memYear, $memName)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni'))
               ->join(array('t2' => 'alumni_programmes'),
                    't2.id = t1.alumni_programmes_id', array('programme_name'))               
               ->where(array('t1.alumni_programmes_id' => $memProgramme));

        if($memYear){
            $select->where->like('graduation_year', $memYear.'%');
            $select->where(array('t1.alumni_programmes_id = ?' => $memProgramme));
        }
        if($memName){
            $select->where->like('first_name', $memName.'%');
            $select->where(array('t1.alumni_programmes_id = ?' => $memProgramme));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result); 
    }

	public function findUpdatedAlumni($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('alumni');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniMemberPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	
	
	public function findAllUpdatedAlumni()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni')); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
    
        
        public function findUpdatedAlumniDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'alumni')); //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }
		
		
	
	
		public function saveUpdatedAlumni(UpdateAlumni $updateAlumniObject)
		{
		$updateAlumniData = $this->hydrator->extract($updateAlumniObject);
		unset($updateAlumniData['id']);
		
		if($updateAlumniObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni');
			$action->set($updateAlumniData);
			$action->where(array('id = ?' => $updateAlumniObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni');
			$action->values($updateAlumniData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $updateAlumniObject->setId($newId);
			}
			return $updateAlumniObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function findAlumniProfile($id)
	{
            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'alumni'))
            	   ->join(array('t2' => 'alumni_programmes'),
            	   		't2.id = t1.alumni_programmes_id', array('programme_name'))
                   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniMemberPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	

	
	public function findAllAlumniProfile()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni')); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
    
        
        public function findAlumniProfileDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'alumni'))
             ->join(array('t2' => 'alumni_programmes'),
               't2.id = t1.alumni_programmes_id', array('programme_name')); //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }
	
		public function saveAlumniProfile(AlumniProfile $alumniProfileObject)
		{
		$alumniProfileData = $this->hydrator->extract($alumniProfileObject);
		unset($alumniProfileData['id']);
		unset($alumniProfileData['programme_Name']);
		
		if($alumniProfileObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni');
			$action->set($alumniProfileData);
			$action->where(array('id = ?' => $alumniProfileObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni');
			$action->values($alumniProfileData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniProfileObject->setId($newId);
			}
			return $alumniProfileObject;
		}
		
		throw new \Exception("Database Error");
	}

public function findAlumni($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('alumni');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniMemberPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	
	
	public function findAllAlumni()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni'))
            ->join(array('t2' => 'student'),
               't2.id = t1.student_id', array('first_name','middle_name', 'last_name', 'cid','date_of_birth','graduation_year'))
               ->join(array('t3' => 'alumni_programmes'), // join table with alias
                     't3.id = t1.alumni_programmes_id', array('programme_name'));  //join expression; // 

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
    
	
	public function findAlumniDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'alumni')); //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }
		
    
	public function saveAlumni(Alumni $alumniObject)
	{
		$alumniData = $this->hydrator->extract($alumniObject);
		unset($alumniData['id']);
		
		if($alumniObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni');
			$action->set($alumniData);
			$action->where(array('id = ?' => $alumniObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni');
			$action->values($alumnilData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniObject->setId($newId);
			}
			return $alumniObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function findAlumniEvent($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('alumni_event');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniMemberPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	
	
	public function findAllAlumniEvent()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_event'))
            ->join(array('t2' => 'alumni_programmes'),
              't2.id = t1.alumni_programmes_id', array('programme_name'))
           	->join(array('t3' => 'alumni'),
              	't3.id = t1.graduation_year_id', array('graduation_year'));
            
              
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
        
 
        
        public function findAlumniEventDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'alumni_event'));
                //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }
		
	
	
	public function saveAlumniEvent(ALumniEvent $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);
		
		if($alumniMemberObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni_event');
			$action->set($alumniMemberData);
			$action->where(array('id = ?' => $alumniMemberObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni_event');
			$action->values($alumniMemberData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniMemberObject->setId($newId);
			}
			return $alumniMemberObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	public function saveAlumniResource(AlumniResource $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);
		
		if($alumniMemberObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni_resource');
			$action->set($alumniMemberData);
			$action->where(array('id = ?' => $alumniMemberObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni_resource');
			$action->values($alumniMemberData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniMemberObject->setId($newId);
			}
			return $alumniMemberObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	public function listAllAlumniResource()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_resource'));
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
	
	public function saveAlumniEnquiry(AlumniEnquiry $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);
		unset($alumniMemberData['alumni_Id']);
		
		if($alumniMemberObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni_academic_help');
			$action->set($alumniMemberData);
			$action->where(array('id = ?' => $alumniMemberObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni_academic_help');
			$action->values($alumniMemberData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniMemberObject->setId($newId);
			}
			return $alumniMemberObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	public function listAllAlumniEnquiry()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_academic_help'))
            ->where (array('t1.enquiry_status' => 'Pending'));
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
	
	
	public function saveAlumniFaqs(AlumniFaqs $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);
		
		
		if($alumniMemberObject->getId()) {
			//ID present, so it is an update
			$action = new Update('alumni_academic_faq');
			$action->set($alumniMemberData);
			$action->where(array('id = ?' => $alumniMemberObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('alumni_academic_faq');
			$action->values($alumniMemberData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniMemberObject->setId($newId);
			}
			return $alumniMemberObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	public function listAllAlumniFaqs()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_academic_faq'));
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


    // 
    public function updateAlumniEnquiry($status, $previousStatus, $id, $organisation_id)
    {
        //need to get the organisaiton id
        //$organisation_id = 1;
        $alumniMemberData['enquiry_status'] = $status;
        $action = new Update('alumni_academic_help');
        $action->set($alumniMemberData);
        if($previousStatus != NULL){
            $action->where(array('enquiry_status = ?' => $previousStatus, 'organisation_id' => $organisation_id));
        } elseif($id != NULL){
            $action->where(array('id = ?' => $id));
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        return;
    }
	
	
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName))
			   ->where(array('t1.organisation_id =? ' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
			
		//Need to make the resultSet as an array
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	} 
	
	public function getAllAlumniStudent($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'alumni_programmes'),
                    't2.id = t1.alumni_programmes_id', array('programme_name'))
					->where(array('t1.organisation_id = ?' => $organisation_id));               
        
        $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniMemberPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
			
    }
	
}