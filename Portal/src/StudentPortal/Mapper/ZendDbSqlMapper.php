<?php

namespace StudentPortal\Mapper;

use StudentPortal\Model\StudentPortal;
use StudentPortal\Model\StudentDetail;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Group;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements StudentPortalMapperInterface
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
	 * @var \StudentPortal\Model\StudentPortalInterface
	*/
	protected $Prototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			StudentPortal $studentPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->studentPrototype = $studentPrototype;
	}
	
	
	/**
	* @return array/JobPortal()
	*/
	public function findAll($tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName)); // join expression

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

		if ($tableName == 'student'){
			$select->from(array('t1' => $tableName));
			$select->where(array('student_id' =>$username));
			$select->columns(array('id', 'student_id', 'programmes_id', 'organisation_id', 'first_name', 'middle_name', 'last_name', 'profile_picture'));
		}

		else if($tableName == 'employee_details'){
			$select->from(array('t1' => $tableName));
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id', 'organisation_id', 'first_name', 'middle_name', 'last_name', 'profile_picture'));
		}
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getStudentDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'programmes'),
					't2.id = t1.programmes_id', array('programme_name'))
			   ->join(array('t3' => 'organisation'),
					't3.id = t1.organisation_id', array('organisation_name'))
			   ->join(array('t4' => 'student_semester_registration'),
					't4.student_id = t1.id', array('semester_id', 'academic_year'))
			   ->join(array('t5' => 'student_semester'),
					't5.id = t4.semester_id', array('semester', 'programme_year_id'))
			   ->join(array('t6' => 'student_status_type'),
					't6.id = t1.student_status_type_id', array('reason'))
		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentPersonalDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'gender'),
					't2.id = t1.gender', array('stdgender' => 'gender'))
			   ->join(array('t3' => 'student_type'),
					't3.id = t1.scholarship_type', array('student_type'))

		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStudentCategoryDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_category_details'))
			   ->join(array('t2' => 'student_category'),
					't2.id = t1.student_category_id', array('student_category'))

		       ->where(array('t1.student_id = ?' => $student_id))
		       ->order('t1.date DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentNationality($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_nationality_details'))
			   ->join(array('t2' => 'nationality'),
					't2.id = t1.student_nationality_id', array('stdnationality' => 'nationality'))

		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentPermanentAddress($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
			   ->join(array('t2' => 'village'),
					't2.id = t1.village', array('stdvillage' => 'village_name'))
			    ->join(array('t3' => 'gewog'),
					't3.id = t1.gewog', array('stdgewog' => 'gewog_name'))
			     ->join(array('t4' => 'dzongkhag'),
					't4.id = t1.dzongkhag', array('stddzongkhag' => 'dzongkhag_name'))

		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentCountry($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_nationality_details'))
			   ->join(array('t2' => 'country'),
					't2.id = t1.student_country_id', array('stdcountry' => 'country'))

		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentContactDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
		       ->where(array('t1.id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStudentRelationDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_relation_details'))
				->join(array('t2' => 'relation_type'),
					't2.id = t1.relation_type', array('relation'))
				->join(array('t3' => 'nationality'),
					't3.id = t1.parent_nationality', array('nationality'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentGuardianDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_guardian_details'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentFatherDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_parents_details'))
			   ->join(array('t2' => 'nationality'),
					't2.id = father_nationality', array('fatherNationality' => 'nationality'))
			   ->join(array('t3' => 'village'),
					't3.id = t1.father_village', array('fatherVillage' => 'village_name'))
			   ->join(array('t4' => 'gewog'),
					't4.id = t1.father_gewog', array('fatherGewog' => 'gewog_name'))
			   ->join(array('t5' => 'dzongkhag'),
					't5.id = t1.father_dzongkhag', array('fatherDzongkhag' => 'dzongkhag_name'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentMotherDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_parents_details'))
			   ->join(array('t2' => 'nationality'),
					't2.id = mother_nationality', array('motherNationality' => 'nationality'))
			   ->join(array('t3' => 'village'),
					't3.id = t1.mother_village', array('motherVillage' => 'village_name'))
			   ->join(array('t4' => 'gewog'),
					't4.id = t1.mother_gewog', array('motherGewog' => 'gewog_name'))
			   ->join(array('t5' => 'dzongkhag'),
					't5.id = t1.mother_dzongkhag', array('motherDzongkhag' => 'dzongkhag_name'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getParentContactDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_parents_details'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentResponsibility($student_id, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_responsibilities'))
			   ->join(array('t2' => 'responsibility_category'),
					't2.id = t1.responsibility_category_id', array('responsibility_name'))
		       ->where(array('t1.student_id = ?' => $student_id, 't2.organisation_id = ?' => $organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentAchievement($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_achievements'))
			   ->join(array('t2' => 'student_achievements_category'),
					't2.id = t1.achievement_name', array('achievement_name'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentParticipation($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_participation'))
				->join(array('t2' => 'student_participation_category'),
						't2.id = t1.participation_type', array('participation_type'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStudentContribution($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_contributions'))
			   ->join(array('t2' => 'student_contributions_category'),
					't2.id = t1.contribution_type', array('contribution_type'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentPreviousSchoolDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_previous_school_details'))
			   ->join(array('t2' => 'school'),
			   		't2.id = t1.previous_institution', array('school_name'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentDisciplineRecords($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_disciplinary_record'))
			   ->join(array('t2' => 'discipline_category'),
					't2.id = t1.discipline_category_id', array('discipline_category'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentSemesterAcademicYear($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->join(array('t2' => 'student_semester'),
					't2.id = t1.semester_id', array('semester'))
			   ->join(array('t3' => 'student_section'),
					't3.id = t1.student_section_id', array('section'))
			   ->join(array('t4' => 'student'),
					't4.id = t1.student_id', array('programmes_id'))
			   ->join(array('t5' => 'programmes'),
					't5.id = t4.programmes_id', array('programme_name'))
		       ->where(array('t1.student_id = ?' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}


	public function getStudentAcademicModules($student_id, $programmes_id, $organisation_id)
	{ 
		$date = date('m');

		if($date >= 1 && $date <= 6){
			$start_year = date('Y')-1;
			$end_year = date('Y');
			$academic_year = $start_year.'-'.$end_year;
		}
		else{
			$start_year = date('Y');
			$end_year = date('Y')+1;
			$academic_year = $start_year.'-'.$end_year;
		}
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->join(array('t2' => 'academic_modules'),
			   		't2.id = t1.academic_modules_id', array('module_title', 'module_year', 'module_semester', 'module_code', 'programmes_id', 'module_code'))
			   ->join(array('t3' => 'programmes'),
					't3.id = t2.programmes_id', array('programme_name', 'organisation_id'))
			   ->join(array('t4' => 'student'),
					't4.programmes_id = t3.id', array('student_id'))
			   ->join(array('t5' => 'student_semester_registration'),
					't4.id = t5.student_id', array('student_section_id', 'academic_year', 'semester_id'))
		       ->where(array('t5.student_id = ?' => $student_id, 't2.programmes_id = ?' => $programmes_id, 't3.organisation_id = ?' => $organisation_id, 't5.semester_id = t1.semester', 't1.academic_year' => $academic_year, 't5.academic_year' => $academic_year));
		     // /  $select->where(array('t5.id' => 'MAX(`id`)'));
		//$select->order('t5.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	} 


	public function getStudentAcademicTimetable($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$academic_timetable = array();
		$year = NULl;
		$semester = NULL;
		$section = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'student_section_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$section = $set['student_section_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        } 

		$academic_timetable = $this->getAcademicTimetable($year, $semester, $section, $programme, $academicYear);
	
		return $academic_timetable;
	} 


	public function getAcademicTimetable($year, $semester, $section, $programme, $academicYear)
	{
		$sql = new Sql($this->dbAdapter); 
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't2.academic_modules_id = t3.id', array('module_title', 'module_code', 'programmes_id'))
                            ->join(array('t4' => 'programmes'), 
                                't3.programmes_id = t4.id', array('programme_name'))
                            ->join(array('t5' => 'academic_modules_allocation'),
                        		't5.id = t1.academic_modules_allocation_id', array('semester'))
                            ->join(array('t6' => 'academic_module_tutors'),
                        		't6.academic_modules_allocation_id = t5.id', array('year', 'module_tutor'))
                            ->join(array('t7' => 'student_section'),
                        		't7.id = t6.section', array('section'))
                            ->join(array('t8' => 'employee_details'),
                        		't8.emp_id = t6.module_tutor', array('first_name', 'middle_name', 'last_name'));

		$select->where(array('t5.semester' => $semester, 't1.group' => $section, 't1.academic_year' => $academicYear, 't1.programmes_id' => $programme, 't3.module_year' => $year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function getAcademicYear($organisation_id)
    {
    	$date = date('Y-m-d');

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'academic_calendar')) 
               ->columns(array('academic_year'))
               ->join(array('t2' => 'academic_calendar_events'),
                    't2.id = t1.academic_event', array('academic_event'));
        $select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date, 't2.organisation_id' => $organisation_id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
            
        //Need to make the resultSet as an array
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $academicYear = NULL;
        foreach($resultSet as $set)
        {
            $academicYear = $set['academic_year'];
        }
        return $academicYear;
    }


    /*
     * Get the semester from the database
     */
    
    public function getCurrentAcademicSession($organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'academic_calendar'))
                    ->columns(array('academic_year'))
                ->join(array('t2' => 'academic_calendar_events'), 
                        't1.academic_event = t2.id', array('academic_event', 'academic_session_id'));
        $select->where(array('from_date <= ? ' => date('Y-m-d')));
        $select->where(array('to_date >= ? ' => date('Y-m-d')));
        $select->where('t2.organisation_id = ' .$organisation_id);
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $academic_session = NULL;
        
        foreach($resultSet as $set){
            if($set['academic_event'] == 'Autumn Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
            else if($set['academic_event'] == 'Spring Semester Duration'){
                $academic_session = $set['academic_session_id'];
            }
        }
        return $academic_session;
    }


	public function getTimetableTiming($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable_timing'));
		$select->where(array('organisation_id' =>$organisation_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$timetable_timing = array();
		foreach($resultSet as $set){
			$timetable_timing[] = $set['from_time'].'-'.$set['to_time'];
		}

		return $timetable_timing;
	}


	public function getStdCurrentSemesterDetails($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$assessment_component = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $assessment_component = $this->getAssessmentComponentWeightage($year, $semester, $programme, $academicYear);

        return $assessment_component;
	}


	public function getAssessmentComponentWeightage($year, $semester, $programme, $academicYear)
	{ 
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;

	}


	public function getAcademicModuleTutor($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_timetable')) 
                            ->join(array('t2' => 'academic_modules'), 
                                't1.academic_modules_id = t2.id', array('module_code'))
                            ->join(array('t3' => 'student'), 
                                't2.programmes_id = t3.programmes_id', array('id'))
                            ->join(array('t4' => 'student_semester_registration'), 
                                't3.id = t4.student_id', array('student_section_id','semester_id'))
                            ->join(array('t5' => 'programmes'), 
                                't1.programmes_id = t5.id', array('programme_name'))
                            ->join(array('t6' => 'academic_modules_allocation'),
                        		't2.id = t6.academic_modules_id', array('academic_year', 'semester', 'year'))
                            ->join(array('t7' => 'academic_module_tutors'),
                        		't6.id = t7.academic_modules_allocation_id', array('module_coordinator', 'module_tutor'))
                            ->join(array('t8' => 'employee_details'),
                        		't8.emp_id = t7.module_coordinator', array('first_name', 'middle_name', 'last_name'));
		$select->where(array('t4.student_id' => $student_id, 't1.semester = t4.semester_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function getAcademicModuleLists($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$module_list = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $module_list = $this->getModuleLists($year, $semester, $programme, $academicYear);
       // /var_dump($module_list); die();
        return $module_list;
	}


	public function crossCheckCompiledCaDetails($academic_modules_id, $student_details_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);

		$student_id = NULL;
		$year = NULL;
		$semester = NULL;
		$section = NULL;
		$programme = NULL;
		$attendance_details = NULL;
		$academic_modules_allocation_id = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->join(array('t2' => 'student'),
			   		't2.id = t1.student_id', array('student_id', 'programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_details_id, 't1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
			$section = $set['student_section_id'];
			$student_id = $set['student_id'];
			$programme = $set['programmes_id'];
		} 

		$select1 = $sql->select();
		$select1->from(array('t1' => 'academic_modules_allocation'));
		$select1->where(array('t1.academic_modules_id' => $academic_modules_id, 't1.semester' => $semester, 't1.year' => $year, 't1.academic_year' => $academic_year));

		$stmt1 = $sql->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);

		foreach($resultSet1 as $set1){
			$academic_modules_allocation_id = $set1['id'];
		}

		$select2 = $sql->select();
		$select2->from(array('t1' => 'compiled_marks_status'));
		$select2->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id, 't1.section' => $section, 't1.type' => 'CA', 't1.status' => 'Compiled'));

		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);

		foreach($resultSet2 as $set2){
			$attendance_details = $set2['id'];
		}
		return $attendance_details;
	}


	public function getStudentModuleCaDetails($academic_modules_id, $student_details_id, $organisation_id)
	{ 
		$academic_year = $this->getAcademicYear($organisation_id);

		$student_id = NULL;
		$year = NULL;
		$semester = NULL;
		$section = NULL;
		$programme = NULL;
		//$attendance_details = array();
		$academic_modules_allocation_id = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->join(array('t2' => 'student'),
			   		't2.id = t1.student_id', array('student_id', 'programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_details_id, 't1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
			$section = $set['student_section_id'];
			$student_id = $set['student_id'];
			$programme = $set['programmes_id'];
		} 

		$select1 = $sql->select();
		$select1->from(array('t1' => 'academic_modules_allocation'));
		$select1->where(array('t1.academic_modules_id' => $academic_modules_id, 't1.semester' => $semester, 't1.year' => $year, 't1.academic_year' => $academic_year));

		$stmt1 = $sql->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);

		foreach($resultSet1 as $set1){
			$academic_modules_allocation_id = $set1['id'];
		}

		$select2 = $sql->select();
		$select2->from(array('t1' => 'assessment_marks'))
				->join(array('t2' => 'academic_assessment'),
						't2.id = t1.academic_assessment_id', array('assessment', 'date_submission', 'assessment_marks', 'assessment_weightage'))
				->join(array('t3' => 'assessment_component'),
						't3.id = t2.assessment_component_id', array('weightage', 'assessment_year'))
				->join(array('t4' => 'assessment_component_types'),
						't4.id = t3.assessment_component_types_id', array('assessment_component_type'))
				->join(array('t5' => 'academic_modules_allocation'),
						't5.id = t3.academic_modules_allocation_id', array('module_title', 'module_code'));
		$select2->where(array('t1.student_id' => $student_details_id, 't3.academic_modules_allocation_id' => $academic_modules_allocation_id, 't1.section' => $section, 't1.programmes_id' => $programme));
		$select2->where(array('t4.assessment_component_type like ? ' => 'Continuous Assessment%'));

		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		return $resultSet2->initialize($result2);
	}
	


	public function getStudentModuleAttendanceDetails($academic_modules_id, $std_id, $organisation_id)
	{ 
		$academic_year = $this->getAcademicYear($organisation_id);

		$student_id = NULL;
		$year = NULL;
		$semester = NULL;
		$section = NULL;
		$attendance_details = array();
		$academic_modules_allocation_id = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->join(array('t2' => 'student'),
			   		't2.id = t1.student_id', array('student_id'))
			    ->where(array('t1.student_id = ?' => $std_id, 't1.academic_year' => $academic_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
			$section = $set['student_section_id'];
			$student_id = $set['student_id'];
        }
		
		$select1 = $sql->select();
		$select1->from(array('t1' => 'academic_modules_allocation'));
		$select1->where(array('t1.academic_modules_id' => $academic_modules_id, 't1.semester' => $semester, 't1.year' => $year, 't1.academic_year' => $academic_year));

		$stmt1 = $sql->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
		$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);

		foreach($resultSet1 as $set1){
			$academic_modules_allocation_id = $set1['id'];
		}

		$select2 = $sql->select();
		$select2->from(array('t1' => 'student_absentee_record'))
				->join(array('t2' => 'student_attendance_dates'),
						't2.id = t1.student_attendance_dates_id', array('attendance_date', 'period'))
				->join(array('t3' => 'academic_modules_allocation'),
						't3.id = t2.academic_modules_allocation_id', array('module_title', 'module_code'));
		$select2->where(array('t1.student_id' => $student_id, 't2.academic_modules_allocation_id' => $academic_modules_allocation_id, 't2.section_id' => $section));

		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		return $resultSet2->initialize($result2);
	}


	public function getDeclaredResult($student_details_id, $student_id, $programmes_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);

		//$student_id = NULL;
		$current_year = NULL;
		$current_semester = NULL;
		$section = NULL;
		$semester_array = array();
		$results = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			    ->where(array('t1.student_id = ?' => $student_details_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
        	$current_year = $set['year_id'];
        	$current_semester = $set['semester_id'];
			$section = $set['student_section_id'];
		} 
		
		for($i=1; $i <= $current_semester; $i++){
			$select1 = $sql->select();

			$select1->from(array('t1' => 'student_consolidated_marks'))
					->where(array('t1.student_id' => $student_id, 't1.semester' => $i, 't1.result_status' => 'Moderated', 't1.programmes_id' => $programmes_id));

			$stmt1 = $sql->prepareStatementForSqlObject($select1);
			$result1 = $stmt1->execute();
			
			$resultSet1 = new ResultSet();
			$resultSet1->initialize($result1);

			foreach($resultSet1 as $set1){
				$results[$set1['semester']][$set1['module_code']][$set1['assessment_type']] = $set1['module_title'];
			}
		} var_dump($results); die();
		return $results;
	}


	public function getModuleLists($year, $semester, $programme, $academic_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'academic_modules_allocation'))
			   ->join(array('t2' => 'academic_modules'),
					't2.id = t1.academic_modules_id', array('module_title', 'module_code'));
		$select->where(array('t1.year' => $year, 't1.semester' => $semester, 't1.academic_year' => $academic_year, 't1.programmes_id' => $programme));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $modules = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$modules[$set['academic_modules_id']][$set['module_code']] = $set['module_title'];
        }

        return $modules;
	}


	public function getStdCurrentCADetails($student_id, $organisation_id)
	{
		$academic_year = $this->getAcademicYear($organisation_id);
		$academic_session = $this->getCurrentAcademicSession($organisation_id);
		$assessment_component = array();
		$year = NULl;
		$semester = NULL;
		$programme = NULL;
		$academicYear = NULL;

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_semester_registration'))
			   ->columns(array('student_id', 'semester_id', 'year_id', 'academic_session_id', 'academic_year'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('programmes_id'))
			    ->where(array('t1.student_id = ?' => $student_id, 't1.academic_year' => $academic_year, 't1.academic_session_id' => $academic_session));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$year = $set['year_id'];
        	$semester = $set['semester_id'];
        	$programme = $set['programmes_id'];
        	$academicYear = $set['academic_year'];
        }

        $ca_component = $this->getCAComponentWeightage($year, $semester, $programme, $academicYear);

        return $ca_component;
	}


	public function getCAComponentWeightage($year, $semester, $programme, $academicYear)
	{
		$assessment_component_weightage = array();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'assessment_component'))
			   ->join(array('t2' => 'academic_modules_allocation'),
					't2.id = t1.academic_modules_allocation_id', array('year', 'semester', 'academic_year', 'programmes_id'));
		$select->where(array('t2.year' => $year, 't2.semester' => $semester, 't2.academic_year' => $academicYear, 't2.programmes_id' => $programme));
		$select->where->notLike('t1.assessment','%Semester Exams%');

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $assessment_component = array();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);

        foreach($resultSet as $set){
        	$assessment_component[$set['id']] = $set['assessment'];
        }

        foreach($assessment_component as $key => $value){
        	$select2 = $sql->select();
			$select2->from(array('t1' => 'assessment_component'))
				    ->join(array('t2' => 'academic_modules_allocation'),
							't2.id = t1.academic_modules_allocation_id', array('academic_modules_id'));
            $select2->where(array('t1.id = ' .$key));

			$stmt2 = $sql->prepareStatementForSqlObject($select2);
			$result2 = $stmt2->execute();
			
			$resultSet2 = new ResultSet();
			$resultSet2->initialize($result2);
			foreach($resultSet2 as $set2){
				$assessment_component_weightage[$value][$set2['academic_modules_id']] = $set2['weightage'];
			}	
        }
       // var_dump($value);
        //var_dump($assessment_component_weightage); die();
        return $assessment_component_weightage;
	}


	public function getStudentRecheckMarkStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_recheck_marks')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't2.id = t1.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id', 'academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't3.id = t2.academic_modules_id', array('module_title', 'module_semester', 'module_code'));
		$select->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function getStudentReassessmentStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_reassessment_module')) 
                            ->join(array('t2' => 'academic_modules_allocation'), 
                                't2.id = t1.academic_modules_allocation_id', array('academic_year', 'semester', 'year', 'programmes_id', 'academic_modules_id'))
                            ->join(array('t3' => 'academic_modules'), 
                                't3.id = t2.academic_modules_id', array('module_title', 'module_semester', 'module_code'));
		$select->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentRepeatModuleStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_repeat_modules')) 
                            ->join(array('t2' => 'student_semester'),
                        		't2.id = t1.backlog_semester', array('semester'));
		$select->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentHostelRoomDetails($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_hostels')) 
                            ->join(array('t2' => 'hostel_rooms'), 
                                't2.id = t1.hostel_rooms_id', array('room_no', 'hostels_list_id'))
                            ->join(array('t3' => 'hostels_list'), 
                                't3.id = t2.hostels_list_id', array('hostel_name', 'hostel_type', 'hostel_category', 'hostel_room_no', 'hostel_floor_no', 'provost_name'));
		$select->where(array('t1.student_id' => $student_id));
		$select->order('t1.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentHostelRoomInventory($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_hostels')) 
                            ->join(array('t2' => 'hostel_rooms'), 
                                't2.id = t1.hostel_rooms_id', array('room_no', 'hostels_list_id'))
                            ->join(array('t3' => 'hostels_list'), 
                                't3.id = t2.hostels_list_id', array('hostel_name', 'hostel_type', 'hostel_category', 'hostel_room_no', 'hostel_floor_no', 'provost_name'))
                            ->join(array('t4' => 'student_hostel_inventory'),
                        		't3.id = t4.hostels_list_id', array('hostel_room_no', 'no_chairs', 'no_tables', 'no_beds', 'sockets', 'lights', 'hostels_list_id'));
		$select->where(array('t1.student_id' => $student_id, 't2.hostels_list_id = t4.hostels_list_id', 't2.room_no = t4.hostel_room_no'));
		$select->order('t1.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getHostelChangeApplicationStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_hostel_application')) 
                            ->join(array('t2' => 'hostels_list'), 
                                't2.id = t1.hostel_name', array('fhostel_name' => 'hostel_name'))
                            ->join(array('t3' => 'hostels_list'), 
                                't3.id = t1.hostel_to_name', array('thostel_name' => 'hostel_name'));
            
		$select->where(array('t1.student_id' => $student_id));
		$select->order('t1.id DESC')->limit(1);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}


	public function getStudentClubApplicationStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_club_applications')) 
                            ->join(array('t2' => 'clubs'), 
                                't2.id = t1.clubs_id', array('club_name', 'maximum_members', 'advisor_name', 'coordinator_name'));
            
		$select->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentClubApplicationDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_club_applications')) 
               ->join(array('t2' => 'clubs'), 
                	't2.id = t1.clubs_id', array('club_name', 'maximum_members', 'advisor_name', 'coordinator_name', 'description'));
            
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentClubList($status, $student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'clubs')) 
               ->join(array('t2' => 'student_clubs'), 
            		't1.id = t2.clubs_id', array('student_id', 'clubs_id', 'date'))
               ->join(array('t3' => 'student_club_applications'),
           			't1.id = t3.clubs_id', array('status', 'student_id', 'clubs_id'));
            
		$select->where(array('t2.student_id' => $student_id, 't3.status' => $status, 't2.student_id = t3.student_id', 't2.clubs_id = t3.clubs_id'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getMemberClubDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'clubs'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getClubMemberNos($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_clubs'))
			   ->columns(array('student_id'))
			   ->where(array('t1.clubs_id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentClubMemberList($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_clubs'))
			   ->join(array('t2' => 'student'),
					't2.id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id', 'gender', 'programmes_id'))
			   ->join(array('t3' => 'gender'),
					't3.id = t2.gender', array('stgender' => 'gender'))
			   ->join(array('t4' => 'programmes'),
					't4.id = t2.programmes_id', array('programme_name'))
			   ->where(array('t1.clubs_id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getStudentClubAttendanceList($attendanceYear, $id, $student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_club_attendance'))
			   ->join(array('t2' => 'student_clubs'),
					't2.id = t1.student_clubs_id', array('clubs_id', 'student_id'))
			   ->join(array('t3' => 'clubs'),
					't3.id = t2.clubs_id', array('club_name'))
			   ->where(array('t2.clubs_id' => $id, 't2.student_id' => $student_id, 't3.id = t2.clubs_id'));
	    $select->where->like('t1.date',$attendanceYear.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStdExtraCurricularAttendanceRecord($attendanceYear, $student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_extracurricular_attendance'))
			   ->join(array('t2' => 'social_events'),
					't2.id = t1.social_events_id', array('date', 'event', 'event_description'))
			   ->where(array('t1.student_id' => $student_id));
	    $select->where->like('t2.date',$attendanceYear.'%');

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getCounselingAppointmentStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_appointment'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getScheduledAppointment($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'scheduled_counseling_appointments'))
			   ->join(array('t2' => 'counseling_appointment'),
					't2.id = t1.counseling_appointment_id', array('counselor_type', 'subject', 'description', 'appointment_time', 'appointment_date', 'remarks', 'appointment_status'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}   

	public function getRecommendedCounseling($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_suggest'))
			   ->join(array('t2' => 'employee_details'),
					't2.id = t1.suggested_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getCounselingAppointmentDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'counseling_appointment'));
            
		$select->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->buffer();
		return $resultSet->initialize($result);
	}

	public function getCounselingScheduledDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'scheduled_counseling_appointments'))
			   ->join(array('t2' => 'counseling_appointment'),
					't2.id = t1.counseling_appointment_id', array('counselor_type', 'subject', 'description', 'appointment_time', 'appointment_date', 'remarks', 'appointment_status'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getDisciplinaryRecords($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_disciplinary_record'))
			   ->join(array('t2' => 'discipline_category'),
					't2.id = t1.discipline_category_id', array('discipline_category'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStdDisciplinaryRecordDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_disciplinary_record'))
			   ->join(array('t2' => 'discipline_category'),
					't2.id = t1.discipline_category_id', array('discipline_category', 'description'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	} 

	public function getStdMedicalRecordList($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_medical_records'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStdMedicalRecordDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_medical_records'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStudentLeaveStatus($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_leave'))
			   ->join(array('t2' => 'student_leave_category'),
					't2.id = t1.student_leave_category_id', array('leave_category', 'approval_by', 'remarks'))
			   ->where(array('t1.student_id' => $student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getStudentLeaveDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_leave'))
			   ->join(array('t2' => 'student_leave_category'),
					't2.id = t1.student_leave_category_id', array('leave_category', 'approval_by', 'lcat_remarks' => 'remarks'))
			   ->where(array('t1.id' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
	}

	public function getExamTimetable($programmes_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'examination_timetable')) 
				->join(array('t2' => 'examination_hall'), 
						't1.examination_hall_id = t2.id', array('hall_no', 'hall_name'))
				->join(array('t3'=>'programmes'),
						't1.programmes_id = t3.id', array('programme_name'))
				->join(array('t4'=>'academic_modules'),
						't1.academic_modules_id = t4.id', array('module_title','module_code'))
				->join(array('t5'=>'academic_modules_allocation'),
						't5.academic_modules_id = t4.id', array('year'))
				->where(array('t1.programmes_id' =>$programmes_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function getExamDates($organisation_id)
	{
		$present_month= date('m');
		$from_date = date('Y').'-'.($present_month-2).'-01';
		$to_date = date('Y').'-'.($present_month+2).'-01';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'examination_timetable'));
		$select->where(array('t1.exam_date >= ? ' => $from_date));
		$select->where(array('t1.exam_date <= ? ' => $to_date));
		$select->where(array('t1.organisation_id' =>$organisation_id));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$exam_dates['start_date'] = NULL;
		$exam_dates['end_date'] = NULl;
		foreach($resultSet as $set){
			if(strtotime($set['exam_date']) < strtotime($exam_dates['start_date']) || $exam_dates['start_date'] == NULL){
				$exam_dates['start_date'] = $set['exam_date'];
			}
			if(strtotime($set['exam_date']) > strtotime($exam_dates['end_date'])){
				$exam_dates['end_date'] = $set['exam_date'];
			}
		}
		return $exam_dates;
	}

	public function getNoEligibleModules($student_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student_examination_noneligibility')) 
				->join(array('t2' => 'academic_modules_allocation'), 
						't1.academic_modules_allocation_id = t2.id', array('academic_modules_id', 'academic_year', 'semester', 'year', 'programmes_id'))
				->join(array('t3'=>'academic_modules'),
						't2.academic_modules_id = t3.id', array('module_title', 'module_code', 'module_year', 'module_semester'))
				->where(array('t1.student_id' =>$student_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}  


	public function getStuddentProfilePicture($id)
	{
		$img_location = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'student'))
		       ->where(array('t1.id = ?' => $id));

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