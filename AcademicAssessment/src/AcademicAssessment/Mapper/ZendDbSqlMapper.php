<?php

namespace AcademicAssessment\Mapper;

use AcademicAssessment\Model\AcademicAssessment;

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
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AcademicAssessmentMapperInterface
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
	 * @var \AcademicAssessment\Model\AcademicAssessmentInterface
	*/
	protected $academicAssessmentPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			AcademicAssessment $academicAssessmentPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->academicAssessmentPrototype = $academicAssessmentPrototype;
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

    public function findAll($caution, $stdId, $stdSemester, $organisation_id, $username)
    { 
        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);

        $sql = new Sql($this->dbAdapter);

        if ($caution == 'repeatModuleDetail'){
            $select = $sql->select();
            $select->from(array('t1' => 'student_repeat_modules'))
                ->join(array('t2' => 'academic_modules_allocation'),
                    't1.academic_modules_id = t2.academic_modules_id')
                ->join(array('t3' => 'academic_module_tutors'),
                    't2.id = t3.academic_modules_allocation_id')
                ->where(array('t1.backlog_status' => 'Not Cleared'))
                ->where(array('t3.module_tutor' => $username))
                ->where(array('t3.year' => $current_academic_year))
                ->group(array('t1.module_code', 't1.module_title'));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $repeat_module_array = array();
            foreach($resultSet as $set){
                $repeat_module_array[$set['student_id']][$set['backlog_semester']][$set['module_code']] = $set['module_title'];
            }

            $repeat_module = array();

            foreach($repeat_module_array as $key => $value){
                foreach($value as $key2 => $value2){
                    foreach($value2 as $key3 => $value3){ 
                        $select1 = $sql->select();
                        $select1->from(array('t1' => 'student'))
                                ->join(array('t2' => 'student_consolidated_marks'),
                            't1.student_id = t2.student_id');
                        $select1->where(array('t2.student_id' => $key, 't2.semester' => $key2, 't2.module_code' => $key3));

                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();
                        
                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $repeat_module[] = $set1;
                        }
                    }
                }
            } 
            return $repeat_module;
        } 
        elseif($caution == 'reModuleDetail'){
            $select = $sql->select();
            $select->from(array('t1' => 'student_repeat_modules'))
                ->join(array('t2' => 'academic_modules_allocation'),
                    't1.academic_modules_id = t2.academic_modules_id')
                ->join(array('t3' => 'academic_module_tutors'),
                    't2.id = t3.academic_modules_allocation_id')
                ->where(array('t1.backlog_status' => 'Not Cleared'))
                ->where(array('t3.module_tutor' => $username))
                ->where(array('t3.year' => $current_academic_year))
                ->group(array('t1.module_code', 't1.module_title'));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $repeat_module_array = array();
            foreach($resultSet as $set){
                $repeat_module_array[$set['student_id']][$set['backlog_semester']][$set['module_code']] = $set['module_title'];
            }

            $repeat_module = array();

            foreach($repeat_module_array as $key => $value){
                foreach($value as $key2 => $value2){
                    foreach($value2 as $key3 => $value3){ 
                        $select1 = $sql->select();
                        $select1->from(array('t1' => 'student'))
                                ->join(array('t2' => 'student_consolidated_marks'),
                            't1.student_id = t2.student_id');
                        $select1->where(array('t2.student_id' => $key, 't2.semester' => $key2, 't2.module_code' => $key3));

                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();
                        
                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $repeat_module[] = $set1;
                        }
                    }
                }
            } 
            return $repeat_module;
        }
    }

    public function deleteCompileAssessment($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id)
    {
        $module_assessment_details = $this->getAllocatedModuleDetails($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $organisation_id, 'module_assessment');
        
        $assessment = NULL;
        foreach($module_assessment_details as $det){
            $assessment = $det['assessment'];
        }

        $assessment_type = NULL;
        if($assessment == 'Continuous Assessment'){
            $assessment_type = 'CA';
        } elseif($assessment == 'Semester Exams'){
            $assessment_type = 'SE';
        } elseif($assessment == 'Continuous Assessment (Practical)'){
            $assessment_type = 'CA (P)';
        } elseif($assessment =='Continuous Assessment (Theory)') {
            $assessment_type = 'CA (T)';
        } elseif($assessment =='Semester Exams (Theory)') {
            $assessment_type = 'SE (T)';
        } else {
            $assessment_type = 'SE (P)';
        }        


       $this->deleteCompiledAssessmentMarks($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);

        $compiled_marks_status_id = $this->getCompiledMarksStatusId($academic_modules_allocation_id, $assessment_type, $section);

        $action = new Delete('compiled_marks_status');
        $action->where(array('id = ?' => $compiled_marks_status_id));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
    }


    public function getCompiledMarksStatusId($academic_modules_allocation_id, $assessment_type, $section)
    {
        $sql = new Sql($this->dbAdapter);
		$select = $sql->select();

        if($assessment_type == 'CA (T)'){
            $assessment_type = 'CA (T)';
        } else if($assessment_type == 'CA (P)'){
            $assessment_type = 'CA (P)';
        } else if($assessment_type == 'SE (P)'){
            $assessment_type = 'SE (P)';
        } else if($assessment_type == 'SE (T)'){
            $assessment_type = 'SE (T)';
        } else if($assessment_type == 'SE'){
            $assessment_type = 'SE';
        } else if($assessment_type == 'CA'){
            $assessment_type = 'CA';
        }

        //var_dump($assessment_type); die();

		$select->from(array('t1' => 'compiled_marks_status'));
		$select->where(array('t1.academic_modules_allocation_id' => $academic_modules_allocation_id, 't1.type' => $assessment_type, 't1.section' => $section));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $compiled_marks_status_id = NULL;
        foreach($resultSet as $set){
            $compiled_marks_status_id = $set['id'];
        }
        //var_dump($assessment_type); die();
        return $compiled_marks_status_id;
    }


    public function deleteCompiledAssessmentMarks($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id)
    { 

        $compiled_assessment_marks_details = $this->getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id);

        if(!empty($compiled_assessment_marks_details)){
            foreach($compiled_assessment_marks_details as $value){  

				$action = new Delete('student_consolidated_marks');
				$action->where(array('id = ?' => $value['id']));

				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
				
			}	
        }else{
            return;
        }
    }


    public function getStudentCompiledMarksList($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $section, $organisation_id)
    {   

        $module_details = $this->getAllocatedModuleDetails($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $organisation_id, 'module_details');

        $module_code = NULL;
        $module_title = NULL;
        foreach($module_details as $details){
            $module_code = $details['module_code'];
            $module_title = $details['module_title'];
        } 

        $module_assessment_details = $this->getAllocatedModuleDetails($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $organisation_id, 'module_assessment');
        
        $assessment = NULL;
        foreach($module_assessment_details as $det){
            $assessment = $det['assessment'];
        }

        $assessment_type = NULL;
        if($assessment == 'Continuous Assessment'){
            $assessment_type = 'CA';
        } elseif($assessment == 'Semester Exams'){
            $assessment_type = 'SE';
        } elseif($assessment == 'Continuous Assessment (Practical)'){
            $assessment_type = 'CA (P)';
        } elseif($assessment =='Continuous Assessment (Theory)') {
            $assessment_type = 'CA (T)';
        } elseif($assessment =='Semester Exams (Theory)') {
            $assessment_type = 'SE (T)';
        } else {
            $assessment_type = 'SE (P)';
        }
        

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_consolidated_marks'))
               ->join(array('t2' => 'student'),
                    't2.student_id = t1.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
               ->join(array('t3' => 'student_semester_registration'),
                    't3.student_id = t2.id', array('student_section_id'))
               ->where(array('t1.programmes_id' => $programmes_id))
               ->where(array('t3.student_section_id' => $section))
               ->where(array('t1.assessment_type' => $assessment_type))
               ->where(array('t1.module_title' => $module_title))
               ->where(array('t1.module_code' => $module_code))
               ->where(array('t1.academic_year' => $academic_year))
               ->where(array('t1.level' => 'Regular')); 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }


    public function getAllocatedModuleDetails($programmes_id, $academic_modules_allocation_id, $assessment_component_id, $organisation_id, $type)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

        if($type == 'module_details'){
            $select->from(array('t1' => 'academic_modules_allocation'))
               ->where(array('t1.programmes_id' => $programmes_id, 't1.academic_session' => $semester, 't1.academic_year' => $academic_year, 't1.id' => $academic_modules_allocation_id));
        } 

        else if($type == 'module_assessment'){
            $select->from(array('t1' => 'assessment_component'))
               ->where(array('t1.id' => $assessment_component_id, 't1.academic_modules_allocation_id' => $academic_modules_allocation_id));
        } 

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
            
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

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
        // e.g. 1=> Category 1, 2 => Category etc.
            
        $selectData = array();
        foreach($resultSet as $set)
        {
            $selectData[$set['id']] = $set[$columnName];
        }
        return $selectData;
    }

    /**
    * @return array/Programme()
    * The following function is for listing data for select/dropdown form
    * For e.g. Need to fill the objectives field with Objectives from the database
    */
    public function listSelectData1($tableName, $columnName, $organisation_id, $username)
    {
        //need to get which part of the year so that we do not mix the enrollment years
        
        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester = $academic_event_details['academic_event'];
        $academic_year = $this->getAcademicYear($academic_event_details);

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($tableName == 'programmes' && $organisation_id != NULL)
        {
            //SELECT * FROM employee_details,academic_modules_allocation, academic_module_tutors WHERE academic_module_tutors.academic_modules_allocation_id = academic_modules_allocation.id AND academic_module_tutors.module_tutor = employee_details.emp_id AND employee_details.emp_id = '200801194'


            $select->from(array('t1' => 'programmes'));
            $select->columns(array('id',$columnName))
            ->join(array('t2' => 'academic_modules_allocation'),
                't1.id = t2.programmes_id', array('programmes_id'))
            ->join(array('t3' => 'academic_module_tutors'),
                't3.academic_modules_allocation_id = t2.id', array('module_tutor'))
            ->where(array('t1.organisation_id' => $organisation_id))
                    
            ->where(array('t3.module_tutor' => $username));
                    
                    
        }
        else if($tableName == 'programme_year' && $organisation_id == NULL)
        {
            $select->from(array('t1' => $tableName));
            $select->columns(array('id', $columnName));
        }
        else if($tableName == 'assessment_component' && $organisation_id != NULL)
        {
            $select->from(array('t1' => 'assessment_component')) ;
            $select->columns(array('id',$columnName))
                    ->join(array('t2' => 'academic_modules_allocation'), 
                            't1.academic_modules_allocation_id = t2.id', array('academic_modules_id'))
                    ->join(array('t3' => 'academic_modules'), 
                            't2.academic_modules_id = t3.id', array('programmes_id'))
                    ->join(array('t4' => 'programmes'), 
                            't3.programmes_id = t4.id', array('organisation_id'))
                    ->where('t4.organisation_id = ' .$organisation_id);
        }
        else if($tableName == 'academic_modules_allocation' && $organisation_id != NULL)
        {
            $select->from(array('t1' => 'academic_modules_allocation')) ;
            $select->columns(array('id'))
                    ->join(array('t2' => 'academic_modules'), 
                            't1.academic_modules_id = t2.id', array('module_title'))
                    ->join(array('t3' => 'programmes'), 
                            't2.programmes_id = t3.id', array('organisation_id'))
                    ->where(array('t1.academic_year' => $academic_year))
                    ->where('t3.organisation_id = ' .$organisation_id);
        }
        else if($tableName == 'academic_modules' && $organisation_id != NULL)
        {
            $select->from(array('t1' => $tableName)) 
                    ->columns(array('id', $columnName))
                    ->join(array('t2' => 'programmes'), 
                            't1.programmes_id = t2.id', array('organisation_id'))
                    ->where('t2.organisation_id = ' .$organisation_id);
        }
        else if($tableName == 'employee_details' && $organisation_id != NULL)
        {
            //here we execute the mysql statement and return it
            // as first name, middle name, last name is needed
            //need to also join with employee title such as professor etc.
            // (this will be done once all employees are assigned their titles)
            $select->from(array('t1' => 'employee_details')) ;
            $select->columns(array('id','first_name', 'middle_name','last_name', 'emp_id'))
                    ->where('t1.organisation_id = ' .$organisation_id);
            $select->order(array('t1.first_name ASC'));
                    
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            
            $selectData = array();
            foreach($resultSet as $set)
            {
                $selectData[$set['id']] = $set['first_name']. ' ' .$set['middle_name']. ' ' .$set['last_name'].' ('.$set['emp_id'].')';
            }
            return $selectData;
        }
         else if($tableName =='student_section'){
            $select->from(array('t1' => $tableName))
                                ->columns(array('id', $columnName));
        } else if($tableName =='academic_session'){
            $select->from(array('t1' => $tableName))
                                ->columns(array('id', $columnName));
        } else {
                $select->from(array('t1' => $tableName))
                            ->columns(array('id', $columnName))
                                    ->where('t1.organisation_id = ' .$organisation_id);
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

    /*
    * Get the list of semesters given an organisation id
    */
    
    public function getSemesterList($organisation_id)
    {

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'programmes'));
        $select->columns(array(new Expression ('MAX(programme_duration) as max_duration')));
        $select->where('t1.organisation_id = ' .$organisation_id);
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

        for($i=1; $i<=($max_years*2); $i++){
                $semesters[$i] = $i ." Semester ";
        }
        return $semesters;
    }

    /*
     * Get the Consolidated Marks for All Students By Programme
     */
    
    public function getStudentConsolidatedMarks($programme, $academic_year, $semester, $username)
    { 
        $organisation_id = $this->getOrganisationIdByProgramme($programme);
        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);

        $module_list = array();
        $assessment_marks = array();
        
        $sql = new Sql($this->dbAdapter);
        $select1 = $sql->select();
        //echo "$semester"; die();

        $select1->from(array('t1' => 'academic_module_tutors'))
                ->join(array('t2' => 'academic_modules_allocation'), 
                    't1.academic_modules_allocation_id = t2.id', array('module_code'))
                ->where(array('t2.academic_year' => $academic_year))
                ->where(array('t2.programmes_id' => $programme))
                ->where(array('t2.semester' => $semester))
                ->where(array('t1.module_tutor' => $username));
        
    
        $stmt1 = $sql->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);

        foreach($resultSet1 as $set1){
            $module_list[] = $set1['module_code'];
        }
        //var_dump($module_list); die();
        

        foreach($module_list as $key => $value){
            $select = $sql->select();
            $select->from(array('t1' => 'student'))
                            ->columns(array('id','student_id','first_name','middle_name','last_name', 'programmes_id'))
                            ->join(array('t2' => 'student_consolidated_marks'), 
                                't1.student_id = t2.student_id', array('assessment_type', 'marks', 'module_code', 'weightage'))
                            ->where(array('t2.academic_year' => $academic_year))
                            ->where(array('t1.programmes_id' => $programme))
                            ->where(array('t2.semester' => $semester))
                            ->where(array('t2.module_code' => $value));
        
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
                    
            foreach($resultSet as $set){
                $assessment_marks[$set['id']][$set['module_code']][$set['assessment_type']][$set['weightage']] = $set['marks'];
            }
        }
         //var_dump($assessment_marks); die();
        return $assessment_marks;
    }

    public function getModuleCreditList($programme, $academic_year, $semester, $username)
    {
        $module_credit_list = array();

        $sql = new Sql($this->dbAdapter);
        $select1 = $sql->select();

        $select1->from(array('t1' => 'academic_module_tutors'))
                ->join(array('t2' => 'academic_modules_allocation'), 
                    't1.academic_modules_allocation_id = t2.id', array('module_code'))
                ->join(array('t3' => 'academic_modules'),
                    't3.id = t2.academic_modules_id', array('module_credit'))
                ->where(array('t2.academic_year' => $academic_year))
                ->where(array('t2.programmes_id' => $programme))
                ->where(array('t2.semester' => $semester))
                ->where(array('t1.module_tutor' => $username));
        
    
        $stmt1 = $sql->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);

        foreach($resultSet1 as $set){
			$module_credit_list[$set['module_code']][$set['module_credit']] = $set['module_credit'];
		} 
		return $module_credit_list;
    }


    public function getStudentLists($caution, $stdId, $stdSemester, $organisation_id, $username, $userrole)
    {
	echo "Student:".$stdSemester;    
        $academic_event_details = $this->getSemester($organisation_id);
        $specrole = '_EXAM_STUDENT_RECORD_SERVICE';
        $specrol = '_ACADEMIC_STAFF';

        if(!empty($academic_event_details)){
            $semester_type = $academic_event_details['academic_event'];
            $current_academic_year = $this->getAcademicYear($academic_event_details);
        }else{
            $semester_type = 'NULL';
            $current_academic_year = 'NULL';
        } 

        $sql = new Sql($this->dbAdapter);

        if ($caution == 'studentDetail') {
            if (preg_match('/'.$specrole.'/', $userrole)){
                $select = $sql->select();
                $select->from(array('t1' => 'student'))
                    ->join(array('t2' => 'student_repeat_modules'),
                        't1.student_id = t2.student_id')
                    ->join(array('t3' => 'academic_modules_allocation'),
                        't2.academic_modules_id = t3.academic_modules_id')
                    ->join(array('t4' => 'academic_module_tutors'),
                        't3.id = t4.academic_modules_allocation_id')
                    //->where(array('t2.backlog_status' => 'Not Cleared'))
                    //->where(array('t4.year' => $current_academic_year))
                    ->where(array('t1.organisation_id' => $organisation_id, 't2.student_id' => $stdId, 't2.backlog_semester' => $stdSemester))
                    ->limit('1');
            } else {
                $select = $sql->select();
                $select->from(array('t1' => 'student'))
                    ->join(array('t2' => 'student_repeat_modules'),
                        't1.student_id = t2.student_id')
                    ->join(array('t3' => 'academic_modules_allocation'),
                        't2.academic_modules_id = t3.academic_modules_id')
                    ->join(array('t4' => 'academic_module_tutors'),
                        't3.id = t4.academic_modules_allocation_id')
                    //->where(array('t2.backlog_status' => 'Not Cleared'))
                    ->where(array('t4.module_tutor' => $username))
                    //->where(array('t4.year' => $current_academic_year))
                    ->where(array('t1.organisation_id' => $organisation_id, 't2.student_id' => $stdId, 't2.backlog_semester' => $stdSemester))
                    ->limit('1');    
            }
            
                
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            return $resultSet->initialize($result);

        } 
        
        else if ($caution == 'repeatModuleDetail'){
            if (preg_match('/'.$specrole.'/', $userrole)){
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));
            } else {
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    ->where(array('t3.module_tutor' => $username))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));    
            }
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $repeat_module_array = array();
            foreach($resultSet as $set){
                $repeat_module_array[$set['student_id']][$set['backlog_semester']][$set['module_code']] = $set['module_title'];
            }

            $repeat_module = array();

            foreach($repeat_module_array as $key => $value){
                foreach($value as $key2 => $value2){
                    foreach($value2 as $key3 => $value3){ 
                        $select1 = $sql->select();
                        $select1->from(array('t1' => 'student_consolidated_marks'));
                        $select1->where(array('t1.student_id' => $key, 't1.semester' => $key2, 't1.module_code' => $key3));
                        $select1->where(array('t1.result_status' => 'Declared'));
                        $select1->where(array('t1.level' => 'Re-assessment'));

                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();
                        
                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $repeat_module[] = $set1;
                        }
                    }
                }
            } 
            return $repeat_module;

        } else if ($caution == 'reModuleDetail'){
            if (preg_match('/'.$specrole.'/', $userrole)){
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    //->where(array('t1.backlog_status' => 'Attemped'))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));
            } else {
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    //->where(array('t1.backlog_status' => 'Attemped'))
                    ->where(array('t3.module_tutor' => $username))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));    
            }
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $repeat_module_array = array();
            foreach($resultSet as $set){
                $repeat_module_array[$set['student_id']][$set['backlog_semester']][$set['module_code']] = $set['module_title'];
            }

            $repeat_module = array();

            foreach($repeat_module_array as $key => $value){
                foreach($value as $key2 => $value2){
                    foreach($value2 as $key3 => $value3){ 
                        $select1 = $sql->select();
                        $select1->from(array('t1' => 'student_consolidated_marks'))
                                ->join(array('t2' => 'student'),
                            't1.student_id = t2.student_id', array('student_id'));
                        $select1->where(array('t1.student_id' => $key, 't1.semester' => $key2, 't1.module_code' => $key3));
                        $select1->where(array('t1.result_status' => 'Moderated'));

                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();
                        
                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $repeat_module[] = $set1;
                        }
                    }
                }
            } 
            return $repeat_module;
        } else if ($caution == 'reAssessmentDetail'){
            if (preg_match('/'.$specrole.'/', $userrole)){
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                $select->where(array('t1.backlog_status' => 'Not Cleared'));
            } else {
                $select = $sql->select();
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    ->where(array('t3.module_tutor' => $username))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester))
                    ->group(array('t1.module_code', 't1.module_title'));
                $select->where(array('t1.backlog_status' => 'Not Cleared'));    
            }
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $repeat_module_array = array();
            foreach($resultSet as $set){
                $repeat_module_array[$set['student_id']][$set['backlog_semester']][$set['module_code']] = $set['module_title'];
            }

            $repeat_module = array();

            foreach($repeat_module_array as $key => $value){
                foreach($value as $key2 => $value2){
                    foreach($value2 as $key3 => $value3){ 
                        $select1 = $sql->select();
                        $select1->from(array('t1' => 'student_consolidated_marks'))
                                ->join(array('t2' => 'student'),
                                    't1.student_id = t2.student_id', array('student_id'));
                        $select1->where(array('t1.student_id' => $key, 't1.semester' => $key2, 't1.module_code' => $key3));
                        $select1->where(array('t1.result_status' => 'Declared'));
                        $select1->where(array('t1.level' => 'Regular'));
                        //$select->group(array('t3.academic_modules_allocation_id'));

                        $stmt1 = $sql->prepareStatementForSqlObject($select1);
                        $result1 = $stmt1->execute();
                        
                        $resultSet1 = new ResultSet();
                        $resultSet1->initialize($result1);
                        foreach($resultSet1 as $set1){
                            $repeat_module[] = $set1;
                        }
                    } 
                    
                }
            }  

            /*$repeat_module2 = array();

            $student_id = $this->getStudentId($stdId); 

            $select3 = $sql->select();
            $select3->from(array('t1' => 'student_reassessment_module'))
                    ->join(array('t2' => 'student'),
                        't1.student_id = t2.id')
                    ->join(array('t3' => 'student_consolidated_marks'),
                        't3.academic_modules_allocation_id = t1.academic_modules_allocation_id');
            $select3->where(array('t1.student_id' => $student_id, 't3.semester' => $stdSemester));
            $select3->where(array('t3.result_status' => 'Declared'));
            $select3->where(array('t3.level' => 'Regular'));
            //$select->group(array('t3.academic_modules_allocation_id'));

            $stmt3 = $sql->prepareStatementForSqlObject($select3);
            $result3 = $stmt3->execute();
            
            $resultSet3 = new ResultSet();
            $resultSet3->initialize($result3);
            foreach($resultSet3 as $set3){
                $repeat_module2[] = $set3;
            } //var_dump($repeat_module2);die();

            if($repeat_module2['academic_modules_allocation_id'] == $repeat_module['academic_modules_allocation_id']){
                return $repeat_module;
            } */
            return $repeat_module;

        } else if($caution == 'repeatModuleAssessment'){
            $select = $sql->select();
            if (preg_match('/'.$specrole.'/', $userrole)){
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester));
                   // ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));
            } else {
                $select->from(array('t1' => 'student_repeat_modules'))
                    ->join(array('t2' => 'academic_modules_allocation'),
                        't1.academic_modules_id = t2.academic_modules_id')
                    ->join(array('t3' => 'academic_module_tutors'),
                        't2.id = t3.academic_modules_allocation_id')
                    ->where(array('t1.backlog_status' => 'Not Cleared'))
                    ->where(array('t3.module_tutor' => $username))
                    //->where(array('t3.year' => $current_academic_year))
                    ->where(array('t1.student_id' => $stdId, 't1.backlog_semester' => $stdSemester));
                   // ->group(array('t1.module_code', 't1.module_title'));
                //$select->where(array('t1.backlog_status' => 'Not Cleared'));
            }
            
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $module_assessment = array();
            foreach($resultSet as $set){
                $module_assessment[] = $set;
            }

            return $module_assessment;
        }
    } 


    public function getStudentId($stdId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student'));
        $select->where(array('student_id' =>$stdId));
        $select->columns(array('id'));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        foreach($resultSet as $set){
            return $set['id'];
        }
    }

    /*
    * Get organisation id based on the programme_id
    */
    
    private function getOrganisationIdByProgramme($programmes_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'programmes'));
        $select->where(array('id' =>$programmes_id));
        $select->columns(array('organisation_id'));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        foreach($resultSet as $set){
            return $set['organisation_id'];
        }
    }



    public function getConsolidatedMarksDetailsById($student_consolidated_marks_id)
    {

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'student_consolidated_marks'));
        $select->where(array('t1.id' => $student_consolidated_marks_id));
                
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        $mark_details = NULL;
        
        foreach($result as $set){
            $mark_details['assessment_type'] = $set['assessment_type'];
            $mark_details['marks'] = $set['marks'];
            $mark_details['programme_name'] = $set['programme_name'];
            $mark_details['academic_modules_allocation_id'] = $set['academic_modules_allocation_id'];
            $mark_details['module_title'] = $set['module_title'];
            $mark_details['semester'] = $set['semester'];
            $mark_details['module_code'] = $set['module_code'];
            $mark_details['credit'] = $set['credit'];
            $mark_details['weightage'] = $set['weightage'];
            $mark_details['programmes_id'] = $set['programmes_id'];
            $mark_details['level'] = $set['level'];
            $mark_details['student_id'] = $set['student_id'];
            $mark_details['temp_student_id'] = $set['temp_student_id'];
            $mark_details['academic_year'] = $set['academic_year'];
        }
        //var_dump($semester); die();
        return $mark_details;
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
        //var_dump($semester); die();
        return $semester;
    }
        
    /*
     * Get the academic year based on the semester from the database
     */
    
    public function getAcademicYear($academic_event_details)
    {
        //$academic_event_details = $this->getSemester();

        $semester = $academic_event_details['academic_event'];
        $academic_year = $academic_event_details['academic_year'];
        //$academic_year = NULL;
        
        if($semester == 'Autumn'){
            $academic_year; 
        } else {
            $academic_year;
        }
        
        return $academic_year;
    }


    public function getBasicStudentNameList($programme, $academic_year, $semester)
    {
        $organisation_id = $this->getOrganisationIdByProgramme($programme);
        

        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);

        $student_list = array();
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student'))
                        ->columns(array('id','student_id','first_name','middle_name','last_name'))
                    ->join(array('t2' => 'student_consolidated_marks'), 
                        't1.student_id = t2.student_id', array('module_code'))
                    ->join(array('t3' => 'student_semester_registration'), 
                                    't1.id = t3.student_id', array('student_section_id'))
                    ->join(array('t4' => 'student_section'), 
                        't3.student_section_id = t4.id', array('section'))
                    ->where(array('t2.academic_year ' => $academic_year))
                    ->where(array('t1.programmes_id = ' .$programme))
                    ->where(array('t2.semester' =>$semester));
        
    
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
                
        foreach($resultSet as $set){
            $student_list[$set['id']]['name'] = $set['first_name'].' '.$set['middle_name'].' '.$set['last_name'].' ('.$set['student_id'].')';
            $student_list[$set['id']]['section'] = $set['section'];
        }
        return $student_list;
    }

    public function listMarksDatail($stdId, $stdSemester, $organisation_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'student_consolidated_marks'))
               ->join(array('t2' => 'student'), 
                   't1.student_id = t2.student_id', array('first_name', 'middle_name', 'last_name', 'student_id'))
                ->where(array('t1.student_id' => $stdId))
                ->where(array('t1.semester' => $stdSemester))
                ->where(array('t1.result_status' => 'Declared'))
                ->where(array('t2.organisation_id' => $organisation_id));


        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);   
    }


    public function inserRepeatConsolidatedMark($data, $organisation_id, $id)
    {
        $markData['marks'] = $data['marks'];
        $markData['programme_name'] = $data['programme_name'];
        $markData['academic_modules_allocation_id'] = $data['academic_modules_allocation_id'];
        $markData['module_title'] = $data['module_title'];
        $markData['semester'] = $data['backlog_semester'];
        $markData['module_code'] = $data['module_code'];
        $markData['credit'] = $data['module_credit'];
        $markData['weightage'] = $data['weightage'];
        $markData['programmes_id'] = $data['programmes_id'];
        $markData['academic_year'] = $data['backlog_academic_year'];
        $markData['level'] = $data['examination_type'];
        $markData['student_id'] = $data['student_id'];
        $markData['temp_student_id'] = $data['student_id'];
        $markData['pass_year'] = date('Y');

        //$result_status = 'Cancelled';
        $status = 'Cancelled';    

        $action = new Update('student_consolidated_marks');
        $action->set(array('status' => $status))
              ->where(array('id' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        $this->insertStudentConsolidatedMarks($data, $organisation_id);
        
        //Check for record for repeat module in student consolidated marks table to update student repeat modules table
        $crosscheck_consolidated = $this->crossCheckConsolidatedMarksRecord($data);

        if(empty($crosscheck_consolidated)){
            $this->updateStudentRepeatModules($data);
            $this->updateStdConsolidatedResultStatus($data);
        } 

        return;
    }

    public function updateReConsolidatedMark($data, $organisation_id, $id)
    {
        $markData['marks'] = $data['marks'];
        $markData['programme_name'] = $data['programme_name'];
        $markData['academic_modules_allocation_id'] = $data['academic_modules_allocation_id'];
        $markData['module_title'] = $data['module_title'];
        $markData['semester'] = $data['backlog_semester'];
        $markData['module_code'] = $data['module_code'];
        $markData['credit'] = $data['module_credit'];
        $markData['weightage'] = $data['weightage'];
        $markData['programmes_id'] = $data['programmes_id'];
        $markData['academic_year'] = $data['backlog_academic_year'];
        $markData['level'] = $data['examination_type'];
        $markData['student_id'] = $data['student_id'];
        $markData['temp_student_id'] = $data['student_id'];
        $markData['pass_year'] = date('Y');

        if(((number_format((float)$data['marks'],2,'.','')/number_format((float)$data['weightage'],1,'.',''))*100)<'40'){            
            $markData['status'] = 'Re-assessment';
        }
        else {
            $markData['status'] = 'Pass';
        }

        $action = new Update('student_consolidated_marks');
        $action->set(array('marks' => $markData['marks'],'status' => $markData['status']))
              ->where(array('id' => $id));
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        
        //Check for record for repeat module in student consolidated marks table to update student repeat modules table
        $crosscheck_consolidated = $this->crossCheckConsolidatedMarksRecord($data);

        if(empty($crosscheck_consolidated)){
            $this->updateStudentRepeatModules($data);
            $this->updateStdConsolidatedResultStatus($data);
        } 

        return;
    }

    public function insertStudentConsolidatedMarks($data, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        
        $semester_type = $academic_event_details['academic_event'];
        $current_academic_year = $this->getAcademicYear($academic_event_details);

        $markData['assessment_type'] = $data['assessment'];
        $markData['marks'] = $data['marks'];
        $markData['programme_name'] = $data['programme_name'];
        $markData['academic_modules_allocation_id'] = $data['academic_modules_allocation_id'];
        $markData['module_title'] = $data['module_title'];
        $markData['semester'] = $data['backlog_semester'];
        $markData['module_code'] = $data['module_code'];
        $markData['credit'] = $data['module_credit'];
        $markData['weightage'] = $data['weightage'];
        $markData['programmes_id'] = $data['programmes_id'];
        $markData['academic_year'] = $data['backlog_academic_year'];
	//$markData['academic_year'] = $current_academic_year;
        $markData['level'] = 'Repeat Module';
        $markData['student_id'] = $data['student_id'];
        $markData['temp_student_id'] = $data['student_id'];
        $markData['pass_year'] = date('Y');
        $markData['result_status'] = 'Moderated';
        if(((number_format((float)$data['marks'],2,'.','')/number_format((float)$data['weightage'],1,'.',''))*100)<'40'){            
            $markData['status'] = 'Repeat';
        }
        else {
            $markData['status'] = 'Pass';
        }
        $action = new Insert('student_consolidated_marks');
        $action->values($markData);
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return;
    }

    public function insertReAssessmentMark($data, $moduleData, $organisation_id, $username, $userrole)
    { 
        //get the student list
        $i=1;
        $consolidatedMarksId = array();
    
        $stdConsolidatedMarksData = $this->getStudentLists('reAssessmentDetail', $moduleData['student_id'], $moduleData['backlog_semester'], $organisation_id, $username, $userrole);

        foreach($stdConsolidatedMarksData as $value)
        {
            $consolidatedMarksId[$i++] = $value['id'];
        }

        if($data != NULL)
        {
            $i =1;
            // Its an update
            foreach ($data as $value) {
                $result_status = 'Cancelled';
                $status = 'Cancelled';    
               
                $action = new Update('student_consolidated_marks');
                $action->set(array('status' => $status,'result_status' => $result_status))
                       ->where(array('id' => $consolidatedMarksId[$i]));

                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                $this->insertReassessmentConsolidatedMarks($consolidatedMarksId[$i], $value, $organisation_id);
                //$this->updateStdConsolidatedResultStatus($data);

                $i++;
            }
        }
    }



    public function insertReassessmentConsolidatedMarks($student_consolidated_marks_id, $value, $organisation_id)
    {
        $academic_event_details = $this->getSemester($organisation_id);
        
        if($academic_event_details){
            $semester_type = $academic_event_details['academic_event'];
            $current_academic_year = $this->getAcademicYear($academic_event_details);
        }else{
            $semester_type = NULL;
            $current_academic_year = NULL;
        }

        $consolidated_marks_data = $this->getConsolidatedMarksDetailsById($student_consolidated_marks_id);

        $markData['assessment_type'] = $consolidated_marks_data['assessment_type'];
        $markData['marks'] = $value;
        $markData['programme_name'] = $consolidated_marks_data['programme_name'];
        $markData['academic_modules_allocation_id'] = $consolidated_marks_data['academic_modules_allocation_id'];
        $markData['module_title'] = $consolidated_marks_data['module_title'];
        $markData['semester'] = $consolidated_marks_data['semester'];
        $markData['module_code'] = $consolidated_marks_data['module_code'];
        $markData['credit'] = $consolidated_marks_data['credit'];
        $markData['weightage'] = $consolidated_marks_data['weightage'];
        $markData['programmes_id'] = $consolidated_marks_data['programmes_id'];
        $markData['academic_year'] = $consolidated_marks_data['academic_year'];
        $markData['level'] = 'Re-assessment';
        $markData['student_id'] = $consolidated_marks_data['student_id'];
        $markData['temp_student_id'] = $consolidated_marks_data['temp_student_id'];
        $markData['pass_year'] = date('Y');
        $markData['result_status'] = 'Moderated';

        $repeatMarkData['module_title'] = $consolidated_marks_data['module_title'];
        $repeatMarkData['backlog_semester'] = $consolidated_marks_data['semester'];
        $repeatMarkData['module_code'] = $consolidated_marks_data['module_code'];
        $repeatMarkData['programmes_id'] = $consolidated_marks_data['programmes_id'];
        $repeatMarkData['backlog_academic_year'] = $consolidated_marks_data['academic_year'];
        $repeatMarkData['student_id'] = $consolidated_marks_data['student_id'];
        $repeatMarkData['backlog_in'] = $consolidated_marks_data['assessment_type']; 

        if(((number_format((float)$value,2,'.','')/number_format((float)$consolidated_marks_data['weightage'],1,'.',''))*100)<='40'){            
            $markData['status'] = 'Re-assessment';
        }
        else {
            $markData['status'] = 'Pass';
        }
        $action = new Insert('student_consolidated_marks');
        $action->values($markData);
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        $this->updateStudentRepeatModules($repeatMarkData);

        return;
    }



    public function updateStudentRepeatModules($data)
    {
        // Get a list of repeat modules based on the data passed
        $student_repeat_module_list = $this->getStudentRepeatModuleList('student_repeat_modules', $data);

        if(!empty($student_repeat_module_list)){
            foreach($student_repeat_module_list as $key => $value){
                
                $markData['module_title'] = $data['module_title'];
                $markData['semester'] = $data['backlog_semester'];
                $markData['module_code'] = $data['module_code'];
                $markData['programmes_id'] = $data['programmes_id'];
                $markData['academic_year'] = $data['backlog_academic_year'];
                $markData['student_id'] = $data['student_id'];
                $markData['backlog_in'] = $data['backlog_in'];
                
                $backlog_status = 'Attemped';
                $action = new Update('student_repeat_modules');
                $action->set(array('backlog_status' => $backlog_status))
                    ->where(array('id' => $key));
                
                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();      
            }
        }
        return; 
    }

    public function updateStdConsolidatedResultStatus($data)
    {
        // Get a list of repeat modules based on the data passed
        $student_repeat_module_list = $this->getStudentRepeatModuleList('student_consolidated_marks', $data);

        if(!empty($student_repeat_module_list)){
            foreach($student_repeat_module_list as $key => $value){
                
                $result_status = 'Cancelled';
                $action = new Update('student_consolidated_marks');
                $action->set(array('result_status' => $result_status))
                    ->where(array('id' => $key));
                
                $sql = new Sql($this->dbAdapter);
                $stmt = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();      
            }
        }
        return; 
    }

    public function crossCheckConsolidatedMarksRecord($data)
    { 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_consolidated_marks'))
            ->where(array('t1.student_id' => $data['student_id']))
            ->where(array('t1.programme_name' => $data['programme_name']))
            ->where(array('t1.module_code' => $data['module_code']))
            ->where(array('t1.module_title' => $data['module_title']))
            ->where(array('t1.semester' => $data['backlog_semester']))
            ->where(array('t1.programmes_id' => $data['programmes_id']))
            ->where(array('t1.result_status' => 'Declared'))
            ->where(array('t1.status != ?' => 'Cancelled'))
            ->where(array('t1.academic_year' => $data['backlog_academic_year']));
            //->where(array('t1.marks' => $markData['marks']));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $consolidated_marks = array();
        foreach($resultSet as $set){
            $consolidated_marks[] = $set;
        }       
        return $consolidated_marks;
    }


    public function getStudentRepeatModuleList($tableName, $data)
    {
        $sql = new Sql($this->dbAdapter);

        if($tableName == 'student_repeat_modules'){
            $select = $sql->select();
        
            $select->from(array('t1' => $tableName))
                ->where(array('t1.student_id' => $data['student_id']))
                ->where(array('t1.module_code' => $data['module_code']))
                ->where(array('t1.module_title' => $data['module_title']))
                ->where(array('t1.backlog_semester' => $data['backlog_semester']))
                ->where(array('t1.programmes_id' => $data['programmes_id']))
                ->where(array('t1.backlog_status' => 'Not Cleared'))
                ->where(array('t1.backlog_academic_year' => $data['backlog_academic_year']));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $repeat_modules_id = array();
            foreach($resultSet as $set){
                $repeat_modules_id[$set['id']] = $set['id'];
            }
            
            return $repeat_modules_id;
        }

        if($tableName == 'student_consolidated_marks'){
            $select = $sql->select();
        
            $select->from(array('t1' => $tableName))
                ->where(array('t1.student_id' => $data['student_id']))
                ->where(array('t1.module_code' => $data['module_code']))
                ->where(array('t1.module_title' => $data['module_title']))
                ->where(array('t1.semester' => $data['backlog_semester']))
                ->where(array('t1.programmes_id' => $data['programmes_id']))
                ->where(array('t1.result_status' => 'Declared'))
                ->where(array('t1.status' => 'Cancelled'))
                ->where(array('t1.academic_year' => $data['backlog_academic_year']));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            $repeat_modules_id = array();
            foreach($resultSet as $set){
                $repeat_modules_id[$set['id']] = $set['id'];
            } 
            
            return $repeat_modules_id;
        }
    }


    public function getResultStatus($data, $organisation_id)
    {
        if($data['assessment'] == 'Continuous Assessment'){
            $markData['assessment_type'] = 'CA';
        } elseif($data['assessment'] == 'Semester Exams'){
            $markData['assessment_type'] = 'SE';
        } elseif($data['assessment'] == 'Continuous Assessment (Practical)'){
            $markData['assessment_type'] = 'CA (P)';
        } elseif($data['assessment'] =='Continuous Assessment (Theory)') {
            $markData['assessment_type'] = 'CA (T)';
        } elseif($data['assessment'] =='Semester Exams (Theory)') {
            $markData['assessment_type'] = 'SE (T)';
        } else {
            $markData['assessment_type'] = 'SE (P)';
        }
        $markData['marks'] = $data['marks'];
        $markData['programme_name'] = $data['programme_name'];
        $markData['module_title'] = $data['module_title'];
        $markData['semester'] = $data['backlog_semester'];
        $markData['module_code'] = $data['module_code'];
        $markData['credit'] = $data['module_credit'];
        $markData['weightage'] = $data['weightage'];
        $markData['programmes_id'] = $data['programmes_id'];
        $markData['academic_year'] = $data['backlog_academic_year'];
        
        $markData['student_id'] = $data['student_id'];

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        
        $select->from(array('t1' => 'student_consolidated_marks'))
            ->columns(array('level'))
            ->where(array('t1.student_id' => $markData['student_id']))
            ->where(array('t1.programme_name' => $markData['programme_name']))
            ->where(array('t1.module_title' => $markData['module_title']))
            ->where(array('t1.semester' => $markData['semester']))
            ->where(array('t1.academic_year' => $markData['academic_year']));
            //->where(array('t1.marks' => $markData['marks']));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $student_level = array();
        foreach($resultSet as $set){
            $student_level['level'] = $set['level'];
        }
        
        return $student_level;
    }
}
