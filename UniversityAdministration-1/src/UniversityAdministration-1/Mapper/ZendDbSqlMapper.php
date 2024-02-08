<?php

namespace UniversityAdministration\Mapper;

use UniversityAdministration\Model\UniversityAdministration;

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
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements UniversityAdministrationMapperInterface
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
	 * @var \UniversityAdministration\Model\UniversityAdministrationInterface
	*/
	protected $universityAdministrationPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			UniversityAdministration $universityAdministrationPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->universityAdministrationPrototype = $universityAdministrationPrototype;
	}

    /*
    * Getting the id for username
    */
    
    public function getUserDetailsId($tableName, $username)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'employee_details'){
            $select->from(array('t1' => $tableName));
            $select->where(array('emp_id' =>$username));
            $select->columns(array('id'));
        }

        else if($tableName == 'student'){
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
    
    public function getOrganisationId($tableName, $username)
    {
        $tableName;
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'employee_details'){
            $select->from(array('t1' => $tableName));        
            $select->where(array('emp_id' =>$username));
            $select->columns(array('organisation_id'));
        }

        else if($tableName == 'student'){
            $select->from(array('t1' => $tableName));        
            $select->where(array('student_id' =>$username));
            $select->columns(array('organisation_id'));
        }
            
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
}