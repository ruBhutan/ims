<?php

namespace GoodsRequisition\Mapper;

use GoodsRequisition\Model\GoodsRequisition;
use GoodsRequisition\Model\GoodsRequisitionApproval;
use GoodsRequisition\Model\GoodsRequisitionForwardApproval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements GoodsRequisitionMapperInterface
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
	 * @var \GoodsRequisition\Model\GoodsRequisitionInterface
	*/
	protected $goodsRequisitionPrototype;
	
	/**
	* @param AdapterInterface $dbAdapter
	*/
	
	public function __construct(
			AdapterInterface $dbAdapter,
			HydratorInterface $hydrator,
			//\stdClass $goodsRequisitionPrototype
			GoodsRequisition $goodsRequisitionPrototype
		)
	{
		$this->dbAdapter = $dbAdapter;
		$this->hydrator = $hydrator;
		$this->goodsRequisitionPrototype = $goodsRequisitionPrototype;
	}

	/*
	* Getting the id for username
	*/
	
	public function getUserDetailsId($username)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'employee_details'));
		$select->where(array('emp_id' =>$username));
		$select->columns(array('id', 'departments_units_id', 'departments_id'));
			
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
	
	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function findRequisitionApproval($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t2' => 'item_name'),
            			't2.id = t1.item_name_id', array('item_name'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}
    

    /**
	* @return GoodsRequisition of Individual
	*/
	public function findIndividualRequisition($status, $employee_details_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_requisition_details')) // Base table
                   ->join(array('t2' => 'item_name'), //join table with alias
                   		't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id')) // join expression
                   ->join(array('t3' => 'item_sub_category'),
               			't3.id = t2.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t4' => 'item_category'),
               			't4.id = t3.item_category_id', array('category_type'))
                   ->join(array('t5' => 'item_quantity_type'),
               			't5.id = t2.item_quantity_type_id', array('item_quantity_type'));
           $select->where(array('t1.requisition_status = ? ' => $status, 't1.employee_details_id =?' => $employee_details_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

	/**
	* @return All Goods Requisition to submit to the store 
	*/
	public function listAllGoodsRequisition($tableName, $status, $employee_details_id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => $tableName)) // Base table
                   ->join(array('t2' => 'item_name'), //join table with alias
                   		't2.id = t1.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id')) // join expression
                   ->join(array('t3' => 'item_sub_category'),
               			't3.id = t2.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t4' => 'item_category'),
               			't4.id = t3.item_category_id', array('category_type'))
                   ->join(array('t5' => 'item_quantity_type'),
               			't5.id = t2.item_quantity_type_id', array('item_quantity_type'));
           $select->where(array('t1.requisition_status = ? ' => $status, 't1.employee_details_id =?' => $employee_details_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}

	/*
	* The following function to change the status from "Not Submitted to Pending"
	*/
	public function updateIndGoodsRequisition($status, $previousStatus, $employee_details_id, $id)
	{
		//need to get the organisaiton id
		//$organisation_id = 1;
		$goodsTransactionData['requisition_status'] = $status;
		$action = new Update('goods_requisition_details');
		$action->set($goodsTransactionData);

		if($previousStatus != NULL){

		$action->where(array('requisition_status = ?' => $previousStatus));
		$action->where(array('employee_details_id = ?' => $employee_details_id));
	}
	elseif($id != NULL) {
		$action->where(array('id = ?' => $id));
	}
			
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();


	}

	
	/**
	* @return All GoodsRequisition with new requisition
	*/
	public function findAllRequisitionApproval($userrole, $organisation_id)
	{
		$auth_type = "Goods Requisition";
		$departments_staff = array();
		$empty = array();

        $sql = new Sql($this->dbAdapter);
        $select1 = $sql->select();

        //first get the department, organisation and authtype for the user role
        $select1->from(array('t1' => 'user_workflow'))
				->columns(array('role_department', 'department','type'));
		$select1->join(array('t2' => 'users'), 
					't1.role = t2.role', array('username'))
                ->join(array('t3' => 'employee_details'), 
                    't2.username = t3.emp_id AND t3.departments_units_id = t1.role_department', array('id', 'departments_units_id'));
		$select1->where(array('t1.type = ?' => $auth_type));
	    $select1->where(array('t1.auth = ?' => $userrole));
	    $select1->where(array('t1.organisation = ?' => $organisation_id));
				
        $stmt1 = $sql->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
        $resultSet1 = new ResultSet();
        $resultSet1->initialize($result1);
        $departments_staff = array();
        foreach($resultSet1 as $set1){
                $departments_staff[$set1['id']] = $set1['id'];
        }

        if(!empty($departments_staff)){
        	$select = $sql->select();
        	$select->from(array('t1' => 'employee_details')) //base table
	               ->join(array('t2' => 'goods_requisition_details'), // join table with alias
	                    't1.id = t2.employee_details_id', array('requisition_date'))
	               ->join(array('t3' => 'departments'),
	               	    't3.id = t1.departments_id', array('department_name'))
	               ->join(array('t4' => 'department_units'),
	               	     't4.id = t1.departments_units_id', array('unit_name'))
	               ->where(array('t2.requisition_status' => 'Pending'))
	               ->where(array('t2.employee_details_id' => $departments_staff))
	               ->group(array('t2.requisition_date', 't1.id', 't3.department_name', 't4.unit_name'));
		$select->order('t2.approval_date DESC');
	        $stmt = $sql->prepareStatementForSqlObject($select);
			$result = $stmt->execute();
			
			$resultSet = new ResultSet();
			return $resultSet->initialize($result); 
        }
        return $empty;
	}


	/*
         * Get the Department Units for HRO
         */
        
        public function getDepartmentUnits($organisation_id)
        {
            $units = array();
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'departments'))
                    ->columns(array('department_name'))
                    ->join(array('t2' => 'department_units'), 
                        't1.id = t2.departments_id', array('id'));
            $select->where(array('t1.organisation_id ' => $organisation_id));
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            $resultSet = new ResultSet();
            $resultSet->initialize($result);
            foreach($resultSet as $set){
                $units[] = $set['id'];
            }
            return $units;
        }


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Requisition 
	 */
	public function getStaffGoodsRequisitionDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_requisition_details'))
		       ->join(array('t2' => 'employee_details'),
		       	       't2.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t3' => 'department_units'),
		       	       't3.id = t2.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t4' => 'departments'),
			       't4.id = t2.departments_id', array('department_name'))
			   ->order('t1.approval_date DESC')
			   ->where(array('t1.employee_details_id = ? ' => $id, 't1.requisition_status = ?' => 'Pending'))
			  // ->where(array('t1.requisition_status' => 'Pending'));
			   ->group(array('t1.id','t1.employee_details_id', 't2.emp_id', 't2.first_name', 't2.middle_name', 't2.last_name', 't2.departments_id', 't2.departments_units_id', 't3.unit_name', 't3.departments_id', 't4.department_name'));
	 $select->order('t1.approval_date DESC');	
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	public function getStaffGoodsRequisitionListDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_requisition_details'))
		       ->join(array('t3' => 'item_name'),
		       	       't3.id = t1.item_name_id', array('item_name', 'item_sub_category_id'))
		       ->join(array('t4' => 'item_sub_category'),
		       	       't4.id = t3.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t5' => 'item_category'),
		       	       't5.id = t4.item_category_id', array('category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t3.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t7.departments_id', array('department_name'))
			       ->where(array('t1.employee_details_id = ? ' .$id, 't1.requisition_status = ?' => 'Pending'));
		 $select->order('t1.approval_date DESC');
			  // ->where(array('t1.requisition_status' => 'Pending'));
			  // ->group(array('t1.requisition_date', 't1.employee_details_id', 't3.item_name', 't3.item_sub_category_id', 't4.sub_category_type', 't6.item_quantity_type', 't7.emp_id', 't7.first_name', 't7.middle_name', 't7.last_name', 't7.departments_id', 't7.departments_units_id', 't8.unit_name', 't8.departments_id', 't9.department_name'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Requisition 
	 */
	public function getGoodsRequisitionDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'goods_requisition_details'))
		       ->join(array('t2' => 'goods_requisition'),
		       	      't2.id = t1.goods_requisition_id', array('employee_details_id'))
		       ->join(array('t3' => 'item_name'),
		       	       't3.id = t1.item_name_id', array('item_name', 'item_sub_category_id'))
		       ->join(array('t4' => 'item_sub_category'),
		       	       't4.id = t3.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t5' => 'item_category'),
		       	       't5.id = t4.item_category_id', array('category_type'))
		       ->join(array('t6' => 'item_quantity_type'),
		       	       't6.id = t3.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t7' => 'employee_details'),
		       	       't7.id = t2.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t8' => 'department_units'),
		       	       't8.id = t7.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t9' => 'departments'),
		       	       't9.id = t8.departments_id', array('department_name'))
               
			       ->where(array('t7.id =' .$id, 'requisition_status = ?' => 'Pending')); // join expression
		 $select->order('t1.approval_date DESC');
			   //->where(array('goods_surrender_status = Pending'));
		
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	* @return All GoodsRequisition with new requisition approved
	*/
	public function findAllRequisitionApproved()
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'goods_requisition_details')) // Base table
                   ->join(array('t2' => 'item_name'), //join table with alias
                   't2.id = t1.item_name_id', array('item_name')) // join expression
                   ->join(array('t3' => 'goods_requisition'),
                   	't3.id = t1.goods_requisition_id', array('employee_details_id'));
            $select->where(array('requisition_status = "Approved"'));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
   
    /**
	* @return GoodsRequisition of All
	*/
	public function findAllRequisitions($status, $userrole, $organisation_id)
	{

		$auth_type = "Goods Requisition";
		$departments_staff = array();
		$empty = array();

        	$sql = new Sql($this->dbAdapter);
        	$select1 = $sql->select();
        	//first get the department, organisation and authtype for the user role
        	$select1->from(array('t1' => 'user_workflow'))
				->columns(array('role_department', 'department','type'));
		$select1->join(array('t2' => 'users'), 
					't1.role = t2.role', array('username'))
                ->join(array('t3' => 'employee_details'), 
                    't2.username = t3.emp_id', array('id', 'departments_units_id'));
		$select1->where(array('t1.type = ?' => $auth_type));
	    	$select1->where(array('t1.auth = ?' => $userrole));
	    	$select1->where(array('t1.organisation = ?' => $organisation_id));
				
        	$stmt1 = $sql->prepareStatementForSqlObject($select1);
		$result1 = $stmt1->execute();
		
        	$resultSet1 = new ResultSet();
		$resultSet1->initialize($result1);
        	$departments_staff = array();
        	foreach($resultSet1 as $set1){
                	$departments_staff[$set1['id']] = $set1['id'];
		}

        	if(!empty($departments_staff)){
        		$select = $sql->select();
	        	$select->from(array('t1' => 'goods_requisition_details')) // Base table
	               		->join(array('t2' => 'item_name'), //join table with alias
	               				't2.id = t1.item_name_id', array('item_name')) // join expression
	               		->join(array('t4' => 'employee_details'),
	               				't4.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
	               		->join(array('t5' => 'department_units'),
	               				't5.id = t4.departments_units_id', array('unit_name', 'departments_id'))
	               		->join(array('t6' => 'departments'),
	               				't6.id = t5.departments_id', array('department_name'));
	        	$select->where(array('t1.requisition_status = ?' => $status, 't4.organisation_id = ?' .$organisation_id, 't1.employee_details_id' => $departments_staff));

	        	$stmt = $sql->prepareStatementForSqlObject($select);
	        	$result = $stmt->execute();

	        	if ($result instanceof ResultInterface && $result->isQueryResult()) {

	                	$resultSet = new ResultSet();
	                	$resultSet->initialize($result);

	                	$resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
	               	 	return $resultSet->initialize($result); 
	        	}
        	}
        return $empty;
	}
      /*
       *  @ function is for viewing only
       */

	public function findAllRequisitionsForViewOnly ($status, $userrole, $organisation_id)
        {
              //echo "USer role:".$userrole.'AND - '.$organisation_id;

                $auth_type = "Goods Requisition";
                $departments_staff = array();
                $empty = array();

                $sql = new Sql($this->dbAdapter);
                $select1 = $sql->select();
                //first get the department, organisation and authtype for the user role
                $select1->from(array('t1' => 'user_workflow'))
                                ->columns(array('role_department', 'department','type'));
                $select1->join(array('t2' => 'users'),
                                        't1.role = t2.role', array('username'))
                ->join(array('t3' => 'employee_details'),
                    't2.username = t3.emp_id', array('id', 'departments_units_id'));
                $select1->where(array('t1.type = ?' => $auth_type));
                //$select1->where(array('t1.role = ?' => $userrole));
                $select1->where(array('t1.organisation = ?' => $organisation_id));

                $stmt1 = $sql->prepareStatementForSqlObject($select1);
                $result1 = $stmt1->execute();

                $resultSet1 = new ResultSet();
                $resultSet1->initialize($result1);
        //      var_dump($resultSet1);
                $departments_staff = array();
                foreach($resultSet1 as $set1){
                        $departments_staff[$set1['id']] = $set1['id'];
		}
		 //var_dump($departments_staff);
		if(!empty($departments_staff)){
                        $select = $sql->select();
                        $select->from(array('t1' => 'goods_requisition_details'), array ('employee_details_id')) // Base table
                                ->join(array('t2' => 'item_name'), //join table with alias
                                                't2.id = t1.item_name_id', array('item_name')) // join expression
                                ->join(array('t4' => 'employee_details'),
                                                't4.id = t1.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
                                ->join(array('t5' => 'department_units'),
                                                't5.id = t4.departments_units_id', array('unit_name', 'departments_id'))
                                ->join(array('t6' => 'departments'),
                                                't6.id = t5.departments_id', array('department_name'));
                        $select->where(array('t1.requisition_status = ?' => $status, 't4.organisation_id = ?' .$organisation_id, 't1.employee_details_id' => $departments_staff));
			 $select->order('t1.approval_date DESC');
                        $stmt = $sql->prepareStatementForSqlObject($select);
                        $result = $stmt->execute();

                        if ($result instanceof ResultInterface && $result->isQueryResult()) {

                                $resultSet = new ResultSet();
                                $resultSet->initialize($result);

                                $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                                return $resultSet->initialize($result);
                        }
                }
        	return $empty;
	}
	/*
	 *  @ Calculate depreciation
	 */

	public function calculateDepreciation($status, $userrole, $organisation_id)
	{
		
		$staffList = $this -> getStaffList($organisation_id);
	
		$sql = new Sql($this->dbAdapter);		
		$status = 'Diminishing Balance';
		
		//$select = $sql->select();

		foreach ($staffList as $staff)
		{
			$select = $sql->select();
                        $select->from(array('t1' => 'depreciation_table'),
                                      array('item_name_id','good_received_date','depreciation_rate', 'goods_life', 'scrap_value')) // Base table
                                ->join(array('t2' => 'item_name'), //join table with alias
                                                't2.id = t1.item_name_id', array('item_name')) // join expression
                                ->join(array('t3' => 'goods_received'),
                                        't3.item_name_id = t2.id', array('item_amount'))
                                ->join (array('t4' => 'goods_requisition_details'),
                                        't4.item_name_id = t3.item_name_id');

                        $select->where(array( 't1.depreciation_method=?' => $status, 't4.employee_details_id = ?' => $staff));
			$stmt = $sql->prepareStatementForSqlObject($select);

			$result = $stmt->execute();

//			var_dump($result);
			foreach ($result as $dep)
                        {
                                $received_date = $dep['good_received_date']; // '2022-08-3';
                                $from = strtotime ($received_date);
                                $today = time ();
                                $difference = $today - $from;

                                $year = $dep['goods_life'];

                                $depreciated = 0;

                                $lifeofthegoods = 365*$year;
                                $received_days = floor($difference / 86400);

                                $duration = $lifeofthegoods - $received_days;

                                if (($lifeofthegoods - $received_days) < 0)
                                {
                                        $depreciation[$dep['item_name_id']][$staff] = $dep['scrap_value'];
                                }
				else
				{
                                        $oneday = (($dep['depreciation_rate']/100)/365);

                                        $balance = ($lifeofthegoods - $received_days);


                                        $depreciated = (($balance * $oneday)  * $dep['item_amount']);


                                        $depreciation[$dep['item_name_id']][$staff] = round($depreciated);

                                }
                                $depreciation[$staff][$dep['item_name_id']] = $dep['good_received_date'];

                                $depreciation['amount'][$dep['item_name_id']] = $dep['item_amount'];

                                $depreciation['scrap'][$dep['item_name_id']] = $dep['scrap_value'];

                                $depreciation['depercent'][$dep['item_name_id']] = $dep['depreciation_rate'];
			}
                
		}
//	       var_dump($depreciation);	
		return $depreciation;
	}

	/**
	 * Get Staff list that have requested goods
	 */

	private function getStaffList($organisation_id)
	{

                $auth_type = "Goods Requisition";
                $departments_staff = array();
                $empty = array();

                $sql = new Sql($this->dbAdapter);
                $select1 = $sql->select();
                //first get the department, organisation and authtype for the user role
                $select1->from(array('t1' => 'user_workflow'))
                                ->columns(array('role_department', 'department','type'));
                $select1->join(array('t2' => 'users'),
                                        't1.role = t2.role', array('username'))
                ->join(array('t3' => 'employee_details'),
                    't2.username = t3.emp_id', array('id', 'departments_units_id'));
                $select1->where(array('t1.type = ?' => $auth_type));
                //$select1->where(array('t1.role = ?' => $userrole));
                $select1->where(array('t1.organisation = ?' => $organisation_id));

                $stmt1 = $sql->prepareStatementForSqlObject($select1);
                $result1 = $stmt1->execute();

                $resultSet1 = new ResultSet();
                $resultSet1->initialize($result1);
		
		$departments_staff = array();
		
		foreach($resultSet1 as $set1){
                        $departments_staff[$set1['id']] = $set1['id'];
		}

		return $departments_staff;
  
	}
	
	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
        public function findRequisitionDetails($id) 
        {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
             $select->from(array('t1' => 'goods_requisition')) //base table
               ->join(array('t2' => 'item_name'), // join table with alias
                    't2.id = t1.item_name_id', array('item_name'));  //join expression

                    
            
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
					$resultSet->buffer();
                    return $resultSet->initialize($result); 
            }
            
            return array();
        }

         /**
	* @return GoodsRequisition of Individual
	*/
	public function findIndividualRequisitionForwarded($employee_details_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'requisition_forward_details')) // Base table
                   ->join(array('t2' => 'goods_requisition_details'), //join table with alias
                        't2.id = t1.goods_requisition_details_id', array('employee_details_id', 'item_name_id', 'requisition_item_quantity', 'requisition_date')) // join expression
                   ->join(array('t3' => 'item_name'),
                   	    't3.id = t2.item_name_id', array('item_name', 'item_sub_category_id', 'item_quantity_type_id'))
                   ->join(array('t4' => 'employee_details'),
                   	    't4.id = t1.requisition_forwarded_by', array('first_name', 'middle_name', 'last_name'))
                   ->join(array('t5' => 'item_sub_category'),
               			't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
               			't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'item_quantity_type'),
               			't7.id = t3.item_quantity_type_id', array('item_quantity_type'));
            $select->where(array('t2.employee_details_id = ?' => $employee_details_id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {

                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);

                    $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}
	
	
	public function findGoodsRequisition($id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(array('t1' => 'goods_requisition_details'))
        	   ->join(array('t2' => 'item_name'),
        			't2.id = t1.item_name_id', array('item_name'))
			   ->join(array('t3' => 'item_sub_category'),
					't3.id = t2.item_sub_category_id', array('sub_category_type', 'item_category_id'))
			    ->join(array('t4' => 'item_category'),
					't4.id = t3.item_category_id', array('category_type'));
        $select->where(array('t1.id = ' .$id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Requisition with given ID: ($id) not found");
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
		$select->where(array('t1.role' =>$userrole, 't1.role_department' => $departments_units_id, 't1.type' => 'Goods Requisition'));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		foreach($resultSet as $set){
        	$supervisor_role = $set['auth'];
        	$supervisor_dept = $set['department'];
        	$supervisor_org = $set['organisation'];
        } 
        
        $supervisor_email = $this->getSupervisorEmail($supervisor_role, $supervisor_dept, $supervisor_org);

        return $supervisor_email;
	}



	public function getSupervisorEmail($supervisor_role, $supervisor_dept, $supervisor_org)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => 'users'))
			   ->join(array('t2' => 'employee_details'),
					't2.emp_id = t1.username', array('email'));
		$select->where(array('t1.role' =>$supervisor_role, 't2.departments_id' => $supervisor_dept, 't2.organisation_id' => $supervisor_org));
			
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		$email = array();
		foreach($resultSet as $set){
			$email[] = $set['email'];
		} 
		return $email;
	}


	public function getRequisitionApplicant($employee_details_id)
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
	

	/**
	* @return All GoodsRequisition forwarded with new requisition
	*/
	public function findAllRequisitionForwardApproval($organisation_id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'requisition_forward_details')) //base table
               ->join(array('t2' => 'goods_requisition_details'), // join table with alias
               		't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'requisition_remarks'))
           	   ->join(array('t3' => 'employee_details'),
                    't3.id = t1.requisition_forwarded_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
               ->join(array('t4' => 'item_name'),
           			 't4.id = t2.item_name_id', array('item_name'))
               ->where(array('t1.requisition_forward_status' => 'Pending', 't3.organisation_id = ?' .$organisation_id));
              // ->group(array('t2.goods_requisition_id')); 
		 $select->order('t2.approval_date DESC');
            $stmt = $sql->prepareStatementForSqlObject($select);
        	$result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function findRequisitionForwardApproval($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'requisition_forward_details'))
            	   ->join(array('t2' => 'goods_requisition_details'),
            			't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'approval_date', 'requisition_remarks', ))
            	   ->join(array('t4' => 'employee_details'),
            			't4.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'departments_id', 'departments_units_id'))
                   ->join(array('t5' => 'department_units'),
                   	't5.id = t4.departments_units_id', array('unit_name', 'departments_id'))
                   ->join(array('t6' => 'departments'),
                   	't6.id = t5.departments_id', array('department_name'))
                   ->join(array('t7' => 'item_name'),
               		't7.id = t2.item_name_id', array('item_name', 'item_quantity_type_id'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	/**
	* To get the list of requisition forwarded status and view the details if approved or rejected.
	**/
	public function findAllRequisitionForwarded($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'requisition_forward_details')) //base table
               ->join(array('t2' => 'goods_requisition_details'), // join table with alias
               		't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'requisition_remarks'))
           	   ->join(array('t4' => 'employee_details'),
                    't4.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                	//'t4.id = t1.requisition_forwarded_by', array('forward_first_name'=>'first_name'))
               ->join(array('t5' => 'item_name'),
           			 't5.id = t2.item_name_id', array('item_name'))
               ->where(array('t4.organisation_id = ?' .$organisation_id));
              // ->group('t1.id');
             //  ->where(array('t1.requisition_forward_status' => 'Pending'));
              // ->group(array('t2.goods_requisition_id')); 
 		$select->order('t1.approval_date DESC');
            $stmt = $sql->prepareStatementForSqlObject($select);
        	$result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	 * 
	 * @param type $id
	 * 
	 * to find Staff details so that their names are displayed and list of applied Goods Requisition Forwarded 
	 */
	public function getStaffRequisitionForwardDetails($id) 
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from(array('t1' => 'requisition_forward_details'))
			   ->join(array('t2' => 'goods_requisition_details'),
			   		  't2.id = t1.goods_requisition_details_id', array('item_name_id', 'goods_requisition_id', 'requisition_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'requisition_remarks'))
		       ->join(array('t3' => 'goods_requisition'),
		       	      't3.id = t2.goods_requisition_id', array('employee_details_id'))
		       ->join(array('t4' => 'item_name'),
		       	       't4.id = t2.item_name_id', array('item_name', 'item_sub_category_id'))
		       ->join(array('t5' => 'item_sub_category'),
		       	       't5.id = t4.item_sub_category_id', array('sub_category_type'))
		       ->join(array('t6' => 'item_category'),
		       	       't6.id = t5.item_category_id', array('category_type'))
		       ->join(array('t7' => 'item_quantity_type'),
		       	       't7.id = t4.item_quantity_type_id', array('item_quantity_type'))
		       ->join(array('t8' => 'employee_details'),
		       	       't8.id = t3.employee_details_id', array('emp_id', 'first_name', 'middle_name', 'last_name', 'departments_id', 'departments_units_id'))
		       ->join(array('t9' => 'department_units'),
		       	       't9.id = t8.departments_units_id', array('unit_name', 'departments_id'))
		       ->join(array('t10' => 'departments'),
		       	       't10.id = t8.departments_id', array('department_name'))
			   ->where(array('t3.id = ? ' .$id, 't1.requisition_forward_status = ?' => 'Pending'));
			  // ->where(array('t1.requisition_status' => 'Pending'));
			   //->group('t1.goods_requisition_id');
		 $select->order('t2.approval_date DESC');
		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		return $resultSet->initialize($result);
		
	}


	/**
	* To get the list of requisition approved forwarded and view the details and update the approved requisition forwarded.
	**/
	public function findAllApprovedRequisitionForwarded($organisation_id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();

            $select->from(array('t1' => 'requisition_forward_details')) //base table
               ->join(array('t2' => 'goods_requisition_details'), // join table with alias
               		't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'requisition_remarks'))
           	   ->join(array('t4' => 'employee_details'),
                    't4.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
               ->join(array('t5' => 'item_name'),
           			 't5.id = t2.item_name_id', array('item_name'))
               ->where(array('t1.requisition_forward_status' => 'Approved', 't1.supply_order_no is NULL', 't1.supply_order_date is NULL', 't4.organisation_id = ?' => $organisation_id));
               //->where(array('t1.supply_order_no is NULL'));
              // ->group(array('t2.goods_requisition_id')); 

            $stmt = $sql->prepareStatementForSqlObject($select);
        	$result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {

            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $resultSet = new HydratingResultSet($this->hydrator, $this->goodsRequisitionPrototype);
                    return $resultSet->initialize($result); 
            }

            return array();
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function findApprovedRequisitionForwarded($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'requisition_forward_details'))
            	   ->join(array('t2' => 'goods_requisition_details'),
            			't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'approval_date', 'requisition_remarks', 'employee_details_id'))
            	   ->join(array('t3' => 'employee_details'),
            			't3.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id', 'departments_id', 'departments_units_id'))
                   ->join(array('t4' => 'department_units'),
                   	't4.id = t3.departments_units_id', array('unit_name', 'departments_id'))
                   ->join(array('t5' => 'departments'),
                   	't5.id = t4.departments_id', array('department_name'))
                   ->join(array('t6' => 'item_name'),
               		't6.id = t2.item_name_id', array('item_name', 'item_quantity_type_id'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function getIndvReqPendingDetails($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t2' => 'item_name'),
            			't2.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t3' => 'item_quantity_type'),
            			't3.id = t2.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t4' => 'item_sub_category'),
                   	't4.id = t2.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'item_category'),
                   	't5.id = t4.item_category_id', array('category_type'))
            	   ->where(array('t1.id = ? ' => $id));
	    $select->order('t1.approval_date DESC');
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function getIndvReqApprovedDetails($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t3' => 'item_name'),
            			't3.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t4' => 'item_quantity_type'),
            			't4.id = t3.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t5' => 'item_sub_category'),
                   	't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
                   	't6.id = t5.item_category_id', array('category_type'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function getIndvReqRejectedDetails($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t2' => 'item_name'),
            			't2.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t3' => 'item_quantity_type'),
            			't3.id = t2.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t4' => 'item_sub_category'),
                   	't4.id = t2.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'item_category'),
                   	't5.id = t4.item_category_id', array('category_type'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	/**
	* @param int/String $id
	* @return GoodsRequisition
	* @throws \InvalidArgumentException
	*/
	
	public function getIndvReqForwardedDetails($id)
	{
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'requisition_forward_details'))
            	   ->join(array('t2' => 'goods_requisition_details'),
            			't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'approval_date', 'requisition_remarks'))
            	   ->join(array('t3' => 'item_name'),
            			't3.id = t2.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t4' => 'item_quantity_type'),
            			't4.id = t3.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t5' => 'item_sub_category'),
                   	't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
                   	't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'employee_details'),
               		't7.id = t1.requisition_forwarded_by', array('first_name', 'middle_name', 'last_name', 'emp_id'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}

	public function getRequisitionPendingDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t3' => 'item_name'),
            			't3.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t4' => 'item_quantity_type'),
            			't4.id = t3.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t5' => 'item_sub_category'),
                   	't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
                   	't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'employee_details'),
               		't7.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t8' => 'departments'),
               		't8.id = t7.departments_id', array('department_name'))
                   ->join(array('t9' => 'department_units'),
               		't9.id = t7.departments_units_id', array('unit_name'))
            	   ->where(array('t1.id = ? ' => $id));
 	    $select->order('t1.approval_date DESC');
            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	public function getRequisitionApprovedDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t2' => 'item_name'),
            			't2.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t3' => 'item_quantity_type'),
            			't3.id = t2.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t4' => 'item_sub_category'),
                   	't4.id = t2.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t5' => 'item_category'),
                   	't5.id = t4.item_category_id', array('category_type'))
                   ->join(array('t6' => 'employee_details'),
               		't6.id = t1.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t7' => 'departments'),
               		't7.id = t6.departments_id', array('department_name'))
                   ->join(array('t8' => 'department_units'),
               		't8.id = t6.departments_units_id', array('unit_name'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	public function getRequisitionRejectedDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'goods_requisition_details'))
            	   ->join(array('t2' => 'goods_requisition'),
            			't2.id = t1.goods_requisition_id', array('employee_details_id' ))
            	   ->join(array('t3' => 'item_name'),
            			't3.id = t1.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t4' => 'item_quantity_type'),
            			't4.id = t3.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t5' => 'item_sub_category'),
                   	't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
                   	't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'employee_details'),
               		't7.id = t2.employee_details_id', array('first_name', 'middle_name', 'last_name', 'emp_id'))
                   ->join(array('t8' => 'departments'),
               		't8.id = t7.departments_id', array('department_name'))
                   ->join(array('t9' => 'department_units'),
               		't9.id = t7.departments_units_id', array('unit_name'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}


	public function getRequisitionForwardedDetails($id)
	{
		$sql = new Sql($this->dbAdapter);
            $select = $sql->select();
            $select->from(array('t1' => 'requisition_forward_details'))
            	   ->join(array('t2' => 'goods_requisition_details'),
            			't2.id = t1.goods_requisition_details_id', array('item_name_id', 'requisition_item_quantity', 'approved_item_quantity', 'item_specification', 'purpose', 'requisition_date', 'approval_date', 'requisition_remarks'))
            	   ->join(array('t3' => 'item_name'),
            			't3.id = t2.item_name_id', array('item_name','item_sub_category_id', 'item_quantity_type_id'))
            	   ->join(array('t4' => 'item_quantity_type'),
            			't4.id = t3.item_quantity_type_id', array('item_quantity_type'))
                   ->join(array('t5' => 'item_sub_category'),
                   	't5.id = t3.item_sub_category_id', array('sub_category_type'))
                   ->join(array('t6' => 'item_category'),
                   	't6.id = t5.item_category_id', array('category_type'))
                   ->join(array('t7' => 'employee_details'),
               		't7.id = t1.requisition_forwarded_by', array('first_name', 'middle_name', 'last_name', 'emp_id', 'departments_units_id'))
                   ->join(array('t8' => 'goods_requisition'),
               		't7.id = t8.employee_details_id', array('employee_details_id'))
                   ->join(array('t9' => 'department_units'),
               		't9.id = t7.departments_units_id', array('departments_id', 'unit_name'))
                   ->join(array('t10' => 'departments'),
               		't10.id = t9.departments_id', array('department_name'))
                   ->join(array('t11' => 'employee_details'),
               			't11.id = t2.employee_details_id', array('first_name' => 'first_name', 'm_name' => 'middle_name', 'l_name' => 'last_name', 'e_id' => 'emp_id'))
            	   ->where(array('t1.id = ? ' => $id));

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();


            if($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()){
                    return $this->hydrator->hydrate($result->current(), $this->goodsRequisitionPrototype);
            }

            throw new \InvalidArgumentException("Goods Requisition with given ID: ($id) not found");
	}
		
	
	/*
	*save Requisition Details 
	*/
	public function saveRequisitionDetails(GoodsRequisition $goodsRequisitionObject, $item_sub_category_id, $item_name_id)
	{
		$goodsRequisitionData = $this->hydrator->extract($goodsRequisitionObject);

		unset($goodsRequisitionData['id']);
        unset($goodsRequisitionData['approval_Date']);
		unset($goodsRequisitionData['item_Quantity_Stock']);
		unset($goodsRequisitionData['item_Sub_Category_Id']);
		unset($goodsRequisitionData['category_Type']);
		unset($goodsRequisitionData['sub_Category_Type']);
		unset($goodsRequisitionData['item_Name']);
		unset($goodsRequisitionData['item_Quantity_Type']);
		unset($goodsRequisitionData['first_Name']);
		unset($goodsRequisitionData['middle_Name']);
		unset($goodsRequisitionData['last_Name']);
		unset($goodsRequisitionData['department_Name']);
		unset($goodsRequisitionData['unit_Name']);
		unset($goodsRequisitionData['approved_Item_Quantity']);
		unset($goodsRequisitionData['emp_Id']);
		unset($goodsRequisitionData['goods_Requisition_Details_Id']);
		unset($goodsRequisitionData['requisition_Forward_Quantity']);
		unset($goodsRequisitionData['requisition_Forwarded_By']);
		unset($goodsRequisitionData['requisition_Forward_Status']);
		unset($goodsRequisitionData['requisition_Forward_Date']);
		unset($goodsRequisitionData['requisition_Forward_Remarks']);
		unset($goodsRequisitionData['forward_Approved_Quantity']);
		unset($goodsRequisitionData['forward_Approval_Date']);
		unset($goodsRequisitionData['approval_No']);
		unset($goodsRequisitionData['approval_Remarks']);
		unset($goodsRequisitionData['supply_Order_No']);
		unset($goodsRequisitionData['supply_Order_Remarks']);
		unset($goodsRequisitionData['updated_By']);

         //get the id of the item name
		$goodsRequisitionData['item_Name_Id'] = $item_name_id;

		if($goodsRequisitionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('goods_requisition_details');
			$action->set($goodsRequisitionData);
			$action->where(array('id = ?' => $goodsRequisitionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('goods_requisition_details');
			$action->values($goodsRequisitionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsRequisitionObject->setId($newId);
			}
			return $goodsRequisitionObject;
		}
		
		throw new \Exception("Database Error");

	}

    
    /*
	*save Requisition Approval Details 
	*/
	public function saveRequisitionApproval(GoodsRequisitionApproval $goodsRequisitionObject)
	{
		$goodsTransactionData = $this->hydrator->extract($goodsRequisitionObject);
		
		unset($goodsTransactionData['id']);

   
		$goodsRequisitionData['item_Name_Id'] = $goodsTransactionData['item_Name_Id'];
		$goodsRequisitionData['employee_Details_Id'] = $goodsTransactionData['employee_Details_Id'];
		$goodsRequisitionData['requisition_Item_Quantity'] = $goodsTransactionData['requisition_Item_Quantity'];
		$goodsRequisitionData['approved_Item_Quantity'] = $goodsTransactionData['approved_Item_Quantity'];
		$goodsRequisitionData['approved_Balance_Quantity'] = $goodsTransactionData['approved_Item_Quantity'];
		$goodsRequisitionData['item_Specification'] = $goodsTransactionData['item_Specification'];
		$goodsRequisitionData['purpose'] = $goodsTransactionData['purpose'];
		$goodsRequisitionData['requisition_Status'] = $goodsTransactionData['requisition_Status'];
		$goodsRequisitionData['requisition_Date'] = $goodsTransactionData['requisition_Date'];
		$goodsRequisitionData['approval_Date'] = $goodsTransactionData['approval_Date'];
		$goodsRequisitionData['requisition_Remarks'] = $goodsTransactionData['requisition_Remarks'];
		$goodsRequisitionData['approved_By'] = $goodsTransactionData['approved_By'];

		
			//ID present, so it is an update
			$action = new Update('goods_requisition_details');
			$action->set($goodsRequisitionData);
			$action->where(array('id = ?' => $goodsRequisitionObject->getId()));
			$sql = new Sql($this->dbAdapter);
			$stmt = $sql->prepareStatementForSqlObject($action);
			$result = $stmt->execute();     	
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				echo $goodsRequisitionObject->setId($newId);
			}
			return $goodsRequisitionObject;
		}
		
		throw new \Exception("Database Error");
	}


	public function approveGoodsRequisition($status, $previousStatus, $id, $employee_details_id)
	{
		$goodsRequisitionData['requisition_Status'] = $status;

		$requisitionItemQuantity = $this->getRequisitionItemQuantity($tableName = 'goods_requisition_details', $id);

		$goodsRequisitionData['approved_item_quantity'] = $requisitionItemQuantity;
		$goodsRequisitionData['approved_balance_quantity'] = $requisitionItemQuantity;
		$goodsRequisitionData['requisition_Remarks'] = "Your requisition has been approved";
		$goodsRequisitionData['approval_Date'] = date('Y-m-d');
		$goodsRequisitionData['approved_By'] = $employee_details_id;

        $action = new Update('goods_requisition_details');
        $action->set($goodsRequisitionData);
        if($previousStatus != NULL){
            $action->where(array('requisition_Status = ?' => $previousStatus));
        } elseif($id != NULL){
            $action->where(array('id = ?' => $id));
        }
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return;
	}



	public function getRequisitionItemQuantity($tableName, $id)
	{
		$sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(array('t1' => $tableName));
        $select->columns(array('requisition_item_quantity'));
        $select->where(array('t1.id = ?' => $id));        

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
       // $id = NULL;
        
        foreach($resultSet as $set)
        {
           $id = $set['requisition_item_quantity'];
        }
        return $id;
	}


	/**
	 * 
	 * @param type $requisitionForwardApproval
	 * 
	 * to save Requisition Forward Approval
	 */

    public function saveRequisitionForwardApproval(GoodsRequisitionForwardApproval $goodsRequisitionObject)
	{
		$goodsRequisitionData = $this->hydrator->extract($goodsRequisitionObject);
		unset($goodsRequisitionData['id']);

		unset($goodsRequisitionData['requisition_Date']);
		unset($goodsRequisitionData['item_Specification']);
		unset($goodsRequisitionData['requisition_Item_Quantity']);
		unset($goodsRequisitionData['approved_Item_Quantity']);
		unset($goodsRequisitionData['purpose']);
		unset($goodsRequisitionData['employee_Details_Id']);
		unset($goodsRequisitionData['item_Name_Id']);
		unset($goodsRequisitionData['item_Sub_Category_Id']);
		unset($goodsRequisitionData['approval_Date']);
		unset($goodsRequisitionData['requisition_Remarks']);
		unset($goodsRequisitionData['goods_Requisition_Id']);
		unset($goodsRequisitionData['item_Category_Type']);
		unset($goodsRequisitionData['sub_Category_Type']);
		unset($goodsRequisitionData['item_Name']);
		unset($goodsRequisitionData['item_Quantity_Type']);
		unset($goodsRequisitionData['emp_Id']);
		unset($goodsRequisitionData['first_Name']);
		unset($goodsRequisitionData['middle_Name']);
		unset($goodsRequisitionData['last_Name']);
		unset($goodsRequisitionData['department_Name']);
		unset($goodsRequisitionData['unit_Name']);

		
		if($goodsRequisitionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('requisition_forward_details');
			$action->set($goodsRequisitionData);
			$action->where(array('id = ?' => $goodsRequisitionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('requisition_forward_details');
			$action->values($goodsRequisitionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsRequisitionObject->setId($newId);
			}
			return $goodsRequisitionObject;
		}
		
		throw new \Exception("Database Error");
	}
	
	
	/**
	 * 
	 * @param type $GoodsRequisition
	 * 
	 * to Delete Goods Requisition
	 */

	public function deleteGoodsRequisition(GoodsRequisition $goodsRequisitionObject)
	{

		$action = new Delete('goods_requisition_details');
		$action->where(array('id = ?' => $goodsRequisitionObject->getId()));

		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();

		return (bool)$result->getAffectedRows();
	}

    
    /**
	 * 
	 * @param type $requisitionForwardApproval
	 * 
	 * to save the details of Approved Requisition Forwarded
	 */
	public function updateApprovedForwardedRequisition(GoodsRequisitionForwardApproval $goodsRequisitionObject)
	{
		$goodsRequisitionData = $this->hydrator->extract($goodsRequisitionObject);
		unset($goodsRequisitionData['id']);

		unset($goodsRequisitionData['requisition_Date']);
		unset($goodsRequisitionData['item_Specification']);
		unset($goodsRequisitionData['requisition_Item_Quantity']);
		unset($goodsRequisitionData['approved_Item_Quantity']);
		unset($goodsRequisitionData['purpose']);
		unset($goodsRequisitionData['employee_Details_Id']);
		unset($goodsRequisitionData['item_Name_Id']);
		unset($goodsRequisitionData['item_Sub_Category_Id']);
		unset($goodsRequisitionData['approval_Date']);
		unset($goodsRequisitionData['requisition_Remarks']);
		unset($goodsRequisitionData['goods_Requisition_Id']);
		unset($goodsRequisitionData['item_Category_Type']);
		unset($goodsRequisitionData['sub_Category_Type']);
		unset($goodsRequisitionData['item_Name']);
		unset($goodsRequisitionData['item_Quantity_Type']);
		unset($goodsRequisitionData['emp_Id']);
		unset($goodsRequisitionData['first_Name']);
		unset($goodsRequisitionData['middle_Name']);
		unset($goodsRequisitionData['last_Name']);
		unset($goodsRequisitionData['department_Name']);
		unset($goodsRequisitionData['unit_Name']);

		
		if($goodsRequisitionObject->getId()) {
			//ID present, so it is an update
			$action = new Update('requisition_forward_details');
			$action->set($goodsRequisitionData);
			$action->where(array('id = ?' => $goodsRequisitionObject->getId()));
		} else {
			//ID is not present, so its an insert
			$action = new Insert('requisition_forward_details');
			$action->values($goodsRequisitionData);
		}
		
		$sql = new Sql($this->dbAdapter);
		$stmt = $sql->prepareStatementForSqlObject($action);
		$result = $stmt->execute();
		
		if($result instanceof ResultInterface) {
			if($newId = $result->getGeneratedValue()){
				//when a value has been generated, set it on the object
				$goodsRequisitionObject->setId($newId);
			}
			return $goodsRequisitionObject;
		}
		
		throw new \Exception("Database Error");
	}


	/*
	* 
	*/
	
	public function getAjaxDataId($tableName, $code)
	{
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();

		$select->from(array('t1' => $tableName));
		$select->columns(array('id'));
		if($tableName == 'item_name'){
			$select->where(array('item_name = ?' => $code));
		}
		

		$stmt = $sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();
		
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$id = NULL;
		
		foreach($resultSet as $set)
		{
			$id = $set['id'];
		}
		return $id;
	}


	/**
	* @return array/GoodsRequisition()
	* The following function is for listing data for select/dropdown form
	* For e.g. Need to fill the Item Category, Item Sub Category, Item Name, Item Quantity Type field with Category, Sub Category, Item Name and Item Quantity Type Type from the database
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
		// e.g. 1=> Category 1, 2 => Category etc.
			
		$selectData = array();
		foreach($resultSet as $set)
		{
			$selectData[$set['id']] = $set[$columnName];
		}
		return $selectData;
			
	}        
    public function getEmailAddress($userrole, $activity)
	{
		$sql = new Sql($this->dbAdapter);
        	$select1 = $sql->select();

        	$select1->from(array('t1' => 'user_workflow'))
			  ->columns(array('auth'));

		$select1->where(array('t1.role=?' => $userrole, 't1.type=?' => $activity));
			
		$stmt1 = $sql->prepareStatementForSqlObject($select1);
		
		$result1 = $stmt1->execute();
              
		foreach( $result1 as $auth)
		{
			$auth = $auth['auth'];
		}
		
		return ($this->setEmailAddress($auth));
	 	
	}
	private function setEmailAddress($auth)
	{
		
		$sql = new Sql($this->dbAdapter);
        $select1 = $sql->select();
			
		$select1->from(array('t1' => 'users'))
               ->join(array('t2' => 'employee_details'),
                't2.emp_id = t1.username', array('email'));
        $select1->where(array('t1.role=?' => $auth));

        $stmt1 = $sql->prepareStatementForSqlObject($select1);
        $result1 = $stmt1->execute();
		
        foreach( $result1 as $row)
        {
                        $email = $row['email'];
		}
	
		return $email;
	 
	}	
}
