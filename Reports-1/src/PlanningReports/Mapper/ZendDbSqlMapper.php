<?php

namespace PlanningReports\Mapper;

//use PlanningReports\Model\PlanningReports;
//use PlanningReports\Model\PlanningReportsCategory;
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

class ZendDbSqlMapper implements PlanningReportsMapperInterface
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
	 * @var \EmployeeTask\Model\EmployeeTaskInterface
	*/
	protected $employeetaskPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			\stdClass $employeetaskPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		//$this->planningreportsPrototype = $planningreportsPrototype;
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
	
	/**
	* @return array/Reports()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the objectives field with Objectives from the database
	*/
	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($organisation_id != NULL){
			if($tableName == 'organisation'){
				if($organisation_id == '1'){
					$select->from(array('t1' => $tableName));
					$select->columns(array('id', $columnName));
				}else{
					$select->from(array('t1' => $tableName));
					$select->columns(array('id', $columnName));
					$select->where(array('t1.id' => $organisation_id));
				}
			}else{
				$select->from(array('t1' => $tableName));
				$select->columns(array('id',$columnName,'from_date'));
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

	
	public function getstaffDetail($report_details, $organisation_id)
	{
		$staffDetail = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":
				$sql = new Sql($this->dbAdapter);

				$select = $sql->select();
				$select->from(array('t1' => 'employee_details'))
					->join(array('t2' => 'emp_position_title'),
						't1.id = t2.employee_details_id')
					->join(array('t3' => 'position_title'),
						't3.id = t2.position_title_id')
					->join(array('t4' => 'departments'),
						't4.id = t1.departments_id');
				$select->where(array('t1.organisation_id' => $organisation));
				$select->where(array('t2.position_title_id' => $position));
				$select->where(array('t1.emp_resignation_id' => '0'));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				foreach($resultSet as $set){
					$staffDetail[] = $set;
				}
				break;
		}

		return $staffDetail;
	}

	public function getobjectiveWeight($report_details, $organisation_id)
	{
		$objectiveWeight = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
		switch($report_name){
			case "compiled_apa":
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'rub_objectives'), array('objectives'))
					->join(array('t7' => 'rub_objectives_weightage'),
						't1.id = t7.rub_objectives_id', array('weightage'));
				$select->where(array('t7.financial_year = ? ' => $financial_year));
				if ($organisation == '1'){
					switch ($position) {
						case '1':
							$select->where(array('t7.departments_id' => '1'));
							break;
						case '2':
							$select->where(array('t7.departments_id' => '2'));
							break;
						case '3':
							$select->where(array('t7.departments_id' => '0'));
							break;
						case '4':
							$select->where(array('t7.departments_id' => '5'));
							break;
						case '5':
							$select->where(array('t7.departments_id' => '4'));
							break;
						case '6':
							$select->where(array('t7.departments_id' => '3'));
							break;
					}
					$select->where(array('t7.organisation_id' => $organisation));
				} else {
					$select->where(array('t7.departments_id = 0'));
				}
				$select->order(array('t1.id'));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);	

				foreach($resultSet as $set){
					$objectiveWeight[] = $set;
				}
		
				break;
		}

		return $objectiveWeight;
		
	}

	public function getkeyAspiration($report_details, $organisation_id)
	{
		$keyAspiration = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'employee_details'))
					->join(array('t2' => 'emp_position_title'),
						't1.id = t2.employee_details_id')
					->join(array('t3'=>'awpa_key_aspiration'),
					      't1.id = t3.employee_details_id');
				$select->where(array('t3.financial_year = ? ' => $financial_year));
				$select->where(array('t1.organisation_id' => $organisation));
				$select->where(array('t2.position_title_id' => $position));
				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				foreach($resultSet as $set){
					$keyAspiration[] = $set;
				}
		
				break;
		}

		return $keyAspiration;
	}

	public function getsuccessIndicator($report_details, $organisation_id)
	{
		$successIndicator = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'rub_objectives'), array('objectives'))
					->join(array('t2' => 'rub_activities'),
						't1.id = t2.rub_objectives_id', array('rub_activity' =>'activity_name'))
					->join(array('t3'=>'awpa_objectives_activity'),
					      't2.id = t3.rub_activities_id',array('activity_name'))
					->join(array('t4'=>'awpa_activities'),
					      't3.id = t4.awpa_objectives_activity_id')
					->join(array('t5'=>'employee_details'),
					      't5.id = t4.employee_details_id')
					->join(array('t6' => 'emp_position_title'),
						't5.id = t6.employee_details_id');
				$select->where(array('t4.financial_year = ? ' => $financial_year));
				$select->where(array('t5.organisation_id' => $organisation));
				$select->where(array('t6.position_title_id' => $position));
				$select->order(array('t1.id'));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);	

				foreach($resultSet as $set){
					$successIndicator[] = $set;
				}
		
				break;			
		}

		return $successIndicator;
		
	}

	public function gettrendsuccessIndicator($report_details, $organisation_id)
	{
		$trendsuccessIndicator = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":
				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'rub_objectives'), array('objectives'))
					->join(array('t2' => 'rub_activities'),
						't1.id = t2.rub_objectives_id', array('rub_activity' => 'activity_name'))
					->join(array('t3'=>'awpa_objectives_activity'),
					      't2.id = t3.rub_activities_id', array('activity_name'))
					->join(array('t4'=>'awpa_activities'),
					      't3.id = t4.awpa_objectives_activity_id',array('financial_year','unit'))
					->join(array('t5'=>'employee_details'),
					      't5.id = t4.employee_details_id', array('id'))
					->join(array('t6' => 'emp_position_title'),
						't5.id = t6.employee_details_id')
					->join(array('t7' => 'success_indicator_trend_values'),
						't3.id = t7.awpa_activities_id');
				$select->where(array('t4.financial_year = ? ' => $financial_year));
				$select->where(array('t5.organisation_id' => $organisation));
				$select->where(array('t6.position_title_id' => $position));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);	

				foreach($resultSet as $set){
					$trendsuccessIndicator[] = $set;
				}
		
				break;
			
			case "success_indicator":
		}

		return $trendsuccessIndicator;
		
	}

	public function getdefinitionsuccessIndicator($report_details, $organisation_id)
	{
		$definitionsuccessIndicator = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":

				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'rub_objectives'), array('objectives'))
					->join(array('t2' => 'rub_activities'),
						't1.id = t2.rub_objectives_id', array('rub_activity' => 'activity_name'))
					->join(array('t3'=>'awpa_objectives_activity'),
					      't2.id = t3.rub_activities_id', array('activity_name'))
					->join(array('t4'=>'awpa_activities'),
					      't3.id = t4.awpa_objectives_activity_id',array('financial_year','unit'))
					->join(array('t5'=>'employee_details'),
					      't5.id = t4.employee_details_id', array('id'))
					->join(array('t6' => 'emp_position_title'),
						't5.id = t6.employee_details_id')
					->join(array('t7' => 'success_indicator_definition'),
						't3.id = t7.awpa_activities_id');
				$select->where(array('t4.financial_year = ? ' => $financial_year));
				$select->where(array('t5.organisation_id' => $organisation));
				$select->where(array('t6.position_title_id' => $position));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);	

				foreach($resultSet as $set){
					$definitionsuccessIndicator[] = $set;
				}
		
				break;
		}

		return $definitionsuccessIndicator;
		
	}

	public function getrequirementssuccessindicator($report_details, $organisation_id)
	{
		$requirementssuccessindicator = array();
		$report_name = $report_details['report_name'];
		$organisation = $report_details['organisation'];
		$position = $report_details['position'];
		$financial_year = $report_details['financial_year']; 
	
		switch($report_name){
			case "compiled_apa":

				$sql = new Sql($this->dbAdapter);
				$select = $sql->select();
				$select->from(array('t1' => 'rub_objectives'), array('objectives'))
					->join(array('t2' => 'rub_activities'),
						't1.id = t2.rub_objectives_id', array('rub_activity' => 'activity_name'))
					->join(array('t3'=>'awpa_objectives_activity'),
					      't2.id = t3.rub_activities_id', array('activity_name'))
					->join(array('t4'=>'awpa_activities'),
					      't3.id = t4.awpa_objectives_activity_id',array('financial_year','unit'))
					->join(array('t5'=>'employee_details'),
					      't5.id = t4.employee_details_id', array('id'))
					->join(array('t6' => 'emp_position_title'),
						't5.id = t6.employee_details_id')
					->join(array('t7' => 'success_indicator_requirements'),
						't3.id = t7.awpa_activities_id');
				$select->where(array('t4.financial_year = ? ' => $financial_year));
				$select->where(array('t5.organisation_id' => $organisation));
				$select->where(array('t6.position_title_id' => $position));

				$stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();

				$resultSet = new ResultSet();
				$resultSet->initialize($result);	

				foreach($resultSet as $set){
					$requirementssuccessindicator[] = $set;
				}
		
				break;
		}

		return $requirementssuccessindicator;
		
	}

	/*
	* Get Five Year Plan
	*/
	
	public function getFiveYearPlan()
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'five_year_plan'));
		$select->columns(array('id', 'five_year_plan','from_date','to_date'));
		$select->where(array('from_date <= ? ' => date('Y-m-d'), 'to_date >= ? ' => date('Y-m-d')));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$five_year_plan = array();
		foreach($resultSet as $set)
		{
			$five_year_plan[] = $set;
		}
		return $five_year_plan;
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
	    
}
