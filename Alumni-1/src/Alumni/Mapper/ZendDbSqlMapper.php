<?php

namespace Alumni\Mapper;

//use Alumni\Model\AlumniMember;
use Alumni\Model\AlumniStudent;
use Alumni\Model\Alumni;
use Alumni\Model\AlumniRegistration;
use Alumni\Model\AlumniEvent;
use Alumni\Model\AlumniResource;
use Alumni\Model\AlumniProfile;
use Alumni\Model\UpdateAlumni;
use Alumni\Model\AlumniEnquiry;
use Alumni\Model\AlumniFaqs;
use Alumni\Model\AlumniContribution;
use Alumni\Model\AlumniSubscriptionDetails;
use Alumni\Model\AlumniSubscriberDetails;
use Alumni\Model\AlumniSubscription;
use Alumni\Model\UpdateAlumniSubscriberDetails;
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

class ZendDbSqlMapper implements AlumniMapperInterface
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
	protected $alumniPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			//\stdClass $goodsTransactionPrototype,
			Alumni $alumniPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->alumniPrototype = $alumniPrototype;
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


    public function getAlumniDetailsId($cid)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni'));
        
        $select->where(array('cid' =>$cid));
        $select->columns(array('id'));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    /*
    * Get organisation id based on the username
    */
    
    public function getOrganisationId($username, $usertype)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($usertype == 1){
            $select->from(array('t1' => 'employee_details'));
        
            $select->where(array('emp_id' =>$username));
            $select->columns(array('organisation_id'));
        }

        else if($usertype == 5){
            $select->from(array('t1' => 'alumni'));
        
            $select->where(array('cid' =>$username));
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

        else if($usertype == 5){
            $select->from(array('t1' => 'alumni'));
            $select->where(array('cid' => $username));
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

        if($usertype == 5){
            $select->from(array('t1' => 'alumni'));
            $select->where(array('t1.cid' => $username));
            $select->columns(array('profile_picture' => NULL));
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
	
	/*public function findAlumniNewRegistered($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('alumni');
            $select->where(array('id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}*/
	
	
	public function findAllAlumniNewRegistered($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni'))
                     ->join(array('t2' => 'alumni_programmes'),
        				    't2.id = t1.alumni_programmes_id', array('programme_name'))
                     
        			 ->where(array('t1.organisation_id = ?' => $organisation_id))
                     ->limit(20);

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
                    
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
	}


    public function getAlumniMemberList($alumniProgramme, $alumniBatch, $alumniName, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($alumniProgramme == 'All' && $alumniBatch == 'All' && $alumniName == NULL){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.alumni_status' => 'Active'));
        }
        else if($alumniProgramme == 'All' && $alumniBatch == 'All' && $alumniName != NULL){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.alumni_status' => 'Active'));
            $select->where->like('t1.first_name', '%'.$alumniName.'%');
        }
        else if($alumniProgramme != 'All' && $alumniBatch == 'All' && $alumniName == NULL){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.alumni_programmes_id' => $alumniProgramme, 't1.alumni_status' => 'Active'));
        } 
        else if($alumniProgramme != 'All' && $alumniBatch == 'All' && $alumniName != NULL){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.alumni_programmes_id' => $alumniProgramme, 't1.alumni_status' => 'Active'));
              $select->where->like('t1.first_name', '%'.$alumniName.'%');
        } 
        else if($alumniProgramme == 'All' && $alumniBatch != 'All' && $alumniName == NULL){ 
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.graduation_year' => $alumniBatch, 't1.alumni_status' => 'Active'));
        } 
        else if($alumniProgramme == 'All' && $alumniBatch != 'All' && $alumniName != NULL){ 
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                    ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.graduation_year' => $alumniBatch, 't1.alumni_status' => 'Active'));
                $select->where->like('t1.first_name', '%'.$alumniName.'%');
        } 
        else if( $alumniProgramme != 'All' && $alumniBatch != 'All' && $alumniName == NULL){
            $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                   ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.graduation_year' => $alumniBatch, 't1.alumni_programmes_id' => $alumniProgramme, 't1.alumni_status' => 'Active'));
        }
        else if($alumniProgramme != 'All' && $alumniBatch != 'All' && $alumniName != NULL){
            $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid', 'date_of_birth', 'graduation_year', 'alumni_status', 'alumni_programmes_id'))
                   ->join(array('t2' => 'alumni_programmes'),
                            't2.id = t1.alumni_programmes_id', array('programme_name'))
                    ->where(array('t1.organisation_id = ?' => $organisation_id, 't1.graduation_year' => $alumniBatch, 't1.alumni_programmes_id' => $alumniProgramme, 't1.alumni_status' => 'Active'));
                 $select->where->like('t1.first_name', '%'.$alumniName.'%');
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result); 

         $alumni_list = array();
        foreach($resultSet as $set){
            $alumni_list[$set['id']] = $set;
        }
        
        return $alumni_list;
    }
        
    
        
        /*public function findAlumniNewRegisteredDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'alumni')); //base table


            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }*/
		
		public function saveAlumniNewRegistered(AlumniRegistration $alumniRegistrationObject)
		{
		$alumniRegistrationData = $this->hydrator->extract($alumniRegistrationObject);
        	$alumniRegistrationDataSample = $alumniRegistrationData;
		unset($alumniRegistrationData['id']);
        	unset($alumniRegistrationData['current_Job_Title']);
        	unset($alumniRegistrationData['current_Job_Organisation']);
        	unset($alumniRegistrationData['qualification_Level_Id']);
        	unset($alumniRegistrationData['qualification_Field']);
         	unset($alumniRegistrationData['present_Address']);
          	unset($alumniRegistrationData['alumni_Status']);
          	unset($alumniRegistrationData['alumni_Type']);

        	$alumniRegistrationData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($alumniRegistrationData['date_Of_Birth'], 0,10)));
        	$alumniRegistrationData['registration_Date'] = date("Y-m-d", strtotime(substr($alumniRegistrationData['registration_Date'], 0,10))); 

        
	//	var_dump($studentAlumniData); die();
		if($alumniRegistrationObject->getId()) {
			//ID present, so it is an update
			$action = new Update('past_student');
			$action->set($alumniRegistrationData);
			$action->where(array('id = ?' => $alumniRegistrationObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('past_student');
			$action->values($alumniRegistrationData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

        $studentAlumniData = array();
        $studentAlumniDataFields = array(
            'first_Name',
            'middle_name',
            'last_Name',
            'cid',
            'student_Id',
            'gender',
            'date_Of_Birth',
            'contact_No',
            'email_Address',
            'present_Address',
            'current_Job_Title',
            'current_Job_Organisation',
            'qualification_Level_Id',
            'qualification_Field',
            'graduation_Year',
            'alumni_Programmes_Id',
            'organisation_Id',
        );

        foreach($alumniRegistrationDataSample as $key=>$value){
            if(in_array($key, $studentAlumniDataFields))
            {
                $studentAlumniData = array_merge($studentAlumniData, array($key=>$value));
                unset($alumniRegistrationData[$key]);
            }

        }
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $alumniRegistrationObject->setId($newId);
			}

            // Need to insert into alumni table 
            $this->addAlumniData($studentAlumniData);
            $this->addAlumniUser($studentAlumniData['cid'], $alumniRegistrationData['date_Of_Birth'], $studentAlumniData['organisation_Id']);

			return $alumniRegistrationObject;
		}
		
		throw new \Exception("Database Error");
	}


    public function addAlumniData($studentAlumniData)
    {
	$studentAlumniData['date_Of_Birth'] = date("Y-m-d", strtotime(substr($studentAlumniData['date_Of_Birth'], 0,10)));
        $action = new Insert('alumni');
        $action->values(array(
            'first_name' => $studentAlumniData['first_Name'],
            'middle_name' => $studentAlumniData['middle_name'],
            'last_name' => $studentAlumniData['last_Name'],
            'cid' => $studentAlumniData['cid'],
            'student_id' => $studentAlumniData['student_Id'],
            'gender' => $studentAlumniData['gender'],
            'date_of_birth' => $studentAlumniData['date_Of_Birth'],
            'contact_no' => $studentAlumniData['contact_No'],
            'email_address' => $studentAlumniData['email_Address'],
            'present_address' => $studentAlumniData['present_Address'],
            'current_job_title' => $studentAlumniData['current_Job_Title'],
            'current_job_organisation' => $studentAlumniData['current_Job_Organisation'],
            'qualification_level_id' => $studentAlumniData['qualification_Level_Id'],
            'qualification_field' => $studentAlumniData['qualification_Field'],
            'graduation_year' => $studentAlumniData['graduation_Year'],
            'alumni_status' => 'Active',
            'alumni_Type' => 'Past',
            'alumni_programmes_id' => $studentAlumniData['alumni_Programmes_Id'],
            'organisation_id' => $studentAlumniData['organisation_Id'],
            
        ));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    public function addAlumniUser($cid, $dob, $organisation_id)
    {
        $abbr = $this->getOrganisationAbbr($organisation_id);

        $action = new Insert('users');
        $action->values(array(
            'username' => $cid,
            'password' => md5($dob),
            'role' => $abbr.'_ALUMNI',
            'region' => $organisation_id,
            'user_type_id' => '5',
        ));
        
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
	
	/*
    * Search for registered alumni members
    */
   /* public function getRegisteredMemberList($memProgramme, $memYear, $memName)
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
                    return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
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

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
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

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
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
	}*/

	public function findAlumniProfile($id)
	{
            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'alumni'))
                   ->join(array('t2' => 'past_student'),
                        't2.cid = t1.cid', array('alumni_programmes_id', 'first_name', 'middle_name', 'last_name', 'date_of_birth'))
            	   ->join(array('t3' => 'alumni_programmes'),
            	   		't3.id = t2.alumni_programmes_id', array('programme_name'))
                   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}
	

	
	/*public function findAllAlumniProfile()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni')); // join expression

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
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
               't2.id = t1.alumni_programmes_id', array('programme_name'))
             ->where(array('id = ? ' => $id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
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
                    return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
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

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
    
	
	public function findAlumniDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'alumni'))
                   ->where(array('t1.id = ? ' => $id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
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
                    return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
            }

            throw new \InvalidArgumentException("Alumni with given ID: ($id) not found");
	}*/
	
	
	public function findAllAlumniEvent($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_event'))
            ->join(array('t2' => 'alumni_programmes'),
              't2.id = t1.alumni_programmes_id', array('programme_name'))
				->where(array('t1.organisation_id = ?' => $organisation_id, 't1.to_date <= ?' => date('Y-m-d')));
            
              
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


    public function listAllContributionDetails($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_contribution_details'))
               ->where(array('t1.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }



    public function getEventEmailList($batch, $programme, $organisation)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($batch == 'All' && $programme == 'All'){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
                    ->where(array('t1.organisation_id = ?' => $organisation, 't1.alumni_status' => 'Active'));
        }
        if($batch == 'All' && $programme != 'All'){
             $select->from(array('t1' => 'alumni'))
                    ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
                    ->where(array('t1.organisation_id = ?' => $organisation, 't1.alumni_programmes_id' => $programme, 't1.alumni_status' => 'Active'));
        } 
        if($batch != 'All' && $programme == 'All'){ 
             $select->from(array('t1' => 'alumni'))
             ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
                    ->where(array('t1.organisation_id = ?' => $organisation, 't1.graduation_year' => $batch, 't1.alumni_status' => 'Active'));
        } 
        if($batch != 'All' && $programme != 'All'){
            $select->from(array('t1' => 'alumni'))
                   ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
                    ->where(array('t1.organisation_id = ?' => $organisation, 't1.graduation_year' => $batch, 't1.alumni_programmes_id' => $programme, 't1.alumni_status' => 'Active'));
        }
        else{
            $select->from(array('t1' => 'alumni'))
                   ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
                    ->where(array('t1.organisation_id = ?' => $organisation));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
 
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['email_address']] = $set['email_address'];
        }
        return $selectData;
    }


    public function getAlumniContributionEmailList($organisation)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni'))
               ->columns(array('id', 'email_address', 'alumni_programmes_id', 'organisation_id', 'graduation_year'))
               ->where(array('t1.organisation_id = ?' => $organisation));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
 
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['email_address']] = $set['email_address'];
        }
        return $selectData;
    }
        
 
        
        /*public function findAlumniEventDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'alumni_event'))
                    ->where(array('t1.id = ? ' => $id));
                //base table
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }*/
		
	
	
	public function saveAlumniEvent(ALumniEvent $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);

        $alumniMemberData['from_Date'] = date("Y-m-d", strtotime(substr($alumniMemberData['from_Date'], 0,10)));
         $alumniMemberData['to_Date'] = date("Y-m-d", strtotime(substr($alumniMemberData['to_Date'], 0,10)));

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



    public function saveAlumniContribution(AlumniContribution $alumniObject)
    {
        $alumniContributionData = $this->hydrator->extract($alumniObject);
        unset($alumniContributionData['id']);
        //unset($alumniMemberData['organisation_Id']);

        $alumniContributionData['contributed_Date'] = date("Y-m-d", strtotime(substr($alumniContributionData['contributed_Date'], 0,10)));

        if($alumniObject->getId()) {
            //ID present, so it is an update
            $action = new Update('alumni_contribution_details');
            $action->set($alumniContributionData);
            $action->where(array('id = ?' => $alumniObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('alumni_contribution_details');
            $action->values($alumniContributionData);
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
	
	
	public function listAllAlumniResource($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_resource'))
			->where(array('t1.organisation_id = ?' => $organisation_id));;
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
    
	
	public function saveAlumniEnquiry(AlumniEnquiry $alumniMemberObject)
	{
		$alumniMemberData = $this->hydrator->extract($alumniMemberObject);
		unset($alumniMemberData['id']);
		//unset($alumniMemberData['organisation_Id']);
		//unset($alumniMemberData['alumni_Id']); 
        //var_dump($alumniMemberData); die();
		
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
	
	
	public function listAllAlumniEnquiry($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_academic_help'))
                   ->join(array('t2' => 'alumni'),
                        't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'contact_no', 'email_address'))
                   ->where(array('t1.enquiry_status' => 'Pending', 't1.organisation_id' => $organisation_id));
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
	}


    public function listAlumniEnquiry($alumni_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_academic_help'))
               ->where(array('t1.alumni_id' => $alumni_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function checkAlumniSubscription($alumni_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_subscriber_details'))
               ->where(array('t1.alumni_id' => $alumni_id, 't1.subscription_status' => 'Approved'));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
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
	
	
	public function listAllAlumniFaqs($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'alumni_academic_faq'))
			->where(array('t1.organisation_id = ?' => $organisation_id));;
            //->join(array('t2' => 'organisation'),
             // 't2.id = t1.organisation_id', array('organisation_id'));
           	  
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


    public function crossCheckSubscriptionDetails($subscription_details)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscription_lists'));
        $select->where(array('subscription_details' => $subscription_details));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $subscriptionDetails = 0;
        foreach($resultSet as $set){
            $subscriptionDetails = $set['subscription_details'];
        }
        return $subscriptionDetails;
    }

    public function crossCheckSubscriptionType($action_type, $id, $subscription_type, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($action_type == 'Add' && $id == NULL){
            $select->from(array('t1' => 'alumni_subscription_details'));
            $select->where(array('t1.subscription_type' => $subscription_type, 't1.organisation_id' => $organisation_id));
        }else if($action_type == 'Edit' && $id != NULL){
            $select->from(array('t1' => 'alumni_subscription_details'));
            $select->where(array('t1.subscription_type' => $subscription_type, 't1.organisation_id' => $organisation_id, 't1.id != ?' => $id));
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $subscriptionType = NULL;
        foreach($resultSet as $set){
            $subscriptionType = $set['subscription_type'];
        }
        return $subscriptionType;
    }

    public function listAllAlumniSubscriptionList($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_subscription_lists'))
               ->where(array('t1.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    public function listAlumniSubscriptionDetailList($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_subscription_details'))
               ->where(array('t1.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getAlumniSubscriptionList($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscription_lists'));             
        $select->where(array('t1.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
    }


    public function checkRegisteredSubscriber($alumni_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscriber_details'));
        $select->where(array('t1.alumni_id' => $alumni_id));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $alumni = 0;
        foreach($resultSet as $set){
            $alumni = $set['alumni_id'];
        }
        return $alumni; 
    }


    public function getAlumniSubscriberDetails($employee_details_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscriber_details'));             
        $select->where(array('t1.alumni_id' => $employee_details_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
    }


    public function getAlumniSubscriptionDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscription_lists'))
                ->where(array('t1.id = ?' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
            }
        throw new \InvalidArgumentException("Alumni Subscription lists with given ID: ($id) not found");
    }


    public function getAlumniSubscription($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscription_details'))
                ->where(array('t1.id = ?' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
            return $this->hydrator->hydrate($result->current(), $this->alumniPrototype);
            }
        throw new \InvalidArgumentException("Alumni Subscription Details with given ID: ($id) not found");
    }


    public function getAlumniSubscriberList($organisation_id, $status)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($status == 'Pending'){
             $select->from(array('t1' => 'alumni_subscriber_details'))
               ->join(array('t2' => 'alumni'),
                    't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'qualification_level_id', 'alumni_programmes_id', 'graduation_year'))
               ->join(array('t3' => 'study_level'),
                    't3.id = t2.qualification_level_id', array('study_level'))
               ->join(array('t4' => 'alumni_programmes'),
                    't4.id = t2.alumni_programmes_id', array('programme_name'));             
            $select->where(array('t1.organisation_id' => $organisation_id, 't1.subscription_status' => $status));
        }

        else if($status == 'Approved'){
             $select->from(array('t1' => 'alumni_subscriber_details'))
               ->join(array('t2' => 'alumni'),
                    't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'qualification_level_id', 'alumni_programmes_id', 'graduation_year'))
               ->join(array('t3' => 'study_level'),
                    't3.id = t2.qualification_level_id', array('study_level'))
               ->join(array('t4' => 'alumni_programmes'),
                    't4.id = t2.alumni_programmes_id', array('programme_name'));             
            $select->where(array('t1.organisation_id' => $organisation_id, 't1.subscription_status' => $status));
        }

        else if($status == 'Rejected'){
             $select->from(array('t1' => 'alumni_subscriber_details'))
               ->join(array('t2' => 'alumni'),
                    't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'qualification_level_id', 'alumni_programmes_id', 'graduation_year'))
               ->join(array('t3' => 'study_level'),
                    't3.id = t2.qualification_level_id', array('study_level'))
               ->join(array('t4' => 'alumni_programmes'),
                    't4.id = t2.alumni_programmes_id', array('programme_name'));             
            $select->where(array('t1.organisation_id' => $organisation_id, 't1.subscription_status' => $status));
        }

         else if($status == NULL){
             $select->from(array('t1' => 'alumni_subscriber_details'))
               ->join(array('t2' => 'alumni'),
                    't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'qualification_level_id', 'alumni_programmes_id', 'graduation_year'))
               ->join(array('t3' => 'study_level'),
                    't3.id = t2.qualification_level_id', array('study_level'))
               ->join(array('t4' => 'alumni_programmes'),
                    't4.id = t2.alumni_programmes_id', array('programme_name'));             
            $select->where(array('t1.organisation_id' => $organisation_id, 't1.subscription_status' => 'Approved', 't1.subscription_type' => 'Yearly'));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
    }


    public function getAlumniSubscribingDetails($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscription_details'));             
        $select->where(array('t1.organisation_id' => $organisation_id));
        $select->limit(1);

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->buffer();
        return $resultSet->initialize($result);
    }


    public function saveSubscriptionList(AlumniSubscriptionDetails $alumniObject)
    {
        $alumniSubscriptionData = $this->hydrator->extract($alumniObject);
        unset($alumniSubscriptionData['id']);
        
        if($alumniObject->getId()) {
            //ID present, so it is an update
            $action = new Update('alumni_subscription_lists');
            $action->set($alumniSubscriptionData);
            $action->where(array('id = ?' => $alumniObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('alumni_subscription_lists');
            $action->values($alumniSubscriptionData);
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


    public function saveSubscriptionDetails(AlumniSubscription $alumniObject)
    {
        $alumniSubscriptionData = $this->hydrator->extract($alumniObject);
        unset($alumniSubscriptionData['id']);
        
        if($alumniObject->getId()) {
            //ID present, so it is an update
            $action = new Update('alumni_subscription_details');
            $action->set($alumniSubscriptionData);
            $action->where(array('id = ?' => $alumniObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('alumni_subscription_details');
            $action->values($alumniSubscriptionData);
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


    public function updateAlumniSubscription(UpdateAlumniSubscriberDetails $alumniObject)
    {
        $alumniSubscriberData = $this->hydrator->extract($alumniObject);
        //unset($alumniSubscriberData['id']); 
        
        if($alumniSubscriberData['subscription_Status'] == 'Approved'){ 

            $alumniSubscriberData['subscriber_Id'] = $this->getSubscriberId($alumniSubscriberData['organisation_Id']);
            //var_dump($alumniSubscriberData); die();

            //ID present, so it is an update
            $action = new Update('alumni_subscriber_details');
            $action->set($alumniSubscriberData);
            $action->where(array('id = ?' => $alumniSubscriberData['id']));
        }else if($alumniSubscriberData['subscription_Status'] == 'Rejected'){
            //ID present, so it is an update
            $action = new Update('alumni_subscriber_details');
            $action->set($alumniSubscriberData);
            $action->where(array('id = ?' => $alumniSubscriberData['id']));
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    public function renewAlumniSubscription($id)
    { 
        //$last_date = $this->getLastUpdatedDate($id);
       // $newEndingDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($last_date)) . " + 365 day"));
        $newEndingDate = date('Y-m-d');

        $action = new Update('alumni_subscriber_details');
        $action->set(array('updated_date' => $newEndingDate));
        $action->where(array('id = ?' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }


    public function getLastUpdatedDate($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_subscriber_details'))
               ->where(array('t1.id' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        $last_updated_date = NULL;
        foreach($resultSet as $set){
            $last_updated_date = $set['updated_date'];
        }

        return $last_updated_date;

    }



    public function listAlumniSubscriptionDetails($id)
    {
       $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'alumni_subscriber_details'))
                   ->join(array('t2' => 'alumni'),
                        't2.id = t1.alumni_id', array('first_name', 'middle_name', 'last_name', 'cid', 'contact_no', 'email_address', 'graduation_year', 'alumni_programmes_id'))
                   ->join(array('t3' => 'alumni_programmes'),
                        't3.id = t2.alumni_programmes_id', array('programme_name'))
                   ->where(array('t1.id' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
    }


    public function getAlumniSubscriptionApplicationDetails($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'alumni_subscriber_details'))
                ->where(array('t1.id' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function saveAlumniSubscription(AlumniSubscriberDetails $alumniObject, $subscription_type, $subscription_charge)
    {
        $alumniSubscriberData = $this->hydrator->extract($alumniObject);
        unset($alumniSubscriberData['id']);
        $alumniSubscriberData['subscription_Type'] = $subscription_type;
        $alumniSubscriberData['subscription_Charge'] = $subscription_charge;
        
        if($alumniObject->getId()) {
            //ID present, so it is an update
            $action = new Update('alumni_subscriber_details');
            $action->set($alumniSubscriberData);
            $action->where(array('id = ?' => $alumniObject->getId()));
        } else {
            //ID is not present, so its an insert
            $action = new Insert('alumni_subscriber_details');
            $action->values($alumniSubscriberData);
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


    public function getSubscriberId($organisation_id)
    {
        $abbr = $this->getOrganisationAbbr($organisation_id);

        //format for employee id
        $Year = date('Y');
        $format = $abbr.substr($Year, 2).date('m');
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'alumni_subscriber_details'))
                ->columns(array('subscriber_id'));
        $select->where->like('subscriber_id','%'.$format.'%');
        $select->order('subscriber_id DESC');
        $select->limit(1);
        
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $subscriber_id = NULL;
        
        foreach($resultSet as $set)
            $subscriber_id = $set['subscriber_id'];
        
        //first employee of the year
        if($subscriber_id == NULL){
            $generated_id = $abbr.substr(date('Y'),2).date('m').'001';
        }
        else{
            //need to get the last 3 digits and increment it by 1 and convert it back to string
            $number = substr($emp_id, -3);
            $number = (int)$number+1;
            $number = strval($number);
            while (mb_strlen($number)<3)
                $number = '0'. strval($number);
            
            $generated_id = $abbr.substr(date('Y'),2).date('m').$number;
        }
        
        return $generated_id;
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

        if($organisation_id == NULL){
             $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }
        else{
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName))
                  ->where(array('t1.organisation_id = ? ' => $organisation_id));
        }

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

    public function listSelectData1($tableName, $columnName, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'alumni_programmes'){
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName))
                ->where(array('t1.organisation_id = ? ' => $organisation_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
                
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
                
            //Need to make the resultSet as an array
            // e.g. 1=> Category 1, 2 => Category etc.
                
            $selectData = array();
            foreach($resultSet as $set)
            {
                $selectData['All'] = 'All';
                $selectData[$set['id']] = $set[$columnName];
            }
            return $selectData;
        }
        else if($tableName == 'alumni'){
            $select->from(array('t1' => $tableName));
            $select->columns(array($columnName))
                ->where(array('t1.organisation_id = ? ' => $organisation_id))
                ->order('t1.graduation_year DESC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
                
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
                
            //Need to make the resultSet as an array
            // e.g. 1=> Category 1, 2 => Category etc.
                
            $selectData = array();
            foreach($resultSet as $set)
            {
                $selectData['All'] = 'All';
                $selectData[$set[$columnName]] = $set[$columnName];
            }
            return $selectData;
        }
    }
	
	/*public function getAllAlumniStudent($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
               ->join(array('t2' => 'programmes'),
                    't2.id = t1.programmes_id', array('programme_name'))
					->where(array('t1.organisation_id = ?' => $organisation_id));               
        
        $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->alumniPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
			
    }*/
	
}