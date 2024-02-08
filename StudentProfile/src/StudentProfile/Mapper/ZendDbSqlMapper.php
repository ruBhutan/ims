<?php

namespace StudentProfile\Mapper;

use StudentProfile\Model\StudentProfile;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentProfileMapperInterface
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
	protected $studentProfilePrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentProfile $studentProfilePrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->studentProfilePrototype = $studentProfilePrototype;
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
    *To get the list of students to view their details
    **/
    public function getStudentList($stdName, $stdId, $stdProgramme, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
               	    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'student_type'),
           			't3.id = t1.scholarship_type', array('student_type'))
               ->join(array('t4' => 'student_category'),
           			't4.id = t1.student_category_id', array('student_category'))
               ->where(array('t1.student_category_id is NOT NULL'));
        //$select->column(array('id'));
                          
        
        if($stdName){
            $select->where->like('first_name','%'.$stdName.'%');
            $select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
        if($stdId){
            $select->where(array('student_id' =>$stdId));
            $select->where(array('t1.organisation_id = ?' => $organisation_id));
        }
        if($stdProgramme){
            $select->where->like('t2.programme_name', $stdProgramme.'%');
            $select->where(array('t1.organisation_id = ?' => $organisation_id));
        }


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    /*
    *To student details by id
    **/
    public function getStudentDetails($id)
    {
    	$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
               ->join(array('t3' => 'organisation'), // join table with alias
                    't3.id = t1.organisation_id', array('organisation_name'))
               ->join(array('t4' => 'student_type'), // join table with alias
                    't4.id = t1.scholarship_type', array('student_type'))
               ->join(array('t5' => 'student_category'),
           			't5.id = t1.student_category_id', array('student_category'))
               ->join(array('t6' => 'dzongkhag'),
           			't6.id = t1.dzongkhag', array('dzongkhag_name'))
               ->join(array('t7' => 'gewog'),
           			't7.id = t1.gewog', array('gewog_name'))
               ->join(array('t8' => 'village'),
           			't8.id = t1.village', array('village_name'))
               ->join(array('t9' => 'student_guardian_details'),
           			't1.id = t9.student_id', array('guardian_name', 'guardian_occupation', 'guardian_relation', 'guardian_contact_no', 'guardian_email_address', 'guardian_present_address', 'guardian_village', 'guardian_gewog', 'guardian_dzongkhag'))
               ->join(array('t10' => 'village'),
           			't10.id = t9.guardian_village', array('guardian_village'=>'village_name'))
               ->join(array('t11' => 'gewog'),
           			't11.id = t9.guardian_gewog', array('guardian_gewog'=>'gewog_name'))
               ->join(array('t12' => 'dzongkhag'),
           			't12.id = t9.guardian_dzongkhag', array('guardian_dzongkhag'=>'dzongkhag_name'))
           	   ->join(array('t13' => 'student_parents_details'),
           			't1.id = t13.student_id', array('father_name', 'father_cid', 'father_nationality', 'father_house_no', 'father_thram_no', 'father_dzongkhag', 'father_gewog', 'father_village', 'father_occupation', 'mother_name', 'mother_cid', 'mother_nationality', 'mother_house_no', 'mother_thram_no', 'mother_dzongkhag', 'mother_gewog', 'mother_village', 'mother_occupation', 'parents_present_address', 'parents_contact_no'))
           	  ->join(array('t14' => 'village'),
           			't14.id = t13.father_village', array('father_village'=>'village_name')) 
           	  ->join(array('t15' => 'gewog'),
           			't15.id = t13.father_gewog', array('father_gewog'=>'gewog_name')) 
           	  ->join(array('t16' => 'dzongkhag'),
           			't16.id = t13.father_dzongkhag', array('father_dzongkhag'=>'dzongkhag_name'))
           	  ->join(array('t17' => 'village'),
           			't17.id = t13.mother_village', array('mother_village'=>'village_name')) 
           	  ->join(array('t18' => 'gewog'),
           			't18.id = t13.mother_gewog', array('mother_gewog'=>'gewog_name'))
           	  ->join(array('t19' => 'dzongkhag'),
           			't19.id = t13.mother_dzongkhag', array('mother_dzongkhag'=>'dzongkhag_name'))
           	  ->join(array('t20' => 'student_previous_school_details'),
           			't1.id = t20.student_id', array('previous_institution', 'aggregate_marks_obtained', 'from_date', 'to_date', 'previous_education'))
               ->where(array('t1.id =' .$id));  //join expression; //   

                $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();      

       if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->studentProfilePrototype);
            }

            throw new \InvalidArgumentException("Item Category with given ID: ($id) not found");
	}


	/*
    *To get the list of students to view their details
    **/
    public function getStudentPreviousDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'student_previous_school_details'),
               	    't1.id = t2.student_id', array('previous_institution', 'aggregate_marks_obtained', 'from_date', 'to_date', 'previous_education'))
               ->where(array('t1.id =' .$id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
}