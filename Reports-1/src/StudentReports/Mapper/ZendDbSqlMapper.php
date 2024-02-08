<?php

namespace StudentReports\Mapper;

use StudentReports\Model\StudentReports;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentReportsMapperInterface
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
	 * @var \Reports\Model\ReportsInterface
	*/
	protected $jobPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentReports $jobPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->jobPrototype = $jobPrototype;
	}
	
	
	/**
	* @return array/Reports()
	*/
	public function findAll($tableName, $applicant_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); 
		if($tableName=='job_applicant')
			$select->where(array('id' =>$applicant_id));
		else
			$select->where(array('job_applicant_id' =>$applicant_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		 return $resultSet->initialize($result);
	}
	
	/*
	* take username and returns employee details id
	*/
	
	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
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
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getHrReport($report_details, $organisation_id)
	{
		$hr_reports = array();
		$report_name = $report_details['report_name'];
		$report_type = $report_details['report_type'];
		$organisation = $report_details['organisation'];
		$five_year_plan = $report_details['five_year_plan']; 
		
		switch($report_name){
			case "rub_hr_report":
				$hr_reports = $this->getEmployeeQualification($five_year_plan, $organisation, $report_type);
				break;
			
			case "hrd_planning":
				$hr_reports = $this->getHRDPlanningReport($five_year_plan, $organisation, $report_type);
				break;
			
			case "hrm_planning":
				$hr_reports = $this->getHRMPlanningReport($five_year_plan, $organisation, $report_type);
				break;
			
			case "position_category_position_level":
				$hr_reports = $this->getEmployeePositionCategoryLevel($five_year_plan, $organisation, $report_type);
				break;
			
			case "agency_employment_type":
				$hr_reports = $this->getAgencyEmploymentType($five_year_plan, $organisation, $report_type);
				break;
			
			case "agency_category_level":
				$hr_reports = $this->getAgencyCategoryLevel($five_year_plan, $organisation, $report_type);
				break;
			
			case "occupational_group_gender":
				$hr_reports = $this->getOccupationalGroupGender($five_year_plan, $organisation, $report_type);
				break;
			
			case "position_level_gender":
				$hr_reports = $this->getPositionLevelGender($five_year_plan, $organisation, $report_type);
				break;
			
			case "recruitment_position_level":
				$hr_reports = $this->getRecruitmentPositionLevel($five_year_plan, $organisation, $report_type);
				break;
			
			case "staff_leave":
				$hr_reports = $this->getStaffOnLeave($report_details['date'], $organisation);
				break;

			case "staff_pending_leave":
				$hr_reports = $this->getStaffPendingLeave($report_details['date'], $organisation_id);
				break;
			
			case "staff_tour":
				$hr_reports = $this->getStaffOnTour($report_details['date'], $organisation);
				break;
			
			case "staff_training":
				$hr_reports = $this->getStaffOnTraining($report_details['date'], $organisation);
				break;
			case "staff_leave_encashment":
				$hr_reports = $this->getStaffLeaveEncashment($report_details['date'], $organisation);
				break;
			case "staff_overall":
				$hr_reports = $this->getOverAllStaffAdministration($report_details['date'], $organisation);
				break;
			
			case "five_year_implementation":
				$hr_reports = $this->getFiveYearTrainingImplementation($five_year_plan, $organisation, $report_type, $organisation_id);
				break;
			
			case "training_implementation":
				$hr_reports = $this->getAgencyTrainingImplementation($five_year_plan, $organisation, $report_type);
				break;
			
			case "training_implementation_category":
				$hr_reports = $this->getTrainingImplementationCategory($five_year_plan, $organisation, $report_type, $organisation_id);
				break;
			
			case "training_implementation_country":
				$hr_reports = $this->getTrainingImplementationCountry($five_year_plan, $organisation, $report_type, $organisation_id);
				break;
			
			case "training_implementation_funding":
				$hr_reports = $this->getTrainingImplementationFunding($five_year_plan, $organisation, $report_type, $organisation_id);
				break;
			
			case "separation_agencies_position":
				$hr_reports = $this->getSeparationAgenciesPosition($five_year_plan, $organisation);
				break;
			
			case "promotions":
				$hr_reports = $this->getEmployeePromotions($five_year_plan, $organisation, $report_type);
				break;
			
			case "recruitment_separation":
				$hr_reports = $this->getRecruitmentSeparation($five_year_plan, $organisation);
				break;
				
			case "staff_promotion_details":
				$hr_reports = $this->getStaffPromotionDetails($organisation, $year);
				break;
			
			case "staff_separation_details":
				$hr_reports = $this->getStaffSepearationDetails($organisation, $year);
				break;
			
			case "staff_apa_details":
				$hr_reports = $this->getStaffApaDetails($organisation, $year);
				break;
			
			case "staff_by_dzongkhag":
				$hr_reports = $this->getStaffByDzongkhag($organisation, $year = 1);
				break;
			
			case "staff_by_nationality":
				$hr_reports = $this->getStaffByNationality($organisation, $year = 1);
				break;
			
			case "staff_by_religion":
				$hr_reports = $this->getStaffByReligion($organisation, $year = 1);
				break;
			
			case "staff_by_gender":
				$hr_reports = $this->getStaffByGender($organisation, $year = 1);
				break;
				
			case "staff_by_organisation":
				$hr_reports = $this->getStaffByOrganisation($organisation, $year = 1);
				break;
			
			case "staff_by_department":
				$hr_reports = $this->getStaffByDepartment($organisation, $year =1);
				break;
			
			case "staff_by_employeetype":
				$hr_reports = $this->getStaffByEmployeeType($organisation, $year=1);
				break;
			
			case "staff_by_section":
				$hr_reports = $this->getStaffBySection($organisation, $year=1);
				break;
			
			case "staff_by_maritialstatus":
				$hr_reports = $this->getStaffByMaritialStatus($organisation, $year=1);
				break;
			
			case "staff_by_positiontitle":
				$hr_reports = $this->getStaffByPositionTitle($organisation, $year=1);
				break;
			
			case "staff_by_positionlevel":
				$hr_reports = $this->getStaffByPositionLevel($organisation, $year=1);
				break;
			
			case "staff_by_bloodgroup":
				$hr_reports = $this->getStaffByBloodGroup($organisation, $year=1);
				break;
			
		}

		return $hr_reports;
	}
	
	/*
	* Get the type of report and the data for the report
	* Here the report is an array containing report type and organisation id
	*/
	
	public function getAcademicReport($report)
	{
		$report_type = $report['report_type'];
		$organisation_id = $report['organisation_id'];
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($report_type == 'programmes_report'){
			$select->from(array('t1' => 'programmes'))
				->join(array('t2' => 'employee_details'), 
                            't1.programme_leader = t2.id', array('first_name','middle_name','last_name'));
			$select->where(array('t1.organisation_id' =>$organisation_id));
		} else if($report_type == 'external_examiner_report') {
			$select->from(array('t1' => 'external_examiners'))
				->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('programme_name'));
			$select->where(array('t2.organisation_id' =>$organisation_id));
		} 
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);	
	}


	public function getAcademicResultReport($report)
	{ 
		$report_type = $report['report_type'];
		$organisation_id = $report['organisation_id'];
		$programmes_id = $report['programmes_id'];
		$section = $report['section']; 
		
		//$semester = $this->getSemester($organisation_id);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($report_type == 'academic_marks_report') {
			if($section == 'All'){
				$select->from(array('t1' => 'student_consolidated_marks'))
					->join(array('t2' => 'programmes'), 
						't1.programmes_id = t2.id', array('programme_name'))
					->join(array('t3' => 'student'), 
						't1.student_id = t3.student_id', array('first_name','middle_name','last_name'));
					$select->where(array('t2.organisation_id' =>$organisation_id, 't1.programmes_id' => $programmes_id, 't1.academic_year' => $academic_year));
			}else{
				$select->from(array('t1' => 'student_consolidated_marks'))
					->join(array('t2' => 'programmes'), 
						't1.programmes_id = t2.id', array('programme_name'))
					->join(array('t3' => 'student'), 
						't1.student_id = t3.student_id', array('first_name','middle_name','last_name'))
					->join(array('t4' => 'student_semester_registration'),
						't3.id = t4.student_id', array('enrollment_year'));
					$select->where(array('t2.organisation_id' =>$organisation_id, 't1.programmes_id' => $programmes_id, 't4.student_section_id' => $section, 't1.academic_year' => $academic_year));
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);	
		}

		else if($report_type == 'academic_result_summary') {
			if($section == 'All'){
				$select->from(array('t1' => 'student_consolidated_marks'))
					->join(array('t2' => 'programmes'), 
						't1.programmes_id = t2.id', array('programme_name'))
					->join(array('t3' => 'student'), 
						't1.student_id = t3.student_id', array('first_name','middle_name','last_name'));
				$select->where(array('t1.programmes_id' => $programmes_id, 't1.academic_year' => $academic_year));
				$select->where->notEqualTo('t1.marks','0.00');
			}else{
				$select->from(array('t1' => 'student_consolidated_marks'))
					->join(array('t2' => 'programmes'), 
						't1.programmes_id = t2.id', array('programme_name'))
					->join(array('t3' => 'student'), 
						't1.student_id = t3.student_id', array('first_name','middle_name','last_name'))
					->join(array('t4' => 'student_semester_registration'),
						't3.id = t4.student_id', array('student_section_id'));
				$select->where(array('t1.programmes_id' => $programmes_id, 't4.student_section_id' => $section, 't1.academic_year' => $academic_year));
				$select->where->notEqualTo('t1.marks','0.00');
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);	
		}

		else if($report_type == 'academic_assessment_status') {
			if($section == 'All'){
				$select->from(array('t1' => 'academic_assessment_status'),array('status'))
					->join(array('t2' => 'academic_assessment'), 
						't2.id = t1.academic_assessment_id',array('assessment'))
					->join(array('t3' => 'assessment_component'), 
						't3.id = t2.assessment_component_id',array('weightage'))
					->join(array('t4' => 'academic_modules_allocation'), 
						't4.id = t3.academic_modules_allocation_id',array('module_title'))
					->join(array('t5' => 'programmes'), 
						't5.id = t4.programmes_id')
					->join(array('t6' => 'student_section'), 
						't6.id = t1.section',array('section'));
				$select->where(array('t4.programmes_id' => $programmes_id, 't4.academic_year' => $academic_year));
				//$select->where(array('t2.id != t1.academic_assessment_id'));
				
			}else{
				$select->from(array('t1' => 'academic_assessment_status'),array('status'))
					->join(array('t2' => 'academic_assessment'), 
						't2.id = t1.academic_assessment_id',array('assessment'))
					->join(array('t3' => 'assessment_component'), 
						't3.id = t2.assessment_component_id',array('weightage'))
					->join(array('t4' => 'academic_modules_allocation'), 
						't4.id = t3.academic_modules_allocation_id',array('module_title'))
					->join(array('t5' => 'programmes'), 
						't5.id = t4.programmes_id')
					->join(array('t6' => 'student_section'), 
						't6.id = t1.section',array('section'));
				$select->where(array('t4.programmes_id' => $programmes_id, 't1.section' => $section, 't4.academic_year' => $academic_year));
			}

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);	
		}
	}


        
        /*
         * Function to get the HRD Planning Report
         */
        
        public function getHRDPlanningReport($five_year_plan, $organisation, $report_type)
        {
                $hr_reports = array();
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                switch($report_type){
                    case "list":
                        $select->from(array('t1' => 'hr_development'))
					->join(array('t2'=>'organisation'),
                                            't1.working_agency = t2.id',array('organisation_name'))
                                        ->join(array('t3'=>'five_year_plan'),
                                            't1.five_year_plan = t3.five_year_plan',array('five_year_plan'))
                                        ->join(array('t4'=>'training_types'),
                                            't1.training_type = t4.id',array('training_type'));
                        $select->where(array('t3.id' =>$five_year_plan));
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[] = $set;
                        }
                        break;
                    
                    case "summary":
                        $select->from(array('t1' => 'hr_development'))
					->join(array('t2'=>'organisation'),
                                            't1.working_agency = t2.id',array('organisation_name'))
                                        ->join(array('t3'=>'five_year_plan'),
                                            't1.five_year_plan = t3.five_year_plan',array('five_year_plan'))
                                        ->join(array('t4'=>'training_types'),
                                            't1.training_type = t4.id',array('training_type'));
                        $select->where(array('t3.id' =>$five_year_plan));
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['organisation_name']][$set['training_type']]['amount_year_1'] += (int)$set['amount_year_1'];
                            $hr_reports[$set['organisation_name']][$set['training_type']]['amount_year_2'] += (int)$set['amount_year_2'];
                            $hr_reports[$set['organisation_name']][$set['training_type']]['amount_year_3'] += (int)$set['amount_year_3'];
                            $hr_reports[$set['organisation_name']][$set['training_type']]['amount_year_4'] += (int)$set['amount_year_4'];
                            $hr_reports[$set['organisation_name']][$set['training_type']]['amount_year_5'] += (int)$set['amount_year_5'];
                        }
                        break;
                }

		return $hr_reports;
        }
        
        /*
         * Function to get the HRM Planning Report
         */
        
        public function getHRMPlanningReport($five_year_plan, $organisation, $report_type)
        {
                $hr_reports = array();
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                switch($report_type){
                    case "list":
                        $select->from(array('t1' => 'hr_management'))
					->join(array('t2'=>'organisation'),
                                            't1.working_agency = t2.id',array('organisation_name'))
                                        ->join(array('t3'=>'five_year_plan'),
                                            't1.five_year_plan = t3.five_year_plan',array('five_year_plan'))
                                        ->join(array('t4'=>'position_title'),
                                            't1.position_title_id = t4.id',array('position_title'))
                                        ->join(array('t5'=>'position_level'),
                                            't1.position_level_id = t5.id',array('position_level'));
                        $select->where(array('t3.id' =>$five_year_plan));
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[] = $set;
                        }
                        break;
                    
                    case "summary":
                        $select->from(array('t1' => 'hr_management'))
					->join(array('t2'=>'organisation'),
                                            't1.working_agency = t2.id',array('organisation_name'))
                                        ->join(array('t3'=>'five_year_plan'),
                                            't1.five_year_plan = t3.five_year_plan',array('five_year_plan'))
                                        ->join(array('t4'=>'position_title'),
                                            't1.position_title_id = t4.id',array('position_title'))
                                        ->join(array('t5'=>'position_level'),
                                            't1.position_level_id = t5.id',array('position_level'));
                        $select->where(array('t3.id' =>$five_year_plan));
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_1'] += (int)$set['requirement_year_1'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_2'] += (int)$set['requirement_year_2'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_3'] += (int)$set['requirement_year_3'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_4'] += (int)$set['requirement_year_4'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_5'] += (int)$set['requirement_year_5'];
                        }
                        break;
                }

		return $hr_reports;
        }
        
	/*
	* Function to get the HR Report by Academic Qualification for each Agency
	*/
	
	public function getEmployeeQualification($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$hr_data = array();
		$hr_type = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_education_details'))
					->columns(array('employee_details_id'))
					->join(array('t2'=>'study_level'),
                            't1.study_level = t2.id',array('id', 'study_level'))
					->join(array('t3'=>'employee_details'),
                            't1.employee_details_id = t3.id',array('organisation_id', 'emp_category'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			if($set['study_level'] == 'PhD'){
				$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
				$hr_type[$set['employee_details_id']] = $set['emp_category'];
			} else if($set['study_level'] == 'Master' || $set['study_level'] == 'Post Graduate Certificate' || $set['study_level'] == 'Post Graduate Diploma'){
				if(in_array($set['employee_details_id'], $hr_data)){
					if($hr_data[$set['organisation_id']][$set['employee_details_id']] != 'PhD')
						$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
						$hr_type[$set['employee_details_id']] = $set['emp_category'];
				} else {
					$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
					$hr_type[$set['employee_details_id']] = $set['emp_category'];
				}
			} else if($set['study_level'] == 'Bachelor'){
				if(in_array($set['employee_details_id'], $hr_data)){
					if($hr_data[$set['organisation_id']][$set['employee_details_id']] != 'PhD' && $hr_data[$set['organisation_id']][$set['employee_details_id']] != 'Master' && $set['study_level'] != 'Post Graduate Certificate' || $set['study_level'] != 'Post Graduate Diploma')
						$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
						$hr_type[$set['employee_details_id']] = $set['emp_category'];
				} else {
					$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
					$hr_type[$set['employee_details_id']] = $set['emp_category'];
				}
			} else {
				if(in_array($set['employee_details_id'], $hr_data)){
					if($hr_data[$set['organisation_id']][$set['employee_details_id']] != 'PhD' && $hr_data[$set['organisation_id']][$set['employee_details_id']] != 'Master' && $hr_data[$set['organisation_id']][$set['employee_details_id']] != 'Bachelor' && $set['study_level'] != 'Post Graduate Certificate' || $set['study_level'] != 'Post Graduate Diploma')
					$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
					$hr_type[$set['employee_details_id']] = $set['emp_category'];
				} else {
					$hr_data[$set['organisation_id']][$set['employee_details_id']] = $set['id'];
					$hr_type[$set['employee_details_id']] = $set['emp_category'];
				}
			}
			//$hr_reports[$set['organisation_id']][$set['emp_category']][$set['study_level']] = $set['study_level'];
		}
		foreach($hr_data as $key=>$value){
			foreach($value as $k=>$v){
				$hr_reports[$key][$hr_type[$k]][$v][$k] = (int)$v; 
			}
		}
		
		return $hr_reports;
	}
	
	/*
	* Get the Employee Position Category and Position Level for RUB as a whole
	*/
	
	public function getEmployeePositionCategoryLevel($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$five_year_plan = $this->getFiveYearPlan($five_year_plan);
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id'))
					->join(array('t2'=>'emp_position_level'),
                            't1.id = t2.employee_details_id',array('date','position_level_id'))
					->order('t2.date ASC')
					->join(array('t3'=>'position_level'),
                            't2.position_level_id = t3.id',array('position_level','major_occupational_group_id'))
					->join(array('t4'=>'emp_position_title'),
                            't1.id = t4.employee_details_id',array('position_title_id'))
					->join(array('t5'=>'position_title'),
                            't4.position_title_id = t5.id',array('position_title','position_category_id'))
					->join(array('t6'=>'position_category'),
                            't6.id = t5.position_category_id',array('category'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			if(substr($set['date'],0,4)<$five_year_plan[0])
				$hr_reports[$set['category']][$set['position_level']][$five_year_plan[0]][$set['id']] = $set['id'];
			else if(substr($set['date'],0,4)>$five_year_plan[0] && substr($set['date'],0,4) <= $five_year_plan[1])
				$hr_reports[$set['category']][$set['position_level']][$five_year_plan[1]][$set['id']] = $set['id'];
			else if(substr($set['date'],0,4)>$five_year_plan[1] && substr($set['date'],0,4) <= $five_year_plan[2])
				$hr_reports[$set['category']][$set['position_level']][$five_year_plan[2]][$set['id']] = $set['id'];
			else if(substr($set['date'],0,4)>$five_year_plan[2] && substr($set['date'],0,4) <= $five_year_plan[3])
				$hr_reports[$set['category']][$set['position_level']][$five_year_plan[3]][$set['id']] = $set['id'];
			else if(substr($set['date'],0,4)>$five_year_plan[3] && substr($set['date'],0,4) <= $five_year_plan[4])
				$hr_reports[$set['category']][$set['position_level']][$five_year_plan[4]][$set['id']] = $set['id'];
		}
		return $hr_reports;
	}
	
	/*
	* Get the Employment Type by Agency For RUB
	*/
	
	public function getAgencyEmploymentType($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$hr_data = array();
		$hr_type = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_education_details'))
					->columns(array('employee_details_id'))
					->join(array('t2'=>'study_level'),
                            't1.study_level = t2.id',array('id', 'study_level'))
					->join(array('t3'=>'employee_details'),
                            't1.employee_details_id = t3.id',array('organisation_id', 'emp_category'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
	}
	
	
	/*
	* Get the Position Categroy and Position Level by Agency For RUB
	*/
	
	public function getAgencyCategoryLevel($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','organisation_id'))
					->join(array('t2'=>'emp_position_title'),
                            't1.id = t2.employee_details_id',array('date','position_title_id','employee_details_id'))
					->order('t2.date ASC')
					->join(array('t3'=>'position_title'),
                            't2.position_title_id = t3.id',array('position_title'))
					->join(array('t4'=>'position_category'),
                            't4.id = t3.position_category_id',array('category'))
					->join(array('t5'=>'major_occupational_group'),
                            't5.id = t4.major_occupational_group_id',array('major_occupational_group'));;
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
				$hr_reports[$set['organisation_id']][$set['category']][$set['id']] = $set['id'];
		}
		
		return $hr_reports;
	}
		
	/*
	* Get the Occupational Group by Gender for RUB
	*/
	
	public function getOccupationalGroupGender($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','organisation_id','gender', 'nationality'))
					->join(array('t2'=>'emp_position_level'),
                            't1.id = t2.employee_details_id',array('date','position_level_id','employee_details_id'))
					->order('t2.date ASC')
					->join(array('t3'=>'position_level'),
                            't2.position_level_id = t3.id',array('position_level','major_occupational_group_id'))
					->join(array('t4'=>'major_occupational_group'),
                            't4.id = t3.major_occupational_group_id',array('major_occupational_group'))
					->join(array('t5'=>'position_category'),
                            't4.id = t5.major_occupational_group_id',array('category'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
				if($set['nationality'] == 23)
					$hr_reports[$set['organisation_id']][$set['major_occupational_group']]['Bhutanese'][$set['gender']][$set['id']] = $set['id'];
				else
					$hr_reports[$set['organisation_id']][$set['major_occupational_group']]['NonBhutanese'][$set['gender']][$set['id']] = $set['id'];
		}
		
		return $hr_reports;
	}
	
	/*
	* Get the Position Level by Gender for RUB
	*/
	
	public function getPositionLevelGender($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id', 'gender','nationality'))
					->join(array('t2'=>'emp_position_level'),
                            't1.id = t2.employee_details_id',array('date','position_level_id'))
					->order('t2.date ASC')
					->join(array('t3'=>'position_level'),
                            't2.position_level_id = t3.id',array('position_level'))
					->join(array('t4'=>'emp_position_title'),
                            't1.id = t4.employee_details_id',array('position_title_id'))
					->join(array('t5'=>'position_title'),
                            't4.position_title_id = t5.id',array('position_title','position_category_id'))
					->join(array('t6'=>'position_category'),
                            't6.id = t5.position_category_id',array('category'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
				if($set['nationality'] == 23)
					$hr_reports[$set['category']][$set['position_level']]['Bhutanese'][$set['gender']][$set['id']] = $set['id'];
				else
					$hr_reports[$set['category']][$set['position_level']]['NonBhutanese'][$set['gender']][$set['id']] = $set['id'];
		}
		return $hr_reports;
	}
	
	/*
	* Get the Recruitment by Position Level
	*/
	
	public function getRecruitmentPositionLevel($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('emp_type','recruitment_date'))
					->join(array('t2'=>'emp_position_level'),
                            't1.id = t2.employee_details_id',array('employee_details_id','position_level_id'))
					->join(array('t3'=>'position_level'),
                            't2.position_level_id = t3.id',array('position_level'))
					->join(array('t4'=>'employee_type'),
                            't1.emp_type = t4.id',array('employee_type'));
		$select->where(array('t1.recruitment_date >= ? ' => $five_year_plan['from_date']));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$hr_reports_temp = array();
		foreach($resultSet as $set){
			if(substr($set['recruitment_date'], 0, 5) == $years[0])
				$hr_reports_temp[$set['position_level']][$years[0]][$set['employee_type']][$set['employee_details_id']] = $set['employee_details_id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[1])
				$hr_reports_temp[$set['position_level']][$years[1]][$set['employee_type']][$set['employee_details_id']] = $set['employee_details_id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[2])
				$hr_reports_temp[$set['position_level']][$years[2]][$set['employee_type']][$set['employee_details_id']] = $set['employee_details_id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[3])
				$hr_reports_temp[$set['position_level']][$years[3]][$set['employee_type']][$set['employee_details_id']] = $set['employee_details_id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[4])
				$hr_reports_temp[$set['position_level']][$years[4]][$set['employee_type']][$set['employee_details_id']] = $set['employee_details_id'];
		}
		
		foreach($hr_reports_temp as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_reports_temp[$key][$key2][$key3]);
				}	
			}
		}
                
		return $hr_reports;
	}
	
	/*
	* Get The Recruitment Record by Agencies
	*/
	
	public function getRecruitmentAgencies()
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
					->columns(array('id','emp_type','recruitment_date','organisation_id'))
					->join(array('t4'=>'employee_type'),
                            't1.emp_type = t4.id',array('employee_type'));
		$select->where(array('t1.recruitment_date >= ? ' => $five_year_plan['from_date']));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//var_dump($resultSet);
		//die();
		
		$hr_reports_temp = array();
		foreach($resultSet as $set){
			if(substr($set['recruitment_date'], 0, 5) == $years[0])
				$hr_reports_temp[$set['organisation_id']][$years[0]][$set['employee_type']][$set['id']] = $set['id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[1])
				$hr_reports_temp[$set['organisation_id']][$years[1]][$set['employee_type']][$set['id']] = $set['id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[2])
				$hr_reports_temp[$set['organisation_id']][$years[2]][$set['employee_type']][$set['id']] = $set['id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[3])
				$hr_reports_temp[$set['organisation_id']][$years[3]][$set['employee_type']][$set['id']] = $set['id'];
			if(substr($set['recruitment_date'], 0, 5) == $years[4])
				$hr_reports_temp[$set['organisation_id']][$years[4]][$set['employee_type']][$set['id']] = $set['id'];
		}
		
		foreach($hr_reports_temp as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_reports_temp[$key][$key2][$key3]);
				}	
			}
		}
		return $hr_reports;
	}
        
        /*
         * Get the Separation by Position Level for Agencies
         */
        
        public function getSeparationAgenciesPosition($five_year_plan, $organisation)
        {
                $hr_reports = array();
                $sql = new Sql($this->dbAdapter);
				$select = $sql->select();
                $five_year_start_end = $this->FiveYearPlan($five_year_plan);
                
               /* switch($report_type){
                    case "list":*/
                        $select->from(array('t1' => 'emp_separation_record'))
					->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select->where(array('t1.separation_order_date >= ? ' => $five_year_start_end['from_date'], 't1.separation_order_date <= ? ' => $five_year_start_end['to_date']));
                        $select->order('t4.date ASC');
                        $select->order('t6.date ASC');
                        if($organisation != 0){
                            $select->where(array('t2.organisation_id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['employee_details_id']] = $set;
                        }
                     /*   break;
                    
                    case "summary":
                        $sql1 = new Sql($this->dbAdapter);
                        $select1 = $sql1->select();
                        $select1->from(array('t1' => 'emp_separation_record'))
					->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select1->where(array('t1.separation_order_date >= ? ' => $five_year_start_end['from_date'], 't1.separation_order_date <= ? ' => $five_year_start_end['to_date']));
                        $select1->order('t4.date ASC');
                        $select1->order('t6.date ASC');
                        if($organisation != 0){
                            $select1->where(array('t2.id' =>$organisation));
                        }
                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();

                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $hr_reports[$set1['organisation_name']][$set1['position_title']]['requirement_year_1'] += (int)$set1['requirement_year_1'];
                            $hr_reports[$set1['organisation_name']][$set1['position_title']]['requirement_year_2'] += (int)$set1['requirement_year_2'];
                            $hr_reports[$set1['organisation_name']][$set1['position_title']]['requirement_year_3'] += (int)$set['requirement_year_3'];
                            $hr_reports[$set1['organisation_name']][$set1['position_title']]['requirement_year_4'] += (int)$set1['requirement_year_4'];
                            $hr_reports[$set1['organisation_name']][$set1['position_title']]['requirement_year_5'] += (int)$set1['requirement_year_5'];
                        }
                       break;
                }*/

		return $hr_reports;
        }
	
	/*
	* Get the promotion for RUB Employees
	*/
	
	public function getEmployeePromotions($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
                $five_year_start_end = $this->FiveYearPlan($five_year_plan);
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                switch($report_type){
                    case "list":
                        $select->from(array('t1' => 'emp_promotion'))
					->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select->where(array('t1.promotion_effective_date >= ? ' => $five_year_start_end['from_date'], 't1.promotion_effective_date <= ? ' => $five_year_start_end['to_date']));
                        $select->order('t4.date ASC');
                        $select->order('t6.date ASC');
                        if($organisation != 0){
                            $select->where(array('t2.organisation_id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['employee_details_id']] = $set;
                        }
                        break;
                    
                    case "summary":
                        $select->from(array('t1' => 'emp_promotion'))
					->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select->where(array('t1.promotion_effective_date >= ? ' => $five_year_start_end['from_date'], 't1.promotion_effective_date <= ? ' => $five_year_start_end['to_date']));
                        $select->order('t4.date ASC');
                        $select->order('t6.date ASC');
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_1'] += (int)$set['requirement_year_1'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_2'] += (int)$set['requirement_year_2'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_3'] += (int)$set['requirement_year_3'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_4'] += (int)$set['requirement_year_4'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_5'] += (int)$set['requirement_year_5'];
                        }
                        break;
                }

		return $hr_reports;
	}
	
	/*
	* Get the Promotion, Recruitment and Separation Record in RUB
	*/
	
	public function getRecruitmentSeparation($five_year_plan, $organisation)
	{
		$hr_reports = array();
                $five_year_start_end = $this->FiveYearPlan($five_year_plan);
                
                $sql = new Sql($this->dbAdapter);
				$select = $sql->select();
                
                /*switch($report_type){
                    case "list":*/
                        $select->from(array('t1' => 'emp_separation_record'))
										->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id', 'recruitment_date'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select->where(array('t1.separation_order_date >= ? ' => $five_year_start_end['from_date'], 't1.separation_order_date <= ? ' => $five_year_start_end['to_date']));
                        $select->order('t4.date ASC');
                        $select->order('t6.date ASC');
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[] = $set;
                        }
                        
                        $sql2 = new Sql($this->dbAdapter);
                        $select2 = $sql2->select();
                        $select2->from(array('t1' => 'employee_details'))
                                                ->columns(array('emp_type','recruitment_date'))
                                                ->join(array('t2'=>'emp_position_level'),
                                    't1.id = t2.employee_details_id',array('employee_details_id','position_level_id'))
                                                ->join(array('t3'=>'position_level'),
                                    't2.position_level_id = t3.id',array('position_level'))
                                                ->join(array('t4'=>'employee_type'),
                                    't1.emp_type = t4.id',array('employee_type'));
                        $select2->where(array('t1.recruitment_date >= ? ' => $five_year_start_end['from_date'], 't1.recruitment_date <= ? ' => $five_year_start_end['to_date']));
                        $select2->order('t2.date ASC');
                        $stmt2 = $sql2->prepareStatementForSqlObject($select);
                        $result2 = $stmt2->execute();

                        $resultSet2 = new ResultSet();
                        $resultSet2->initialize($result2);
                        
                       /* break;
                    
                    case "summary":
                        $select->from(array('t1' => 'emp_separation_record'))
										->join(array('t2'=>'employee_details'),
                                            't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
                                        ->join(array('t3'=>'organisation'),
                                            't2.organisation_id = t3.id',array('organisation_name'))
                                        ->join(array('t4'=>'emp_position_title'),
                                            't2.id = t4.employee_details_id',array('position_title_id'))
                                        ->join(array('t5'=>'position_title'),
                                            't4.position_title_id = t5.id',array('position_title'))
                                        ->join(array('t6'=>'emp_position_level'),
                                            't2.id = t6.employee_details_id',array('position_level_id'))
                                        ->join(array('t7'=>'position_level'),
                                            't6.position_level_id = t7.id',array('position_level'));
                        $select->where(array('t1.separation_order_date >= ? ' => $five_year_start_end['from_date'], 't1.separation_order_date <= ? ' => $five_year_start_end['to_date']));
                        $select->order('t4.date ASC');
                        $select->order('t6.date ASC');
                        if($organisation != 0){
                            $select->where(array('t2.id' =>$organisation));
                        }
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        $resultSet = new ResultSet();
                        $resultSet->initialize($result);
                        foreach($resultSet as $set){
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_1'] += (int)$set['requirement_year_1'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_2'] += (int)$set['requirement_year_2'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_3'] += (int)$set['requirement_year_3'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_4'] += (int)$set['requirement_year_4'];
                            $hr_reports[$set['organisation_name']][$set['position_title']]['requirement_year_5'] += (int)$set['requirement_year_5'];
                        }
                        
                        $sql2 = new Sql($this->dbAdapter);
                        $select2 = $sql2->select();
                        $select2->from(array('t1' => 'employee_details'))
                                                ->columns(array('emp_type','recruitment_date'))
                                                ->join(array('t2'=>'emp_position_level'),
                                    't1.id = t2.employee_details_id',array('employee_details_id','position_level_id'))
                                                ->join(array('t3'=>'position_level'),
                                    't2.position_level_id = t3.id',array('position_level'))
                                                ->join(array('t4'=>'employee_type'),
                                    't1.emp_type = t4.id',array('employee_type'));
                        $select->order('t2.date ASC');
                        $select->where(array('t1.recruitment_date >= ? ' => $five_year_start_end['from_date'], 't1.recruitment_date <= ? ' => $five_year_start_end['to_date']));
                        $stmt2 = $sql2->prepareStatementForSqlObject($select);
                        $result2 = $stmt2->execute();

                        $resultSet2 = new ResultSet();
                        $resultSet2->initialize($result2);
                        
                        break;
                }*/

		return $hr_reports;
	}
	
	/*
	* Get Staff Promotion Details
	*/
    
	public function getStaffPromotionDetails($organisation, $year)
	{
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_separation_record'))
						->join(array('t2'=>'employee_details'),
							't1.employee_details_id = t2.id',array('first_name','middle_name', 'last_name', 'emp_id'))
						->join(array('t3'=>'organisation'),
							't2.organisation_id = t3.id',array('organisation_name'))
						->join(array('t4'=>'emp_position_title'),
							't2.id = t4.employee_details_id',array('position_title_id'))
						->join(array('t5'=>'position_title'),
							't4.position_title_id = t5.id',array('position_title'))
						->join(array('t6'=>'emp_position_level'),
							't2.id = t6.employee_details_id',array('position_level_id'))
						->join(array('t7'=>'position_level'),
							't6.position_level_id = t7.id',array('position_level'));
		$select->where(array('t1.separation_order_date >= ? ' => $five_year_start_end['from_date'], 't1.separation_order_date <= ? ' => $five_year_start_end['to_date']));
		$select->order('t4.date ASC');
		$select->order('t6.date ASC');
		if($organisation != 0){
			$select->where(array('t2.id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_reports[] = $set;
		}
		
		return $hr_reports;
	}
	
	/*
	* Get Staff Separation Details
	*/
	
	public function getStaffSepearationDetails($organisation, $year)
	{
		 
	}
	
	/*
	* Get Staff APA Details
	*/
	
	public function getStaffApaDetails($organisation, $year)
	{
		
	}
	
	/*
	* Get Staff By Dzongkhag
	*/
	
	public function getStaffByDzongkhag($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'emp_dzongkhag'))
						->join(array('t2'=>'dzongkhag'),
							't1.emp_dzongkhag = t2.id',array('dzongkhag_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't8.id = t7.position_level_id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		$select->order('t2.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['dzongkhag_name']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Nationality
	*/
	
	public function getStaffByNationality($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'country'))
						->join(array('t2'=>'country'),
							't1.country = t2.id',array('country'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['country']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Religion
	*/
	
	public function getStaffByReligion($organisation, $year)
	{                
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'religion'))
						->join(array('t2'=>'religion'),
							't1.religion = t2.id',array('religion'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['religion']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Gender
	*/
	
	public function getStaffByGender($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'gender'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Organisation
	*/
	
	public function getStaffByOrganisation($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'gender'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Department
	*/
	
	public function getStaffByDepartment($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'departments_id'))
						->join(array('t2'=>'departments'),
							't1.departments_id = t2.id',array('department_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['department_name']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Employee Type
	*/
	
	public function getStaffByEmployeeType($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'emp_type'))
						->join(array('t2'=>'employee_type'),
							't1.emp_type = t2.id',array('employee_type'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['employee_type']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Section
	*/
	
	public function getStaffBySection($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'departments_units_id'))
						->join(array('t2'=>'department_units'),
							't1.departments_units_id = t2.id',array('unit_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['unit_name']][$set['major_occupational_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$hr_reports[$key][$key2][$key3][$key4] = count($hr_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff Marital Status
	*/
	
	public function getStaffByMaritialStatus($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id', 'marital_status'))
						->join(array('t2'=>'maritial_status'),
							't1.marital_status = t2.id',array('maritial_status'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t7'=>'emp_position_level'),
							't1.id = t7.employee_details_id',array('position_level_id'))
						->join(array('t8'=>'position_level'),
							't7.position_level_id = t8.id',array('position_level'))
						->join(array('t9'=>'major_occupational_group'),
							't8.major_occupational_group_id = t9.id',array('major_occupational_group'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['maritial_status']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff POsition Title Details
	*/
	
	public function getStaffByPositionTitle($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id'))
						->join(array('t2'=>'emp_position_title'),
							't1.id = t2.employee_details_id',array('position_title_id'))
						->join(array('t3'=>'position_title'),
							't2.position_title_id = t3.id',array('position_title'))
						->join(array('t4'=>'gender'),
							't1.gender = t4.id',array('gender'))
						->join(array('t5'=>'organisation'),
							't1.organisation_id = t5.id',array('abbr'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t5.id ASC');
		$select->order('t2.date ASC');
		$select->order('t3.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['position_title']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff Position Level
	*/
	
	public function getStaffByPositionLevel($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id'))
						->join(array('t2'=>'emp_position_level'),
							't1.id = t2.employee_details_id',array('position_level_id'))
						->join(array('t3'=>'position_level'),
							't2.position_level_id = t3.id',array('position_level'))
						->join(array('t4'=>'gender'),
							't1.gender = t4.id',array('gender'))
						->join(array('t5'=>'organisation'),
							't1.organisation_id = t5.id',array('abbr'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t5.id ASC');
		$select->order('t2.date ASC');
		$select->order('t3.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['position_level']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	/*
	* Get Staff By Blood Group
	*/
	
	public function getStaffByBloodGroup($organisation, $year)
	{
		$hr_data = array();
		$hr_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'employee_details'))
						->columns(array('emp_id'))
						->join(array('t2'=>'blood_group'),
							't1.blood_group = t2.id',array('blood_group'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'));
		$select->where(array('t1.emp_resignation_id = ? ' => '0'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_data[$set['abbr']][$set['blood_group']][$set['gender']][$set['emp_id']] = $set['emp_id'];
		}
		foreach($hr_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$hr_reports[$key][$key2][$key3] = count($hr_data[$key][$key2][$key3]);
				}
			}
		}
		return $hr_reports;
	}
	
	 
	/*
	 * Get the List of Staff that are on leave
	 */
	
	public function getStaffOnLeave($date, $organisation)
	{
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
		
		$on_leave_report = array();

		$sql = new Sql($this->dbAdapter);
		if($organisation != 1){
			$select = $sql->select();
			$select->from(array('t1' => 'emp_leave'))
				->join(array('t2'=>'employee_details'),
					't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
				->join(array('t3'=>'departments'),
					't2.departments_id = t3.id',array('department_name'))
				->join(array('t4'=>'emp_leave_category'),
					't1.emp_leave_category_id = t4.id',array('leave_category'))
				->join(array('t5'=>'employee_details'),
					't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'))
				->join(array('t6'=>'organisation'),
					't2.organisation_id = t6.id',array('abbr'));
			$select->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select->where(array('t1.leave_status' => 'Approved'));
			$select->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select->where(array('t2.organisation_id' => $organisation));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->buffer();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$on_leave_report[] = $set;
			}
			return $on_leave_report;
		}else {
			$select = $sql->select();
			$select->from(array('t1' => 'emp_leave'))
				->join(array('t2'=>'employee_details'),
					't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
				->join(array('t3'=>'departments'),
					't2.departments_id = t3.id',array('department_name'))
				->join(array('t4'=>'emp_leave_category'),
					't1.emp_leave_category_id = t4.id',array('leave_category'))
				->join(array('t5'=>'employee_details'),
					't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'))			
				->join(array('t6'=>'organisation'),
					't2.organisation_id = t6.id',array('abbr'));
			$select->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select->where(array('t1.leave_status' => 'Approved'));
			$select->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select->where(array('t2.organisation_id' => $organisation));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->buffer();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$on_leave_report[] = $set;
			}

			$select1 = $sql->select();
			$select1->from(array('t1' => 'emp_leave'))
				->join(array('t2'=>'employee_details'),
					't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
				->join(array('t3'=>'departments'),
					't2.departments_id = t3.id',array('department_name'))
				->join(array('t4'=>'emp_leave_category'),
					't1.emp_leave_category_id = t4.id',array('leave_category'))
				->join(array('t5'=>'employee_details'),
					't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'))			
				->join(array('t6'=>'organisation'),
					't2.organisation_id = t6.id',array('abbr'))
				->join(array('t7' => 'users'),
					't7.username = t2.emp_id', array('role'));
			$select1->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select1->where(array('t1.leave_status' => 'Approved'));
			$select1->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select1->where->notLike('t2.organisation_id','1');
			$select1->where->like('t7.role', "%_PRESIDENT");


			$stmt1 = $sql->prepareStatementForSqlObject($select1);
			$result1 = $stmt1->execute();

			$resultSet1 = new ResultSet();
			$resultSet1->buffer();
			$resultSet1->initialize($result1);

			foreach($resultSet1 as $set1){
				$on_leave_report[] = $set1;
			}

			return $on_leave_report;
		}	
	}


	/*
	 * Get the List of Staff that are on leave
	 */
	
	public function getStaffPendingLeave($date, $organisation_id)
	{ 
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
		$pending_leave_report = array();

		$sql = new Sql($this->dbAdapter);

		if($organisation_id != 1){
			$select = $sql->select();
			$select->from(array('t1' => 'emp_leave'))
								->join(array('t2'=>'employee_details'),
									't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
								->join(array('t3'=>'departments'),
									't2.departments_id = t3.id',array('department_name'))
								->join(array('t4'=>'emp_leave_category'),
									't1.emp_leave_category_id = t4.id',array('leave_category'))
								->join(array('t5'=>'employee_details'),
									't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'));
			$select->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select->where(array('t1.leave_status' => 'Pending'));
			$select->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select->where(array('t2.organisation_id' => $organisation_id));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->buffer();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$pending_leave_report[] = $set;
			}
			return $pending_leave_report;
		}else{
			$select = $sql->select();
			$select->from(array('t1' => 'emp_leave'))
								->join(array('t2'=>'employee_details'),
									't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
								->join(array('t3'=>'departments'),
									't2.departments_id = t3.id',array('department_name'))
								->join(array('t4'=>'emp_leave_category'),
									't1.emp_leave_category_id = t4.id',array('leave_category'))
								->join(array('t5'=>'employee_details'),
									't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'));
			$select->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select->where(array('t1.leave_status' => 'Pending'));
			$select->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select->where(array('t2.organisation_id' => $organisation_id));
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			$resultSet = new ResultSet();
			$resultSet->buffer();
			$resultSet->initialize($result);

			foreach($resultSet as $set){
				$pending_leave_report[] = $set;
			}

			$select1 = $sql->select();
			$select1->from(array('t1' => 'emp_leave'))
								->join(array('t2'=>'employee_details'),
									't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
								->join(array('t3'=>'departments'),
									't2.departments_id = t3.id',array('department_name'))
								->join(array('t4'=>'emp_leave_category'),
									't1.emp_leave_category_id = t4.id',array('leave_category'))
								->join(array('t5'=>'employee_details'),
									't1.substitution = t5.id',array('sub_first_name'=>'first_name', 'sub_middle_name'=>'middle_name', 'sub_last_name'=>'last_name'))
								->join(array('t6' => 'users'),
										't6.username = t2.emp_id', array('role'));
			$select1->where(array('t2.emp_resignation_id = ? ' => '0'));
			$select1->where(array('t1.leave_status' => 'Pending'));
			$select1->where(array('t1.from_date >= ? ' => $from_date, 't1.to_date <= ? ' => $to_date));
			$select1->where->notLike('t2.organisation_id', "1");
			$select1->where->like('t6.role', '%_PRESIDENT');
			$stmt1 = $sql->prepareStatementForSqlObject($select1);
			$result1 = $stmt1->execute();
			$resultSet1 = new ResultSet();
			$resultSet1->buffer();
			$resultSet1->initialize($result1);

			foreach($resultSet1 as $set1){
				$pending_leave_report[] = $set1;
			}

			return $pending_leave_report;
		}
	}

	
	/*
	 * Get the List of Staff that are on Tour
	 */
	
	public function getStaffOnTour($date, $organisation)
	{
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'travel_authorization'))
		       ->join(array('t2'=>'employee_details'),
			      't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
		       ->join(array('t3'=>'departments'),
			      't2.departments_id = t3.id',array('department_name'));
		$select->where(array('t2.emp_resignation_id = ? ' => '0'));
		$select->where(array('t1.tour_status' => 'Approved'));
		$select->where(array('t1.start_date >= ? ' => $from_date, 't1.end_date <= ? ' => $to_date));
		$select->where(array('t2.organisation_id' => $organisation));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);		
	}
	
	/*
	 * Get the list of Staff that are on Training
	 */
	
	public function getStaffOnTraining($date, $organisation)
	{
		$hr_reports = array();
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'training_details'))
							->columns(array('title'=>'course_title','start_date'=> 'training_start_date','end_date'=>'training_end_date', 'type'=>'hrd_type'))
							->join(array('t2'=>'training_nominations'),
								't1.id = t2.training_details_id',array('employee_details_id'))
							->join(array('t3'=>'employee_details'),
								't2.employee_details_id = t3.id',array('first_name', 'middle_name', 'last_name'))
							->join(array('t4'=>'departments'),
								't3.departments_id = t4.id',array('department_name'));
		$select->where(array('t1.training_start_date >= ? ' => $from_date, 't1.training_end_date <= ? ' => $to_date));
		$select->where(array('t3.organisation_id' => $organisation));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$hr_reports[] = $set;
		}
		
		$select2 = $sql->select();
		$select2->from(array('t1' => 'workshop_details'))
							->columns(array('title','start_date'=> 'workshop_start_date','end_date'=>'workshop_end_date', 'type'))
							->join(array('t2'=>'training_nominations'),
								't1.id = t2.workshop_details_id',array('employee_details_id'))
							->join(array('t3'=>'employee_details'),
								't2.employee_details_id = t3.id',array('first_name', 'middle_name', 'last_name'))
							->join(array('t4'=>'departments'),
								't3.departments_id = t4.id',array('department_name'));
		$select2->where(array('t1.workshop_start_date >= ? ' => $from_date, 't1.workshop_end_date <= ? ' => $to_date));
		$select2->where(array('t3.organisation_id' => $organisation));
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();

		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		foreach($resultSet2 as $set2){
			$hr_reports[] = $set2;
		}
		
		return $hr_reports;
	}

	/*
	 * Get the List of Staff Who have claimed Leave Encashment
	 */
	
	public function getStaffLeaveEncashment($date, $organisation)
	{
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_leave_encashment'))
							->join(array('t2'=>'employee_details'),
								't1.employee_details_id = t2.id',array('first_name', 'middle_name', 'last_name'))
							->join(array('t3'=>'departments'),
								't2.departments_id = t3.id',array('department_name'));
							
		$select->where(array('t2.emp_resignation_id = ? ' => '0'));
		$select->where(array('t1.leave_encashment_status' => 'Approved'));
		$select->where(array('t1.application_date >= ? ' => $from_date, 't1.application_date <= ? ' => $to_date));
		$select->where(array('t2.organisation_id' => $organisation));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Get the list of staff on tour, trainings, leave etc.
	 * i.e. all staff that are not in office
	 */
	
	public function getOverAllStaffAdministration($date, $organisation)
	{
		$from_date = date("Y-m-d", strtotime(substr($date,0,10)));
		$to_date = date("Y-m-d", strtotime(substr($date,13,10)));
	}
	
	/*
	* Get the training implementation for the Five Year Plan
	*/
	
	public function getFiveYearTrainingImplementation($five_year_plan, $organisation, $report_type, $organisation_id)
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		$organisation_list = $this->listSelectData('organisation','organisation_name', $organisation_id);
		
		//preset the value to '0'
		foreach($organisation_list as $key => $value){
			for($i=0; $i<=4; $i++){
				$hr_reports[$key]['Planned']['longterm'][$years[$i]] = 0;
				$hr_reports[$key]['Planned']['shortterm'][$years[$i]] = 0;
				$hr_reports[$key]['Planned_implemented']['longterm'][$years[$i]] = 0;
				$hr_reports[$key]['Planned_implemented']['shortterm'][$years[$i]] = 0;
				$hr_reports[$key]['Adhoc']['longterm'][$years[$i]] = 0;
				$hr_reports[$key]['Adhoc']['shortterm'][$years[$i]] = 0;
			}
		}
		
		//get the short term and long term planned
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'hr_development'))
					->columns(array('working_agency', 'training_type', 'amount_year_1', 'amount_year_2', 'amount_year_3', 'amount_year_4', 'amount_year_5'))
					->join(array('t2'=>'training_types'),
                            't1.training_type = t2.id',array('training_type'))
					->join(array('t3'=>'five_year_plan'),
                            't1.five_year_plan = t3.five_year_plan',array('five_year_plan'))
					->join(array('t4'=>'organisation'),
                            't1.working_agency = t4.id',array('organisation_name'));
		$select->where(array('t1.approval_status' => 'Approved'));
		$select->where(array('t3.from_date <= ? ' => date('Y-m-d'), 't3.to_date >= ? ' => date('Y-m-d')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			if(preg_match('/Long Term/', $set['training_type'])){
				$hr_reports[$set['working_agency']]['Planned']['longterm'][$years[0]] += $set['amount_year_1'];
				$hr_reports[$set['working_agency']]['Planned']['longterm'][$years[1]] += $set['amount_year_2'];
				$hr_reports[$set['working_agency']]['Planned']['longterm'][$years[2]] += $set['amount_year_3'];
				$hr_reports[$set['working_agency']]['Planned']['longterm'][$years[3]] += $set['amount_year_4'];
				$hr_reports[$set['working_agency']]['Planned']['longterm'][$years[4]] += $set['amount_year_5'];
			}
				
			if(preg_match('/Short Term/', $set['training_type'])){
				$hr_reports[$set['working_agency']]['Planned']['shortterm'][$years[0]] += $set['amount_year_1'];
				$hr_reports[$set['working_agency']]['Planned']['shortterm'][$years[1]] += $set['amount_year_2'];
				$hr_reports[$set['working_agency']]['Planned']['shortterm'][$years[2]] += $set['amount_year_3'];
				$hr_reports[$set['working_agency']]['Planned']['shortterm'][$years[3]] += $set['amount_year_4'];
				$hr_reports[$set['working_agency']]['Planned']['shortterm'][$years[4]] += $set['amount_year_5'];
			}
			
		}
		
		//implemented long term trainings by year
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'training_details'))
					->columns(array('hrd_type','training_start_date', 'training_end_date'))
					->join(array('t2'=>'emp_training_details'),
                            't1.id = t2.training_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select2->where(array('t1.training_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$hr_reports_temp = array();
		foreach($resultSet2 as $set2){
			if(preg_match('/Planned/', $set2['hrd_type'])){
				$hr_reports_temp[$set2['organisation_id']]['Planned_implemented']['longterm'][$set2['training_start_date']][$set2['employee_details_id']] = $set2['employee_details_id'];
			}
			if(preg_match('/Adhoc/', $set2['hrd_type'])){
				$hr_reports_temp[$set2['organisation_id']]['Adhoc']['longterm'][$set2['training_start_date']][$set2['employee_details_id']] = $set2['employee_details_id'];
			}
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					if(substr($value3, 0, 5) == $years[0])
						$hr_reports[$key][$key2][$key3][$years[0]] += count($hr_reports_temp[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[1])
						$hr_reports[$key][$key2][$key3][$years[1]] += count($hr_reports_temp[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[2])
						$hr_reports[$key][$key2][$key3][$years[2]] += count($hr_reports_temp[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[3])
						$hr_reports[$key][$key2][$key3][$years[3]] += count($hr_reports_temp[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[4])
						$hr_reports[$key][$key2][$key3][$years[4]] += count($hr_reports_temp[$key][$key2][$key3][$value3['training_start_date']]);
				}
			}
			
		}
		
		//implemented short term trainings by year
		$sql3 = new Sql($this->dbAdapter);
		$select3 = $sql3->select();
		$select3->from(array('t1' => 'workshop_details'))
					->columns(array('hrd_type', 'workshop_start_date', 'workshop_end_date'))
					->join(array('t2'=>'emp_workshop_details'),
                            't1.id = t2.workshop_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select3->where(array('t1.workshop_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt3 = $sql3->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		unset($hr_reports_temp);
		$hr_reports_temp = array();
		foreach($resultSet3 as $set3){
			if(preg_match('/Planned/', $set3['hrd_type'])){
				$hr_reports_temp[$set3['organisation_id']]['Planned_implemented']['shortterm'][$set3['workshop_start_date']][$set3['employee_details_id']] = $set3['employee_details_id'];
			}
			if(preg_match('/Adhoc/', $set3['hrd_type'])){
				$hr_reports_temp[$set3['organisation_id']]['Adhoc']['shortterm'][$set3['workshop_start_date']][$set3['employee_details_id']] = $set3['employee_details_id'];
			}
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						if(substr($key4, 0, 5) == $years[0])
							$hr_reports[$key][$key2][$key3][$years[0]] += count($hr_reports_temp[$key][$key2][$key3][$key4]);
						if(substr($key4, 0, 5) == $years[1])
							$hr_reports[$key][$key2][$key3][$years[1]] += count($hr_reports_temp[$key][$key2][$key3][$key4]);
						if(substr($key4, 0, 5) == $years[2])
							$hr_reports[$key][$key2][$key3][$years[2]] += count($hr_reports_temp[$key][$key2][$key3][$key4]);
						if(substr($key4, 0, 5) == $years[3])
							$hr_reports[$key][$key2][$key3][$years[3]] += count($hr_reports_temp[$key][$key2][$key3][$key4]);
						if(substr($key4, 0, 5) == $years[4])
							$hr_reports[$key][$key2][$key3][$years[4]] += count($hr_reports_temp[$key][$key2][$key3][$key4]);
					}
				}
			}
			
		}
		return $hr_reports;
	}
	
	/*
	* Get the training implementation by Category
        * (not used at the present)
	*/
	
	public function getAgencyTrainingImplementation($five_year_plan, $organisation, $report_type)
	{
		$hr_reports = array();
		
		return $hr_reports;
	}
	
	/*
	* Gett the training implementation by Category
	*/
	
	public function getTrainingImplementationCategory($five_year_plan, $organisation, $report_type, $organisation_id)
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		$organisation_list = $this->listSelectData('organisation','organisation_name', $organisation_id);
		
		//preset the value to '0'
		foreach($organisation_list as $key => $value){
			for($i=0; $i<=4; $i++){
				$hr_reports[$key]['longterm'][$years[$i]] = 0;
				$hr_reports[$key]['shortterm'][$years[$i]] = 0;
				$hr_reports[$key]['others'][$years[$i]] = 0;
			}
		}
                
		//implemented long term trainings by year
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'training_details'))
					->columns(array('hrd_type','training_start_date', 'training_end_date'))
					->join(array('t2'=>'emp_training_details'),
                            't1.id = t2.training_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select2->where(array('t1.training_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$hr_reports_temp = array();
		foreach($resultSet2 as $set2){
                        $hr_reports_temp[$set2['organisation_id']]['longterm'][$set2['training_start_date']][$set2['employee_details_id']] = $set2['employee_details_id'];
			
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
                                if(substr($value2, 0, 5) == $years[0])
                                        $hr_reports[$key][$key2][$years[0]] += count($hr_reports_temp[$key][$key2][$value2['training_start_date']]);
                                if(substr($value2, 0, 5) == $years[1])
                                        $hr_reports[$key][$key2][$years[1]] += count($hr_reports_temp[$key][$key2][$value2['training_start_date']]);
                                if(substr($value2, 0, 5) == $years[2])
                                        $hr_reports[$key][$key2][$years[2]] += count($hr_reports_temp[$key][$key2][$value2['training_start_date']]);
                                if(substr($value2, 0, 5) == $years[3])
                                        $hr_reports[$key][$key2][$years[3]] += count($hr_reports_temp[$key][$key2][$value2['training_start_date']]);
                                if(substr($value2, 0, 5) == $years[4])
                                        $hr_reports[$key][$key2][$years[4]] += count($hr_reports_temp[$key][$key2][$value2['training_start_date']]);
				
			}
			
		}
		
		//implemented short term trainings by year
		$sql3 = new Sql($this->dbAdapter);
		$select3 = $sql3->select();
		$select3->from(array('t1' => 'workshop_details'))
					->columns(array('hrd_type', 'workshop_start_date', 'workshop_end_date'))
					->join(array('t2'=>'emp_workshop_details'),
                            't1.id = t2.workshop_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select3->where(array('t1.workshop_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt3 = $sql3->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		unset($hr_reports_temp);
		$hr_reports_temp = array();
		foreach($resultSet3 as $set3){
                        $hr_reports_temp[$set3['organisation_id']]['shortterm'][$set3['workshop_start_date']][$set3['employee_details_id']] = $set3['employee_details_id'];
			
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
                                        if(substr($key3, 0, 5) == $years[0])
                                                $hr_reports[$key][$key2][$years[0]] += count($hr_reports_temp[$key][$key2][$key3]);
                                        if(substr($key3, 0, 5) == $years[1])
                                                $hr_reports[$key][$key2][$years[1]] += count($hr_reports_temp[$key][$key2][$key3]);
                                        if(substr($key3, 0, 5) == $years[2])
                                                $hr_reports[$key][$key2][$years[2]] += count($hr_reports_temp[$key][$key2][$key3]);
                                        if(substr($key3, 0, 5) == $years[3])
                                                $hr_reports[$key][$key2][$years[3]] += count($hr_reports_temp[$key][$key2][$key3]);
                                        if(substr($key3, 0, 5) == $years[4])
                                                $hr_reports[$key][$key2][$years[4]] += count($hr_reports_temp[$key][$key2][$key3]);
					
				}
			}
			
		}
		return $hr_reports;
	}
	
	/*
	* Get the training implementation by Country
	*/
	
	public function getTrainingImplementationCountry($five_year_plan, $organisation, $report_type, $organisation_id)
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		$organisation_list = $this->listSelectData('organisation','organisation_name', $organisation_id);
		
		//preset the value to '0'
		foreach($organisation_list as $key => $value){
			for($i=0; $i<=4; $i++){
				$hr_reports[$key]['longterm'][$years[$i]][] = NULL;
				$hr_reports[$key]['shortterm'][$years[$i]][] = NULL;
				$hr_reports[$key]['others'][$years[$i]][] = NULL;
			}
		}
                
		//implemented long term trainings by year
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'training_details'))
					->columns(array('hrd_type','training_start_date', 'training_end_date','institute_country'))
					->join(array('t2'=>'emp_training_details'),
                            't1.id = t2.training_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select2->where(array('t1.training_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$hr_reports_temp = array();
		foreach($resultSet2 as $set2){
                        $hr_reports_temp[$set2['organisation_id']]['longterm'][$set2['training_start_date']][$set2['institute_country']] = $set2['institute_country'];
			
		}
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
                            foreach($value2 as $key3 => $value3){
                                foreach($value3 as $key4 => $value4){
                                    if(substr($key3, 0, 5) == $years[0])
                                        $hr_reports[$key][$key2][$years[0]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[1])
                                        $hr_reports[$key][$key2][$years[1]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[2])
                                        $hr_reports[$key][$key2][$years[2]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[3])
                                        $hr_reports[$key][$key2][$years[3]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[4])
                                        $hr_reports[$key][$key2][$years[4]][$key4]= $key4;
                                }
                            }
			}
		}
		//implemented short term trainings by year
		$sql3 = new Sql($this->dbAdapter);
		$select3 = $sql3->select();
		$select3->from(array('t1' => 'workshop_details'))
					->columns(array('hrd_type', 'workshop_start_date', 'workshop_end_date','institute_country'))
					->join(array('t2'=>'emp_workshop_details'),
                            't1.id = t2.workshop_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'));
		$select3->where(array('t1.workshop_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt3 = $sql3->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		unset($hr_reports_temp);
		$hr_reports_temp = array();
		foreach($resultSet3 as $set3){
                        $hr_reports_temp[$set3['organisation_id']]['shortterm'][$set3['workshop_start_date']][$set3['institute_country']] = $set3['institute_country'];
			
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
                            foreach($value2 as $key3 => $value3){
                                foreach($value3 as $key4 => $value4){
                                    if(substr($key3, 0, 5) == $years[0])
                                        $hr_reports[$key][$key2][$years[0]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[1])
                                        $hr_reports[$key][$key2][$years[1]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[2])
                                        $hr_reports[$key][$key2][$years[2]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[3])
                                        $hr_reports[$key][$key2][$years[3]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[4])
                                        $hr_reports[$key][$key2][$years[4]][$key4]= $key4;
                                }
                            }
			}
		}
                
		return $hr_reports;
	}
	
	/*
	* Get the training implementation by Funding
	*/
	
	public function getTrainingImplementationFunding($five_year_plan, $organisation, $report_type, $organisation_id)
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan($five_year_plan);
		$five_year_plan = $this->FiveYearPlan($five_year_plan);
		$organisation_list = $this->listSelectData('organisation','organisation_name', $organisation_id);
		
		//preset the value to '0'
		foreach($organisation_list as $key => $value){
			for($i=0; $i<=4; $i++){
				$hr_reports[$key]['longterm'][$years[$i]][] = NULL;
				$hr_reports[$key]['shortterm'][$years[$i]][] = NULL;
				$hr_reports[$key]['others'][$years[$i]][] = NULL;
			}
		}
                
		//implemented long term trainings by year
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'training_details'))
					->columns(array('hrd_type','training_start_date', 'training_end_date','source_of_funding'))
					->join(array('t2'=>'emp_training_details'),
                            't1.id = t2.training_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'))
                                        ->join(array('t4'=>'funding_category'),
                            't1.source_of_funding = t4.id',array('funding_type'));
		$select2->where(array('t1.training_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$hr_reports_temp = array();
		foreach($resultSet2 as $set2){
                        $hr_reports_temp[$set2['organisation_id']]['longterm'][$set2['training_start_date']][$set2['funding_type']] = $set2['funding_type'];
			
		}
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
                            foreach($value2 as $key3 => $value3){
                                foreach($value3 as $key4 => $value4){
                                    if(substr($key3, 0, 5) == $years[0])
                                        $hr_reports[$key][$key2][$years[0]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[1])
                                        $hr_reports[$key][$key2][$years[1]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[2])
                                        $hr_reports[$key][$key2][$years[2]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[3])
                                        $hr_reports[$key][$key2][$years[3]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[4])
                                        $hr_reports[$key][$key2][$years[4]][$key4]= $key4;
                                }
                            }
			}
		}
		//implemented short term trainings by year
		$sql3 = new Sql($this->dbAdapter);
		$select3 = $sql3->select();
		$select3->from(array('t1' => 'workshop_details'))
					->columns(array('hrd_type', 'workshop_start_date', 'workshop_end_date','source_of_funding'))
					->join(array('t2'=>'emp_workshop_details'),
                            't1.id = t2.workshop_details_id',array('employee_details_id'))
					->join(array('t3'=>'employee_details'),
                            't3.id = t2.employee_details_id',array('organisation_id'))
                                        ->join(array('t4'=>'funding_category'),
                            't1.source_of_funding = t4.id',array('funding_type'));
		$select3->where(array('t1.workshop_start_date >= ? ' => $five_year_plan['from_date']));
		$stmt3 = $sql3->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		unset($hr_reports_temp);
		$hr_reports_temp = array();
		foreach($resultSet3 as $set3){
                        $hr_reports_temp[$set3['organisation_id']]['shortterm'][$set3['workshop_start_date']][$set3['funding_type']] = $set3['funding_type'];
			
		}
		
		foreach($hr_reports_temp as $key=>$value){
			foreach($value as $key2 => $value2){
                            foreach($value2 as $key3 => $value3){
                                foreach($value3 as $key4 => $value4){
                                    if(substr($key3, 0, 5) == $years[0])
                                        $hr_reports[$key][$key2][$years[0]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[1])
                                        $hr_reports[$key][$key2][$years[1]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[2])
                                        $hr_reports[$key][$key2][$years[2]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[3])
                                        $hr_reports[$key][$key2][$years[3]][$key4] = $key4;
                                    if(substr($key3, 0, 5) == $years[4])
                                        $hr_reports[$key][$key2][$years[4]][$key4]= $key4;
                                }
                            }
			}
		}
                
		return $hr_reports;
	}
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getStudentReport($report_details)
	{ 
		$student_reports = array();
                
                $report_name = $report_details['report_name'];
                //$report_type = $report_details['report_type'];
                $organisation = $report_details['organisation'];
                
                
                switch($report_name){
                    case "student_intake_report_scholarship":
                		$year = $report_details['year'];
                        $student_reports = $this->getStudentByScholarship($year, $organisation);
                        break;
                    
                    case "student_intake_report_college":
                    	$year = $report_details['year'];
                        $student_reports = $this->getStudentByCollege($year, $organisation);
                        break;
                    
                    //not sure whether this is used
                    case "total_student_report":
                        $student_reports = $this->getTotalStudent($year, $organisation);
                        break;
					
					case "overall_student_by_programme_inrub":
                        $student_reports = $this->getStudentByProgrammeInRUB($organisation);
                        break;

					case "currently_student_by_programme_incampus":
                        $student_reports = $this->getStudentByProgrammeInCampus($organisation);
                        break;
					
					case "currently_student_by_programme_offcampus":
                        $student_reports = $this->getStudentByProgrammeOffCampus($organisation);
                        break;

					case "student_by_programme_enrolled":
						$year = $report_details['year'];
                        $student_reports = $this->getStudentByProgrammeEnrolled($organisation, $year);
                        break;
					
					case "grudated_students":
						$year = $report_details['year'];
                        $student_reports = $this->getGraudatedStudents($organisation, $year);
                        break;
                    case "currently_suspended_students":
                        $student_reports = $this->getSuspendedStudents($organisation);
                        break;
                    case "overall_terminated_students":
                        $student_reports = $this->getTerminatedStudents($organisation);
                        break;
                    case "overall_withdrawn_students":
                        $student_reports = $this->getwithdrawnStudents($organisation);
                        break;
					
					case "student_by_dzongkhag":
                        $student_reports = $this->getStudentByDzongkhag($organisation);
                        break;
					
					case "student_by_nationality":
                        $student_reports = $this->getStudentByNationality($organisation);
                        break;
					
					case "student_by_religion":
                        $student_reports = $this->getStudentByReligion($organisation);
                        break;
					
					case "student_by_gender":
                        $student_reports = $this->getStudentByGender($organisation);
                        break;
					
					case "student_by_bloodgroup":
                        $student_reports = $this->getStudentByBloodGroup($organisation);
                        break;
                }

		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by scholarship type
	*/
	
	public function getStudentByScholarship($year, $organisation)
	{ 
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'scholarship_type', 'programmes_id', 'enrollment_year'))
						->join(array('t2'=>'student_type'),
							't1.scholarship_type = t2.id',array('student_type'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5' => 'programmes'),
							't5.id = t1.programmes_id', array('programme_name'));
		$select->where(array('t1.enrollment_year' => $year));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		} 
		foreach($student_data as $key => $value){ 
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){ 
						$student_reports[$key][$key2][$key3][$key4] = count($student_data[$key][$key2][$key3][$key4]);
					}
				}			
			}
		} 
		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by college for a given year
	*/
	
	public function getStudentByCollege($year, $organisation)
	{
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'scholarship_type', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'));
		$select->where(array('t1.enrollment_year' =>$year));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$student_reports[$key][$key2][$key3] = count($student_data[$key][$key2][$key3]);
				}			
			}
		}
		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by year
	*/
	
	public function getStudentByYear($year, $organisation, $report_type)
	{
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'scholarship_type'))
						->join(array('t2'=>'student_type'),
							't1.scholarship_type = t2.id',array('student_type'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'));
		$select->where(array('t1.enrollment_year' =>$year));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				$student_reports[$key][$key2] = count($student_data[$key][$key2]);
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Programme
	*/
	
	public function getStudentByProgramme($organisation, $year)
	{
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$student_reports[$key][$key2][$key3] = count($student_data[$key][$key2][$key3]);
				}		
			}
		}
		return $student_reports;
	}
	
	
	public function getStudentByProgrammeInRUB($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}
		//var_dump($year_id); die();
		// 1- Reported, 2 & 3- Suspended (disciplinary and medical) and 9 - Suspended (Other Reason)
		$student_status = array('1','2','3','9');
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}
	
	public function getStudentByProgrammeInCampus($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$semester = NULL;
		if($organisation == 0){
			//$semester = $this->getSemester(5);
			$academic_event_details = $this->getSemester(5);
	        $semester = $academic_event_details['academic_event'];
	        
		} else{
			//$semester = $this->getSemester($organisation);
			$academic_event_details = $this->getSemester($organisation_id);
	        $semester = $academic_event_details['academic_event'];
		}
		//$academic_year = $this->getAcademicYear($semester);
		//$academic_event_details = $this->getSemester($semester);
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}

		//var_dump($year_id); die();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where->EqualTo('t5.academic_year', $academic_year);
		$select->where(array('t1.student_status_type_id' =>'1'));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Programme Enrolled by Year
	*/
	
	public function getStudentByProgrammeEnrolled($organisation, $year)
	{
		$student_data = array();
		$student_reports = array();
		//$semester = $this->getSemester($organisation);
		//$academic_year = $this->getAcademicYear($semester);

		$academic_event_details = $this->getSemester($organisation_id);

        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}
		
		//student status
		// 1- Reported, 2 and 3- Suspended (disciplinary and medical)
        $student_status = array('1','2','3');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id', 'enrollment_year'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t1.enrollment_year' =>$year));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['enrollment_year']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$student_reports[$key][$key2][$key3][$key4] = count($student_data[$key][$key2][$key3][$key4]);
					}
				}		
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Programme Off Campus
	*/
	
	public function getStudentByProgrammeOffCampus($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$semester = NULL;
		if($organisation == 0){
			//$semester = $this->getSemester(5);
			$academic_event_details = $this->getSemester(5);
        	$semester_session = $academic_event_details['academic_event'];
        
		} else{
			//$semester = $this->getSemester($organisation);
			$academic_event_details = $this->getSemester($organisation_id);
	        $semester = $academic_event_details['academic_event'];
		}
		//$academic_year = $this->getAcademicYear($semester);
		//$academic_event_details = $this->getSemester($semester);
        $academic_year = $this->getAcademicYear($academic_event_details);
		
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}
		
		//student status
		// 1- Reported, 2 and 3- Suspended (disciplinary and medical) 9 - Suspended (Other Reason)
        $student_status = array('1','2','3','9');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->where->notEqualTo('t5.academic_year', $academic_year);
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}
	
	/*
	* Total Graduated Students
	*/
	
	public function getGraudatedStudents($organisation, $year)
	{
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5' => 'alumni'),
							't5.student_id = t1.student_id', array('graduation_year'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t1.student_status_type_id' => '7'));
		$select->where(array('t5.graduation_year' => $year));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
			$select->where(array('t5.graduation_year' => $year));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['gender']][$set['student_id']] = $set['student_id'];
		} 
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				//foreach($value2 as $key3 => $value3){
					$student_reports[$key][$key2] = count($student_data[$key][$key2]);
			//	}			
			}
		}
		return $student_reports;
	}

	/*
	* Total Withdrawn Students
	*/
	
	public function getSuspendedStudents($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}

		//student status
		// 2-Suspended(disciplinary), 3-Suspended(medical) 9 - Suspended(Other Reason)
        $student_status = array('2','3','9');
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}

	/*
	* Total Terminated Students
	*/
	
	public function getTerminatedStudents($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}

		//student status
		// 4- Terminated (Disciplinary), 8 - Terminated (Chance Over)
        $student_status = array('4','8');
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}
	/*
	* Total Withdrawn Students
	*/
	
	public function getWithdrawnStudents($organisation)
	{
		$student_data = array();
		$student_reports = array();
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$years = $this->getProgrammeYears($max_programme_duration);
		$year_id = array();
		//need to use year_ids instead
		foreach($years as $key => $value){
			$year_id[] = $key;
		}

		//student status
		// 5-Withdrawn (Volunteer), 6-Withdrawn (Medical)
        $student_status = array('5','6');
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'programmes_id'))
						->join(array('t2'=>'programmes'),
							't1.programmes_id = t2.id',array('programme_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_semester_registration'),
							't1.id = t5.student_id',array('year_id'))
						->join(array('t6'=>'student_type'),
							't1.scholarship_type = t6.id',array('student_type'));
		$select->where(array('t5.year_id' =>$year_id));
		$select->where(array('t1.student_status_type_id' => $student_status));
		$select->order('t4.id ASC');
		$select->order('t5.year_id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['programme_name']][$set['year_id']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						foreach($value4 as $key5 => $value5){
							$student_reports[$key][$key2][$key3][$key4][$key5] = count($student_data[$key][$key2][$key3][$key4][$key5]);
						}
					}
				}		
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Dzongkhag
	*/
	
	public function getStudentByDzongkhag($organisation)
	{
		$student_data = array();
		$student_reports = array();
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'dzongkhag'))
						->join(array('t2'=>'dzongkhag'),
							't1.dzongkhag = t2.id',array('dzongkhag_name'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_type'),
							't1.scholarship_type = t5.id',array('student_type'));
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));
		$select->order('t4.id ASC');
		$select->order('t2.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['dzongkhag_name']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$student_reports[$key][$key2][$key3][$key4] = count($student_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Dzongkhag
	*/
	
	public function getStudentByNationality($organisation)
	{
		$student_data = array();
		$student_reports = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'country_id'))
						->join(array('t2'=>'country'),
							't1.country_id = t2.id',array('country'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_type'),
							't1.scholarship_type = t5.id',array('student_type'));
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));
		$select->order('t4.id ASC');
		$select->order('t2.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['country']][$set['student_type']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					foreach($value3 as $key4 => $value4){
						$student_reports[$key][$key2][$key3][$key4] = count($student_data[$key][$key2][$key3][$key4]);
					}
				}
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Religion
	*/
	
	public function getStudentByReligion($organisation)
	{
		$student_data = array();
		$student_reports = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'religion_id'))
						->join(array('t2'=>'religion'),
							't1.religion_id = t2.id',array('religion'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_type'),
							't1.scholarship_type = t5.id',array('student_type'));
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));
		$select->order('t4.id ASC');
		$select->order('t2.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['religion']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$student_reports[$key][$key2][$key3] = count($student_data[$key][$key2][$key3]);
				}
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Gender
	*/
	
	public function getStudentByGender($organisation)
	{
		$student_data = array();
		$student_reports = array();
		
		$max_programme_duration = $this->getMaxProgrammeDuration();
		$programme_years = $this->getProgrammeYears($max_programme_duration);
                
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'enrollment_year'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_type'),
							't1.scholarship_type = t5.id',array('student_type'));
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));
		$select->order('t4.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				$student_reports[$key][$key2] = count($student_data[$key][$key2]);
			}
		}
		return $student_reports;
	}
	
	/*
	* Student By Blood Group
	*/
	
	public function getStudentByBloodGroup($organisation)
	{
		$student_data = array();
		$student_reports = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
						->columns(array('student_id', 'blood_group_id'))
						->join(array('t2'=>'blood_group'),
							't1.blood_group_id = t2.id',array('blood_group'))
						->join(array('t3'=>'gender'),
							't1.gender = t3.id',array('gender'))
						->join(array('t4'=>'organisation'),
							't1.organisation_id = t4.id',array('abbr'))
						->join(array('t5'=>'student_type'),
							't1.scholarship_type = t5.id',array('student_type'));
		$select->where(array('t1.student_status_type_id NOT IN (4,5,6,7,8)'));
		$select->order('t4.id ASC');
		$select->order('t2.id ASC');
		if($organisation != 0){
			$select->where(array('t1.organisation_id' =>$organisation));
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$student_data[$set['abbr']][$set['blood_group']][$set['gender']][$set['student_id']] = $set['student_id'];
		}
		foreach($student_data as $key => $value){
			foreach($value as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$student_reports[$key][$key2][$key3] = count($student_data[$key][$key2][$key3]);
				}
			}
		}
		return $student_reports;
	}
	
	/*
	* Function to get the total student report
	* Get the total no. of students by college
	* Need to get the number of active students in college
	*/
	
	public function getTotalStudent($year, $organisation)
	{
		$student_reports = array();
		$date = '2017';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id','gender','organisation_id', 'enrollment_year', 'scholarship_type'));
		//$select->where(array('t1.enrollment_year' => $year));
		$select->where(array('t1.student_status_type_id' => '1'));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_reports[$set['organisation_id']][$set['gender']][$set['id']] = $set['gender'];
		}
		
		return $student_reports;
	}
        
   /*
	* Get the type of report and the data for the report
    * 
    * Feedback of the students
	*/
	
	public function getStudentFeedbackReport($report_type, $organisation_id)
	{
		$student_feedback_reports = array();
	
		if($report_type == 'overall_feedback_ratings')
			$student_feedback_reports = $this->getOverAllStudentFeedback($organisation_id);
			
		else if($report_type == 'individual_feedback_ratings')
			$student_feedback_reports = $this->getIndividualStudentFeedback($organisation_id);
				
	
		return $student_feedback_reports;
	}
	
	/*
	 * OverAll Student Feeedback
	 * Summary of Ratings received
	 */
	
	public function getOverAllStudentFeedback($organisation_id)
	{
		$feedback_report = array();
		//preset the values
		$feedback_report[5][] = 0;
		$feedback_report[4][] = 0;
		$feedback_report[3][] = 0;
		$feedback_report[2][] = 0;
		$feedback_report[1][] = 0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_student'))
								->join(array('t2'=>'employee_details'),
										't1.employee_details_id = t2.id',array('organisation_id'));
		$select->where(array('t1.academic_year' => date('Y')));
		$select->where(array('t2.organisation_id' => $organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
					$feedback_report[$set['ratings']][$set['id']] = $set['ratings'];
		}
		return $feedback_report;
	}
	
	/*
	 * Individual Student Feedback
	 * Summary for each Question
	 */
	
	public function getIndividualStudentFeedback($organisation_id)
	{
		$feedback_report = array();
		//preset the values
		$feedback_report[5][] = 0;
		$feedback_report[4][] = 0;
		$feedback_report[3][] = 0;
		$feedback_report[2][] = 0;
		$feedback_report[1][] = 0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'feedback_ratings_student'))
								->join(array('t2'=>'employee_details'),
										't1.employee_details_id = t2.id',array('organisation_id'));
		$select->where(array('t1.academic_year' => date('Y')));
		$select->where(array('t2.organisation_id' => $organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
					$feedback_report[$set['employee_details_id']][$set['ratings']][$set['id']] = $set['ratings'];
		}
		
		return $feedback_report;
	}


	public function getResearchReport($report_details, $organisation_id)
	{   
		$research_reports = array();
		$report_name = $report_details['report_name'];
		//$report_type = $report_details['report_type'];
		$organisation = $report_details['organisation'];
		$from_date = $report_details['from_date']; 
		$to_date = $report_details['to_date'];  

		switch($report_name){
			case "university_research_grant":
				$research_reports = $this->getUniversityResearchGrantReport($organisation, $from_date, $to_date);
				break;

			case "college_research_grant":
				$research_reports = $this->getCollegeResearchGrantReport($organisation, $from_date, $to_date);
				break;

			case "university_publication":
				$research_reports = $this->getUniversityPublicationAnnouncementReport($organisation, $from_date, $to_date);
				break;

			case "college_publication":
				$research_reports = $this->getCollegePublicationAnnouncementReport($organisation, $from_date, $to_date);
				break;
			
		}

		return $research_reports;
	}


	public function getUniversityResearchGrantReport($organisation, $from_date, $to_date)
	{
		$f_date = date("Y-m-d", strtotime(substr($from_date,0,10)));
		$t_date =date("Y-m-d", strtotime(substr($to_date,0,10)));

		$research_reports = array();
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
        $select->from(array('t1' => 'research_grant_announcement'))
			   ->join(array('t2'=>'research_type'),
                    't1.research_grant_type = t2.id',array('grant_type', 'grant_category', 'organisation_id'))
                ->join(array('t3'=>'organisation'),
                    't2.organisation_id = t3.id',array('organisation_name'));
        $select->where(array('t2.grant_category' => 'University Grant', 't1.start_date >= ? ' => $f_date, 't1.end_date <= ? ' => $t_date));
        if($organisation != 0){
            $select->where(array('t3.id' =>$organisation));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        foreach($resultSet as $set){
            $research_reports[] = $set;
        }//var_dump($research_reports); die();

		return $research_reports;
	}



	public function getCollegeResearchGrantReport($organisation, $from_date, $to_date)
	{
		$f_date = date("Y-m-d", strtotime(substr($from_date,0,10)));
		$t_date =date("Y-m-d", strtotime(substr($to_date,0,10)));

		$research_reports = array();
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
        $select->from(array('t1' => 'research_grant_announcement'))
			   ->join(array('t2'=>'research_type'),
                    't1.research_grant_type = t2.id',array('grant_type', 'grant_category', 'organisation_id'))
                ->join(array('t3'=>'organisation'),
                    't2.organisation_id = t3.id',array('organisation_name'));
        $select->where(array('t2.grant_category' => 'College Grant', 't1.start_date >= ? ' => $f_date, 't1.end_date <= ? ' => $t_date));
        if($organisation != 0){
            $select->where(array('t3.id' =>$organisation));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        foreach($resultSet as $set){
            $research_reports[] = $set;
        }//var_dump($research_reports); die();

		return $research_reports;
	}



	public function getUniversityPublicationAnnouncementReport($organisation, $from_date, $to_date)
	{
		$f_date = date("Y-m-d", strtotime(substr($from_date,0,10)));
		$t_date =date("Y-m-d", strtotime(substr($to_date,0,10)));

		$research_reports = array();
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
        $select->from(array('t1' => 'research_publication_announcement'))
			   ->join(array('t2'=>'research_publication_types'),
                    't1.research_publication_type = t2.id',array('publication_name', 'publication_type', 'organisation_id'))
                ->join(array('t3'=>'organisation'),
                    't2.organisation_id = t3.id',array('organisation_name'));
        $select->where(array('t2.publication_type' => 'University Publication', 't1.start_date >= ? ' => $f_date, 't1.end_date <= ? ' => $t_date));
        if($organisation != 0){
            $select->where(array('t3.id' =>$organisation));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        foreach($resultSet as $set){
            $research_reports[] = $set;
        }//var_dump($research_reports); die();

		return $research_reports;
	}



	public function getCollegePublicationAnnouncementReport($organisation, $from_date, $to_date)
	{
		$f_date = date("Y-m-d", strtotime(substr($from_date,0,10)));
		$t_date =date("Y-m-d", strtotime(substr($to_date,0,10)));

		$research_reports = array();
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
        $select->from(array('t1' => 'research_publication_announcement'))
			   ->join(array('t2'=>'research_publication_types'),
                    't1.research_publication_type = t2.id',array('publication_name', 'publication_type', 'organisation_id'))
                ->join(array('t3'=>'organisation'),
                    't2.organisation_id = t3.id',array('organisation_name'));
        $select->where(array('t2.publication_type' => 'College Publication', 't1.start_date >= ? ' => $f_date, 't1.end_date <= ? ' => $t_date));
        if($organisation != 0){
            $select->where(array('t3.id' =>$organisation));
        }
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        foreach($resultSet as $set){
            $research_reports[] = $set;
        }//var_dump($research_reports); die();

		return $research_reports;
	}


	
	/*
	* Get the Five Year Plan
	* Returns the value of the years in array
	*/
	
	public function getFiveYearPlan($five_year_plan)
	{
		$five_year = array();
		$start_year = NULL;
		$end_year = NULL;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'five_year_plan'));
		//$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
                $select->where(array('t1.id' => $five_year_plan));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$start_year = $set['from_date'];
			$end_year = $set['to_date'];
		}
		for($i=0; $i<5; $i++){
			$five_year[] = substr($start_year, 0, 4)+$i;
		}

		return $five_year;
	}
	
	/*
	* Get the Years of Five Year Plan
	* Instead of returning the years, this function returns the start and end of the Five Year Plan
	*/
	
	public function FiveYearPlan($five_year_plan)
	{
		$five_year_start_end = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'five_year_plan'));
                $select->where(array('t1.id' => $five_year_plan));
		//$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$five_year_start_end['from_date'] = $set['from_date'];
			$five_year_start_end['to_date'] = $set['to_date'];
		}
		
		return $five_year_start_end;
	}
	
	/*
	 * Get the semester from the database
	 */
	
	public function getSemester($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'academic_calendar'))
					->columns(array('academic_year'))
				->join(array('t2' => 'academic_calendar_events'), 
						't1.academic_event = t2.id', array('academic_event'));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		$select->where('t2.organisation_id = ' .$organisation_id);
                
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$semester = NULL;
		
		foreach($result as $set){
			if($set['academic_event'] == 'Autumn Semester Duration'){
				$semester['academic_event'] = 'Autumn';
                $semester['academic_year'] = $set['academic_year'];
			}
			else if($set['academic_event'] == 'Spring Semester Duration'){
				$semester['academic_event'] = 'Spring';
                $semester['academic_year'] = $set['academic_year'];
			}
		}
		return $semester;
	}
        
	/*
	 * Get the academic year based on the semester from the database
	 */
	
	public function getAcademicYear($academic_event_details)
	{
		$semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
		//$academic_year = NULL;
		
		if($semester == 'Autumn'){
			$academic_year; // = (date('Y')).'-'.(date('Y')+1);
		} else {
			$academic_year; // = (date('Y')-1).'-'.date('Y');
		}
		
		return $academic_year;
	}
	
	/*
	* Get the max programme duration
	*/
	
	private function getMaxProgrammeDuration()
	{
		$max_years = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'programmes'));
		$select->columns(array(new Expression ('MAX(programme_duration) as max_duration')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$semesters = array();
		$index = 1;
		foreach ($resultSet as $res) {
			$tmp_number = $res['max_duration'];
			preg_match_all('!\d+!', $tmp_number, $matches);
			$max_years = implode(' ', $matches[0]);
		}
		
		return $max_years;
	}
	
	/*
	* Get Array of Academic Enrollment Years
	*/
	
	private function getProgrammeYears($max_programme_duration)
	{
		$academic_years = array();
		for($i=1; $i<= $max_programme_duration; $i++){
				$academic_years[$i] = date('Y')-$i;
		}
		return $academic_years;
	}
	
	
	/**
	* @return array/Reports()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'position_level'){
			$select->columns(array('id'));
			$select->columns(array(new Expression ('DISTINCT(position_level) as position_level')));
		}
		else if($tableName == 'organisation'){
			if($organisation_id == '1'){
				$select->columns(array('id', $columnName));
			}else{
				$select->columns(array('id', $columnName));
				$select->where(array('t1.id' => $organisation_id));
			}
		}else{
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
			if($tableName=='position_level'){
				$selectData[] = $set[$columnName];
			}
			else if($tableName == 'student_section'){
				$selectData['All'] = 'All';
				$selectData[$set['id']] = $set[$columnName];
			}	
			else {
					$selectData[$set['id']] = $set[$columnName];
			}
		}
		return $selectData;
			
	}
        
}
