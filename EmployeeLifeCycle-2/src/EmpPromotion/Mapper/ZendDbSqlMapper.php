<?php

namespace EmpPromotion\Mapper;

use EmpPromotion\Model\EmpPromotion;
use EmpPromotion\Model\RejectPromotion;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements EmpPromotionMapperInterface
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
	 * @var \EmpPromotion\Model\EmpPromotionInterface
	*/
	protected $promotionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			EmpPromotion $promotionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->promotionPrototype = $promotionPrototype;
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
		$select->columns(array('id', 'departments_units_id', 'departments_id'));
			
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
	
	/**
	* @return array/EmpPromotion()
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

		if ($result instanceof ResultInterface && $result->isQueryResult()) {
			$resultSet = new ResultSet();
			$resultSet->initialize($result);

			$resultSet = new HydratingResultSet($this->hydrator, $this->promotionPrototype);
			return $resultSet->initialize($result); 
		}

		return array();
	}
        
        /*
         * Function to list all meritorious promotion details
         */
        
        public function listMeritoriousPromotion($organisation_id)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
                $select->from(array('t1' => 'emp_promotion'))
                            ->join(array('t2' => 'employee_details'), 
                                't1.employee_details_id = t2.id', array('first_name','middle_name', 'last_name', 'emp_id', 'departments_id', 'departments_units_id'))
                            ->join(array('t3'=>'departments'),
				't2.departments_id = t3.id', array('department_name'))
                            ->join(array('t4'=>'department_units'),
                                't2.departments_units_id = t4.id', array('unit_name'));
                
                $select->where(array('t1.promotion_type' => 'Meritorious'));
                $select->where(array('t1.promotion_status' => 'Pending'));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
        }
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find the Promotion Details for a given $id
	 */
	 
	public function findPromotionDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_promotion'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}

	public function findEmployeeExtraDetails($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();


		$select->from(array('t1' => $tableName))
			   ->join(array('t2' => 'organisation'),
					't2.id = t1.working_agency', array('organisation_name'))
			   ->join(array('t3' => 'major_occupational_group'),
					't3.id = t1.occupational_group', array('occupational_group' => 'major_occupational_group'))
			   ->join(array('t4' => 'position_category'),
					't4.id = t1.position_category', array('category'))
			   ->join(array('t5' => 'position_title'),
					't5.id = t1.position_title', array('position_title_name' => 'position_title'))
			   ->join(array('t6' => 'position_level'),
					't6.id = t1.position_level', array('position_level_name' => 'position_level'))
			   ->where(array('t1.employee_details_id = ' .$id));
	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
		
	/**
	 * 
	 * @param type $EmpPromotionInterface
	 * 
	 * to save Vision and Mission Details
	 */
	
	public function save(EmpPromotion $promotionObject, $performance_year_data, $performance_rating_data, $performance_category_data, $userrole)
	{ 
		$promotionData = $this->hydrator->extract($promotionObject);
		unset($promotionData['id']);
		unset($promotionData['recommended_Position']);
		unset($promotionData['proposed_Position']);
		unset($promotionData['job_Requirements_Remarks']); 
		unset($promotionData['emp_Promotion_Id']);
		unset($promotionData['performance_Year']);
		unset($promotionData['performance_Rating']);
		unset($promotionData['performance_Category']);
		unset($promotionData['supporting_File']);
		/*var_dump($promotionData); echo '<br>';
		var_dump($performance_year_data); echo '<br>';
		var_dump($performance_rating_data); echo '<br>';
		var_dump($performance_category_data); echo '<br>';
		var_dump($performance_file_data);
		die();*/ 

		//Temporary variables to store the file of supporting_file
		$performance_file_name1 = $promotionData['performance_Detail_File1'];
		$promotionData['performance_Detail_File1'] = $performance_file_name1['tmp_name'];
		$performance_detail_file1 = $promotionData['performance_Detail_File1'];

		$performance_file_name2 = $promotionData['performance_Detail_File2'];
		$promotionData['performance_Detail_File2'] = $performance_file_name2['tmp_name'];
		$performance_detail_file2 = $promotionData['performance_Detail_File2'];

		$performance_file_name3 = $promotionData['performance_Detail_File3'];
		$promotionData['performance_Detail_File3'] = $performance_file_name3['tmp_name'];
		$performance_detail_file3 = $promotionData['performance_Detail_File3'];

		$performance_file_name4 = $promotionData['performance_Detail_File4'];
		$promotionData['performance_Detail_File4'] = $performance_file_name4['tmp_name'];
		$performance_detail_file4 = $promotionData['performance_Detail_File4'];
		
		//need to get the file locations and store them in database
		$audit_file_name = $promotionData['audit_Clearance_File'];
		$promotionData['audit_Clearance_File'] = $audit_file_name['tmp_name'];
		
		$security_file_name = $promotionData['security_Clearance_File'];
		$promotionData['security_Clearance_File'] = $security_file_name['tmp_name'];
		
		$other_file_name = $promotionData['other_Certificate_File'];
		$promotionData['other_Certificate_File'] = $other_file_name['tmp_name'];
        
		if($promotionData['meritorious_Promotion_File'] == 'none'){
			$promotionData['meritorious_Promotion_File'] = NULL;
		} else {
			$meritorious_file_name = $promotionData['meritorious_Promotion_File'];
			$promotionData['meritorious_Promotion_File'] = $meritorious_file_name['tmp_name'];
		}

		unset($promotionData['performance_Detail_File1']);
		unset($promotionData['performance_Detail_File2']);
		unset($promotionData['performance_Detail_File3']);
		unset($promotionData['performance_Detail_File4']);
        
		
		if($promotionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('emp_promotion');
			$action->set($promotionData);
			$action->where(array('id = ?' => $promotionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('emp_promotion');
			$action->values($promotionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $promotionObject->setId($newId);

				$this->updateEmpPromotionDetails($newId, $promotionData['employee_Details_Id'], $performance_year_data, $performance_rating_data, $performance_category_data, $userrole, $performance_detail_file1, $performance_detail_file2, $performance_detail_file3, $performance_detail_file4);
			}
			return $promotionObject;
		}
		
		throw new \Exception("Database Error");
	}



	public function updateEmpPromotionDetails($emp_promotion_id, $employee_details_id, $performance_year_data, $performance_rating_data, $performance_category_data, $userrole, $performance_detail_file1, $performance_detail_file2, $performance_detail_file3, $performance_detail_file4)
	{
		$i = 1;
		$yearData = array();
		foreach($performance_year_data as $year){
			$yearData[$i++] = $year;
		}

		$i = 1;
		$ratingData = array();
		foreach($performance_rating_data as $rating){
			$ratingData[$i++] = $rating;
		}

		$i = 1;
		$categoryData = array();
		foreach($performance_category_data as $category){
			$categoryData[$i++] = $category;
		}

		/*$i = 1;
		$fileData = array();
		foreach($performance_file_data as $file){
			$fileData[$i++] = $file;
		}*/

		$i = 1;
		$fileData = array();
		$fileData[$i] = $performance_detail_file1;
		$fileData[$i+1] = $performance_detail_file2;
		$fileData[$i+2] = $performance_detail_file3;
		$fileData[$i+3] = $performance_detail_file4;

		for($i = 1; $i <= 4; $i++){ 
			$action = new Insert('emp_promotion_details');
			$action->values(array(
				'employee_details_id' => $employee_details_id,
				'emp_promotion_id' => $emp_promotion_id,
				'performance_year' => $yearData[$i],
				'performance_rating' => $ratingData[$i],
				'performance_category' => $categoryData[$i],
				'supporting_file' => $fileData[$i]
			));
		
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();
		}
		return;
	}


	public function crossCheckAppliedPromotion($promotion_type, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($promotion_type == 'Normal'){
			$select->from(array('t1' => 'emp_promotion'))
				->columns(array('employee_details_id', 'id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.promotion_type' => $promotion_type, 't1.promotion_status' => 'Pending'));
		}else if($promotion_type == 'Meritorious'){
			$select->from(array('t1' => 'emp_promotion'))
				->columns(array('employee_details_id', 'id'))
		        ->where(array('t1.employee_details_id' => $employee_details_id, 't1.promotion_type' => $promotion_type, 't1.promotion_status' => 'Pending'));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$appliedPromotion = NULL;
		foreach($resultSet as $set){
				$appliedPromotion= $set['id'];
		}
		return $appliedPromotion;
	}
	
	/*
	* Save Promotion Approval Details
	*/
	
	public function savePromotionApprovalDetails($data)
	{
		$promotionData['promotion_Order_No'] = $data['promotion_order_no'];
		$promotionData['promotion_Order_Type'] = $data['promotion_order_type'];
		$promotionData['promotion_Order_Date'] = date("Y-m-d", strtotime(substr($data['promotion_order_date'],0,10)));
		$promotionData['promotion_Effective_Date'] = date("Y-m-d", strtotime(substr($data['promotion_effective_date'],0,10)));
		//need to work on file upload
		//need to get the file locations and store them in database
		$promotion_file_name = $data['promotion_order_file'];
		$promotionData['promotion_Order_File'] = $promotion_file_name['tmp_name'];
		$promotionData['recommended_Position_Title'] = $data['recommended_position_title'];
		$promotionData['recommended_Position_Level'] = $data['recommended_position_level'];
		$promotionData['recommended_Position_Category'] = $data['recommended_position_category'];
		$promotionData['recommended_Pay_Scale'] = $data['recommended_pay_scale'];
		$promotionData['proposed_Position_Remarks'] = $data['proposed_position_remarks'];
		$promotionData['job_Requirements_Remarks'] = $data['job_requirements_remarks'];
		$promotionData['promotion_Remarks'] = $data['job_requirements_remarks'];
		$promotionData['promotion_Status'] = 'Approved'; 
						
		$action = new Update('emp_promotion');
		$action->set($promotionData);
		$action->where(array('id = ?' => $data['id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute(); 
		
		//keep a record of previous position level and position title
		$position_title_id = $promotionData['recommended_Position_Title']; 
		$position_level_id = $promotionData['recommended_Position_Level'];
		$employee_details_id = $this->getPromotionEmployeeId($data['id']);
		
                
        //data to upload work history
        //To get the current emp position details
        /*$empPositionDetails = $this->getEmploymentDetails($employee_details_id);

        $work_history['occupational_Group'] = $empPositionDetails['major_occupational_group_id'];
        $work_history['position_Category'] = $empPositionDetails['position_category_id'];
        $work_history['position_Title'] = $empPositionDetails['position_title_id'];
        $work_history['position_Level'] = $empPositionDetails['position_level_id'];
        $work_history['start_Period'] = $empPositionDetails['date'];*/
        $work_history['end_Period'] = $promotionData['promotion_Effective_Date'];
        $work_history['office_Order_No'] = $promotionData['promotion_Order_No'];
        $work_history['office_Order_Date'] = $promotionData['promotion_Order_Date'];
        $work_history['remarks'] = $promotionData['promotion_Remarks'];
        $work_history['employee_Details_Id'] = $employee_details_id;
        //update the work history
        $this->updateWorkHistory($work_history);
       

		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}

			$this->updatePositionDetails('emp_position_level', $promotionData['promotion_Order_Date'], $position_level_id, $employee_details_id);
			$this->updatePositionDetails('emp_position_title', $promotionData['promotion_Order_Date'], $position_title_id, $employee_details_id);
			return $data;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Save Promotion via Open Competition
	*/
	
	public function saveOpenCompetitionPromotion($data)
	{
		//$promotionObject = new EmpPromotion();
		
		$promotionData['promotion_Type'] = 'Open Competition';
		$promotionData['promotion_Order_No'] = $data['promotion_order_no'];
		$promotionData['promotion_Order_Date'] = date("Y-m-d", strtotime(substr($data['promotion_order_date'],0,10)));
		$promotionData['promotion_Effective_Date'] = date("Y-m-d", strtotime(substr($data['promotion_effective_date'],0,10)));
		$promotionData['new_Working_Agency'] = $data['new_working_agency'];
		//need to work on file upload
		//need to get the file locations and store them in database
		$promotion_file_name = $data['promotion_order_file'];
		$promotionData['promotion_Order_File'] = $promotion_file_name['tmp_name'];
		$promotionData['recommended_Position_Title'] = $data['recommended_position_title'];
		$promotionData['recommended_Position_Level'] = $data['recommended_position_level'];
		$promotionData['recommended_Position_Category'] = $data['recommended_position_category'];
		$promotionData['recommended_Pay_Scale'] = $data['recommended_pay_scale'];
		$promotionData['promotion_Remarks'] = $data['promotion_remarks'];
		$promotionData['promotion_Status'] = 'Approved';
		$promotionData['employee_Details_Id'] = $data['employee_details_id'];
		$promotionData['departments_Id'] = $data['departments_id'];
		$promotionData['departments_Units_Id'] = $data['departments_units_id'];

		$departments_id = $promotionData['departments_Id'];
		$departments_units_id = $promotionData['departments_Units_Id'];
		unset($promotionData['departments_Id']);
		unset($promotionData['departments_Units_Id']);
		
		//ID is not present, so its an insert
		$action = new Insert('emp_promotion');
		$action->values($promotionData);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute(); 

		//keep a record of previous position level and position title
		$position_title_id = $promotionData['recommended_Position_Title']; 
		$position_level_id = $promotionData['recommended_Position_Level'];
		$employee_details_id = $promotionData['employee_Details_Id'];

		//data to upload work history
        //To get the current emp position details
        /*$empPositionDetails = $this->getEmploymentDetails($employee_details_id);

        $work_history['occupational_Group'] = $empPositionDetails['major_occupational_group_id'];
        $work_history['position_Category'] = $empPositionDetails['position_category_id'];
        $work_history['position_Title'] = $empPositionDetails['position_title_id'];
        $work_history['position_Level'] = $empPositionDetails['position_level_id'];
        $work_history['start_Period'] = $empPositionDetails['date'];*/
        $work_history['end_Period'] = $promotionData['promotion_Effective_Date'];
        $work_history['office_Order_No'] = $promotionData['promotion_Order_No'];
        $work_history['office_Order_Date'] = $promotionData['promotion_Order_Date'];
        $work_history['remarks'] = $promotionData['promotion_Remarks'];
        $work_history['employee_Details_Id'] = $employee_details_id;
        //update the work history
        $this->updateWorkHistory($work_history);
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $data['id'] = $newId;
			}
			$this->updatePositionDetails('emp_position_level', $promotionData['promotion_Order_Date'], $position_level_id, $employee_details_id);
			$this->updatePositionDetails('emp_position_title', $promotionData['promotion_Order_Date'], $position_title_id, $employee_details_id);
			$this->updateEmployeeDetails($promotionData['new_Working_Agency'], $departments_id, $departments_units_id, $employee_details_id);
			return $data;
		}
		
		throw new \Exception("Database Error");
		
	}
	
	/*
	* Reject Promotion
	*/
	
	public function rejectPromotion(RejectPromotion $promotionObject)
	{
		$promotionData = $this->hydrator->extract($promotionObject);
		unset($promotionData['id']);
		
		$action = new Update('emp_promotion');
		$action->set($promotionData);
		$action->where(array('id = ?' => $promotionObject->getId()));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $promotionObject->setId($newId);
			}
			return $promotionObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	/*
	* Get Personal Details
	*/
	
	public function getPersonalDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'))
				->join(array('t2' => 'dzongkhag'), 
                        't1.emp_dzongkhag = t2.id', array('dzongkhag_name'))
				->join(array('t3'=>'gewog'),
					't1.emp_gewog = t3.id', array('gewog_name'))
				->join(array('t4'=>'village'),
					't1.emp_village = t4.id', array('village_name'))
				->join(array('t5'=>'nationality'),
					't1.nationality = t5.id', array('nationality'))
		       ->where(array('t1.id' => $employee_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Education Details of the employee
	*/
	
	public function getEducationDetails($employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_education_details'))
			   ->join(array('t2' => 'study_level'),
						't2.id = t1.study_level', array('study_level'));
		$select->where(array('t1.employee_details_id' =>$employee_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Employment Details such as Position Title, Position Level etc. of the employee
	*/
	
	public function getEmploymentDetails($employee_id)
	{
		$employment_details = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'position_title'))
					->columns(array('position_title', 'position_category_id'))
					->join(array('t2' => 'emp_position_title'), 
							't1.id = t2.position_title_id', array('employee_details_id', 'position_title_id'))
					->join(array('t3'=>'position_category'),
                            't1.position_category_id = t3.id', array('category', 'major_occupational_group_id'))
					->join(array('t4'=>'major_occupational_group'),
                            't3.major_occupational_group_id = t4.id', array('major_occupational_group'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $data){
			$employment_details['position_title'] = $data['position_title'];
			$employment_details['category'] = $data['category'];
			$employment_details['major_occupational_group'] = $data['major_occupational_group'];
			$employment_details['major_occupational_group_id'] = $data['major_occupational_group_id'];
			$employment_details['position_title_id'] = $data['position_title_id'];
			$employment_details['position_category_id'] = $data['position_category_id'];
		}
		
		$select2 = $sql->select();
		$select2->from(array('t1' => 'position_level'))
					->columns(array('position_level'))
					->join(array('t2' => 'emp_position_level'), 
							't1.id = t2.position_level_id', array('employee_details_id', 'position_level_id', 'date'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		foreach($resultSet2 as $data){
			$employment_details['position_level'] = $data['position_level'];
			$employment_details['position_level_id'] = $data['position_level_id'];
			$employment_details['date'] = $data['date'];
		}

		$select3 = $sql->select();
		$select3->from(array('t1' => 'pay_scale'))
					->columns(array('minimum_pay_scale', 'maximum_pay_scale'))
					->join(array('t2' => 'emp_position_level'), 
							't1.position_level = t2.position_level_id', array('employee_details_id'))
					->order('t2.date ASC')
                    ->where('t2.employee_details_id = ' .$employee_id);
			
		$stmt3 = $sql->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		foreach($resultSet3 as $data){
			$employment_details['minimum_pay_scale'] = $data['minimum_pay_scale'];
			$employment_details['maximum_pay_scale'] = $data['maximum_pay_scale'];
		}
		
		return $employment_details;
	}


	public function getEmployeeLastPromotion($last_promotion, $employee_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		if($last_promotion == 'promotion_date'){
			$select->from(array('t1' => 'emp_promotion'));
			$select->where(array('t1.employee_details_id' =>$employee_id, 't1.promotion_status' => 'Approved'))
			   ->order('t1.promotion_order_date DESC')
			   ->limit(1);

			    $stmt = $sql->prepareStatementForSqlObject($select);
				$result = $stmt->execute();
				
				$resultSet = new ResultSet();
				$resultSet->initialize($result);

				$promotion_date = NULL;
				foreach($resultSet as $set){
					$promotion_date = $set['promotion_order_date'];
				}
				return $promotion_date;
		}
		else if($last_promotion == NULL){
			$select->from(array('t1' => 'emp_promotion'));
			$select->where(array('t1.id' => $employee_id, 't1.promotion_status' => 'Pending'));

			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result);
		}
	}


	public function getPromotionDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_promotion_details'));
		$select->where(array('t1.emp_promotion_id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Get Training Details of the employee
	*/
	
	public function getTrainingDetails($employee_id)
	{
		$trainings = array();
		$index = 0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'training_details'))
					->columns(array('title'=>'course_title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_location', 'institute_country'=>'institute_country',
									'start_date'=>'training_start_date','end_date' =>'training_end_date'))
					->join(array('t2' => 'emp_training_details'), 
                            't1.id = t2.training_details_id', array('employee_details_id'))
					->where('t2.employee_details_id = ' .$employee_id);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $value){
			$trainings[$index++][] = $value;
		}
				
		$select2 = $sql->select();		
		$select2->from(array('t1' => 'workshop_details'))
					->columns(array('title'=>'title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_location', 
													'institute_country'=>'institute_country', 'start_date'=>'workshop_start_date','end_date' =>'workshop_end_date'))
					->join(array('t2' => 'emp_workshop_details'), 
                            't2.workshop_details_id = t1.id', array('employee_details_id'))
                    ->where('t2.employee_details_id = ' .$employee_id);
		
		$stmt2 = $sql->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		foreach($resultSet2 as $value2){
			$trainings[$index++][] = $value2;
		}
                
                $select3 = $sql->select();		
		$select3->from(array('t1' => 'emp_previous_trainings'))
					->columns(array('title'=>'course_title', 'institute_name'=>'institute_name', 'institute_location'=>'institute_address', 
													'institute_country'=>'country', 'start_date'=>'from_date','end_date' =>'to_date'))
                    ->where('t1.employee_details_id = ' .$employee_id);
		
		$stmt3 = $sql->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
                
		foreach($resultSet3 as $value3){
			$trainings[$index++][] = $value3;
		}
                
		return $trainings;
	}
	
	/*
	* Get Research Details of the employee
	*/
	
	public function getResearchDetails($employee_id)
	{
		$research = array();
		$index=0;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'aurg_grant'))
					->columns(array('title'=>'research_title', 'year'=>'research_year', 'purpose'=>'problem_statement'))
					->where('t1.employee_details_id = ' .$employee_id);
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $value){
			$research[$index][] = $value;
		}
		
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'carg_grant'))
					->columns(array('title'=>'research_title', 'year'=>'research_year', 'purpose'=>'research_summary'))
					->where('t1.employee_details_id = ' .$employee_id);
			
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		
		$sql3 = new Sql($this->dbAdapter);
		$select3 = $sql3->select();
		$select3->from(array('t1' => 'emp_previous_research'))
					->columns(array('title'=>'publication_name', 'year'=>'publication_year', 'purpose'=>'publication_name'))
					->where('t1.employee_details_id = ' .$employee_id);
			
		$stmt3 = $sql3->prepareStatementForSqlObject($select3);
		$result3 = $stmt3->execute();
		
		$resultSet3 = new ResultSet();
		$resultSet3->initialize($result3);
		
		foreach($resultSet3 as $value){
			$research[$index][] = $value;
		}
		return $research;
	}
	
	/*
	* Get Study Leave Details of the employee
	*/
	
	public function getStudyLeaveDetails($employee_id)
	{
		$study_leave_details = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
				->join(array('t2' => 'emp_leave_category'), 
									't1.emp_leave_category_id = t2.id', array('leave_category'));
		$select->where(array('t1.employee_details_id' =>$employee_id));
		$select->where(array('t1.leave_status' => 'Approved'));
		$select->where->like('t2.leave_category','%Study%');
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		foreach($resultSet as $set){
			$study_leave_details[] = $set;
		}
		
		return $study_leave_details;
	}
	
	/*
	* Get EOL Leave Details of the employee
	*/
	
	public function getEolLeaveDetails($employee_id)
	{
		$leave_details = array();
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_leave'))
				->join(array('t2' => 'emp_leave_category'), 
									't1.emp_leave_category_id = t2.id', array('leave_category'));
		$select->where(array('t1.employee_details_id' =>$employee_id));
		$select->where(array('t1.leave_status' => 'Approved'));
		$select->where->like('t2.leave_category','%Extra%');
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$leave_details[] = $set;
		}
		
		return $leave_details;
		
	}
	
	/*
	* Get PMS Details of the employee
	*/
	
	public function getPmsDetails($employee_id, $userrole)
	{ 
		$pms_details = array();
		
		if($userrole == "%"."MODULE_TUTOR" || $userrole == "%"."HOD" || $userrole == "ACADEMIC_STAFF" )
			$evaluation_type = 'academic';
		else
			$evaluation_type = 'administrative';
				
		//get the latest pms year and use it as the base for getting the last 3 years of PMS details
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		if($evaluation_type == 'academic'){
			$select->from(array('t1' => 'pms_academic_api')) 
				->columns(array('appraisal_period'))
				->where(array('t1.employee_details_id ' => $employee_id))
				->order('appraisal_period DESC')
				->limit(1);
		}
			
		else {
			$select->from(array('t1' => 'iwp_subactivities')) 
				->columns(array('appraisal_period'))
				->where(array('t1.employee_details_id ' => $employee_id))
				->order('appraisal_period DESC')
				->limit(1);
		}
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$last_appraisal_period = NULL;
		foreach($resultSet as $set){
			$last_appraisal_period = $set['appraisal_period'];
		} //var_dump($last_appraisal_period); die();
		
		$performance_score = array();
		
		//as HODs can be both administrative and academic, we need to check to both academic api and iwp activities
		if($last_appraisal_period){ 

			for($i=0; $i<4; $i++){
				$performance_score[$last_appraisal_period-$i] = $this->getAcademicPerformanceScore($employee_id, $last_appraisal_period-$i);
				if($performance_score[$last_appraisal_period-$i] == 0)
					$performance_score[$last_appraisal_period-$i] = $this->getAdministrativePerformanceScore($employee_id, $last_appraisal_period-$i);
			} 
		}else{
			for($i=0; $i<4; $i++){
				$date = date('Y');
				$year_range = ($date-1).'-'.$date; 
				$performance_score[$year_range-$i] = "No Record";
				if($performance_score[$year_range-$i] == 0)
					$performance_score[$year_range-$i] = "No Record";
			}
		}
		 
		return $performance_score;	
	}
	
	/*
	* Get the pay details for the employee
	*/
	
	public function getPayDetails($position_level)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'pay_scale'))
					->join(array('t2' => 'position_level'), 
                            't2.id = t1.position_level', array('position_level'))
					->where->like('t2.position_level', $position_level);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$pay_scale = NULL;
		foreach($resultSet as $set){
			$pay_scale = $set['minimum_pay_scale'].'-'.$set['maximum_pay_scale'];
		}
		
		return $pay_scale;
	}
	
	/*
	* Get the details of the position
	*/
	
	public function getPositionDetails($position_title)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'position_title'))
					->join(array('t2' => 'position_directory'), 
                            't1.id = t2.position_title_id', array('work_activity'))
					->where->like('t1.position_title', $position_title);
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$work_activity = NULL;
		foreach($resultSet as $set){
			$work_activity = $set['work_activity'];
		}
		
		return $work_activity;
	}
	
	/*
	* Return the academic performance score for the PMS details for an employee
	*/
	
	public function getAcademicPerformanceScore($employee_details_id, $year)
	{
		$sql2 = new Sql($this->dbAdapter);
		$select2 = $sql2->select();
		$select2->from(array('t1' => 'pms_academic_weight')) 
				->columns(array('category', 'weight', 'maximum_api'))
				->join(array('t2' => 'pms_nature_activity'), 
                            't1.id = t2.pms_academic_weight_id', array('id','maximum_score'));		
		$stmt2 = $sql2->prepareStatementForSqlObject($select2);
		$result2 = $stmt2->execute();
		$resultSet2 = new ResultSet();
		$resultSet2->initialize($result2);
		$academic_weight = array();
		foreach($resultSet2 as $set2){
			$academic_weight[] = $set2;
		}
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'pms_academic_api')) 
				->columns(array('performance_rating', 'pms_nature_activity_id'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $year));
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$academic_api = array();
		foreach($resultSet as $set){
			$academic_api[] = $set;
		}
		
		//loop through each activity and add the scores according to the activity
		$academic_score = 0;
		$temp_score = array();
		foreach($academic_weight as $key=>$value){
			foreach($academic_api as $key1=>$value1){
				if($value1['pms_nature_activity_id'] == $value['id']){
					if(!array_key_exists($value1['pms_nature_activity_id'], $temp_score))
						$temp_score[$value1['pms_nature_activity_id']] = (int) $value1['performance_rating'];
					else
						$temp_score[$value1['pms_nature_activity_id']] += (int) $value1['performance_rating'];
				}
			}
		}
		
		//loop through the scores according to activity and ensure that the score does not exceed the maximum activity score
		$temp_score_2 = array();
		$maximum_api = array();
		$api_percentage = array();
		foreach($academic_weight as $key=>$value){
			if(array_key_exists($value['id'], $temp_score)){
				if($temp_score[$value['id']] >= $value['maximum_score'])
					$temp_score[$value['id']] = $value['maximum_score'];
				
				if(!array_key_exists($value['category'], $temp_score_2))
					$temp_score_2[$value['category']] = (int) $temp_score[$value['id']];
				else
					$temp_score_2[$value['category']] += (int) $temp_score[$value['id']];
			}
			$maximum_api[$value['category']] = $value['maximum_api'];
			$api_percentage[$value['category']] = $value['weight'];
		}
		
		//ensure that the score does not exceed the mamimum api based on the themes
		// then take the score according to the percentage weightage of the score and return the academic score
		foreach($maximum_api as $key => $value){
			if(array_key_exists($key, $temp_score_2)){
				if($temp_score_2[$key] >= $value)
					$temp_score_2[$key] = $value;
				
				$academic_score += $temp_score_2[$key]*($api_percentage[$key]/100);
			}
		}
		return $academic_score;
	}
	
	/*
	* Return the administrative performance score for PMS Details for an employee
	*/
	
	public function getAdministrativePerformanceScore($employee_details_id, $year)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();		
		$select->from(array('t1' => 'iwp_subactivities')) 
				->columns(array('supervisor_evaluation'))
				->where(array('t1.employee_details_id ' => $employee_details_id, 't1.appraisal_period' => $year));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
				
		$administrative_score = 0;
		$activities_count = count($result);
		foreach($resultSet as $set){
			$administrative_score += (int) $set['supervisor_evaluation'];
		}
		//to avoid division by '0'
		if($activities_count == 0)
			return $administrative_score;
		else
			return $administrative_score/$activities_count;
	}
	
	/*
	* Get the list of applicants applying for promotion
	*/
	
	public function getPromotionApplicantList($organisation_id, $userrole, $employee_details_id, $departments_id, $status)
	{
			$auth_type = 'Staff Promotion';

			//Get whether the particular user have assigned his/ her officiating
			$check_assigned_officiating = $this->checkOwnAssignedOfficiating($userrole);

			if($check_assigned_officiating){
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);
				$employee_promotion = array();

				if(!empty($check_authorized_role)){
					$sql = new Sql($this->dbAdapter);
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$auth_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 
				}

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
					if($officiating_role){
						$sql = new Sql($this->dbAdapter);
                                      		$select3 = $sql->select();
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select3->where->like('t1.type', '%'.$auth_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_promotion'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.promotion_status = ? ' => $status));
									//$select2->where(array('t1.promotion_order_date  >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_promotion[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$auth_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					}

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'emp_promotion'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.promotion_status = ? ' => $status));
										//$select4->where(array('t1.promotion_order_date >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_promotion[] = $set1;
										} 
									}
								}
							}
						}
					}
					return $employee_promotion;
			}
			else{
				$check_authorized_role = $this->checkAuthorizedRole($userrole, $organisation_id);
				$employee_promotion = array();

				if(!empty($check_authorized_role)){
					$sql = new Sql($this->dbAdapter);
					$select = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select->where(array('t1.organisation = ? ' => $organisation_id));
					$select->where->like('t1.type', '%'.$auth_type.'%');
					
					$stmt = $sql->prepareStatementForSqlObject($select);
					$result = $stmt->execute();
					
					$resultSet = new ResultSet();
					$resultSet->initialize($result); 
					
					foreach($resultSet as $tmp_data){
						$type_authorisation[$tmp_data['type']][$tmp_data['role_department']][$tmp_data['role']] = $tmp_data['role'];
					} 
				}

					//get details of departments if username is officiating
					$officiating_role = $this->getOfficiatingRole($employee_details_id);

					//Need to redo previous sql statements for officiating role
				if($officiating_role){
                                        $sql = new Sql($this->dbAdapter);
                                        $select3 = $sql->select();
						$select3 = $sql->select();
						$select3->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
						$select3->where(array('t1.auth = ? ' => $officiating_role));
						$select3->where(array('t1.organisation = ? ' => $organisation_id));
						$select3->where->like('t1.type', '%'.$auth_type.'%');
						
						$stmt3 = $sql->prepareStatementForSqlObject($select3);
						$result3 = $stmt3->execute();
						
						$resultSet3 = new ResultSet();
						$resultSet3->initialize($result3);
						
						foreach($resultSet3 as $tmp_data3){
							$type_authorisation[$tmp_data3['type']][$tmp_data3['role_department']][$tmp_data3['role']] = $tmp_data3['role'];
						} 
					}
					
					

					if(!empty($type_authorisation)){
						foreach ($type_authorisation as $type => $value) {
							$applied_type = $type;
							foreach ($value as $role_department => $value2) {
								foreach($value2 as $role){
									$authorizee_emp_ids = $this->getEmployeeIdByRoles($role, $role_department);
									
									foreach($authorizee_emp_ids as $value3){
										//get the list of employees
									$select2 = $sql->select();
									$select2->from(array('t1' => 'emp_promotion'))
												->join(array('t2' => 'employee_details'), 
														't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
									$select2->where(array('t1.promotion_status = ? ' => $status));
									//$select2->where(array('t1.promotion_order_date  >= ? ' => date('Y'.'-01-01')));
									$select2->where(array('t2.departments_units_id ' => $role_department));
									$select2->where(array('t1.employee_details_id != ?' => $employee_details_id));
									if($authorizee_emp_ids){
										$select2->where(array('t1.employee_details_id' => $value3));
									}
									
									$stmt2 = $sql->prepareStatementForSqlObject($select2);
									$result2 = $stmt2->execute();
									
									$resultSet2 = new ResultSet();
									$resultSet2->initialize($result2);
									foreach($resultSet2 as $set){
										$employee_promotion[] = $set;
										} 
									}
								}
							}
						}

					}

					// This will display the tour approval of officiating supervisor to the supervisor
					$sql = new Sql($this->dbAdapter);
					$select1 = $sql->select();
							
					//first get the department, organisation and authtype for the user role
					$select1->from(array('t1' => 'user_workflow'))
								->columns(array('role_department','department','type', 'role'));
					$select1->where(array('t1.auth = ? ' => $userrole));
					//$select->where(array('t1.department = ? ' => $departments_id));
					$select1->where(array('t1.organisation = ? ' => $organisation_id));
					$select1->where->like('t1.type', '%'.$auth_type.'%');
					
					$stmt1 = $sql->prepareStatementForSqlObject($select1);
					$result1 = $stmt1->execute();
					
					$resultSet1 = new ResultSet();
					$resultSet1->initialize($result1); 
					
					foreach($resultSet1 as $tmp_data1){
						$type_authorisation1[$tmp_data1['type']][$tmp_data1['role_department']][$tmp_data1['role']] = $tmp_data1['role'];
					}

					if(!empty($type_authorisation1)){
						foreach ($type_authorisation1 as $type1 => $value1) {
							$applied_type1 = $type1;
							foreach ($value1 as $role_department1 => $value4) {
								foreach ($value4 as $role1) { 
									$departments_id = $this->getSupervisorDepartmentId($role_department1);
									$officiating_supervisor_ids = $this->getEmployeeIdBySupervisorRole($role1, $departments_id);

									foreach ($officiating_supervisor_ids as $value5) { 
										//get the list of employees
										$select4 = $sql->select();
										$select4->from(array('t1' => 'emp_promotion'))
													->join(array('t2' => 'employee_details'), 
															't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'));
										$select4->where(array('t1.promotion_status = ? ' => $status));
										//$select4->where(array('t1.promotion_order_date >= ? ' => date('Y'.'-01-01')));
										//$select4->where(array('t2.departments_units_id ' => $role_department));

										if($officiating_supervisor_ids){
											$select4->where(array('t1.employee_details_id' => $value5));
										}
										
										$stmt4 = $sql->prepareStatementForSqlObject($select4);
										$result4 = $stmt4->execute();
										
										$resultSet4 = new ResultSet();
										$resultSet4->initialize($result4);
										foreach($resultSet4 as $set1){
											$employee_promotion[] = $set1;
										} 
									}
								}
							}
						}
					}

					return $employee_promotion;
				}
	}



	// Function to check whether the user have assigned his/ her own officiating
	public function checkOwnAssignedOfficiating($userrole)
	{
		$date = date('Y-m-d');
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('id', 'officiating_supervisor','from_date','to_date','supervisor', 'supervisor_id', 'department'))
                    ->where(array('t1.supervisor' => $userrole, 't1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$officiated = NULL;
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
			$officiated = $set['officiating_supervisor'];
		}

		return $officiated;
	}


	/*
	* To check whether the particular logged in role is in user_workflow table or not 
	*/

	private function checkAuthorizedRole($userrole, $organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow'));
		$select->where(array('t1.auth' => $userrole));
		$select->where(array('t1.organisation' => $organisation_id));
		$select->where->like('type','Staff Promotion');
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$role = array();
		foreach($resultSet as $set){
            $role[$set['id']] = $set['id'];
        }
        return $role;

	}


	public function getOfficiatingRole($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'))
					->columns(array('supervisor'));
		$select->where(array('t1.officiating_supervisor = ? ' => $employee_details_id));
		$select->where(array('from_date <= ? ' => date('Y-m-d')));
		$select->where(array('to_date >= ? ' => date('Y-m-d')));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		//officiating role
		$officiating_role = NULL;

		
		foreach($resultSet as $tmp_data){
			$officiating_role = $tmp_data['supervisor'];
		}
		return $officiating_role;
	}


	/*
	* Get employee ids given roles
	*/

	private function getEmployeeIdByRoles($role, $departments_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'users'))
						->join(array('t2' => 'employee_details'), 
								't1.username = t2.emp_id', array('id'));
		$select->where(array('t1.role ' => $role));
		$select->where(array('t2.departments_units_id ' => $departments_units_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['id']] = $set['id'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;

	}



	public function getSupervisorDepartmentId($department_units_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'department_units'))
					->columns(array('departments_id'))
                    ->where(array('t1.id' =>$department_units_id));

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$departments_id = NULL;
		foreach($resultSet as $set)
		{
			$departments_id = $set['departments_id'];
		}
		return $departments_id;
	}



	public function getEmployeeIdBySupervisorRole($role, $departments_id)
	{
		$date = date('Y-m-d');

		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
				
		//first get the department, organisation and authtype for the user role
		$select->from(array('t1' => 'user_workflow_officiating'));
		$select->where(array('t1.from_date <= ?' => $date, 't1.to_date >= ?' => $date));
		$select->where(array('t1.supervisor' => $role));
		$select->where(array('t1.department' => $departments_id));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$employee_ids = array();
		//$employee_details_id = array();
		foreach($resultSet as $set){
            $employee_ids[$set['officiating_supervisor']] = $set['officiating_supervisor'];

        } 
        
        //$employee_ids = array_splice($employee_ids, $employee_details_id);   	
        return $employee_ids;
	}

	
	/*
	* Get the details of the applicant for promotion
	* Used when viewing the details of the applicant
	* Takes the id of the promotion details
	*/
	
	public function getPromotionApplicantDetail($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'emp_promotion'))
				->join(array('t2' => 'employee_details'), 
							't1.employee_details_id = t2.id', array('first_name','middle_name','last_name','emp_id'))
				->join(array('t3' => 'users'), 
							't2.emp_id = t3.username', array('role'));
		$select->where(array('t1.id' =>$id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$applicant_details = array();
		foreach($resultSet as $set){
			$applicant_details['employee_details_id'] = $set['employee_details_id'];
			$applicant_details['role'] = $set['role'];
		}
		return $applicant_details;
	}
	
	/*
	* List Employees
	*/
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id)
	{
		$employee_list = array();
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details')) 
				->columns(array('id','first_name','middle_name','last_name','emp_id'))
				->join(array('t2' => 'departments'), 
						't1.departments_id = t2.id', array('department_name'))
				->join(array('t3' => 'emp_position_title'), 
						't1.id = t3.employee_details_id', array('position_title_id'))
				->join(array('t4' => 'position_title'), 
						't3.position_title_id = t4.id', array('position_title'));
		$select->order('t3.date ASC');
		
		if($organisation_id != 1){
			$select->where(array('t1.organisation_id' =>$organisation_id));
		}
		if($empName){
			$select->where->like('t1.first_name','%'.$empName.'%');
		}
		if($empId){
			$select->where(array('t1.emp_id' =>$empId));
		}
		if($department){
			$select->where(array('t1.departments_id' =>$department));
		}

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$employee_list[$set['id']] = $set;
		}
		
		return $employee_list;
	}
	
	/*
	* For downloading files. Need to get the file location from database
	*/
	
	public function getFileName($promotion_id, $document_type)
	{
		$column_name = $document_type.'_file';
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'emp_promotion')) 
				->columns(array($column_name))
				->where('t1.id = ' .$promotion_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		foreach($resultSet as $set){
			$file_location = $set[$column_name];
		}
		return $file_location;
	}


	public function getPromotionDetailFileName($emp_promotion_id, $column_name)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'emp_promotion_details')) 
                ->columns(array($column_name))
				->where('t1.id = ' .$emp_promotion_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	public function getEmployeePromotionId($id, $promotion_type)
	{ 
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
			
		$select->from(array('t1' => 'emp_promotion'))
				->where(array('t1.employee_details_id' => $id, 't1.promotion_type' => $promotion_type));
		$select->order('t1.id DESC');
		$select->limit(1);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Update the Position Level/Title History of the Employee After Promotion
	*/
	public function updatePositionDetails($table_name, $date, $position_detail, $employee_details_id)
	{ 
		$promotionData['date'] = $date;
		$promotionData['employee_Details_Id'] = $employee_details_id;
		if($table_name== 'emp_position_title')
			$promotionData['position_Title_Id'] = $position_detail;
		else
			$promotionData['position_Level_Id'] = $position_detail;
			
		$action = new Update($table_name);
		$action->set($promotionData);
		$action->where(array('employee_details_id = ?' => $promotionData['employee_Details_Id']));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;
	}


	public function updateEmployeeDetails($organisation_id, $departments_id, $departments_units_id, $employee_details_id)
	{
		$action = new Update('employee_details');
		$action->set(array('organisation_id' => $organisation_id, 'departments_id' => $departments_id, 'departments_units_id' => $departments_units_id));
		$action->where(array('id = ?' => $employee_details_id));
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return;
	}
        
        /*
	* Update the Work History of the Employee After Promotion
	*/
	
	public function updateWorkHistory($work_history)
	{                
        $work_history['working_Agency'] = $this->getWorkingAgency($work_history['employee_Details_Id']);
        //$work_history['occupational_Group'] = $this->getOccupationalGroup($work_history['occupational_Group']);

        $empPositionDetails = $this->getEmploymentDetails($work_history['employee_Details_Id']);

        $work_history['occupational_Group'] = $empPositionDetails['major_occupational_group_id'];
        $work_history['position_Category'] = $empPositionDetails['position_category_id'];
        $work_history['position_Title'] = $empPositionDetails['position_title_id'];
        $work_history['position_Level'] = $empPositionDetails['position_level_id'];
        $work_history['start_Period'] = $empPositionDetails['date'];
        $work_history['working_agency_type'] = 'RUB';

			
		$action = new Insert('emp_employment_record');
		$action->values($work_history);
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		return;

	}
	
	/*
	* Get the Employee ID for Promotion Detail
	* given the promotion approval id
	*/
	
	public function getPromotionEmployeeId($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'emp_promotion'))
				->columns(array('employee_details_id'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set){
			$id = $set['employee_details_id'];
		}
		return $id;
	}
        
        /*
         * Get the working agency given the employee id
         */
        
        public function getWorkingAgency($employee_details_id)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'employee_details'))
				->columns(array('organisation_id'))
                        ->join(array('t2' => 'organisation'), 
                                't1.organisation_id = t2.id', array('organisation_name'));
		$select->where('t1.id = ' .$employee_details_id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$organisation = NULL;
		
		foreach($resultSet as $set){
			$organisation = $set['organisation_id'];
		}
		return $organisation;
        }
        
        /*
         * Get the Occupational Group given the id
         */
        
        public function getOccupationalGroup($id)
        {
                $sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		$select->from(array('t1' => 'major_occupational_group'));
		$select->where('t1.id = ' .$id);
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$occupational_group = NULL;
		
		foreach($resultSet as $set){
			$occupational_group = $set['major_occupational_group'];
		}
		return $occupational_group;
        }
		
	/*
	* Get the notification details, i.e. submission to and submission to department
	*/
	
	public function getNotificationDetails($organisation_id)
	{
		$submission_to = NULL;
		if($organisation_id == 1){
			$submission_to = "OVC_HRO";
			return $submission_to;
		} else {
			$sql = new Sql($this->dbAdapter);
			$select = $sql->select();
			
			$select->from(array('t1' => 'user_role'))
					->columns(array('rolename'));
			$select->where('t1.organisation_id = ' .$organisation_id);
			$select->where->like('t1.rolename','%ADMINISTRATIVE_SECTION_HEAD');
			
			$stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			$resultSet->initialize($result);
			
			$occupational_group = NULL;
			
			foreach($resultSet as $set){
					$submission_to = $set['rolename'];
				}
			return $submission_to;
		}
		
	}


	public function getSupervisorEmailId($userrole, $departments_units_id)
	{
		$supervisor_email = array();
		$supervisor_role = NULL;
		$supervisor_dept = NULL;
		$supervisor_org = NULL;
		
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'user_workflow'))
			   ->columns(array('auth', 'department', 'organisation'));
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => 'Staff Promotion'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

        $authorizee_role = array();
        foreach($resultSet as $set){
        	$authorizee_role[$set['auth']][$set['department']][$set['organisation']] = $set['organisation'];
        }

        if(!empty($authorizee_role)){
        	foreach($authorizee_role as $auth_role => $value){
        		foreach($value as $auth_department => $value2){
        			foreach($value2 as $organisation){
        				$select1 = $sql->select();
						$select1->from(array('t1' => 'users'))
							   ->join(array('t2' => 'employee_details'),
									't2.emp_id = t1.username', array('email'));
						$select1->where(array('t1.role' =>$auth_role, 't2.departments_id' => $auth_department, 't2.organisation_id' => $organisation));
							
						$stmt1 = $sql->prepareStatementForSqlObject($select1);
						$result1 = $stmt1->execute();
						
						$resultSet1 = new ResultSet();
						$resultSet1->initialize($result1);

						foreach($resultSet1 as $set1){
							$supervisor_email[] = $set1['email'];
						} 
        			}
        		}
        	}
        }

        return $supervisor_email;
	}


	public function getPromotionApplicant($employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('t1.id' => $employee_details_id));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}
	
	/*
	* Return an id 
	* this is done as the ajax returns a value and not the id
	*/
	
	public function getAjaxDataId($tableName, $ajaxName, $conditional_id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		
		if($tableName == 'position_level'){
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			//$select->where->like('t1.description','%'.$conditional_id.'%');
			$select->where('t1.major_occupational_group_id = ' .$conditional_id);
			$select->where->like('t1.position_level','%'.$ajaxName);
		} else {
			$select->from(array('t1' => $tableName))
				->columns(array('id'));
			$select->where->like('t1.position_title','%'.$ajaxName);
		}
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set){
			$id = $set['id'];
		}
		return $id;
	}
		
	/**
	* @return array/EmpPromotion()
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
        
}
