<?php

namespace Planning\Mapper;

use Planning\Model\Vision;
use Planning\Model\Mission;
use Planning\Model\Objectives;
use Planning\Model\ObjectivesWeightage;
use Planning\Model\Activities;
use Planning\Model\AwpaObjectives;
use Planning\Model\AwpaActivities;
use Planning\Model\KeyAspiration;
use Planning\Model\FiveYearPlan;
use Planning\Model\ApaActivation;
use Planning\Model\SuccessIndicatorDefinition;
use Planning\Model\SuccessIndicatorTrend;
use Planning\Model\SuccessIndicatorRequirements;
use Planning\Model\BudgetOverlay;
use Planning\Model\OrganisationBudgetOverlay;

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

class ZendDbSqlMapper implements PlanningMapperInterface
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
	 * @var \Planning\Model\PlanningInterface
	*/
	protected $planningPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $planningPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->planningPrototype = $planningPrototype;
	}
	
	/*
	* Getting the id for username
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
	* take username and returns employee details id/student id
	*/
	
	public function getUserDetailsId($username, $tableName)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		if($tableName == 'employee_details'){
			$select->where(array('emp_id' =>$username));
			$select->columns(array('id', 'departments_id'));
		} else {
			$select->where(array('student_id' =>$username));
			$select->columns(array('id'));
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
	* Get the position title of the user
	*/
		
	public function getPositionTitle($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_position_title')) 
                    ->join(array('t2' => 'position_title'), 
                            't1.position_title_id = t2.id', array('position_title'))
                    ->where('t1.employee_details_id = ' .$employee_details_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'five_year_plan'));
		$select->columns(array('id', 'five_year_plan'));
		$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$five_year_plan = array();
		foreach($resultSet as $set)
		{
			$five_year_plan[$set['id']] = $set['five_year_plan'];
		}
		return $five_year_plan;
	}


	/*
	*Get Five Year Duration
	**/
	public function getFiveYearPlanDuration($five_year_plan_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'five_year_plan'));
		$select->where(array('t1.id' => $five_year_plan_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$five_year = array();
		foreach($resultSet as $set)
		{
			$five_year[] = $set;
		}
		return $five_year;
	}


	public function crossCheckFiveYearPlan($five_year_plan)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'five_year_plan'));
        $select->where(array('t1.five_year_plan' => $five_year_plan));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $fiveYearPlan = NULL;
        foreach($resultSet as $set){
            $fiveYearPlan = $set['five_year_plan'];
        }
        return $fiveYearPlan;
	}


	public function crossCheckFiveYearVision($five_year, $vision)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'rub_vision'));
        $select->where(array('t1.five_year_plan_id' => $five_year, 't1.vision' => $vision));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rubVision = NULL;
        foreach($resultSet as $set){
            $rubVision = $set['vision'];
        }
        return $rubVision;	
	}


	public function crossCheckFiveYearMission($five_year, $mission)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'rub_mission'));
        $select->where(array('t1.five_year_plan_id' => $five_year, 't1.mission' => $mission));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rubMission = NULL;
        foreach($resultSet as $set){
            $rubMission = $set['mission'];
        }
        return $rubMission;		
	}


	public function crossCheckFiveYearObjective($five_year_plan, $objectives)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'rub_objectives'));
        $select->where(array('t1.five_year_plan_id' => $five_year_plan, 't1.objectives' => $objectives));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rubObjective = NULL;
        foreach($resultSet as $set){
            $rubObjective = $set['objectives'];
        }
        return $rubObjective;	
	}


	public function crossCheckFiveYearOActivity($rub_objective, $activity_name)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => 'rub_activities'));
        $select->where(array('t1.rub_objectives_id' => $rub_objective, 't1.activity_name' => $activity_name));
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rubActivity = NULL;
        foreach($resultSet as $set){
            $rubActivity = $set['activity_name'];
        }
        return $rubActivity;
	}


	public function crossCheckOVCObjective($id, $rub_objectives_id, $five_year_plan_id, $departments_id, $financial_year)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        if($id == NULL && $financial_year == NULL)
        {
        	$select->from(array('t1' => 'rub_objectives_weightage'));
        	$select->where(array('t1.rub_objectives_id' => $rub_objectives_id, 't1.five_year_plan_id' => $five_year_plan_id, 't1.departments_id' => $departments_id));
        }
        else if($id != NULL && $financial_year != NULL){
        	$select->from(array('t1' => 'rub_objectives_weightage'));
        	$select->where(array('t1.rub_objectives_id' => $rub_objectives_id, 't1.five_year_plan_id' => $five_year_plan_id, 't1.departments_id' => $departments_id, 't1.financial_year' => $financial_year, 't1.id != ?' => $id));
        }
            
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rubActivity = array();
        foreach($resultSet as $set){
            $rubActivity = $set['id'];
        }
        return $rubActivity;
	}
	
	/*
	* Get the Vision and Mission for a given Five Year Plan
	*/
	
	public function getVisionMission($table_name, $five_year_plan)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $table_name));
		$select->where(array('five_year_plan_id = ?' => $five_year_plan));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getRubObjectives($table_name, $five_year_plan)
	{
		$date = date('m');
		if($date >=1 && $date <= 6){
            $start_year = date('Y')-1;
            $end_year = date('Y');
            $financial_year = $start_year.'-'.$end_year;
        }else{
            $start_year = date('Y');
            $end_year = date('Y')+1;
            $financial_year = $start_year.'-'.$end_year;
        }

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $table_name))
			   ->join(array('t2' => 'rub_objectives'),
					't2.id = t1.rub_objectives_id', array('objectives','remarks'));
		$select->where(array('t1.five_year_plan_id = ?' => $five_year_plan, 't1.financial_year' => $financial_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getRubObjectivesWeightage($table_name, $five_year_plan, $organisation_id, $supervisor_dept_id)
	{
		$date = date('m');
		if($date >=1 && $date <= 6){
            $start_year = date('Y')-1;
            $end_year = date('Y');
            $financial_year = $start_year.'-'.$end_year;
        }else{
            $start_year = date('Y');
            $end_year = date('Y')+1;
            $financial_year = $start_year.'-'.$end_year;
        }

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id != 1){
			$select->from(array('t1' => $table_name))
			   ->join(array('t2' => 'rub_objectives'),
					't2.id = t1.rub_objectives_id', array('objectives'));
			$select->where(array('t1.five_year_plan_id = ?' => $five_year_plan, 't1.organisation_id' => '0', 't1.departments_id' => '0', 't1.financial_year' => $financial_year));
		}
		else if($organisation_id == 1){
			$select->from(array('t1' => $table_name))
			   ->join(array('t2' => 'rub_objectives'),
					't2.id = t1.rub_objectives_id', array('objectives'));
			$select->where(array('t1.five_year_plan_id = ?' => $five_year_plan, 't1.organisation_id' => $organisation_id, 't1.departments_id' => $supervisor_dept_id, 't1.financial_year' => $financial_year));
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getOVCObjectives($tableName, $five_year_plan, $organisation_id)
	{
		$date = date('m');

        if($date >=1 && $date <= 6){
            $start_year = date('Y')-1;
            $end_year = date('Y');
            $financial_year = $start_year.'-'.$end_year;
        }else{
            $start_year = date('Y');
            $end_year = date('Y')+1;
            $financial_year = $start_year.'-'.$end_year;
        }

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'rub_objectives'),
					't2.id = t1.rub_objectives_id', array('objectives'))
			   ->join(array('t3' => 'departments'),
					't3.id = t1.departments_id', array('department_name'));
		$select->where(array('t1.five_year_plan_id = ?' => $five_year_plan, 't1.organisation_id' => $organisation_id, 't1.financial_year' => $financial_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
		
	/**
	* @param int/String $id
	* @return Planning
	* @throws \InvalidArgumentException
	*/
	
	public function findVisionMission($table_name, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select($table_name);
		$select->where(array('id = ? ' => $id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/**
	* @return array/Planning()
	*/
	public function findAll($tableName, $employee_details_id)
	{
		$financial_year = $this->getApaPeriod(); 

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                if($tableName=='awpa_activities'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'awpa_objectives_activity'), 
                            't2.id = t1.awpa_objectives_activity_id', array('rub_activities_id', 'activity_name','financial_year'))
                        ->join(array('t3' => 'rub_activities'), 
                            't3.id = t2.rub_activities_id', array('rub_objectives_id'))
                        ->join(array('t4' => 'rub_objectives'), 
                            't4.id = t3.rub_objectives_id', array('objectives', 'five_year_plan_id'))
                        ->join(array('t5' => 'five_year_plan'),
                    		't5.id = t4.five_year_plan_id', array('from_date', 'to_date'));
                    $select->where(array('t1.employee_details_id = ?' => $employee_details_id, 't1.activity_status = ?' => 'Approved', 't5.from_date <= ?' => date('Y-m-d'), 't5.to_date >= ?' => date('Y-m-d'), 't2.financial_year' => $financial_year));
                } 
                else if($tableName=='awpa_objectives_activity'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'rub_activities'), 
                            't2.id = t1.rub_activities_id', array('rub_objectives_id'))
                        ->join(array('t3' => 'rub_objectives'), 
                            't3.id = t2.rub_objectives_id', array('objectives', 'five_year_plan_id'))
                        ->join(array('t4' => 'five_year_plan'),
                    		't4.id = t3.five_year_plan_id', array('from_date', 'to_date'));
                    $select->where(array('t1.employee_details_id ' => $employee_details_id, 't4.from_date <= ?' => date('Y-m-d'), 't4.to_date >= ?' => date('Y-m-d'), 't1.financial_year' => $financial_year));
                } 
                else if($tableName=='rub_activities'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'rub_objectives'), 
                            't1.rub_objectives_id = t2.id', array('objectives','weightage','remarks','five_year_plan_id'))
                        ->join(array('t3' => 'five_year_plan'),
                    		't3.id = t2.five_year_plan_id', array('from_date', 'to_date'))
                        ->where(array('t3.from_date <= ?' => date('Y-m-d'), 't3.to_date >= ?' => date('Y-m-d')));
				}
				else if($tableName=='awpa_key_aspiration'){
                    $select->from(array('t1' => $tableName))
                        ->where(array('t1.financial_year' => $financial_year, 't1.employee_details_id' => $employee_details_id));
                } else {
                    $select->from(array('t1' => $tableName));
                }

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function findAllEvaluation($tableName, $employee_details_id)
	{
		$financial_year = $this->getApaEvaluationPeriod(); 

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                
                if($tableName=='awpa_activities'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'awpa_objectives_activity'), 
                            't2.id = t1.awpa_objectives_activity_id', array('rub_activities_id', 'activity_name'))
                        ->join(array('t3' => 'rub_activities'), 
                            't3.id = t2.rub_activities_id', array('rub_objectives_id'))
                        ->join(array('t4' => 'rub_objectives'), 
                            't4.id = t3.rub_objectives_id', array('objectives', 'five_year_plan_id'))
                        ->join(array('t5' => 'five_year_plan'),
                    		't5.id = t4.five_year_plan_id', array('from_date', 'to_date'));
                    $select->where(array('t1.employee_details_id = ?' => $employee_details_id, 't1.activity_status = ?' => 'Approved', 't5.from_date <= ?' => date('Y-m-d'), 't5.to_date >= ?' => date('Y-m-d'), 't2.financial_year' => $financial_year));
                } 
                else if($tableName=='awpa_objectives_activity'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'rub_activities'), 
                            't2.id = t1.rub_activities_id', array('rub_objectives_id'))
                        ->join(array('t3' => 'rub_objectives'), 
                            't3.id = t2.rub_objectives_id', array('objectives', 'five_year_plan_id'))
                        ->join(array('t4' => 'five_year_plan'),
                    		't4.id = t3.five_year_plan_id', array('from_date', 'to_date'));
                    $select->where(array('t1.employee_details_id ' => $employee_details_id, 't4.from_date <= ?' => date('Y-m-d'), 't4.to_date >= ?' => date('Y-m-d'), 't1.financial_year' => $financial_year));
                } 
                else if($tableName=='rub_activities'){
                    $select->from(array('t1' => $tableName))
                        ->join(array('t2' => 'rub_objectives'), 
                            't1.rub_objectives_id = t2.id', array('objectives','weightage','remarks','five_year_plan_id'))
                        ->join(array('t3' => 'five_year_plan'),
                    		't3.id = t2.five_year_plan_id', array('from_date', 'to_date'))
                        ->where(array('t3.from_date <= ?' => date('Y-m-d'), 't3.to_date >= ?' => date('Y-m-d')));
				}
				else if($tableName=='awpa_key_aspiration'){
                    $select->from(array('t1' => $tableName))
                        ->where(array('t1.financial_year' => $financial_year, 't1.employee_details_id' => $employee_details_id));
                } else {
                    $select->from(array('t1' => $tableName));
                }

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	* @return array/Objectives()
	*/
	public function listSupervisorObjectives($supervisor_ids, $organisation_id)
	{
	        if(is_array($supervisor_ids)){	
			$supervisor_detail_id = $supervisor_ids[0];
		}
		else{
			$supervisor_detail_id = $supervisor_ids;
		}

		$supervisor_dept_id = $this->getSupervisorDeptIds($supervisor_detail_id);
		
        $date = date('m');

        if($date >=1 && $date <= 6){
                $start_year = date('Y')-1;
                $end_year = date('Y');
                $financial_year = $start_year.'-'.$end_year;
            }else{
                $start_year = date('Y');
                $end_year = date('Y')+1;
                $financial_year = $start_year.'-'.$end_year;
            }

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
        if($organisation_id == '1'){
        	$select->from(array('t1' => 'rub_objectives'))
                        ->columns(array('objectives'))
                ->join(array('t2' => 'rub_activities'), 
                    't1.id = t2.rub_objectives_id', array('id','activity_name'))
                 ->join(array('t3' => 'five_year_plan'),
            		't3.id = t1.five_year_plan_id', array('from_date', 'to_date'))
                 ->join(array('t4' => 'rub_objectives_weightage'),
             		't4.rub_objectives_id = t1.id', array('weightage', 'organisation_id', 'departments_id'))
                ->where(array('t3.from_date <= ?' => date('Y-m-d'), 't3.to_date >= ?' => date('Y-m-d'), 't4.organisation_id' => $organisation_id, 't4.departments_id' => $supervisor_dept_id, 't4.financial_year' => $financial_year));
        }else{
        	$select->from(array('t1' => 'rub_objectives'))
                        ->columns(array('objectives'))
                ->join(array('t2' => 'rub_activities'), 
                    't1.id = t2.rub_objectives_id', array('id','activity_name'))
                 ->join(array('t3' => 'five_year_plan'),
            		't3.id = t1.five_year_plan_id', array('from_date', 'to_date'))
                 ->join(array('t4' => 'rub_objectives_weightage'),
             		't4.rub_objectives_id = t1.id', array('weightage', 'organisation_id', 'departments_id'))
                ->where(array('t3.from_date <= ?' => date('Y-m-d'), 't3.to_date >= ?' => date('Y-m-d'), 't4.organisation_id' => '0', 't4.departments_id' => '0', 't4.financial_year' => $financial_year));
        }
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		
		/*if($type == NULL){
			return $resultSet->initialize($result);
		} else{*/
			$resultSet->initialize($result);
			$selectData = array();
			foreach($resultSet as $set){
				$selectData[$set['id']] = $set['activity_name'].' ('.$set['objectives'].' : '.$set['weightage'].')';
			}
			return $selectData;
		//}
		
	}
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Objectives for a given $id
	 */
	 
	public function findObjectives($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'rub_objectives_weightage'))
			   ->join(array('t2' => 'rub_objectives'),
					't2.id = t1.rub_objectives_id', array('objectives','remarks'))
			   ->join(array('t3' => 'five_year_plan'),
					't3.id = t1.five_year_plan_id', array('five_year_plan'));
		$select->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Activity for a given $id
	 */
	 
	public function findActivities($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'awpa_activities'))
			   ->join(array('t2' => 'awpa_objectives_activity'),
					't2.id = t1.awpa_objectives_activity_id', array('activity_name'));
		$select->where(array('t1.id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	 * Activity Detail when editing the Activity Objectives
	 */
	 
	public function findObjectivesActivity($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'awpa_objectives_activity'));
		$select->where(array('id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
        /*
          * Generic function to get the details of various tables when editing such as RUB Activities etc.
          */
         
        public function getDetailsById($table_name, $id)
        {
	        $details = array();
	        $sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			$select->from(array('t1' => $table_name));
			$select->where(array('t1.id = ?' => $id));
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();

			$resultSet = new ResultSet();
			$resultSet->initialize($result);
            foreach($resultSet as $set){
                $details[] = $set;
            }
            
            return $details;
        }
		
	/*
	 * Find Five Year Plan Details
	 */
	 
	public function findFiveYearPlan($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'five_year_plan'));
		$select->where(array('id = ?' => $id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	 /**
	 * @param PlanningInterface $planningObject
	 */
	 
	 public function saveVision(Vision $planningObject)
	 {
		$planningData = $this->hydrator->extract($planningObject);
		
		$planningData['five_Year_Plan_Id'] = $planningData['five_Year_Plan'];
		unset($planningData['id']);
		unset($planningData['five_Year_Plan']);
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('rub_vision');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('rub_vision');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	 }
	 
	 /**
	 * @param PlanningInterface $planningObject
	 *
	 */
	 
	 public function saveMission(Mission $planningObject)
	 {
		$planningData = $this->hydrator->extract($planningObject);
		$planningData['five_Year_Plan_Id'] = $planningData['five_Year_Plan'];
		unset($planningData['id']);
		unset($planningData['five_Year_Plan']);
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('rub_mission');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('rub_mission');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	 }
	
	
	/**
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save Objectives Details
	 */
	
	public function saveObjectives(Objectives $planningObject)
	{
		
		$planningData = $this->hydrator->extract($planningObject);
		$planningData['five_Year_Plan_Id'] = $planningData['five_Year_Plan'];

		$organisation = $planningData['remarks'];

		unset($planningData['id']);
		unset($planningData['five_Year_Plan']);
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('rub_objectives');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('rub_objectives');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $planningObject->setId($newId);
			}
			if($organisation != 'ovc'){
				$this->saveRubCollegeObjectiveWeightage($newId, $planningData['five_Year_Plan_Id'], $planningData['weightage']);
			}
			
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}


	/*
	*Function to add the rub college objectives weightage at the time of adding RUB objectives in financial year wise
	**/
	public function saveRubCollegeObjectiveWeightage($rub_objectives_id, $five_year_plan_id, $weightage)
	{
		$five_year_duration = $this->getFiveYearPlanDuration($five_year_plan_id);
		$start_date = NULL;
		$end_date = NULL;
		//$five_year = array();
		foreach($five_year_duration as $value){
			$start_date = $value['from_date'];
			$end_date = $value['to_date'];
		}

		$start_parts = explode('-', $start_date);
		$end_parts = explode('-', $end_date);
		$start_year = $start_parts[0];
		$end_year = $end_parts[0]; 

		$no_of_year = $this->yearsDifference($end_date, $start_date);

		$financial_year = array();
		for($i=$no_of_year; $i>=1; $i--){
		$financial_year[($end_year-$i)."-".($end_year-$i+1)] = ($end_year-$i)."-".($end_year-$i+1);
		}
		foreach($financial_year as $value){
			$planningData['rub_Objectives_Id'] = $rub_objectives_id;
			$planningData['five_Year_Plan_Id'] = $five_year_plan_id;
			$planningData['weightage'] = $weightage;
			$planningData['organisation_Id'] = '0';
			$planningData['departments_Id'] = '0';
			$planningData['financial_year'] = $value;

			$action = new Insert('rub_objectives_weightage');
	        $action->values($planningData);

	        $sql = new Sql($this->dbAdapter);
	        $stmt = $sql->prepareStatementForSqlObject($action);
	        $result = $stmt->execute(); 
		}
		return;			
	}


	public function saveObjectivesWeightage(Objectives $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		$planningData['five_Year_Plan_Id'] = $planningData['five_Year_Plan'];
		//unset($planningData['id']);
		unset($planningData['five_Year_Plan']);

		$rub_objectives_id = $this->getRubObjectivesId($planningData['id']);
		
		$action = new Update('rub_objectives_weightage');
		$action->set(array('five_year_plan_id' => $planningData['five_Year_Plan_Id'], 'weightage' => $planningData['weightage']));
		$action->where(array('id = ?' => $planningData['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute(); 

		$this->updateObjective($rub_objectives_id, $planningData['objectives'], $planningData['weightage']);
	}


	public function updateObjective($id, $objectives, $weightage)
	{ 
		$action = new Update('rub_objectives');
		$action->set(array('objectives' => $objectives, 'weightage' => $weightage));
		$action->where(array('id = ?' => $id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	}


	public function getRubObjectivesId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'rub_objectives_weightage'))
			   ->columns(array('rub_objectives_id'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$rub_objectives_id = NULL;
		foreach($resultSet as $set){
			$rub_objectives_id = $set['rub_objectives_id'];
		}
		return $rub_objectives_id;
	}


	public function saveOVCObjectivesWeightage(ObjectivesWeightage $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
		
		$five_year_duration = $this->getFiveYearPlanDuration($planningData['five_Year_Plan_Id']);
		$start_date = NULL;
		$end_date = NULL;
		//$five_year = array();
		foreach($five_year_duration as $value){
			$start_date = $value['from_date'];
			$end_date = $value['to_date'];
		}

		$start_parts = explode('-', $start_date);
		$end_parts = explode('-', $end_date);
		$start_year = $start_parts[0];
		$end_year = $end_parts[0]; 

		$no_of_year = $this->yearsDifference($end_date, $start_date);

		$financial_year = array();
		for($i=$no_of_year; $i>=1; $i--){
		$financial_year[($end_year-$i)."-".($end_year-$i+1)] = ($end_year-$i)."-".($end_year-$i+1);
		}
		foreach($financial_year as $value){
			$weightageData['rub_Objectives_Id'] = $planningData['rub_Objectives_Id'];
			$weightageData['five_Year_Plan_Id'] = $planningData['five_Year_Plan_Id'];
			$weightageData['weightage'] = $planningData['weightage'];
			$weightageData['organisation_Id'] = $planningData['organisation_Id'];
			$weightageData['departments_Id'] = $planningData['departments_Id'];
			$weightageData['financial_year'] = $value;

			$action = new Insert('rub_objectives_weightage');
	        $action->values($weightageData);

	        $sql = new Sql($this->dbAdapter);
	        $stmt = $sql->prepareStatementForSqlObject($action);
	        $result = $stmt->execute(); 
		}
		return;	
	}


	public function updateOVCObjectivesWeightage(ObjectivesWeightage $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);

		//ID present, so it is an update
		$action = new Update('rub_objectives_weightage');
		$action->set($planningData);
		$action->where(array('id = ?' => $planningData['id']));
	
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}
        
        /*
          * Save RUB Activities
          * saves RUB Activities while saveActivities saves individual APA activities
          */
         
        public function saveRubActivities(Activities $planningObject)
        {
            $planningData = $this->hydrator->extract($planningObject);
			unset($planningData['id']);
		
			if($planningObject->getId()) {
			//ID present, so it is an update
				$action = new Update('rub_activities');
				$action->set($planningData);
				$action->where(array('id = ?' => $planningObject->getId()));
			} else {
				//ID is not present, so its an insert
				$action = new Insert('rub_activities');
				$action->values($planningData);
			}
		
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();

		
			if($result instanceof ResultInterface) {
				if($newId = $result->getGeneratedValue()){
					//when a value has been generated, set it on the object
					echo $planningObject->setId($newId);
				}
				return $planningObject;
			}
			
			throw new \Exception("Database Error");
        }
	 
	
	
	/**
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveActivities(AwpaObjectives $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
		
		/*
		foreach($objectivesObject as $data)
		{
			array_push($objectivesData, $data['id']);
			array_push($objectivesData, $data['activity_name']);
			array_push($objectivesData, $data['awpa_remarks']);
			array_push($objectivesData, $data['employee_details_id']);
		}
		*/
		$financial_year = $this->getApaPeriod();

		$planningData['rub_Activities_Id'] = $planningData['objectives'];
		unset($planningData['objectives']);
		unset($planningData['objectives_Remarks']);
		unset($planningData['awpa_Objectives_Id']);
        unset($planningData['rub_Objectives_Id']);
        $planningData['financial_Year'] = $financial_year;

		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('awpa_objectives_activity');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('awpa_objectives_activity');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
				
				//the following loop is to insert action plan
				//need to fix it
				/*
				if($objectivesId != NULL)
				{
					$action = new Insert('awpa_objectives');
					$action->values(array(
						'objectives'=> $objectivesData[1],
						'objectives_remarks' => $objectivesData[2],
						'awpa_objectives_activity_id' => $newId,
						'employee_details_id'=> $objectivesData[3],
					));
					
					$sql = new Sql($this->dbAdapter);
					$stmt = $sql->prepareStatementForSqlObject($action);
					$result = $stmt->execute();
				}
				*/
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the Success Indicator Trend
	*/
	 
	public function saveSuccessIndicatorTrend(SuccessIndicatorTrend $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
				
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('success_indicator_trend_values');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('success_indicator_trend_values');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	/*
	* Save the Success Indicator Definition
	*/
	 
	public function saveSuccessIndicatorDefinition(SuccessIndicatorDefinition $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
				
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('success_indicator_definition');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('success_indicator_definition');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	 
	/*
	* Save the Success Indicator Requirements
	*/
	 
	public function saveSuccessIndicatorRequirements(SuccessIndicatorRequirements $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
				
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('success_indicator_requirements');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('success_indicator_requirements');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/**
	 * Save Five Year Plan
	 *
	 */
	 
	public function saveFiveYearPlan(FiveYearPlan $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
		
		
		//Need to extract the date range and insert into respective dates
		$planningData['from_Date'] = date("Y-m-d", strtotime(substr($planningData['date_Range'],0,10)));
		$planningData['to_Date'] = date("Y-m-d",strtotime(substr($planningData['date_Range'],13,10)));
		unset($planningData['date_Range']);
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('five_year_plan');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('five_year_plan');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save APA Dates
	*/
	 
	public function saveApaDates(ApaActivation $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
		
		//Need to extract the date range and insert into respective dates
		$planningData['start_Date'] = date("Y-m-d", strtotime(substr($planningData['date_Range'],0,10)));
		$planningData['end_Date'] = date("Y-m-d",strtotime(substr($planningData['date_Range'],13,10)));
		unset($planningData['date_Range']);
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('apa_activation_date');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('apa_activation_date');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	 * Get the APA activation dates
	 */
	 
	public function getActivationDates($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('apa_activation_date');
		if($id){
			$select->where(array('id = ? ' => $id));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	/*
	* Get the last date for APA submission
	*/
	 
	public function getLastDateApa()
	{
		$last_submission_date = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select('apa_activation_date');
		$select->where(array('end_date >= ? ' => date('Y-m-d')));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$last_submission_date = $set['end_date'];
		}
		return $last_submission_date;
	}
        
        /*
          * Get the weight of the objectives
          */
         
        public function getObjectiveWeightage($id, $five_year_plan, $organisation_id, $supervisor_dept_id)
        { 
            $total_weight = 0;

            $date = date('m');
            if($date >=1 && $date <= 6){
	            $start_year = date('Y')-1;
	            $end_year = date('Y');
	            $financial_year = $start_year.'-'.$end_year;
	        }else{
	            $start_year = date('Y');
	            $end_year = date('Y')+1;
	            $financial_year = $start_year.'-'.$end_year;
	        }
            
            $sql = new Sql($this->dbAdapter);
            
            if($five_year_plan != NULL && $id == NULL && $organisation_id == NULL && $supervisor_dept_id == NULL){
                $select = $sql->select();
                $select->from(array('t1' => 'rub_objectives'));
                $select->where(array('t1.five_year_plan_id = ? ' => $five_year_plan));

                $stmt = $sql->prepareStatementForSqlObject($select);
                $result = $stmt->execute();
                
            }else if($five_year_plan != NULL && $id != NULL && $organisation_id == NULL && $supervisor_dept_id == NULL){
                $select = $sql->select();
                $select->from(array('t1' => 'rub_objectives_weightage'))
                	   ->join(array('t2' => 'rub_objectives'),
                			't2.id = t1.rub_objectives_id', array('objectives'));
                $select->where(array('t1.five_year_plan_id = ? ' => $five_year_plan, 't1.id != ?' => $id, 't1.financial_year' => $financial_year, 't1.organisation_id' => '0', 't1.departments_id' => '0'));

                $stmt = $sql->prepareStatementForSqlObject($select);
                $result = $stmt->execute();
                
            }
             else if($five_year_plan == NULL && $id != NULL && $organisation_id != NULL && $supervisor_dept_id != NULL){
             	if($organisation_id == 1){
             		$select = $sql->select();
	                $select->from(array('t1' => 'awpa_objectives_activity'))
	                        ->join(array('t2' => 'rub_activities'), 
	                            't1.rub_activities_id = t2.id', array('rub_objectives_id'))
	                        ->join(array('t3' => 'rub_objectives_weightage'),
	                            't2.rub_objectives_id = t3.rub_objectives_id', array('weightage'));
	                $select->where(array('t1.id = ? ' => $id, 't3.organisation_id' => $organisation_id, 't3.departments_id' => $supervisor_dept_id, 't3.financial_year' => $financial_year));

	                $stmt = $sql->prepareStatementForSqlObject($select);
	                $result = $stmt->execute();
             	}
             	else{
             		$select = $sql->select();
	                $select->from(array('t1' => 'awpa_objectives_activity'))
	                        ->join(array('t2' => 'rub_activities'), 
	                            't1.rub_activities_id = t2.id', array('rub_objectives_id'))
	                        ->join(array('t3' => 'rub_objectives_weightage'),
	                            't2.rub_objectives_id = t3.rub_objectives_id', array('weightage'));
	                $select->where(array('t1.id = ? ' => $id, 't3.organisation_id' => '0', 't3.departments_id' => '0', 't3.financial_year' => $financial_year));

	                $stmt = $sql->prepareStatementForSqlObject($select);
	                $result = $stmt->execute();
             	}                
            }
            
            $resultSet = new ResultSet();
                $resultSet->initialize($result);
            foreach($resultSet as $set){
                    $total_weight += $set['weightage'];
            }
            return $total_weight; 
        }



        public function getOVCObjectiveWeightage($id, $five_year_plan_id, $departments_id)
        {
        	$total_weight = 0;

        	$date = date('m');

	        if($date >=1 && $date <= 6){
	            $start_year = date('Y')-1;
	            $end_year = date('Y');
	            $financial_year = $start_year.'-'.$end_year;
	        }else{
	            $start_year = date('Y');
	            $end_year = date('Y')+1;
	            $financial_year = $start_year.'-'.$end_year;
	        }

            $sql = new Sql($this->dbAdapter);
            
            $select = $sql->select();

            if($id == NULL){
            	$select->from(array('t1' => 'rub_objectives_weightage'));
            	$select->where(array('t1.five_year_plan_id' => $five_year_plan_id, 't1.departments_id' => $departments_id, 't1.financial_year' => $financial_year));
            }
            else if($id != NULL){
            	$select->from(array('t1' => 'rub_objectives_weightage'));
            	$select->where(array('t1.five_year_plan_id' => $five_year_plan_id, 't1.departments_id' => $departments_id, 't1.financial_year' => $financial_year, 't1.id != ?' => $id));
            }

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();
            
            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            foreach($resultSet as $set){
                    $total_weight += $set['weightage'];
            } 
            return $total_weight;
        }
        
        /*
          * Get the weight of the Success Indicator
          */
         
        public function getIndicatorWeightage($awpa_objectives_activity_id, $id)
        {
            $total_weight = 0;
                
            $sql = new Sql($this->dbAdapter);

            $select = $sql->select();

            if($id == NULL){
            	$select->from(array('t1' => 'awpa_activities'));
            	$select->where(array('t1.awpa_objectives_activity_id = ? ' => $id));
            }

            else{
            	$select->from(array('t1' => 'awpa_activities'));
        		$select->where(array('t1.awpa_objectives_activity_id = ? ' => $id, 't1.id != ?' => $id));
            }
            

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
                $resultSet->initialize($result);
            foreach($resultSet as $set){
                    $total_weight += $set['weight'];
            }
            return $total_weight;
            
        }
	
	/**
	 * 
	 * @param type $PlanningInterface
	 * 
	 * to save Activities Details
	 */
	
	public function saveKpi(AwpaActivities $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);

		$planningData['financial_Year'] = $this->getApaPeriod();
		
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('awpa_activities');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('awpa_activities');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		$this->saveInitialKpi($planningData);

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveKeyAspiration(KeyAspiration $planningObject)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']);
		
		$planningData['financial_Year'] = $this->getApaPeriod();
		//var_dump($planningData); die();
		if($planningObject->getId()) {
			//ID present, so it is an update
			$action = new Update('awpa_key_aspiration');
			$action->set($planningData);
			$action->where(array('id = ?' => $planningObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('awpa_key_aspiration');
			$action->values($planningData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function saveInitialKpi($planningData)
	{
		$action = new Insert('awpa_initial_activities');
		$action->values($planningData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
	}

	
	public function saveMidTermReview(AwpaActivities $planningObject, $id)
	{
		$planningData = $this->hydrator->extract($planningObject);
		unset($planningData['id']); 
                
		$action = new Update('awpa_activities');
		$action->set($planningData);
		$action->where(array('id = ?' => $planningObject->getId()));
                
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$planningObject->setId($newId);
			}
			return $planningObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save the APA self evaluation
	*/
	 
	public function saveApaEvaluation($data)
	{
            if($data){
			foreach($data as $key=>$value){
				$evaluationData['self_Evaluation'] = $value['evaluation'];
				$evaluationData['status'] = $value['status'];
                                $evaluationData['verification_Means'] = $value['verification_means'];
				$evaluationData['awpa_Activities_Id'] = $key;
				
				$action = new Insert('awpa_activities_evaluation');
				$action->values($evaluationData);
				
				$sql = new Sql($this->dbAdapter);
				$stmt = $sql->prepareStatementForSqlObject($action);
				$result = $stmt->execute();
			}
		}
		
		return;
	}
        
        /*
         * Save Budget Overlay
         */
         
        public function saveBudgetOverlay(BudgetOverlay $planningObject)
        {
            $planningData = $this->hydrator->extract($planningObject);
            unset($planningData['id']);

            if($planningObject->getId()) {
                    //ID present, so it is an update
                    $action = new Update('budget_overlay');
                    $action->set($planningData);
                    $action->where(array('id = ?' => $planningObject->getId()));
            } else {
                    //ID is not present, so its an insert
                    $action = new Insert('budget_overlay');
                    $action->values($planningData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();


            if($result instanceof ResultInterface) {
                    if($newId = $result->getGeneratedValue()){
                            //when a value has been generated, set it on the object
                            $planningObject->setId($newId);
                    }
                    return $planningObject;
            }

            throw new \Exception("Database Error");
        }
         
        /*
         * Save Organisation Budget Overlay
         */
         
        public function saveOrganisationBudgetOverlay(OrganisationBudgetOverlay $planningObject)
        {
            $planningData = $this->hydrator->extract($planningObject);
            unset($planningData['id']);

            if($planningObject->getId()) {
                    //ID present, so it is an update
                    $action = new Update('organisation_budget_overlay');
                    $action->set($planningData);
                    $action->where(array('id = ?' => $planningObject->getId()));
            } else {
                    //ID is not present, so its an insert
                    $action = new Insert('organisation_budget_overlay');
                    $action->values($planningData);
            }

            $sql = new Sql($this->dbAdapter);
            $stmt = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();


            if($result instanceof ResultInterface) {
                    if($newId = $result->getGeneratedValue()){
                            //when a value has been generated, set it on the object
                            $planningObject->setId($newId);
                    }
                    return $planningObject;
            }

            throw new \Exception("Database Error");
        }
	
	/*
          * This is a generic function to get the table values for
          * Success Indicator Trends, Requirements and Definitions
          *
          * takes table name and employee details id
          */
         
        public function getSuccessIndicatorVariables($table_name, $employee_details_id)
	{
		$financial_year = $this->getApaPeriod();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => $table_name))
                            ->join(array('t2' => 'awpa_objectives_activity'), 
                                't1.awpa_activities_id = t2.id', array('activity_name'))
                            ->join(array('t3' => 'rub_activities'),
                        		't3.id = t2.rub_activities_id', array('rub_objectives_id'))
                            ->join(array('t4' => 'rub_objectives'),
                        		't4.id = t3.rub_objectives_id', array('five_year_plan_id'))
                            ->join(array('t5' => 'five_year_plan'),
                        		't5.id = t4.five_year_plan_id', array('from_date', 'to_date'))
                           ->where(array('t2.employee_details_id = ' .$employee_details_id, 't5.from_date <= ?' => date('Y-m-d'), 't5.to_date >= ?' => date('Y-m-d'), 't2.financial_year' => $financial_year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}
	
	
	/*
	 * Get the Self Evaluation of APA
	 */
	 
	public function getSelfEvaluation($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'awpa_activities_evaluation'))
				->join(array('t2' => 'awpa_activities'), 
                            't1.awpa_activities_id = t2.id')
				->join(array('t3' => 'awpa_objectives_activity'), 
                            't3.rub_activities_id = t2.id');
		$select->where(array('t2.employee_details_id = ?' => $employee_details_id));
		//->where('t2.employee_details_id = ' .$employee_details_id);

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
        
        /*
          * Get the budget overlay
          */
         
        public function getBudgetOverlay($table_name, $organisation_id)
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            if($table_name == 'budget_overlay'){
                $select->from(array('t1' => $table_name))
                            ->join(array('t2' => 'awpa_objectives_activity'), 
                                't1.awpa_objectives_activity_id = t2.id', array('activity_name'));
            }
            else {
                $select->from(array('t1' => $table_name))
                            ->join(array('t2' => 'awpa_objectives_activity'), 
                                't1.awpa_objectives_activity_id = t2.id', array('activity_name'))
                            ->join(array('t3' => 'organisation'),
                                't1.organisation_id = t3.id', array('organisation_name'));
            }
            
            if($organisation_id){
                $select->where('t1.organisation_id = ' .$organisation_id);
            }
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            return $resultSet->initialize($result);
        }
        
        /*
          * Get the ids of the supervisors
          * Only for Planning Division, there will be two , i.e VC and Directors
          * Other departments/organisations will have only one.
          */
         
        public function getSupervisorIds($employee_details_id, $supervisor_role, $organisation_id)
        {
            //store the list of supervisors in array
            $id = array();
            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            if($organisation_id == '1'){
                //get supervisor role based on employee department
                $supervisor_role = $this->getSupervisorRole($employee_details_id, $supervisor_role);

                $select->from(array('t1' => 'employee_details'))
                        ->columns(array('id'))
                        ->join(array('t2' => 'users'), 
                                't1.emp_id = t2.username', array('role'))
                        ->where(array('role ' => $supervisor_role));
            }
            else {
                $select->from(array('t1' => 'employee_details'))
                        ->columns(array('id'))
                        ->join(array('t2' => 'users'), 
                                't1.emp_id = t2.username', array('username'))
                        ->where('t1.organisation_id = ' .$organisation_id);
                $select->where->like('t2.role','%'.'PRESIDENT');
            }
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            
            foreach($resultSet as $set){
                $id[] = $set['id'];
            }
            return $id;    
        }


        public function getSupervisorDeptIds($employee_details_id)
        {            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'employee_details'))
                    ->where(array('t1.id = ' .$employee_details_id));
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            
            $departments_id = NULL;
            foreach($resultSet as $set){
                $departments_id = $set['departments_id'];
            }
            return $departments_id;    
        }


        public function getApaDeadline($apa_type)
        {
        	//var_dump($apa_type); die();
        	$deadline = NULL;
        	if($apa_type == 'APA') {
        		$apa_period = $this->getApaPeriod();
        	} else {
        		$apa_period = $this->getApaEvaluationPeriod();
        	}
			
			 
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();

			$select->from(array('t1' => 'apa_activation_date'))
								->columns(array('start_date', 'end_date'));
			$select->where(array('apa_year' =>$apa_period));
			$select->where(array('apa_type' =>$apa_type));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
        }

        public function getApaEvaluationPeriod()
        {
        $apa_period = NULL;
		if(date('m') <= 7){
			$apa_period = (date('Y')-1)."-".(date('Y'));
		 } else {
			 $apa_period = (date('Y'))."-".(date('Y')+1);
		 } //echo $apa_period; die();
		 return $apa_period;
        }

        public function getApaPeriod()
        {
        $apa_period = NULL;
		if(date('m') < 6){
			$apa_period = (date('Y')-1)."-".(date('Y'));
		 } else {
			 $apa_period = (date('Y'))."-".(date('Y')+1);
		 } //echo $apa_period; die();
		 return $apa_period;
        }
        
        /*
         * For OVC, get the supervisor roles based on the employee details id
         */
        
        public function getSupervisorRole($employee_details_id, $supervisor_role)
        {
            //store the list of supervisors roles in array
            $roles = array();
            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'employee_details'))
                    ->columns(array('id'))
                    ->join(array('t2' => 'departments'), 
                            't1.departments_id = t2.id', array('department_name'))
                    ->where('t1.id = ' .$employee_details_id);
            
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            
            foreach($resultSet as $set){
                if($set['department_name'] == 'Department of Planning and Resources' && $supervisor_role == 'Executive'){
                    $roles[] = 'PLANNING_DIRECTOR';
                } else if($set['department_name'] == 'Department of Planning and Resources' && $supervisor_role == 'VICE_CHANCELLOR'){
                    $roles[] = 'VICE_CHANCELLOR';
                }
                else if($set['department_name'] == 'Office of the Vice Chancellor'){
                    $roles[] = 'VICE_CHANCELLOR';
                }
                 else if($set['department_name'] == 'Office of the Registrar'){
                    $roles[] = 'REGISTRAR';
                } else if($set['department_name'] == 'Department of Academic Affairs'){
                    $roles[] = 'ACADEMIC_DIRECTOR';
                } else if($set['department_name'] == 'Department of Research and External Relations'){
                    $roles[] = 'RESEARCH_DIRECTOR';
                }
            }
            
            return $roles;
        }
        
        /*
          * Get the supervisor roles
          * in the same format as listSelectData
          */
         
        
        public function listSupervisorRoles($supervisor_ids)
        {
            //store the list of supervisors roles in array
            $roles = array();
            
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            
            $select->from(array('t1' => 'employee_details'))
                    ->columns(array('id'))
                    ->join(array('t2' => 'users'), 
                            't1.emp_id = t2.username', array('role'))
                    ->where(array('t1.id ' => $supervisor_ids));
            
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            
            foreach($resultSet as $set){
                $roles[$set['id']] = $set['role'];
            }
            
            return $roles;
        }


    /*
    *Function to return the number of years from start year and end year
    **/
    public function yearsDifference($endDate, $beginDate)
	{
	   $date_parts1=explode("-", $beginDate);
	   $date_parts2=explode("-", $endDate);
	   return $date_parts2[0] - $date_parts1[0];
	}


	public function listRubObjectives($five_year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'rub_objectives'));
		$select->columns(array('id', 'objectives', 'five_year_plan_id'))
			   ->where(array('t1.five_year_plan_id' => $five_year));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		//Need to make the resultSet as an array
		// e.g. 1=> Objective 1, 2 => Objective etc.
		
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set['objectives'];
		}
		return $selectData;
	}
	
	/**
	* @return array/Planning()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $emp_id)
	{
		$financial_year = $this->getApaPeriod();

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($tableName == 'rub_objectives'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName))
				   ->join(array('t2' => 'five_year_plan'),
						't2.id = t1.five_year_plan_id', array('from_date', 'to_date'))
				   ->where(array('t2.from_date <= ?' => date('Y-m-d'), 't2.to_date >= ?' => date('Y-m-d')));
		}

		if($tableName == 'departments'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id', $columnName))
				   ->where(array('t1.organisation_id' => '1'));
		}

		else if($tableName == 'awpa_objectives_activity'){
			$select->from(array('t1' => $tableName));
			$select->columns(array('id',$columnName))
				   ->join(array('t2' => 'rub_activities'),
						't2.id = t1.rub_activities_id', array('rub_objectives_id'))
				   ->join(array('t3' => 'rub_objectives'),
						't3.id = t2.rub_objectives_id', array('five_year_plan_id'))
				   ->join(array('t4' => 'five_year_plan'),
						't4.id = t3.five_year_plan_id', array('from_date', 'to_date'))
				   ->where(array('t4.from_date <= ?' => date('Y-m-d'), 't4.to_date >= ?' => date('Y-m-d'), 't1.financial_year' => $financial_year));

		   if($emp_id != NULL)
			{
				$select->where(array('employee_details_id = ?' => $emp_id));
			}
		}

		$select->from(array('t1' => $tableName));
		$select->columns(array('id',$columnName));
		if($emp_id != NULL)
		{
			$select->where(array('employee_details_id = ?' => $emp_id));
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
