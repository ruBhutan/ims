<?php

namespace JobApplicant\Mapper;

use JobApplicant\Model\JobApplicant;
use JobApplicant\Model\JobApplication;
use JobApplicant\Model\JobRegistrant;
use JobApplicant\Model\SelectedApplicant;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements JobApplicantMapperInterface
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
	protected $jobApplicantPrototype;
		
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			JobApplicant $jobApplicantPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->jobApplicantPrototype = $jobApplicantPrototype;
	}
	
	/**
	* @return array/Vacancy()
	*/
	
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

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

		if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id', 'first_name', 'middle_name', 'last_name'));
		}
		
		else if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName));
			$select->where(array('email' =>$username));
			$select->columns(array('id', 'first_name', 'middle_name', 'last_name', 'cid_copy'));
		}
		
			
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
			$select->columns(array('profile_picture', 'middle_name', 'last_name'));
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


	public function getPersonalDetails($tableName, $job_applicant_id)
	{

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		//need to ensure that columns are output the same
		if($tableName == 'job_applicant'){
			$select->from(array('t1' => $tableName));
			if ($job_applicant_id != NULL){
				$select->where(array('t1.id' =>$job_applicant_id));
			}
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
/*
		foreach($resultSet as $set){
			$registrantList[] = $set;
		}
		
		return $registrantList; */
	}

	public function getApplicantAddressDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

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
		$select->where(array('t1.id' => $job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getPresentJobDescription($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$emp_job_details = array();

		$select->from(array('t1' => 'job_applicant_employment_record'))
			   ->columns(array('position_level', 'position_title', 'position_category', 'major_occupational_group' => 'occupational_group', 'working_agency'))
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
				$emp_job_details['working_agency'] = $set['working_agency'];
				//$emp_job_details['position_level_id'] = NULL;
			}
		return $emp_job_details;	
	}

	public function getEmploymentDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'job_applicant_employment_record'))
				->where(array('t1.job_applicant_id' => $job_applicant_id))
				->order(array('t1.end_period DESC'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getEducationDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_education'))
			    ->join(array('t3' => 'study_level'),
					't3.id = t1.study_level', array('study_level'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id))
		       ->order(array('t1.end_date DESC'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function getApplicantMarksDetail($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_marks'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getLanguageDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_languages'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getTrainingDetails($job_applicant_id)
	{
		$trainings = array();
		$index = 0;
	
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'job_applicant_training_details'))
					->columns(array('title'=>'course_title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_address', 'institute_country'=>'country',
									'start_date'=>'from_date','end_date' =>'to_date'))
					->where('t1.job_applicant_id = ' .$job_applicant_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $value){
			$trainings[$index][] = $value;
		}
		return $trainings;
	}

	public function getResearchDetails($job_applicant_id)
	{
		$research = array();
		$index=0;
			
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'job_applicant_research_details'))
		       ->columns(array('title'=>'publication_name', 'year'=>'publication_year', 'publisher', 'publication_no'))
				->join(array('t2' => 'research_category'),
						't2.id = t1.research_type', array('type' => 'research_category'))
					->where('t1.job_applicant_id = ' .$job_applicant_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $value){
			$research[$index][] = $value;
		}
		return $research;
	}
	
	public function getApplicantCommunityServices($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_community_service'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	public function getApplicantAwardDetail($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_awards'));
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

		$select->from(array('t1' => 'job_applicant_memberships'));
		$select->where(array('t1.job_applicant_id' =>$job_applicant_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Save Job Application
	 */
	 
	public function saveJobApplication(JobApplication $jobObject)
	{
		$jobData = $this->hydrator->extract($jobObject);
		unset($jobData['id']);
		unset($jobData['name']);
		unset($jobData['title']);
		unset($jobData['position']);
		unset($jobData['organisation']);
		unset($jobData['relation_Applicant']);
		unset($jobData['telephone']);
		unset($jobData['mobile']);
		unset($jobData['email']);
		//if empty, set to NULL
		if($jobData['employee_Details_Id'] == NULL){
			$jobData['employee_Details_Id'] = NULL;
		}
		if($jobData['job_Applicant_Id'] == NULL){
			$jobData['job_Applicant_Id'] = NULL;
		}
		
		$identity = $jobData['identity_Proof'];
		$jobData['identity_Proof'] = $identity['tmp_name'];

		if($jobData['identity_Proof'] == NULL){
			$jobData['identity_Proof'] = $this->getUploadedFileLink($tableName = 'job_applicant', $type='cid', $jobData['job_Applicant_Id']);
		} 
		
		$security = $jobData['security_Clearance_File'];
		$jobData['security_Clearance_File'] = $security['tmp_name'];
		
		$medical = $jobData['medical_Clearance_File'];
		$jobData['medical_Clearance_File'] = $medical['tmp_name'];
		
		$audit = $jobData['audit_Clearance_File'];
		$jobData['audit_Clearance_File'] = $audit['tmp_name'];
		
		$tax = $jobData['tax_Clearance_File'];
		$jobData['tax_Clearance_File'] = $tax['tmp_name'];
		
		$other = $jobData['other_Certificate_File'];
		$jobData['other_Certificate_File'] = $other['tmp_name'];
		
                
        $referenceData = array();
        //need to extract the reference details
        $referenceDetails = $this->getApplicantReferenceDetails($jobData['job_Applicant_Id']);
        foreach($referenceDetails as $details){
        	$referenceData[$details['id']]['name'] = $details['name'];
            $referenceData[$details['id']]['title'] = $details['title'];
            $referenceData[$details['id']]['position'] = $details['position'];
            $referenceData[$details['id']]['organisation'] = $details['organisation'];
            $referenceData[$details['id']]['relation_Applicant'] = $details['relation_applicant'];
            $referenceData[$details['id']]['telephone'] = $details['telephone'];
            $referenceData[$details['id']]['mobile'] = $details['mobile'];
            $referenceData[$details['id']]['email'] = $details['email'];
        }   

        $referenceData1 = array();
        //need to extract the reference details
        $referenceDetails1 = $this->getApplicantEducationDetails($jobData['job_Applicant_Id']);
        foreach($referenceDetails1 as $details){
        	$referenceData1[$details['id']]['study_level'] = $details['study_level'];
            $referenceData1[$details['id']]['college_name'] = $details['college_name'];
            $referenceData1[$details['id']]['college_location'] = $details['college_location'];
            $referenceData1[$details['id']]['college_country'] = $details['college_country'];
            $referenceData1[$details['id']]['field_study'] = $details['field_study'];
            $referenceData1[$details['id']]['study_mode'] = $details['study_mode'];
            $referenceData1[$details['id']]['start_date'] = $details['start_date'];
            $referenceData1[$details['id']]['funding'] = $details['funding'];
            $referenceData1[$details['id']]['marks_obtained'] = $details['marks_obtained'];
            $referenceData1[$details['id']]['academic_transcript'] = $details['academic_transcript'];
            $referenceData1[$details['id']]['pass_certificate'] = $details['pass_certificate'];
            $referenceData1[$details['id']]['job_applicant_id'] = $details['job_applicant_id'];
        } 

        $referenceData2 = array();
        //need to extract the reference details
        $referenceDetails2 = $this->getApplicantMarkDetails($jobData['job_Applicant_Id']);

        foreach($referenceDetails2 as $details){
        	$referenceData2[$details['id']]['x_english'] = $details['x_english'];
            $referenceData2[$details['id']]['x_sub1_mark'] = $details['x_sub1_mark'];
            $referenceData2[$details['id']]['x_sub2_mark'] = $details['x_sub2_mark'];
            $referenceData2[$details['id']]['x_sub3_mark'] = $details['x_sub3_mark'];
            $referenceData2[$details['id']]['x_sub4_mark'] = $details['x_sub4_mark'];
            $referenceData2[$details['id']]['xll_english'] = $details['xll_english'];
            $referenceData2[$details['id']]['xll_sub1_mark'] = $details['xll_sub1_mark'];
            $referenceData2[$details['id']]['xll_sub2_mark'] = $details['xll_sub2_mark'];
            $referenceData2[$details['id']]['xll_sub3_mark'] = $details['xll_sub3_mark'];
            $referenceData2[$details['id']]['job_applicant_id'] = $details['job_applicant_id'];
        }  

        $referenceData3 = array();
        //need to extract the reference details
        $referenceDetails3 = $this->getApplicantEmploymentRecord($jobData['job_Applicant_Id']);

        foreach($referenceDetails3 as $details){
        	$referenceData3[$details['id']]['working_agency'] = $details['working_agency'];
            $referenceData3[$details['id']]['occupational_group'] = $details['occupational_group'];
            $referenceData3[$details['id']]['position_category'] = $details['position_category'];
            $referenceData3[$details['id']]['position_title'] = $details['position_title'];
            $referenceData3[$details['id']]['position_level'] = $details['position_level'];
            $referenceData3[$details['id']]['start_period'] = $details['start_period'];
            $referenceData3[$details['id']]['end_period'] = $details['end_period'];
            $referenceData3[$details['id']]['remarks'] = $details['remarks'];
            $referenceData3[$details['id']]['employment_record_file'] = $details['employment_record_file'];
            $referenceData3[$details['id']]['job_applicant_id'] = $details['job_applicant_id'];
        }            
				
		if($jobObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_job_applications');
			$action->set($jobData);
			$action->where(array('id = ?' => $jobObject->getId()));
		} else {
			//ID is not present, so its an insert
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
                $this->saveJobApplicationEducation($referenceData1, $newId);
                $this->saveJobApplicationMarks($referenceData2, $newId);
                $this->saveJobApplicationEmploymentRecord($referenceData3, $newId);
			}
			return $jobObject;
		}
		
		throw new \Exception("Database Error");
	}


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
                    $reference['mobile'] = $value['mobile'];
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
	public function saveJobApplicationEducation($referenceData1, $emp_job_application_id)
	{	
                foreach($referenceData1 as $key=>$value){
                    $reference['study_level'] = $value['study_level'];
		            $reference['college_name'] = $value['college_name'];
		            $reference['college_location'] = $value['college_location'];
		            $reference['college_country'] = $value['college_country'];
		            $reference['field_study'] = $value['field_study'];
		            $reference['study_mode'] = $value['study_mode'];
		            $reference['start_date'] = $value['start_date'];
		            $reference['funding'] = $value['funding'];
		            $reference['marks_obtained'] = $value['marks_obtained'];
		            $reference['academic_transcript'] = $value['academic_transcript'];
		            $reference['pass_certificate'] = $value['pass_certificate'];
		            $reference['job_applicant_id'] = $value['job_applicant_id'];
                    $reference['emp_job_applications_id'] = $emp_job_application_id;
                    
                    $action = new Insert('job_application_education');
                    $action->values($reference);

                    $sql = new Sql($this->dbAdapter);
                    $stmt = $sql->prepareStatementForSqlObject($action);
                    $result = $stmt->execute();
                }     

		return;
	}

	public function saveJobApplicationMarks($referenceData2, $emp_job_application_id)
	{	

	    foreach($referenceData2 as $key=>$value){

	    	$reference['x_english'] = $value['x_english'];
	        $reference['x_sub1_mark'] = $value['x_sub1_mark'];
	        $reference['x_sub2_mark'] = $value['x_sub2_mark'];
	        $reference['x_sub3_mark'] = $value['x_sub3_mark'];
	        $reference['x_sub4_mark'] = $value['x_sub4_mark'];
	        $reference['xll_english'] = $value['xll_english'];
	        $reference['xll_sub1_mark'] = $value['xll_sub1_mark'];
	        $reference['xll_sub2_mark'] = $value['xll_sub2_mark'];
	        $reference['xll_sub3_mark'] = $value['xll_sub3_mark'];
            $reference['job_applicant_id'] = $value['job_applicant_id'];
	        $reference['emp_job_applications_id'] = $emp_job_application_id;
	        
	        $action = new Insert('job_application_marks');
	        $action->values($reference);

	        $sql = new Sql($this->dbAdapter);
	        $stmt = $sql->prepareStatementForSqlObject($action);
	        $result = $stmt->execute();
	    }     

		return;
	}

	public function saveJobApplicationEmploymentRecord($referenceData3, $emp_job_application_id)
	{	
	    foreach($referenceData3 as $key=>$value){

	    	$reference['working_agency'] = $value['working_agency'];
	        $reference['occupational_group'] = $value['occupational_group'];
	        $reference['position_category'] = $value['position_category'];
	        $reference['position_title'] = $value['position_title'];
	        $reference['position_level'] = $value['position_level'];
	        $reference['start_period'] = $value['start_period'];
	        $reference['end_period'] = $value['end_period'];
	        $reference['remarks'] = $value['remarks'];
	        $reference['employment_record_file'] = $value['employment_record_file'];
            $reference['job_applicant_id'] = $value['job_applicant_id'];
	        $reference['emp_job_applications_id'] = $emp_job_application_id;
	        
	        $action = new Insert('job_application_employment_record');
	        $action->values($reference);

	        $sql = new Sql($this->dbAdapter);
	        $stmt = $sql->prepareStatementForSqlObject($action);
	        $result = $stmt->execute();
	    }     

		return;
	}


	public function getUploadedFileLink($tableName, $type, $id)
	{
		$file_location = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($type == 'cid'){
			$select->from(array('t1' => 'job_applicant'));
			$select->where(array('id' =>$id));
			$select->columns(array('cid_copy'));
				
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$file_location = $set['cid_copy'];
			}
		}						
		return $file_location;
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

	public function getApplicantEducationLevel($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_education'))
			   ->columns(array('study_level'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
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


	public function getApplicantReferenceDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_references'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getApplicantEducationDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'job_applicant_education'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getApplicantMarkDetails($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();


		$select->from(array('t1' => 'job_applicant_marks'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getApplicantEmploymentRecord($job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();


		$select->from(array('t1' => 'job_applicant_employment_record'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getJobApplicationList($tableName, $job_applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'employee_details'){
			$select->from(array('t1' => 'emp_job_applications'))
				   ->join(array('t2' => 'vacancy_announcements'),
						't2.id = t1.vacancy_announcements_id', array('date_of_advertisement', 'last_date_submission', 'working_agency', 'employee_type', 'position_title', 'position_category', 'position_level', 'no_of_slots'))
			   ->where(array('t1.employee_details_id' => $job_applicant_id));
		}
		else if($tableName == 'job_applicant'){
			$select->from(array('t1' => 'emp_job_applications'))
				   ->join(array('t2' => 'vacancy_announcements'),
						't2.id = t1.vacancy_announcements_id', array('date_of_advertisement', 'last_date_submission', 'working_agency', 'employee_type', 'position_title', 'position_category', 'position_level', 'no_of_slots'))
			   ->where(array('t1.job_applicant_id' => $job_applicant_id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	
	/**
	* @return array/Vacancy()
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
	public function saveJobRegistrantDetails(JobRegistrant $jobregistrantObject, $registrantList)
	{
		$jobregistrantData = $this->hydrator->extract($jobregistrantObject);

		foreach($registrantList as $registrantList){
			$email = $registrantList['email'];
		}
		
		$tableName = 'users';
		$columnName = $email;
		$this->saveJobRegistrantUser($tableName, $columnName, $jobregistrantData);

		//var_dump($jobregistrantData); die();

		if($jobregistrantObject->getId()) {
			//ID present, so it is an update
			$action = new Update('job_applicant');
			$action->set($jobregistrantData);
			$action->where(array('id = ?' => $jobregistrantData['id']));
		} 

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		//return;
	}

	public function saveJobRegistrantUser($tableName,$columnName, $jobregistrantData)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->where(array('t1.username' => $jobregistrantData['email']));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData = $set['username'];
		}

		//var_dump($jobregistrantData['email']); die();

		if ($selectData != $jobregistrantData['email'] ){
			$passwordData['username'] = $jobregistrantData['email'];
			$passwordData['password'] = md5('admin');
			$passwordData['user_Status_Id'] = '1'; 

			$action = new Update('users');
			$action->set($passwordData);
			$action->where(array('username = ?' => $columnName));

			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		else {
			echo "Email Already Registered"; die();
		}

		//return;

	}

		
        
}