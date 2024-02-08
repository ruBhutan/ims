<?php

namespace Reports\Mapper;

use Reports\Model\Awards;
use Reports\Model\PersonalDetails;
use Reports\Model\CommunityService;
use Reports\Model\Documents;
use Reports\Model\EducationDetails;
use Reports\Model\EmploymentDetails;
use Reports\Model\Reports;
use Reports\Model\LanguageSkills;
use Reports\Model\MembershipDetails;
use Reports\Model\PublicationDetails;
use Reports\Model\References;
use Reports\Model\TrainingDetails;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements ReportsMapperInterface
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
			Reports $jobPrototype
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
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getHrReport($report_type)
	{
		$hr_reports = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($report_type == 'rub_hr_report')
			$hr_reports = $this->getEmployeeQualification();
			
		else if($report_type == 'position_category_position_level')
			$hr_reports = $this->getEmployeePositionCategoryLevel();
		
		else if($report_type == 'agency_employment_type')
			$hr_reports = $this->getAgencyEmploymentType();
			
		else if($report_type == 'agency_category_level')
			$hr_reports = $this->getAgencyCategoryLevel();
				
		else if($report_type == 'occupational_group_gender')
			$hr_reports = $this->getOccupationalGroupGender();
		
		else if($report_type == 'position_level_gender')
			$hr_reports = $this->getPositionLevelGender();
		
		else if($report_type == 'recruitment_position_level')
			$hr_reports = $this->getRecruitmentPositionLevel();
		
		else if($report_type == 'recruitment_agencies')
			$hr_reports = $this->getRecruitmentAgencies();
		
		else if($report_type == 'promotions')
			$hr_reports = $this->getEmployeePromotions();
		
		//the promotion, recruitment and separation record in RUB
		else if($report_type == 'promotion_recruitment_separation')
			$hr_reports = $this->getPromotionRecruitmentSeparation();
		
		//five year training implemetation
		else if($report_type == 'five_year_implementation')
			$hr_reports = $this->getFiveYearTrainingImplementation();
		
		//training implementation by agencies for year
		else if($report_type == 'training_implementation')
			$hr_reports = $this->getAgencyTrainingImplementation();
		
		else if($report_type == 'training_implementation_category')
			$hr_reports = $this->getTrainingImplementationCategory();
		
		else if($report_type == 'training_implementation_country')
			$hr_reports = $this->getTrainingImplementationCountry();
		
		else if($report_type == 'training_implementation_funding')
			$hr_reports = $this->getTrainingImplementationFunding();

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
		} else {
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
	
	/*
	* Function to get the HR Report by Academic Qualification for each Agency
	*/
	
	public function getEmployeeQualification()
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
	
	public function getEmployeePositionCategoryLevel()
	{
		$hr_reports = array();
		$five_year_plan = $this->getFiveYearPlan();
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
	
	public function getAgencyEmploymentType()
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
	
	public function getAgencyCategoryLevel()
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
	
	public function getOccupationalGroupGender()
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
	
	public function getPositionLevelGender()
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
	
	public function getRecruitmentPositionLevel()
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan();
		$five_year_plan = $this->FiveYearPlan();
		
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
		$years = $this->getFiveYearPlan();
		$five_year_plan = $this->FiveYearPlan();
		
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
	* Get the promotion for RUB Employees
	*/
	
	public function getEmployeePromotions()
	{
		
	}
	
	/*
	* Get the Promotion, Recruitment and Separation Record in RUB
	*/
	
	public function getPromotionRecruitmentSeparation()
	{
		
	}
	
	/*
	* Get the training implementation for the Five Year Plan
	*/
	
	public function getFiveYearTrainingImplementation()
	{
		$hr_reports = array();
		$years = $this->getFiveYearPlan();
		$five_year_plan = $this->FiveYearPlan();
		$organisation_list = $this->listSelectData('organisation','organisation_name');
		
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
						$hr_reports[$key][$key2][$key3][$years[0]] += count($hr_reports[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[1])
						$hr_reports[$key][$key2][$key3][$years[1]] += count($hr_reports[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[2])
						$hr_reports[$key][$key2][$key3][$years[2]] += count($hr_reports[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[3])
						$hr_reports[$key][$key2][$key3][$years[3]] += count($hr_reports[$key][$key2][$key3][$value3['training_start_date']]);
					if(substr($value3, 0, 5) == $years[4])
						$hr_reports[$key][$key2][$key3][$years[4]] += count($hr_reports[$key][$key2][$key3][$value3['training_start_date']]);
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
	* Gett the training implementation by Category
	*/
	
	public function getAgencyTrainingImplementation()
	{
		$hr_reports = array();
		
		return $hr_reports;
	}
	
	/*
	* Gett the training implementation by Category
	*/
	
	public function getTrainingImplementationCategory()
	{
		
	}
	
	/*
	* Get the training implementation by Country
	*/
	
	public function getTrainingImplementationCountry()
	{
		
	}
	
	/*
	* Get the training implementation by Funding
	*/
	
	public function getTrainingImplementationFunding()
	{
		
	}
	
	/*
	* Get the type of report and the data for the report
	*/
	
	public function getStudentReport($report_type)
	{
		$student_reports = array();
		
		if($report_type == 'student_intake_report_scholarship')
			$student_reports = $this->getStudentByScholarship();
			
		else if($report_type == 'student_intake_report_college')
			$student_reports = $this->getStudentByCollege();
		
		else if($report_type == 'student_intake_report_year')
			$student_reports = $this->getStudentByYear();
			
		else if($report_type == 'total_student_report')
			$student_reports = $this->getTotalStudent();

		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by scholarship type
	*/
	
	public function getStudentByScholarship()
	{
		$student_reports = array();
		$date = '2017';
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id','gender','organisation_id', 'enrollment_year', 'scholarship_type'));
		$select->where(array('t1.enrollment_year' => $date));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_reports[$set['organisation_id']][$set['gender']][$set['scholarship_type']][$set['id']] = $set['gender'];
		}
		
		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by college for a given year
	*/
	
	public function getStudentByCollege()
	{
		$student_reports = array();
		$date = '2017';
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id','gender','organisation_id', 'enrollment_year', 'scholarship_type'));
		$select->where(array('t1.enrollment_year' => $date));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_reports[$set['organisation_id']][$set['gender']][$set['scholarship_type']][$set['id']] = $set['gender'];
		}
		
		return $student_reports;
	}
	
	/*
	* Function to get the student intake report by year
	*/
	
	public function getStudentByYear()
	{
		$student_reports = array();
		$date = '2012';		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id','gender','organisation_id', 'enrollment_year', 'scholarship_type'));
		$select->where(array('t1.enrollment_year >= ? ' => $date));
		//$select->where(array('t1.enrollment_year' => date('Y')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
			
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$student_reports[$set['organisation_id']][$set['enrollment_year']][$set['gender']][$set['id']] = $set['gender'];
		}
		
		return $student_reports;
	}
	
	/*
	* Function to get the total student report
	* Get the total no. of students by college
	* Need to get the number of active students in college
	*/
	
	public function getTotalStudent()
	{
		$student_reports = array();
		$date = '2017';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'student'))
				->columns(array('id','gender','organisation_id', 'enrollment_year', 'scholarship_type'));
		$select->where(array('t1.enrollment_year' => $date));
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
	
	/*
	* Get the Five Year Plan
	* Returns the value of the years in array
	*/
	
	public function getFiveYearPlan()
	{
		$five_year_plan = array();
		$start_year;
		$end_year;
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'five_year_plan'));
		$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$start_year = $set['from_date'];
			$end_year = $set['to_date'];
		}
		for($i=0; $i<5; $i++){
			$five_year_plan[] = substr($start_year, 0, 4)+$i;
		}

		return $five_year_plan;
	}
	
	/*
	* Get the Years of Five Year Plan
	* Instead of return the years, this function returns the start and end of the Five Year Plan
	*/
	
	public function FiveYearPlan()
	{
		$five_year_plan = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'five_year_plan'));
		$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$five_year_plan['from_date'] = $set['from_date'];
			$five_year_plan['to_date'] = $set['to_date'];
		}
		
		return $five_year_plan;
	}
	
	
	/**
	* @return array/Reports()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'position_level'){
			$select->columns(array('id'));
			$select->columns(array(new Expression ('DISTINCT(position_level) as position_level')));
		}
		else
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
			if($tableName=='position_level')
				$selectData[] = $set[$columnName];
			else
				$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}
        
}