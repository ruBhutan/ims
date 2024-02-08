<?php

namespace Vacancy\Mapper;

use Vacancy\Model\Vacancy;
use Vacancy\Model\JobApplication;
use Vacancy\Model\SelectedApplicant;
use Vacancy\Model\JobApplicantMarks;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements VacancyMapperInterface
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
	 * @var \Vacancy\Model\VacancyInterface
	*/
	protected $vacancyPrototype;
		
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			Vacancy $vacancyPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->vacancyPrototype = $vacancyPrototype;
	}
	
	/**
	* @return array/Vacancy()
	*/
	
	public function findAll($tableName, $type, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		//var_dump(date('H:i:s', time())); die();

		if($tableName == 'vacancy_announcements' && $type == 'Vacancy List'){
			$select->from(array('t1' => $tableName))
			->join(array('t2' => 'study_level'),
						't2.id = t1.minimum_study_level_id', array('study_level'));
			$select->where(array('t1.date_of_advertisement <= ?' => date('Y-m-d'), 't1.last_date_submission >= ?' => date('Y-m-d')));
			if ('t1.last_date_submission' == date('Y-m-d')) {
				$select->where(array('t1.last_time_submission >= ?' => date('h:i:s')));
			}
		}
		else if($tableName == 'vacancy_announcements' && $type == 'Adhoc'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'study_level'),
						't2.id = t1.minimum_study_level_id', array('study_level'));
			$select->where(array('t1.status = ?' => 'Open'));
			$select->where(array('t1.vacancy_type = ?' => 'Adhoc'));
		}
		else if($tableName == 'vacancy_announcements' && $type == 'Planned'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'study_level'),
						't2.id = t1.minimum_study_level_id', array('study_level'));
			$select->where(array('t1.status = ?' => 'Open'));
			$select->where(array('t1.vacancy_type = ?' => 'Planned'));
		}
		else if($tableName == 'vacancy_announcements' && $type == 'Lists'){
			$select->from(array('t1' => $tableName))
				   ->join(array('t2' => 'study_level'),
						't2.id = t1.minimum_study_level_id', array('study_level'));
			$select->where(array('t1.status = ?' => 'Open'));
			$select->where(array('t1.organisation_id = ?' => $organisation_id));
		}
		
		$select->from(array('t1' => $tableName)); 

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function findEmpDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('employee_details');
		$select->where(array('emp_id = ? ' => $id));

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
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
		} else{
			$select->where(array('email' =>$username));
		}
		$select->columns(array('id', 'first_name', 'middle_name', 'last_name'));
			
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

		if($usertype == 4){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('t1.email' => $username));
			$select->columns(array('profile_picture'=>NULL)); 

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
	* @return Vacancy
	* @throws \InvalidArgumentException
	*/
	
	public function findVacancy($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('vacancy');
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
				return $this->hydrator->hydrate($result->current(), $this->vacancyPrototype);
		}

		throw new \InvalidArgumentException("Vacancy Proposal with given ID: ($id) not found");
	}
	        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Objectives for a given $id
	 */
	 
	public function findModule($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'modules'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			$resultSet = new HydratingResultSet($this->hydrator, $this->modulePrototype);
			$resultSet->buffer();
			return $resultSet->initialize($result); 
		}
		
		return array();
	}
		
	/**
	 * 
	 * @param type $VacancyInterface
	 * 
	 * to save Vacancy
	 */
	
	public function saveVacancy(Vacancy $vacancyObject)
	{
		$vacancyData = $this->hydrator->extract($vacancyObject);
		unset($vacancyData['id']);
		$vacancyData['date_Of_Advertisement'] = date("Y-m-d", strtotime(substr($vacancyData['date_Of_Advertisement'],0,10)));
		$vacancyData['last_Date_Submission'] = date("Y-m-d", strtotime(substr($vacancyData['last_Date_Submission'],0,10)));

		if($vacancyObject->getId()) {
			//ID present, so it is an update
			$action = new Update('vacancy_announcements');
			$action->set($vacancyData);
			$action->where(array('id = ?' => $vacancyObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('vacancy_announcements');
			$action->values($vacancyData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $vacancyObject->setId($newId);
			}
			return $vacancyObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * 
	 * @param type $VacancyInterface
	 * 
	 * to save Vacancy
	 */
	
	public function saveAdhocVacancy(Vacancy $vacancyObject)
	{
		$vacancyData = $this->hydrator->extract($vacancyObject);
		unset($vacancyData['id']); //var_dump($vacancyData); die();
		//Need to retrieve the following values from position directory
		//unset($vacancyData['general_Responsibilities']);
		//unset($vacancyData['education_Qualification_Experience']);
		//unset($vacancyData['knowledge_Skills']);
		
		$positionDetail = $this->getPositionDetails($vacancyData['position_Title']);
		foreach($positionDetail as $detail){
			$vacancyData['general_Responsibilities'] = $detail['work_activity'];
			$vacancyData['education'] = $detail['education'];
			$vacancyData['education_Qualification'] = $detail['education_qualification'];
			$vacancyData['experience'] = $detail['experience'];
			$vacancyData['knowledge_Skills'] = $detail['knowledge_skills_abilities'];
		}
		$vacancyData['date_Of_Advertisement'] = date("Y-m-d", strtotime(substr($vacancyData['date_Of_Advertisement'],0,10)));
		$vacancyData['last_Date_Submission'] = date("Y-m-d", strtotime(substr($vacancyData['last_Date_Submission'],0,10)));
		
		if($vacancyObject->getId()) {
			//ID present, so it is an update
			$action = new Update('vacancy_announcements');
			$action->set($vacancyData);
			$action->where(array('id = ?' => $vacancyObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('vacancy_announcements');
			$action->values($vacancyData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $vacancyObject->setId($newId);
			}
			return $vacancyObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Save Job Application
	 */
	 
	public function saveJobApplication(JobApplication $jobObject)
	{
		
		$jobData = $this->hydrator->extract($jobObject);
		//var_dump($jobData); die();
		unset($jobData['id']);
		//if empty, set to NULL
		if($jobData['employee_Details_Id'] == NULL){
			$jobData['employee_Details_Id'] = NULL;
		}
		if($jobData['job_Applicant_Id'] == NULL){
			$jobData['job_Applicant_Id'] = NULL;
		}
		
		$identity = $jobData['identity_Proof'];
		$jobData['identity_Proof'] = $identity['tmp_name'];
		
		$security = $jobData['security_Clearance_File'];
		$jobData['security_Clearance_File'] = $security['tmp_name'];
		
		$medical = $jobData['medical_Clearance_File'];
		$jobData['medical_Clearance_File'] = $medical['tmp_name'];
		
		$other = $jobData['other_Certificate_File'];
		$jobData['other_Certificate_File'] = $other['tmp_name'];
                
        $referenceData = array();
        //need to extract the reference details
        for($i=1; $i<=2; $i++){
            $referenceData[$i]['name'] = $jobData['reference_Name_'.$i];
            unset($jobData['reference_Name_'.$i]);
            $referenceData[$i]['title'] = $jobData['reference_Title_'.$i];
            unset($jobData['reference_Title_'.$i]);
            $referenceData[$i]['position'] = $jobData['reference_Position_'.$i];
            unset($jobData['reference_Position_'.$i]);
            $referenceData[$i]['organisation'] = $jobData['reference_Organisation_'.$i];
            unset($jobData['reference_Organisation_'.$i]);
            $referenceData[$i]['relation_Applicant'] = $jobData['reference_Relation_Applicant_'.$i];
            unset($jobData['reference_Relation_Applicant_'.$i]);
            $referenceData[$i]['telephone'] = $jobData['reference_Telephone_'.$i];
            unset($jobData['reference_Telephone_'.$i]);
            $referenceData[$i]['email'] = $jobData['reference_Email_'.$i];
            unset($jobData['reference_Email_'.$i]);
        }

        $referenceData1 = array();
        //need to extract the reference details
         for($i=1; $i<=1; $i++){
	        $referenceData1[$i]['x_english'] = $jobData['x_English'];
	        unset($jobData['x_English']);
	        $referenceData1[$i]['x_sub1_mark'] = $jobData['x_Sub1_Mark'];
	        unset($jobData['x_Sub1_Mark']);
	        $referenceData1[$i]['x_sub2_mark'] = $jobData['x_Sub2_Mark'];
	        unset($jobData['x_Sub2_Mark']);
	        $referenceData1[$i]['x_sub3_mark'] = $jobData['x_Sub3_Mark'];
	        unset($jobData['x_Sub3_Mark']);
	        $referenceData1[$i]['x_sub4_mark'] = $jobData['x_Sub4_Mark'];
	        unset($jobData['x_Sub4_Mark']);
	        $referenceData1[$i]['xll_english'] = $jobData['xll_English'];
	        unset($jobData['xll_English']);
	        $referenceData1[$i]['xll_sub1_mark'] = $jobData['xll_Sub1_Mark'];
	        unset($jobData['xll_Sub1_Mark']);
	        $referenceData1[$i]['xll_sub2_mark'] = $jobData['xll_Sub2_Mark'];
	        unset($jobData['xll_Sub2_Mark']);
	        $referenceData1[$i]['xll_sub3_mark'] = $jobData['xll_Sub3_Mark'];
	        unset($jobData['xll_Sub3_Mark']);
		 }

		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_job_applications');
			$action->set($jobData);

			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
			//var_dump($jobData); die();
			$action = new Insert('emp_job_applications');
			$action->values($jobData);
		}

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
                $this->saveJobApplicationReferences($referenceData, $newId);
                //var_dump($referenceData1); die();
                $this->saveJobApplicationReferences1($referenceData1, $newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}

	public function closeAdhocVacancy($id)
	{
		//var_dump($id); die();
		$action = new Update('vacancy_announcements');
		$action->set(array(
					'status'=> 'Close',
				));
		$action->where(array('id = ?' => $id));
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
		
	}
        
        /*
	 * Save Job Application References
	 */
	 
	public function saveJobApplicationReferences($referenceData, $emp_job_application_id)
	{	
        foreach($referenceData as $key=>$value){
            $reference['name'] = $value['name'];
            $reference['title'] = $value['title'];
            $reference['position'] = $value['position'];
            $reference['organisation'] = $value['organisation'];
            $reference['relation_Applicant'] = $value['relation_Applicant'];
            $reference['telephone'] = $value['telephone'];
            $reference['email'] = $value['email'];
            $reference['emp_job_applications_id'] = $emp_job_application_id;
            
            $action = new Insert('job_application_references');
            $action->values($reference);

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
        }
		return;
	}
	public function saveJobApplicationReferences1($referenceData1, $emp_job_application_id)
	{	
		//var_dump($referenceData1); die();
        foreach($referenceData1 as $key=>$value){
        	$reference['x_english'] = $value['x_english'];
	        $reference['x_sub1_mark'] = $value['x_sub1_mark'];
	        $reference['x_sub2_mark'] = $value['x_sub2_mark'];
	        $reference['x_sub3_mark'] = $value['x_sub3_mark'];
	        $reference['x_sub4_mark'] = $value['x_sub4_mark'];
	        $reference['xll_english'] = $value['xll_english'];
	        $reference['xll_sub1_mark'] = $value['xll_sub1_mark'];
	        $reference['xll_sub2_mark'] = $value['xll_sub2_mark'];
	        $reference['xll_sub3_mark'] = $value['xll_sub3_mark']; 
            $reference['emp_job_applications_id'] = $emp_job_application_id;
            
            $action = new Insert('job_application_marks');
            $action->values($reference);

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();
        }
		return;
	}
	
	/*
	 * Get the details of the HRD Proposal for announcing vacancy
	 */
	 
	public function getProposalDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_management'))
				->join(array('t2' => 'position_title'), 
                            't1.position_title_id = t2.id')
                    ->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the details of the Job Vacancy
	*/
	 
	public function getVacancyDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'vacancy_announcements'))
				->join(array('t2' => 'position_title'), 
                            't1.position_title = t2.id')
				->join(array('t3' => 'position_category'), 
                            't1.position_category = t3.id', array('category'))
				->join(array('t4' => 'position_level'), 
                            't1.position_level = t4.id', array('level_name' => 'position_level'))
                    ->where(array('t1.id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function getAppliedVacancyDetail($table_name, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($table_name == 'employee_details'){
			$select->from(array('t1' => 'vacancy_announcements'))
			    ->join(array('t2' => 'emp_job_applications'),
					't1.id = t2.vacancy_announcements_id')
				->join(array('t3' => 'position_title'), 
                            't1.position_title = t3.id')
				->join(array('t4' => 'position_category'), 
                            't1.position_category = t4.id', array('category'))
				->join(array('t5' => 'position_level'), 
							't1.position_level = t5.id', array('level_name' => 'position_level'))
				->join(array('t6' => $table_name),
							't6.id = t2.employee_details_id', array('emp_id'))
                ->where(array('t2.id = ? ' => $id));
		}else{
			$select->from(array('t1' => 'vacancy_announcements'))
			    ->join(array('t2' => 'emp_job_applications'),
					't1.id = t2.vacancy_announcements_id')
				->join(array('t3' => 'position_title'), 
                            't1.position_title = t3.id')
				->join(array('t4' => 'position_category'), 
                            't1.position_category = t4.id', array('category'))
				->join(array('t5' => 'position_level'), 
							't1.position_level = t5.id', array('level_name' => 'position_level'))
				->join(array('t6' => $table_name),
							't6.id = t2.job_applicant_id')			
				->where(array('t2.id = ? ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get the details of the job applicant
	* Used when viewing the details of the applicant
	* Takes the id of the job application 
	*/
	
	public function getJobApplicantDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_job_applications'))
				->columns(array('employee_details_id','job_applicant_id'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$applicant_details = array();
		
		foreach($resultSet as $set){
			$applicant_details['job_applicant_id'] = $set['job_applicant_id'];
			$applicant_details['employee_details_id'] = $set['employee_details_id'];
		}
		return $applicant_details;
	}
        
        /*
	* Get the details of the selected applicant
	* Used when viewing the details of the applicant
	* Takes the id of the recruited applicant 
	*/
	
	public function getSelectedApplicantDetail($id)
        {
            $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                $select->from(array('t1' => 'employee_recruitment_details'))
				->columns(array('emp_job_applications_id'))
				->join(array('t2' => 'emp_job_applications'), 
                            't1.emp_job_applications_id = t2.id', array('employee_details_id','job_applicant_id'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$applicant_details = array();
		foreach($resultSet as $set){
			$applicant_details['job_applicant_id'] = $set['job_applicant_id'];
			$applicant_details['employee_details_id'] = $set['employee_details_id'];
		}
		return $applicant_details;
        }
	
        /*
         * Get the Recruitment details
         */
        
	public function getRecruitmentDetails($id)
        {
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
					
			$select->from(array('t1' => 'employee_recruitment_details'));
			$select->where(array('t1.id' =>$id));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
        }


        public function getApplicantEducationLevel($employee_details_id)
        {
        	$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'emp_education_details'))
				   ->columns(array('study_level'))
				   ->where(array('t1.employee_details_id' => $employee_details_id));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$study_level = array();
			foreach($resultSet as $set){
				$study_level[$set['study_level']] = $set['study_level'];
			} //var_dump($study_level); die();
			return $study_level;
        }


        public function getApplicantAddressDetails($employee_details_id, $type)
        {
        	$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

		if($type == 'staff'){
			$select->from(array('t1' => 'employee_details'))
			   ->join(array('t2' => 'nationality'),
					't2.id = t1.nationality', array('nationality'))
			   ->join(array('t3' => 'country'),
					't3.id = t1.country', array('country'))
			   ->join(array('t4' => 'maritial_status'),
					't4.id = t1.marital_status', array('maritial_status'))
			   ->join(array('t5' => 'gender'),
					't5.id = t1.gender', array('gender'))
			   ->join(array('t6' => 'dzongkhag'),
					't6.id = t1.emp_dzongkhag', array('dzongkhag_name'))
			   ->join(array('t7' => 'gewog'),
					't7.id = t1.emp_gewog', array('gewog_name'))
			   ->join(array('t8' => 'village'),
					't8.id = t1.emp_village', array('village_name'));
		$select->where(array('t1.id' => $employee_details_id));
		} else {
			$select->from(array('t1' => 'job_applicant'))
			   ->join(array('t2' => 'nationality'),
					't2.id = t1.nationality', array('nationality'))
			   ->join(array('t3' => 'country'),
					't3.id = t1.country', array('country'))
			   ->join(array('t4' => 'maritial_status'),
					't4.id = t1.maritial_status', array('maritial_status'))
			   ->join(array('t5' => 'gender'),
					't5.id = t1.gender', array('gender'))
			   ->join(array('t6' => 'dzongkhag'),
					't6.id = t1.dzongkhag', array('dzongkhag_name'))
			   ->join(array('t7' => 'gewog'),
					't7.id = t1.gewog', array('gewog_name'))
			   ->join(array('t8' => 'village'),
					't8.id = t1.village', array('village_name'));
		$select->where(array('t1.id' => $employee_details_id));
		}
		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
        }
        
	/*
	* To check whether the user has applied for the job or not
	*/
	
	public function getJobApplication($employee_details_id, $job_applicant_id, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_job_applications'))
				->columns(array('employee_details_id','job_applicant_id'))
				->join(array('t2' => 'vacancy_announcements'), 
                            't1.vacancy_announcements_id = t2.id', array('id'));
		if($employee_details_id){
			$select->where(array('t1.employee_details_id' =>$employee_details_id));
			$select->where(array('t2.id' =>$id));
		}
		else {
			$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			$select->where(array('t2.id' =>$id));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$applicant_id = NULL;
		foreach($resultSet as $set){
			if($set['employee_details_id'])
				$applicant_id= $set['employee_details_id'];
			if($set['job_applicant_id'])
				$applicant_id= $set['job_applicant_id'];
		}
		return $applicant_id;
	}
	
	/*
	* Get Personal Details of the job applicant 
	*/
	 
	public function getPersonalDetails($tableName, $applicant_id)
	{ 
		//var_dump($applicant_id); die();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to ensure that columns are output the same
		if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName))
					->join(array('t2' => 'dzongkhag'), 
                            't1.dzongkhag = t2.id', array('dzongkhag_name'))
                    ->join(array('t3'=>'gewog'),
                            't3.id = t1.gewog', array('gewog_name'))
					->join(array('t4' => 'village'), 
                            't4.id = t1.village', array('village_name'))
                    ->join(array('t5'=>'nationality'),
                            't1.nationality = t5.id', array('nationality'))
                    ->join(array('t6' => 'country'),
						't6.id = t1.country', array('country'))
					->join(array('t7' => 'maritial_status'),
						't7.id = t1.maritial_status', array('maritial_status'))
					->join(array('t8' => 'gender'),
						't8.id = t1.gender', array('gender'));
            $select->where(array('t1.id' =>$applicant_id));
		} else if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName))
					->join(array('t2' => 'dzongkhag'), 
                            't1.emp_dzongkhag = t2.id', array('dzongkhag_name'))
                    ->join(array('t3'=>'gewog'),
                            't3.id = t1.emp_gewog', array('gewog_name'))
					->join(array('t4' => 'village'), 
                            't4.id = t1.emp_village', array('village_name'))
                    ->join(array('t5'=>'nationality'),
                            't1.nationality = t5.id', array('nationality'))
                    ->join(array('t6' => 'country'),
						't6.id = t1.country', array('country'))
					->join(array('t7' => 'maritial_status'),
						't7.id = t1.marital_status', array('maritial_status'))
					->join(array('t8' => 'gender'),
						't8.id = t1.gender', array('gender'));
            $select->where(array('t1.id' =>$applicant_id));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	 
	/*
	* Get the education details of the job applicant
	*/
	 
	public function getEducationDetails($tableName, $applicant_id, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			//var_dump($applicant_id); die();
			$select->from(array('t1' => 'employee_details'))
				   ->join(array('t2' => 'emp_education_details'),
				   		't1.id = t2.employee_details_id', array('id', 'college_name', 'college_location', 'college_country', 'field_study', 'study_mode', 'start_date', 'end_date', 'funding', 'marks_obtained', 'education_evidence_file' => 'evidence_file', 'academic_transcript' => 'evidence_file'))
				   ->join(array('t3' => 'study_level'),
						't3.id = t2.study_level', array('study_level'))
				   ->order(array('t2.end_date DESC'));
				   $select->where(array('t2.employee_details_id' => $applicant_id));
		} else{
			$select->from(array('t1' => 'job_applicant'))
				->join(array('t2' => 'emp_job_applications'),
						't1.id = t2.job_applicant_id')
				->join(array('t3' => 'job_application_education'),
						't2.id = t3.emp_job_applications_id', array('id', 'college_name', 'college_location', 'college_country', 'field_study', 'study_mode', 'start_date', 'end_date', 'funding', 'marks_obtained', 'education_evidence_file' => 'pass_certificate', 'academic_transcript'))
				    ->join(array('t4' => 'study_level'),
						't4.id = t3.study_level', array('study_level'));
			$select->where(array('t3.emp_job_applications_id' => $id));
		}
		
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	public function getApplicantMarksDetail($table_name, $job_applicant_id,$id)
	{
		//var_dump($table_name); die();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($table_name == 'employee_details'){
			
			$select->from(array('t1' => 'job_application_marks'))
				   ->join(array('t2' => 'emp_job_applications'),
						't2.id = t1.emp_job_applications_id', array('job_applicant_id'))
			   //->where(array('t2.employee_details_id' => $job_applicant_id))
			   ->where(array('t1.emp_job_applications_id ' => $id));
		} else if($table_name == 'job_applicant'){
			//var_dump($job_applicant_id); die();
			$select->from(array('t1' => 'job_application_marks'))
				   ->join(array('t2' => 'emp_job_applications'),
						't2.id = t1.emp_job_applications_id', array('job_applicant_id'))
			   //->where(array('t2.job_applicant_id' => $job_applicant_id));
			   ->where(array('t1.emp_job_applications_id ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	 
	/*
	* get employment details of the job applicant
	*/
	 
	public function getEmploymentDetails($tableName, $applicant_id, $id)
	{
		//var_dump($applicant_id); die();
		
		//if RUB Employee
		if($tableName == 'employee_details'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'job_application_employment_record'))
				   ->columns(array('id', 'start_period', 'end_period', 'employment_record_file' => 'evidence_file'))
					->join(array('t2' => 'organisation'), 
							't1.working_agency = t2.id', array('working_agency' => 'organisation_name'))
					->join(array('t3'=>'position_category'),
							't1.position_category = t3.id', array('position_category' => 'category'))
					->join(array('t4'=>'major_occupational_group'),
							't1.occupational_group = t4.id', array('occupational_group' => 'major_occupational_group'))
					->join(array('t5' => 'position_title'),
							't5.id = t1.position_title', array('position_title'))
					->join(array('t6' => 'position_level'),
							't6.id = t1.position_level', array('position_level'))
					->where(array('t1.employee_details_id' => $applicant_id))
					->where(array('t1.emp_job_applications_id' => $id))
					->order(array('t1.end_period DESC'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);	

		}else if($tableName == 'job_applicant'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'job_application_employment_record'))
					->where(array('t1.job_applicant_id' => $applicant_id))
					->where(array('t1.emp_job_applications_id' => $id));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
		
		//return $employment_details;
	}
	 
	/*
	* get training details of the job applicant
	*/
	 
	public function getTrainingDetails($tableName, $applicant_id)
	{
		$trainings = array();
		$index = 0;
		//var_dump($applicant_id); die();
		
		//if RUB Employee
		if($tableName == 'employee_details'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'emp_previous_trainings'))
						->columns(array('title'=>'course_title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_address', 'institute_country'=>'country',
										'start_date'=>'from_date','end_date' =>'to_date'))
						->where('t1.employee_details_id = ' .$applicant_id);
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $value){
				$trainings[$index][] = $value;
			}
					
			$select2 = $sql->select();		
			$select2->from(array('t1' => 'workshop_details'))
						->columns(array('title'=>'title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_location','institute_country'=>'institute_country', 'start_date'=>'workshop_start_date','end_date' =>'workshop_end_date'))
						->join(array('t2' => 'emp_workshop_details'), 
								't2.workshop_details_id = t1.id', array('id', 'employee_details_id', 'training_report' => NULL))
						->where('t2.employee_details_id = ' .$applicant_id);
			
			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();		
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			
			foreach($resultSet2 as $value){
				$trainings[$index][] = $value;
			}
			
		} else {
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'job_applicant_training_details'))
						->columns(array('id', 'title'=>'course_title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_address', 'institute_country'=>'country',
										'start_date'=>'from_date','end_date' =>'to_date', 'training_report' => 'training_certificate'))
						->where('t1.job_applicant_id = ' .$applicant_id);
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $value){
				$trainings[$index][] = $value;
			}
		}
		return $trainings;
	}
	 
	/*
	* get research details of the job applicant
	*/
	 
	public function getResearchDetails($tableName, $applicant_id)
	{
		$research = array();
		$index=0;
			
		//RUB Employee
		if($tableName == 'employee_details'){
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'emp_previous_research'))
						->columns(array('id', 'title'=>'publication_name', 'year'=>'publication_year', 'publisher', 'publication_no', 'research_evidence_file' => 'evidence_file'))
						->join(array('t2' => 'research_category'),
						't2.id = t1.research_type', array('type' => 'research_category'))
					->where('t1.employee_details_id = ' .$applicant_id);
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $value){
				$research[$index][] = $value;
			}
			
		} else {
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => 'job_applicant_research_details'))
		       ->columns(array('id', 'title'=>'publication_name', 'year'=>'publication_year', 'publisher', 'publication_no', 'research_evidence_file' => NULL))
				->join(array('t2' => 'research_category'),
						't2.id = t1.research_type', array('type' => 'research_category'))
					->where('t1.job_applicant_id = ' .$applicant_id);
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $value){
				$research[$index][] = $value;
			}
			
		}
		return $research;
	}
	
	
	public function getApplicantCommunityServices($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_community_service'))
			   ->columns(array('id', 'service_name', 'service_date', 'service_supporting_file' => 'supporting_file'));
		$select->where(array('t1.job_applicant_id' => $job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function getApplicantAwardDetail($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_awards'))
			   ->columns(array('id', 'award_name', 'award_date', 'award_given_by', 'award_supporting_file' => 'supporting_file'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function getApplicantMembershipDetail($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_memberships'))
			   ->columns(array('id', 'agency', 'position', 'start_period', 'end_period', 'member_supporting_file' => 'supporting_file'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getApplicantReferenceDetails($table_name, $job_applicant_id, $id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($table_name == 'employee_details'){
			$select->from(array('t1' => 'job_application_references'))
				   ->join(array('t2' => 'emp_job_applications'),
						't2.id = t1.emp_job_applications_id', array('job_applicant_id'))
			   ->where(array('t2.employee_details_id' => $job_applicant_id, 't1.emp_job_applications_id' => $id));
		}

		if($table_name == 'job_applicant'){
			$select->from(array('t1' => 'job_application_references'))
				   ->join(array('t2' => 'emp_job_applications'),
						't2.id = t1.emp_job_applications_id', array('job_applicant_id'))
			   ->where(array('t2.job_applicant_id' => $job_applicant_id, 't1.emp_job_applications_id' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getPresentJobDescription($table_name, $job_applicant_id)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$emp_job_details = array();

		if($table_name == 'employee_details'){
			$select->from(array('t1' => 'emp_position_title'))
				   ->join(array('t2' => 'position_title'),
						't2.id = t1.position_title_id', array('position_title'))
				   ->join(array('t3' => 'position_category'),
						't3.id = t2.position_category_id', array('position_category' => 'category'))
				   ->join(array('t4' => 'major_occupational_group'),
						't4.id = t3.major_occupational_group_id', array('major_occupational_group'))
				   ->join(array('t5' => 'employee_details'),
						't5.id = t1.employee_details_id', array('id', 'organisation_id', 'country'))
				   ->join(array('t6' => 'emp_position_level'),
						't5.id = t6.employee_details_id', array('position_level_id'))
				   ->join(array('t7' => 'position_level'),
						't7.id = t6.position_level_id', array('position_level'))
				   ->join(array('t8' => 'departments'),
						't8.id = t5.departments_id', array('department_name'))
			   ->where(array('t1.employee_details_id' => $job_applicant_id, 't6.employee_details_id' => $job_applicant_id));

			   $stmt = $sql->prepareStatementForSqlObject($select);
			   $result = $stmt->execute();
				
			    $resultSet = new ResultSet();
				$resultSet->initialize($result);

				foreach($resultSet as $set){
					$emp_job_details['position_title'] = $set['position_title'];
					$emp_job_details['position_category'] = $set['position_category'];
					$emp_job_details['major_occupational_group'] = $set['major_occupational_group'];
					$emp_job_details['position_level'] = $set['position_level'];
					$emp_job_details['position_level_id'] = $set['position_level_id'];
					$emp_job_details['organisation_id'] = $set['organisation_id'];
					$emp_job_details['department_name'] = $set['department_name'];
					$emp_job_details['country'] = $set['country'];
				}
		}

		else if($table_name == 'job_applicant'){
			$select->from(array('t1' => 'job_applicant_employment_record'))
				   ->columns(array('position_level', 'position_title', 'position_category', 'major_occupational_group' => 'occupational_group'))
			   		->where(array('t1.job_applicant_id' => $job_applicant_id));

			   $stmt = $sql->prepareStatementForSqlObject($select);
			   $result = $stmt->execute();
				
			    $resultSet = new ResultSet();
				$resultSet->initialize($result);
			   foreach($resultSet as $set){
					$emp_job_details['position_title'] = $set['position_title'];
					$emp_job_details['position_category'] = $set['position_category'];
					$emp_job_details['major_occupational_group'] = $set['major_occupational_group'];
					$emp_job_details['position_level'] = $set['position_level'];
					$emp_job_details['position_level_id'] = NULL;
				}
		} 
		return $emp_job_details;		
	}


	public function getLanguageDetails($employee_details_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'staff'){
			$select->from(array('t1' => 'emp_languages'))
		       ->join(array('t2' => 'languages'),
		   			't2.id = t1.languages_id', array('language'))
		   ->where(array('t1.employee_details_id' => $employee_details_id));
		} else {
			$select->from(array('t1' => 'job_applicant_languages'))
		       ->join(array('t2' => 'languages'),
		   			't2.id = t1.language', array('language'))
		   ->where(array('t1.job_applicant_id' => $employee_details_id));
		}
		
	   $stmt = $sql->prepareStatementForSqlObject($select);
	   $result = $stmt->execute();
		
	   $resultSet = new ResultSet();
	   return $resultSet->initialize($result);
	}


	public function getApplicantPromotionDetails($table_name, $job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$emp_job_details = array();

		if($table_name == 'employee_details'){
			$select->from(array('t1' => 'emp_promotion'))
			   ->where(array('t1.employee_details_id' => $job_applicant_id, 't1.promotion_status' => 'Approved'))
			   ->order('promotion_effective_date DESC');
			$select->limit(1);

		   $stmt = $sql->prepareStatementForSqlObject($select);
		   $result = $stmt->execute();
			
		   $resultSet = new ResultSet();
		   return $resultSet->initialize($result);

		}

		else if($table_name == 'job_applicant'){
			$select->from(array('t1' => 'job_applicant'))
			   		->where(array('t1.id' => $job_applicant_id));

		    $stmt = $sql->prepareStatementForSqlObject($select);
		    $result = $stmt->execute();
			
		    $resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}
	
	
	public function getApplicantDocuments($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_job_applications'))
			   ->columns(array('id', 'cid' => 'identity_proof', 'security_clearance_no', 'security_clearance_file', 'medical_clearance_no', 'medical_clearance_file', 'audit_clearance_no', 'audit_clearance_file', 'tax_clearance_no', 'tax_clearance_file', 'other_certificate_description', 'other_certificate_file'))
			   ->where(array('t1.id' => $job_applicant_id));
		
	   $stmt = $sql->prepareStatementForSqlObject($select);
	   $result = $stmt->execute();
		
	   $resultSet = new ResultSet();
	   return $resultSet->initialize($result);
	}
	
	
	//Function to get the document list
	public function getApplicantDocumentList($table_name, $job_applicant_id, $type)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'awards'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'emp_awards'))
					   ->columns(array('id', 'award_evidence_file' => 'evidence_file', 'award_category_id'))
				       ->join(array('t2' => 'emp_award_category'),
							't2.id = t1.award_category_id', array('award_category'))
				  ->where(array('t1.employee_details_id' => $job_applicant_id));
			}
			else {
			$select->from(array('t1' => 'job_applicant_awards'))
				   ->columns(array('id', 'award_evidence_file' => 'supporting_file', 'award_category' => 'award_name'))
				   ->where(array('t1.job_applicant_id' => $job_applicant_id));
			}	
		}
		
		else if($type == 'community_services'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'emp_community_services'))
					   ->columns(array('id', 'service_evidence_file' => 'evidence_file', 'community_service_category_id'))
				       ->join(array('t2' => 'emp_community_service_category'),
							't2.id = t1.community_service_category_id', array('community_service_category'))
				  ->where(array('t1.employee_details_id' => $job_applicant_id));
			}
			else{
			$select->from(array('t1' => 'job_applicant_community_service'))
				   ->columns(array('id', 'service_evidence_file' => 'supporting_file', 'community_service_category' => 'service_name'))
				   ->where(array('t1.job_applicant_id' => $job_applicant_id));
			}
			
		}
		
		else if($type == 'membership'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'employee_details'))
				   ->where(array('t1.id' => $job_applicant_id));
			}
			else {
				$select->from(array('t1' => 'job_applicant_memberships'))
				   ->columns(array('id', 'member_supporting_file' => 'supporting_file', 'position', 'agency'))
				   ->where(array('t1.job_applicant_id' => $job_applicant_id));
			}
			
		}
		
		else if($type == 'contributions'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'emp_contributions'))
					   ->columns(array('id', 'contribution_category_id', 'contribution_evidence_file' => 'evidence_file'))
					   ->join(array('t2' => 'emp_contribution_category'),
							't2.id = t1.contribution_category_id', array('contribution_category'))
				   ->where(array('t1.employee_details_id' => $job_applicant_id));
			}
			else {
				$select->from(array('t1' => 'job_applicant'))
				   ->where(array('t1.id' => $job_applicant_id));
			}
			
		}
		
		else if($type == 'disciplinary'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'emp_disciplinary_record'))
					   ->columns(array('id', 'disciplinary_details', 'disciplinary_evidence_file' => 'evidence_file'))
					   ->join(array('t2' => 'discipline_category'),
							't2.id = t1.disciplinary_details', array('discipline_category'))
				   ->where(array('t1.employee_details_id' => $job_applicant_id));
			}
			else {
				$select->from(array('t1' => 'job_applicant'))
				   ->where(array('t1.id' => $job_applicant_id));
			}
			
		}
		
		else if($type == 'responsibility'){
			if($table_name == 'employee_details'){
				$select->from(array('t1' => 'emp_responsibilities'))
					   ->columns(array('id', 'responsibility_category_id', 'responsibility_evidence_file' => 'evidence_file'))
					   ->join(array('t2' => 'emp_responsibility_category'),
							't2.id = t1.responsibility_category_id', array('responsibility_category'))
				   ->where(array('t1.employee_details_id' => $job_applicant_id));
			}
			else {
				$select->from(array('t1' => 'job_applicant'))
				   ->where(array('t1.id' => $job_applicant_id));
			}
			
		}
		
	   $stmt = $sql->prepareStatementForSqlObject($select);
	   $result = $stmt->execute();
		
	   $resultSet = new ResultSet();
	   return $resultSet->initialize($result);
	}
	
	
	//Function to get the uploaded file link for download
	public function getFileName($file_id, $column_name)
	{ 
		//var_dump($file_id); var_dump($column_name); die();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($column_name == 'profile_picture'){
			$select->from(array('t1' => 'job_applicant')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
			
		}
		else if($column_name == 'education_evidence_file'){
			$select->from(array('t1' => 'job_application_education')) 
				->columns(array($column_name => 'pass_certificate'))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'academic_transcript'){
			$select->from(array('t1' => 'job_application_education')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'training_report'){
			$select->from(array('t1' => 'job_applicant_training_details')) 
				->columns(array($column_name => 'training_certificate'))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'research_evidence_file'){
			$select->from(array('t1' => 'job_applicant_research_details')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'employment_record_file'){
			$select->from(array('t1' => 'job_application_employment_record')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'promotion_order_file'){
			$select->from(array('t1' => 'emp_promotion')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'meritorious_promotion_file'){
			$select->from(array('t1' => 'emp_promotion')) 
				->columns(array($column_name))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'cid'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name => 'identity_proof'))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'security_clearance_file'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'medical_clearance_file'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'audit_clearance_file'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'tax_clearance_file'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'other_certificate_file'){
				$select->from(array('t1' => 'emp_job_applications')) 
                    ->columns(array($column_name))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'award_supporting_file'){
				$select->from(array('t1' => 'job_applicant_awards')) 
                    ->columns(array($column_name => 'supporting_file'))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'service_supporting_file'){
			$select->from(array('t1' => 'job_applicant_community_service')) 
				->columns(array($column_name => 'supporting_file'))
				->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'member_supporting_file'){
				$select->from(array('t1' => 'job_applicant_memberships')) 
                    ->columns(array($column_name => 'supporting_file'))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'contribution_evidence_file'){
				$select->from(array('t1' => 'emp_contributions')) 
                    ->columns(array($column_name => 'evidence_file'))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'disciplinary_evidence_file'){
				$select->from(array('t1' => 'emp_disciplinary_record')) 
                    ->columns(array($column_name => 'evidence_file'))
					->where('t1.id = ' .$file_id);
		}
		
		else if($column_name == 'responsibility_evidence_file'){
				$select->from(array('t1' => 'emp_responsibilities')) 
                    ->columns(array($column_name => 'evidence_file'))
					->where('t1.id = ' .$file_id);
		}
				
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/EmpWorkForceProposal()
	*/
	public function listAllProposals($organisation_id)
	{
		//$position_level = array('Position Level 9', 'Position Level 10', 'Position Level 11', 'Position Level 12', 'Position Level 13', 'Position Level 14', 'Position Level 15',
		//'Position Level 16', 'Position Level 17');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'hr_management'))
				 ->join(array('t2' => 'position_level'), 
					't1.position_level_id = t2.id', array('position_level'))
                                ->join(array('t3' => 'organisation'), 
					't1.working_agency = t3.id', array('organisation_name'));
		$select->where(array('proposal_status = ? ' => 'Approved'));
		if($organisation_id != 1){
			$select->where(array('t2.description ' => 'Administrative'));
                        $select->where(array('t3.id ' => $organisation_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);

	}
	
	/*
	 * Get list of Job Applicants
	 */
	 
	public function listJobApplicants($type,$status, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($type == 'in_service'){
			if($status == 'Selected'){
				$select->from(array('t1' => 'emp_job_applications'))
                    ->join(array('t2' => 'vacancy_announcements'), 
                            't1.vacancy_announcements_id = t2.id', array('working_agency','area'))
					->join(array('t3' => 'employee_details'), 
                            't1.employee_details_id = t3.id', array('first_name','middle_name','last_name', 'cid', 'phone_no','email'))
					->join(array('t4' => 'position_title'), 
                            't2.position_title = t4.id', array('position_title'))
					->join(array('t5' => 'organisation'), 
                            't2.working_agency = t5.id', array('organisation_name','abbr'))
					->where(array('t2.organisation_id = ? ' => $organisation_id));
				$select->where->notEqualTo('t1.status','Submitted');
				$select->where->notEqualTo('t1.status','Short Listed');
				$select->where->notEqualTo('t1.status','Rejected');
			}else{
				$select->from(array('t1' => 'emp_job_applications'))
                    ->join(array('t2' => 'vacancy_announcements'), 
                            't1.vacancy_announcements_id = t2.id', array('working_agency','area'))
					->join(array('t3' => 'employee_details'), 
                            't1.employee_details_id = t3.id', array('first_name','middle_name','last_name', 'cid', 'phone_no','email'))
					->join(array('t4' => 'position_title'), 
                            't2.position_title = t4.id', array('position_title'))
					->join(array('t5' => 'organisation'), 
                            't2.working_agency = t5.id', array('organisation_name','abbr'))
					->where(array('t2.organisation_id = ? ' => $organisation_id))	
					->where(array('t1.status = ? ' => $status))
					->where(array('t2.status = ?' => 'Open'));
			}
		} else {
			if($status == 'Selected'){
				$select->from(array('t1' => 'emp_job_applications'))
                    ->join(array('t2' => 'vacancy_announcements'), 
                            't1.vacancy_announcements_id = t2.id', array('working_agency','area'))
					->join(array('t3' => 'job_applicant'), 
                            't1.job_applicant_id = t3.id', array('first_name','middle_name','last_name', 'cid', 'contact_no','email'))
					->join(array('t4' => 'position_title'), 
                            't2.position_title = t4.id', array('position_title'))
					->join(array('t5' => 'organisation'), 
                            't2.working_agency = t5.id', array('organisation_name','abbr'))
					->where(array('t2.organisation_id = ? ' => $organisation_id));
				$select->where->notEqualTo('t1.status','Submitted');
				$select->where->notEqualTo('t1.status','Short Listed');
				$select->where->notEqualTo('t1.status','Rejected');
			}else{
				$select->from(array('t1' => 'emp_job_applications'))
                    ->join(array('t2' => 'vacancy_announcements'), 
                            't1.vacancy_announcements_id = t2.id', array('working_agency','area'))
					->join(array('t3' => 'job_applicant'), 
                            't1.job_applicant_id = t3.id', array('first_name','middle_name','last_name', 'cid', 'contact_no','email'))
					->join(array('t4' => 'position_title'), 
                            't2.position_title = t4.id', array('position_title'))
					->join(array('t5' => 'organisation'), 
                            't2.working_agency = t5.id', array('organisation_name','abbr'))
					->where(array('t2.organisation_id = ? ' => $organisation_id))	
					->where(array('t1.status = ? ' => $status))
					->where(array('t2.status = ?' => 'Open'));
			}
		}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function listJobApplicantsLatestEducation($type)
	{
		$educationDetails = array();
		$index = 0;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($type == 'in_service'){
			$select->from(array('t1' => 'emp_education_details'))
					->order(array('t1.end_date DESC'));
		} 		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$educationDetails[$set['employee_details_id']][$set['college_name']][$set['field_study']] = $set['field_study'];
		} 
		return $educationDetails;
	}
	
	/*
	 * Update Job Application i.e. Selected, Shortlisted or Rejected
	 */
	 
	public function updateJobApplication($id, $status)
	{
		//ID present, so it is an update
		$action = new Update('emp_job_applications');
		$action->set(array(
					'status'=> $status,
				));
		$action->where(array('id = ?' => $id));
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/*
	 * Update Job Applicant Details i.e. once selected update details into the employee details table
	 */
	 
	public function updateJobApplicantDetails($table_name, $job_applicant_id, $data, SelectedApplicant $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject); 
		unset($jobData['id']);
		unset($jobData['emp_Category']);
		unset($jobData['occupational_Group']);
		unset($jobData['emp_Id']); 

		if($table_name == 'job_applicant'){
			$category = 'Outsider';
		}

		// Get job applicant details from job_applicant table
		$applicantDetails = array();
		$selectedApplicantDetails = $this->getApplicantDetail($job_applicant_id, $category);
		foreach($selectedApplicantDetails as $details){
			$applicantDetails = $details;
		}

		$jobData['organisation_Id'] = $data['organisation_id'];
		$jobData['departments_Id'] = $data['departments_id'];
		$jobData['departments_Units_Id'] = $data['departments_units_id']; 
		$jobData['status'] = $data['status'];
		$jobData['religion'] = $data['religion'];
		$jobData['blood_Group'] = $data['blood_group'];
		$jobData['position_Title_Id'] = $data['position_title_id'];
		$jobData['position_Level_Id'] = $data['position_level_id'];
		$jobData['emp_Type'] = $data['emp_type'];
		
		$jobData['first_Name'] = $applicantDetails['first_name'];
		$jobData['middle_Name'] = $applicantDetails['middle_name'];
		$jobData['last_Name'] = $applicantDetails['last_name'];
		$jobData['cid'] = $applicantDetails['cid'];
		$jobData['date_Of_Birth'] = $applicantDetails['date_of_birth'];
		$jobData['nationality'] = $applicantDetails['nationality'];
		$jobData['emp_House_No'] = $applicantDetails['house_no'];
		$jobData['emp_Thram_No'] = $applicantDetails['thram_no'];
		$jobData['emp_Dzongkhag'] = $applicantDetails['dzongkhag'];
		$jobData['emp_Gewog'] = $applicantDetails['gewog'];
		$jobData['emp_Village'] = $applicantDetails['village'];
		$jobData['country'] = $applicantDetails['country'];
		$jobData['gender'] = $applicantDetails['gender'];
		$jobData['marital_Status'] = $applicantDetails['maritial_status'];
		$jobData['phone_No'] = $applicantDetails['contact_no'];
		$jobData['email'] = $applicantDetails['email'];
		$jobData['job_Applicant_Id'] = $job_applicant_id;
		$jobData['emp_Job_Applications_Id'] = $data['emp_job_applications_id'];
		$jobData['recruitment_Date'] = date("Y-m-d", strtotime(substr($data['recruitment_date'],0,10))); 

		$newEmpDocData = array();
		$announcement_doc = $jobData['announcement_Doc'];
		$newEmpDocData['announcement_Doc'] = $announcement_doc['tmp_name'];

		$shortlist_doc = $jobData['shortlist_Doc'];
		$newEmpDocData['shortlist_Doc'] = $shortlist_doc['tmp_name'];

		$selection_doc = $jobData['selection_Doc'];
		$newEmpDocData['selection_Doc'] = $selection_doc['tmp_name'];

		$minutes_doc = $jobData['minutes_Doc'];
		$newEmpDocData['minutes_Doc'] = $minutes_doc['tmp_name'];

		$emp_application_form_doc = $jobData['emp_Application_Form_Doc'];
		$newEmpDocData['emp_Application_Form_Doc'] = $emp_application_form_doc['tmp_name'];

		$emp_academic_transcript_doc = $jobData['emp_Academic_Transcript_Doc'];
		$newEmpDocData['emp_Academic_Transcript_Doc'] = $emp_academic_transcript_doc['tmp_name'];

		$emp_training_doc = $jobData['emp_Training_Doc'];
		$newEmpDocData['emp_Training_Doc'] = $emp_training_doc['tmp_name'];

		$emp_cid_wp_doc = $jobData['emp_Cid_Wp_Doc'];
		$newEmpDocData['emp_Cid_Wp_Doc'] = $emp_cid_wp_doc['tmp_name'];

		$emp_security_cl_doc = $jobData['emp_Security_Cl_Doc'];
		$newEmpDocData['emp_Security_Cl_Doc'] = $emp_security_cl_doc['tmp_name'];

		$emp_medical_doc = $jobData['emp_Medical_Doc'];
		$newEmpDocData['emp_Medical_Doc'] = $emp_medical_doc['tmp_name'];

		$emp_no_objec_doc = $jobData['emp_No_Objec_Doc'];
		$newEmpDocData['emp_No_Objec_Doc'] = $emp_no_objec_doc['tmp_name'];

		$appointment_order_doc = $jobData['appointment_Order_Doc'];
		$newEmpDocData['appointment_Order_Doc'] = $appointment_order_doc['tmp_name'];

		$others_doc = $jobData['others_Doc'];
		$newEmpDocData['others_Doc'] = $others_doc['tmp_name'];

		unset($jobData['announcement_Doc']);
		unset($jobData['shortlist_Doc']);
		unset($jobData['selection_Doc']);
		unset($jobData['minutes_Doc']);
		unset($jobData['emp_Application_Form_Doc']);
		unset($jobData['emp_Academic_Transcript_Doc']);
		unset($jobData['emp_Training_Doc']);
		unset($jobData['emp_Cid_Wp_Doc']);
		unset($jobData['emp_Security_Cl_Doc']);
		unset($jobData['emp_Medical_Doc']);
		unset($jobData['emp_No_Objec_Doc']);
		unset($jobData['appointment_Order_Doc']);
		unset($jobData['others_Doc']);
		unset($jobData['new_Employee_Details_Id']); 

		$emp_job_application_id = $data['id']; 
		//var_dump($emp_job_application_id); echo '<br>'; 
		//var_dump($jobData); die();

		//To insert into the employee_details table
		$action = new Insert('new_employee_details');
		$action->values($jobData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}

			$this->addNewEmpDocument($newId, $newEmpDocData);
			$this->updateEmpJobApplication($emp_job_application_id);

			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}


	//Function to save the new employee documents
	public function addNewEmpDocument($new_employee_details_id, $newEmpDocData)
	{
		$newEmployeeData = array();
		$newEmployeeData['announcement_Doc'] = $newEmpDocData['announcement_Doc'];
		$newEmployeeData['shortlist_Doc'] = $newEmpDocData['shortlist_Doc'];
		$newEmployeeData['selection_Doc'] = $newEmpDocData['selection_Doc'];
		$newEmployeeData['minutes_Doc'] = $newEmpDocData['minutes_Doc'];
		$newEmployeeData['emp_Application_Form_Doc'] = $newEmpDocData['emp_Application_Form_Doc'];
		$newEmployeeData['emp_Academic_Transcript_Doc'] = $newEmpDocData['emp_Academic_Transcript_Doc'];
		$newEmployeeData['emp_Training_Doc'] = $newEmpDocData['emp_Training_Doc'];
		$newEmployeeData['emp_Cid_Wp_Doc'] = $newEmpDocData['emp_Cid_Wp_Doc'];
		$newEmployeeData['emp_Security_Cl_Doc'] = $newEmpDocData['emp_Security_Cl_Doc'];
		$newEmployeeData['emp_Medical_Doc'] = $newEmpDocData['emp_Medical_Doc'];
		$newEmployeeData['emp_No_Objec_Doc'] = $newEmpDocData['emp_No_Objec_Doc'];
		$newEmployeeData['appointment_Order_Doc'] = $newEmpDocData['appointment_Order_Doc'];
		$newEmployeeData['others_Doc'] = $newEmpDocData['others_Doc'];
		$newEmployeeData['new_Employee_Details_Id'] = $new_employee_details_id;

		//To insert into the employee_details table
		$action = new Insert('new_employee_documents');
		$action->values($newEmployeeData);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
			}

			return;
		}
	}


	public function updateEmpJobApplication($id)
	{ 
		$action = new Update('emp_job_applications');
		$action->set(array('status' => 'Selected Updated'));
		$action->where(array('id = ?' => $id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}


	public function saveJobApplicantMarks(JobApplicantMarks $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		//var_dump($jobData['job_Applicant_Id']); die();
		//unset($jobData['id']); 
		
		if($jobData['job_Applicant_Id']){
			$x_aggregate = NULL;
			$xii_aggregate = NULL;
			if($jobData['x_English'] != NULL){
				$x_aggregate = ($jobData['x_English']+$jobData['x_Sub1_Mark']+$jobData['x_Sub2_Mark']+$jobData['x_Sub3_Mark']+$jobData['x_Sub4_Mark'])/5;
			}else{
				$x_aggregate = 0.00;
			}

			if($jobData['xll_English'] != NULL){
				$xii_aggregate = ($jobData['xll_English']+$jobData['xll_Sub1_Mark']+$jobData['xll_Sub2_Mark']+$jobData['xll_Sub3_Mark'])/4;
			}else{
				$xii_aggregate = 0.00;
			}

			if($jobData['x_English'] != NULL)
			{
				$this->updateJobApplicantMarks($jobData['job_Applicant_Id'], $x_aggregate, 'ten');
			}
			if($jobData['xll_English'] != NULL){
				$this->updateJobApplicantMarks($jobData['job_Applicant_Id'], $xii_aggregate, 'twelve');
			}
			
			if($jobObject->getId()) {
				//ID present, so it is an update

				$action = new Update('job_application_marks');
				$action->set($jobData);
				$action->where(array('emp_job_applications_id = ?' => $jobData['id']));

			} else {
				//ID is not present, so its an insert
				$action = new Insert('job_applicant_marks');
				$action->values($jobData);
			}	
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $jobObject->setId($newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}


	// FUnction to update the class 10 and 12 aggregate
	public function updateJobApplicantMarks($job_applicant_id, $aggregate, $type)
	{
		if($type == 'ten'){
			$action = new Update('job_applicant_education');
			$action->set(array('marks_obtained' => $aggregate));
			$action->where(array('job_applicant_id = ?' => $job_applicant_id, 'study_level' => '4'));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		else if($type == 'twelve'){
			$action = new Update('job_applicant_education');
			$action->set(array('marks_obtained' => $aggregate));
			$action->where(array('job_applicant_id = ?' => $job_applicant_id, 'study_level' => '5'));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}	
	}
        
        /*
          * Get the list of Job Applicants selected by Colleges for OVC to update
          */
         
        public function listRecruitedCandidates()
        {
             $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_recruitment_details'))
                    ->where(array('t1.status = ? ' => 'Pending'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
        }
        
        /*
	 * Update Job Applicant Details i.e. once selected update details into the employee details table by OVC
	 */
	 
	 public function updateSelectedCandidateDetails($table_name, $job_applicant_id, SelectedApplicant $jobObject, $data)
         {
             //get the employee details id and id
		if($table_name == 'employee_details'){
			$job_applicant_details = $this->getJobApplicantDetail($job_applicant_id);
			$data['employee_details_id'] = $job_applicant_details['employee_details_id'];
		} else {
			//generate new employee details id
			$data['emp_id'] = $this->generateEmployeeId();
		}
		
		//employee details
		$employeeData['organisation_Id'] = $data['organisation_id'];
		$employeeData['departments_Id'] = $data['departments_id'];
		$employeeData['departments_Units_Id'] = $data['departments_units_id'];
			
		if($table_name == 'employee_details'){
			//in service candidate, so update the employee details table
			$action = new Update('employee_details');
			$action->set($employeeData);
			$action->where(array('emp_id = ?' => $data['employee_details_id']));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();			
		} else {
			//additional employee data which will be retrieved from job applicant table
			$temp_personal_details = $this->getPersonalDetails('job_applicant', $job_applicant_id);
			foreach($temp_personal_details as $detail ){
				$applicant_personal_details = $detail;
			}
			$employeeData['emp_Id'] = $data['emp_id'];
			$employeeData['first_Name'] = $applicant_personal_details['first_name'];
			$employeeData['middle_Name'] = $applicant_personal_details['middle_name'];
			$employeeData['last_Name'] = $applicant_personal_details['last_name'];
			$employeeData['cid'] = $applicant_personal_details['cid'];
			$employeeData['nationality'] = $applicant_personal_details['nationality'];
			$employeeData['date_Of_Birth'] = $applicant_personal_details['date_of_birth'];
			$employeeData['emp_House_No'] = $applicant_personal_details['house_no'];
			$employeeData['emp_Thram_No'] = $applicant_personal_details['thram_no'];
			$employeeData['emp_Dzongkhag'] = $applicant_personal_details['dzongkhag'];
			$employeeData['country'] = $applicant_personal_details['country'];
			$employeeData['recruitment_Date'] = $data['date_of_appointment'];
			$employeeData['emp_Category'] = $data['position_category'];
			$employeeData['gewog'] = $applicant_personal_details['gewog'];
			$employeeData['village'] = $applicant_personal_details['village'];
			$employeeData['gender'] = $applicant_personal_details['gender'];
			$employeeData['martial_Status'] = $applicant_personal_details['martial_status'];
			
			//new candidate, so need to get all details and then insert into employee details
			$action = new Insert('employee_details');
			$action->values($employeeData);
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$data['employee_details_id']= $newId;
				//echo $data['employee_details_id']= $newId;
			}
			//position level
			$positionLevelData['date'] = date("Y-m-d", strtotime(substr($data['date_of_appointment'],0,10)));
			$positionLevelData['position_Level_Id'] = $data['position_level'];
			$positionLevelData['employee_Details_Id'] = $data['employee_details_id'];
			//position title
			$positionTitleData['date'] = $data['date_of_appointment'];
			$positionTitleData['position_Title_Id'] = $data['position_title'];
			$positionTitleData['employee_Details_Id'] = $data['employee_details_id'];
			
			$this->insertPositionLevel($positionLevelData);
			$this->insertPositionTitle($positionTitleData);
			$this->insertEmployeeEducationDetails($job_applicant_id, $data['employee_details_id']);
			$this->insertEmployeeTrainingDetails($job_applicant_id, $data['employee_details_id']);
			$this->insertEmployeeAwards($job_applicant_id, $data['employee_details_id']);
			$this->insertWorkExperience($job_applicant_id, $data['employee_details_id']);
			$this->insertResearchDetails($job_applicant_id, $data['employee_details_id']);
			$this->insertCommunityService($job_applicant_id, $data['employee_details_id']);
			
			return $data;
		}
		
		throw new \Exception("Database Error");
         }
	
	/*
	* The following function is to retrieve the values from the position directory
	*/
	
	public function getPositionDetails($position_title)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'position_directory'))
                    ->join(array('t2' => 'position_title'), 
                            't1.position_title = t2.position_title', array('position_title'))
                    ->where(array('t2.id = ? ' => $position_title));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Function to generate the employee id for new employee
	*/
	
	public function generateEmployeeId()
	{
		//format for employee id
		$Year = date('Y');
		$format = 'RUB'.substr($Year, 2).date('m');
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->columns(array('emp_id'));
		$select->where->like('emp_id','%'.$format.'%');
		$select->order('emp_id DESC');
		$select->limit(1);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$emp_id = NULL;
		
		foreach($resultSet as $set)
			$emp_id = $set['emp_id'];
		
		//first employee of the year
		if($emp_id == NULL){
			$generated_id = 'RUB'.substr(date('Y'),2).date('m').'001';
		}
		else{
			//need to get the last 3 digits and increment it by 1 and convert it back to string
			$number = substr($emp_id, -3);
			$number = (int)$number+1;
			$number = strval($number);
			while (mb_strlen($number)<3)
				$number = '0'. strval($number);
			
			$generated_id = 'RUB'.substr(date('Y'),2).date('m').$number;
		}
		
		return $generated_id;
	}
	
	/*
	* Update Employee Position Level after an applicant is selected
	*/
	
	public function insertPositionLevel($data)
	{
		//insert to emp position level
		$action = new Insert('emp_position_level');
		$action->values($data);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/*
	* Update Employee Position Title after an applicant is selected
	*/
	
	public function insertPositionTitle($data)
	{
		//insert into emp position title
		$action = new Insert('emp_position_title');
		$action->values($data);
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}
	
	/*
	* Update Employee Education Details after an applicant is selected
	*/
	
	public function insertEmployeeEducationDetails($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_education'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$education_details = array();
		foreach($resultSet as $set){
			$education_details['study_Level'] = $set['study_level'];
			$education_details['college_Name'] = $set['college_name'];
			$education_details['college_Location'] = $set['college_location'];
			$education_details['college_Country'] = $set['college_country'];
			$education_details['field_Study'] = $set['field_study'];
			$education_details['study_Mode'] = $set['study_mode'];
			$education_details['start_Date'] = $set['start_date'];
			$education_details['end_Date'] = $set['end_date'];
			$education_details['funding'] = $set['funding'];
			$education_details['result_Obtained'] = $set['marks_obtained'];
		}
		
		//insert into emp education details
		$action = new Insert('emp_education_details');
		$action->values($education_details);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}
	
	/*
	* Update Employee Training Details after an applicant is selected
	*/
	
	public function insertEmployeeTrainingDetails($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_training_details'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$training_details = array();
		foreach($resultSet as $set){
			$training_details['course_Title'] = $set['course_title'];
			$training_details['institute_Name'] = $set['institute_name'];
			$training_details['institute_Address'] = $set['institute_address'];
			$training_details['country'] = $set['country'];
			$training_details['from_Date'] = $set['from_date'];
			$training_details['to_Date'] = $set['to_date'];
			$training_details['funding'] = $set['funding'];
		}
		
		//insert into emp previous trainings
		$action = new Insert('emp_previous_trainings');
		$action->values($training_details);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}
	
	/*
	* Update Employee Awardsl after an applicant is selected
	*/
	
	public function insertEmployeeAwards($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_awards'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$awards = array();
		foreach($resultSet as $set){
			$awards['award_Name'] = $set['award_name'];
			$awards['award_Date'] = $set['award_date'];
			$awards['award_Given_By'] = $set['award_given_by'];
			$awards['award_Reasons'] = $set['award_reasons'];
		}
		
		//insert into emp previous trainings
		$action = new Insert('emp_awards');
		$action->values($awards);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}
	
	/*
	* Update Employee Work Experience after an applicant is selected
	*/
	
	public function insertWorkExperience($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_employment_record'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$work_experience = array();
		foreach($resultSet as $set){
			$work_experience['working_Agency'] = $set['working_agency'];
			$work_experience['occupational_Group'] = $set['occupational_group'];
			$work_experience['position_Category'] = $set['position_category'];
			$work_experience['position_Title'] = $set['position_title'];
			$work_experience['position_Level'] = $set['position_level'];
			$work_experience['start_Period'] = $set['start_period'];
			$work_experience['end_Period'] = $set['end_period'];
			$work_experience['remarks'] = $set['remarks'];
		}
		
		//insert into emp previous trainings
		$action = new Insert('emp_employment_record');
		$action->values($work_experience);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}
	
	/*
	* Update Employee Research Details after an applicant is selected
	*/
	
	public function insertResearchDetails($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_research_details'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$research_details = array();
		foreach($resultSet as $set){
			$research_details['publication_Year'] = $set['publication_year'];
			$research_details['publication_Name'] = $set['publication_name'];
			$research_details['research_Type'] = $set['research_type'];
			$research_details['publisher'] = $set['publisher'];
			$research_details['publication_Url'] = $set['publication_url'];
			$research_details['publication_No'] = $set['publication_no'];
			$research_details['author_Level'] = $set['author_level'];
		}
		
		//insert into emp previous research
		$action = new Insert('emp_previous_research');
		$action->values($research_details);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}
	
	/*
	* Update Employee Community Service after an applicant is selected
	*/
	
	public function insertCommunityService($job_applicant_id, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'job_applicant_community_service'))
                    ->where(array('t1.id = ? ' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$community_service = array();
		foreach($resultSet as $set){
			$community_service['service_Name'] = $set['service_name'];
			$community_service['service_Date'] = $set['service_date'];
			$community_service['remarks'] = $set['remarks'];
		}
		
		//insert into emp community service
		$action = new Insert('emp_community_service');
		$action->values($community_service);
		$sql2 = new Sql($this->dbAdapter);
		$stmt2 = $sql2->prepareStatementForSqlObject($action);
		$result2 = $stmt2->execute();
		
		return;
	}


	public function listAllAppliedApplicant($type, $organisation_id)
	{

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'in_service'){
			$select->from(array('t1' => 'emp_job_applications')) 
				->join(array('t2' => 'vacancy_announcements'), 
						't1.vacancy_announcements_id = t2.id', array('area','additional_position_title'))
				->join(array('t3' => 'position_title'), 
						't2.position_title  = t3.id', array('position_title'))
				->join(array('t4' => 'employee_details'), 
						't1.employee_details_id = t4.id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
				->join(array('t5' => 'job_application_marks'),
						't5.emp_job_applications_id = t1.id', array('x_english', 'x_sub1_mark', 'x_sub2_mark', 'x_sub3_mark', 'x_sub4_mark', 'xll_english', 'xll_sub1_mark', 'xll_sub2_mark', 'xll_sub3_mark'))
				->join(array('t6' => 'organisation'), 
						't2.working_agency  = t6.id', array('abbr'))
				->where(array('t2.organisation_id' => $organisation_id))
				->order('t2.id DESC');
		}
		else if($type == 'outsider'){
			$select->from(array('t1' => 'emp_job_applications')) 
				->join(array('t2' => 'vacancy_announcements'), 
						't1.vacancy_announcements_id = t2.id', array('area','additional_position_title'))
				->join(array('t3' => 'position_title'), 
						't2.position_title  = t3.id', array('position_title'))
				->join(array('t4' => 'job_applicant'), 
						't1.job_applicant_id = t4.id', array('first_name', 'middle_name', 'last_name','cid','contact_no'))
				->join(array('t5' => 'job_application_marks'),
						't5.emp_job_applications_id = t1.id', array('x_english', 'x_sub1_mark', 'x_sub2_mark', 'x_sub3_mark', 'x_sub4_mark', 'xll_english', 'xll_sub1_mark', 'xll_sub2_mark', 'xll_sub3_mark'))
				->join(array('t6' => 'organisation'), 
						't2.working_agency  = t6.id', array('abbr'))
				->where(array('t2.organisation_id' => $organisation_id))
				->order('t2.id DESC')
				->limit(20);
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	

	public function listAppliedApplicants($type, $position_title, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'in_service'){
			$select->from(array('t1' => 'emp_job_applications')) 
				->join(array('t2' => 'vacancy_announcements'), 
						't1.vacancy_announcements_id = t2.id', array('area','additional_position_title'))
				->join(array('t3' => 'position_title'), 
						't2.position_title  = t3.id', array('position_title'))
				->join(array('t4' => 'employee_details'), 
						't1.employee_details_id = t4.id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
				->join(array('t5' => 'job_application_marks'),
						't5.emp_job_applications_id = t1.id', array('x_english', 'x_sub1_mark', 'x_sub2_mark', 'x_sub3_mark', 'x_sub4_mark', 'xll_english', 'xll_sub1_mark', 'xll_sub2_mark', 'xll_sub3_mark'))
				->join(array('t6' => 'organisation'), 
						't2.working_agency  = t6.id', array('abbr'))
				->where(array('t2.organisation_id' => $organisation_id))
				->order('t2.id DESC');
		}
		else if($type == 'outsider'){
			$select->from(array('t1' => 'emp_job_applications')) 
				->join(array('t2' => 'vacancy_announcements'), 
						't1.vacancy_announcements_id = t2.id', array('area','additional_position_title'))
				->join(array('t3' => 'position_title'), 
						't2.position_title  = t3.id', array('position_title'))
				->join(array('t4' => 'job_applicant'), 
						't1.job_applicant_id = t4.id', array('first_name', 'middle_name', 'last_name','cid','contact_no'))
				->join(array('t5' => 'job_application_marks'),
						't5.emp_job_applications_id = t1.id', array('x_english', 'x_sub1_mark', 'x_sub2_mark', 'x_sub3_mark', 'x_sub4_mark', 'xll_english', 'xll_sub1_mark', 'xll_sub2_mark', 'xll_sub3_mark'))
				->join(array('t6' => 'organisation'), 
						't2.working_agency  = t6.id', array('abbr'))
				->where(array('t2.organisation_id' => $organisation_id))
				->order('t2.id DESC');
		}
		
		if($position_title){
			$select->where(array('t1.vacancy_announcements_id' => $position_title));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
				
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	public function listAllApplicantDegreeMarks($type)
	{
		$study_level_list = array(
		  9 => '9',
		  10 => '10',
		  11 => '11',
		);
		$marks = array();
		$master = array();
		$employmentHistory = array();
		//var_dump($study_level_list); die();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'degree'){
			$select->from(array('t1' => 'job_applicant_education'))
				   ->columns(array('marks_obtained', 'job_applicant_id'))
				   ->join(array('t2' => 'study_level'),
						't2.id = t1.study_level', array('study_level'))
				->where(array('t1.study_level' => $study_level_list))
				->order('t1.id DESC');
		

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
					
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$marks[$set['job_applicant_id']]['job_applicant_id'] = $set['job_applicant_id'];
				$marks[$set['job_applicant_id']]['study_level'] = $set['study_level'];
				$marks[$set['job_applicant_id']]['marks_obtained'] = $set['marks_obtained'];
			} //var_dump($marks); die();
			return $marks;
		} else if($type == 'master'){
			$select->from(array('t1' => 'job_applicant_education'))
				   ->join(array('t2' => 'study_level'),
						't2.id = t1.study_level', array('study_level'))
				->where(array('t1.study_level' => '14'))
				->order('t1.id DESC');
		

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
					
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$master[] = $set;
			} //var_dump($master); die(); 
			return $master;
		}
		else if($type == 'employment'){
			$select->from(array('t1' => 'job_applicant_employment_record'))
					->order('t1.id DESC');
		

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
					
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			foreach($resultSet as $set){
				$employmentHistory[] = $set;
				/*$employmentHistory[$set['job_applicant_id']]['position_title'] = $set['position_title'];
				$employmentHistory[$set['job_applicant_id']]['start_period'] = $set['start_period'];
				$employmentHistory[$set['job_applicant_id']]['end_period'] = $set['end_period'];*/
			} //var_dump($employmentHistory); die();
			return $employmentHistory;
		} 
		//return $marks;
	}
	

	/**
	 * @return array/announcedVacancie()
	 * The following function is for list data for select/dropdown from 
	 */

	public function listAnnouncedVacancy($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'vacancy_announcements'))
			   ->join(array('t2' => 'position_title'),
			   		't2.id = t1.position_title', array('position_title'))
			   ->join(array('t3' => 'organisation'),
			   		't3.id = t1.working_agency', array('abbr'));
		$select->where(array('t1.organisation_id' => $organisation_id));
		$select->where(array('t1.status' => 'Open'));
		$select->order('id DESC');

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			if($set['additional_position_title'] != NULL){
				$selectData[$set['id']] = $set['abbr'].' - '.$set['position_title'].' / '.$set['additional_position_title'].'('.$set['area'].')';
			}else{
				$selectData[$set['id']] = $set['position_title'];
			}
		}
		return $selectData;
	}

	public function getApplicantDetail($applicant_id, $category)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($category == 'Outsider'){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('t1.id' =>$applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}


	public function getJobApplicantMarks($applicant_id, $category)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		/*if($category == 'Outsider'){
			$select->from(array('t1' => 'job_applicant_marks'));

			$select->where(array('t1.job_applicant_id' =>$applicant_id));
		}*/
		if($category == 'Outsider'){
			//var_dump($job_applicant_id); die();
			$select->from(array('t1' => 'job_application_marks'))
				   ->join(array('t2' => 'emp_job_applications'),
						't2.id = t1.emp_job_applications_id')
			   ->where(array('t2.job_applicant_id' => $applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}


	public function listApplicantStudyLevel($tableName, $job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'job_applicant_education'){
			$select->from(array('t1' => $tableName)); 
			$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$educationDetails = array();
		foreach($resultSet as $set){
			$educationDetails[$set['study_level']] = $set['study_level'];
		}
		return $educationDetails;
	}
	
	/**
	* @return array/Vacancy()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id){
			if($organisation_id == 1){
				$select->from(array('t1' => $tableName));
				$select->columns(array('id',$columnName)); 
			}else{
				$select->from(array('t1' => $tableName));
				$select->columns(array('id',$columnName)); 
				$select->where(array('t1.id' => $organisation_id));
			}

		}else{
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName)); 
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
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}
        
}
