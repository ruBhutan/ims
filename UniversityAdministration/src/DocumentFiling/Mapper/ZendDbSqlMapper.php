<?php

namespace DocumentFiling\Mapper;

use DocumentFiling\Model\DocumentFiling;
use DocumentFiling\Model\FilingType;
use DocumentFiling\Model\FilingDocument;

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
//use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;


class ZendDbSqlMapper implements DocumentFilingMapperInterface
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
	 * @var \DocumentFiling\Model\DocumentFilingInterface
	*/
	protected $documentFilingPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			DocumentFiling $documentFilingPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->documentFilingPrototype = $documentFilingPrototype;
	}

    public function findAll($tableName, $columnName, $id, $department_id)
    {

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        if ($tableName == 'newspaper') {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id','newspaper_type', 'newspaper_date', 'dzongkha_newspaper', 'english_newspaper','recorded_by','created_at'));
            $select->order(array('t1.newspaper_date DESC')); 
        }

        if ($tableName == 'meeting_type') {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id','meeting','meeting_abbr', 'organisation_id','department_id', 'status', 'employee_details_id','created_at'));
            $select->where(array('organisation_id = ?' => $columnName));
            $select->where(array('department_id = ?' => $department_id));
            $select->order(array('t1.meeting DESC')); 
        }

        if ($tableName == 'employee_details') {
            $select->from(array('t1' => $tableName));
            $select->columns(array('departments_id'));
            $select->where(array('organisation_id = ?' => $columnName));
            $select->where(array('id = ?' => $id));
        }
        if ($tableName == 'department_filing_documents') {
            if ($columnName == '0') {
                $select->from(array('t1' => $tableName));
                $select->columns(array('id','filing_details', 'meeting_type_id', 'filing_date','recorded_by','evidence_file','created_at'))
                     ->join(array('t2' => 'employee_details'), 
                                't1.recorded_by = t2.id', array('emp_id','first_name','middle_name','last_name'))
                     ->join(array('t3' => 'meeting_type'), 
                                't1.meeting_type_id = t3.id', array('organisation_id','meeting','meeting_abbr'));
                $select->where(array('t3.organisation_id = ? ' => '0'));
                $select->where(array('t3.department_id = ? ' => $department_id));
                $select->order(array('t1.filing_date DESC')); 
                # code...
            } else {
                $select->from(array('t1' => $tableName));
                $select->columns(array('id','filing_details', 'meeting_type_id', 'filing_date','recorded_by','evidence_file','created_at'))
                     ->join(array('t2' => 'employee_details'), 
                                't1.recorded_by = t2.id', array('emp_id','first_name','middle_name','last_name'))
                     ->join(array('t3' => 'meeting_type'), 
                                't1.meeting_type_id = t3.id', array('organisation_id','meeting','meeting_abbr'));
                $select->where(array('t3.organisation_id = ? ' => $columnName));
                $select->where(array('t3.department_id = ? ' => $department_id));
                $select->order(array('t1.filing_date DESC')); 

            }
        }
       

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        //Need to make the resultSet as an array
        // e.g. 1=> Objective 1, 2 => Objective etc.
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
    public function saveDetails(FilingType $filingtypeObject)
    {
        //var_dump($meetingtypeObject); die();
        $filingtypeData = $this->hydrator->extract($filingtypeObject);

        unset($filingtypeData['id']);

        if($filingtypeObject->getId()) {
            //ID present, so it is an update
            $action = new Update('meeting_type');
            $action->set($filingtypeData);
            $action->where(array('id = ?' => $filingtypeObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('meeting_type');
            $action->values($filingtypeData);
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        
        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $filingtypeObject->setId($newId);
            }
            return $filingtypeObject;
        }
        
        throw new \Exception("Database Error");
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
            $select->columns(array('id','departments_id'));
            //$select->columns(array('departments_id'));
        }

        else if($tableName == 'student'){
            $select->from(array('t1' => $tableName));
            $select->where(array('student_id' =>$username));
            $select->columns(array('id'));
        }
        else if($tableName == 'job_applicant'){
            $select->from(array('t1' => $tableName));
            $select->where(array('email' =>$username));
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
        else if($tableName == 'job_applicant'){
            $select->from(array('t1' => $tableName));        
            $select->where(array('email' =>$username));
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
        else if($usertype == 4){
            $select->from(array('t1' => 'job_applicant'));
            $select->where(array('email' => $username));
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

        if($usertype == 4){
            $select->from(array('t1' => 'job_applicant'));
            $select->where(array('t1.email' => $username));
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

    public function saveFilingDocument(FilingDocument $filingdocumentObject)
    {
        $filingdocumentData = $this->hydrator->extract($filingdocumentObject);

        //var_dump($filingdocumentData); die;

        $evidence_file_id = $filingdocumentData['id'];

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'department_filing_documents')) 
                    ->columns(array('id','evidence_file'));
        $select->where(array('t1.id' => $evidence_file_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $id = $resultSet->initialize($result);

        foreach($resultSet as $set){
            $evidence_file = $set['evidence_file'];
        }
        if($evidence_file != NULL){
            $evidence_file_name = $filingdocumentData['evidence_file'];           
        } else {
            $evidence_file_name = $filingdocumentData['evidence_file'];
            $filingdocumentData['evidence_file'] = $evidence_file_name['tmp_name'];
            
        }

        $filingdocumentData['filing_date'] = date("Y-m-d", strtotime(substr($filingdocumentData['filing_date'],0,10)));
        //var_dump($filingdocumentData); die();
        if($filingdocumentObject->getId()) {
            //ID present, so it is an update    
            //var_dump($employeetaskModel); die();                              
            $action = new Update('department_filing_documents');
            $action->set($filingdocumentData);
            $action->where(array('id = ?' => $filingdocumentObject->getId()));
        } else {
            //ID is not present, so its an insert
            unset($filingdocumentData['id']);

            $action = new Insert('department_filing_documents');
            $action->values($filingdocumentData);
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if($result instanceof ResultInterface) {
            if($newId = $result->getGeneratedValue()){
                //when a value has been generated, set it on the object
                echo $filingdocumentObject->setId($newId);
            }
            return $filingdocumentObject;
        }
        
        throw new \Exception("Database Error");
    }

    public function getFileName($table,$file_id)
    { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if ($table == 'department_filing_documents') {
            $select->from(array('t1' => $table))
                ->where(array('t1.id' => $file_id));
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $fileLocation;
        if ($table == 'department_filing_documents') {
            foreach($resultSet as $set)
            {
                $fileLocation = $set['evidence_file'];
            }
        }

        return $fileLocation;

    }

    public function getFileName1($file_id)
    { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'newspaper')) 
                ->where(array('t1.id' => $file_id));
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $fileLocation;
        foreach($resultSet as $set)
        {
            $fileLocation = $set['dzongkha_newspaper'];
        }

        return $fileLocation;

    }

    public function listSelectData($tableName, $organisation_id, $department_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        //var_dump($department_id); die();

        $select->from(array('t1' => $tableName));
        if($tableName == 'meeting_type'){
            if($organisation_id == '1'){
                $select->columns(array('id','meeting'));
                $select->where(array('t1.organisation_id <= 1'));
                $select->where(array('t1.department_id' => $department_id));
            }else{
                $select->columns(array('id','meeting'));
                $select->where(array('t1.organisation_id' => $organisation_id));
            }
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        
        //Need to make the resultSet as an array
        // e.g. 1=> Objective 1, 2 => Objective etc.
        
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['id']] = $set['meeting'];
        }
        return $selectData;
            
    }

    public function listSelectData1($tableName, $id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'department_filing_documents')) 
            ->columns(array('filing_document_id'=>'id','filing_details','filing_date','recorded_by', 'evidence_file'))
            ->join(array('t2' => 'meeting_type'), 
                    't1.meeting_type_id = t2.id', array('id','meeting'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        //Need to make the resultSet as an array
        // e.g. 1=> Objective 1, 2 => Objective etc.
        
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['id']] = $set['meeting'];
        }
        return $selectData;
    }

    public function getFilingDocumentDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'department_filing_documents')); 
        $select->where(array('id = ?' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function getFilingTypeDetails($id)
    {   
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'meeting_type')); 
        $select->where(array('id = ?' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }
}